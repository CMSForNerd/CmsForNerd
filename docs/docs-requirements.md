---
okf_version: 0.1
type: documentation
title: "Architecture Requirements v4.0.0 (RFC 2119)"
description: "OKF-compliant documentation for docs-requirements.md."
resource: "file:///docs/docs-requirements.md"
timestamp: 2026-08-07T05:39:28Z
topics: [docs, requirements, architecture, rfc, logic]
---
# Architecture Requirements v4.0.0 (RFC 2119)

This document defines the "Laws of the Project" using the requirement levels described in RFC 2119.

## Core Logic Requirements

1. **Strict Typing:** All PHP files **MUST** begin with `declare(strict_types=1);`.
2. **Namespace:** All core classes **MUST** reside within the `CmsForNerd\` namespace.
3. **Global Variables:** Code **MUST NOT** rely on the `global` keyword. All shared data **MUST** be accessed via the `Registry` class or `CmsContext` object.
4. **Dependencies:** Third-party libraries **SHOULD** be managed exclusively via Composer.

## Security Requirements

1. **Input:** The `page` parameter **MUST** be sanitized using `preg_replace` to prevent directory traversal.
2. **Output:** All user-provided strings **MUST** be escaped using `htmlspecialchars()` before being rendered in the theme.
3. **Sessions:** Sessions **SHOULD** be configured with `cookie_httponly` set to true.
4. **Security Disclosure:** The project **MUST** maintain a valid `security.txt` file at the path `/.well-known/security.txt`.
5. **Expiration:** The `Expires` field in `security.txt` **MUST NOT** be set to a date more than one year in the future.
6. **Response:** The organization **SHOULD** acknowledge vulnerability reports within 72 hours.

## AI Discoverability & Semantic Web

1. **JSON-LD:** All pages **MUST** output a valid JSON-LD block to assist AI tools in understanding the curriculum.
2. **Schema Types:** The `@type` **SHOULD** be "Course" for lab modules and "WebPage" for standard content.
3. **Dynamic Metadata:** JSON-LD content **MUST** be populated dynamically from `$CONTENT` variables.
4. **Microdata:** The `<html>` tag **MUST** include `itemscope` and `itemtype` attributes for immediate AI classification.
5. **Consistency:** The `itemtype` in the `<html>` tag **SHOULD** match the `@type` in JSON-LD to avoid confusing crawlers.
6. **Semantic Audit:** All new pages **MUST** pass the `composer audit-metadata` test before deployment.

## Documentation & Educational Requirements

1. **Modernization History:** For every major improvement or architectural change, the `contents/history-body.inc` file **MUST** be updated with a summary of the change.
2. **PHP Scaling:** Documentation MUST emphasize **PHP 8.4+ and PHP 9 Readiness**. Modern features should be presented as the new standard for longevity.
3. **Teaching Mode:** Comments added to code **SHOULD** explain the *why* (rationale) behind the implementation to serve as a learning resource for others.
