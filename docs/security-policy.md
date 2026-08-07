---
okf_version: 0.1
type: documentation
title: "Security Policy (SECURITY.md)"
description: "Vulnerability reporting policy and defensive standards for CMSForNerd v4.0.0."
resource: "file:///docs/security-policy.md"
timestamp: "2026-07-27T12:00:00Z (Planned Handover Date)"
topics: [docs, security, policy, responsible, disclosure]
---
# 🛡️ Security Policy (SECURITY.md)

This document outlines the procedures for reporting and managing security vulnerabilities within the **CMSForNerd v4.0.0** project.

## Responsible Disclosure
We value the work of security researchers. To encourage responsible disclosure, we promise not to pursue legal action against researchers
who:
* Provide us with reasonable time to fix a bug before public disclosure.
* Avoid privacy violations and data destruction.
* Do not interrupt our laboratory services.

## Scope
The following components are in scope for security research:
* **Core Logic:** `includes/SecurityUtils.php`, `src/CmsContext.php`, `src/Registry.php`.
* **Path Security:** Protection against directory traversal in routing and `SecurityUtils::resolvePageName()`.
* **XSS Protection:** Correct implementation of CSP Nonces and dynamic XML/HTML output escaping using `SecurityUtils::escapeHtml()`.
* **Host Header Protection:** Trusted-host validation and sanitized base URL detection in `SecurityUtils::getSafeBaseUrl()`.

## Reporting Channel
Send all reports to `security@cmsfornerd.test` (example only; for production please contact the local systems administrator or your
organization's IT security team). Use PGP encryption if the vulnerability is critical.


---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-07-27 (Planned Handover Date)*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
