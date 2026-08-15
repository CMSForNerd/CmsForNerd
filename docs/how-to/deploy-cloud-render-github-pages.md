---
okf_version: 0.1
type: guide
title: "🛠️ Cloud Hosting on Render.com and GitHub Pages"
description: "Deploy dynamic PHP 8.4 containers to Render using render.yaml or static-bake HTML for free GitHub Pages hosting."
resource: "file:///docs/how-to/deploy-cloud-render-github-pages.md"
timestamp: "2026-08-15T12:00:00Z"
topics: [cloud-deployment, render, github-pages, static-baking, deployment]
---

# 🛠️ Cloud Hosting on Render.com and GitHub Pages

Learn how to publish CmsForNerd to cloud hosting providers.

---

## ☁️ Deployment Methods

1. **Render.com Blueprint:**
   Connect your fork to Render Dashboard and choose **New > Blueprint**. Render automatically parses `render.yaml` and builds the service via `Dockerfile`.

2. **GitHub Pages Static Baking:**
   Compile dynamic PHP templates to flat HTML:
   ```bash
   php tools/bake-static-pages.php
   ```
   Outputs pre-rendered HTML, sitemaps, `.nojekyll`, and LLM indices to `build_static/` for automated GitHub Actions deployment.

---

*CmsForNerd Cloud Deployment Guide | DSOM Protocol 2026 | Harisfazillah Jamel (LinuxMalaysia)*
