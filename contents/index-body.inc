<article class="lab-home">
    <header class="hero-section">
        <h1>Welcome to CMSForNerd v4.3.0: The Secure Coding Laboratory</h1>
        <div class="runtime-status">
            <span class="badge php-version">PHP <?= phpversion() ?></span>
            <span class="badge status-strict">Strict Mode: Active</span>
            <span class="badge status-check">PHPStan: Level 8</span>
            <span class="badge status-sec">CSP: Nonce-Enforced</span>
        </div>
    </header>

    <div class="intro-box">
        <p>
            <strong>CMSForNerd</strong> is a lightweight, "Zero-Debt" PHP 8.4 Flat-File CMS designed for the modern security environment. 
            While frameworks hide complexity, we expose it—teaching you to write code that is fast, immutable, and impenetrable. 
            Master the "Pair Logic" workflow used in high-security infrastructure development.
        </p>
        <div class="quick-links">
            <a href="welcome-kit.php" class="btn welcome-btn">🚀 Welcome Kit</a>
            <a href="lab-manual.php" class="btn lab-btn">🎓 Lab Manual</a>
            <a href="template.php" class="btn guide-btn">🎨 Template Guide</a>
            <a href="ui-kit.php" class="btn audit-btn">🧪 UI Audit</a>
        </div>
    </div>

    <section class="grid-features">
        <div class="feature-card">
            <h3>🛡️ Security Engineering</h3>
            <ul>
                <li><strong>Hybrid SecurityUtils:</strong> Native path sanitization preventing LFI and Directory Traversal.</li>
                <li><strong>CSP Engine:</strong> Automatic Nonce generation for every request to block XSS.</li>
                <li><strong>Immutable State:</strong> Use of <code>readonly</code> classes and <code>createCmsContext()</code>.</li>
                <li><strong>Directory Privacy:</strong> 403 Forbidden gateways on all core and theme folders.</li>
            </ul>
        </div>

        <div class="feature-card">
            <h3>🚀 Training Standards</h3>
            <ul>
                <li><strong>"Pair Logic" Architecture:</strong> Separation of Controller (Master) and Fragment (Slave).</li>
                <li><strong>Modern Control:</strong> Strict <code>match()</code> expressions and Type Hinting throughout.</li>
                <li><strong>Compliance Ready:</strong> Aligned with <strong>RFC 9116</strong> (Security.txt) and PSR-12.</li>
                <li><strong>Bot Hardening:</strong> Integrated bot detection and Turnstile support for lab forms.</li>
            </ul>
        </div>
    </section>

    

    <section class="lab-workflow">
        <h3>The "Nerd-Stack" Secure Workflow</h3>
        <div class="workflow-steps">
            <div class="step">
                <span class="step-num">1</span>
                <h4>The Controller</h4>
                <p>Duplicate <code>template.php</code> and update page metadata.</p>
            </div>
            <div class="step">
                <span class="step-num">2</span>
                <h4>The Fragment</h4>
                <p>Create a <code>-body.inc</code> file in the <code>contents/</code> folder.</p>
            </div>
            <div class="step">
                <span class="step-num">3</span>
                <h4>The Audit</h4>
                <p>Verify compliance via <code>phpstan</code> and the <strong>UI Kit</strong>.</p>
            </div>
        </div>
    </section>

    <section class="stack-recommendation">
        <h3>Optimized Learning Stack</h3>
        <table class="stack-table">
            <tr>
                <td><strong>Runtime</strong></td>
                <td>PHP 8.4+ (Zend OPcache recommended)</td>
            </tr>
            <tr>
                <td><strong>Standards</strong></td>
                <td>PSR-12, RFC 9116, Strict Types <code>1</code></td>
            </tr>
            <tr>
                <td><strong>OS Support</strong></td>
                <td>Debian/Ubuntu Linux & Windows 11 (via Herd/WSL2)</td>
            </tr>
            <tr>
                <td><strong>Dev Tools</strong></td>
                <td>Composer, Git, PHPStan, VS Code, <strong>Google Antigravity</strong></td>
            </tr>
        </table>
    </section>
</article>

<style>
    /* CSS maintained as per previous refinement for consistency */
    .lab-home { max-width: 1000px; margin: 0 auto; line-height: 1.6; font-family: var(--f-sans, sans-serif); }
    .hero-section h1 { color: var(--lab-heading, #2c3e50); border-bottom: 4px solid var(--lab-purple, #8e44ad); padding-bottom: 10px; margin-bottom: 10px; }
    
    .runtime-status { margin-bottom: 25px; }
    .badge { display: inline-block; padding: 4px 12px; border-radius: 4px; font-size: 0.85rem; font-weight: bold; margin-right: 5px; }
    .php-version { background: #4F5B93; color: white; }
    .status-strict { background: #27ae60; color: white; }
    .status-check { background: #f39c12; color: white; }
    .status-sec { background: #8e44ad; color: white; }

    .intro-box { background: var(--lab-surface, #f4f7f6); border: 1px solid var(--lab-border, #e2e8f0); color: var(--lab-text); padding: 30px; border-radius: 12px; border-left: 6px solid var(--lab-purple, #8e44ad); margin-bottom: 40px; }
    .quick-links { margin-top: 20px; display: flex; gap: 10px; flex-wrap: wrap; }
    
    .btn { padding: 12px 20px; text-decoration: none; border-radius: 6px; font-weight: bold; color: white; transition: all 0.2s ease; font-size: 0.9rem; }
    .welcome-btn { background: #2980b9; }
    .lab-btn { background: #c0392b; }
    .guide-btn { background: #8e44ad; }
    .audit-btn { background: #2c3e50; }
    .btn:hover { filter: brightness(1.1); transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.1); }

    .grid-features { display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 25px; margin-bottom: 40px; }
    .feature-card { background: var(--lab-surface, white); border: 1px solid var(--lab-border, #e2e8f0); color: var(--lab-text); padding: 25px; border-radius: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
    .feature-card h3 { color: var(--lab-heading, #2c3e50); margin-top: 0; border-bottom: 1px solid var(--lab-border, #eee); padding-bottom: 10px; }
    .feature-card ul { padding-left: 20px; }
    .feature-card li { margin-bottom: 8px; font-size: 0.95rem; }

    .lab-workflow { background: #0f172a; color: white; padding: 30px; border-radius: 12px; margin-bottom: 40px; }
    .workflow-steps { display: flex; justify-content: space-around; gap: 20px; margin-top: 20px; text-align: center; }
    .step-num { background: #8e44ad; width: 40px; height: 40px; display: block; margin: 0 auto 10px; border-radius: 50%; line-height: 40px; font-weight: bold; font-size: 1.2rem; }
    .step h4 { margin-bottom: 5px; color: #ecf0f1; }
    .step p { font-size: 0.85rem; color: #bdc3c7; }

    .stack-table { width: 100%; border-collapse: collapse; background: var(--lab-surface, white); border-radius: 8px; overflow: hidden; border: 1px solid var(--lab-border, #e2e8f0); color: var(--lab-text); }
    .stack-table td { padding: 15px; border: 1px solid var(--lab-border, #eee); }
    .stack-table tr td:first-child { background: var(--lab-bg, #f9f9f9); width: 30%; font-weight: bold; color: var(--lab-muted, #64748b); font-size: 0.9rem; }
</style>
