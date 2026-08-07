<article class="csp-guide" itemscope itemtype="https://schema.org/TechArticle">
    <header class="guide-header">
        <h1 itemprop="headline">🛡️ CSP Nonce Implementation Guide</h1>
        <p class="intro">
            Implementing a <strong>Content Security Policy (CSP) Nonce</strong> is the gold standard for stopping 
            <strong>Cross-Site Scripting (XSS)</strong> while allowing verified inline scripts to execute.
        </p>
    </header>

    <section class="overview">
        <h2>What is a CSP Nonce?</h2>
        <p>
            A <strong>nonce</strong> (number used once) is a cryptographically random string generated per page load. 
            Only scripts with this exact token are permitted to run by the browser.
        </p>
        <div class="highlight-box">
            <p><strong>Key Concept:</strong> We replace <code>'unsafe-inline'</code> with a unique 128-bit token. 
            Since an attacker cannot predict the token, their injected scripts are ignored.</p>
        </div>
    </section>

    <section class="implementation">
        <h2>Step 1: The Verification Logic</h2>
        <p>In <strong>CMSForNerd v4.3.0</strong>, we use the <code>SecurityUtils</code> class to generate a secure base64 string.</p>
        
        <div class="code-block">
            <pre><code>// SecurityUtils::generateNonce() logic
return base64_encode(random_bytes(16));</code></pre>
        </div>

        <p>This is then applied to your <code>common-headertag.inc</code>:</p>
        <div class="code-block">
            <pre><code>&lt;meta http-equiv="Content-Security-Policy" 
      content="script-src 'self' 'nonce-<?= $ctx->cspNonce ?>';"&gt;</code></pre>
        </div>
    </section>

    <section class="comparison-section">
        <h2>XSS Protection in Action</h2>
        <div class="comparison-grid">
            <div class="card before">
                <h3>❌ Legacy (Vulnerable)</h3>
                <pre><code>&lt;script&gt;
  alert(document.cookie);
&lt;/script&gt;</code></pre>
                <p class="danger">Browser executes the malicious code.</p>
            </div>

            <div class="card after">
                <h3>✅ v4.3.0 Standard (Secure)</h3>
                <pre><code>&lt;script nonce="XYZ123..."&gt;
  // This is safe
&lt;/script&gt;</code></pre>
                <p class="success">Only matched nonces are allowed.</p>
            </div>
        </div>
    </section>

    <section class="lab-challenge">
        <div class="try-it">
            <h3>🧪 Lab Challenge: Manual Injection</h3>
            <p>Open your console (F12) and try to run a dynamic script. Notice the <strong>CSP Refusal</strong> error. This is the "Green Bar" of security testing.</p>
        </div>
    </section>

    <nav class="footer-nav">
        <a href="lab-manual.php" class="btn btn-secondary">← Back to Lab</a>
        <a href="security-policy.php" class="btn btn-primary">View Security Policy →</a>
    </nav>
</article>

<style>
    :root { --csp-red: #c7254e; --csp-green: #5cb85c; --csp-blue: #0066cc; }
    .csp-guide { max-width: 900px; margin: 0 auto; line-height: 1.6; }
    .guide-header h1 { color: var(--csp-red); border-bottom: 3px solid var(--csp-red); }
    .intro { background: #fff3cd; padding: 20px; border-radius: 8px; border-left: 5px solid #ffc107; }
    .code-block { background: #2d2d2d; color: #f8f8f2; padding: 15px; border-radius: 5px; font-family: monospace; margin: 15px 0; }
    .comparison-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin: 20px 0; }
    .card { padding: 15px; border-radius: 8px; border: 1px solid #ddd; }
    .before { border-top: 5px solid var(--csp-red); }
    .after { border-top: 5px solid var(--csp-green); }
    .danger { color: var(--csp-red); font-weight: bold; }
    .success { color: var(--csp-green); font-weight: bold; }
    .try-it { background: #f0f8ff; border: 2px dashed var(--csp-blue); padding: 20px; border-radius: 10px; }
    .btn { padding: 12px 24px; text-decoration: none; border-radius: 6px; display: inline-block; font-weight: bold; }
    .btn-primary { background: var(--csp-red); color: white; }
    .btn-secondary { background: #eee; color: #333; }
    @media (max-width: 600px) { .comparison-grid { grid-template-columns: 1fr; } }
</style>
