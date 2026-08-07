<article class="ai-dev-guide" itemscope itemtype="https://schema.org/TechArticle">
    <header class="guide-header">
        <h1 itemprop="headline">🤖 AI-Assisted Development: Gemini + Antigravity</h1>
        <p class="subtitle">The Modern Workflow: "From Prompt to Production"</p>
    </header>

    <div class="intro-box" itemprop="description">
        <p>
            In the modern era of backend engineering, you are no longer coding in isolation. 
            <strong>CMSForNerd v4.3.0</strong> was architected using a high-speed synergy between
            <strong>Google Gemini</strong> (The Architect) and <strong>Google Antigravity</strong> (The Agent).
        </p>
    </div>

    

    <section class="workflow-grid">
        <div class="workflow-card gemini">
            <h2>🧠 Google Gemini</h2>
            <p class="role">The Architect & Strategist</p>
            <ul>
                <li><strong>Logic & Design:</strong> Plans class hierarchies and Property Hooks.</li>
                <li><strong>RFC Compliance:</strong> Validates code against RFC 2119 and PSR-12.</li>
                <li><strong>Theory:</strong> Explains the "Why" behind CmsContext vs. Global variables.</li>
            </ul>
        </div>

        <div class="workflow-card antigravity">
            <h2>🚀 Google Antigravity</h2>
            <p class="role">The Agentic Executioner</p>
            <ul>
                <li><strong>File Operations:</strong> Writes <code>.php</code> and <code>.inc</code> files to disk.</li>
                <li><strong>Terminal Control:</strong> Runs <code>composer compliance</code> and <code>phpunit</code>.</li>
                <li><strong>Git Mastery:</strong> Handles staging and commits once code is verified.</li>
            </ul>
        </div>
    </section>

    <section class="semantic-strategy">
        <h2>🌐 The "Triple Threat" Discovery Strategy</h2>
        <p>We use three layers to ensure AI tools accurately categorize your CMS content:</p>
        
        <div class="threat-grid">
            <div class="threat-card">
                <h3>1️⃣ Microdata</h3>
                <p class="tech-detail">HTML tag attributes</p>
                <code class="code-snippet">&lt;article itemscope itemtype="..."&gt;</code>
                <p>Ensures immediate classification by LLM scrapers.</p>
            </div>

            <div class="threat-card">
                <h3>2️⃣ JSON-LD</h3>
                <p class="tech-detail">Structured Scripting</p>
                <code class="code-snippet">"@type": "TechArticle"</code>
                <p>Enables Google Rich Results and Knowledge Graphs.</p>
            </div>

            <div class="threat-card">
                <h3>3️⃣ RDF/Dublin Core</h3>
                <p class="tech-detail">Formal Metadata</p>
                <code class="code-snippet">&lt;dc:creator&gt;Harisfazillah&lt;/dc:creator&gt;</code>
                <p>W3C-compliant semantic web relationships.</p>
            </div>
        </div>
    </section>

    

    <section class="standards-warning">
        <h2>⚖️ The "Good AI Citizen" Rules</h2>
        <ul>
            <li><strong>MUST:</strong> Verify <code>declare(strict_types=1);</code> exists on every AI-generated file.</li>
            <li><strong>MUST NOT:</strong> Bypass <code>composer compliance</code>. If tests fail, the AI's code is rejected.</li>
            <li><strong>SHOULD:</strong> Ask Gemini to generate a Unit Test for every new logic block it writes.</li>
        </ul>
    </section>

    <nav class="footer-nav">
        <p>Mastered the workflow? Move to the next module.</p>
        <div class="btn-group">
            <a href="ai-sop.php" class="btn btn-secondary">📜 View AI Ethics SOP</a>
            <a href="lab-manual.php" class="btn btn-primary">Return to Lab Manual</a>
        </div>
    </nav>
</article>

<style>
    :root {
        --gemini-blue: #1a73e8;
        --anti-red: #d93025;
        --border-color: #dadce0;
    }

    .ai-dev-guide { max-width: 900px; margin: 0 auto; line-height: 1.6; }
    .guide-header h1 { color: var(--gemini-blue); border-bottom: 3px solid var(--gemini-blue); padding-bottom: 10px; }
    .subtitle { font-size: 1.2rem; color: #5f6368; font-style: italic; }

    .intro-box { background: #e8f0fe; padding: 20px; border-radius: 8px; border-left: 5px solid var(--gemini-blue); margin: 25px 0; }

    .workflow-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 40px; }
    .workflow-card { padding: 20px; border: 1px solid var(--border-color); border-radius: 12px; background: #fff; transition: transform 0.2s; }
    .workflow-card:hover { transform: translateY(-5px); box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
    .workflow-card h2 { margin-top: 0; }
    .role { font-weight: bold; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 1px; margin-bottom: 15px; }

    .gemini h2 { color: var(--gemini-blue); }
    .gemini .role { color: var(--gemini-blue); }
    .antigravity h2 { color: var(--anti-red); }
    .antigravity .role { color: var(--anti-red); }

    .threat-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px; margin-top: 20px; }
    .threat-card { background: #f8f9fa; padding: 15px; border-radius: 8px; border: 1px solid #eee; }
    .code-snippet { display: block; background: #202124; color: #f1f3f4; padding: 8px; border-radius: 4px; font-size: 0.85rem; margin: 10px 0; font-family: 'Courier New', monospace; }

    .standards-warning { background: #fff7e0; padding: 20px; border-radius: 8px; border: 1px solid #f9ab00; margin-top: 40px; }

    .footer-nav { margin-top: 50px; text-align: center; border-top: 1px solid #eee; padding-top: 30px; }
    .btn-group { display: flex; gap: 10px; justify-content: center; margin-top: 15px; }
    .btn { padding: 12px 24px; border-radius: 6px; text-decoration: none; font-weight: bold; }
    .btn-primary { background: var(--gemini-blue); color: white; }
    .btn-secondary { background: #f1f3f4; color: #3c4043; }

    @media (max-width: 600px) {
        .workflow-grid { grid-template-columns: 1fr; }
    }
</style>
