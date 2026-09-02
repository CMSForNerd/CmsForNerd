---
okf_version: 0.1
type: skill
title: "Universal Sovereign Signature Injector"
name: "dsom-signature-injector"
description: "Automatically injects the standard DSOM ownership, timestamp, and GPL v3.0 license signature into Markdown files and executable scripts based on the file's last modified date."
topics: [signature, license, gpl, okf, markdown]
timestamp: 2026-07-12T07:08:35Z
---
# DSOM Signature Injector

**Purpose**: To enforce digital sovereignty and authorship across all readable files in the DSOM framework.

## Execution Workflow

1. **Trigger Condition**: When generating a new Markdown file, script, or playbook, or when the user asks to sign/stamp a directory or file.
2. **Execute Script**: Run the embedded Python script targeting the specific file or directory.

   ```bash
   python .agents/skills/dsom-signature-injector/scripts/inject.py <target_path>
   ```

## Technical Constraints
- **Markdown (.md):** The script blind-appends the signature to the bottom using an italicized Markdown footer. The timestamp is dynamically calculated from the file's `mtime`.
- **Code Files (.sh, .ps1, .yml):** The script prepends the signature to the top of the file using the appropriate comment syntax (e.g., `#`) to ensure the code remains executable without fatal syntax errors.


---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-07-12*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
