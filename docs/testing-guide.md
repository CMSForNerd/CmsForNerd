---
description: "How to use the Dual Testing Architecture (Pest + PHPUnit) in CMSForNerd v4.0.0-alpha."
---

# ⚗️ Dual Testing Guide

Welcome to the **Testing Laboratory**.

CMSForNerd v4.0.0-alpha employs a unique **Dual Testing Architecture**. We maintain our strict, legacy security benchmarks using classic **PHPUnit**, while also providing you the modern, elegant, and frictionless experience of **Pest PHP** for your feature development.

This guide will teach you how to run both engines, what they are used for, and the strict architectural rules that prevent them from cross-contaminating.

---

## 1. Why Two Frameworks?

In a real-world enterprise environment, you will often inherit legacy test suites that cannot be immediately rewritten, yet you still want to adopt modern tools for new features.

* **PHPUnit (Legacy Validation)**: Used strictly for `CmsForNerd\Tests`. These tests validate PSR-12 indentation standards, `strict_types` enforcement, and foundational core security. They exist to definitively prove the "Zero-Error" baseline of the CMS.
* **Pest PHP (Modern Expansion)**: Used for all future student laboratory work in `Tests\Unit` and `Tests\Feature`. It is built *on top* of PHPUnit, providing a faster, more readable syntax (`it()` and `test()`).

---

## 2. Running the Laboratory Tests

We have configured Composer scripts to handle the dual-engine execution automatically, ensuring you never have to memorize complex binary paths or filters.

### A. The "Gatekeeper" (Run Everything)

To prove your entire laboratory environment is secure and all features are functioning correctly, use the primary compliance command:

```bash
composer compliance
```

> [!NOTE]
> This runs the Intelligence Audit, Static Analysis (PHPStan Level 8), and then invokes Pest to run **both** the modern Pest tests and the legacy PHPUnit tests simultaneously.

### B. Running ONLY Legacy (PHPUnit)

If you only want to verify the core CMS architectural rules without running your custom features:

```bash
composer test:phpunit
```

> [!TIP]
> This command uses Pest's internal engine but enforces a strict `--filter` to execute *only* the classes inside the `CmsForNerd\Tests` namespace.

### C. Running ONLY Modern (Pest)

If you are rapidly building a new feature and want to evaluate your Pest assertions:

```bash
composer test:pest
```

---

## 3. Writing Your First Pest Test

Pest ditches the verbose Object-Oriented boilerplate of PHPUnit. You don't need `class`, `public function`, or `$this->assertTrue()`.

Create a new file in `tests/Unit/ExampleTest.php`:

```php
<?php

declare(strict_types=1);

// A simple Unit test using the "test" function
test('that mathematics still works in the lab', function () {
    expect(1 + 1)->toBe(2);
});

// A behavioral test using the "it" function
it('can detect a true boolean', function () {
    $isValid = true;
    
    expect($isValid)->toBeTrue();
});
```

---

## 4. Architectural Rules (Zero Cross-Contamination)

To maintain order in the v4.0.0-alpha Laboratory, you MUST adhere to the following namespaces and boundaries:

> [!WARNING]
> Do NOT mix test styles within the same directory. This causes the Dual Testing execution parameters to fail.

1. **Legacy Root**: Do not place new files in `tests/` directly *(e.g., `tests/MyNewTest.php`)*. That root is reserved for the legacy PHPUnit classes (`SecurityTest.php`, etc.).
2. **Modern Root**: All custom Pest tests MUST be placed inside the `tests/Unit/` or `tests/Feature/` subdirectories.
3. **No Laravel Scaffolding**: If you manually execute `pest --init`, it will attempt to generate a `TestCase.php` that extends Laravel. **Delete it immediately.** CMSForNerd uses a vanilla `PHPUnit\Framework\TestCase` binding defined cleanly in `tests/Pest.php`.

---

Happy testing! May your assertions always return green.
