#!/usr/bin/env python3
# -*- coding: utf-8 -*-
# ---
# okf_version: 0.1
# type: executable_script
# title: "Core Token Counter Runtime v2"
# description: "Measures token footprints per file and per Markdown section. Raises breach alerts when sections exceed the 4,000-token quality gate."
# license: "GNU General Public License v3.0"
# author: "Harisfazillah Jamel (LinuxMalaysia)"
# timestamp: 2026-07-19
# ---

import io
import os
import re
import sys
import time
import tiktoken

# Force UTF-8 stdout on Windows to handle special characters
if sys.platform == "win32":
    sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding="utf-8", errors="replace")

GATE_THRESHOLD = 4000  # Token circuit-breaker per file or section

def get_encoding(model: str = "gpt-4"):
    """Return tiktoken encoding, falling back to cl100k_base."""
    try:
        return tiktoken.encoding_for_model(model)
    except Exception:
        return tiktoken.get_encoding("cl100k_base")

def count_tokens(text: str, enc) -> int:
    """Count tokens for a given text string."""
    return len(enc.encode(text))

def split_markdown_sections(md_text: str) -> list:
    """
    Split Markdown into sections on H2 boundaries.
    Anchors to line-start with (?m)^## to avoid matching ## inside YAML frontmatter strings.
    Returns list of (section_title, section_content) tuples.
    """
    parts = re.split(r'(?m)^## ', md_text)
    sections = []
    if parts[0].strip():
        sections.append(("(preamble / frontmatter)", parts[0]))
    for part in parts[1:]:
        lines = part.split("\n", 1)
        title = lines[0].strip()
        body = lines[1] if len(lines) > 1 else ""
        sections.append((f"## {title}", body))
    return sections

def analyse_file(filepath: str, enc, verbose_sections: bool = False):
    """
    Analyse a single file: total tokens, per-section tokens, breach flags.
    Returns (total_tokens, breached: bool).
    """
    try:
        t0 = time.perf_counter()
        with open(filepath, "r", encoding="utf-8", errors="ignore") as f:
            text = f.read()
        read_ms = (time.perf_counter() - t0) * 1000

        t1 = time.perf_counter()
        total_tokens = count_tokens(text, enc)
        tok_ms = (time.perf_counter() - t1) * 1000

        status = "[BLOCKED]" if total_tokens >= GATE_THRESHOLD else "[OK]    "
        print(f"  {status} {total_tokens:>7,} tok  read={read_ms:>7.2f}ms  tok={tok_ms:>6.2f}ms  {filepath}")

        if total_tokens >= GATE_THRESHOLD:
            print(f"           [!] File exceeds {GATE_THRESHOLD:,}-token gate. Use view_file with line ranges instead of full load.")
            if verbose_sections:
                _report_sections(text, enc, os.path.basename(filepath))
        return total_tokens, total_tokens >= GATE_THRESHOLD

    except Exception as e:
        print(f"  [ERROR]  Could not read {filepath}: {e}")
        return 0, False

def _report_sections(text: str, enc, filename: str):
    """Print per-section token breakdown and flag breaching sections."""
    sections = split_markdown_sections(text)
    print(f"           --- Section breakdown for {filename} ---")
    for title, body in sections:
        sec_tokens = count_tokens(f"## {title}\n{body}", enc)
        flag = "  [BREACH]" if sec_tokens >= GATE_THRESHOLD else ""
        print(f"           {sec_tokens:>6,} tok  {title[:70]}{flag}")

def is_safe_path(filepath, base_dir=""):
    """
    Validates that the file path does not escape the current workspace directory (project root),
    preventing path traversal vulnerabilities (SonarCloud S2083).
    """
    if not base_dir:
        base_dir = os.getcwd()
    abs_filepath = os.path.realpath(os.path.abspath(filepath))
    abs_base = os.path.realpath(os.path.abspath(base_dir))
    return abs_filepath.startswith(abs_base + os.path.sep) or abs_filepath == abs_base

def scan_path(target_path: str, verbose_sections: bool = False):
    """Scan a file or directory tree and report token footprints with breach flags."""
    if not is_safe_path(target_path):
        print(f"Error: Path traversal blocked on target path '{target_path}'.", file=sys.stderr)
        sys.exit(1)

    if not os.path.exists(target_path):
        print(f"[ERROR] Target path '{target_path}' does not exist.")
        sys.exit(1)

    enc = get_encoding()
    total_tokens = 0
    total_breaches = 0
    file_count = 0

    ALLOWED_EXTS = (".py", ".md", ".txt", ".json", ".yml", ".yaml", ".sh", ".ps1", ".css", ".html", ".js")

    print(f"[DSOM Token Calculator v2] Gate threshold: {GATE_THRESHOLD:,} tokens")
    print(f"[TARGET] {target_path}")
    print("-" * 72)

    if os.path.isfile(target_path):
        tokens, breached = analyse_file(target_path, enc, verbose_sections=True)
        total_tokens = tokens
        total_breaches = int(breached)
        file_count = 1
    elif os.path.isdir(target_path):
        for root, _, files in os.walk(target_path):
            for fname in sorted(files):
                if not fname.endswith(ALLOWED_EXTS):
                    continue
                fpath = os.path.join(root, fname)
                tokens, breached = analyse_file(fpath, enc, verbose_sections=verbose_sections)
                total_tokens += tokens
                total_breaches += int(breached)
                file_count += 1

    print("-" * 72)
    print(f"[SUMMARY] Files: {file_count}  |  Total tokens: {total_tokens:,}  |  Breached files: {total_breaches}")
    if total_tokens >= GATE_THRESHOLD:
        print(f"[ACTION]  Directory total exceeds {GATE_THRESHOLD:,} tokens. Chunk reads; do NOT load directory wholesale.")
    else:
        print(f"[ACTION]  Safe to load into active context.")

if __name__ == "__main__":
    args = sys.argv[1:]
    if not args:
        print("Usage: uv run --with tiktoken calculate-tokens.py [TARGET_PATH] [--sections]")
        print("  --sections   Show per-Markdown-section token breakdown for breaching files")
        sys.exit(1)

    target = args[0]
    show_sections = "--sections" in args
    scan_path(target, verbose_sections=show_sections)
