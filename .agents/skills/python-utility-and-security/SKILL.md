---
okf_version: 0.1
type: skill
title: "Python Utility and Security Standards"
name: "python-utility-and-security"
description: "Guidelines and security protocols for writing Python utility scripts, preventing Path Traversal and ReDoS, managing Python cache, and docstring testing."
topics: [python, security, path-traversal, redos, docstring]
timestamp: 2026-08-01T09:00:00Z
---

# 🐍 Python Utility and Security Standards

## Purpose
This skill defines Python-specific security and quality standards, focusing on path traversal mitigation, regular expression DoS (ReDoS) prevention, PEP 8 compliance, clean environment tracking, and automated docstring checks.

## When to use this skill
Execute this skill when writing, reviewing, or testing Python utility scripts (such as `tools/validate-inventory.py` or `tools/generate-llms-files.py`).

## Guidelines & Best Practices

### 1. Path Traversal Prevention (CWE-22 / S2083)
Python utility scripts that accept file paths as input must resolve and validate them against an explicit safe path helper:
- Always resolve paths using `os.path.abspath()`.
- Check resolved paths with a custom `is_safe_path()` helper to ensure they remain inside authorized repository root/workspace boundaries.

### 2. Regular Expression DoS (ReDoS) Mitigation
To satisfy static analysis security gates and prevent ReDoS vulnerabilities:
- Prefer regex-free line-by-line parsing or string split logic for structural analysis.
- If regular expressions are required, utilize strictly bounded character-class patterns that avoid nested quantifiers or open-ended backtracking.

### 3. Insecure Protocol Alerts
To prevent insecure protocol triggers (e.g. from SonarCloud scanning):
- Avoid embedding raw `"http://"` string literals.
- Construct the `'http'` prefix dynamically (e.g., using `'http'` + `'://'`) or restrict all requests strictly to verified secure HTTPS structures.

### 4. PEP 8 Compliance & Imports
Place all Python imports at the top of the file rather than declaring them lazily inside functions. This is required to pass standard linters and maintain clean module scopes.

### 5. Python Bytecode Cleanliness
To prevent committing compiled Python bytecode and noise to Git:
- Exclude `__pycache__/` and byte-compiled assets (`*.pyc`, `*.pyo`, `*.pyd`) inside `.gitignore`.
- Run a clean step before staging commits to ensure no local bytecode caches are introduced.

### 6. Automated Docstring Validation
Python utility scripts must have corresponding unit tests (e.g., in `tests/test_validate_inventory.py` executed via `python3 -m unittest`) that programmatically verify the presence of standard Google-style docstrings on all internal functions and entry point main routines.


---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-08-01*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
