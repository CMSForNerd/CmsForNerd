<article class="modernization-history"> <header class="history-header"> <h1>Modernization History: The Journey to v3.5</h1> <p class="intro"> Starting in late 2025, CMSForNerd underwent a radical transformation. This log tracks the shift from a 20-year-old legacy codebase to a modern, AI-synergized developer's laboratory. </p> </header>
<div class="timeline">
    <section class="version-block v35">
        <div class="version-tag">v3.5.1</div>
        <h2>PWA Engine & Modernization Mastery (Current)</h2>
        <p><strong>Focus:</strong> SPA-Hybrid Routing, CSP Synchronization, and Architectural Finalization.</p>
        <ul>
            <li><strong>Progressive Web App:</strong> Introduced Stale-While-Revalidate caching and bfcache instantaneous navigation logic.</li>
            <li><strong>CSP Symmetry:</strong> Standardized <code>nonce</code> controls mapping strict validations across both Standard Turnstile injections and AMP <code>blob:</code> web workers.</li>
            <li><strong>GitBook Synchronization:</strong> Full alignment of <code>docs/</code> with <code>contents/</code> logic.</li>
            <li><strong>Factory Pattern:</strong> Refactored context initialization to <code>createCmsContext()</code> factory.</li>
            <li><strong>Audit Completion:</strong> 100% pass rate for PHPStan Level 8 and PSR-12 across all controllers.</li>
        </ul>
    </section>

    <section class="version-block v34">
        <div class="version-tag">v3.4</div>
        <h2>The Semantic Alignment</h2>
        <p><strong>Focus:</strong> AI Readiness and Metadata standardization.</p>
        <ul>
            <li><strong>JSON-LD 2.0:</strong> Modernized metadata layer in <code>common-headertag.inc</code> using the <code>CmsContext</code> object.</li>
            <li><strong>Theme Modernization:</strong> Corrected theme path references from <code>lab_v3</code> to the standardized <code>CmsForNerd</code> theme.</li>
            <li><strong>Controller Sync:</strong> Synchronized 21 page controllers to the v3.4 template baseline.</li>
        </ul>
    </section>

    <section class="version-block v33">
        <div class="version-tag">v3.3</div>
        <h2>The "Bootstrap" Milestone</h2>
        <p><strong>Focus:</strong> Performance, Stability, and Runtime Global-Safe Initialization.</p>
        <ul>
            <li><strong>Centralized Bootstrap:</strong> Introduced <code>includes/bootstrap.php</code> to fix scope issues in the <code>CmsContext</code>.</li>
            <li><strong>GZIP Optimization:</strong> Standardized <code>ob_gzhandler</code> across all entry points for 70% faster data delivery.</li>
            <li><strong>Instructor Resources:</strong> Finalized the <em>Official Answer Key</em> and <em>Hall of Fame</em> with full v3.3 logic.</li>
        </ul>
    </section>

    <section class="version-block v32">
        <div class="version-tag">v3.2</div>
        <h2>The "Laboratory" Expansion</h2>
        <p><strong>Focus:</strong> Educational tooling and cross-platform reliability.</p>
        <ul>
            <li><strong>Cross-Platform Setup:</strong> Created specialized guides for <em>Windows 11 (Herd)</em> and <em>Linux (AlmaLinux/Debian)</em>.</li>
            <li><strong>Security Policy:</strong> Implemented <code>security.txt</code> (RFC 9116) and a formal Responsible Disclosure protocol.</li>
            <li><strong>AI Synergy:</strong> Integrated <em>Google Antigravity</em> instructions for AI-assisted coding workflows.</li>
        </ul>
    </section>

    <section class="version-block v31">
        <div class="version-tag">v3.1</div>
        <h2>The Architectural Rebirth</h2>
        <p><strong>Focus:</strong> Refactoring the 2005 core into modern PHP 8.4.</p>
        <ul>
            <li><strong>Object-Oriented Core:</strong> Replaced global state with the <code>CmsContext</code> object and Readonly properties.</li>
            <li><strong>Security Hardening:</strong> Added <em>Path Traversal</em> protection and <em>Cloudflare Turnstile</em>.</li>
            <li><strong>PSR-12 Compliance:</strong> First automated style-check integration using PHPCS.</li>
            <li><strong>PHP 8.4 Hooks:</strong> Introduced Property Hooks to replace legacy Getters/Setters.</li>
        </ul>
    </section>
