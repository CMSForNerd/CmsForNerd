---
okf_version: 0.1
type: skill
title: "PHP Code Quality, SonarCloud, and PHPStan Standards"
name: "php-quality-sonar-phpstan"
description: "Guidelines and procedures for ensuring strict PHPStan and SonarCloud compliance, including PHPUnit attributes, safe type annotations, and resource consumption guards."
topics: [php, phpstan, sonarcloud, attributes, testing]
timestamp: 2026-08-01T09:00:00Z
---

# 🛡️ PHP Code Quality, SonarCloud, and PHPStan Standards

## Purpose
This skill provides the exact patterns and rules required to maintain high-fidelity code quality, satisfying strict static analysis gates (such as PHPStan Level 8) and SonarCloud/SonarQube analysis.

## When to use this skill
Use this skill whenever you write or modify PHP source files, configure SonarCloud pipelines, write PHPUnit tests, or resolve static analysis errors.

## Guidelines & Best Practices

### 1. PHPStan URL Type Annotations
To satisfy PHPStan Level 8 analysis for dynamically constructed URLs passed to `curl_setopt()`, do not use `non-empty-string` annotations. Instead, use the `non-falsy-string` annotation to align perfectly with native type inference.
```php
/** @var non-falsy-string $url */
curl_setopt($ch, CURLOPT_URL, $url);
```
For regular dynamic variables (like `CURLOPT_USERAGENT` or general variables), annotate with `non-empty-string`:
```php
/** @var non-empty-string $variable */
curl_setopt($ch, CURLOPT_USERAGENT, $variable);
```

### 2. SonarCloud Analysis Failure Prevention
To resolve SonarCloud analysis failures (such as HTTP 403 Forbidden errors) and Node.js 20 deprecation warnings in CI/CD pipelines:
- Upgrade to `SonarSource/sonarqube-scan-action@v8` or higher.
- Set `FORCE_JAVASCRIPT_ACTIONS_TO_NODE24: true` in action envs.
- Explicitly grant `pull-requests: read` permission scope.
- Ensure that the `sonar.projectKey` is strictly lowercase (e.g., `linuxmalaysia_cmsfornerd`).
- Explicitly set `sonar.host.url=https://sonarcloud.io`.
- *Note:* Command-line `-D` arguments in workflow files override `sonar-project.properties`.

### 3. PHPUnit Attributes
Prefer modern PHPUnit attributes over legacy docblock annotations for data providers to comply with modern PHPUnit standards and satisfy SonarCloud maintainability gates.
```php
use PHPUnit\Framework\Attributes\DataProvider;

#[\PHPUnit\Framework\Attributes\DataProvider('additionProvider')]
public function testAdd($a, $b, $expected) { ... }
```

### 4. Uncontrolled Resource Consumption (DoS Prevention)
To prevent SonarCloud Security Hotspots and analysis failures related to uncontrolled resource consumption:
- **CIDR bits validation:** Explicitly validate and clamp CIDR prefix lengths to `0-32` (for IPv4) or `0-128` (for IPv6) before using them in bitwise operations or mask generations.
- **String padding operations:** Avoid using `str_repeat()` or `str_pad()` with variable integers derived from external user input, even if the input is clamped. Instead, perform in-place modifications of pre-allocated fixed-length strings.

### 5. Markdown Fenced Code Blocks
All fenced code blocks in markdown files must contain explicit language specifications (e.g., ` ```php ` instead of ` ``` `) to strictly comply with MD040 linting rules and pass CI validation checks.


---
*Deep State of Mind (DSOM) For My AI Protocol | Harisfazillah Jamel (LinuxMalaysia) | 2026-08-05*
*Standard: UK English | DBP-standard Bahasa Melayu Malaysia (Piawai) | GNU General Public License v3.0*
