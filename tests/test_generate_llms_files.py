"""Unit tests for tools/generate-llms-files.py.

These tests validate parsing of llms.txt, XML context document generation,
and compilation of consolidated markdown documents (llms-full.txt).
"""

import importlib.util
import os
import unittest
import io
import os
import shutil
import sys
import tempfile
import unittest
from contextlib import redirect_stderr, redirect_stdout
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


class IsSafePathTest(unittest.TestCase):
    """Tests for the path-traversal guard `is_safe_path` (SonarCloud S2083 hardening)."""

    def test_uses_cwd_as_base_dir_when_none_supplied(self):
        nested = os.path.join(os.getcwd(), "some", "nested.txt")
        self.assertTrue(gen_llms.is_safe_path(nested))

    def test_rejects_path_that_escapes_base_dir_via_parent_traversal(self):
        base_dir = tempfile.mkdtemp()
        try:
            traversal_path = os.path.join(base_dir, "..", "outside.txt")
            self.assertFalse(gen_llms.is_safe_path(traversal_path, base_dir))
        finally:
            shutil.rmtree(base_dir, ignore_errors=True)

    def test_allows_path_exactly_equal_to_base_dir(self):
        base_dir = tempfile.mkdtemp()
        try:
            self.assertTrue(gen_llms.is_safe_path(base_dir, base_dir))
        finally:
            shutil.rmtree(base_dir, ignore_errors=True)

    def test_allows_nested_path_within_custom_base_dir(self):
        base_dir = tempfile.mkdtemp()
        try:
            nested_path = os.path.join(base_dir, "docs", "file.md")
            self.assertTrue(gen_llms.is_safe_path(nested_path, base_dir))
        finally:
            shutil.rmtree(base_dir, ignore_errors=True)

    def test_rejects_sibling_directory_sharing_a_name_prefix(self):
        # Regression guard: a naive `startswith(base_dir)` check (without the
        # trailing os.sep) would incorrectly treat "/tmp/base2" as being
        # inside "/tmp/base". Verify the os.sep-qualified check rejects it.
        base_dir = tempfile.mkdtemp()
        try:
            sibling_path = base_dir + "-sibling" + os.path.sep + "file.txt"
            self.assertFalse(gen_llms.is_safe_path(sibling_path, base_dir))
        finally:
            shutil.rmtree(base_dir, ignore_errors=True)


class ParseLlmsTxtEdgeCasesTest(unittest.TestCase):
    """Additional edge-case coverage for `parse_llms_txt`."""

    def test_parse_empty_content_returns_safe_defaults(self):
        parsed = gen_llms.parse_llms_txt("")
        self.assertEqual(parsed["title"], "Untitled")
        self.assertEqual(parsed["summary"], "")
        self.assertEqual(parsed["info"], "")
        self.assertEqual(parsed["sections"], {})

    def test_parse_content_without_heading_defaults_title_to_untitled(self):
        parsed = gen_llms.parse_llms_txt("> Just a summary, no title heading.\n")
        self.assertEqual(parsed["title"], "Untitled")
        self.assertEqual(parsed["summary"], "Just a summary, no title heading.")

    def test_parse_multiple_sections_are_all_captured_independently(self):
        sample_txt = (
            "# Multi Section Doc\n\n"
            "## Alpha\n\n"
            "- [A1](a1.md)\n\n"
            "## Beta\n\n"
            "- [B1](b1.md)\n"
            "- [B2](b2.md)\n"
        )
        parsed = gen_llms.parse_llms_txt(sample_txt)
        self.assertEqual(list(parsed["sections"].keys()), ["Alpha", "Beta"])
        self.assertEqual(len(parsed["sections"]["Alpha"]), 1)
        self.assertEqual(len(parsed["sections"]["Beta"]), 2)

    def test_parse_link_with_plus_bullet_marker(self):
        parsed = gen_llms.parse_llms_txt("## Sec\n\n+ [Title](url.md): description\n")
        item = parsed["sections"]["Sec"][0]
        self.assertEqual(item["title"], "Title")
        self.assertEqual(item["url"], "url.md")
        self.assertEqual(item["desc"], "description")

    def test_parse_link_missing_closing_paren_falls_back_to_plain_text(self):
        parsed = gen_llms.parse_llms_txt("## Sec\n\n- [Broken](no-closing-paren\n")
        item = parsed["sections"]["Sec"][0]
        self.assertIsNone(item["url"])
        self.assertIsNone(item["desc"])
        self.assertEqual(item["title"], "[Broken](no-closing-paren")

    def test_parse_strips_leading_bullet_marker_from_each_plain_text_style(self):
        sample_txt = "## Sec\n\n* star item\n+ plus item\n- dash item\n"
        parsed = gen_llms.parse_llms_txt(sample_txt)
        titles = [item["title"] for item in parsed["sections"]["Sec"]]
        self.assertEqual(titles, ["star item", "plus item", "dash item"])
        for item in parsed["sections"]["Sec"]:
            self.assertIsNone(item["url"])
            self.assertIsNone(item["desc"])

    def test_parse_ignores_blank_lines_within_a_section(self):
        sample_txt = "## Sec\n\n- [A](a.md)\n\n\n- [B](b.md)\n"
        parsed = gen_llms.parse_llms_txt(sample_txt)
        self.assertEqual(len(parsed["sections"]["Sec"]), 2)


