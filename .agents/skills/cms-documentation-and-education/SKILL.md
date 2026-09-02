---
okf_version: 0.1
type: skill
title: "CMS Documentation, Version Alignment, and Educational Synchronization"
name: "cms-documentation-and-education"
description: "Standards for record-keeping file synchronization order, docblock sectioning, environment installation, and educational manual alignment."
topics: [documentation, architecture, composer, education, alignment]
timestamp: 2026-08-01T09:00:00Z
---

# 📚 CMS Documentation, Version Alignment, and Educational Synchronization

## Purpose
This skill defines documentation standards, educational file synchronization procedures, docblock section styling, Diátaxis framework structuring, and dependency installation constraints.

## When to use this skill
Trigger this skill when performing project-wide documentation audits, editing controller code, updating README/CHANGELOG/HISTORY, or running initial workspace configurations.

## Guidelines & Best Practices

### 1. Document Update Sequencing & Omni-Documentation Sync
- When performing project-wide documentation updates or changes: always update core record-keeping files (`README.md`, `CHANGELOG.md`, `HISTORY.md`) **last** to accurately summarize all changes executed.
- **Rule 14 (Omni-Documentation Sync):** In compliance with Rule 14, newly created technical documentation is systematically registered in four distinct navigation maps: `SUMMARY.md` (GitBook), `mkdocs.yml` (MkDocs), `START-HERE.md` (Sovereign Onboarding Entry Point map with 12 distinct Entry Points), and `llms.txt` (External AI Indexing).
- **Modern LLM Standards:** The project implements modern `/llms.txt` and `.llms/index.md` standards for semantic LLM crawlability, mapping architectural specifications, file directories, and security rules clearly for cognitive agents.
- **Rulebook Synchronization:** Changes to rulebooks (like `.agents/AGENTS.md` and root `AGENTS.md`) and guides (such as `docs/AI-AGENT-SKILLS-GUIDE.md`) must be kept synchronized, maintain updated ISO 8601 timestamps inside OKF frontmatters, and carry valid DSOM footer signatures.

### 2. Diátaxis Documentation Framework
The Local User Manual for CmsForNerd is structured using the Diátaxis documentation framework across:
- `docs/tutorials/`
- `docs/how-to/`
- `docs/reference/`
- `docs/explanation/`
- `docs/user-manual/`
with `user-manual.php` (Entry Point 17) providing web access.

### 3. High-Fidelity Architecture Version
The active architecture is targeted at `v4.0.0-alpha` (or higher) and leverages:
- PHP 8.4+ (strict types).
- Zero-Global design using immutable `CmsContext` and `Registry`.
- High-Fidelity Glassmorphism UI.
- AMP dual-view rendering engine.
- Flat-file pair storage logic.

### 4. Controller Docblock Section Numbering
Maintain strict sequential numbering for docblock comment sections in all PHP controllers to preserve readability and design consistency:
```php
/**
 * 1. [PERFORMANCE]
 * 2. [LAB] BOOTSTRAP PHASE
 * 3. [PAGE] RENDERING ACTIONS
 */
```

### 5. Educational Guide Synchronization
When modifying any core CMS routing, template logic, or architectural functions, you must proactively update corresponding educational code and template instructions inside the `docs/` Palace (such as `LAB-GUIDE.md` and `template-guide.md`) to keep references in perfect synchronization.

### 6. Production Installation & Local Runtime Validation
The standard deployment requires PHP 8.4+ as the normal installation path.
- In production, always perform standard installations without ignoring platform requirements.
- If an older local runtime (e.g., PHP 8.3) is used solely to prepare dependencies or run pre-flight tests offline, dependencies can be prepared locally. However, you must instruct the developer or CI runner to validate the environment configuration against the target runtime using:
  ```bash
  composer check-platform-reqs
  ```
  in the target PHP 8.4+ execution environment.


---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-08-05*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
