# 🚩 The Final Exam: Break-Fix Challenge (v4.0.0-alpha)

> **Scenario:** A "junior dev" has pushed code that violates our RFC 2119 standards and breaks PSR-12 compliance. To pass the "Certified Nerd" audit, you must repair these 5 failures.

---

## 🎯 Challenge 1: The Security Breach (Module 3)

The following loader allows an attacker to read sensitive files via path traversal.
**Task:** Refactor using `SecurityUtils::sanitizePageName()` to make it a "MUST" level security block.

```php
// BROKEN CODE
$page = $_GET['page']; 
include "contents/" . $page . ".inc"; 
```

---

## 🎯 Challenge 2: The Logic Error (Module 1)

This PHP 8.4+ class is throwing a syntax error in its Property Hook.
**Task:** Fix the hook—remember that short-arrow hooks (`=>`) do not use the `return` keyword.

```php
// BROKEN CODE
class Project {
    public string $author {
        set => $this->author = $value;
        get => return strtoupper($this->author); // Error here!
    }
}
```

---

## 🎯 Challenge 3: The PSR-12 Audit (Module 2)

The following code is functional but fails the style audit.
**Task:** Reformat this block to be **PSR-12 compliant**.

```php
// BROKEN CODE
class checker{
function validate($data){
if($data=="valid"){return true;}
else{return false;}
}
}
```

---

## 🎯 Challenge 4: The Failing Test (Module 4)

A test uses the wrong assertion, causing loose type-matching.
**Task:** Switch `assertEquals()` to the strict type-safe version (`assertSame()`) as per our RFC 2119 requirements.

```php
// BROKEN CODE
public function testTypeSafety(): void {
    $result = "100"; // String
    $this->assertEquals(104, $result); // This might pass loosely!
}
```

---

## 🎯 Challenge 5: The CSP Leak (Module 3)

The Content Security Policy is currently "Wide Open."
**Task:** Restrict `script-src` to only allow `'self'`.

```html
<!-- BROKEN CODE -->
<meta http-equiv="Content-Security-Policy" content="script-src *;">
```

---

## ✅ Evaluation Criteria

A student passes if they can run the following command and receive a perfectly clean report:
`composer compliance`

[🏁 Go to Graduation](graduation.md)
