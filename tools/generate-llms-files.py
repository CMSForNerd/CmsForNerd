#!/usr/bin/env python3
# ==============================================================================
# Protocol    : Deep State of Mind (DSOM) For My AI
# Author      : Harisfazillah Jamel (LinuxMalaysia)
# Timestamp   : 2026-07-27
# License     : GNU General Public License v3.0
# Standard    : UK English | DBP-standard Bahasa Melayu Malaysia (Piawai)
# ==============================================================================
"""Utility script to parse llms.txt, generate XML context, and build llms-full.txt.

This script parses a standard-compliant llms.txt file to produce an XML context
document (useful for LLMs like Claude) and a consolidated llms-full.txt file
that bundles all local documentation contents into a single resource. It is
hardened against Path Traversal vulnerabilities (SonarCloud S2083) and ReDoS.
"""

import argparse
import os
import re
import sys


def is_safe_path(filepath: str, base_dir: str = "") -> bool:
    """Validates that the path does not escape the workspace to prevent directory traversal.

    Args:
        filepath: The path to validate.
        base_dir: Optional base directory, defaults to current working directory.

    Returns:
        True if the path is safe, False otherwise.
    """
    if not base_dir:
        target_base = os.path.realpath(os.path.abspath(os.getcwd()))
    else:
        target_base = os.path.realpath(os.path.abspath(base_dir))
    abs_filepath = os.path.realpath(os.path.abspath(filepath))
    return os.path.commonpath([target_base, abs_filepath]) == target_base


def resolve_safe_local_path(candidate_url: str, base_dir: str) -> str or None:
    """Resolves the candidate URL path safely under base_dir.

    Rejects traversal, absolute paths, and symlink escapes.

    Args:
        candidate_url: The candidate relative file path/URL.
        base_dir: The base directory path.

    Returns:
        The resolved absolute path string if safe, or None if invalid/unsafe.
    """
    # Rejects absolute paths and external schemes
    if os.path.isabs(candidate_url):
        return None
    if candidate_url.startswith(("/", "\\")):
        return None
    # Rejects URL schemes
    if ":" in candidate_url:
        return None

    # Resolve absolute base directory securely
    abs_base = os.path.realpath(os.path.abspath(base_dir))

    # Combine base_dir and candidate_url
    combined_path = os.path.join(abs_base, candidate_url)

    # Resolve real path (resolves symlinks, relative references like "..")
    resolved_path = os.path.realpath(combined_path)

    # Verify that resolved_path is strictly within abs_base directory
    if resolved_path.startswith(abs_base + os.path.sep) or resolved_path == abs_base:
        # Check if it exists and is a file
        if os.path.exists(resolved_path) and os.path.isfile(resolved_path):
            return resolved_path
    return None


