<article class="graduation-page">
    <header class="graduation-header no-print">
        <h1>🏁 Curriculum Complete</h1>
        <p class="congrats">
            You have successfully transformed a legacy script into a secure, 
            standards-compliant PHP 8.4 application.
        </p>
    </header>

    <div class="certificate-container">
        <div class="certificate">
            <div class="cert-border-outer">
                <div class="cert-border-inner">
                    <div class="cert-content">
                        <span class="cert-seal">🎓</span>
                        <h2 class="cert-title">Certificate of Completion</h2>
                        <h3 class="cert-subtitle">PHP 8.4+ Modernization Mastery</h3>
                        
                        <div class="cert-award">
                            <p>This certifies that</p>
                            <div class="student-name">
                                <?= \CmsForNerd\SecurityUtils::escapeHtml((string) ($_GET['student'] ?? '[Your Name Here]')) ?>
                            </div>
                        </div>

                        <p class="cert-text">
                            Has demonstrated professional proficiency in <strong>Modern PHP Architecture</strong>, 
                            <strong>Defensive Engineering</strong>, and <strong>Automated Testing</strong> 
                            within the CMSForNerd v4.3.0 Lab environment.
                        </p>

                        <table class="competency-table">
                            <thead>
                                <tr>
                                    <th>Core Competency</th>
                                    <th>Standard Achieved</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td>Modern Architecture</td><td>PHP 8.4 Property Hooks & Hooks</td></tr>
                                <tr><td>Security Hygiene</td><td>Level 2 CSP & Nonce Implementation</td></tr>
                                <tr><td>Quality Assurance</td><td>PHPUnit 11 & >90% Code Coverage</td></tr>
                            </tbody>
                        </table>

                        <div class="signatures">
                            <div class="signature">
                                <div class="sig-line"></div>
                                <p>Harisfazillah Jamel</p>
                                <span>Lead Developer, CMSForNerd</span>
                            </div>
                            <div class="signature">
                                <div class="sig-line"></div>
                                <p>Gemini AI</p>
                                <span>Thought Partner & Auditor</span>
                            </div>
                        </div>

                        <div class="footer-meta">
                            <p>Verification Date: <?= date('F j, Y') ?></p>
                            <p class="digital-sig">SHA-256 ID: <?= \CmsForNerd\SecurityUtils::escapeHtml(hash('sha256', (string) ($_GET['student'] ?? 'anon') . date('Y-m-d'))) ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="next-steps no-print">
        <h2>🛠️ Teacher's Instructions</h2>
        <div class="instruction-box">
            <ul>
                <li><strong>To Issue:</strong> Send students the link: <code>graduation.php?student=Full+Name</code></li>
                <li><strong>To Print:</strong> Press <kbd>Ctrl + P</kbd>. The layout is optimized to hide UI elements and print only the certificate.</li>
            </ul>
        </div>
    </section>
</article>
