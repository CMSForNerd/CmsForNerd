#!/usr/bin/env python3
# ==============================================================================
# Protocol    : Deep State of Mind (DSOM) For My AI
# Author      : Harisfazillah Jamel (LinuxMalaysia)
# Timestamp   : 2026-07-27
# License     : GNU General Public License v3.0
# Standard    : UK English | DBP-standard Bahasa Melayu Malaysia (Piawai)
# ==============================================================================
import sys
import json
import os
import shutil
import subprocess

required = {
    "podman_cms_user": "dsom-admin",
    "podman_cms_uid": "2001",
    "podman_cms_group": "dsom-admin",
    "podman_cms_gid": "2001"
}

def is_safe_path(filepath):
    """
    Validates that the file path does not escape the current workspace directory (project root),
    preventing path traversal vulnerabilities (SonarCloud S2083).
    """
    abs_filepath = os.path.realpath(os.path.abspath(filepath))
    base_dir = os.path.realpath(os.path.abspath(os.getcwd()))
    try:
        return os.path.commonpath([base_dir, abs_filepath]) == base_dir
    except ValueError:
        return False

def load_vars_from_file(filepath):
    """
    Loads and parses variables from a YAML or JSON file safely.
    Uses JSON parsing if JSON, otherwise line-by-line parsing for YAML.
    """
    base_dir = os.path.realpath(os.path.abspath(os.getcwd()))
    safe_filepath = os.path.realpath(os.path.abspath(filepath))

    try:
        if not (safe_filepath.startswith(base_dir + os.path.sep) or safe_filepath == base_dir):
            raise ValueError(f"Access denied to path '{filepath}'")

        # Open with O_NOFOLLOW to reject symlinks at open time (closing TOCTOU gap)
        flags = os.O_RDONLY
        if hasattr(os, 'O_NOFOLLOW'):
            flags |= os.O_NOFOLLOW
        fd = os.open(safe_filepath, flags)
        with open(fd, "r", encoding="utf-8") as f:
            content = f.read()
        try:
            return json.loads(content)
        except json.JSONDecodeError:
            parsed = {}
            for line in content.splitlines():
                line = line.strip()
                if not line or line.startswith("#") or line.startswith("---"):
                    continue
                if ":" in line:
                    k, v = line.split(":", 1)
                    k = k.strip()
                    v = v.strip().strip("\"").strip("'")
                    if k.isidentifier():
                        parsed[k] = v
            return parsed
    except Exception as e:
        print(f"Warning: Failed to load extra-vars file {filepath}: {e}")
        return {}

def parse_key_value_string(s):
    """
    Parses space-separated or comma-separated key=value pairs into a dictionary safely.
    Uses split-based parsing to avoid ReDoS (Regular Expression Denial of Service) risks.
    """
    parsed = {}
    parts = s.replace(",", " ").split()
    for part in parts:
        if "=" in part:
            k, v = part.split("=", 1)
            k = k.strip()
            v = v.strip().strip("\"").strip("'")
            if k.isidentifier():
                parsed[k] = v
    return parsed

def main(args):
    """Validate inventory identity variables for the Podman CMS.

    This function coordinates validation of required platform identity settings.
    Exits with status 1 if any standards are violated, and status 0 if completely compliant.
    """
    effective_vars = {}
    ansible_success = False

    # 1. Start by running ansible-inventory
    try:
        ansible_bin = shutil.which("ansible-inventory")
        if ansible_bin:
            cmd = [ansible_bin, "-i", "inventory/hosts.prod.yml", "--list"]
            res = subprocess.run(cmd, capture_output=True, text=True)
            if res.returncode == 0:
                data = json.loads(res.stdout)
                all_vars = data.get("all", {}).get("vars", {})
                for k, v in all_vars.items():
                    effective_vars[k] = str(v)

                # Check specific groups or host variables
                _meta = data.get("_meta", {})
                hostvars = _meta.get("hostvars", {})
                for host, hvars in hostvars.items():
                    for k, v in hvars.items():
                        effective_vars[k] = str(v)
                ansible_success = True
            else:
                print(f"Warning: ansible-inventory returned non-zero status: {res.stderr.strip()}")
        else:
            print("Warning: ansible-inventory executable not found in system PATH.")
    except Exception as e:
        print(f"Warning: ansible-inventory failed to execute: {e}")

    # 2. Fallback to parsing inventory file with line-by-line parsing if ansible-inventory failed
    if not ansible_success:
        print("Attempting fallback YAML parsing of inventory/hosts.prod.yml...")
        try:
            with open("inventory/hosts.prod.yml", "r", encoding="utf-8") as f:
                for line in f:
                    line = line.strip()
                    if not line or line.startswith("#") or line.startswith("---"):
                        continue
                    if ":" in line:
                        k, v = line.split(":", 1)
                        k = k.strip()
                        v = v.strip().strip("\"").strip("'")
                        if k in required:
                            effective_vars[k] = v
        except Exception as fallback_err:
            print(f"Error: Fallback YAML parsing of inventory/hosts.prod.yml failed: {fallback_err}")

    # Incorporate command-line overrides from args
    extra_vars_list = []
    for i, arg in enumerate(args):
        if arg == "-e" or arg == "--extra-vars":
            if i + 1 < len(args):
                extra_vars_list.append(args[i+1])
        elif arg.startswith("--extra-vars="):
            extra_vars_list.append(arg.split("=", 1)[1])
        elif arg.startswith("-e"):
            extra_vars_list.append(arg[2:])

    for evar in extra_vars_list:
        # Detect @filename inputs
        if evar.startswith("@"):
            filepath = evar[1:]
            file_vars = load_vars_from_file(filepath)
            for k, v in file_vars.items():
                effective_vars[k] = str(v)
        elif evar.startswith("{"):
            try:
                parsed = json.loads(evar)
                for k, v in parsed.items():
                    effective_vars[k] = str(v)
            except Exception as parse_err:
                print(f"Warning: Failed to parse JSON extra-vars: {parse_err}")
        else:
            file_vars = parse_key_value_string(evar)
            for k, v in file_vars.items():
                effective_vars[k] = str(v)

    # Fail if effective_vars is completely empty
    if not effective_vars:
        print("\033[0;31m[FAIL] Inventory verification failed: No inventory variables could be loaded from either source.\033[0m")
        sys.exit(1)

    # Validate identities
    violations = 0
    for k, expected in required.items():
        val = effective_vars.get(k)
        if val is None:
            print(f"\033[0;31m[FAIL] Identity Standard violation: required key '{k}' is absent from effective variables.\033[0m")
            violations += 1
        elif val != expected:
            print(f"\033[0;31m[FAIL] Identity Standard violation: {k} is overridden to {val} (expected: {expected})")
            violations += 1

    if violations > 0:
        print("\033[0;31m[FAIL] Conflicting overrides detected. Aborting deployment to preserve Sovereign Gate.\033[0m")
        sys.exit(1)

    print("\033[0;32m[OK] Sovereign Gate validation complete. Identities are locked to dsom-admin:2001:2001.\033[0m")
    sys.exit(0)

if __name__ == "__main__":
    main(sys.argv[1:])
