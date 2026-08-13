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
that bundles all local documentation contents into a single resource.
"""

import argparse
import os
import re
import sys


def parse_llms_txt(content: str) -> dict:
    """Parses the content of an llms.txt file.

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
    # Pattern to extract individual Markdown links
    link_pat = r'-\s*\[(?P<title>[^\]]+)\]\((?P<url>[^\)]+)\)(?::\s*(?P<desc>.*))?'

    # Split by H2 headers to separate sections
    parts = re.split(r'^##\s*(.*?$)', content, flags=re.MULTILINE)

    # First chunk contains H1, blockquote, and initial preamble info
    start = parts[0].strip()

    # Match Title (H1) and Blockquote Summary
    title_match = re.search(r'^#\s*(?P<title>.+?)$', start, re.MULTILINE)
    title = title_match.group('title').strip() if title_match else "Untitled"

    summary_match = re.search(r'^>\s*(?P<summary>.+?)$', start, re.MULTILINE | re.DOTALL)
    summary = summary_match.group('summary').strip() if summary_match else ""

    # Remove title and summary from preamble to get the remaining info
    info = start
    if title_match:
        info = info.replace(title_match.group(0), "", 1)
    if summary_match:
        info = info.replace(summary_match.group(0), "", 1)
    info = info.strip()

    # Parse remaining chunks as H2 sections
    sections = {}
    for i in range(1, len(parts), 2):
        sec_title = parts[i].strip()
        sec_body = parts[i + 1].strip() if i + 1 < len(parts) else ""

        # Parse links inside the section
        links = []
        for line in sec_body.splitlines():
            line_str = line.strip()
            if not line_str:
                continue
            match = re.search(link_pat, line_str)
            if match:
                links.append({
                    "title": match.group("title").strip(),
                    "url": match.group("url").strip(),
                    "desc": match.group("desc").strip() if match.group("desc") else None
                })
            else:
                # Store plain list items or notes as text block entries
                # Strip leading dash, star, or plus from the bullet list item
                clean_title = re.sub(r'^[\-\*\+]\s+', '', line_str)
                links.append({
                    "title": clean_title,
                    "url": None,
                    "desc": None
                })
        sections[sec_title] = links

    return {
        "title": title,
        "summary": summary,
        "info": info,
        "sections": sections
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

                # Check if file is local and fetch its content
                if base_dir and not item["url"].startswith(("http://", "https://", "mailto:")):
                    file_path = os.path.join(base_dir, item["url"])
                    if os.path.exists(file_path) and os.path.isfile(file_path):
                        try:
                            with open(file_path, "r", encoding="utf-8") as f:
                                file_content = f.read()
                            xml_lines.append(xml_escape(file_content))
                        except Exception as e:
                            xml_lines.append(f'<!-- Error reading file: {xml_escape(str(e))} -->')
                    else:
                        xml_lines.append('<!-- Local file not found on disk -->')

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
                # Check if local file
                is_local = not item["url"].startswith(("http://", "https://", "mailto:"))
                desc_str = f" - {item['desc']}" if item["desc"] else ""
                full_md.append(f"### File: {item['title']} (`{item['url']}`){desc_str}")
                full_md.append("")

                if is_local and base_dir:
                    file_path = os.path.join(base_dir, item["url"])
                    if os.path.exists(file_path) and os.path.isfile(file_path):
                        try:
                            with open(file_path, "r", encoding="utf-8") as f:
                                content = f.read()
                            full_md.append(content.strip())
                        except Exception as e:
                            full_md.append(f"*Error reading file content: {e}*")
                    else:
                        full_md.append("*Local file not found on disk.*")
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
        xml_content = generate_xml_context(parsed, args.base_dir)
        try:
            with open(xml_path, "w", encoding="utf-8") as f:
                f.write(xml_content)
            print(f"✅ Generated XML context: {xml_path}", file=sys.stderr)
        except Exception as e:
            print(f"Error writing XML to {xml_path}: {e}", file=sys.stderr)

        # 2. Markdown output to llms-full.txt
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
