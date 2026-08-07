---
okf_version: 0.1
type: agent_skill
title: pdf-text-extractor
name: pdf-text-extractor
description: Safely download external PDF files and extract their text content using curl and an isolated uv Python environment.
topics: [pdf, text, extraction, curl, uv]
timestamp: 2026-08-07T06:14:57Z
---
# PDF Text Extractor Workflow

Use this skill when you need to read, parse, or extract text from an external PDF document (e.g., government directives, manuals, or research papers) provided via a URL.

## Step 1: Download the PDF Safely
Standard web requests often fail on strict servers. Use `curl.exe` with a standard browser User-Agent (`-A`) and follow redirects (`-L`).

**Action:** Use the `run_command` tool to execute:
`powershell
curl.exe -A "Mozilla/5.0" -L "<PDF_URL>" -o "temp_document.pdf"
`

## Step 2: Extract Text using `uv` and `pypdf`
Do not attempt to install global Python packages. Instead, use `uv` to run a temporary, isolated Python script with the `pypdf` dependency.

**Action:** Use the `run_command` tool to execute:
`powershell
uv run --with pypdf python -c "
from pypdf import PdfReader
import sys
try:
    reader = PdfReader('temp_document.pdf')
    text = ''
    for page in reader.pages:
        text += page.extract_text() + '\n'
    print(text)
except Exception as e:
    print(f'Error reading PDF: {e}', file=sys.stderr)
    sys.exit(1)
"
`

## Step 3: Analyze and Clean Up
1. Read the standard output from the command to get the extracted text.
2. If the text is extremely long, parse it or summarize it as requested by the user.
3. Clean up the downloaded file by running `Remove-Item temp_document.pdf`.


---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-07-04*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
