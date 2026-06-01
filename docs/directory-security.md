# CMSForNerd v4.0.0-alpha: 🛡️ Directory Security Lab Exercise

## Step 4: Directory Browsing Protection

### The Threat

If an attacker can browse `/includes/`, they can see all your PHP files, analyze your logic, and find vulnerabilities (Information Disclosure).

### Three-Layer Defense

**Layer 1: .htaccess (Apache)**

```apache
Options -Indexes
```

**Layer 2: index.html Files**

- Located in: `includes/`, `tests/`, `themes/`, `contents/`, `images/`
- Shows 403 error page instead of file listings

**Layer 3: Nginx (Laravel Herd)**

```nginx
location ~ ^/(includes|tests|vendor)/ {
    deny all;
}
```

### Security Test

1. Navigate to: `http://localhost:8888/includes/`
2. **Expected**: 403 error page
3. **Not expected**: File list

### Files Created

- `.htaccess` (8 security layers)
- `index.html` templates in 5 directories
