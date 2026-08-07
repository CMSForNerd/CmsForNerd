---
okf_version: 0.1
type: documentation
title: "🧪 Lab Module 1: Modern Architecture (v4.0.0)"
description: "OKF-compliant documentation for lab-module1.md."
resource: "file:///docs/lab-module1.md"
timestamp: 2026-08-07T05:39:28Z
topics: [docs, lab, module, modern, architecture]
---
# 🧪 Lab Module 1: Modern Architecture (v4.0.0)

> **Topic:** Modern PHP 8.4+ Architecture & PHP 9 Readiness

---

## 🎯 Learning Objectives
1. Eliminate boilerplate using **Constructor Property Promotion**.
2. Master **Property Hooks** to replace traditional Getters/Setters.
3. Understand **Asymmetric Visibility** for secure data encapsulation.

---

## ⚠️ Requirement Level
Students **MUST** implement Constructor Promotion and Property Hooks to pass the "Code Elegance" audit.

---

## 🛠️ Step 1: Constructor Property Promotion

In legacy PHP, you had to declare a property, define it in the constructor, and then assign it. In PHP 8.4, we do all three in one line.

**Task:** Refactor the `User` class in `includes/User.php`.

### Old Way (Legacy)
```php
final class User {
    public readonly string $username;
    public string $role;
    
    public function __construct(string $username, string $role = 'student') {
        $this->username = $username;
        $this->role = $role;
    }
}
```

### New Way (PHP 8.4)
```php
final class User {
    public function __construct(
        public readonly string $username,
        public private(set) string $role = 'student'
    ) {}
}
```

---

## 🧪 Step 2: Implementing Property Hooks

Property hooks allow you to intercept the "Get" or "Set" action. This is perfect for data that needs to be formatted or validated on the fly.

**Exercise 1.1: The Auto-Title Hook**
Update your `CmsContext` to automatically capitalize page titles when they are accessed.

```php
public string $pageTitle {
    // The 'get' hook acts like a virtual getter
    get => ucfirst($this->pageTitle);
    
    // The 'set' hook can sanitize data before it hits the property
    set => $this->pageTitle = trim($value);
}
```

---

## 🔐 Step 3: Asymmetric Visibility

This allows a property to be **Publicly Readable** but **Privately Writable**.

**Exercise 1.2: The Counter Challenge**
1. Create a property called `$viewCount`.
2. Set its visibility to `public private(set)`.
3. **The Test:** Try to change the count from `index.php` (it should fail). Only internal logic can update it.

```php
public private(set) int $viewCount = 0;

public function incrementViews(): void {
    $this->viewCount++; // This works!
}
```

---

## ✅ Step 4: Verification (The "Architect" Audit)

Run your compliance tool to ensure your new architecture follows PSR-12:
`composer check-style`

**Question for the Student:** If a property is marked as `readonly`, can it also have a `set` hook?  
*(Hint: Think about why 'Readonly' and 'Setting a value' might conflict).*

---

## 🎓 RFC 2119 Standards Summary
* **MUST:** Use `readonly` for any data that should never change.
* **SHOULD:** Use Constructor Promotion for all Data Transfer Objects (DTOs).
* **MAY:** Use Property Hooks to replace complex getter methods.

---

### 🚀 Git Commands
Run these in your **Antigravity Terminal** to sync your progress:

```bash
git add .
git commit -m "Build v4.0.0: Completed Module 1 (Modern Architecture)"
git push origin master
```

[Next: Module 2 (Standards)](lab-module2.md)
