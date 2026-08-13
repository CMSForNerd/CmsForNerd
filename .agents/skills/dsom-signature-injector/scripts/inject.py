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
    """Generate a PowerShell block-comment header containing DSOM protocol metadata.
    
    Parameters:
    	date_str (str): Date to include in the timestamp field.
    
    Returns:
    	str: Formatted PowerShell header text.
    """
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
    Determine whether a path remains within a specified base directory.
    
    Parameters:
        filepath (str): Path to validate.
        base_dir (str): Directory that the path must remain within. Defaults to the current working directory.
    
    Returns:
        bool: True if the resolved path is within the resolved base directory, false otherwise.
    """
    if not base_dir:
        base_dir = os.getcwd()
    abs_filepath = os.path.realpath(os.path.abspath(filepath))
    abs_base = os.path.realpath(os.path.abspath(base_dir))
    return os.path.commonpath([abs_base, abs_filepath]) == abs_base

def inject_signature(target_path):
    """
    Inject DSOM protocol signatures into supported files.
    
    Parameters:
    	target_path: A file or directory to process. Directory traversal is limited to
    	paths within the current working directory.
    
    The function processes Markdown, shell, YAML, and PowerShell files, skipping files
    that already contain a DSOM signature.
    """
    if not is_safe_path(target_path):
        print(f"Error: Path traversal blocked on target path '{target_path}'.", file=sys.stderr)
        sys.exit(1)

    files_to_process = []

    if os.path.isfile(target_path):
        files_to_process.append(target_path)
    elif os.path.isdir(target_path):
        for root, _, files in os.walk(target_path):
            if '.git' in root or os.path.join('.agents', 'brain') in root:
                continue
            for file in files:
                if file.endswith(('.md', '.sh', '.ps1', '.yml', '.yaml')):
                    files_to_process.append(os.path.join(root, file))

    for filepath in files_to_process:
        date_str = get_last_modified_date(filepath)

        md_footer = f"\n\n---\n*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | {date_str}*\n*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*\n"

        with open(filepath, 'r', encoding='utf-8', errors='ignore') as f:
            lines = f.readlines()

        content = "".join(lines)
        if "Deep State of Mind (DSOM) For My AI Protocol" in content:
            print(f"Skipping {filepath} (Signature already exists)")
            continue

        try:
            if filepath.endswith('.md'):
                with open(filepath, 'a', encoding='utf-8') as f:
                    f.write(md_footer)
                print(f"Appended Markdown footer to {filepath}")
            elif filepath.endswith(('.sh', '.yml', '.yaml')):
                header = get_sh_yml_header(date_str)
                if len(lines) > 0 and (lines[0].startswith("#!") or lines[0].startswith("---")):
                    # Shebang or YAML doc start present, insert after it
                    first_line = lines[0]
                    rest = "".join(lines[1:])
                    new_content = first_line + "\n" + header + rest
                else:
                    new_content = header + content

                with open(filepath, 'w', encoding='utf-8') as f:
                    f.write(new_content)
                print(f"Prepended SH/YML header to {filepath}")
            elif filepath.endswith('.ps1'):
                header = get_ps1_header(date_str)
                new_content = header + content
                with open(filepath, 'w', encoding='utf-8') as f:
                    f.write(new_content)
                print(f"Prepended PS1 header to {filepath}")
        except Exception as e:
            print(f"Error processing {filepath}: {e}")

if __name__ == "__main__":
    if len(sys.argv) < 2:
        print("Usage: python inject.py <file_or_directory_path>")
        sys.exit(1)

    inject_signature(sys.argv[1])