class XmlEscapeAdditionalTest(unittest.TestCase):
    """Additional edge-case coverage for `xml_escape`."""

    def test_xml_escape_leaves_plain_text_unaffected(self):
        self.assertEqual(gen_llms.xml_escape("Plain text, no specials."), "Plain text, no specials.")

    def test_xml_escape_escapes_ampersand_before_other_entities(self):
        # If '&' were escaped after '<'/'>' this would double-escape the
        # entities produced by escaping '<' and '>' themselves.
        self.assertEqual(gen_llms.xml_escape("&amp;"), "&amp;amp;")

    def test_xml_escape_handles_falsy_zero_like_empty_string(self):
        self.assertEqual(gen_llms.xml_escape(0), "")


class GenerateXmlContextAdditionalTest(unittest.TestCase):
    """Additional edge-case coverage for `generate_xml_context`."""

    def test_omits_local_file_reading_when_no_base_dir_given(self):
        parsed = {
            "title": "T",
            "summary": "S",
            "info": "",
            "sections": {"Sec": [{"title": "Loc", "url": "x.md", "desc": None}]},
        }
        xml_out = gen_llms.generate_xml_context(parsed, base_dir="")
        self.assertIn('<file name="Loc" url="x.md" description="">', xml_out)
        self.assertNotIn("not found on disk", xml_out)

    def test_skips_reading_https_urls_even_with_base_dir(self):
        parsed = {
            "title": "T",
            "summary": "S",
            "info": "",
            "sections": {"Sec": [{"title": "Ext", "url": "https://example.com/doc", "desc": None}]},
        }
        with patch("builtins.open") as mock_file:
            xml_out = gen_llms.generate_xml_context(parsed, base_dir="/workspace")
        mock_file.assert_not_called()
        self.assertIn('url="https://example.com/doc"', xml_out)

    def test_skips_reading_mailto_urls_even_with_base_dir(self):
        parsed = {
            "title": "T",
            "summary": "S",
            "info": "",
            "sections": {"Sec": [{"title": "Contact", "url": "mailto:someone@example.com", "desc": None}]},
        }
        with patch("builtins.open") as mock_file:
            xml_out = gen_llms.generate_xml_context(parsed, base_dir="/workspace")
        mock_file.assert_not_called()
        self.assertIn('url="mailto:someone@example.com"', xml_out)

    @patch("os.path.exists", return_value=False)
    def test_reports_missing_local_file_as_a_comment(self, _mock_exists):
        parsed = {
            "title": "T",
            "summary": "S",
            "info": "",
            "sections": {"Sec": [{"title": "Loc", "url": "missing.md", "desc": None}]},
        }
        xml_out = gen_llms.generate_xml_context(parsed, base_dir="/workspace")
        self.assertIn("<!-- Local file not found on disk or path traversal blocked -->", xml_out)

    def test_reports_path_traversal_attempt_as_a_comment(self):
        parsed = {
            "title": "T",
            "summary": "S",
            "info": "",
            "sections": {"Sec": [{"title": "Loc", "url": "../../etc/passwd", "desc": None}]},
        }
        xml_out = gen_llms.generate_xml_context(parsed, base_dir="/workspace/sub")
        self.assertIn("<!-- Local file not found on disk or path traversal blocked -->", xml_out)

    @patch("os.path.isfile", return_value=True)
    @patch("os.path.exists", return_value=True)
    @patch("builtins.open", side_effect=OSError("disk error"))
    def test_reports_file_read_errors_as_a_comment(self, _mock_open, _mock_exists, _mock_isfile):
        parsed = {
            "title": "T",
            "summary": "S",
            "info": "",
            "sections": {"Sec": [{"title": "Loc", "url": "broken.md", "desc": None}]},
        }
        xml_out = gen_llms.generate_xml_context(parsed, base_dir="/workspace")
        self.assertIn("<!-- Error reading file: disk error -->", xml_out)

    def test_info_tag_is_omitted_when_info_is_empty(self):
        parsed = {"title": "T", "summary": "S", "info": "", "sections": {}}
        xml_out = gen_llms.generate_xml_context(parsed)
        self.assertNotIn("<info>", xml_out)


