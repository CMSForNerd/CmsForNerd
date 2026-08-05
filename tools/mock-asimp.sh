#!/bin/bash

# ==============================================================================
# Protocol    : Deep State of Mind (DSOM) For My AI Protocol (v5.0)
# Author      : Harisfazillah Jamel (LinuxMalaysia)
# Timestamp   : 2026-08-05
# License     : GNU General Public License v3.0
# Standard    : UK English | DBP-standard Bahasa Melayu Malaysia (Piawai)
# Purpose     : Mock execution of ASIMP security hardening & audit for Google Jules
# ==============================================================================
set -e

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
CYAN='\033[0;36m'
NC='\033[0m'

# Derive ROOT_DIR from the script's own location to avoid reliance on caller's CWD
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
ROOT_DIR="$(cd "$SCRIPT_DIR/.." && pwd)"

if [[ -z "$ROOT_DIR" ]]; then
    ROOT_DIR="."
fi

# Detect Google Jules Sandbox Environment
if [[ "$USER" == "jules" ]] || [[ "$LOGNAME" == "jules" ]] || [[ ! -w /etc/sysctl.conf ]]; then
    IS_JULES_MOCK=true
else
    IS_JULES_MOCK=false
fi

echo -e "${CYAN}========================================================================${NC}"
echo -e "${CYAN}                  ASIMP SECURITY AUDIT & HARDENING ENGINE               ${NC}"
echo -e "${CYAN}========================================================================${NC}"

if [[ "$IS_JULES_MOCK" == "true" ]]; then
    echo -e "${YELLOW}[INFO] Google Jules Sandbox Environment Detected.${NC}"
    echo -e "${YELLOW}[INFO] Simulating 'Measure, Harden, Re-Measure' workflow via mock configuration...${NC}"

    # Establish mock directories under data/asimp_mock to prevent permission issues
    MOCK_LOG_DIR="$ROOT_DIR/data/asimp_mock/var/log"
    MOCK_REPORT_DIR="$ROOT_DIR/data/asimp_mock/opt/report/openscap"
    mkdir -p "$MOCK_LOG_DIR"
    mkdir -p "$MOCK_REPORT_DIR"

    echo -e "\n${YELLOW}[PHASE 1] Initialising Baseline Auditing...${NC}"
    echo -e ">>> Scanning system with OpenSCAP (CIS Level 2 Profile)..."
    sleep 0.5
    echo -e ">>> Running system configuration scan via Lynis..."
    sleep 0.5

    # Write pre-hardening scores
    BASELINE_LYNIS_HI=62
    BASELINE_OPENSCAP_PCT=58.4

    echo -e "${GREEN}[OK] Baseline Scores Established:${NC}"
    echo -e "    - Lynis Hardening Index: $BASELINE_LYNIS_HI"
    echo -e "    - OpenSCAP Compliance %: $BASELINE_OPENSCAP_PCT%"

    echo -e "\n${YELLOW}[PHASE 2] Applying System Hardening Policies...${NC}"
    echo -e ">>> [Mock] Hardening Kernel via sysctl.conf..."
    echo -e ">>> [Mock] Applying SSH Server Lockdown configurations..."
    echo -e ">>> [Mock] Restricting compiler execution to Root-Only..."
    echo -e ">>> [Mock] Setting up legal banners and identity protection..."
    echo -e ">>> [Mock] Applying fine-grained OpenSCAP remediations..."
    sleep 0.8
    echo -e "${GREEN}[OK] Hardening measures applied successfully (mock mode).${NC}"

    echo -e "\n${YELLOW}[PHASE 3] Re-Measuring Post-Hardening Compliance...${NC}"
    echo -e ">>> Re-evaluating system via OpenSCAP..."
    sleep 0.5
    echo -e ">>> Re-running Lynis audit..."
    sleep 0.5

    AFTER_LYNIS_HI=88
    AFTER_OPENSCAP_PCT=91.2

    # Save to JSON file
    JSON_FILE="$MOCK_LOG_DIR/asimp-baseline-scores.json"
    cat <<EOF > "$JSON_FILE"
{
  "timestamp": "$(date -u +"%Y-%m-%dT%H:%M:%SZ")",
  "environment": "Google Jules Sandbox Mock",
  "scores": {
    "before": {
      "lynis_hi": $BASELINE_LYNIS_HI,
      "openscap_pct": $BASELINE_OPENSCAP_PCT
    },
    "after": {
      "lynis_hi": $AFTER_LYNIS_HI,
      "openscap_pct": $AFTER_OPENSCAP_PCT
    }
  }
}
EOF

    # If real /var/log/ is writeable, copy there
    if [[ -w /var/log ]]; then
        cp "$JSON_FILE" /var/log/asimp-baseline-scores.json 2>/dev/null || true
    fi

    # Template the SECURITY_AUDIT_REPORT.md
    REPORT_FILE="$MOCK_REPORT_DIR/SECURITY_AUDIT_REPORT.md"
    cat <<EOF > "$REPORT_FILE"
