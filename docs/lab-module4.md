# 🧪 Lab Module 4: Automated Testing (v4.0.0-alpha)

> **Topic:** Automated Testing with PHPUnit 11

---

## 🎯 Learning Objectives

1. Understand the **Arrange-Act-Assert (AAA)** pattern.
2. Write a unit test for the `SecurityUtils` class.
3. Master the terminal-based test runner.

---

## ⚠️ Requirement Level

Students **MUST** pass all assertions to achieve "Certified Nerd" status and pass the laboratory audit.

---

## 📂 Step 1: The Test Anatomy

In PHPUnit, every test file **MUST** end with the suffix `Test.php` and its class **MUST** extend `TestCase`.

### The AAA Pattern

* **Arrange:** Set up the objects and data needed for the test.
* **Act:** Execute the specific function you want to test.
* **Assert:** Check if the result matches your expectations.

---

## 🛠️ Step 2: Writing Your First Test

You will write a test for the `SecurityUtils::escapeHtml` method to ensure it correctly prevents XSS (Cross-Site Scripting).

**Task:** Create `tests/SecurityUtilsTest.php` and enter this code:

```php
<?php

declare(strict_types=1);

namespace CmsForNerd\Tests;

use PHPUnit\Framework\TestCase;
use CmsForNerd\SecurityUtils;

final class SecurityUtilsTest extends TestCase
{
    /**
     * Requirement: HTML special characters MUST be converted to entities.
     */
    public function testEscapesHtmlSpecialCharacters(): void
    {
        // 1. Arrange
        $input = '<script>alert("hack");</script>';
        $expected = '&lt;script&gt;alert(&quot;hack&quot;);&lt;/script&gt;';

        // 2. Act
        $result = SecurityUtils::escapeHtml($input);

        // 3. Assert
        $this->assertSame($expected, $result, "The HTML was not escaped correctly!");
    }
}
```

---

## 🚀 Step 3: Running the Lab

Open your Antigravity Terminal and execute the test suite:
`./vendor/bin/phpunit tests/SecurityUtilsTest.php`

**What to look for:**

* **. (Dot):** This means your test passed!
* **F (Failure):** Something went wrong. PHPUnit will show you exactly what it expected vs. what it got.

---

## 🧪 Step 4: The "Breaking" Exercise

To truly understand testing, you must see a failure.

1. Open `includes/SecurityUtils.php`.
2. Temporarily change the `escapeHtml` function to just `return $content;` (breaking the security).
3. Run the test again.
4. **Observe:** Watch how PHPUnit catches your mistake instantly. This is why we test!

---

## ⚖️ RFC 2119 Standards Summary

* **MUST:** Every test method name **MUST** start with the word `test`.
* **MUST:** Test classes **MUST** be marked as `final`.
* **SHOULD:** You **SHOULD** use `assertSame()` instead of `assertEquals()` for strict comparison.

---

**Question for the Student:** Why is it better to test small units of code (Unit Testing) before testing the entire website (Integration Testing)?

[Next: Module 5 (Coverage & QA)](lab-module5.md)
