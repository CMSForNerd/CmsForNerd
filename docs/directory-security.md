---
okf_version: 0.1
type: documentation
title: "CMSForNerd v4.0.0: 🛡️ Directory Security Lab Exercise"
description: "OKF-compliant documentation for directory-security.md."
resource: "file:///docs/directory-security.md"
timestamp: 2026-08-07T05:39:28Z
topics: [docs, directory, security, cmsfornerd, lab]
---
# CMSForNerd v4.0.0: 🛡️ Directory Security Lab Exercise

## Step 4: Directory Browsing Protection

### The Threat
If an attacker can browse `/includes/` or `/src/`, they can see all your PHP files, analyze your logic, and find vulnerabilities (Information Disclosure).

### Three-Layer Defense

**Layer 1: .htaccess (Apache)**
```apache
Options -Indexes
```

**Layer 2: index.html Files**
- Located in: `includes/`, `src/`, `tests/`, `themes/`, `contents/`, `images/`
- Shows 403 error page instead of file listings

**Layer 3: Nginx (Laravel Herd)**
```nginx
location ~ ^/(includes|src|tests|vendor)/ {
    deny all;
}
```

### Security Test
1. Navigate to: `http://localhost:8888/includes/`
2. **Expected**: 403 error page
3. **Not expected**: File list

### Files Created
- `.htaccess` (8 security layers)
- `index.html` templates in 6 directories
