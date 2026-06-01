# 📊 Lab Module 5: Coverage & QA (v3.5)

> **Topic:** Test Coverage and Quality Assurance

---

## 🎯 Learning Objectives

1. Understand the difference between **Code Execution** and **Code Coverage**.
2. Generate an interactive **HTML Coverage Report**.
3. Use the **CRAP Index** to identify risky, poorly-tested code.

---

## ⚠️ Requirement Level

Students **MUST** generate an HTML report showing 100% coverage for all `SecurityUtils` methods.

---

## ⚙️ Step 1: The Coverage Engine

To track which lines of code are tested, PHP needs a driver. We use **Xdebug**.

**Task:** Verify your environment:
`php -m | findstr "xdebug"`

---

## 🧪 Step 2: Generating the Visual Map

We convert raw data into a readable website showing our code with highlighted lines.

**Task:** Run PHPUnit with the HTML coverage flag:
`XDEBUG_MODE=coverage ./vendor/bin/phpunit --coverage-html build/coverage`

1. Open `build/coverage/index.html` in your browser.
2. Navigate to `SecurityUtils.php`.

---

## 🔍 Step 3: Analyzing "Unreachable" Code

Red lines in your report indicate "Dark Spots"—logic that hasn't been verified by a test.

**Exercise:**

1. Look at `SecurityUtils::sanitizePageName`.
2. Do you have a test case that tries to use a hyphen `-` (e.g., `about-us`)?
3. If the hyphen regex is Red, add a test case in `SecurityUtilsTest.php`.
4. **Re-Run:** Regenerate the report. The line should now be **Green**.

---

## ✅ Step 4: The Quality Audit (CRAP Index)

The **Change Risk Anti-Patterns (CRAP)** index measures how hard code is to maintain.

* **High CRAP:** High complexity + Low coverage = Dangerous.
* **Low CRAP:** Clean code + High coverage = Professional.

**Requirement:** Your `SecurityUtils` class **MUST** have a CRAP score of less than 5.

---

## ⚖️ RFC 2119 Standards Summary

* **MUST:** Generate a coverage report before every production release.
* **SHOULD:** Aim for 90% coverage on core security logic.
* **MAY:** Exclude view files/themes from the report.

---

**Question for the Student:** Does 100% Code Coverage mean your code is 100% bug-free?  
*(Hint: Think about "logic errors"—if your test expects `2+2` to be `5` and the code gives `5`, the test passes, but the logic is wrong).*

[Final Task: The Graduation Exam](final-exam.md)