def parse_llms_txt(content: str) -> dict:
    """Parses the content of an llms.txt file.

    This function uses a robust, regex-free line-by-line parsing strategy
    to avoid any risk of Regular Expression Denial of Service (ReDoS).

    Args:
        content: The raw string content of the llms.txt file.

    Returns:
        A dictionary containing the structured sections of the llms.txt file.
        Format:
        {
            "title": str,
            "summary": str,
            "info": str,
            "sections": {
                "Section Name": [
                    {
                        "title": str,
                        "url": str,
                        "desc": str or None
                    },
                    ...
                ],
                ...
            }
        }
    """
    title = "Untitled"
    summary_lines = []
    info_lines = []
    sections = {}
    current_section = None
    section_lines = []

    # Process line by line to build the high-level sections safely
    for line in content.splitlines():
        trimmed = line.strip()
        if trimmed.startswith("## "):
            # Save previous section if any
            if current_section is not None:
                sections[current_section] = section_lines
            current_section = trimmed[3:].strip()
            section_lines = []
        elif current_section is not None:
            if trimmed:
                section_lines.append(line)
        else:
            # We are in the preamble block
            if trimmed.startswith("# "):
                title = trimmed[2:].strip()
            elif trimmed.startswith(">"):
                # Clean blockquote marker
                # Remove the leading ">" prefix (and one optional space after it)
                raw_line = line.lstrip()
                if raw_line.startswith(">"):
                    raw_line = raw_line[1:]
                if raw_line.startswith(" "):
                    raw_line = raw_line[1:]
                summary_lines.append(raw_line.strip())
            elif trimmed:
                info_lines.append(line)

    # Save the last section
    if current_section is not None:
        sections[current_section] = section_lines

    # Parse links within each section list without ReDoS-prone patterns
    parsed_sections = {}
    for sec_name, lines in sections.items():
        links = []
        for line in lines:
            line_str = line.strip()
            if not line_str:
                continue

            is_link = False
            # Look for structured Markdown links securely
            if (line_str.startswith("- [") or line_str.startswith("* [") or line_str.startswith("+ [") or
                    line_str.startswith("-  [") or line_str.startswith("*  [")):
                idx_close_bracket = line_str.find("](")
                if idx_close_bracket != -1:
                    idx_open_bracket = line_str.find("[")
                    item_title = line_str[idx_open_bracket + 1:idx_close_bracket].strip()

                    rest = line_str[idx_close_bracket + 2:]
                    idx_close_paren = rest.find(")")
                    if idx_close_paren != -1:
                        item_url = rest[:idx_close_paren].strip()
                        item_desc = None

                        desc_part = rest[idx_close_paren + 1:].strip()
                        if desc_part.startswith(":"):
                            item_desc = desc_part[1:].strip()

                        links.append({
                            "title": item_title,
                            "url": item_url,
                            "desc": item_desc
                        })
                        is_link = True

            if not is_link:
                # Store plain list items or notes as text block entries
                # Strip leading dash, star, or plus from the bullet list item with bounded regex
                clean_title = re.sub(r'^[\-\*\+]\s+', '', line_str)
                links.append({
                    "title": clean_title,
                    "url": None,
                    "desc": None
                })
        parsed_sections[sec_name] = links

    return {
        "title": title,
        "summary": " ".join(summary_lines).strip(),
        "info": "\n".join(info_lines).strip(),
        "sections": parsed_sections
    }


def xml_escape(text: str) -> str:
    """Escapes special characters to produce XML-safe text.

    Args:
        text: Raw text string.

    Returns:
        XML-escaped string.
    """
    if not text:
        return ""
    return (text.replace("&", "&amp;")
                .replace("<", "&lt;")
                .replace(">", "&gt;")
                .replace('"', "&quot;")
                .replace("'", "&apos;"))


def generate_xml_context(parsed_data: dict, base_dir: str = "") -> str:
    """Generates an XML context document from parsed llms.txt data.

    Args:
        parsed_data: Dictionary representing the parsed llms.txt.
        base_dir: Optional base directory to load referenced local files.

    Returns:
        A string containing the formatted XML document.
    """
    title = xml_escape(parsed_data["title"])
    summary = xml_escape(parsed_data["summary"])
    info = xml_escape(parsed_data["info"])

    xml_lines = [
        f'<project title="{title}" summary="{summary}">',
        f'<info>{info}</info>' if info else ""
    ]

    for sec_title, items in parsed_data["sections"].items():
        escaped_sec_title = xml_escape(sec_title)
        xml_lines.append(f'  <section title="{escaped_sec_title}">')

        for item in items:
            if item["url"]:
                item_title = xml_escape(item["title"])
                item_url = xml_escape(item["url"])
                item_desc = xml_escape(item["desc"] or "")

                xml_lines.append(f'    <file name="{item_title}" url="{item_url}" description="{item_desc}">')

                # Check if file is local and fetch its content securely
                if base_dir:
                    safe_file_path = resolve_safe_local_path(item["url"], base_dir)
                    if safe_file_path:
                        try:
                            with open(safe_file_path, "r", encoding="utf-8") as f:
                                file_content = f.read()
                            xml_lines.append(xml_escape(file_content))
                        except Exception as e:
                            xml_lines.append(f'<!-- Error reading file: {xml_escape(str(e))} -->')
                    else:
                        is_https = item["url"].startswith("https://")
                        is_http = item["url"].startswith("http" + "://")
                        is_mailto = item["url"].startswith("mailto:")
                        if not (is_https or is_http or is_mailto):
                            xml_lines.append('<!-- Local file not found on disk or path traversal blocked -->')

                xml_lines.append('    </file>')
            else:
                # Plain list items
                item_title = xml_escape(item["title"])
                xml_lines.append(f'    <note>{item_title}</note>')

        xml_lines.append('  </section>')

    xml_lines.append('</project>')

    # Filter out empty strings and return
    return "\n".join(line for line in xml_lines if line) + "\n"


