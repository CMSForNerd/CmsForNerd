<?php
/**
 * ==========================================================================
 * FILE: contents/ansible-lab-body.inc
 * ROLE: Content Fragment for Ansible Laboratory Guide
 * ==========================================================================
 */

declare(strict_types=1);
?>

<article class="lab-documentation">
    <header class="content-header">
        <h1>🤖 Ansible Laboratory Orchestration</h1>
        <p class="subtitle">Automated Nginx & PHP 8.4-FPM Deployment v3.5.1</p>
    </header>

    <section class="lab-section shadow-sm">
        <h2>🚀 Deployment Fast-Track</h2>
        <p>The CmsForNerd infrastructure is powered by a multi-distribution Ansible fabric designed for Ubuntu, Debian, AlmaLinux, and RHEL.</p>
        
        <div class="code-block-wrapper">
            <label>The "Zero-Debt" Gateway:</label>
            <pre><code>bash tools/deploy-lab.sh</code></pre>
            <p class="hint">This command enforces <strong>PHPStan Level 8</strong> and <strong>PSR-12</strong> compliance before touching the server.</p>
        </div>
    </section>

    <section class="lab-section">
        <h2>🛠️ Core Orchestration Roles</h2>
        <div class="grid-container">
            <div class="grid-item">
                <h3>🛡️ Common</h3>
                <p>OS hardening, Firewall (UFW/Firewalld), and security tuning.</p>
            </div>
            <div class="grid-item">
                <h3>🌐 Webserver</h3>
                <p>Secure Nginx VHost with automated <code>.git</code> and <code>.inc</code> blocking.</p>
            </div>
            <div class="grid-item">
                <h3>🐘 PHP 8.4</h3>
                <p>PHP-FPM pool tuning and optimized dependency management.</p>
            </div>
            <div class="grid-item">
                <h3>📦 CmsForNerd</h3>
                <p>Automated Git sync, ownership mapping, and <code>composer install</code>.</p>
            </div>
        </div>
    </section>

    <section class="lab-section bg-light-purple">
        <h2>🎯 Manual Control Tags</h2>
        <p>Execute specific segments of the deployment architecture:</p>
        <ul class="tag-list">
            <li><code>--tags foundation</code>: Firewall & OS Hardening</li>
            <li><code>--tags nginx</code>: Proxy Configuration</li>
            <li><code>--tags php</code>: Application Engine</li>
            <li><code>--tags codebase</code>: Repository Sync & Build</li>
        </ul>
    </section>

    <footer class="content-footer">
        <p>Reference Document: <a href="docs/ANSIBLE-LAB-MANUAL.md">docs/ANSIBLE-LAB-MANUAL.md</a></p>
    </footer>
</article>

<style>
.lab-section {
    background: #fff;
    padding: 2.5rem;
    border-radius: 12px;
    margin-bottom: 2rem;
    border: 1px solid #eef;
}
.bg-light-purple { background: #f8f4ff; border-color: #e6d5ff; }
.grid-container { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-top: 1.5rem; }
.grid-item { background: #fdfdfd; padding: 1.5rem; border-radius: 8px; border-left: 4px solid #8e44ad; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
.tag-list { list-style: none; padding: 0; }
.tag-list li { background: #eee; padding: 0.5rem 1rem; margin-bottom: 0.5rem; border-radius: 6px; font-family: monospace; font-size: 0.9rem; border-left: 3px solid #666; }
.code-block-wrapper { background: #2d3436; color: #dfe6e9; padding: 1.5rem; border-radius: 8px; margin: 1.5rem 0; }
.code-block-wrapper label { display: block; margin-bottom: 0.5rem; color: #fab1a0; font-weight: bold; font-size: 0.85rem; text-transform: uppercase; }
.code-block-wrapper pre { margin: 0; font-size: 1.1rem; color: #55efc4; }
.hint { font-size: 0.85rem; color: #b2bec3; margin-top: 0.5rem; }
</style>
