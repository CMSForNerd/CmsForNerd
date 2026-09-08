---
okf_version: 0.1
type: explanation
title: "⚡ Reactive UI (HTMX/Alpine.js) and WebAssembly (Wasm) Cryptography Architecture"
description: "Technical architectural breakdown of client-side Wasm cryptography, document processing, and HTMX/Alpine.js."
resource: "file:///docs/explanation/reactive-wasm-architecture.md"
timestamp: "2026-08-01T14:00:00Z"
topics: [htmx, alpinejs, webassembly, wasm, cryptography, zero-global]
---

# ⚡ Reactive UI (HTMX/Alpine.js) and WebAssembly (Wasm) Architecture

This document details CmsForNerd's integration strategy for lightweight client-side reactivity (HTMX and Alpine.js)
and high-performance **WebAssembly (Wasm)** client-side cryptographic and document processing while maintaining
CmsForNerd's immutable **Zero-Global PHP 8.4** engine architecture.

---

## 🏛️ Architectural Overview

CmsForNerd pairs server-side PHP 8.4 Front Controllers with client-side Wasm and lightweight reactive engines.
Heavy computational tasks—such as cryptographic hashing, key pair generation, PDF rendering, and document OCR—are
executed in isolated client-side WebAssembly sandboxes, while PHP 8.4 handles immutable routing and session security.

```
+-----------------------------------------------------------------------+
|                         Browser Client Sandbox                        |
|                                                                       |
|  +--------------------+   +-------------------+   +----------------+  |
|  |   Alpine.js State  |   |   HTMX Fragment   |   |   Wasm Module  |  |
|  |  (Local Micro-UI)  |   | (AJAX Fragment)   |   | (Crypto & Doc) |  |
|  +---------+----------+   +---------+---------+   +-------+--------+  |
+------------|------------------------|---------------------|-----------+
             |                        |                     |
             |                        v                     |
             |             X-Requested-With / AJAX          |
             |             Sends Pre-Verified Hashes       |
             v                        |                     v
+-------------------------------------v---------------------------------+
|                       Zero-Global PHP 8.4 Engine                      |
|                                                                       |
|  +------------------+     +-------------------+     +--------------+  |
|  | SecurityUtils    | --> | CmsContext        | --> | Pager Theme  |  |
|  | (Host & Nonce)   |     | (Immutable State) |     | Dispatcher   |  |
|  +------------------+     +-------------------+     +--------------+  |
+-----------------------------------------------------------------------+
```

---

## 📱 1. Lightweight Reactive UI (HTMX & Alpine.js)

Traditional Single-Page Applications (SPAs) require massive JavaScript runtimes, whereas Multi-Page Applications (MPAs)
can trigger disruptive full-page reloads. CmsForNerd resolves this by combining HTMX and Alpine.js:

1. **HTMX Fragment Routing:** HTMX issues asynchronous HTTP requests (`hx-get`, `hx-post`) with `HX-Request` headers.
   The PHP 8.4 backend evaluates the target route via `SecurityUtils::resolvePageName()` and returns isolated
   `-body.inc` fragments.
2. **Alpine.js Micro-Reactivity:** Component-level interactions (modals, dropdowns, reactive tabs) are managed
   declaratively using Alpine attributes (`x-data`, `x-show`, `x-on:click`).
3. **CSP Nonce Authorization:** Inline event listeners are prohibited by Content Security Policy. Script evaluation
   is explicitly authorized using server-generated CSP nonces (`$nonce`).

---

## 🔒 2. WebAssembly (Wasm) Client-Side Cryptography

WebAssembly executes compiled binary bytecode (Rust, C/C++) inside a memory-safe browser sandbox at near-native speed.

### Key Capabilities & Benefits:
- **Fast Local Processing:** Performs intensive hashing (SHA-256/SHA-512), key generation, and symmetric encryption
  directly on user hardware without backend network latency.
- **Enhanced Privacy & Zero-Knowledge:** Sensitive plaintext, passphrases, and private keys remain local to the
  user's browser device, eliminating transmission of unencrypted data over public networks.
- **Side-Channel Resistance (CT-Wasm):** Adheres to Constant-Time WebAssembly execution principles to prevent
  microarchitectural timing side-channels from leaking secret keys during cryptographic operations.

---

## 📄 3. Client-Side Document & Identity Processing

Complex document operations are offloaded from backend infrastructure directly onto client hardware:

1. **Heavy Local Operations:** Parses, renders, compresses, and resizes multi-megabyte PDFs, 4K identity photos,
   and optical character recognition (OCR) scans directly in browser memory.
2. **Drastic Server Load Reduction:** Offloads CPU-intensive document processing from backend Apache, PHP, and
   Podman container stacks.
3. **Immediate User Feedback:** Provides instant client-side validation and digital signature verification prior to
   optional network upload.

---

## 🛡️ 4. Zero-Global PHP 8.4 Backend Integration

Client-side Wasm and reactive interactions integrate cleanly with CmsForNerd's Zero-Global engine:

- **Immutable Context:** Client components submit pre-processed cryptographic hashes or verification tokens. PHP
  processes these inputs inside an immutable `CmsContext`.
- **Zero Global State:** Backend state is strictly managed through `\CmsForNerd\Registry` without using `global`
  keywords or mutable `$GLOBALS`.
- **Dual-View Support:** Responses adapt seamlessly to both Standard views and AMP views (`?view=amp`).

---

*CmsForNerd Reactive & Wasm Architecture Guide | DSOM Protocol 2026 | Harisfazillah Jamel (LinuxMalaysia)*
