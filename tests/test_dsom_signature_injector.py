"""Unit tests for .agents/skills/dsom-signature-injector/scripts/inject.py.

These tests cover the path-traversal safety guard (``is_safe_path``) added to
the DSOM signature injector, and confirm that ``inject_signature`` still
performs its Markdown/shell/YAML/PowerShell signature injection correctly for
paths that remain within the current working directory. Because the script
file name is not a valid Python module name in this layout, it is loaded
dynamically via ``importlib``, mirroring the pattern used by
``tests/test_generate_llms_files.py`` and ``tests/test_validate_inventory.py``.

Run with:
    python3 -m unittest tests.test_dsom_signature_injector -v
"""

import importlib.util
import io
import os
import tempfile
import unittest
from contextlib import redirect_stderr

REPO_ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
MODULE_PATH = os.path.join(
    REPO_ROOT, ".agents", "skills", "dsom-signature-injector", "scripts", "inject.py"
)

_spec = importlib.util.spec_from_file_location("dsom_inject", MODULE_PATH)
dsom_inject = importlib.util.module_from_spec(_spec)
_spec.loader.exec_module(dsom_inject)


class DsomSignatureInjectorIsSafePathTest(unittest.TestCase):
    """Tests the is_safe_path helper used to guard inject_signature."""

    def test_relative_path_within_base_dir_is_safe(self):
        with tempfile.TemporaryDirectory() as tmp_dir:
            target = os.path.join(tmp_dir, "notes.md")
            self.assertTrue(dsom_inject.is_safe_path(target, base_dir=tmp_dir))

    def test_base_dir_itself_is_safe(self):
        with tempfile.TemporaryDirectory() as tmp_dir:
            self.assertTrue(dsom_inject.is_safe_path(tmp_dir, base_dir=tmp_dir))

    def test_parent_traversal_escaping_base_dir_is_unsafe(self):
        with tempfile.TemporaryDirectory() as tmp_dir:
            escaping = os.path.join(tmp_dir, "..", "escape.md")
            self.assertFalse(dsom_inject.is_safe_path(escaping, base_dir=tmp_dir))

    def test_defaults_to_current_working_directory_when_base_dir_omitted(self):
        original_cwd = os.getcwd()
        with tempfile.TemporaryDirectory() as tmp_dir:
            try:
                os.chdir(tmp_dir)
                self.assertTrue(dsom_inject.is_safe_path("inside.md"))
                self.assertFalse(dsom_inject.is_safe_path("../outside.md"))
            finally:
                os.chdir(original_cwd)

    def test_symlink_escaping_base_dir_is_unsafe(self):
        """Tests that is_safe_path correctly rejects symlinks escaping the base directory."""
        with tempfile.TemporaryDirectory() as tmp_dir:
            parent_dir = os.path.dirname(tmp_dir)
            with tempfile.NamedTemporaryFile(dir=parent_dir, delete=False) as outside_f:
                outside_file = outside_f.name
                outside_f.write(b"secret data")

            try:
                symlink_path = os.path.join(tmp_dir, "bad_link.txt")
                os.symlink(outside_file, symlink_path)

                # is_safe_path should return False because realpath points outside tmp_dir
                self.assertFalse(dsom_inject.is_safe_path(symlink_path, base_dir=tmp_dir))
            except (OSError, NotImplementedError, AttributeError):
                # Symlinks not supported/allowed in the test environment, skip gracefully
                pass
            finally:
                if os.path.exists(outside_file):
                    os.remove(outside_file)


