---
okf_version: 0.1
type: reference
title: "📋 Registry Static Key-Value Store API"
description: "API reference for the Registry class providing Zero-Global static state management."
resource: "file:///docs/reference/registry-api.md"
timestamp: "2026-08-15T12:00:00Z"
topics: [registry, zero-global, state-management, api-reference, php84]
---

# 📋 Registry Static Key-Value Store API

The `\CmsForNerd\Registry` static class replaces legacy `$GLOBALS` usage.

---

## 🏛️ Methods

* `Registry::set(string $key, mixed $value): void`
* `Registry::get(string $key, mixed $default = null): mixed`
* `Registry::has(string $key): bool`
* `Registry::clear(): void`

---

*Registry API Reference Specification | DSOM Protocol 2026 | Harisfazillah Jamel (LinuxMalaysia)*
