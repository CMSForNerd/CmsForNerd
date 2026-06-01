# 🎓 Lab Manual: The Developer’s Laboratory (v3.5)

Welcome to the **v3.5 educational suite**. This CMS is designed to be a **transparent laboratory**. Every line of code is accessible, every architectural choice is documented, and every security feature is a lesson in professional standards.

---

## 📚 Laboratory Module 1: Modern PHP 8.4+ Architecture

In this module, you will explore the transition from procedural scripts to modern object-oriented patterns, optimized for PHP 8.4 and ready for PHP 9.

### Exercise 1.1: Strict Typing

Observe how `declare(strict_types=1);` prevents the CMS from accepting incorrect data types, forcing developers to write predictable code.

* **Worksheet:** [Module 1 Worksheet](lab-module1.md)

### Exercise 1.2: The Context Pattern

See how the `CmsContext` object replaces the "Global Variable" anti-pattern, teaching the principle of **Single Source of Truth**.

### Exercise 1.3: Property Hooks (PHP 8.4)

Learn to use PHP 8.4 hooks to automate data validation within class properties, reducing boilerplate code.

---

## ⚖️ Laboratory Module 2: Standards & Compliance

Professional code is defined by its adherence to global standards.

* **Worksheet:** [Module 2 Worksheet](lab-module2.md)

### Requirement Level (RFC 2119)

We use *MUST*, *SHOULD*, and *MAY* to define project boundaries.

* **Rule:** All templates **MUST** be HTML5 compliant.
* **Rule:** Security utilities **MUST NOT** be bypassed by the core loader.

### PSR-12 Linting

Use the integrated `composer check-style` command to identify and fix visual inconsistencies in your code.

### Exercise 2.3: Semantic Web Metadata

Understand how **RDF** and **JSON-LD** help AI tools categorize your educational content.

* View `labels.rdf` to see Dublin Core metadata (legacy W3C standard).
* Examine `common-headertag.inc` to see dynamic JSON-LD generation using Schema.org.
* **Verification:** Use [Google's Structured Data Testing Tool](https://validator.schema.org/) to validate your JSON-LD.

---

## 🛡️ Laboratory Module 3: Defensive Engineering

Security is the "Red Team vs. Blue Team" playground of CMSForNerd. Master both input hardening and perimeter defense.

* **Worksheet:** [Module 3 Worksheet](lab-module3.md)

### Lab: Perimeter & Path Traversal

Implement **Defense-in-Depth** by securing the file loader and configuring a **Content Security Policy (CSP)**.

### Lab 3.2: CSP Nonces (2026 Best Practice)

Learn to configure the Content Security Policy (CSP) with **cryptographic nonces** instead of dangerous `'unsafe-inline'` directives.

* **Concept:** A nonce is a "number used once" - a random token that changes every page load.
* **Implementation:** Review how `SecurityUtils::generateNonce()` creates cryptographically secure tokens.

### Module 3.5: Responsible Disclosure (RFC 9116)

Understand how to communicate with security researchers using the `security.txt` standard.

* **Exercise:** Verify the machine-readable `/.well-known/security.txt` file in your browser.
* **Exercise:** Draft a simulated email for a discovery following the [Security Policy](security-policy.md) rules.

---

## 🧪 Laboratory Module 4: Automated Testing (TDD)

Master the art of Test-Driven Development using **PHPUnit 11**.

* **Worksheet:** [Module 4 Worksheet](lab-module4.md)

### Exercise: Write a new test

Create a test in `tests/ThemeIntegrityTest.php` that fails if a student accidentally deletes the `style.css` file.
**Concept: "Green-Light Thinking"**—if the tests aren't green, the code isn't ready.

---

## 📊 Laboratory Module 5: Test Coverage and QA

Learn to visualize the safety net of your application using Code Coverage reports.

* **Worksheet:** [Module 5 Worksheet](lab-module5.md)

### Exercise: Generate a Coverage Report

Use Xdebug and PHPUnit to create an interactive HTML report to identify "dark spots" in your code.
**Concept: "Confidence in Code"**—100% coverage doesn't mean bug-free, but it means everything was tested.

---

## 🤖 Laboratory Module 6: AI-Assisted Workflow

Learn to lead an "AI Agent" as your junior developer. Master the Prompt-to-Product methodology.

### Exercise 6.1: The Agentic Challenge

Use Gemini/Antigravity to create a new page defined by a single sentence. Review the implementation plan and verify the automated build.

* **Guide:** [AI Dev Guide](ai-dev.md)

### Exercise 6.2: AI Ethics & Responsible Usage

Study the **Standard Operating Procedure #2026-01** for ethical AI integration.

* Read the [📜 AI Ethics SOP](ai-sop.md) to understand the "Think First" rule and "Trust but Verify" law.

---

## 🚩 The Final Exam: Break-Fix Challenge

The ultimate test of a modern backend engineer. Repair a broken system to prove your mastery.

* **Exam:** [Final Exam Challenge](final-exam.md)

---

## 📝 The History of v3.5: A Modernization Case Study

CMSForNerd v3.5 isn't just a version; it's a **Modernization Journey**.

1. **Phase 1:** Refactored the 2005 foundation into PHP 8.4+ classes.
2. **Phase 2:** Standardized UI with CSS Grid.
3. **Phase 3:** Hardened perimeter with Cloudflare Turnstile and `SecurityUtils`.
4. **Phase 4:** Automated workflow with PHPUnit and `PHP_CodeSniffer`.
5. **Phase 5:** Integrated AI-native configuration (JSON-LD, CSP nonces) for 2026 standards.

---

### 🎓 Ready to Graduate?

If you have completed all modules and passed all compliance audits, you are ready to claim your certificate.
[🏁 Claim Your Certificate of Completion](graduation.md)

**Educational Motto:** "Modernization without loss of simplicity." — Harisfazillah Jamel & Gemini, 2026.
