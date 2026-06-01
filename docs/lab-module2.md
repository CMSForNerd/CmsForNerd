# 🎨 Lab Module 2: PSR-12 Standards (v4.0.0-alpha)

> **Topic:** PSR-12 and the Art of Clean Code

---

## 🎯 Learning Objectives

1. Identify common PSR-12 violations (Indentation, Braces, Spacing).
2. Automate code formatting using **PHPCBF**.
3. Integrate style checks into your developer workflow.

---

## ⚠️ Requirement Level

Students **MUST** pass a zero-error style audit using automated linting tools to qualify for certification.

---

## 🛠️ Step 1: The "Manual Eye" Test

Before using tools, you must recognize "messy" code. In PHP 8.4+, PSR-12 governs everything from basic classes to match expressions.

**Common Violations to spot:**

* **Braces:** Opening braces `{` for classes/methods **MUST** be on a new line.
* **Indentation:** Use **4 Spaces**. Never use Tabs.
* **Visibility:** Properties **MUST** have a visibility keyword (`public`, `private`, `protected`).

---

## 🧪 Step 2: The Automated Audit (Linter)

Instead of arguing over where a bracket goes, we use **PHP_CodeSniffer (phpcs)**.

**Task:** Run a style audit in your terminal:
`composer check-style`

**Observation:** Look for the difference between **Errors** (Must fix) and **Warnings** (Should improve).

---

## 🪄 Step 3: The "Magic" Fixer (PHPCBF)

Professional nerds don't fix spaces manually. We use the **PHP Code Beautifier and Fixer**.

**Task:** Run the fixer:
`composer fix-style`

**Observation:** Re-run the audit. The error count should drop to zero.

---

## 🧩 Step 4: The "Strict Header" Challenge

PSR-12 requires a specific order for file headers to ensure predictability for both humans and AI agents.

**Exercise:** Ensure every `.php` and `.inc` file follows this sequence:

1. Opening `<?php` tag.
2. Blank line.
3. `declare(strict_types=1);` statement.
4. Namespace declaration.
5. Import (`use`) statements.

---

## ⚖️ RFC 2119 Standards Summary

* **MUST:** Use 4 spaces for indentation.
* **MUST:** Place the opening brace for classes/methods on a new line.
* **SHOULD:** Keep lines under 120 characters.
* **MUST NOT:** Use "Short Tags" like `<?`.

---

**Question:** Why does PSR-12 require the closing `?>` tag to be omitted in files that only contain PHP?  
*(Hint: Think about hidden whitespace causing "Headers already sent" errors).*

[Next: Module 3 (Defensive Engineering)](lab-module3.md)