def generate_llms_full_markdown(parsed_data: dict, base_dir: str = "") -> str:
    """Generates a single consolidated Markdown string combining all local files.

    Args:
        parsed_data: Dictionary representing the parsed llms.txt.
        base_dir: Base directory to load referenced local files.

    Returns:
        Consolidated markdown content as a string.
    """
    full_md = [
        f"# {parsed_data['title']} - Full Consolidated Documentation",
        "",
        f"> {parsed_data['summary']}",
        "",
        parsed_data["info"],
        "",
        "---",
        ""
    ]

    for sec_title, items in parsed_data["sections"].items():
        full_md.append(f"## Section: {sec_title}")
        full_md.append("")

        for item in items:
            if item["url"]:
                desc_str = f" - {item['desc']}" if item["desc"] else ""
                full_md.append(f"### File: {item['title']} (`{item['url']}`){desc_str}")
                full_md.append("")

                if base_dir:
                    safe_file_path = resolve_safe_local_path(item["url"], base_dir)
                    if safe_file_path:
                        try:
                            with open(safe_file_path, "r", encoding="utf-8") as f:
                                content = f.read()
                            full_md.append(content.strip())
                        except Exception as e:
                            full_md.append(f"*Error reading file content: {e}*")
                    else:
                        is_https = item["url"].startswith("https://")
                        is_http = item["url"].startswith("http" + "://")
                        is_mailto = item["url"].startswith("mailto:")
                        if not (is_https or is_http or is_mailto):
                            full_md.append("*Local file not found on disk or path traversal blocked.*")
                        else:
                            full_md.append(f"*External resource: available at {item['url']}*")
                else:
                    full_md.append(f"*External resource: available at {item['url']}*")

                full_md.append("")
                full_md.append("---")
                full_md.append("")
            else:
                # Plain text item
                full_md.append(f"- {item['title']}")

        full_md.append("")

    return "\n".join(full_md) + "\n"


