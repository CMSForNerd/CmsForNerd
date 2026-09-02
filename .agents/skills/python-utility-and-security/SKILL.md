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
This skill defines Python-specific security and quality standards, focusing on path traversal mitigation, regular expression DoS (ReDoS) prevention, PEP 8 compliance, clean environment tracking, symlink safety, descriptor-anchored opens, and automated docstring checks.

## When to use this skill
Execute this skill when writing, reviewing, or testing Python utility scripts (such as `tools/validate-inventory.py`, `tools/generate-llms-files.py`, `apply_okf.py`, `calculate-tokens.py`, or `inject.py`).

## Guidelines & Best Practices

### 1. Path Traversal Prevention (CWE-22 / S2083)
Python utility scripts that accept file paths as input must resolve and validate them against an explicit safe path helper:
- Resolve paths using `os.path.abspath()` and `os.path.realpath(os.path.abspath(...))`.
- Canonicalize candidate paths with `os.path.realpath()` and use `os.path.commonpath()` or an equivalent separator-aware containment helper instead of direct `startswith()` checks, ensuring similarly prefixed sibling paths are rejected before filesystem operations.
- Check resolved paths with a custom `is_safe_path()` helper to verify that the target path remains strictly within allowed workspace boundaries.
- Every path yielded by `os.walk` in Python skills scripts (such as `inject.py`, `calculate-tokens.py`, and `apply_okf.py`) must be validated via `is_safe_path()` before any file system operations, and symlinks must be cleanly skipped inside the walk loop.

### 2. Path Canonicalization & Argument Input Validation
To satisfy SonarCloud security validations for user-influenced arguments (e.g., `args.input`, `args.base_dir`, `args.xml_out`):
- Path variables derived from user-influenced arguments are canonicalized using `os.path.realpath`.
- Validate containment via `os.path.commonpath([abs_base, abs_candidate]) == abs_base` before performing any filesystem reads, writes, or deletes.

### 3. Symlink & TOCTOU Protection (CWE-59)
To prevent symlink-following (CWE-59) and TOCTOU exploits during file access:
- Use low-level descriptor-anchored opens by calling `os.open` with `os.O_NOFOLLOW` (where supported by the OS).
- Cleanly close file descriptors using `os.close(fd)` inside explicit `finally:` blocks.
- Test suites utilize uniquely named temporary file fixtures via `tempfile.NamedTemporaryFile` and verify symlink safety rules using comprehensive, mock-free command-line writing workflows.

### 4. Regular Expression DoS (ReDoS) Mitigation
To satisfy static analysis security gates and prevent ReDoS vulnerabilities:
- Prefer regex-free line-by-line parsing or string split logic for structural analysis.
- If regular expressions are required, utilize strictly bounded character-class patterns that avoid nested quantifiers or open-ended backtracking.

### 5. Insecure Protocol Safeguards
To block unvalidated plaintext endpoints:
- Block unvalidated `"http://"` endpoints, construct `'http'` + `'://'` dynamically, and require HTTPS URLs by default.
- Allow plaintext HTTP only for explicitly allowlisted loopback or test endpoints, and reject all other non-HTTPS URLs.

### 6. PEP 8 Compliance & Imports
Place all Python imports at the top of the file rather than declaring them lazily inside functions. This is required to adhere to PEP 8 standards, pass standard linters, and maintain clean module scopes.

### 7. Python Bytecode Cleanliness
To prevent accidental commits of Python bytecode and other cache noise to Git:
- Exclude `__pycache__/` and byte-compiled assets (`*.pyc`, `*.pyo`, `*.pyd`) inside `.gitignore`.
- Run a clean step before staging commits to ensure no local bytecode caches are introduced.

### 8. Automated Docstring & LLM File Generation Testing
- Python utility modules like `tools/validate-inventory.py` have a Python unit test suite `tests/test_validate_inventory.py` run via `python3 -m unittest` which strictly checks and enforces standard Google-style docstrings on internal functions and entry point main routines.
- To parse `llms.txt` and automate LLM documentation index updates, the project uses the Python utility script `tools/generate-llms-files.py` (tested via `tests/test_generate_llms_files.py`), which generates the XML context document `llms.xml` and the consolidated single-file markdown index `llms-full.txt`.


---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-08-01*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
