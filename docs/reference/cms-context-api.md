---
okf_version: 0.1
type: reference
title: "📋 CmsContext API Specification"
description: "API reference for the immutable CmsContext object and createCmsContext factory helper method."
resource: "file:///docs/reference/cms-context-api.md"
timestamp: "2026-08-15T12:00:00Z"
topics: [cms-context, api-reference, immutability, factory-method, php84]
---

# 📋 CmsContext API Specification

The `\CmsForNerd\CmsContext` readonly object carries request state and nonces safely through the render pipeline.

---

## 🏛️ Class & Factory Signature

```php
namespace CmsForNerd;

readonly class CmsContext {
    public function __construct(
        public array $content,
        public string $pageName,
        public string $themeName,
        public string $cssPath,
        public string $dataFile,
        public string $nonce
    ) {}
}
```

Instantiated via `createCmsContext(...)`.

---

*CmsContext API Reference Specification | DSOM Protocol 2026 | Harisfazillah Jamel (LinuxMalaysia)*
