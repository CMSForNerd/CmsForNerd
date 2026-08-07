---
okf_version: 0.1
type: governance_protocol
title: "📜 AI Cognitive Logging Protocol (Cross-AI Standard)"
description: "OKF-compliant documentation for AI-COGNITIVE-LOGGING-PROTOCOL.md."
resource: "file:///docs/governance/AI-COGNITIVE-LOGGING-PROTOCOL.md"
timestamp: 2026-07-04T09:40:04Z
topics: [docs, governance, cognitive, logging, protocol]
---
# 📜 AI Cognitive Logging Protocol (Cross-AI Standard)
# docs/AI-COGNITIVE-LOGGING-PROTOCOL.md

## 🎯 1. The Purpose of the Immutable Ledger

In the DSOM framework, the **Spatial Memory Palace** (`.agents/brain/`) contains distilled, human-readable summaries written by the AI and the Architect during the EOD Hibernation. However, human-readable summaries lack bit-perfect forensic detail (e.g., exact bash commands executed, regex applied, raw error outputs).

To solve this, we integrate the AI's native **Conversation Transcripts (System Logs)** into the Palace as an **Immutable Ledger**.

## 🌐 2. Cross-AI Compatibility Mandate

Because DSOM is provider-agnostic, you may be Gemini, Claude, OpenAI, or a local open-source LLM.
**Every AI provider has a different way of storing conversation history.**

If you are **not** Gemini:
1. You MUST document how your specific provider logs conversation history (e.g., where your transcripts are stored, or if you only use a cloud dashboard).
2. You MUST explain your logging mechanism to the User and to other AIs (like Gemini) by creating a section in your relevant `closet.md` or by updating this document to include your specific format.
3. You MUST establish a way to map your specific session/conversation ID to the Palace.

## 🔗 3. How to Map Logs to the Palace

When updating a `closet.md` during the EOD Hibernation Ritual, the active AI must inject its specific Session/Conversation ID and Log Path at the top of the file.

### Standard Format Header

```markdown
# 🗄️ Spatial Closet: [Topic]
**Primary Conversation ID:** [Insert Unique Session/Conversation ID Here]
**Log Path / Reference:** [Insert Absolute Path to Transcript OR Cloud URL]

## 📌 Context
[The distilled human-readable summary...]
```

## 🧠 4. Current Implementations

### Implementation A: Google DeepMind Gemini (Antigravity)
*   **Mechanism**: Gemini automatically generates chronological JSONL transcripts for every conversation.
*   **Log Location**: `<appDataDir>\brain\<conversation-id>\.system_generated\logs\transcript.jsonl`
*   **Data Stored**: Exact user prompts (`USER_INPUT`), terminal commands executed (`tool_calls`), and thought processes.
*   **How it maps**: We drop the Gemini `conversation-id` into the `closet.md`. If a future agent needs to know exactly what bash command Gemini ran to fix a bug, they grep that specific `transcript.jsonl` file.

### Implementation B: [Other AI Provider]
*(If you are Claude, ChatGPT, or another agent reading this: You must fill out this section for your own architecture when you encounter it!)*

---
*Maintained by the DSOM Engineering Team | Cross-AI Logging Standard v1.0*


---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-07-04*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
