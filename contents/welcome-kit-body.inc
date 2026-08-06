<article class="welcome-kit">
    <header class="kit-header">
        <h1>🚀 CMSForNerd v4.3.0: Student Welcome Kit</h1>
        <p class="quote">"Modernization without loss of simplicity."</p>
    </header>

    <div class="kit-intro">
        <p>
            Welcome to the lab. This document contains everything you need to manage your environment and pass your modules. 
            Keep this <strong>Cheat Sheet</strong> open as you work through the curriculum.
        </p>
    </div>

    <section class="kit-section">
        <h2>🛠️ The Essential "Nerd Stack"</h2>
        <table class="stack-table">
            <thead>
                <tr>
                    <th>Tool</th>
                    <th>Purpose</th>
                    <th>Status Check</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Laravel Herd</strong></td>
                    <td>PHP 8.4+ Runtime & Server</td>
                    <td><code>php -v</code> (Must be 8.4+)</td>
                </tr>
                <tr>
                    <td><strong>Git for Windows</strong></td>
                    <td>Version Control & Cloning</td>
                    <td><code>git --version</code></td>
                </tr>
                <tr>
                    <td><strong>Antigravity</strong></td>
                    <td>High-Performance Terminal</td>
                    <td>(Open App)</td>
                </tr>
                <tr>
                    <td><strong>VS Code</strong></td>
                    <td>Code Editor / IDE</td>
                    <td><code>code .</code></td>
                </tr>
            </tbody>
        </table>
    </section>

    <section class="kit-section">
        <h2>📂 Quick Start Commands</h2>
        <p>Run these inside your <code>C:\Users\YourName\Herd\CmsForNerd</code> folder:</p>
        <div class="command-grid">
            <div class="cmd-item">
                <strong>Initialize Project:</strong>
                <code>composer install</code>
            </div>
            <div class="cmd-item">
                <strong>Run All Audits:</strong>
                <code>composer compliance</code>
            </div>
            <div class="cmd-item">
                <strong>Check Style Only:</strong>
                <code>composer check-style</code>
            </div>
            <div class="cmd-item">
                <strong>Fix Style Automatically:</strong>
                <code>composer fix-style</code>
            </div>
            <div class="cmd-item">
                <strong>Run Security Tests:</strong>
                <code>composer test</code>
            </div>
        </div>
    </section>

    <section class="kit-section">
        <h2>⚖️ Our RFC 2119 Standards</h2>
        <p>As a student of this laboratory, your code must adhere to these requirement levels:</p>
        <ul class="standard-list">
            <li><span class="must">MUST:</span> Every file begins with <code>declare(strict_types=1);</code>.</li>
            <li><span class="must">MUST:</span> Opening braces for classes/methods are on a new line.</li>
            <li><span class="must-not">MUST NOT:</span> Use <code>global</code> variables. Use <code>CmsContext</code> instead.</li>
            <li><span class="should">SHOULD:</span> Aim for 90% code coverage in your logic.</li>
            <li><span class="may">MAY:</span> Use Property Hooks for simple data transformations.</li>
        </ul>
    </section>

    <section class="kit-section">
        <h2>🛡️ Security Laws</h2>
        <div class="security-grid">
            <div class="law">
                <h3>1. Never Trust User Input</h3>
                <p>Always run <code>$_GET</code> or <code>$_POST</code> data through <code>SecurityUtils::sanitizePageName()</code>.</p>
            </div>
            <div class="law">
                <h3>2. Escape Output</h3>
                <p>Use <code>SecurityUtils::escapeHtml()</code> for all variables rendered in the theme.</p>
            </div>
            <div class="law">
                <h3>3. Strict Routing</h3>
                <p>Only include files that exist in the <code>contents/</code> directory.</p>
            </div>
            <div class="law rfc-law">
                <h3>4. Be Reachable (RFC 9116)</h3>
                <p>Maintain your <code>security.txt</code> and <a href="security-policy.php">Security Policy</a>. This is the "Front Door" for ethical hackers to report bugs safely.</p>
            </div>
        </div>
    </section>

    <section class="kit-section troubleshooting">
        <h2>🆘 Troubleshooting</h2>
        <dl>
            <dt>404 Error?</dt>
            <dd>Ensure the file exists in <code>contents/</code> and has no special characters in the filename.</dd>
            
            <dt>PHP Version Error?</dt>
            <dd>Open Laravel Herd Settings and ensure <strong>"PHP 8.4"</strong> is selected and "Nginx" is running.</dd>
            
            <dt>Terminal Permissions?</dt>
            <dd>If Windows blocks your scripts, run: <code>Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser</code>.</dd>
        </dl>
    </section>

    <footer class="path-to-cert">
        <h2>🎓 Path to Certification</h2>
        <div class="roadmap">
            <div class="step"><strong>1. Setup:</strong> Install tools & clone repo.</div>
            <div class="step"><strong>2. Modules 1-3:</strong> Architecture, Standards, Security.</div>
            <div class="step"><strong>3. Modules 4-5:</strong> Write Unit Tests & Coverage.</div>
            <div class="step"><strong>4. Final Exam:</strong> Fix the "Broken Lab" code.</div>
            <div class="step"><strong>5. Certification:</strong> Claim your digital certificate!</div>
        </div>
        <div class="cta">
            <a href="lab-manual.php" class="btn welcome-btn">🚀 Start Laboratory Module 1</a>
        </div>
    </footer>
