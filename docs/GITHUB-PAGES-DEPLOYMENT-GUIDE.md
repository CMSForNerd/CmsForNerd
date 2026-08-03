---
okf_version: 0.1
type: documentation
title: "GitHub Pages Deployment Guide"
description: "Comprehensive guide for static baking and deploying CmsForNerd to GitHub Pages while bypassing Jekyll."
resource: "file:///docs/GITHUB-PAGES-DEPLOYMENT-GUIDE.md"
topics: [github-pages, deployment, static, jekyll, nojekyll]
timestamp: 2026-08-01T15:00:00Z
---
# GitHub Pages Static Deployment Guide

This guide describes how to deploy **CmsForNerd** onto GitHub Pages (view our [Live Demo](https://cmsfornerd.onrender.com/index.php) or access via our [Context7 MCP & LLM standard link](https://context7.com/cmsfornerd/cmsfornerd/llms.txt?tokens=10000)) by baking the dynamic PHP codebase into 100% self-contained static HTML pages.

---

## 🏛️ Architecture Overview

While CmsForNerd is designed as a dynamic, database-free PHP application, it features an advanced **Static Page Baker** engine that crawls local routes and pre-renders them into a fully optimized, static distribution folder. This enables developers to host the laboratory CMS on static hosting platforms like **GitHub Pages** with zero hosting costs.

The compilation pipeline is managed as follows:
- **Local Server**: A local PHP server is spawned in the background during the build stage.
- **Bake Script (`tools/bake-static-pages.php`)**: Iterates over all PHP templates, crawls them using cURL, translates all internal `.php` navigation links to `.html` references, converts absolute asset references to relative paths, and outputs the result into a clean, isolated directory (`build_static/`).
- **Deployment Artifact**: The self-contained `build_static/` directory is uploaded directly to GitHub Pages, bypassing the need for a live PHP backend.

---

## 🚫 Jekyll Integration & The `.nojekyll` Mandate

By default, GitHub Pages processes all uploaded files using the **Jekyll** static site generator. While Jekyll is excellent for markdown-based blogs, running it on pre-baked HTML distributions introduces major operational hazards:

1. **Liquid Syntax Conflicts**: Jekyll parses double curly braces `{{ ... }}` and percentage symbols `{% ... %}` as Liquid template tags. Because our glassmorphic CSS styles, progressive web apps, and web workers utilize similar syntax, Jekyll compilation frequently fails or corrupts our files.
2. **Ignored Directories**: Jekyll automatically ignores any directory starting with a dot (such as `.well-known/` which contains critical security files like `security.txt`) or directories starting with an underscore.
3. **Broken Subdirectory Routing**: On subdirectory hosting (e.g., `https://username.github.io/repository-name/`), Jekyll's routing rules can conflict with our SPA/PWA vanilla JS router.

### 💡 The Solution: Bypassing Jekyll

To prevent Jekyll from interfering with the pre-baked HTML, CSS, and JS, our static page baker automatically creates an empty **`.nojekyll`** file at the root of the output build directory (`build_static/`).

This simple, empty file serves as an explicit instruction instructing the GitHub Pages server to completely bypass Jekyll processing and serve our high-performance static files exactly as they are baked.

---

## 🚀 The Automated GHA Workflow (`static-build.yml`)

The deployment process is fully automated via the GitHub Actions workflow located in `.github/workflows/static-build.yml`.

```yaml
name: Bake PHP to Static HTML
on:
  push:
    branches: [ "master" ]

jobs:
  build:
    runs-on: ubuntu-latest
    permissions:
      contents: read
    steps:
      - name: Checkout Code
        uses: actions/checkout@v4

      - name: Setup PHP 8.4
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.4'
          extensions: mbstring, curl

      - name: Install dependencies
        run: composer install --prefer-dist --no-progress --ignore-platform-reqs

      - name: Start Local PHP Server
        run: php -S 127.0.0.1:8000 &

      - name: Staticize (Bake) All PHP Pages
        run: |
          sleep 3
          php tools/bake-static-pages.php build_static

      - name: Upload Static Files
        uses: actions/upload-pages-artifact@v3
        with:
          path: 'build_static'

  deploy:
    needs: build
    runs-on: ubuntu-latest
    permissions:
      contents: read
      pages: write
      id-token: write
    environment:
      name: github-pages
      url: ${{ steps.deployment.outputs.page_url }}
    steps:
      - name: Deploy to GitHub Pages
        id: deployment
        uses: actions/deploy-pages@v4
```

---

## 🛠️ Step-by-Step GitHub Pages Activation

To activate automated static baking and publishing on your repository:

1. **Push to master**: Ensure your latest changes are merged into the `master` branch.
2. **Configure Pages Source**:
   - Navigate to your repository on GitHub.
   - Click on **Settings** -> **Pages**.
   - Under **Build and deployment**, change the **Source** dropdown from "Deploy from a branch" to **GitHub Actions**.
3. **Trigger Workflow**: Pushing to `master` will now automatically trigger the "Bake PHP to Static HTML" workflow, baking your site and publishing it seamlessly.

---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-08-01*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
