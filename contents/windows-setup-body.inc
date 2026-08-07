<article class="setup-guide">
    <header class="guide-header">
        <h1>🚀 The "Future-Proof" Setup Guide: CMSForNerd</h1>
        <p class="subtitle">Prepared for PHP 8.4 & PHP 9 Ready</p>
    </header>

    <div class="guide-intro">
        <p>
            This guide will walk you through setting up a professional development environment on <strong>Windows 11</strong>. 
            We are building this to support the latest PHP 8.4 features (like Property Hooks) and ensure compatibility with 
            the upcoming PHP 9 standards. By focusing on strict typing and modern engine compatibility, this setup is "future-proof."
        </p>
    </div>

    <section class="phase">
        <h2>🛠️ Phase 1: Installing the Professional Toolchain</h2>
        
        <div class="tool-card">
            <h3>1. Laravel Herd (The PHP 8.4+ Engine)</h3>
            <p>Herd is the preferred environment because it manages multiple PHP versions without complex configuration.</p>
            <ul>
                <li><strong>Download:</strong> Visit <a href="https://herd.laravel.com" target="_blank">herd.laravel.com</a>.</li>
                <li><strong>Version Selection:</strong> During setup, ensure you select <strong>PHP 8.4</strong>.</li>
                <li><strong>PHP 9 Readiness:</strong> Herd allows you to update the PHP engine with one click as soon as the PHP 9 Alpha/Beta versions are released.</li>
            </ul>
        </div>

        <div class="tool-card">
            <h3>2. Git for Windows (The Code Mover)</h3>
            <ul>
                <li><strong>Download:</strong> <a href="https://git-scm.com" target="_blank">git-scm.com</a>.</li>
                <li><strong>Crucial Step:</strong> During installation, select <strong>"Enable symbolic links"</strong>. This is important for modern PHP project structures.</li>
            </ul>
        </div>

        <div class="tool-card">
            <h3>3. Google Antigravity (The Advanced Terminal)</h3>
            <p><strong>Why:</strong> Standard Windows CMD often struggles with complex PHP 8.4 CLI output. Antigravity provides the high-speed rendering needed for automated audit tools.</p>
        </div>
    </section>

    <section class="phase">
        <h2>📂 Phase 2: Cloning the Repository</h2>
        <ol>
            <li>Open <strong>Antigravity</strong>.</li>
            <li>Navigate to your Herd sites folder (usually in your user profile):
                <div class="terminal-block"><code>cd ~\Herd</code></div>
            </li>
            <li>Clone the project:
                <div class="terminal-block"><code>git clone https://github.com/CMSForNerd/CmsForNerd.git</code></div>
            </li>
            <li>Enter the directory:
                <div class="terminal-block"><code>cd CmsForNerd</code></div>
            </li>
        </ol>
    </section>

    <section class="phase">
        <h2>⚙️ Phase 3: Initializing for PHP 8.4 & 9</h2>
        <p>To make the CMS run on the latest engines, we must install the modern dependencies.</p>
        <ol>
            <li>Run Composer Install:
                <div class="terminal-block"><code>composer install</code></div>
                <p><em>*This downloads PHPUnit 11+ and PHP_CodeSniffer, which are required for PHP 8.4/9 testing.*</em></p>
            </li>
            <li>Verify PHP Version:
                <div class="terminal-block"><code>php -v</code></div>
                <p>Ensure it says <strong>PHP 8.4.x</strong>. If it says 8.2 or 8.3, go to the Herd Settings and change the version to 8.4.</p>
            </li>
        </ol>
    </section>

    <section class="phase">
        <h2>🧪 Phase 4: Running the "Nerd Audit"</h2>
        <p>To confirm your installation is perfect and follows the RFC 2119 "MUST" requirements:</p>
        <div class="terminal-block"><code>composer compliance</code></div>
        <h3>What this checks:</h3>
        <ul>
            <li><strong>Strict Types:</strong> Are all files using <code>declare(strict_types=1);</code>? (Required for PHP 9 readiness).</li>
            <li><strong>PSR-12:</strong> Is the code formatted for modern readability?</li>
            <li><strong>Security:</strong> Are the <code>SecurityUtils</code> functions active?</li>
        </ul>
    </section>

    <section class="phase reasoning">
        <h2>💡 Important: Why PHP 8.4/9 Matters for Beginners</h2>
        <ul>
            <li><strong>Property Hooks:</strong> You will learn to write code that is 30% shorter by using PHP 8.4 hooks instead of old-fashioned getters and setters.</li>
            <li><strong>Type Safety:</strong> PHP 9 will continue to push for stricter types. By learning with CMSForNerd v4.3.0, you are learning the "Correct Way" from day one.</li>
            <li><strong>Performance:</strong> PHP 8.4 and 9 are significantly faster than older versions, making your CMS feel "instant" on your local machine.</li>
        </ul>
    </section>

    <footer class="guide-footer">
        <h3>Next Step for Students</h3>
        <p>Once your terminal shows "Audit Passed", you are ready to open the project in your editor and start the <a href="lab-manual.php">Lab Manual</a>!</p>
    </footer>
</article>

<style>
.setup-guide h1 { color: #007bff; border-bottom: 2px solid #007bff; padding-bottom: 0.5rem; }
.setup-guide .guide-header { text-align: center; margin-bottom: 3rem; }
.setup-guide .subtitle { font-size: 1.2rem; color: #666; font-weight: bold; }
.setup-guide .guide-intro { background: #e7f3ff; padding: 1.5rem; border-radius: 8px; border-left: 5px solid #007bff; margin-bottom: 3rem; }
.setup-guide h2 { color: #333; margin-top: 3rem; border-bottom: 1px solid #eee; padding-bottom: 0.5rem; }
.setup-guide .tool-card { background: #fff; border: 1px solid #ddd; padding: 1.5rem; border-radius: 8px; margin-bottom: 1.5rem; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
.setup-guide .tool-card h3 { margin-top: 0; color: #0056b3; }
.setup-guide .terminal-block { background: #1a1a1a; color: #00ff00; padding: 1rem; font-family: 'Consolas', monospace; border-radius: 4px; margin: 1rem 0; overflow-x: auto; max-width: 100%; box-sizing: border-box; white-space: pre-wrap; word-break: break-all; }
.setup-guide .phase.reasoning { background: #fffbea; padding: 1.5rem; border-radius: 8px; border: 1px solid #fde68a; }
.setup-guide .phase.reasoning h2 { border-bottom-color: #fde68a; }
.setup-guide .guide-footer { background: #f8f9fa; padding: 2rem; border-radius: 8px; text-align: center; margin-top: 4rem; }
</style>

