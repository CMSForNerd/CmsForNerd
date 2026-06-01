# 🎓 CMSForNerd Laboratory Guide (v3.5)

Welcome to the CmsForNerd Developer Laboratory. This guide helps you understand modern PHP 8.4 architecture through hands-on exercises.

---

## 🔬 Module 1: The Front Controller Pattern
**Objective:** Understand how index.php acts as the gateway to the application.

### Background
In older PHP sites, every .php file was a separate entry point. In modern systems, we use a Front Controller.
* **Task:** Open `index.php` and identify where `bootstrap.php` is called.
* **Question:** Why do we include a bootstrap file instead of writing the autoloader logic in every single page?



---

## 🛡️ Module 2: Strict Typing & Security
**Objective:** Learn to prevent common vulnerabilities like Directory Traversal.

### Exercise: The Match Expression
Look at the routing logic in index.php. We use the PHP 8 `match` expression:

(Example Logic)
$pageName = \CmsForNerd\SecurityUtils::resolvePageName($scriptName);

* **Challenge:** Modify the logic in `SecurityUtils::resolvePageName()` to explicitly reject any query string that contains "../".
* **Goal:** Master type-safe comparisons.

---

## 📦 Module 3: Dependency Injection (CmsContext)
**Objective:** Understand State Management without Global Variables.

### Background
We use the `CmsContext` object to carry data across the application. This is a Data Transfer Object (DTO).



* **Task:** Add a new key to the `$content` array in `index.php` called `'lab_status' => 'Learning'`.
* **Challenge:** Update `CmsContext.php` to accept this new data and display it in your theme.

---

## 🎨 Module 4: Theme Scope
**Objective:** Master variable scope within function-based inclusions.

### Background
Our active theme's `pager.php` is included based on the context. It can only see variables passed to it or defined in its scope.

* **Task:** Change `$themeName` in `global-control.inc.php` to a name that does not exist.
* **Observation:** Notice how the Null Coalescing Operator (`??`) in `bootstrap.php` provides a fallback to keep the site running.

---

## 🚀 Final Lab Challenge
**Objective:** Extend the CMS.

1. Create a file: `includes/my-plugin.inc.php`.
2. Define a function `get_lab_timer()` that returns `time()`.
3. Include this file in `bootstrap.php`.
4. Display the timer result on the installation page.

---

## 📜 Compliance Check
Run the following to verify your work:
`composer compliance`

**Lead Instructor:** Harisfazillah Jamel
**Environment:** PHP 8.4
