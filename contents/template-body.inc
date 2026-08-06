<article class="template-guide">
    <h1>🎨 CmsForNerd v4.3.0 Laboratory Guide</h1>
    <p class="subtitle">Mastering the "Pair Logic" & Context Engine</p>

    <div class="intro-box">
        <p>
            The <code>template.php</code> file acts as the <strong>Master Controller</strong>. In v4.3.0, you don't need to write new PHP logic for every page. You simply <strong>duplicate</strong> the template and pair it with a content fragment.
        </p>
    </div>

    <section class="guide-step">
        <h2>📂 Step 1: Locate the Folders</h2>
        <p>Focus on these key areas in the Laboratory environment:</p>
        <ul>
            <li><strong>Root Directory:</strong> Public <code>.php</code> entry points (e.g., <code>index.php</code>, <code>about.php</code>).</li>
            <li><strong>contents/ Directory:</strong> Raw "body" fragments (<code>-body.inc</code> files).</li>
            <li><strong>src/ & includes/:</strong> The core engine, security utilities, and bootstrap logic.</li>
        </ul>
    </section>

    <section class="guide-step">
        <h2>📝 Step 2: Create Your Content (.inc file)</h2>
        <p>Code only the internal HTML structure for your page body.</p>
        <ol>
            <li>Create a new file inside <code>contents/</code>.</li>
            <li><strong>Naming Rule:</strong> It must end in <code>-body.inc</code> (e.g., <code>contact-body.inc</code>).</li>
            <li>Use semantic HTML5 tags for structure. Do not include <code>&lt;html&gt;</code> or <code>&lt;body&gt;</code> tags here.</li>
        </ol>
        <div class="code-example">
            <h3>Example: <code>contents/contact-body.inc</code></h3>
            <pre><code>&lt;section class="contact-page"&gt;
    &lt;h1&gt;Contact Us&lt;/h1&gt;
    &lt;p&gt;Send a message to the lab administrators.&lt;/p&gt;
&lt;/section&gt;</code></pre>
        </div>
    </section>

    <section class="guide-step">
        <h2>🚀 Step 3: Create Your Page (.php file)</h2>
        <p>In CMSForNerd, you never write new engine code. You simply <strong>copy, rename, and adjust metadata</strong>.</p>
        
        <div class="instruction-box">
            <ol>
                <li><strong>Copy:</strong> Duplicate <code>template.php</code> in the root folder.</li>
                <li><strong>Rename:</strong> Change the copy to match your content (e.g., <code>contact.php</code>).</li>
                <li><strong>Adjust:</strong> Update only the <code>$content</code> array metadata.</li>
            </ol>
        </div>

        <div class="code-example">
            <h3>Metadata Adjustment in <code>contact.php</code>:</h3>
            <pre><code>/**
 * 3. [SEO] Metadata - CUSTOMIZE THESE FOR EVERY NEW PAGE
 */
$content = [
    'title'       => "Contact Us | CmsForNerd",
    'author'      => "Harisfazillah Jamel",
    'description' => "Get in touch with the laboratory team.",
    'keywords'    => "Contact, PHP 8.4, CmsForNerd",
];

/**
 * 4. [LAB] ROUTING LOGIC (Body-Partial Rule)
 * DO NOT MODIFY: This automatically finds 'contact-body.inc'
 */
$baseName = pathinfo(basename(__FILE__), PATHINFO_FILENAME);
$pageName = "{$baseName}-body";
$content['data'] = $pageName;</code></pre>
        </div>
        <p><em>The rest of the file (GZIP, Security, and Theme Execution) remains exactly as copied.</em></p>
    </section>

    <section class="guide-step security-check">
        <h2>🛡️ Step 4: Verify Safety & Compliance</h2>
        <p>Before moving to production, perform these three laboratory checks:</p>
        <div class="check-grid">
            <div class="check-item">
                <h3>1. Static Analysis</h3>
                <p>Run <code>composer analyze</code>. Your new page must show 0 errors at PHPStan Level 8.</p>
            </div>
            <div class="check-item">
                <h3>2. CSP Nonce Verification</h3>
                <p>If you add inline <code>&lt;script&gt;</code>, you must use <code>$ctx->cspNonce</code> to pass the security policy.</p>
            </div>
            <div class="check-item">
                <h3>3. Sanitization</h3>
                <p>Ensure your page name is valid and safe using <code>SecurityUtils::isValidPageName()</code>.</p>
            </div>
        </div>
    </section>

    <section class="standards-box">
        <h2>⚖️ Laboratory Standards (v4.3.0 Update)</h2>
        <ul>
            <li><strong>MUST:</strong> Keep <code>declare(strict_types=1);</code> at the top of all <code>.php</code> files.</li>
            <li><strong>MUST NOT:</strong> Modify the Routing Logic or Theme Execution blocks in copied files.</li>
            <li><strong>REQUIRED:</strong> Use the <code>$ctx</code> object to access any page data within your theme.</li>
        </ul>
    </section>
</article>

<style>
.template-guide h1 { color: #8e44ad; border-bottom: 2px solid #8e44ad; padding-bottom: 0.5rem; }
.template-guide .subtitle { font-size: 1.2rem; color: #666; font-weight: bold; margin-bottom: 2rem; }
.template-guide .intro-box { background: #f3e5f5; padding: 1.5rem; border-radius: 8px; border-left: 5px solid #8e44ad; margin-bottom: 2rem; }
.template-guide .instruction-box { background: #fff; padding: 1rem; border: 1px dashed #8e44ad; border-radius: 8px; margin-bottom: 1rem; }
.template-guide .guide-step { margin-bottom: 3rem; }
.template-guide h2 { color: #333; border-bottom: 1px solid #eee; padding-bottom: 0.5rem; }
.template-guide .code-example { background: #1a1a1a; color: #fff; padding: 1rem; border-radius: 8px; margin: 1rem 0; }
.template-guide .code-example h3 { color: #e1bee7; margin-top: 0; font-size: 0.9rem; text-transform: uppercase; }
.template-guide .code-example pre { margin: 0; white-space: pre-wrap; word-break: break-all; }
.template-guide .code-example code { font-family: 'Consolas', monospace; color: #f0f0f0; }
.template-guide .security-check { background: #fff3e0; padding: 1.5rem; border-radius: 8px; border: 1px solid #ffe0b2; }
.template-guide .check-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-top: 1rem; }
.check-item { background: white; padding: 1rem; border-radius: 4px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
.check-item h3 { font-size: 1rem; color: #e65100; margin-top: 0; }
.template-guide .standards-box { background: #f8f9fa; padding: 1.5rem; border-radius: 8px; border: 1px solid #ddd; margin-top: 2rem; }
</style>