</div>

<footer class="history-footer">
    <p><em>"Refining the past to secure the future."</em> — LinuxMalaysia & Google Gemini</p>
</footer>
</article>

<style> :root { --v35-color: #e67e22; --v34-color: #f1c40f; --v33-color: #2ecc71; --v32-color: #3498db; --v31-color: #9b59b6; --bg-gray: #f9f9f9; } .modernization-history { max-width: 900px; margin: 0 auto; line-height: 1.8; } .history-header { text-align: center; margin-bottom: 50px; } .history-header h1 { color: #2c3e50; font-size: 2.4rem; border-bottom: 3px solid var(--v33-color); display: inline-block; } .intro { background: var(--bg-gray); padding: 20px; border-left: 5px solid #ccc; font-style: italic; }
.timeline { position: relative; padding: 20px 0; }
.version-block { margin-bottom: 40px; padding: 25px; border-radius: 12px; background: #fff; border: 1px solid #eee; position: relative; }
.version-tag { position: absolute; top: -15px; right: 20px; padding: 5px 15px; border-radius: 20px; color: white; font-weight: bold; font-size: 0.9rem; }

.v35 { border-left: 6px solid var(--v35-color); } .v35 .version-tag { background: var(--v35-color); }
.v34 { border-left: 6px solid var(--v34-color); } .v34 .version-tag { background: var(--v34-color); }
.v33 { border-left: 6px solid var(--v33-color); } .v33 .version-tag { background: var(--v33-color); }
.v32 { border-left: 6px solid var(--v32-color); } .v32 .version-tag { background: var(--v32-color); }
.v31 { border-left: 6px solid var(--v31-color); } .v31 .version-tag { background: var(--v31-color); }

.version-block h2 { margin-top: 0; color: #2c3e50; }
.version-block ul { padding-left: 1.5rem; }
.version-block li { margin-bottom: 8px; }

.history-footer { text-align: center; margin-top: 60px; color: #7f8c8d; font-size: 0.95rem; border-top: 1px solid #eee; padding-top: 20px; }
</style>

<article class="modernization-history">
    <h1>Modernization History: The v3.1 Journey</h1>
    <p class="intro">
        In late 2025, a landmark project was undertaken to bring <strong>CmsForNerd</strong> from its roots in 2005 into the modern PHP era. 
        This page documents the steps, standards, and improvements that transformed a classic flat-file CMS into a high-performance, 
        standards-compliant, and secure teaching tool.
    </p>

    <section>
        <h2>1. The PHP 8.4 Foundation</h2>
        <p>
            The entire codebase was refactored to support <strong>PHP 8.4+ and PHP 9</strong>. This included embracing modern object-oriented 
            patterns while maintaining the "Radically Simple" philosophy of the original author.
        </p>
        <ul>
            <li><strong>Strict Types:</strong> Every core file now starts with <code>declare(strict_types=1);</code>.</li>
            <li><strong>State Management:</strong> Replaced hundreds of global variables with an immutable <code>CmsContext</code> object.</li>
            <li><strong>Modern Classes:</strong> Implemented <code>readonly</code> classes and <code>Constructor Property Promotion</code>.</li>
        </ul>
    </section>

    <section>
        <h2>2. Standards & Compliance (PSR-12 & RFC 2119)</h2>
        <p>
            To ensure the codebase is professional and easy to maintain, we adopted global standards for style and requirements.
        </p>
        <ul>
            <li><strong>PSR-12 Style:</strong> Standardized indentation (4 spaces), brace placement, and naming conventions.</li>
            <li><strong>RFC 2119 Maturity:</strong> Codified architectural "Laws of the Project" using <em>MUST</em>, <em>SHOULD</em>, and <em>RECOMMENDED</em> terminology.</li>
            <li><strong>Automated Audits:</strong> Integrated <code>php_codesniffer</code> and custom <code>composer compliance</code> workflows.</li>
        </ul>
    </section>

    <section>
        <h2>3. Security Hardening</h2>
        <p>
            Modern web threats require modern defenses. CmsForNerd v3.1 is now a bunker of security best practices.
        </p>
        <ul>
            <li><strong>Cloudflare Turnstile:</strong> Integrated invisible bot protection for all forms.</li>
            <li><strong>Zero Directory Traversal:</strong> Implemented strict <code>SecurityUtils</code> for sanitizing every request.</li>
            <li><strong>Content Security Policy (CSP):</strong> Hardened headers to block XSS and unauthorized script execution.</li>
            <li><strong>Bot Defense:</strong> High-speed Regex-based detection to protect resources from aggressive scrapers.</li>
        </ul>
    </section>

    <section>
        <h2>4. Design Evolution (CSS Grid)</h2>
        <p>
            We replaced the legacy float-based layouts from the mid-2000s with a fluid, responsive <strong>CSS Grid</strong> system. 
            The site now feels premium on 4K monitors and mobile phones alike.
        </p>
    </section>

    <section>
        <h2>5. Automated Testing Suite</h2>
        <p>
            For the first time in its history, CmsForNerd features a comprehensive test suite powered by <strong>PHPUnit 11</strong>.
        </p>
        <ul>
            <li><strong>Logic Tests:</strong> Verifies core routing and context integrity.</li>
            <li><strong>Security Tests:</strong> Ensures input sanitization and bot detection never fail.</li>
            <li><strong>Standards Tests:</strong> Programmatically enforces PSR-12 and strict typing.</li>
        </ul>
    </section>

    <section>
        <h2>6. Rebranding: The Developer’s Laboratory</h2>
        <p>
            In late December 2025, the homepage was updated to reflect the project's core mission: serving as a 
            <strong>Developer’s Laboratory</strong>. This shift emphasizes educational empowerment over simple content management.
        </p>
    </section>

    <section>
        <h2>7. Education: The Developer’s Lab Manual</h2>
        <p>
            The project was further enhanced with the creation of a comprehensive <strong>Lab Manual</strong>. 
            This interactive guide provides students with specific exercises in modern architecture, security, 
            and testing, solidifying the CMS's role as a learning laboratory.
        </p>
        <p><strong>Educational Tools:</strong> Added <em>The Developer’s Lab Manual</em>, a <em>Break-Fix Final Exam</em>, a <em>Windows 11 Setup Guide</em>, and a <em>Student Welcome Kit</em>.</p>
        <p><strong>Standards Adoption:</strong> Integrated <strong>RFC 9116 (security.txt)</strong> and established a formal <strong>Security Policy</strong> and <strong>Hall of Fame</strong> to teach Responsible Disclosure protocols.</p>
        <p><strong>Worksheets:</strong> Added <em>Modules 1-6</em>, the <em>Final Exam</em>, and the <em>Onboarding Kit</em>.</p>
        <p><strong>AI Synergy:</strong> Established the <strong>AI-Assisted Development</strong> workflow using Google Gemini and Antigravity.</p>
    </section>

    <p class="footer-note">
        <em>"Modernization without loss of simplicity."</em> — Harisfazillah Jamel & Google Gemini, 2025.
    </p>
</article>

<style>
.modernization-history h1 { color: #004d00; border-bottom: 2px solid #004d00; padding-bottom: 0.5rem; }
.modernization-history h2 { color: #006600; margin-top: 2rem; font-size: 1.4rem; }
.modernization-history section { margin-bottom: 2rem; }
.modernization-history ul { list-style-type: square; padding-left: 1.5rem; }
.modernization-history li { margin-bottom: 0.5rem; }
.modernization-history .intro { font-style: italic; color: #444; background: #f9fff9; padding: 1rem; border-left: 4px solid #004d00; }
.modernization-history .footer-note { margin-top: 3rem; text-align: center; color: #666; font-size: 0.9rem; }
</style>

<!-- Recent changelog entry -->
<section aria-label="recent-changelog" class="recent-changelog">
    <h2>Recent updates (2025-12-27)</h2>
    <ul>
        <li>Updated documentation and AI guidance: <code>.github/copilot-instructions.md</code>.</li>
        <li>Added example page and content partial: <code>about.php</code>, <code>contents/about-body.inc</code>.</li>
        <li>Minor fixes to include files and tests, plus PSR-12/PHPCS auto-fixes.</li>
        <li>Normalized repository line endings to LF for source files and verified style & tests.</li>
    </ul>
</section>
