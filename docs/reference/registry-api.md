---
okf_version: 0.1
type: reference
title: "📋 API Reference: Registry Class (Zero-Global State)"
description: "Complete API specification for the Registry static key-value store that replaces PHP global variables in CmsForNerd."
resource: "file:///docs/reference/registry-api.md"
timestamp: "2026-08-15T12:00:00Z"
topics: [registry, zero-global, state-management, api-reference, php84]
---

# 📋 API Reference: Registry Class (Zero-Global State)

The `\CmsForNerd\Registry` class provides a thread-safe, static key-value store designed to completely replace legacy PHP `$GLOBALS` and `global` keyword usages in CmsForNerd v4+.

---

## 🏛️ Class Methods

### `Registry::set(string $key, mixed $value): void`
Stores a value in the registry under the specified key.

```php
\CmsForNerd\Registry::set('site_name', 'CmsForNerd Laboratory');
```

---

### `Registry::get(string $key, mixed $default = null): mixed`
Retrieves a stored value by key. Returns `$default` if the key does not exist.

```php
$siteName = \CmsForNerd\Registry::get('site_name', 'Default Site');
```

---

### `Registry::has(string $key): bool`
Checks whether a given key exists in the registry.

```php
if (\CmsForNerd\Registry::has('site_name')) {
    // Key exists
}
```

---

### `Registry::clear(): void`
Clears all stored entries from the registry. Primarily used during unit test tear-down to ensure zero test contamination.

```php
\CmsForNerd\Registry::clear();
```

---

*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-08-15*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
