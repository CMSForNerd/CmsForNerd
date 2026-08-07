---
okf_version: 0.1
type: agent_skill
title: node-proposal-formatter
name: node-proposal-formatter
description: Compiles a markdown proposal document into a professionally formatted DOCX file using Node.js and the docx npm package.
topics: [node, docx, proposal, document, formatter]
timestamp: 2026-08-07T05:39:28Z
---
# node-proposal-formatter

Use this skill when the user asks to compile or generate a DOCX proposal using the Node.js compiler, or when updating a document formatted via Node.

## Instructions
1. Ensure the source markdown file exists (e.g., `docs/proposal.md` or `docs/client_name/client_proposal.md`).
2. Run the Node.js compiler script passing the input markdown file and the desired output docx file.
   Command: `node tools/compile_node_proposal.js <input.md> <output.docx>`
   Example: `node tools/compile_node_proposal.js docs/proposal.md docs/Node_Proposal.docx`
3. Verify the output was created successfully.


---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-07-04*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
