---
okf_version: 0.1
type: skill
title: "Static Page Baking and PWA Routing"
name: "static-baking-and-routing"
description: "Instructions for baking dynamic PHP sites into static HTML, deploying to GitHub Pages, and handling seamless SPA/PWA routing."
topics: [static-baking, github-pages, pwa, router, nojekyll]
timestamp: 2026-08-01T09:00:00Z
---

# 🌐 Static Page Baking and PWA Routing

## Purpose
This skill governs the static generation (baking) of dynamic PHP pages, its secure deployment pipeline to GitHub Pages, and the frontend SPA/PWA routing compatibility that bridges static hosting with dynamic fallback rendering.

## When to use this skill
Use this skill when configuring static builds, modifying the static baking script (`tools/bake-static-pages.php`), updating GitHub Actions workflows, or editing PWA-related scripts (e.g., `assets/pwa/router.js`).

## Guidelines & Best Practices

### 1. Static Baking with Jekyll Bypass & SEO Copying
To deploy CMSForNerd on GitHub Pages as a pre-baked static site while bypassing the default Jekyll compilation:
- An empty `.nojekyll` file must be automatically generated in the root of the output/build directory (typically `build_static/`) by `tools/bake-static-pages.php`.
- **Copying Static SEO & LLM Files:** To guarantee that all static SEO and context files are deployed to GitHub Pages, the static page baking script `tools/bake-static-pages.php` copies `llms.txt`, `llms-full.txt`, and `llms.xml` from the repository root to `build_static/`. It also copies `sitemap.txt`, `sitemap.xml`, `rss.xml`, `ror.xml`, and `schema-org.json` to `build_static/`.

### 2. Static Build Workflow to GitHub Pages
The static deployment is orchestrated in `.github/workflows/static-build.yml`:
- The runner launches a local PHP server and executes `tools/bake-static-pages.php`.
- The script generates flat static `.html` files inside `build_static/` from dynamic `.php` controllers.
- Assets (`assets/`, `themes/`, `images/`) and root-level static config files (`manifest.json`, `sw.js`, `labels.rdf`, `robots.txt`, `favicon.ico`, `sitemap.xml`) are copied over to the output directory.
- The workflow uploads the build artifact specifically from the `build_static/` folder.
- **Rule of Cleanliness:** `build_static/` must be ignored in `.gitignore`. No compiled static `.html` files must be committed to the repository root. The root `index.html` is strictly reserved as a minimal redirection fallback to `index.php`.

### 3. Automated SEO Suite Generator & Page Registration
- **Automated SEO Generator:** The repository includes an automated SEO and sitemap suite generator script `tools/generate-seo-files.php` which programmatically compiles lists of PHP routes and GitBook Markdown files to write standard `sitemap.txt`, `sitemap.xml`, `rss.xml`, `ror.xml`, and `schema-org.json` files to the repository root.
- **Robots.txt Sitemap Declarations:** The site's `robots.txt` is updated with absolute `Sitemap` declarations pointing to both standard XML and raw TXT sitemaps across the production domain (`www.linuxmalaysia.com`) and GitHub Pages deployment domain (`linuxmalaysia.github.io`).
- **Registering a New Page:** To add or register a new page in CMSForNerd, create a root-level controller file `[page-name].php` (defining `$content` metadata and initializing `CmsContext`) and a corresponding content body file `contents/[page-name]-body.inc`. The new page is dynamically indexed by `tools/generate-seo-files.php` and compiled to static HTML by `tools/bake-static-pages.php`.
- **GitBook Publishing:** The project is configured for GitBook publishing via `.gitbook.yaml` in the root (specifying `version: "1.0.0"`, root `./`, and structure mapping) and uses root `SUMMARY.md` as the primary table of contents sidebar navigation.

### 4. SPA/PWA Router Integration
The client-side SPA/PWA router (`assets/pwa/router.js`) must be fully compatible with both dynamic server-side fragments and full static pages:
- Use `DOMParser` to parse fetched HTML responses.
- If a `<main>` element is detected in the response (which is the case for pre-baked static pages on GitHub Pages), extract and inject *only* its inner HTML content into the workspace, and update `document.title` from the response.
- If no `<main>` element is found, fall back to injecting the raw response as-is (enabling backward compatibility with dynamic AJAX page fragments sent by a dynamic PHP server).


---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-08-05*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
