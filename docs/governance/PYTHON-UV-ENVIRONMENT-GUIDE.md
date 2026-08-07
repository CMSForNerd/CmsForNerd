---
okf_version: 0.1
type: documentation
title: "Python uv Environment Guide"
description: "Governance policy and operational standard for managing isolated Python environments using the uv package manager within the DSOM framework."
resource: "file:///docs/governance/PYTHON-UV-ENVIRONMENT-GUIDE.md"
timestamp: 2026-07-12T07:50:00Z
topics: [docs, governance, python, environment, problem]
---
# Python `uv` Environment Setup Guide

This document outlines the standard governance approach for managing Python environments and dependencies across the Deep State of Mind (DSOM) framework and its distributed control nodes.

## The Problem with Global Python on Windows
Traditional Python installations on Windows often suffer from critical operational failures:
1. **PATH Conflicts**: The Windows Store "App Execution Aliases" hijack `python.exe` and `python3.exe`, maliciously intercepting terminal commands.
2. **Version Clashes**: Different architectural logic wings require distinct Python versions (e.g., 3.10 vs 3.12).
3. **Agent Automation Failures**: AI Cognitive Twins running background scripts frequently fail due to missing dependencies or inconsistent global Python states.

## The Solution: `uv`
To solve this, the DSOM framework formally mandates the use of [uv](https://github.com/astral-sh/uv), an extremely fast Python package and project manager written in Rust.

The `uv` engine bypasses global PATH issues by downloading and isolating exact Python versions on-demand without altering or contaminating the host machine's global state. This enforces strict digital sovereignty and operational isolation.

## Installation

### Windows (PowerShell)
Execute the following native command to securely install `uv` into your user profile (`~/.local/bin`):
```powershell
powershell -ExecutionPolicy ByPass -c "irm https://astral.sh/uv/install.ps1 | iex"
```

### Linux / macOS / Termux
```bash
curl -LsSf https://astral.sh/uv/install.sh | sh
```

---

## Daily Workflow for Developers & AI Agents

Once `uv` is installed, **do not utilise standard `pip` or `python` commands**. Exclusively use `uv` for all operations to ensure strict environmental isolation and operational compliance.

### 1. Running a Script (Zero-Setup)
If an agent or operator must run a script (`verify.py`) flawlessly without installing Python manually:
```bash
uv run verify.py
```
*Note: If the required Python interpreter is not installed, `uv` will automatically pull the correct version, cache it locally, and execute the script in milliseconds.*

### 2. Forcing a Specific Python Version
If a specific script requires legacy support (e.g., Python 3.10):
```bash
uv run --python 3.10 verify.py
```

### 3. Creating a Project Environment
When scaffolding a new Python dependency isolated to a specific logic wing:
```bash
uv init my-project
cd my-project
uv add requests    # Installs the 'requests' library instantly
uv run main.py     # Runs your code using the isolated environment
```

## Why this matters for DSOM Sovereign AI Agents
By strictly adopting `uv`, AI Agents operating within the DSOM framework can safely execute verification scripts, data processing tools, and automation tasks without risking the contamination of the host control node's global environment or silently failing due to missing PATH variables.

---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-07-12*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
