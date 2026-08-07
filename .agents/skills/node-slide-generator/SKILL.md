---
okf_version: 0.1
type: agent_skill
title: node-slide-generator
name: node-slide-generator
description: Generates a PowerPoint presentation from a markdown outline using Node.js and pptxgenjs.
topics: [node, pptx, slides, presentation, generator]
timestamp: 2026-08-07T05:39:28Z
---
# node-slide-generator

Use this skill when the user asks to compile, generate, or format PowerPoint presentation slides using Node.js.

## Instructions
1. Ensure the source markdown outline exists (e.g., `docs/slides_outline.md` or `docs/client_name/client_slides.md`).
2. Run the Node.js compiler script passing the input markdown file and the desired output pptx file.
   Command: `node tools/compile_node_slides.js <input.md> <output.pptx>`
   Example: `node tools/compile_node_slides.js docs/slides_outline.md docs/Node_Migration_Presentation.pptx`
3. Verify the output was created successfully.


---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-07-04*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
