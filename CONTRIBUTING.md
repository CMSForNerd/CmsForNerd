# Contributing to CmsForNerd

Thank you for your interest in improving the CmsForNerd "Nerd Stack"!

## Our Standards

* **PHP 8.4+**: We use modern PHP features.
* **Strict Types**: Every file must start with `declare(strict_types=1);`.
* **Security**: Never hardcode domains. Use dynamic detection as seen in `global-control.inc.php`.

## How to Contribute

1. Fork the repository.
2. **AI Sync**: If you are using an AI agent (like Antigravity, Copilot, or Cursor), ensure it performs an **Intelligence Audit** first. Refer to [.agent/workflows/nerd-lab-protocol.md](.agent/workflows/nerd-lab-protocol.md) and [docs/AI-STATE-SYNC.md](docs/AI-STATE-SYNC.md).
3. Create a feature branch (`git checkout -b feature/AmazingFeature`).
4. Commit your changes (`git commit -m 'Add some AmazingFeature'`). Use granular commits.
5. **Brain Update**: Update [.agent/brain/walkthrough.md](.agent/brain/walkthrough.md) to record the state of mind for your changes.
6. Push to the branch (`git push origin feature/AmazingFeature`).
7. Open a Pull Request.

## AI-Assisted Contributions (The "State of Mind")

We embrace AI-agentic workflows. To ensure your AI stays synchronized with the "Nerd Stack" architecture:

* **Persistent Brain**: Always check `.agent/brain/task.md` and `walkthrough.md` before starting.
* **Git-Delta awareness**: Your agent MUST fetch and log remote changes to avoid architectural drift.
* **Compliance**: All contributions must pass `composer compliance` (PSR-12 + PHPStan Level 8 + PHPUnit).

## Security Reports

Please do not report security vulnerabilities via GitHub issues. Refer to our `/.well-known/security.txt` for reporting procedures (RFC 9116).