class DsomSignatureInjectorInjectSignatureTest(unittest.TestCase):
    """Tests the inject_signature entry point, including its safety gate."""

    def _chdir_tmp(self, tmp_dir):
        self._original_cwd = os.getcwd()
        os.chdir(tmp_dir)

    def _restore_cwd(self):
        os.chdir(self._original_cwd)

    def test_blocks_path_traversal_outside_the_working_directory(self):
        """Tests that inject_signature exits with status 1 for a target outside cwd."""
        with tempfile.TemporaryDirectory() as tmp_dir:
            self._chdir_tmp(tmp_dir)
            try:
                buf = io.StringIO()
                with redirect_stderr(buf):
                    with self.assertRaises(SystemExit) as raised:
                        dsom_inject.inject_signature("../outside.md")
                self.assertEqual(raised.exception.code, 1)
                self.assertIn("Path traversal blocked", buf.getvalue())
            finally:
                self._restore_cwd()

    def test_appends_markdown_footer_to_a_safe_file(self):
        """Tests that a Markdown file within cwd receives the DSOM footer."""
        with tempfile.TemporaryDirectory() as tmp_dir:
            self._chdir_tmp(tmp_dir)
            try:
                filepath = "notes.md"
                with open(filepath, "w", encoding="utf-8") as f:
                    f.write("# Notes\n\nSome content.\n")

                dsom_inject.inject_signature(filepath)

                with open(filepath, "r", encoding="utf-8") as f:
                    content = f.read()
                self.assertIn("Deep State of Mind (DSOM) For My AI Protocol", content)
                self.assertIn("Harisfazillah Jamel (LinuxMalaysia)", content)
                self.assertTrue(content.startswith("# Notes"))
            finally:
                self._restore_cwd()

    def test_skips_a_file_that_already_contains_the_signature(self):
        """Tests that a previously signed file is left untouched (no duplicate signature)."""
        with tempfile.TemporaryDirectory() as tmp_dir:
            self._chdir_tmp(tmp_dir)
            try:
                filepath = "already_signed.md"
                original_content = (
                    "# Signed\n\n---\n"
                    "*Deep State of Mind (DSOM) For My AI Protocol | "
                    "Harisfazillah Jamel (LinuxMalaysia) | 2026-01-01*\n"
                )
                with open(filepath, "w", encoding="utf-8") as f:
                    f.write(original_content)

                dsom_inject.inject_signature(filepath)

                with open(filepath, "r", encoding="utf-8") as f:
                    content = f.read()
                self.assertEqual(content, original_content)
            finally:
                self._restore_cwd()

    def test_prepends_header_to_a_safe_shell_script(self):
        """Tests that a shell script within cwd receives a prepended DSOM header, preserving the shebang."""
        with tempfile.TemporaryDirectory() as tmp_dir:
            self._chdir_tmp(tmp_dir)
            try:
                filepath = "deploy.sh"
                with open(filepath, "w", encoding="utf-8") as f:
                    f.write("#!/bin/bash\necho hello\n")

                dsom_inject.inject_signature(filepath)

                with open(filepath, "r", encoding="utf-8") as f:
                    content = f.read()
                self.assertTrue(content.startswith("#!/bin/bash\n"))
                self.assertIn("Protocol    : Deep State of Mind (DSOM) For My AI", content)
                self.assertIn("Author      : Harisfazillah Jamel (LinuxMalaysia)", content)
                self.assertIn("echo hello", content)
            finally:
                self._restore_cwd()

    def test_processes_a_safe_directory_recursively(self):
        """Tests that a directory target is walked and eligible files receive signatures."""
        with tempfile.TemporaryDirectory() as tmp_dir:
            self._chdir_tmp(tmp_dir)
            try:
                sub_dir = os.path.join(".", "docs")
                os.makedirs(sub_dir)
                md_path = os.path.join(sub_dir, "readme.md")
                with open(md_path, "w", encoding="utf-8") as f:
                    f.write("# Readme\n")

                dsom_inject.inject_signature(".")

                with open(md_path, "r", encoding="utf-8") as f:
                    content = f.read()
                self.assertIn("Deep State of Mind (DSOM) For My AI Protocol", content)
            finally:
                self._restore_cwd()


if __name__ == "__main__":
    unittest.main()