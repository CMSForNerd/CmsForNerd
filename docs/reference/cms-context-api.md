---
okf_version: 0.1
type: reference
title: "📋 API Reference: CmsContext Class and Factory Method"
description: "Complete API specification for the immutable CmsContext object carrying page metadata and nonces through the CmsForNerd render pipeline."
resource: "file:///docs/reference/cms-context-api.md"
timestamp: "2026-08-15T12:00:00Z"
topics: [cms-context, api-reference, immutability, factory-method, php84]
---

# 📋 API Reference: CmsContext Class and Factory Method

The `\CmsForNerd\CmsContext` object is an immutable data container that safely carries request parameters, theme metadata, and security nonces through the rendering pipeline without relying on global variables.

---

## 🏛️ Class Definition

```php
namespace CmsForNerd;

readonly class CmsContext
{
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

---

## 🛠️ Factory Method: `createCmsContext()`

The global helper function `createCmsContext()` instantiates and registers a `CmsContext` instance:

```php
function createCmsContext(
    array $content,
    string $pageName,
    string $themeName,
    string $cssPath,
    string $dataFile,
    string $nonce
): \CmsForNerd\CmsContext
```

### Parameters:
* **`$content` (array):** Array containing page metadata (`title`, `author`, `description`, `keywords`, `schemaType`).
* **`$pageName` (string):** Sanitized identifier of the current page (e.g., `'index'`, `'user-manual'`).
* **`$themeName` (string):** Active theme folder name (e.g., `'CmsForNerd'`).
* **`$cssPath` (string):** Path to the theme stylesheet.
* **`$dataFile` (string):** Path to the content body include file in `contents/`.
* **`$nonce` (string):** 128-bit cryptographic nonce string for Content Security Policy headers.

### Return Value:
An immutable instance of `\CmsForNerd\CmsContext`.

---

## 📖 Usage Example

```php
$ctx = createCmsContext(
    content: $content,
    pageName: 'user-manual',
    themeName: 'CmsForNerd',
    cssPath: 'themes/CmsForNerd/style.css',
    dataFile: 'contents/user-manual-body.inc',
    nonce: $nonce
);

// Accessing properties inside themes:
echo $ctx->content['title'];
echo $ctx->nonce;
```

---

*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-08-15*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
