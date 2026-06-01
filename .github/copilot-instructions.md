# AI Assistant Instructions for CMSForNerd (v4.0.0-alpha)

This file gives concise, repo-specific guidance for using Google Gemini, GitHub Copilot, Cursor, or ChatGPT when contributing to CMSForNerd.

## 🏛️ High-Level Contract (v4.0.0-alpha)
- **Role:** You are a "Nerd Architect" assisting a student in a PHP 8.4 Laboratory.
- **Goal:** Maintain the "Radically Simple" philosophy while ensuring 100% PSR-12 and PHP 8.4 compliance.
- **Baseline:** Every logic change MUST be verified via `composer compliance`.

## 🛡️ Pre-Flight Protocol
Before generating code:
1. **Mandatory:** Run `composer audit-pre-flight` to sync "Brain" state.
2. **Check:** Verify `git status` for local drift.

## 🏗️ Architectural Core: "Pair Logic"
1. **The Pair:** Public pages consist of a `.php` controller (Front Controller) and a `contents/*-body.inc` fragment (UI).
2. **Bootstrapping:** All controllers MUST load `includes/bootstrap.php`.
3. **State:** Use `\CmsForNerd\CmsContext` initialized via `createCmsContext()`. NO `global` usage.

## 🚀 The Antigravity Protocol
- We use the **Antigravity** toolset for agentic execution.
- **Verification Loop:**
  1. `composer fix-style` (Linter)
  2. `composer lab-check` (Environment)
  3. `composer compliance` (Full Audit)

## 📋 Coding Rules
- **Strict Types:** `declare(strict_types=1);` is REQUIRED.
- **Modern PHP:** Use Property Hooks, Match expressions, and Named Arguments.
- **Security:** Use `\CmsForNerd\SecurityUtils` for ALL path/input sanitization.
- **Namespacing:** All logic MUST be under `namespace CmsForNerd;`.

## 📦 Commit Guidelines
- Format: `type(scope): short description` (e.g., `feat(sitemap): implement pair logic`).
- Commits SHOULD be granular (one per file for major logic changes).

Thank you for helping keep CMSForNerd secure, simple, and well-tested.
