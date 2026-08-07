---
okf_version: 0.1
type: documentation
title: "Procedural Automation: Byte-Capped Execution Framework"
description: "Technical layout and deployment model of the DSOM Token Calculator Skill."
timestamp: 2026-07-18T22:54:00+08:00
topics: [docs, governance, byte, capped, execution]
---
# Procedural Automation: Byte-Capped Execution Framework

## Abstract

Memory load management and token inflation pose critical failure risks within complex multi-agent setups. If context sizes grow unchecked, processing cycles fail due to truncated payloads and context drift. This article breaks down the technical layout and deployment model of the **DSOM Token Calculator Skill** (`dsom-token-calculator`). This skill acts as a localized gatekeeper that programmatically checks file and workspace sizes prior to cross-thread mutations.

---

## 1. Skill Architecture Mapping

Under the Deep State of Mind framework, passive procedural scripts are banned. Instead, code execution rules must reside within an OKF-compliant structure.

```
.agents/skills/dsom-token-calculator/
├── SKILL.md                          # Declarative Instructions & Guardrails
└── scripts/
    └── calculate-tokens.py           # Self-contained Token Engine
```

### 1.1 Declarative Guardrails (`SKILL.md`)

With this specification, the agent is restricted from making blind context extensions. The skill binds the model's output mechanics to strict token boundaries:

```markdown
---
okf_version: 0.1
type: procedural_skill
title: "Procedural Specification: DSOM Token Calculator"
description: "Calculates token counts via tiktoken in isolated uv Python runspaces."
resource: "file:///.agents/skills/dsom-token-calculator/SKILL.md"
timestamp: 2026-07-18T14:52:39Z
---

# Operational Enforcements:
- Trigger this skill dynamically before any output loop that handles extensive datasets or raw configurations.
- If payload calculations return totals greater than **4000 tokens**, the system must block raw screen serialization and switch to targeted `view_file` calls or chunked reading.
```

---

## 2. Tokenizer Script Engine Implementation

By configuring an isolated, on-demand execution runtime via Python `uv`, the script operates without modifying systemic python system frameworks. This mechanism eliminates package clutter and mitigates dependencies errors.

```python
#!/usr/bin/env python3
# -*- coding: utf-8 -*-
# ---
# okf_version: 0.1
# type: executable_script
# title: "Core Token Counter Runtime"
# license: "GNU General Public License v3.0"
# ---

import os
import sys
import tiktoken

def count_tokens_in_file(filepath: str, model: str = "gpt-4") -> int:
    """Reads file content and calculates exact token footprints via tiktoken."""
    try:
        with open(filepath, "r", encoding="utf-8", errors="ignore") as f:
            text = f.read()
        encoding = tiktoken.encoding_for_model(model)
        return len(encoding.encode(text))
    except Exception as e:
        print(f"[ERROR] Failed to read target resource {filepath}: {e}")
        return 0

def scan_path(target_path: str):
    """Parses and computes tokens for explicit files or workspace directory trees."""
    if not os.path.exists(target_path):
        print(f"[ERROR] Target path '{target_path}' does not exist in active workspace.")
        sys.exit(1)

    total_tokens = 0
    if os.path.isfile(target_path):
        tokens = count_tokens_in_file(target_path)
        print(f"[FILE] {target_path}: {tokens} tokens")
        total_tokens = tokens
    elif os.path.isdir(target_path):
        print(f"[DIR] Initiating workspace sweep across target: {target_path}")
        for root, _, files in os.walk(target_path):
            for file in files:
                # Restrict scanning scope to plaintext assets to prevent binary processing overhead
                if not file.endswith((".py", ".md", ".txt", ".json", ".yml", ".yaml", ".sh", ".ps1", ".css", ".html", ".js")):
                    continue
                filepath = os.path.join(root, file)
                tokens = count_tokens_in_file(filepath)
                if tokens > 0:
                    print(f" [ASSET] {filepath}: {tokens} tokens")
                    total_tokens += tokens

    print("-" * 60)
    print(f"[SUMMARY] TOTAL COMPUTED WORKSPACE TOKENS: {total_tokens}")

if __name__ == "__main__":
    if len(sys.argv) < 2:
        print("[ERROR] Missing target parameter path.")
        print("Usage: uv run --with tiktoken calculate-tokens.py [TARGET_PATH]")
        sys.exit(1)

    scan_path(sys.argv[1])
```

---

## 3. Operational Deployment Model

### Runtime Execution Command

By routing execution parameters directly via the `uv` toolchain, package requirements are resolved entirely in memory during run initialization:

```bash
uv run --with tiktoken .agents/skills/dsom-token-calculator/scripts/calculate-tokens.py [TARGET_PATH]
```

### Self-Audit Loop Workflow

This programmatic loop enforces predictable context scaling across distributed subagent threads:

```text
[AGENT TRIGGERED TASK]
          │
          ▼
[Execute dsom-token-calculator via uv]
          │
          ├──► Token Count < 4000  ──► [Commit output directly to Main Thread]
          │
          └──► Token Count >= 4000 ──► [Engage Progressive Chunking]
                                       • Halt raw text stream
                                       • Cache payload to filesystem
                                       • Emit metadata status header only
```

With this integration applied, subagents running automated diagnostic sweeps across multi-node infrastructures can inspect log sizes locally. This ensures they summarize data blocks before passing heavy text streams back to the primary deployment thread.

---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-07-18*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
