<article class="lab-manual">
    <h1>🎓 CMSForNerd v4.3.0: The Developer’s Lab Manual</h1>
    <p class="intro">
        Welcome to the v4.3.0 educational suite. This CMS is designed to be a <strong>transparent laboratory</strong>.
        Every line of code is accessible, every architectural choice is documented, and every security feature 
        is a lesson in professional standards.
    </p>

    <section class="module">
        <h2>📚 Laboratory Module 1: Modern PHP 8.4+ Architecture</h2>
        <p>In this module, students explore the transition from procedural scripts to modern object-oriented patterns, optimized for PHP 8.4 and ready for PHP 9.</p>
        <div class="exercise">
            <h3>Exercise 1.1: Strict Typing</h3>
            <p>Observe how <code>declare(strict_types=1);</code> prevents the CMS from accepting incorrect data types, forcing developers to write predictable code.</p>
            <p><a href="lab-module1.php" class="btn">📝 Open Student Worksheet: Module 1</a></p>
        </div>
        <div class="exercise">
            <h3>Exercise 1.2: The Context Pattern</h3>
            <p>See how the <code>CmsContext</code> object replaces the "Global Variable" anti-pattern, teaching the principle of <strong>Single Source of Truth</strong>.</p>
        </div>
        <div class="exercise">
            <h3>Exercise 1.3: Property Hooks (PHP 8.4)</h3>
            <p>Learn to use PHP 8.4 hooks to automate data validation within class properties, reducing boilerplate code.</p>
        </div>
    </section>

    <section class="module">
        <h2>⚖️ Laboratory Module 2: Standards & Compliance</h2>
        <p>Students learn that professional code is defined by its adherence to global standards.</p>
        <p><a href="lab-module2.php" class="btn">📝 Open Student Worksheet: Module 2</a></p>
        <ul>
            <li><strong>Requirement Level (RFC 2119):</strong> We use <em>MUST</em>, <em>SHOULD</em>, and <em>MAY</em> to define project boundaries.
                <ul>
                    <li>Rule: All templates <strong>MUST</strong> be HTML5 compliant.</li>
                    <li>Rule: Security utilities <strong>MUST NOT</strong> be bypassed by the core loader.</li>
                </ul>
            </li>
            <li><strong>PSR-12 Linting:</strong> Use the integrated <code>composer check-style</code> command to identify and fix visual inconsistencies in your code.</li>
        </ul>
        <div class="exercise">
            <h3>Exercise 2.3: Semantic Web Metadata</h3>
            <p>Understand how <strong>RDF</strong> and <strong>JSON-LD</strong> help AI tools categorize your educational content.</p>
            <ul>
                <li>View <code>labels.rdf</code> to see Dublin Core metadata (legacy W3C standard).</li>
                <li>Examine <code>common-headertag.inc</code> to see dynamic JSON-LD generation using Schema.org.</li>
                <li><strong>Verification:</strong> Use <a href="https://validator.schema.org/" target="_blank">Google's Structured Data Testing Tool</a> to validate your JSON-LD.</li>
            </ul>
        </div>
    </section>

    <section class="module">
        <h2>🛡️ Laboratory Module 3: Defensive Engineering</h2>
        <p>Security is the "Red Team vs. Blue Team" playground of CMSForNerd. Master both input hardening and perimeter defense.</p>
        <div class="lab-box">
            <h3>Lab: Perimeter & Path Traversal</h3>
            <p>Students are challenged to implement <strong>Defense-in-Depth</strong> by securing the file loader and configuring a <strong>Content Security Policy (CSP)</strong>.</p>
            <p><a href="lab-module3.php" class="btn">📝 Open Student Worksheet: Module 3</a></p>
        </div>
        <div class="lab-box">
            <h3>Lab 3.2: CSP Nonces (2025 Best Practice)</h3>
            <p>Learn to configure the Content Security Policy (CSP) with <strong>cryptographic nonces</strong> instead of dangerous <code>'unsafe-inline'</code> directives.</p>
            <ul>
                <li><strong>Concept:</strong> A nonce is a "number used once" - a random token that changes every page load.</li>
                <li><strong>Implementation:</strong> Review how <code>SecurityUtils::generateNonce()</code> creates cryptographically secure tokens.</li>
                <li><strong>Exercise:</strong> Inspect the CSP header and find the <code>nonce-XXXXX</code> value. Compare it to the <code>&lt;script nonce=&quot;....&quot;&gt;</code> in your JSON-LD block.</li>
                <li><strong>Challenge:</strong> Try injecting a script without a nonce using browser DevTools - observe how CSP blocks it.</li>
            </ul>
        </div>
        <div class="lab-box security-disclosure">
            <h3>Module 3.5: Responsible Disclosure (RFC 9116)</h3>
            <p>Understand how to communicate with security researchers using the <code>security.txt</code> standard.</p>
            <ul>
                <li><strong>Objective:</strong> Learn to establish a protocol for ethical vulnerability reporting.</li>
                <li><strong>Exercise 3.5a:</strong> Verify the machine-readable <code>/.well-known/security.txt</code> file in your browser.</li>
                <li><strong>Exercise 3.5b (Simulation):</strong> Draft a simulated email for an SQL Injection discovery following the <a href="security-policy.php">Security Policy</a> rules.</li>
            </ul>
        </div>
    </section>

    <section class="module">
        <h2>🧪 Laboratory Module 4: Automated Testing (TDD)</h2>
        <p>Master the art of Test-Driven Development using <strong>PHPUnit 11</strong>.</p>
        <div class="exercise">
            <h3>Exercise: Write a new test</h3>
            <p>Create a test in <code>tests/ThemeIntegrityTest.php</code> that fails if a student accidentally deletes the <code>style.css</code> file.</p>
            <p><a href="lab-module4.php" class="btn">📝 Open Student Worksheet: Module 4</a></p>
        </div>
        <p><strong>Concept: "Green-Light Thinking"</strong>—if the tests aren't green, the code isn't ready.</p>
    </section>

    <section class="module">
        <h2>📊 Laboratory Module 5: Test Coverage and QA</h2>
        <p>Learn to visualize the safety net of your application using Code Coverage reports.</p>
        <div class="exercise">
            <h3>Exercise: Generate a Coverage Report</h3>
            <p>Use Xdebug and PHPUnit to create an interactive HTML report to identify "dark spots" in your code.</p>
            <p><a href="lab-module5.php" class="btn">📝 Open Student Worksheet: Module 5</a></p>
        </div>
        <p><strong>Concept: "Confidence in Code"</strong>—100% coverage doesn't mean bug-free, but it means everything was tested.</p>
    </section>

    <section class="module ai-module">
        <h2>🤖 Laboratory Module 6: AI-Assisted Workflow</h2>
        <p>Learn to lead an "AI Agent" as your junior developer. Master the Prompt-to-Product methodology.</p>
        <div class="exercise">
            <h3>Exercise 6.1: The Agentic Challenge</h3>
            <p>Use Gemini/Antigravity to create a new page defined by a single sentence. Review the implementation plan and verify the automated build.</p>
            <p><a href="ai-dev.php" class="btn">🚀 Open AI Dev Guide</a></p>
        </div>
        <div class="exercise">
            <h3>Exercise 6.2: AI Ethics & Responsible Usage</h3>
            <p>Study the <strong>Standard Operating Procedure #2025-01</strong> for ethical AI integration.</p>
            <ul>
                <li>Read the <a href="ai-sop.php">📜 AI Ethics SOP</a> to understand the "Think First" rule and "Trust but Verify" law.</li>
                <li><strong>Practice:</strong> Before asking AI for help, attempt problem-solving for 10 minutes using existing documentation.</li>
                <li><strong>Verification Loop:</strong> Always run <code>composer compliance</code> on AI-generated code before merging.</li>
            </ul>
        </div>
        <div class="exercise">
            <h3>Exercise 6.3: Cross-Platform Configuration</h3>
            <p>Learn how to make your project AI-aware and portable across Windows/Linux environments.</p>
            <ul>
                <li>Examine <code>.cursorrules</code> - the "AI Configuration File" that teaches ChatGPT/Gemini your project standards.</li>
                <li>Review <code>.vscode/settings.json</code> - note how it avoids hardcoded paths for cross-platform safety.</li>
                <li>Check <code>.gitattributes</code> - ensures GitHub recognizes <code>.inc</code> files as PHP.</li>
                <li><strong>Outcome:</strong> Your project is now "AI-native" and works seamlessly on any development environment.</li>
            </ul>
        </div>
    </section>

    <section class="module exam-module">
        <h2>🚩 The Final Exam: Break-Fix Challenge</h2>
        <p>The ultimate test of a modern backend engineer. Repair a broken system to prove your mastery.</p>
        <div class="exercise">
            <h3>Challenge: Repair the Laboratory</h3>
            <p>You must fix 5 deliberate security and logic errors to pass the certification audit.</p>
            <p><a href="final-exam.php" class="btn">🚀 Start the Final Exam</a></p>
        </div>
    </section>

    <hr>

    <section class="case-study">
        <h2>📝 The History of v4.3.0: A Modernisation Case Study</h2>
        <p>CMSForNerd v4.3.0 isn't just a version; it's a <strong>Modernisation Journey</strong>.</p>
        <ol>
            <li><strong>Phase 1:</strong> Refactored the 2005 foundation into PHP 8.4+ classes with PHP 9 readiness. See the <a href="template.php">Template Guide</a>.</li>
            <li><strong>Phase 2:</strong> Standardized the UI with CSS Grid (replacing legacy float-based layouts).</li>
            <li><strong>Phase 3:</strong> Hardened the perimeter with Cloudflare Turnstile and <code>SecurityUtils</code>.</li>
            <li><strong>Phase 4:</strong> Automated the workflow with PHPUnit and <code>PHP_CodeSniffer</code>.</li>
            <li><strong>Phase 5:</strong> Visualized quality with Code Coverage and the CRAP index.</li>
            <li><strong>Phase 6:</strong> Integrated AI-native configuration (<code>.cursorrules</code>, JSON-LD, CSP nonces) for 2025 standards.</li>
        </ol>
        <div class="graduation-cta">
            <h3>🎓 Ready to Graduate?</h3>
            <p>If you have completed all 5 modules and passed all compliance audits, you are ready to claim your certificate.</p>
            <p><a href="graduation.php" class="btn graduation-btn">🏁 Claim Your Certificate of Completion</a></p>
        </div>
        <p class="motto"><strong>Educational Motto:</strong> "Modernisation without loss of simplicity." — Harisfazillah Jamel & Gemini, 2025.</p>
    </section>
