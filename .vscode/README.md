---
okf_version: 0.1
type: documentation
title: "CMSForNerd - Development Environment Configuration"
description: "OKF-compliant documentation for README.md."
resource: "file:///.vscode/README.md"
timestamp: 2026-08-07T05:39:28Z
topics: [vscode, cmsfornerd, development, environment, configuration]
---
# CMSForNerd - Development Environment Configuration

## Platform-Specific Setup Instructions

### 🪟 Windows (with Laravel Herd)
Add this to your **User Settings** (Ctrl+Shift+P → "Preferences: Open User Settings (JSON)"):
```json
{
  "php.validate.executablePath": "C:\\Users\\YOUR_USERNAME\\.config\\herd\\bin\\php.exe",
  "terminal.integrated.profiles.windows": {
    "Antigravity": {
      "path": "pwsh.exe",
      "icon": "terminal-powershell"
    }
  },
  "terminal.integrated.defaultProfile.windows": "Antigravity"
}
```

### 🐧 Linux (Debian/Ubuntu/AlmaLinux)
Add this to your **User Settings**:
```json
{
  "php.validate.executablePath": "/usr/bin/php8.4",
  "terminal.integrated.defaultProfile.linux": "bash"
}
```

## Why User Settings?
Hardcoded paths in workspace settings break portability. By using **User Settings**, 
each developer configures their own environment once, and the project works everywhere.

## Verifying Your PHP Path

### Windows (Herd):
```powershell
Get-Command php | Select-Object -ExpandProperty Source
```

### Linux:
```bash
which php8.4
```
