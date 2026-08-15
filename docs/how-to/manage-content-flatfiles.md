---
okf_version: 0.1
type: guide
title: "🛠️ Flat-File Content Management and Version Control"
description: "Store, edit, and version control flat HTML body fragments in contents/ using Git workflows."
resource: "file:///docs/how-to/manage-content-flatfiles.md"
timestamp: "2026-08-15T12:00:00Z"
topics: [content-management, flat-files, git, workflow, html]
---

# 🛠️ Flat-File Content Management and Version Control

In CmsForNerd, 100% of website content is stored in plain text HTML files inside `contents/`.

---

## 🌿 Plain Text Git Workflow

1. Modify any fragment file: `contents/about-body.inc`
2. Track modifications using Git:
   ```bash
   git status
   git diff contents/about-body.inc
   git commit -am "docs: update about fragment"
   ```

---

*CmsForNerd Content Management Guide | DSOM Protocol 2026 | Harisfazillah Jamel (LinuxMalaysia)*
