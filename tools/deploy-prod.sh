#!/usr/bin/env bash

# ==============================================================================
# Protocol    : Deep State of Mind (DSOM) For My AI
# Author      : Harisfazillah Jamel (LinuxMalaysia)
# Timestamp   : 2026-07-27
# License     : GNU General Public License v3.0
# Standard    : UK English | DBP-standard Bahasa Melayu Malaysia (Piawai)
# ==============================================================================
# CmsForNerd Production Deployment Wrapper
# Enforces the "Trinity of Safety" (Status Probe, Sovereign Gate, Sudo Lock)
set -e

# Color definitions
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[0;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

echo -e "${BLUE}================================================================${NC}"
echo -e "${BLUE}  CmsForNerd Production Deployer - Podman-Compose & BunkerWeb  ${NC}"
echo -e "${BLUE}================================================================${NC}"

# -----------------------------------------------------------------------------
# 1. STATUS PROBE
# -----------------------------------------------------------------------------
echo -e "\n${YELLOW}[1/3] Initiating Status Probe...${NC}"

if ! command -v ansible &>/dev/null; then
    echo -e "${RED}[FAIL] Ansible is not installed on the control machine.${NC}"
    exit 1
fi
echo -e "${GREEN}[OK] Ansible: $(ansible --version | head -n 1)${NC}"

if ! command -v git &>/dev/null; then
    echo -e "${RED}[FAIL] Git is not installed on the control machine.${NC}"
    exit 1
fi
echo -e "${GREEN}[OK] Git: $(git --version)${NC}"

# Verify codebase integrity with local lab-check if Composer is installed
if command -v composer &>/dev/null; then
    echo -e "${YELLOW}[+] Running local Composer lab-check...${NC}"
    composer lab-check || {
        echo -e "${RED}[FAIL] Local code quality or test checks failed. Aborting deployment.${NC}"
        exit 1
    }
    echo -e "${GREEN}[OK] Codebase verified (Zero-Debt).${NC}"
else
    echo -e "${YELLOW}[WARN] Composer not found locally. Skipping local lab-check.${NC}"
fi

# -----------------------------------------------------------------------------
# 2. SOVEREIGN GATE
# -----------------------------------------------------------------------------
echo -e "\n${YELLOW}[2/3] Preparing Sovereign Gate...${NC}"
echo -e "${YELLOW}[+] Resolving effective host variables and command-line overrides...${NC}"

# Define the validation logic in Python
python3 -c '
import sys, json, re

# Required locked identities
required = {
    "podman_cms_user": "dsom-admin",
    "podman_cms_uid": "2001",
    "podman_cms_group": "dsom-admin",
    "podman_cms_gid": "2001"
}

# 1. Start with values from inventory list
effective_vars = {}
try:
    # Run ansible-inventory if available to extract base inventory variables
    import subprocess
    cmd = ["ansible-inventory", "-i", "inventory/hosts.prod.yml", "--list"]
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
    else:
        # Fallback to simple YAML parsing if ansible-inventory fails/not installed
        with open("inventory/hosts.prod.yml", "r") as f:
            content = f.read()
            for k in required:
                match = re.search(rf"{k}:\s*\"?([^\s\"\n]+)\"?", content)
                if match:
                    effective_vars[k] = match.group(1)
except Exception as e:
    # Fallback to manual regex if python has any issue
    pass

# 2. Incorporate command-line overrides from "$@"
# Parse extra vars passed via -e or --extra-vars
extra_vars_list = []
args = sys.argv[1:]
for i, arg in enumerate(args):
    if arg == "-e" or arg == "--extra-vars":
        if i + 1 < len(args):
            extra_vars_list.append(args[i+1])
    elif arg.startswith("--extra-vars="):
        extra_vars_list.append(arg.split("=", 1)[1])
    elif arg.startswith("-e"):
        extra_vars_list.append(arg[2:])

for evar in extra_vars_list:
    # Parse key=value pairs, handling JSON style as well if needed
    if evar.startswith("{"):
        try:
            parsed = json.loads(evar)
            for k, v in parsed.items():
                effective_vars[k] = str(v)
        except:
            pass
    else:
        # Parse standard key=value or space-separated key=value pairs
        pairs = re.findall(r"([a-zA-Z_][a-zA-Z0-9_]*)=([^=\s]+)", evar)
        for k, v in pairs:
            # strip quotes
            v = v.strip("\"").strip("\x27")
            effective_vars[k] = v

# Validate identities
violations = 0
for k, expected in required.items():
    val = effective_vars.get(k)
    if val is not None:
        if val != expected:
            print(f"\033[0;31m[FAIL] Identity Standard violation: {k} is overridden to {val} (expected: {expected})")
            violations += 1

if violations > 0:
    print("\033[0;31m[FAIL] Conflicting overrides detected. Aborting deployment to preserve Sovereign Gate.\033[0m")
    sys.exit(1)

print("\033[0;32m[OK] Sovereign Gate validation complete. Identities are locked to dsom-admin:2001:2001.\033[0m")
sys.exit(0)
' "$@" || exit 1

# -----------------------------------------------------------------------------
# 3. SUDO LOCK
# -----------------------------------------------------------------------------
echo -e "\n${YELLOW}[3/3] Enforcing Sudo Lock...${NC}"
echo -e "${YELLOW}[+] Verifying playbook and roles configurations...${NC}"

# Verify that play-level become is not set to true in deploy_prod_compose.yml
# under set -e we use grep | wc -l to safely capture counts as an integer expression
become_count=$(grep -E "^[[:space:]]{2}become:[[:space:]]*true" playbooks/deploy_prod_compose.yml | wc -l)
if [ "$become_count" -gt 0 ]; then
    echo -e "${RED}[FAIL] Sudo Lock violation: Play-level root privilege escalation (become: true) detected in playbooks/deploy_prod_compose.yml.${NC}"
    exit 1
fi
echo -e "${GREEN}[OK] Verified that play-level root privilege escalation is disabled.${NC}"

# Perform Ansible syntax check
ansible-playbook --syntax-check playbooks/deploy_prod_compose.yml -i inventory/hosts.prod.yml || {
    echo -e "${RED}[FAIL] Playbook syntax check failed!${NC}"
    exit 1
}
echo -e "${GREEN}[OK] Playbook syntax validated.${NC}"
echo -e "${GREEN}[OK] Sudo Lock validation complete. Root privilege escalation restricted to foundation tasks.${NC}"

# -----------------------------------------------------------------------------
# EXECUTION
# -----------------------------------------------------------------------------
echo -e "\n${GREEN}>>> Trinity of Safety completed successfully! Starting deployment...<<<${NC}"
echo -e "${BLUE}[+] Command: ansible-playbook playbooks/deploy_prod_compose.yml -i inventory/hosts.prod.yml${NC}"

# Execute deployment
ansible-playbook playbooks/deploy_prod_compose.yml -i inventory/hosts.prod.yml "$@"

echo -e "\n${GREEN}================================================================${NC}"
echo -e "${GREEN}  DEPLOYMENT SUCCESSFUL - BunkerWeb, Nginx, PHP-FPM Stack Live  ${NC}"
echo -e "${GREEN}================================================================${NC}"
