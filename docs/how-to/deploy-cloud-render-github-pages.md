---
okf_version: 0.1
type: guide
title: "🛠️ How-To: Deploy CmsForNerd to Render.com and GitHub Pages"
description: "Deploy CmsForNerd to Render.com with a one-click render.yaml blueprint, or compile flat HTML static files for hosting on GitHub Pages."
resource: "file:///docs/how-to/deploy-cloud-render-github-pages.md"
timestamp: "2026-08-15T12:00:00Z"
topics: [cloud-deployment, render, github-pages, static-baking, deployment]
---

# 🛠️ How-To: Deploy CmsForNerd to Render.com and GitHub Pages

This guide explains how to deploy **CmsForNerd v4.3.0** to cloud providers:
1. **Render.com:** Dynamic PHP 8.4 containerized web service deployment.
2. **GitHub Pages:** Static HTML baking and hosting for zero-cost static publishing.

---

## ☁️ Option 1: Render.com Cloud Deployment

CmsForNerd includes a root `render.yaml` infrastructure-as-code blueprint configured for Docker web services.

### Steps to Deploy:
1. Fork or push the CmsForNerd repository to your GitHub/GitLab account.
2. Log in to [Render Dashboard](https://dashboard.render.com).
3. Click **New + > Blueprint**.
4. Connect your repository containing `render.yaml`.
5. Render will automatically detect `render.yaml`, parse the build specification, and build the Docker container using `Dockerfile`.
6. Once deployed, Render provides a secure `https://<your-app>.onrender.com` URL.

---

## 📄 Option 2: Static Baking for GitHub Pages

CmsForNerd features a static baking utility (`tools/bake-static-pages.php`) that compiles every PHP page route into standard HTML files and copies sitemaps, feeds, and LLM indices into `build_static/`.

### Steps to Bake & Deploy:
1. Open terminal in repository root and run static page baker:
   ```bash
   php tools/bake-static-pages.php
   ```
2. The script compiles all routes into `build_static/`:
   * `index.html`
   * `user-manual.html`
   * `installation.html`
   * `sitemap.xml`, `sitemap.txt`, `rss.xml`, `ror.xml`, `schema-org.json`
   * `llms.txt`, `llms-full.txt`, `llms.xml`
   * `.nojekyll` (prevents GitHub Pages from filtering underscore/inc directories)
3. Commit and push the `build_static/` output or use the automated GitHub Actions workflow (`.github/workflows/static-build.yml`) to publish directly to GitHub Pages.

---

*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-08-15*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
