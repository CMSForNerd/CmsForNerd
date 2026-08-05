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
This skill defines documentation standards, educational file synchronization procedures, docblock section styling, and dependency installation constraints.

## When to use this skill
Trigger this skill when performing project-wide documentation audits, editing controller code, updating README/CHANGELOG/HISTORY, or running initial workspace configurations.

## Guidelines & Best Practices

### 1. Document Update Sequencing
When performing project-wide documentation updates or changes:
- Always update core record-keeping files (`README.md`, `CHANGELOG.md`, `HISTORY.md`) **last**.
- This ensures these files accurately summarize all changes actually executed and completed during the task.

### 2. High-Fidelity Architecture Version
The active architecture is targeted at `v4.0.0-alpha` (or higher) and leverages:
- PHP 8.4+ (strict types).
- Zero-Global design using immutable `CmsContext` and `Registry`.
- High-Fidelity Glassmorphic UI.
- AMP dual-view rendering engine.
- Flat-file pair storage logic.

### 3. Controller Docblock Section Numbering
Maintain strict sequential numbering for docblock comment sections in all PHP controllers to preserve readability and design consistency:
```php
/**
 * 1. [PERFORMANCE]
 * 2. [LAB] BOOTSTRAP PHASE
 * 3. [PAGE] RENDERING ACTIONS
 */
```

### 4. Educational Guide Synchronization
When modifying any core CMS routing, template logic, or architectural functions, you must proactively update corresponding educational code and template instructions inside the `docs/` Palace (such as `LAB-GUIDE.md` and `template-guide.md`) to keep references in perfect synchronization.

### 5. Platform-Agnostic Composer Configuration
If the local runtime environment has a lower PHP version (e.g., PHP 8.3) than the project's requirement (PHP 8.4):
- Instruct or execute composer installation bypassing platform version checks:
  ```bash
  composer install --ignore-platform-reqs
  ```


---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-08-05*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
