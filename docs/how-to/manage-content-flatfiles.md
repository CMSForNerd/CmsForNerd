---
okf_version: 0.1
type: guide
title: "🛠️ How-To: Manage Flat-File Content and Git Workflows"
description: "Learn how CmsForNerd manages content as flat HTML files in contents/ with zero database overhead and full Git version control."
resource: "file:///docs/how-to/manage-content-flatfiles.md"
timestamp: "2026-08-15T12:00:00Z"
topics: [content-management, flat-files, git, workflow, html]
---

# 🛠️ How-To: Manage Flat-File Content and Git Workflows

CmsForNerd stores 100% of its content in flat HTML files in the `contents/` directory. There are no SQL databases, connection pools, or ORMs.

---

## 📁 Content Directory Structure

Content files correspond directly to root controller page names:

```text
contents/
├── index-body.inc          # Home page content
├── user-manual-body.inc    # Local user manual content
├── installation-body.inc   # Installation guide content
├── about-body.inc          # About page content
└── services-body.inc       # Services page content
```

---

## 🌿 Git Version Control Workflow

Because content is stored in plain text files, your entire website content history is tracked natively by Git:

1. Edit content file locally:
   ```bash
   nano contents/about-body.inc
   ```
2. Inspect content diffs:
   ```bash
   git diff contents/about-body.inc
   ```
3. Commit and push content changes:
   ```bash
   git add contents/about-body.inc
   git commit -m "docs: update about page content"
   git push origin main
   ```

---

## 🔒 File Security & Path Sanitization

The `CmsContext` bootstrap automatically validates requested page names using `SecurityUtils::resolvePageName()`. This sanitizes user inputs, preventing Path Traversal (CWE-22) attempts such as `../../etc/passwd`.

---

*Deep State of Mind (DSOM) For My AI Protocol | CmsForNerd Flat-File Content Management | Harisfazillah Jamel (LinuxMalaysia)*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
