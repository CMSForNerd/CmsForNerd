"""Unit tests for .agents/skills/dsom-token-calculator/scripts/calculate-tokens.py.

These tests focus on the path-traversal safety guard (``is_safe_path``) added
to the token calculator and its integration into ``scan_path``. The real
``tiktoken`` dependency is stubbed out when it is not installed so these
tests do not require network access or the ``tiktoken`` package to be
present in the test environment.

Run with:
    python3 -m unittest tests.test_dsom_token_calculator -v
"""

import importlib.util
import io
import os
import sys
import tempfile
import types
import unittest
from contextlib import redirect_stderr, redirect_stdout

REPO_ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
MODULE_PATH = os.path.join(
    REPO_ROOT, ".agents", "skills", "dsom-token-calculator", "scripts", "calculate-tokens.py"
)

try:
    import tiktoken  # noqa: F401
except ImportError:
    class _StubEncoding:
        """Minimal stand-in for a tiktoken encoding, used only when the real
        package is unavailable in the test environment."""

        def encode(self, text):
            return text.split()

    _stub_tiktoken = types.ModuleType("tiktoken")
    _stub_tiktoken.encoding_for_model = lambda model: _StubEncoding()
    _stub_tiktoken.get_encoding = lambda name: _StubEncoding()
    sys.modules["tiktoken"] = _stub_tiktoken

_spec = importlib.util.spec_from_file_location("dsom_calculate_tokens", MODULE_PATH)
calc_tokens = importlib.util.module_from_spec(_spec)
_spec.loader.exec_module(calc_tokens)


class DsomTokenCalculatorIsSafePathTest(unittest.TestCase):
    """Tests the is_safe_path helper used to guard scan_path."""

    def test_relative_path_within_base_dir_is_safe(self):
        with tempfile.TemporaryDirectory() as tmp_dir:
            target = os.path.join(tmp_dir, "file.md")
            self.assertTrue(calc_tokens.is_safe_path(target, base_dir=tmp_dir))

    def test_base_dir_itself_is_safe(self):
        with tempfile.TemporaryDirectory() as tmp_dir:
            self.assertTrue(calc_tokens.is_safe_path(tmp_dir, base_dir=tmp_dir))

    def test_parent_traversal_escaping_base_dir_is_unsafe(self):
        with tempfile.TemporaryDirectory() as tmp_dir:
            escaping = os.path.join(tmp_dir, "..", "escape.md")
            self.assertFalse(calc_tokens.is_safe_path(escaping, base_dir=tmp_dir))

    def test_defaults_to_current_working_directory_when_base_dir_omitted(self):
        original_cwd = os.getcwd()
        with tempfile.TemporaryDirectory() as tmp_dir:
            try:
                os.chdir(tmp_dir)
                self.assertTrue(calc_tokens.is_safe_path("inside.md"))
                self.assertFalse(calc_tokens.is_safe_path("../outside.md"))
            finally:
                os.chdir(original_cwd)

    def test_symlink_escaping_base_dir_is_unsafe(self):
        """Tests that is_safe_path correctly rejects symlinks escaping the base directory."""
        with tempfile.TemporaryDirectory() as tmp_dir:
            parent_dir = os.path.dirname(tmp_dir)
            outside_file = os.path.join(parent_dir, "outside_secret.txt")
            with open(outside_file, "w") as f:
                f.write("secret data")

            try:
                symlink_path = os.path.join(tmp_dir, "bad_link.txt")
                os.symlink(outside_file, symlink_path)

                # is_safe_path should return False because realpath points outside tmp_dir
                self.assertFalse(calc_tokens.is_safe_path(symlink_path, base_dir=tmp_dir))
            except (OSError, NotImplementedError, AttributeError):
                # Symlinks not supported/allowed in the test environment, skip gracefully
                pass
            finally:
                if os.path.exists(outside_file):
                    os.remove(outside_file)


class DsomTokenCalculatorScanPathTest(unittest.TestCase):
    """Tests the scan_path entry point, including its safety gate."""

    def _chdir_tmp(self, tmp_dir):
        self._original_cwd = os.getcwd()
        os.chdir(tmp_dir)

    def _restore_cwd(self):
        os.chdir(self._original_cwd)

    def test_blocks_path_traversal_outside_the_working_directory(self):
        """Tests that scan_path exits with status 1 for a target outside cwd."""
        with tempfile.TemporaryDirectory() as tmp_dir:
            self._chdir_tmp(tmp_dir)
            try:
                buf = io.StringIO()
                with redirect_stderr(buf):
                    with self.assertRaises(SystemExit) as raised:
                        calc_tokens.scan_path("../outside.md")
                self.assertEqual(raised.exception.code, 1)
                self.assertIn("Path traversal blocked", buf.getvalue())
            finally:
                self._restore_cwd()

    def test_scans_a_safe_file_and_reports_a_summary(self):
        """Tests that a safe, existing file is scanned and a summary is printed."""
        with tempfile.TemporaryDirectory() as tmp_dir:
            self._chdir_tmp(tmp_dir)
            try:
                filepath = "sample.md"
                with open(filepath, "w", encoding="utf-8") as f:
                    f.write("# Heading\n\nSome sample content for token counting.\n")

                buf = io.StringIO()
                with redirect_stdout(buf):
                    calc_tokens.scan_path(filepath)
                output = buf.getvalue()
                self.assertIn("[SUMMARY]", output)
                self.assertIn("Files: 1", output)
            finally:
                self._restore_cwd()

    def test_scans_a_safe_directory_and_counts_multiple_files(self):
        """Tests that scanning a safe directory aggregates token counts across allowed extensions."""
        with tempfile.TemporaryDirectory() as tmp_dir:
            self._chdir_tmp(tmp_dir)
            try:
                with open("a.md", "w", encoding="utf-8") as f:
                    f.write("Alpha content here.\n")
                with open("b.py", "w", encoding="utf-8") as f:
                    f.write("print('beta')\n")
                with open("c.ignore", "w", encoding="utf-8") as f:
                    f.write("should not be counted\n")

                buf = io.StringIO()
                with redirect_stdout(buf):
                    calc_tokens.scan_path(".")
                output = buf.getvalue()
                self.assertIn("Files: 2", output)
            finally:
                self._restore_cwd()

    def test_errors_when_the_safe_target_path_does_not_exist(self):
        """Tests that a safe but non-existent path still results in a controlled exit(1)."""
        with tempfile.TemporaryDirectory() as tmp_dir:
            self._chdir_tmp(tmp_dir)
            try:
                buf = io.StringIO()
                with redirect_stdout(buf):
                    with self.assertRaises(SystemExit) as raised:
                        calc_tokens.scan_path("missing.md")
                self.assertEqual(raised.exception.code, 1)
                self.assertIn("does not exist", buf.getvalue())
            finally:
                self._restore_cwd()


if __name__ == "__main__":
    unittest.main()