class GenerateLlmsFullMarkdownAdditionalTest(unittest.TestCase):
    """Additional edge-case coverage for `generate_llms_full_markdown`."""

    def test_plain_text_items_are_rendered_as_markdown_bullets(self):
        parsed = {
            "title": "T",
            "summary": "S",
            "info": "",
            "sections": {"Sec": [{"title": "Just a note", "url": None, "desc": None}]},
        }
        full_md = gen_llms.generate_llms_full_markdown(parsed)
        self.assertIn("- Just a note", full_md)

    def test_external_urls_are_referenced_rather_than_embedded(self):
        parsed = {
            "title": "T",
            "summary": "S",
            "info": "",
            "sections": {"Sec": [{"title": "Ext", "url": "https://example.com/x", "desc": None}]},
        }
        full_md = gen_llms.generate_llms_full_markdown(parsed, base_dir="/workspace")
        self.assertIn("*External resource: available at https://example.com/x*", full_md)

    @patch("os.path.exists", return_value=False)
    def test_missing_local_file_produces_a_placeholder_message(self, _mock_exists):
        parsed = {
            "title": "T",
            "summary": "S",
            "info": "",
            "sections": {"Sec": [{"title": "Loc", "url": "missing.md", "desc": None}]},
        }
        full_md = gen_llms.generate_llms_full_markdown(parsed, base_dir="/workspace")
        self.assertIn("*Local file not found on disk or path traversal blocked.*", full_md)

    def test_local_url_without_base_dir_falls_back_to_external_reference(self):
        # Regression guard for the documented behaviour: local resolution is
        # only attempted when a base_dir is supplied.
        parsed = {
            "title": "T",
            "summary": "S",
            "info": "",
            "sections": {"Sec": [{"title": "Loc", "url": "x.md", "desc": None}]},
        }
        full_md = gen_llms.generate_llms_full_markdown(parsed, base_dir="")
        self.assertIn("*External resource: available at x.md*", full_md)

    @patch("os.path.isfile", return_value=True)
    @patch("os.path.exists", return_value=True)
    @patch("builtins.open", side_effect=OSError("disk error"))
    def test_file_read_errors_produce_an_inline_error_message(self, _mock_open, _mock_exists, _mock_isfile):
        parsed = {
            "title": "T",
            "summary": "S",
            "info": "",
            "sections": {"Sec": [{"title": "Loc", "url": "broken.md", "desc": None}]},
        }
        full_md = gen_llms.generate_llms_full_markdown(parsed, base_dir="/workspace")
        self.assertIn("*Error reading file content: disk error*", full_md)