</article>

<style>
.welcome-kit h1 { color: #004085; border-bottom: 2px solid #004085; padding-bottom: 0.5rem; margin-top: 0; }
.welcome-kit .kit-header { text-align: center; margin-bottom: 2rem; }
.welcome-kit .quote { font-style: italic; color: #666; font-size: 1.1rem; }
.welcome-kit .kit-intro { background: #e2f3f5; padding: 1.5rem; border-radius: 8px; border-left: 5px solid #004085; margin-bottom: 2rem; }
.welcome-kit h2 { color: #333; margin-top: 2rem; border-bottom: 1px solid #eee; padding-bottom: 0.3rem; }
.welcome-kit .stack-table { width: 100%; border-collapse: collapse; margin: 1rem 0; }
.welcome-kit .stack-table th, .welcome-kit .stack-table td { text-align: left; padding: 0.75rem; border: 1px solid #dee2e6; }
.welcome-kit .stack-table th { background: #f8f9fa; }
.welcome-kit .command-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin: 1.5rem 0; }
.welcome-kit .cmd-item { background: #fff; border: 1px solid #ddd; padding: 1rem; border-radius: 4px; }
.welcome-kit .cmd-item code { display: block; margin-top: 0.5rem; background: #212529; color: #f8f9fa; padding: 0.5rem; border-radius: 4px; white-space: pre-wrap; word-break: break-all; }
.welcome-kit .standard-list { list-style: none; padding: 0; }
.welcome-kit .standard-list li { margin-bottom: 0.75rem; }
.welcome-kit .must { color: #d9534f; font-weight: bold; }
.welcome-kit .must-not { color: #d9534f; font-weight: bold; text-decoration: underline; }
.welcome-kit .should { color: #f0ad4e; font-weight: bold; }
.welcome-kit .may { color: #5bc0de; font-weight: bold; }
.welcome-kit .security-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin: 1.5rem 0; }
.welcome-kit .law { background: #fff9f9; border: 1px solid #ffeeba; padding: 1rem; border-radius: 4px; }
.welcome-kit .law h3 { margin-top: 0; font-size: 1rem; color: #856404; }
.welcome-kit .troubleshooting dl { margin: 1rem 0; }
.welcome-kit .troubleshooting dt { font-weight: bold; color: #c82333; margin-top: 1rem; }
.welcome-kit .path-to-cert { background: #f8f9fa; padding: 2rem; border-radius: 8px; margin-top: 3rem; text-align: center; }
.welcome-kit .roadmap { display: flex; flex-direction: column; gap: 0.5rem; margin-bottom: 2rem; text-align: left; max-width: 400px; margin-left: auto; margin-right: auto; }
.welcome-kit .step { border-left: 3px solid #004085; padding-left: 1rem; }
.welcome-kit .welcome-btn { background: #004085; color: #fff; padding: 1rem 2rem; border-radius: 4px; text-decoration: none; font-weight: bold; }
.welcome-kit .welcome-btn:hover { background: #002752; }
</style>
