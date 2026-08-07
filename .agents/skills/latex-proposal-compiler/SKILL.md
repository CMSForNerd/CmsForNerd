---
okf_version: 0.1
type: agent_skill
title: latex-proposal-compiler
name: latex-proposal-compiler
description: Compiles a markdown proposal document into a professionally formatted PDF using Pandoc and XeLaTeX. Contains crucial fixes for TOC generation, double-numbering, and table layout bugs.
topics: [latex, pandoc, pdf, proposal, document]
timestamp: 2026-08-07T05:39:28Z
---
# latex-proposal-compiler

When the user requests to compile a proposal to PDF using LaTeX/Pandoc, follow these strict guidelines to prevent silent compilation failures:

## 1. Pre-flight Cleanup (CRITICAL)
- xelatex reads auxiliary files (.toc, .aux) from the **current working directory** (project root) *before* reading from the -output-directory.
- If an older compilation was run manually without an output directory, stale files in the root will silently hijack the build, resulting in a broken or missing Table of Contents.
- **Always run this cleanup before compiling:**
  `powershell
  Remove-Item *.aux, *.toc, *.log, *.out -ErrorAction SilentlyContinue
  Remove-Item docs/proposal/<client>/*.aux, docs/proposal/<client>/*.toc, docs/proposal/<client>/*.out -ErrorAction SilentlyContinue
  `

## 2. Compilation Command
- Use the dedicated Node.js wrapper script which automatically handles Pandoc heading shifts, strips problematic \LTcaptype wrappers, and executes the required two-pass XeLaTeX compilation.
- **Command**: `node tools/compile_latex_proposal.js <CLIENT_NAME>` (e.g., PPZ)
- If `tools/compile_latex_proposal.js` or `tools/compile_proposal.py` are missing, reconstruct them using the blueprint in `docs/tools/compile-engines.md`.

## 3. Pandoc Table Alignment (Overfull \hbox)
- If a Markdown table renders with broken alignments or overlapping text in the PDF, it is because Pandoc's longtable generator falls back to standard l c c c columns when widths aren't explicitly declared.
- **Fix**: Modify the *Markdown table* to balance character lengths. Shorten overly long row labels (e.g., "Ph 1: Discovery" instead of "Phase 1: Discovery & Planning") and lengthen short column headers (e.g., "Week 1" instead of "W1"). This forces Pandoc to allocate balanced column widths natively.

## 4. Double Section Numbering
- If section headers display double numbers (e.g., 1 1. Executive Summary), it means Pandoc's converted \section{...} tags are clashing with the manual numbers in the Markdown.
- **Fix**: Ensure \setcounter{secnumdepth}{-1} is present in docs/templates/proposal_preamble.tex to disable LaTeX auto-numbering, while keeping \setcounter{tocdepth}{4} so the sections still correctly populate the TOC.


---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-07-04*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
