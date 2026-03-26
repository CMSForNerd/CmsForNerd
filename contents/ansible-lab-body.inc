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
        <p>The CmsForNerd infrastructure uses a "Zero-Debt" deployment gateway. This ensures every server rollout is verified for PHP 8.4 compliance and PSR-12 formatting before deployment.</p>
        
        <div class="code-block-wrapper">
            <label>The Secure Gateway Command:</label>
            <pre><code>bash tools/deploy-lab.sh</code></pre>
            <p class="hint">Recommended for all laboratory staging and production updates.</p>
        </div>
    </section>

    <section class="lab-section bg-light-purple">
        <h2>🛠️ What Ansible Installs & Configures</h2>
        <p>Our automation fabric handles the entire stack setup autonomously across Ubuntu, Debian, AlmaLinux, and RHEL:</p>
        
        <div class="proc-grid">
            <div class="proc-card">
                <h3>🌐 Nginx (Secure Proxy)</h3>
                <ul>
                    <li>Automated package installation & service hardening.</li>
                    <li>VHost generation with <code>.git</code> and <code>.inc</code> blocking.</li>
                    <li>Automatic 80-to-443 redirection logic.</li>
                </ul>
            </div>
            <div class="proc-card">
                <h3>🐘 PHP 8.4-FPM</h3>
                <ul>
                    <li>Installation of PHP 8.4, MBString, XML, and CLI.</li>
                    <li>Pool tuning (<code>www.conf</code>) based on OS family.</li>
                    <li>Optimized socket communication (<code>run/php/</code>).</li>
                </ul>
            </div>
            <div class="proc-card">
                <h3>📦 CmsForNerd</h3>
                <ul>
                    <li>Automated Git synchronization (v3.5 master).</li>
                    <li><strong><code>composer install</code></strong> execution with optimizations.</li>
                    <li>Strict ownership mapping (<code>www-data</code> vs <code>nginx</code>).</li>
                </ul>
            </div>
        </div>
    </section>

    <section class="lab-section">
        <h2>📂 Orchestration File Hierarchy</h2>
        <p>The core deployment files are located in the laboratory root:</p>
        <ul class="file-list">
            <li><code>ansible.cfg</code>: Optimized SSH and YAML output settings.</li>
            <li><code>deploy.yml</code>: The Master Playbook orchestrating the lab.</li>
            <li><code>inventory/hosts.staging.yml</code>: Target node definitions.</li>
            <li><code>playbooks/roles/</code>: Modular logic for Common, Web, PHP, and CMS.</li>
        </ul>
    </section>

    <section class="lab-section shadow-sm">
        <h2>🎯 Essential Ansible Commands</h2>
        <table class="cmd-table">
            <thead>
                <tr>
                    <th>Target Operation</th>
                    <th>CLI Command</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Full Deployment</strong></td>
                    <td><code>ansible-playbook deploy.yml -i inventory/hosts.staging.yml</code></td>
                </tr>
                <tr>
                    <td><strong>Just Update Code</strong></td>
                    <td><code>ansible-playbook deploy.yml --tags codebase</code></td>
                </tr>
                <tr>
                    <td><strong>Harden Firewall</strong></td>
                    <td><code>ansible-playbook deploy.yml --tags foundation</code></td>
                </tr>
                <tr>
                    <td><strong>Nginx & PHP Setup</strong></td>
                    <td><code>ansible-playbook deploy.yml --tags web,php</code></td>
                </tr>
            </tbody>
        </table>
    </section>

    <footer class="content-footer">
        <p>Technical Lead: <a href="docs/ANSIBLE-LAB-MANUAL.md">Practical Laboratory Guide</a></p>
    </footer>
</article>

<style>
.lab-section { background: #fff; padding: 2rem; border-radius: 12px; margin-bottom: 2rem; border: 1px solid #eef; }
.bg-light-purple { background: #fdfbff; border-color: #f0e6ff; }
.proc-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; margin-top: 1.5rem; }
.proc-card { background: #fff; padding: 1.5rem; border-radius: 8px; border-top: 5px solid #8e44ad; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
.proc-card h3 { color: #2d3436; margin-top: 0; font-size: 1.1rem; }
.proc-card ul { padding-left: 1.2rem; font-size: 0.9rem; color: #636e72; }
.file-list { list-style: none; padding: 0; }
.file-list li { padding: 0.4rem 0.8rem; border-bottom: 1px solid #f1f2f6; font-family: monospace; font-size: 0.95rem; }
.cmd-table { width: 100%; border-collapse: collapse; margin-top: 1rem; font-size: 0.95rem; }
.cmd-table th { text-align: left; padding: 0.8rem; background: #f8f9fa; border-bottom: 2px solid #eee; }
.cmd-table td { padding: 0.8rem; border-bottom: 1px solid #eee; }
.cmd-table td:last-child { font-family: monospace; color: #2c3e50; background: #fdfdfd; }
.code-block-wrapper { background: #2d3436; color: #dfe6e9; padding: 1.5rem; border-radius: 8px; margin: 1.5rem 0; }
.code-block-wrapper pre { margin: 0; font-size: 1.1rem; color: #55efc4; }
</style>
