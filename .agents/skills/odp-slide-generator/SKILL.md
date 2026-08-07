---
okf_version: 0.1
type: agent_skill
title: odp-slide-generator
name: odp-slide-generator
description: Generates highly styled 3-column Corporate Presentation slides (PPTX/ODP format) based on a specific HTML/CSS theme using python-pptx.
topics: [presentation, pptx, odp, python-pptx, slides]
timestamp: 2026-08-07T05:39:28Z
---
# odp-slide-generator

## When to use this skill
Use this skill when you need to generate a LibreOffice Impress / Microsoft PowerPoint presentation that strictly adheres to the custom 3-column corporate theme (deep blue, vibrant orange accents, grid layout).

## Format Rules

The slide generator (`tools/compile_3col_slides.py`) expects a highly specific Markdown format to populate the complex geometric shapes.

1. Create a markdown file with the following exact structure:
   - `# [Main Slide Title]` (Must be Top Level)
   - `## [Slide Subtitle]` (Optional Subtitle)
   - `### [Column Label]` (e.g. Stage 01: Source)
   - `**[Box Header Text]**` (e.g. Direct Extraction)
   - `- [Bullet point 1]`
   - `- [Bullet point 2]`
   - `> "[Footer Quote Text]"` (Optional footer quote block)

### Example Markdown:
```markdown
# Achieving Zero-Downtime Cutover
## Near-Real-Time Delta Sync (CDC) Architecture

### Stage 01: Source
**Direct Extraction**
- Native connection to PostgreSQL Write-Ahead Logs (WAL).
- Row-level mutation tracking without database triggers.

### Stage 02: Transport
**Streaming Fabric**
- Apache Kafka (KRaft Mode) for high-throughput event bus.
- Debezium Connect for automated change capture.

### Stage 03: Target
**Instant Mirroring**
- Near-instant transaction mirroring to Percona target.
- Continuous data validation during sync phase.

> "By streaming row-level mutations in real-time, we compress the final production cutover window."
```

## Execution

1. Write the target markdown file (e.g., `docs/slide_data.md`).
2. Run the compiler using `uv`:
   ```powershell
   uv run --with python-pptx python tools/compile_3col_slides.py docs/slide_data.md docs/my_presentation.pptx
   ```
3. The resulting `.pptx` file is 100% natively compatible with LibreOffice Impress and will retain all shape geometries, exact RGB colors, and custom fonts.


---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-07-04*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
