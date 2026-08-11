<article class="legal-notice-page">
    <header class="legal-header">
        <h1>Legal Notice & Disclaimer</h1>
        <p class="tagline">Privacy Policy, Critical Assumptions & Disclaimer of Liability</p>
    </header>

    <div class="legal-notice-intro-card">
        <p>
            <strong>IMPORTANT NOTICE:</strong> All costs, designs, unit amounts, and scenarios detailed within this document and project are based entirely on assumptions. This project and its accompanying documentation are compiled strictly for training, educational, and planning proposal purposes. Use at your own risk. The project contributors, authors, and organisations shall not be held liable or responsible for any decisions or actions taken based on these materials.
        </p>
    </div>

    <section class="legal-sections">
        <div class="legal-card" id="purpose">
            <h2>1. Educational and Training Purpose</h2>
            <p>
                This project, including its architectural designs, playbooks, scripts, configuration templates, and associated documentation, is created strictly for training, educational, and planning proposal purposes only. It serves as an open learning reference and laboratory simulation for modern rootless deployment patterns.
            </p>
        </div>

        <div class="legal-card" id="assumptions">
            <h2>2. Reliance on Critical Assumptions</h2>
            <p>
                Please note that this project does not represent a live, production-grade deployment mandate for any specific enterprise environment without significant adaptation. In particular:
            </p>
            <ul>
                <li><strong>Infrastructure Design:</strong> All designs, node placements, and configuration specifications are completely based on hypothetical architectural assumptions.</li>
                <li><strong>Cost Estimations:</strong> Any and all financial calculations, resource costs, licensing designs, and hardware budgets are based entirely on assumptions and standard baseline estimations.</li>
                <li><strong>System Capacity & Units:</strong> The designated amount of units, specific subdirectories, hardware footprints, and simulated network scenarios are illustrative models intended for educational exercises and planning proposal purposes.</li>
            </ul>
        </div>

        <div class="legal-card" id="privacy">
            <h2>3. Privacy Statement & Data Protection</h2>
            <p>
                We are deeply committed to privacy and data protection. We have done our best to protect anyone and organisation referenced, simulated, or involved in the design and execution of this baseline.
            </p>
            <ul>
                <li><strong>Anonymised Metadata:</strong> All IP addresses, domains, names, and contact details used within the configurations, tests, and documentation are either strictly fictional, non-routable, or reserved documentation blocks (such as .internal or .example).</li>
                <li><strong>Zero Real-World Storage:</strong> This repository does not harvest, process, or store any actual personal identifying information (PII) of third parties.</li>
            </ul>
        </div>

        <div class="legal-card" id="liability">
            <h2>4. Assumption of Risk & Liability Disclaimer</h2>
            <p>
                Use of this project, its code, and its documents is at your own risk.
            </p>
            <ul>
                <li><strong>As-Is Basis:</strong> All files, playbooks, scripts, and documentation are provided "as-is" without warranty of any kind, express or implied.</li>
                <li><strong>Disclaimer of Responsibility:</strong> We are not going to be responsible. The project contributors, authors, and organisations shall not be held liable or responsible for any decisions or actions taken based on these materials, including service interruptions, system crashes, security incidents, loss of data, or any other damages arising from the implementation or adaptation of these materials.</li>
                <li><strong>User Responsibility:</strong> Users assume full responsibility for validating and securing their own infrastructure and configurations before applying any patterns demonstrated in this repository.</li>
            </ul>
        </div>
    </section>

    <blockquote class="legal-quote">
        "We have done our best to protect anyone and organisation. Use by your own risks."
    </blockquote>

    <div class="legal-footer">
        <p>
            [ REGULATION: DISCLAIMER ] | [ PURPOSE: TRAINING ] | [ RISK: ASSUMED ]
        </p>
    </div>
</article>

<style>
    :root {
        --legal-primary: #2c3e50;
        --legal-accent: #8e44ad;
        --legal-light: #f6f8fa;
        --legal-border-color: rgba(142, 68, 173, 0.2);
    }
    .legal-notice-page {
        max-width: 900px;
        margin: 0 auto;
        line-height: 1.8;
        color: var(--lab-text, #333);
        padding: 20px;
    }
    .legal-header {
        text-align: center;
        margin-bottom: 40px;
    }
    .legal-header h1 {
        font-size: 2.8rem;
        color: var(--lab-text, var(--legal-primary));
        margin-bottom: 10px;
        position: relative;
        display: inline-block;
    }
    .legal-header h1::after {
        content: '';
        display: block;
        width: 80px;
        height: 4px;
        background: var(--legal-accent);
        margin: 10px auto 0;
        border-radius: 2px;
    }
    .legal-header .tagline {
        font-size: 1.1rem;
        color: var(--lab-muted, #7f8c8d);
        font-weight: 500;
    }

    .legal-notice-intro-card {
        background: var(--lab-bg, #fff);
        border: 1px solid var(--lab-border, #eee);
        border-left: 6px solid #e74c3c;
        padding: 25px 30px;
        border-radius: 8px;
        margin-bottom: 40px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }
    .legal-notice-intro-card p {
        margin: 0;
        font-size: 1.05rem;
        color: var(--lab-text, #333);
    }

    .legal-card {
        background: var(--lab-bg, #fff);
        border: 1px solid var(--lab-border, #eee);
        border-top: 4px solid var(--legal-accent);
        padding: 30px;
        border-radius: 8px;
        margin-bottom: 30px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .legal-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(142, 68, 173, 0.1);
    }
    .legal-card h2 {
        margin-top: 0;
        color: var(--legal-accent);
        font-size: 1.5rem;
        margin-bottom: 15px;
    }
    .legal-card ul {
        margin-top: 15px;
        padding-left: 20px;
    }
    .legal-card li {
        margin-bottom: 10px;
    }

    .legal-quote {
        font-size: 1.25rem;
        font-style: italic;
        color: var(--lab-text, #555);
        background: var(--lab-bg, #f8f9fa);
        border: 1px solid var(--lab-border, #eee);
        border-left: 5px solid var(--legal-accent);
        border-radius: 8px;
        padding: 30px;
        text-align: center;
        margin: 40px 0;
    }

    .legal-footer {
        text-align: center;
        margin-top: 50px;
        padding: 20px;
        font-family: 'SF Mono', monospace;
        font-size: 0.85rem;
        color: var(--lab-muted, #94a3b8);
        border-top: 1px dashed var(--lab-border, #eee);
    }

    @media (max-width: 768px) {
        .legal-header h1 { font-size: 2rem; }
    }
</style>
