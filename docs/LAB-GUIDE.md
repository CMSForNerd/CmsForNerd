---
okf_version: 0.1
type: documentation
title: "🎓 CMSForNerd Laboratory Guide (v4.0.0-alpha)"
description: "OKF-compliant documentation for LAB-GUIDE.md."
resource: "file:///docs/LAB-GUIDE.md"
timestamp: 2026-08-07T05:39:28Z
topics: [docs, lab, cmsfornerd, laboratory, alpha]
---
# 🎓 CMSForNerd Laboratory Guide (v4.0.0-alpha)

Welcome to the CmsForNerd Developer Laboratory. This guide helps you understand modern PHP 8.4 architecture through hands-on exercises, focusing on the **Phase 11 Glassmorphism** evolution and **Zero-Global** engineering.

---

## 🔬 Module 1: The Front Controller & Zero-Global
**Objective:** Understand how `index.php` acts as the gateway and how global state is eliminated.

### Background
In older PHP sites, every .php file was a separate entry point and relied heavily on the `global` keyword. In modern systems, we use a Front Controller and a centralized `Registry`.
* **Task:** Open `index.php` and identify where `bootstrap.php` is called.
* **Question:** Why do we use `\CmsForNerd\Registry` instead of the `global` keyword for state management?



---

## 🛡️ Module 2: Strict Typing & Security
**Objective:** Learn to prevent common vulnerabilities using PHP 8.4's strict engine.

### Exercise: The Match Expression & SecurityUtils
Look at the routing logic in `index.php`. We use the PHP 8 `match` expression and `SecurityUtils`.

(Example Logic)
`$pageName = \CmsForNerd\SecurityUtils::resolvePageName($scriptName);`

* **Challenge:** Modify the logic in `src/SecurityUtils.php` to explicitly reject any query string that contains path traversal attempts.
* **Goal:** Master type-safe comparisons and secure path resolution.

---

## 📦 Module 3: Dependency Injection (CmsContext)
**Objective:** Understand Immutable State Management.

### Background
We use the **Immutable `CmsContext`** object to carry data. This is a Data Transfer Object (DTO) that enforces "Zero-Global" compliance.

* **Task:** Add a new key to the `$content` array in `index.php` called `'lab_status' => 'Modernizing'`.
* **Challenge:** Update `src/CmsContext.php` (using Constructor Property Promotion) to accept this new data and display it in the glassmorphism theme.

---

## 🎨 Module 4: Theme Scope
**Objective:** Master variable scope within function-based inclusions.

### Background
Our active theme's `pager.php` is included based on the context. It can only see variables passed to it or defined in its scope.

* **Task:** Change `$themeName` in `global-control.inc.php` to a name that does not exist.
* **Observation:** Notice how the Null Coalescing Operator (`??`) in `bootstrap.php` provides a fallback to keep the site running.

---

## 🚀 Final Lab Challenge
**Objective:** Extend the CMS for Phase 11.

1. Create a file: `includes/my-plugin.inc.php`.
2. Define a function `get_lab_timer()` that returns `time()`.
3. Include this file in `bootstrap.php`.
4. Display the timer result in a Glassmorphism-styled div on the installation page.

---

## 📜 Compliance Check
Run the following to verify your work:
`composer lab-check`

**Lead Instructor:** Harisfazillah Jamel
**Environment:** PHP 8.4+ (Strict Mode)
