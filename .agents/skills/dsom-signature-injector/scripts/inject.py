# -*- coding: utf-8 -*-
# ---
# okf_version: 0.1
# type: executable_script
# title: "Sovereign Signature Injector"
# description: "Injects deep digital sovereignty headers, footers and licenses to workspace documents and scripts."
# license: "GNU General Public License v3.0"
# author: "Harisfazillah Jamel (LinuxMalaysia)"
# timestamp: 2026-08-01
# topics: [sovereignty, injector, digital-signature, dsom, metadata]
# ---
import os
import sys
import glob
from datetime import datetime

def get_last_modified_date(filepath):
    timestamp = os.path.getmtime(filepath)
    return datetime.fromtimestamp(timestamp).strftime('%Y-%m-%d')

def get_sh_yml_header(date_str):
    return f"""# ==============================================================================
# Protocol    : Deep State of Mind (DSOM) For My AI
# Author      : Harisfazillah Jamel (LinuxMalaysia)
# Timestamp   : {date_str}
# License     : GNU General Public License v3.0
# Standard    : UK English | DBP-standard Bahasa Melayu Malaysia (Piawai)
# ==============================================================================
"""

def get_ps1_header(date_str):
    return f"""<#
.SYNOPSIS
    Deep State of Mind (DSOM) For My AI Protocol
.NOTES
    Author    : Harisfazillah Jamel (LinuxMalaysia)
    Timestamp : {date_str}
    License   : GNU General Public License v3.0
    Standard  : UK English | DBP-standard Bahasa Melayu Malaysia (Piawai)
#>
"""

def is_safe_path(filepath, base_dir=""):
    """
    Validates that the file path does not escape the current workspace directory (project root),
    preventing path traversal vulnerabilities (SonarCloud S2083).
    """
    if not base_dir:
        base_dir = os.getcwd()
    abs_filepath = os.path.realpath(os.path.abspath(filepath))
    abs_base = os.path.realpath(os.path.abspath(base_dir))
    try:
        return os.path.commonpath([abs_base, abs_filepath]) == abs_base
    except ValueError:
        return False

def inject_signature(target_path):
    if not is_safe_path(target_path):
        print(f"Error: Path traversal blocked on target path '{target_path}'.", file=sys.stderr)
        sys.exit(1)

    files_to_process = []

    if os.path.isfile(target_path):
        if not os.path.islink(target_path):
            files_to_process.append(target_path)
    elif os.path.isdir(target_path):
        for root, dirs, files in os.walk(target_path):
            dirs[:] = [d for d in dirs if d not in ('.git', 'node_modules', 'scratch', 'vendor', 'data', 'asimp')]
            for file in files:
                fpath = os.path.join(root, file)
                if os.path.islink(fpath):
                    continue
                if not is_safe_path(fpath):
                    continue
                if file.endswith(('.md', '.sh', '.ps1', '.yml', '.yaml', '.py')):
                    files_to_process.append(fpath)

    for filepath in files_to_process:
        if os.path.islink(filepath) or not is_safe_path(filepath):
            continue

        date_str = get_last_modified_date(filepath)

        md_footer = f"\n\n---\n*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | {date_str}*\n*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*\n"

        # Check and read file descriptor securely using O_NOFOLLOW
        try:
            flags = os.O_RDONLY
            if hasattr(os, 'O_NOFOLLOW'):
                flags |= os.O_NOFOLLOW
            fd = None
            try:
                fd = os.open(filepath, flags)
                with open(fd, 'r', encoding='utf-8', errors='ignore') as f:
                    lines = f.readlines()
            finally:
                if fd is not None:
                    try:
                        os.close(fd)
                    except OSError:
                        # Ignore if descriptor was already closed by standard open wrapper
                        pass
        except Exception as e:
            print(f"Error reading {filepath}: {e}")
            continue

        content = "".join(lines)
        if "Deep State of Mind (DSOM) For My AI Protocol" in content:
            print(f"Skipping {filepath} (Signature already exists)")
            continue

        try:
            if filepath.endswith('.md'):
                flags = os.O_WRONLY | os.O_APPEND
                if hasattr(os, 'O_NOFOLLOW'):
                    flags |= os.O_NOFOLLOW
                fd = None
                try:
                    fd = os.open(filepath, flags)
                    with open(fd, 'w', encoding='utf-8') as f:
                        f.write(content + md_footer)
                finally:
                    if fd is not None:
                        try:
                            os.close(fd)
                        except OSError:
                            # Ignore if descriptor was already closed by standard open wrapper
                            pass
                print(f"Appended Markdown footer to {filepath}")
            elif filepath.endswith(('.sh', '.yml', '.yaml', '.py')):
                header = get_sh_yml_header(date_str)
                if len(lines) > 0 and (lines[0].startswith("#!") or lines[0].startswith("---")):
                    # Shebang or YAML doc start present, insert after it
                    first_line = lines[0]
                    rest = "".join(lines[1:])
                    new_content = first_line + "\n" + header + rest
                else:
                    new_content = header + content

                flags = os.O_WRONLY | os.O_TRUNC
                if hasattr(os, 'O_NOFOLLOW'):
                    flags |= os.O_NOFOLLOW
                fd = None
                try:
                    fd = os.open(filepath, flags)
                    with open(fd, 'w', encoding='utf-8') as f:
                        f.write(new_content)
                finally:
                    if fd is not None:
                        try:
                            os.close(fd)
                        except OSError:
                            # Ignore if descriptor was already closed by standard open wrapper
                            pass
                print(f"Prepended SH/YML/PY header to {filepath}")
            elif filepath.endswith('.ps1'):
                header = get_ps1_header(date_str)
                new_content = header + content
                flags = os.O_WRONLY | os.O_TRUNC
                if hasattr(os, 'O_NOFOLLOW'):
                    flags |= os.O_NOFOLLOW
                fd = None
                try:
                    fd = os.open(filepath, flags)
                    with open(fd, 'w', encoding='utf-8') as f:
                        f.write(new_content)
                finally:
                    if fd is not None:
                        try:
                            os.close(fd)
                        except OSError:
                            # Ignore if descriptor was already closed by standard open wrapper
                            pass
                print(f"Prepended PS1 header to {filepath}")
        except Exception as e:
            print(f"Error processing {filepath}: {e}")

if __name__ == "__main__":
    if len(sys.argv) < 2:
        print("Usage: python inject.py <file_or_directory_path>")
        sys.exit(1)

    inject_signature(sys.argv[1])