</article>

<style>
/* ... existing styles ... */
.graduation-cta { background: #fffbeb; border: 2px solid #fde68a; padding: 2rem; border-radius: 8px; text-align: center; margin: 2rem 0; }
.graduation-cta h3 { color: #92400e; margin-top: 0; }
.graduation-btn { background: #10b981; color: #fff; font-size: 1.2rem; padding: 1rem 2rem; }
.graduation-btn:hover { background: #059669; }
</style>

<style>
.lab-manual h1 { color: #004d00; border-bottom: 2px solid #004d00; padding-bottom: 0.5rem; }
.lab-manual h2 { color: #006600; margin-top: 2rem; border-left: 5px solid #006600; padding-left: 10px; }
.lab-manual section { margin-bottom: 3rem; background: #fff; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
.lab-manual .intro { font-size: 1.1rem; color: #333; line-height: 1.6; background: #e6f7ff; padding: 1.5rem; border-radius: 8px; border-left: 5px solid #007bff; }
.lab-manual .exercise, .lab-manual .lab-box { background: #f9f9f9; padding: 1rem; border-radius: 4px; border: 1px solid #ddd; margin: 1rem 0; }
.lab-manual .exercise h3, .lab-manual .lab-box h3 { margin-top: 0; color: #d9534f; }
.lab-manual .motto { text-align: center; font-style: italic; font-size: 1.2rem; color: #555; margin-top: 2rem; }
.lab-manual hr { border: 0; border-top: 2px dashed #ccc; margin: 3rem 0; }
</style>