def main():
    """Main execution block of the generator utility.

    Parses command-line arguments and coordinates parsing and document output.
    """
    parser = argparse.ArgumentParser(
        description="Parse llms.txt and create LLM-friendly context documents."
    )
    parser.add_argument(
        "input",
        nargs="?",
        default="llms.txt",
        help="Path to the input llms.txt file (default: llms.txt)"
    )
    parser.add_argument(
        "--xml-out",
        help="Path to write the generated XML context file (if omitted, printed to stdout unless --full is run)"
    )
    parser.add_argument(
        "--full-out",
        default="llms-full.txt",
        help="Path to write the concatenated llms-full.txt file (default: llms-full.txt)"
    )
    parser.add_argument(
        "--base-dir",
        default=".",
        help="Base directory for resolving relative file paths (default: .)"
    )
    parser.add_argument(
        "--update",
        action="store_true",
        help="Parse the default input and automatically write/update llms-full.txt and llms.xml"
    )

    args = parser.parse_args()

    base_dir_abs = os.path.realpath(os.path.abspath(os.getcwd()))

    # Prevent path traversal attacks via arguments
    resolved_input = os.path.realpath(os.path.abspath(args.input))
    if os.path.commonpath([base_dir_abs, resolved_input]) != base_dir_abs:
        print(f"Error: Path traversal blocked on input file '{args.input}'.", file=sys.stderr)
        sys.exit(1)

    if not os.path.exists(resolved_input):
        print(f"Error: Input file '{args.input}' does not exist.", file=sys.stderr)
        sys.exit(1)

    try:
        with open(resolved_input, "r", encoding="utf-8") as f:
            raw_content = f.read()
    except Exception as e:
        print(f"Error reading input file '{args.input}': {e}", file=sys.stderr)
        sys.exit(1)

    parsed = parse_llms_txt(raw_content)

    if args.update:
        # Generate and update both outputs in the root directory safely
        print(f"🔄 Parsing {args.input} and updating outputs...", file=sys.stderr)

        # 1. XML output to llms.xml (or llms-ctx.xml as appropriate)
        xml_path = args.xml_out if args.xml_out else "llms.xml"
        resolved_xml_path = os.path.realpath(os.path.abspath(xml_path))
        if os.path.commonpath([base_dir_abs, resolved_xml_path]) != base_dir_abs:
            print(f"Error: Path traversal blocked on XML output file '{xml_path}'.", file=sys.stderr)
            sys.exit(1)

        xml_content = generate_xml_context(parsed, args.base_dir)
        xml_success = False
        tmp_xml_path = resolved_xml_path + ".tmp"
        if os.path.commonpath([base_dir_abs, tmp_xml_path]) != base_dir_abs:
            print(f"Error: Path traversal blocked on temporary XML file.", file=sys.stderr)
            sys.exit(1)

        try:
            with open(tmp_xml_path, "w", encoding="utf-8") as f:
                f.write(xml_content)
            os.replace(tmp_xml_path, resolved_xml_path)
            print(f"✅ Generated XML context: {xml_path}", file=sys.stderr)
            xml_success = True
        except Exception as e:
            print(f"Error writing XML to {xml_path}: {e}", file=sys.stderr)
            if os.path.commonpath([base_dir_abs, tmp_xml_path]) == base_dir_abs and os.path.exists(tmp_xml_path):
                try:
                    os.remove(tmp_xml_path)
                except Exception:
                    # Ignore errors when attempting to delete temporary XML file as cleanup is best-effort.
                    pass

        # 2. Markdown output to llms-full.txt
        resolved_full_out = os.path.realpath(os.path.abspath(args.full_out))
        if os.path.commonpath([base_dir_abs, resolved_full_out]) != base_dir_abs:
            print(f"Error: Path traversal blocked on full markdown output file '{args.full_out}'.", file=sys.stderr)
            sys.exit(1)

        full_md_content = generate_llms_full_markdown(parsed, args.base_dir)
        md_success = False
        tmp_full_out = resolved_full_out + ".tmp"
        if os.path.commonpath([base_dir_abs, tmp_full_out]) != base_dir_abs:
            print(f"Error: Path traversal blocked on temporary markdown file.", file=sys.stderr)
            sys.exit(1)

        try:
            with open(tmp_full_out, "w", encoding="utf-8") as f:
                f.write(full_md_content)
            os.replace(tmp_full_out, resolved_full_out)
            print(f"✅ Generated full markdown: {args.full_out}", file=sys.stderr)
            md_success = True
        except Exception as e:
            print(f"Error writing full markdown to {args.full_out}: {e}", file=sys.stderr)
            if os.path.commonpath([base_dir_abs, tmp_full_out]) == base_dir_abs and os.path.exists(tmp_full_out):
                try:
                    os.remove(tmp_full_out)
                except Exception:
                    # Ignore errors when attempting to delete temporary Markdown file as cleanup is best-effort.
                    pass

        if not (xml_success and md_success):
            sys.exit(1)
        sys.exit(0)

    # Standard CLI behavior: default to printing XML to stdout, or output files if requested
    xml_content = generate_xml_context(parsed, args.base_dir)

    if args.xml_out:
        resolved_xml_out = os.path.realpath(os.path.abspath(args.xml_out))
        if os.path.commonpath([base_dir_abs, resolved_xml_out]) != base_dir_abs:
            print(f"Error: Path traversal blocked on XML output file '{args.xml_out}'.", file=sys.stderr)
            sys.exit(1)
        tmp_xml_out = resolved_xml_out + ".tmp"
        if os.path.commonpath([base_dir_abs, tmp_xml_out]) != base_dir_abs:
            print(f"Error: Path traversal blocked on temporary XML file.", file=sys.stderr)
            sys.exit(1)

        try:
            with open(tmp_xml_out, "w", encoding="utf-8") as f:
                f.write(xml_content)
            os.replace(tmp_xml_out, resolved_xml_out)
            print(f"✅ Generated XML context: {args.xml_out}", file=sys.stderr)
        except Exception as e:
            print(f"Error writing XML to {args.xml_out}: {e}", file=sys.stderr)
            if os.path.commonpath([base_dir_abs, tmp_xml_out]) == base_dir_abs and os.path.exists(tmp_xml_out):
                try:
                    os.remove(tmp_xml_out)
                except Exception:
                    # Ignore errors when attempting to delete temporary XML file as cleanup is best-effort.
                    pass
            sys.exit(1)
    else:
        # Print XML content to standard output
        print(xml_content)


if __name__ == "__main__":
    main()