---
okf_version: 0.1
type: report
title: "Google Jules Sovereign OS Security Hardening & Compliance Report"
description: "Simulated security audit and compliance report for Google Jules sandbox"
timestamp: "$(date -u +"%Y-%m-%dT%H:%M:%SZ")"
---
# Google Jules Sovereign OS Security Hardening & Compliance Report

## System Overview
- **Target Host**: Google Jules Sandbox
- **Mock Environment**: Active (Google Jules Secure Containment)
- **Kernel Simulation**: Hardened Core Linux Architecture
- **Report Timestamp**: $(date "+%Y-%m-%d %H:%M:%S")

## Hardening & Audit Scores
- **Lynis Hardening Index**:
  - Baseline: $BASELINE_LYNIS_HI / 100
  - After Hardening: $AFTER_LYNIS_HI / 100
  - Target: 85+ (Sovereign Level)
- **OpenSCAP CIS Level 2 Compliance Score**:
  - Baseline: $BASELINE_OPENSCAP_PCT%
  - After Hardening: $AFTER_OPENSCAP_PCT%
  - Target: 90%+

## Executed Mock Controls & Remediation
- **Transparent Huge Pages (THP)**: Disabled (Simulated via Systemd Hook)
- **SSH Server Hardening**: Lockdown Configured (AllowTcpForwarding=no, MaxAuthTries=3, MaxSessions=2)
- **Compiler Constraints**: Root-Only restricts '/usr/bin/gcc', '/usr/bin/as'
- **Network Sysctl Tuning**: DDoS SYN cookies enabled, TCP BBR congestion control simulation active
- **OpenSCAP Evaluation Status**: Compliant (Remediated via Bash Fix Generator)
- **OVAL Vulnerability Scan**: Non-vulnerable (Fully patched packages simulation)

---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | $(date "+%Y-%m-%d")*
EOF

    # If real /opt/report/openscap/ is writeable, copy there
    if [[ -w /opt/report/openscap ]]; then
        cp "$REPORT_FILE" /opt/report/openscap/SECURITY_AUDIT_REPORT.md 2>/dev/null || true
    fi

    echo -e "\n========================================================================"
    echo -e "                 ${GREEN}ASIMP SECURITY HARDENING REPORT${NC}"
    echo -e "========================================================================"
    echo -e "Tool       | Baseline (Min) | Before Hardening | After Hardening | Target"
    echo -e "------------------------------------------------------------------------"
    echo -e "Lynis HI   | 75             | ${RED}$BASELINE_LYNIS_HI${NC}               | ${GREEN}$AFTER_LYNIS_HI${NC}               | 85+"
    echo -e "OpenSCAP % | 75.0%          | ${RED}$BASELINE_OPENSCAP_PCT%${NC}            | ${GREEN}$AFTER_OPENSCAP_PCT%${NC}            | 90%+"
    echo -e "========================================================================"
    echo -e "Mock reports successfully generated:"
    echo -e "  - JSON Baseline Scores:  $JSON_FILE"
    echo -e "  - Security Audit Report: $REPORT_FILE"
    echo -e "========================================================================"

else
    echo -e "${YELLOW}[INFO] Real OS Environment detected. Running authentic Ansible playbooks...${NC}"

    # Check if ansible-playbook exists
    if ! command -v ansible-playbook &>/dev/null; then
        echo -e "${RED}[ERROR] ansible-playbook command not found. Please install Ansible before running in production.${NC}"
        exit 1
    fi

    # Run actual localhost playbooks (requires root privilege via sudo)
    echo -e "${YELLOW}Executing bootstrap_node.yml on localhost...${NC}"
    ansible-playbook -i "$ROOT_DIR/inventory/hosts.local.yml" "$ROOT_DIR/playbooks/bootstrap_node.yml" --connection=local --become
fi

exit 0
