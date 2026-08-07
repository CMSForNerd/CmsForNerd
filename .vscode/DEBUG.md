---
okf_version: 0.1
type: documentation
title: "CMSForNerd - VS Code Debug Configuration"
description: "OKF-compliant documentation for DEBUG.md."
resource: "file:///.vscode/DEBUG.md"
timestamp: 2026-08-07T05:39:28Z
topics: [vscode, debug, cmsfornerd, code, configuration]
---
# CMSForNerd - VS Code Debug Configuration

This file is a **template** for PHP debugging with Xdebug.

## Prerequisites
To use this configuration, install the PHP Debug extension:
- Extension ID: `xdebug.php-debug`
- Command: `code --install-extension xdebug.php-debug`

## Xdebug Setup
1. **Windows (Herd)**: Xdebug comes pre-installed with Laravel Herd
2. **Linux**: Install `php8.4-xdebug` from your package manager

## Creating launch.json (After Installing Extension)
Create `.vscode/launch.json` with:
```json
{
  "version": "0.2.0",
  "configurations": [
    {
      "name": "Listen for Xdebug",
      "type": "php",
      "request": "launch",
      "port": 9003
    }
  ]
}
```

For more information, see: https://xdebug.org/docs/step_debug
