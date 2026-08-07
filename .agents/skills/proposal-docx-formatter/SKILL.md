---
okf_version: 0.1
type: agent_skill
title: proposal-docx-formatter
name: proposal-docx-formatter
description: >
  Compiles a markdown proposal document into a professionally formatted DOCX file
  using standard corporate document templates (Times New Roman, A4 page, grey-header tables,
  0.5pt borders, proper heading hierarchy). Use when the user asks to compile, generate,
  or format the migration proposal document.
topics: [docx, proposal, word, document, formatter]
noss_section: "Enterprise Database Migration"
target_format: docx
timestamp: 2026-08-07T05:39:28Z
---
# Proposal DOCX Formatter

## Purpose

Generates a professionally formatted Microsoft Word (.docx) document from a structured
markdown source file. The formatting standards are adopted from the national
Occupational Skills Standard) document generation system.

## Formatting Standards

### Fonts
- **Font Family:** Times New Roman (exclusively)
- **Cover Title:** 24pt bold, centred
- **Cover Subtitle:** 16pt, centred
- **Heading 1 (`##`):** 14pt bold
- **Heading 2 (`###`):** 12pt bold
- **Heading 3 (`####`):** 11pt bold
- **Body Text:** 12pt, 1.15 line spacing
- **Table Content:** 11pt

### Page Layout
- **Paper Size:** A4 Portrait (210mm × 297mm)
- **Margins:** 1.0 inch (1440 dxa) on all sides
- **Header/Footer:** 0.5 inch (720 dxa)

### Table Styling
- **Borders:** 0.5pt single solid black (`000000`) on all edges
- **Header Row Fill:** Light Grey (`D9D9D9`)
- **Header Row Font:** 11pt bold
- **Body Cell Fill:** White (`FFFFFF`)
- **Cell Padding:** 80 dxa (~4pt) on all sides
- **Vertical Alignment:** Centre

### Colour Palette
| Usage               | Hex Code   |
|:--------------------|:-----------|
| Table header fill   | `D9D9D9`   |
| Sub-header fill     | `BFBFBF`   |
| Borders             | `000000`   |
| Body cells          | `FFFFFF`   |

### Linguistic Rules
- **UK English** mandatory (organisation, modernisation, virtualisation)
- Active voice for procedural descriptions
- Passive voice for formal specifications

## Markdown Source Format

The source markdown file must follow this structure:

```markdown
---
title: "Document Title"
subtitle: "Document Subtitle"
version: "X.Y"
date: "DD Month YYYY"
classification: "CONFIDENTIAL"
---

## 1. Section Heading

Body paragraph text with **bold** inline formatting.

### 1.1. Subsection Heading

- Bullet list item
- Another **bold lead-in:** with description

| Column 1 | Column 2 | Column 3 |
|:---------|:---------|:---------|
| Data     | Data     | Data     |

#### Sub-subsection

1. Numbered list item
2. Another item
```

## Build Command

```powershell
uv run --with python-docx python tools/compile_proposal.py docs/proposal.md docs/proposal.docx
```

*Note: If `tools/compile_proposal.py` or `tools/proposal_docx_template.py` are missing, reconstruct them by reading their blueprints in `docs/tools/compile-engines.md`.*

## Files

| File | Role |
|:-----|:-----|
| `tools/proposal_docx_template.py` | Shared template module (fonts, colours, margins, helpers) |
| `tools/compile_proposal.py` | Markdown-to-DOCX compiler script |
| `docs/proposal.md` | Source markdown (structured with YAML frontmatter) |
| `docs/proposal.docx` | Generated output document |

## Dependencies

- Python 3.10+
- `python-docx` (installed via `uv run --with python-docx`)
- `pyyaml` (installed via `uv run --with pyyaml`)


---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-07-04*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