class MainCliIntegrationTest(unittest.TestCase):
    """Integration tests exercising the argparse-driven `main()` entry point."""

    def setUp(self):
        self._orig_cwd = os.getcwd()
        self._tmpdir = tempfile.mkdtemp()
        os.chdir(self._tmpdir)

    def tearDown(self):
        os.chdir(self._orig_cwd)
        shutil.rmtree(self._tmpdir, ignore_errors=True)

    def _write_llms_txt(self, content=None):
        if content is None:
            content = (
                "# Demo\n\n"
                "> Demo summary\n\n"
                "## Docs\n\n"
                "- [Doc](doc.md): a doc\n"
            )
        with open("llms.txt", "w", encoding="utf-8") as f:
            f.write(content)

    def test_update_flag_generates_both_xml_and_full_markdown_files(self):
        self._write_llms_txt()
        with open("doc.md", "w", encoding="utf-8") as f:
            f.write("Doc body content.")

        with patch.object(sys, "argv", ["generate-llms-files.py", "llms.txt", "--update"]):
            stderr_buf = io.StringIO()
            with redirect_stderr(stderr_buf):
                with self.assertRaises(SystemExit) as raised:
                    gen_llms.main()

        self.assertEqual(raised.exception.code, 0)
        self.assertTrue(os.path.exists("llms.xml"))
        self.assertTrue(os.path.exists("llms-full.txt"))

        with open("llms.xml", encoding="utf-8") as f:
            xml_out = f.read()
        with open("llms-full.txt", encoding="utf-8") as f:
            full_out = f.read()

        self.assertIn('<project title="Demo"', xml_out)
        self.assertIn("Doc body content.", xml_out)
        self.assertIn("Doc body content.", full_out)
        self.assertIn("Generated XML context", stderr_buf.getvalue())
        self.assertIn("Generated full markdown", stderr_buf.getvalue())

    def test_missing_input_file_exits_with_status_one(self):
        with patch.object(sys, "argv", ["generate-llms-files.py", "does-not-exist.txt"]):
            stderr_buf = io.StringIO()
            with redirect_stderr(stderr_buf):
                with self.assertRaises(SystemExit) as raised:
                    gen_llms.main()
        self.assertEqual(raised.exception.code, 1)
        self.assertIn("does not exist", stderr_buf.getvalue())

    def test_path_traversal_on_input_argument_is_blocked(self):
        with patch.object(sys, "argv", ["generate-llms-files.py", "../outside.txt"]):
            stderr_buf = io.StringIO()
            with redirect_stderr(stderr_buf):
                with self.assertRaises(SystemExit) as raised:
                    gen_llms.main()
        self.assertEqual(raised.exception.code, 1)
        self.assertIn("Path traversal blocked", stderr_buf.getvalue())

    def test_default_invocation_prints_xml_context_to_stdout(self):
        self._write_llms_txt()
        with patch.object(sys, "argv", ["generate-llms-files.py", "llms.txt"]):
            stdout_buf = io.StringIO()
            with redirect_stdout(stdout_buf):
                gen_llms.main()
        self.assertIn('<project title="Demo"', stdout_buf.getvalue())

    def test_xml_out_argument_writes_the_context_to_the_given_file(self):
        self._write_llms_txt()
        with patch.object(sys, "argv", ["generate-llms-files.py", "llms.txt", "--xml-out", "context.xml"]):
            stderr_buf = io.StringIO()
            with redirect_stderr(stderr_buf):
                gen_llms.main()
        self.assertTrue(os.path.exists("context.xml"))
        self.assertIn("Generated XML context: context.xml", stderr_buf.getvalue())

    def test_path_traversal_on_xml_out_argument_is_blocked(self):
        self._write_llms_txt()
        with patch.object(sys, "argv", ["generate-llms-files.py", "llms.txt", "--xml-out", "../escape.xml"]):
            stderr_buf = io.StringIO()
            with redirect_stderr(stderr_buf):
                with self.assertRaises(SystemExit) as raised:
                    gen_llms.main()
        self.assertEqual(raised.exception.code, 1)
        self.assertIn("Path traversal blocked", stderr_buf.getvalue())

    def test_update_continues_and_exits_zero_even_if_full_markdown_write_fails(self):
        # Regression guard: a write failure for llms-full.txt during --update
        # is logged but must not abort the run with a non-zero exit code,
        # unlike the equivalent failure in the non-update, --xml-out path.
        self._write_llms_txt()
        real_open = open

        def flaky_open(path, mode="r", *args, **kwargs):
            if path == "llms-full.txt" and "w" in mode:
                raise OSError("disk full")
            return real_open(path, mode, *args, **kwargs)

        with patch.object(sys, "argv", ["generate-llms-files.py", "llms.txt", "--update"]):
            with patch("builtins.open", side_effect=flaky_open):
                stderr_buf = io.StringIO()
                with redirect_stderr(stderr_buf):
                    with self.assertRaises(SystemExit) as raised:
                        gen_llms.main()

        self.assertEqual(raised.exception.code, 0)
        self.assertIn("Error writing full markdown", stderr_buf.getvalue())
        self.assertTrue(os.path.exists("llms.xml"))


if __name__ == "__main__":
    unittest.main()
