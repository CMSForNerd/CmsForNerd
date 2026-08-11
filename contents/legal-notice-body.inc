<article class="ln-container">
    <header class="ln-title-area">
        <h1>Legal Notice & Disclaimer</h1>
        <p class="ln-lead">Privacy Policy, Critical Assumptions & Disclaimer of Liability</p>
    </header>

    <div class="ln-warning-banner">
        <p>
            <strong>IMPORTANT NOTICE:</strong> All costs, designs, unit amounts, and scenarios detailed within this document and project are based entirely on assumptions. This project and its accompanying documentation are compiled strictly for training, educational, and planning proposal purposes. Use at your own risk. The project contributors, authors, and organisations shall not be held liable or responsible for any decisions or actions taken based on these materials.
        </p>
    </div>

    <section class="ln-grid">
        <div class="ln-box-item" id="purpose">
            <h2>1. Educational and Training Purpose</h2>
            <p>
                This project, including its architectural designs, playbooks, scripts, configuration templates, and associated documentation, is created strictly for training, educational, and planning proposal purposes only. It serves as an open learning reference and laboratory simulation for modern rootless deployment patterns.
            </p>
        </div>

        <div class="ln-box-item" id="assumptions">
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

        <div class="ln-box-item" id="privacy">
            <h2>3. Privacy Statement & Data Protection</h2>
            <p>
                We are deeply committed to privacy and data protection. We have done our best to protect anyone and organisation referenced, simulated, or involved in the design and execution of this baseline.
            </p>
            <ul>
                <li><strong>Anonymised Metadata:</strong> All IP addresses, domains, names, and contact details used within the configurations, tests, and documentation are either strictly fictional, non-routable, or reserved documentation blocks (such as .internal or .example).</li>
                <li><strong>Zero Real-World Storage:</strong> This repository does not harvest, process, or store any actual personal identifying information (PII) of third parties.</li>
            </ul>
        </div>

        <div class="ln-box-item" id="liability">
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

    <blockquote class="ln-quote-highlight">
        "We have done our best to protect anyone and organisation. Use by your own risks."
    </blockquote>

    <div class="ln-footer-tag">
        <p>
            [ REGULATION: DISCLAIMER ] | [ PURPOSE: TRAINING ] | [ RISK: ASSUMED ]
        </p>
    </div>
</article>

<style>
    .ln-container {
        max-width: 880px;
        margin: 10px auto;
        line-height: 1.75;
        color: var(--lab-text, #1e293b);
        padding: 15px;
    }
    .ln-title-area {
        text-align: center;
        margin-bottom: 35px;
    }
    .ln-title-area h1 {
        font-size: 2.6rem;
        color: var(--lab-text, #0f172a);
        margin-bottom: 8px;
        position: relative;
        display: inline-block;
    }
    .ln-title-area h1::after {
        content: '';
        display: block;
        width: 75px;
        height: 4px;
        background: var(--lab-purple, #8e44ad);
        margin: 8px auto 0;
        border-radius: 2px;
    }
    .ln-lead {
        font-size: 1.15rem;
        color: var(--lab-muted, #64748b);
        font-weight: 500;
    }

    .ln-warning-banner {
        background: var(--lab-bg, #f8f9fa);
        border: 1px solid var(--lab-border, #e2e8f0);
        border-left: 5px solid #e74c3c;
        padding: 20px 25px;
        border-radius: 6px;
        margin-bottom: 35px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.03);
    }
    .ln-warning-banner p {
        margin: 0;
        font-size: 1rem;
    }

    .ln-box-item {
        background: var(--lab-bg, #f8f9fa);
        border: 1px solid var(--lab-border, #e2e8f0);
        border-top: 4px solid var(--lab-purple, #8e44ad);
        padding: 25px;
        border-radius: 6px;
        margin-bottom: 25px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
    }
    .ln-box-item h2 {
        margin-top: 0;
        color: var(--lab-purple, #8e44ad);
        font-size: 1.45rem;
        margin-bottom: 12px;
    }
    .ln-box-item ul {
        margin-top: 10px;
        padding-left: 18px;
    }
    .ln-box-item li {
        margin-bottom: 8px;
    }

    .ln-quote-highlight {
        font-size: 1.2rem;
        font-style: italic;
        color: var(--lab-text, #1e293b);
        background: var(--lab-bg, #f8f9fa);
        border: 1px solid var(--lab-border, #e2e8f0);
        border-left: 5px solid var(--lab-purple, #8e44ad);
        border-radius: 6px;
        padding: 25px;
        text-align: center;
        margin: 35px 0;
    }

    .ln-footer-tag {
        text-align: center;
        margin-top: 45px;
        padding: 15px;
        font-family: var(--f-mono, monospace);
        font-size: 0.8rem;
        color: var(--lab-muted, #94a3b8);
        border-top: 1px dashed var(--lab-border, #e2e8f0);
    }

    @media (max-width: 768px) {
        .ln-title-area h1 { font-size: 1.95rem; }
    }
</style>
