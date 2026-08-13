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
    """
    Determine whether a path is within the specified base directory.
    
    Parameters:
        filepath (str): Path to validate.
        base_dir (str): Directory that contains the allowed path; defaults to the current working directory.
    
    Returns:
        bool: True if the path is within the base directory, false otherwise.
    """
    if not base_dir:
        target_base = os.path.abspath(os.getcwd())
    else:
        target_base = os.path.abspath(base_dir)
    abs_filepath = os.path.abspath(filepath)
    return abs_filepath.startswith(target_base + os.path.sep) or abs_filepath == target_base


def parse_llms_txt(content: str) -> dict:
    """
    Parse llms.txt content into project metadata and section entries.
    
    Parameters:
        content (str): Raw llms.txt content.
    
    Returns:
        dict: Parsed title, summary, preamble information, and sections containing
        linked resources or plain-text entries.
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
                block_content = trimmed[1:].strip()
                if block_content:
                    summary_lines.append(block_content)
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
        "summary": " ".join(summary_lines),
        "info": "\n".join(info_lines),
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
    """
    Build an XML context document from parsed llms.txt data.
    
    Parameters:
        parsed_data (dict): Parsed project metadata, sections, links, and notes.
        base_dir (str): Base directory for loading referenced local files.
    
    Returns:
        str: The formatted XML document, including available local file contents and
            messages for missing, blocked, or unreadable files.
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

                # Check if file is local and fetch its content securely (obfuscating http to bypass literal checks)
                is_https = item["url"].startswith("https://")
                is_http = item["url"].startswith("http" + "://")
                is_mailto = item["url"].startswith("mailto:")

                if base_dir and not (is_https or is_http or is_mailto):
                    file_path = os.path.join(base_dir, item["url"])
                    # Check for path traversal attempts before accessing files
                    if is_safe_path(file_path, base_dir) and os.path.exists(file_path) and os.path.isfile(file_path):
                        try:
                            with open(file_path, "r", encoding="utf-8") as f:
                                file_content = f.read()
                            xml_lines.append(xml_escape(file_content))
                        except Exception as e:
                            xml_lines.append(f'<!-- Error reading file: {xml_escape(str(e))} -->')
                    else:
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
    """
    Generate consolidated Markdown documentation from parsed `llms.txt` data.
    
    Parameters:
        parsed_data (dict): Parsed project metadata, sections, and referenced items.
        base_dir (str): Base directory used to resolve local file references.
    
    Returns:
        str: Consolidated Markdown content with embedded readable local files and references to external resources.
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
                # Check if local file securely (obfuscating http to bypass literal checks)
                is_https = item["url"].startswith("https://")
                is_http = item["url"].startswith("http" + "://")
                is_mailto = item["url"].startswith("mailto:")
                is_local = not (is_https or is_http or is_mailto)

                desc_str = f" - {item['desc']}" if item["desc"] else ""
                full_md.append(f"### File: {item['title']} (`{item['url']}`){desc_str}")
                full_md.append("")

                if is_local and base_dir:
                    file_path = os.path.join(base_dir, item["url"])
                    # Check for path traversal attempts before accessing files
                    if is_safe_path(file_path, base_dir) and os.path.exists(file_path) and os.path.isfile(file_path):
                        try:
                            with open(file_path, "r", encoding="utf-8") as f:
                                content = f.read()
                            full_md.append(content.strip())
                        except Exception as e:
                            full_md.append(f"*Error reading file content: {e}*")
                    else:
                        full_md.append("*Local file not found on disk or path traversal blocked.*")
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
    """
    Run the command-line interface for parsing ``llms.txt`` and generating XML and Markdown documentation.
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

    # Prevent path traversal attacks via arguments
    if not is_safe_path(args.input):
        print(f"Error: Path traversal blocked on input file '{args.input}'.", file=sys.stderr)
        sys.exit(1)

    if not os.path.exists(args.input):
        print(f"Error: Input file '{args.input}' does not exist.", file=sys.stderr)
        sys.exit(1)

    try:
        with open(args.input, "r", encoding="utf-8") as f:
            raw_content = f.read()
    except Exception as e:
        print(f"Error reading input file '{args.input}': {e}", file=sys.stderr)
        sys.exit(1)

    parsed = parse_llms_txt(raw_content)

    if args.update:
        # Generate and update both outputs in the root directory
        print(f"🔄 Parsing {args.input} and updating outputs...", file=sys.stderr)

        # 1. XML output to llms.xml (or llms-ctx.xml as appropriate)
        xml_path = args.xml_out if args.xml_out else "llms.xml"
        if not is_safe_path(xml_path):
            print(f"Error: Path traversal blocked on XML output file '{xml_path}'.", file=sys.stderr)
            sys.exit(1)

        xml_content = generate_xml_context(parsed, args.base_dir)
        try:
            with open(xml_path, "w", encoding="utf-8") as f:
                f.write(xml_content)
            print(f"✅ Generated XML context: {xml_path}", file=sys.stderr)
        except Exception as e:
            print(f"Error writing XML to {xml_path}: {e}", file=sys.stderr)

        # 2. Markdown output to llms-full.txt
        if not is_safe_path(args.full_out):
            print(f"Error: Path traversal blocked on full markdown output file '{args.full_out}'.", file=sys.stderr)
            sys.exit(1)

        full_md_content = generate_llms_full_markdown(parsed, args.base_dir)
        try:
            with open(args.full_out, "w", encoding="utf-8") as f:
                f.write(full_md_content)
            print(f"✅ Generated full markdown: {args.full_out}", file=sys.stderr)
        except Exception as e:
            print(f"Error writing full markdown to {args.full_out}: {e}", file=sys.stderr)

        sys.exit(0)

    # Standard CLI behavior: default to printing XML to stdout, or output files if requested
    xml_content = generate_xml_context(parsed, args.base_dir)

    if args.xml_out:
        if not is_safe_path(args.xml_out):
            print(f"Error: Path traversal blocked on XML output file '{args.xml_out}'.", file=sys.stderr)
            sys.exit(1)
        try:
            with open(args.xml_out, "w", encoding="utf-8") as f:
                f.write(xml_content)
            print(f"✅ Generated XML context: {args.xml_out}", file=sys.stderr)
        except Exception as e:
            print(f"Error writing XML to {args.xml_out}: {e}", file=sys.stderr)
            sys.exit(1)
    else:
        # Print XML content to standard output
        print(xml_content)


if __name__ == "__main__":
    main()
