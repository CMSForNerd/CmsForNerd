"""Unit tests for .agents/skills/okf-frontmatter-injector/scripts/apply_okf.py.

These tests focus on the path-traversal safety guard (``is_safe_path``) added
to the OKF frontmatter injector and its integration into ``apply_okf``. Because
the script's directory contains a hyphen, it is loaded dynamically via
``importlib``, mirroring the pattern used elsewhere in this test suite.

Run with:
    python3 -m unittest tests.test_okf_frontmatter_injector -v
"""

import importlib.util
import io
import os
import tempfile
import unittest
from contextlib import redirect_stderr

REPO_ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
MODULE_PATH = os.path.join(
    REPO_ROOT, ".agents", "skills", "okf-frontmatter-injector", "scripts", "apply_okf.py"
)

_spec = importlib.util.spec_from_file_location("okf_apply_okf", MODULE_PATH)
apply_okf_module = importlib.util.module_from_spec(_spec)
_spec.loader.exec_module(apply_okf_module)


class OkfFrontmatterInjectorIsSafePathTest(unittest.TestCase):
    """Tests the is_safe_path helper used to guard apply_okf."""

    def test_relative_path_within_base_dir_is_safe(self):
        with tempfile.TemporaryDirectory() as tmp_dir:
            target = os.path.join(tmp_dir, "doc.md")
            self.assertTrue(apply_okf_module.is_safe_path(target, base_dir=tmp_dir))

    def test_base_dir_itself_is_safe(self):
        with tempfile.TemporaryDirectory() as tmp_dir:
            self.assertTrue(apply_okf_module.is_safe_path(tmp_dir, base_dir=tmp_dir))

    def test_parent_traversal_escaping_base_dir_is_unsafe(self):
        with tempfile.TemporaryDirectory() as tmp_dir:
            escaping = os.path.join(tmp_dir, "..", "escape.md")
            self.assertFalse(apply_okf_module.is_safe_path(escaping, base_dir=tmp_dir))

    def test_defaults_to_current_working_directory_when_base_dir_omitted(self):
        original_cwd = os.getcwd()
        with tempfile.TemporaryDirectory() as tmp_dir:
            try:
                os.chdir(tmp_dir)
                self.assertTrue(apply_okf_module.is_safe_path("inside_dir"))
                self.assertFalse(apply_okf_module.is_safe_path("../outside_dir"))
            finally:
                os.chdir(original_cwd)

    def test_symlink_escaping_base_dir_is_unsafe(self):
        """Tests that is_safe_path correctly rejects a symlinked directory escaping the base directory."""
        with tempfile.TemporaryDirectory() as tmp_dir:
            parent_dir = os.path.dirname(tmp_dir)
            outside_dir = os.path.join(parent_dir, "outside_secret_dir")
            os.makedirs(outside_dir, exist_ok=True)

            try:
                symlink_path = os.path.join(tmp_dir, "bad_link_dir")
                os.symlink(outside_dir, symlink_path)

                # is_safe_path should return False because realpath points outside tmp_dir
                self.assertFalse(apply_okf_module.is_safe_path(symlink_path, base_dir=tmp_dir))
            except (OSError, NotImplementedError, AttributeError):
                # Symlinks not supported/allowed in the test environment, skip gracefully
                pass
            finally:
                if os.path.isdir(outside_dir) and not os.path.islink(outside_dir):
                    os.rmdir(outside_dir)


class OkfFrontmatterInjectorApplyOkfTest(unittest.TestCase):
    """Tests the apply_okf entry point, including its safety gate."""

    def _chdir_tmp(self, tmp_dir):
        self._original_cwd = os.getcwd()
        os.chdir(tmp_dir)

    def _restore_cwd(self):
        os.chdir(self._original_cwd)

    def test_blocks_path_traversal_outside_the_working_directory(self):
        """Tests that apply_okf exits with status 1 for a root directory outside cwd."""
        with tempfile.TemporaryDirectory() as tmp_dir:
            self._chdir_tmp(tmp_dir)
            try:
                buf = io.StringIO()
                with redirect_stderr(buf):
                    with self.assertRaises(SystemExit) as raised:
                        apply_okf_module.apply_okf("../outside_dir")
                self.assertEqual(raised.exception.code, 1)
                self.assertIn("Path traversal blocked", buf.getvalue())
            finally:
                self._restore_cwd()

    def test_injects_new_frontmatter_into_a_safe_markdown_file(self):
        """Tests that a Markdown file without frontmatter receives a new OKF frontmatter block."""
        with tempfile.TemporaryDirectory() as tmp_dir:
            self._chdir_tmp(tmp_dir)
            try:
                filepath = os.path.join(tmp_dir, "guide.md")
                with open(filepath, "w", encoding="utf-8") as f:
                    f.write("# My Guide\n\nSome content here.\n")

                apply_okf_module.apply_okf(tmp_dir)

                with open(filepath, "r", encoding="utf-8") as f:
                    content = f.read()
                self.assertTrue(content.startswith("---\n"))
                self.assertIn('title: "My Guide"', content)
                self.assertIn("okf_version: 0.1", content)
                self.assertIn("# My Guide", content)
            finally:
                self._restore_cwd()

    def test_adds_missing_fields_to_existing_frontmatter(self):
        """Tests that a Markdown file with partial frontmatter has only the missing fields appended."""
        with tempfile.TemporaryDirectory() as tmp_dir:
            self._chdir_tmp(tmp_dir)
            try:
                filepath = os.path.join(tmp_dir, "partial.md")
                with open(filepath, "w", encoding="utf-8") as f:
                    f.write(
                        "---\n"
                        "okf_version: 0.1\n"
                        "type: documentation\n"
                        "---\n"
                        "# Partial\n\nBody text.\n"
                    )

                apply_okf_module.apply_okf(tmp_dir)

                with open(filepath, "r", encoding="utf-8") as f:
                    content = f.read()
                self.assertIn("title:", content)
                self.assertIn("timestamp:", content)
                self.assertIn("topics:", content)
                self.assertIn("type: documentation", content)
            finally:
                self._restore_cwd()

    def test_skips_files_within_ignored_directories(self):
        """Tests that Markdown files inside excluded directories (e.g. vendor) are left untouched."""
        with tempfile.TemporaryDirectory() as tmp_dir:
            self._chdir_tmp(tmp_dir)
            try:
                ignored_dir = os.path.join(tmp_dir, "vendor")
                os.makedirs(ignored_dir)
                ignored_file = os.path.join(ignored_dir, "third_party.md")
                original_content = "# Third Party\n\nDo not touch.\n"
                with open(ignored_file, "w", encoding="utf-8") as f:
                    f.write(original_content)

                apply_okf_module.apply_okf(tmp_dir)

                with open(ignored_file, "r", encoding="utf-8") as f:
                    content = f.read()
                self.assertEqual(content, original_content)
            finally:
                self._restore_cwd()


if __name__ == "__main__":
    unittest.main()