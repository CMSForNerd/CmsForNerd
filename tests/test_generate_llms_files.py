"""Unit tests for tools/generate-llms-files.py.

These tests validate parsing of llms.txt, XML context document generation,
and compilation of consolidated markdown documents (llms-full.txt).
"""

import importlib.util
import os
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

    def test_xml_escape_escaping_behavior(self):
        """Tests that special characters are correctly escaped for XML."""
        self.assertEqual(gen_llms.xml_escape("A & B < C > D \"E\" 'F'"), "A &amp; B &lt; C &gt; D &quot;E&quot; &apos;F&apos;")
        self.assertEqual(gen_llms.xml_escape(""), "")
        self.assertEqual(gen_llms.xml_escape(None), "")

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
        xml_out = gen_llms.generate_xml_context(parsed, base_dir="/workspace")
        self.assertIn("Mock File Content Here", xml_out)
        mock_file.assert_called_once_with(os.path.join("/workspace", "docs/local.md"), "r", encoding="utf-8")

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
        full_md = gen_llms.generate_llms_full_markdown(parsed, base_dir="/workspace")
        self.assertIn("# My Title - Full Consolidated Documentation", full_md)
        self.assertIn("### File: Doc A (`docs/doc-a.md`) - Doc A Desc", full_md)
        self.assertIn("Local file contents inside full.", full_md)


if __name__ == "__main__":
    unittest.main()
