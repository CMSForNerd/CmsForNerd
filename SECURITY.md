---
okf_version: 0.1
type: documentation
title: "Security Policy"
description: "OKF-compliant documentation for SECURITY.md."
resource: "file:///SECURITY.md"
timestamp: 2026-08-07T05:39:28Z
topics: [security, policy, maintenance, stance, supported]
---
# Security Policy

## Maintenance Stance
This repository is a downstream implementation maintained by **Harisfazillah Jamel (SongketMail Sdn Bhd)**. Our strategy for resiliency and sovereignty is to strictly follow the upstream baseline at [cmsfornerd/cmsfornerd](https://github.com/CMSForNerd/CmsForNerd).

## Supported Versions

We only provide support for the current master branch, which is regularly synchronised with the latest upstream stable releases.

| Version     | Supported          | PHP Requirement | Architectural Note |
| ----------- | ------------------ | --------------- | ------------------ |
| 4.0.0       | :white_check_mark: | >= 8.4          | Glassmorphism      |
| 3.5.x - 3.6 | :white_check_mark: | >= 8.4          | Zero-Global Arch   |
| < 3.5       | :x:                | < 8.4           | End of Life        |

## Front-End & PWA Security (v4.0.0+)

CMSForNerd v4.0.0 maintains the Progressive Web App (PWA) architecture with a new **Glassmorphism** visual layer. All Service Worker (`sw.js`) registrations strictly require **HTTPS**. The system is protected by strict Content Security Policy (CSP) nonces (injected via `includes/bootstrap.php`) and uses a **Zero-Global** architecture via the `Registry` class to prevent state pollution and Cross-Site Scripting (XSS).

## Reporting a Vulnerability

As this project prioritises synchronisation with the core engine:

1. **Upstream Vulnerabilities:** If you discover a security flaw in the core logic or dependencies, please report it directly to the upstream maintainers at [CMSForNerd Security](https://github.com/CMSForNerd/CmsForNerd/security/policy).
2. **Implementation Flaws:** If the vulnerability is specific to the `linuxmalaysia` (Harisfazillah Jamel) implementation or our specific environment configurations, please contact:
   - **Email:** linuxmalaysia@songketmail.org
   - **Protocol:** Please include a detailed description of the exploit and steps to reproduce.
   - **Response Time:** You can expect an initial acknowledgement within 48 hours.

## Disclosure Policy
We adhere to responsible disclosure. We ask that you do not share details of a suspected vulnerability publicly until we have had the opportunity to synchronise a patch from upstream or implement a local mitigation.
