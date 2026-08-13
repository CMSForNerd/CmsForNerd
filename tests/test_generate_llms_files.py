"""Unit tests for tools/generate-llms-files.py.

These tests validate parsing of llms.txt (including multi-line summaries),
XML context document generation, path traversal validation, and compilation
of consolidated markdown documents (llms-full.txt).
"""

import importlib.util
import os
import tempfile
import sys
import unittest
from unittest.mock import patch, mock_open

REPO_ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
MODULE_PATH = os.path.join(REPO_ROOT, "tools", "generate-llms-files.py")

# Dynamically import generate-llms-files.py (has hyphen in name)
_spec = importlib.util.spec_from_file_location("generate_llms_files", MODULE_PATH)
gen_llms = importlib.util.module_from_spec(_spec)
_spec.loader.exec_module(gen_llms)


class GenerateLlmsFilesTest(unittest.TestCase):
    """Test suite for the LLMs index generation utility script."""

    def test_parse_llms_txt_standard_format(self):
        """Tests that a standard compliant llms.txt string is correctly parsed."""
        sample_txt = (
            "# Test Project v1.0.0\n\n"
            "> This is a test project summary block.\n\n"
            "Some detailed initial info about the project.\n\n"
            "## Section One\n\n"
            "- [First Doc](docs/first.md): First document notes\n"
            "- [Second Doc](docs/second.md)\n"
            "- Plain list text note\n"
        )
        parsed = gen_llms.parse_llms_txt(sample_txt)

        self.assertEqual(parsed["title"], "Test Project v1.0.0")
        self.assertEqual(parsed["summary"], "This is a test project summary block.")
        self.assertEqual(parsed["info"], "Some detailed initial info about the project.")
        self.assertIn("Section One", parsed["sections"])

        sec_items = parsed["sections"]["Section One"]
        self.assertEqual(len(sec_items), 3)

        self.assertEqual(sec_items[0]["title"], "First Doc")
        self.assertEqual(sec_items[0]["url"], "docs/first.md")
        self.assertEqual(sec_items[0]["desc"], "First document notes")

        self.assertEqual(sec_items[1]["title"], "Second Doc")
        self.assertEqual(sec_items[1]["url"], "docs/second.md")
        self.assertIsNone(sec_items[1]["desc"])

        self.assertEqual(sec_items[2]["title"], "Plain list text note")
        self.assertIsNone(sec_items[2]["url"])
        self.assertIsNone(sec_items[2]["desc"])

    def test_parse_llms_txt_multiline_summary(self):
        """Tests that multi-line blockquote summaries are correctly collected and combined."""
        sample_txt = (
            "# Test Project v1.0.0\n\n"
            "> This is the first line of the summary.\n"
            "> This is the second line of the summary.\n"
            "> And a third line.\n\n"
            "Some detailed initial info about the project.\n\n"
            "## Section One\n\n"
            "- [First Doc](docs/first.md)\n"
        )
        parsed = gen_llms.parse_llms_txt(sample_txt)

        self.assertEqual(parsed["title"], "Test Project v1.0.0")
        self.assertEqual(
            parsed["summary"],
            "This is the first line of the summary. This is the second line of the summary. And a third line."
        )
        self.assertEqual(parsed["info"], "Some detailed initial info about the project.")

    def test_xml_escape_escaping_behavior(self):
        """Tests that special characters are correctly escaped for XML."""
        self.assertEqual(gen_llms.xml_escape("A & B < C > D \"E\" 'F'"), "A &amp; B &lt; C &gt; D &quot;E&quot; &apos;F&apos;")
        self.assertEqual(gen_llms.xml_escape(""), "")
        self.assertEqual(gen_llms.xml_escape(None), "")

    def test_resolve_safe_local_path_validation(self):
        """Tests resolve_safe_local_path with relative, absolute, and traversing paths."""
        with tempfile.TemporaryDirectory() as tmp_dir:
            # Create a mock file
            safe_file_name = "test.txt"
            safe_file_path = os.path.join(tmp_dir, safe_file_name)
            with open(safe_file_path, "w") as f:
                f.write("safe")

            # 1. Valid relative path should resolve to absolute path
            resolved = gen_llms.resolve_safe_local_path(safe_file_name, tmp_dir)
            self.assertIsNotNone(resolved)
            self.assertEqual(resolved, os.path.realpath(safe_file_path))

            # 2. Reject absolute path
            resolved_abs = gen_llms.resolve_safe_local_path(safe_file_path, tmp_dir)
            self.assertIsNone(resolved_abs)

            # 3. Reject relative traversal path escaping base_dir
            resolved_escape = gen_llms.resolve_safe_local_path("../escape.txt", tmp_dir)
            self.assertIsNone(resolved_escape)

            # 4. Reject absolute path with leading slash/backslash
            resolved_slash = gen_llms.resolve_safe_local_path("/etc/passwd", tmp_dir)
            self.assertIsNone(resolved_slash)

            resolved_backslash = gen_llms.resolve_safe_local_path("\\Windows\\System32", tmp_dir)
            self.assertIsNone(resolved_backslash)

            # 5. Reject URL schemes
            resolved_http = gen_llms.resolve_safe_local_path("http://example.com/test.txt", tmp_dir)
            self.assertIsNone(resolved_http)

    def test_is_safe_path_symlink_safety(self):
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
                self.assertFalse(gen_llms.is_safe_path(symlink_path, base_dir=tmp_dir))
            except (OSError, NotImplementedError, AttributeError):
                # Symlinks not supported/allowed in the test environment, skip gracefully
                pass
            finally:
                if os.path.exists(outside_file):
                    os.remove(outside_file)

    def test_pre_existing_symlink_safety(self):
        """Tests that pre-existing temporary symlinks are completely bypassed and not followed."""
        with tempfile.TemporaryDirectory() as tmp_dir:
            xml_out_path = os.path.join(tmp_dir, "llms.xml")
            symlink_path = xml_out_path + ".tmp"

            outside_file = os.path.join(os.path.dirname(tmp_dir), "outside_malicious.txt")
            with open(outside_file, "w") as f:
                f.write("unaffected original")

            try:
                os.symlink(outside_file, symlink_path)

                parsed = {
                    "title": "Title",
                    "summary": "Summary",
                    "info": "Info",
                    "sections": {}
                }

                xml_content = gen_llms.generate_xml_context(parsed)

                # Verify that the outside_file contents are NOT overwritten/affected!
                with open(outside_file, "r") as check_f:
                    outside_content = check_f.read()
                self.assertEqual(outside_content, "unaffected original")
            except (OSError, NotImplementedError, AttributeError):
                pass
            finally:
                if os.path.exists(outside_file):
                    os.remove(outside_file)

    def test_is_safe_path_default_base_dir_uses_cwd(self):
        """Tests that is_safe_path defaults to validating against the current working directory."""
        original_cwd = os.getcwd()
        with tempfile.TemporaryDirectory() as tmp_dir:
            try:
                os.chdir(tmp_dir)
                self.assertTrue(gen_llms.is_safe_path("safe_file.txt"))
                self.assertFalse(gen_llms.is_safe_path("../escape.txt"))
            finally:
                os.chdir(original_cwd)

    def test_is_safe_path_rejects_parent_traversal_with_explicit_base_dir(self):
        """Tests that a '..'-based relative path escaping an explicit base_dir is rejected."""
        with tempfile.TemporaryDirectory() as tmp_dir:
            nested = os.path.join(tmp_dir, "nested")
            os.makedirs(nested)
            escaping = os.path.join(nested, "..", "..", "outside.txt")
            self.assertFalse(gen_llms.is_safe_path(escaping, base_dir=tmp_dir))

    def test_is_safe_path_accepts_the_base_dir_itself(self):
        """Tests that the base directory path itself is considered safe."""
        with tempfile.TemporaryDirectory() as tmp_dir:
            self.assertTrue(gen_llms.is_safe_path(tmp_dir, base_dir=tmp_dir))

    def _run_main_in_tmp_dir(self, tmp_dir, argv):
        """Helper: runs gen_llms.main() with cwd and sys.argv set for the given tmp_dir/argv."""
        original_cwd = os.getcwd()
        os.chdir(tmp_dir)
        try:
            with patch.object(sys, "argv", ["generate-llms-files.py"] + argv):
                gen_llms.main()
        finally:
            os.chdir(original_cwd)

    def test_main_blocks_path_traversal_on_input_file(self):
        """Tests that main() exits with status 1 when the input path escapes the working directory."""
        with tempfile.TemporaryDirectory() as tmp_dir:
            with self.assertRaises(SystemExit) as raised:
                self._run_main_in_tmp_dir(tmp_dir, ["../outside.txt"])
            self.assertEqual(raised.exception.code, 1)

    def test_main_errors_when_input_file_is_missing(self):
        """Tests that main() exits with status 1 when the (safe) input file does not exist."""
        with tempfile.TemporaryDirectory() as tmp_dir:
            with self.assertRaises(SystemExit) as raised:
                self._run_main_in_tmp_dir(tmp_dir, ["missing.txt"])
            self.assertEqual(raised.exception.code, 1)

    def test_main_update_mode_writes_xml_and_full_outputs(self):
        """Tests that --update writes both llms.xml and llms-full.txt and exits with status 0."""
        with tempfile.TemporaryDirectory() as tmp_dir:
            llms_txt_path = os.path.join(tmp_dir, "llms.txt")
            with open(llms_txt_path, "w", encoding="utf-8") as f:
                f.write(
                    "# Sample Project\n\n"
                    "> Summary line.\n\n"
                    "Info body.\n\n"
                    "## Section\n\n"
                    "- Plain note\n"
                )

            with self.assertRaises(SystemExit) as raised:
                self._run_main_in_tmp_dir(tmp_dir, ["llms.txt", "--update"])
            self.assertEqual(raised.exception.code, 0)

            xml_path = os.path.join(tmp_dir, "llms.xml")
            full_path = os.path.join(tmp_dir, "llms-full.txt")
            self.assertTrue(os.path.exists(xml_path))
            self.assertTrue(os.path.exists(full_path))
            self.assertFalse(os.path.exists(xml_path + ".tmp"))
            self.assertFalse(os.path.exists(full_path + ".tmp"))

            with open(xml_path, encoding="utf-8") as f:
                xml_out = f.read()
            self.assertIn('<project title="Sample Project"', xml_out)

            with open(full_path, encoding="utf-8") as f:
                full_out = f.read()
            self.assertIn("# Sample Project - Full Consolidated Documentation", full_out)

    def test_main_blocks_path_traversal_on_xml_out(self):
        """Tests that main() exits with status 1 when --xml-out escapes the working directory."""
        with tempfile.TemporaryDirectory() as tmp_dir:
            with open(os.path.join(tmp_dir, "llms.txt"), "w", encoding="utf-8") as f:
                f.write("# Sample Project\n\n> Summary.\n\nInfo.\n")

            with self.assertRaises(SystemExit) as raised:
                self._run_main_in_tmp_dir(tmp_dir, ["llms.txt", "--xml-out", "../escape.xml"])
            self.assertEqual(raised.exception.code, 1)

    def test_main_blocks_path_traversal_on_full_out_in_update_mode(self):
        """Tests that main() exits with status 1 when --full-out escapes the working directory during --update."""
        with tempfile.TemporaryDirectory() as tmp_dir:
            with open(os.path.join(tmp_dir, "llms.txt"), "w", encoding="utf-8") as f:
                f.write("# Sample Project\n\n> Summary.\n\nInfo.\n")

            with self.assertRaises(SystemExit) as raised:
                self._run_main_in_tmp_dir(
                    tmp_dir, ["llms.txt", "--update", "--full-out", "../escape-full.txt"]
                )
            self.assertEqual(raised.exception.code, 1)

    def test_main_writes_xml_out_to_a_safe_relative_path(self):
        """Tests that main() writes the XML output file when the given path is safe."""
        with tempfile.TemporaryDirectory() as tmp_dir:
            with open(os.path.join(tmp_dir, "llms.txt"), "w", encoding="utf-8") as f:
                f.write("# Sample Project\n\n> Summary.\n\nInfo.\n")

            self._run_main_in_tmp_dir(tmp_dir, ["llms.txt", "--xml-out", "custom.xml"])

            custom_xml_path = os.path.join(tmp_dir, "custom.xml")
            self.assertTrue(os.path.exists(custom_xml_path))
            with open(custom_xml_path, encoding="utf-8") as f:
                self.assertIn('<project title="Sample Project"', f.read())

    def test_main_exits_one_when_xml_out_directory_does_not_exist(self):
        """Tests that main() exits with status 1 and cleans up when the XML output cannot be written."""
        with tempfile.TemporaryDirectory() as tmp_dir:
            with open(os.path.join(tmp_dir, "llms.txt"), "w", encoding="utf-8") as f:
                f.write("# Sample Project\n\n> Summary.\n\nInfo.\n")

            with self.assertRaises(SystemExit) as raised:
                self._run_main_in_tmp_dir(
                    tmp_dir, ["llms.txt", "--xml-out", "nonexistent_dir/out.xml"]
                )
            self.assertEqual(raised.exception.code, 1)
            self.assertFalse(
                os.path.exists(os.path.join(tmp_dir, "nonexistent_dir", "out.xml.tmp"))
            )

    def test_generate_xml_context_structure(self):
        """Tests that XML context content is generated with expected structure."""
        parsed = {
            "title": "Title & Co",
            "summary": "Summary & more",
            "info": "Info <tag>",
            "sections": {
                "S1": [
                    {"title": "Link 1", "url": "http://example.com", "desc": "Desc 1"},
                    {"title": "Note 1", "url": None, "desc": None}
                ]
            }
        }
        xml_out = gen_llms.generate_xml_context(parsed)
        self.assertIn('<project title="Title &amp; Co" summary="Summary &amp; more">', xml_out)
        self.assertIn('<info>Info &lt;tag&gt;</info>', xml_out)
        self.assertIn('<section title="S1">', xml_out)
        self.assertIn('<file name="Link 1" url="http://example.com" description="Desc 1">', xml_out)
        self.assertIn('<note>Note 1</note>', xml_out)

    @patch("builtins.open", new_callable=mock_open, read_data="Mock File Content Here")
    @patch("os.path.exists", return_value=True)
    @patch("os.path.isfile", return_value=True)
    def test_generate_xml_context_with_local_file_inclusion(self, mock_isfile, mock_exists, mock_file):
        """Tests that local files are read and embedded inside the XML context."""
        parsed = {
            "title": "Project",
            "summary": "Summary",
            "info": "",
            "sections": {
                "S1": [
                    {"title": "Local File", "url": "docs/local.md", "desc": "Local doc"}
                ]
            }
        }
        xml_out = gen_llms.generate_xml_context(parsed, base_dir=REPO_ROOT)
        self.assertIn("Mock File Content Here", xml_out)
        mock_file.assert_called_once_with(os.path.realpath(os.path.join(REPO_ROOT, "docs/local.md")), "r", encoding="utf-8")

    @patch("builtins.open", new_callable=mock_open, read_data="Local file contents inside full.")
    @patch("os.path.exists", return_value=True)
    @patch("os.path.isfile", return_value=True)
    def test_generate_llms_full_markdown(self, mock_isfile, mock_exists, mock_file):
        """Tests that full consolidated Markdown bundles all referenced files."""
        parsed = {
            "title": "My Title",
            "summary": "My Summary",
            "info": "My Info",
            "sections": {
                "S1": [
                    {"title": "Doc A", "url": "docs/doc-a.md", "desc": "Doc A Desc"}
                ]
            }
        }
        full_md = gen_llms.generate_llms_full_markdown(parsed, base_dir=REPO_ROOT)
        self.assertIn("# My Title - Full Consolidated Documentation", full_md)
        self.assertIn("### File: Doc A (`docs/doc-a.md`) - Doc A Desc", full_md)
        self.assertIn("Local file contents inside full.", full_md)


if __name__ == "__main__":
    unittest.main()
