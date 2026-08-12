<article class="ai-asimp-guide" itemscope itemtype="https://schema.org/TechArticle">
    <header class="guide-header">
        <h1 itemprop="headline">🛡️ ASIMP-Driven Agent Security Compliance</h1>
        <p class="subtitle">Orchestrating Autonomous Host Auditing and Hardening Safely</p>
    </header>

    <div class="intro-box" itemprop="description">
        <p>
            Within the <strong>CmsForNerd v4.3.1</strong> system architecture, the <strong>Ansible System Integrity Management Platform</strong>
            functions as a decentralized mechanism for protecting computing nodes. Directed by the <strong>Deep State of Mind</strong>
            specification, our cognitive agents employ ASIMP to continuously assess platform posture, apply safe declarative definitions,
            and re-calculate compliance figures without administrative friction.
        </p>
    </div>

    <section class="workflow-grid">
        <div class="workflow-card dsom">
            <h2>🧠 Cognitive Governance (DSOM)</h2>
            <p class="role">Sovereign Rules & Local Memory</p>
            <ul>
                <li><strong>Constitutional Mandates:</strong> Restricts all autonomous activities to the directives detailed in <code>.agents/AGENTS.md</code>.</li>
                <li><strong>Spatial Memory Partitioning:</strong> Records operational walkthrough traces and anchors directly under <code>.agents/brain/task.md</code>, <code>.agents/brain/walkthrough.md</code>, and <code>.agents/brain/palace_registry.md</code>.</li>
                <li><strong>Low-Token Overhead:</strong> Utilises compact frontmatter structures for efficient context parsing and routing.</li>
            </ul>
        </div>

        <div class="workflow-card asimp">
            <h2>🛡️ Idempotent Execution (ASIMP)</h2>
            <p class="role">Scans & Host Level Audits</p>
            <ul>
                <li><strong>Systemic Diagnostics:</strong> Audits software package integrity using standard OpenSCAP rulesets and Lynis profiles.</li>
                <li><strong>Unprivileged Containment:</strong> Automatically defaults to simulated testing when local root capabilities are unavailable.</li>
                <li><strong>Declarative Hardening:</strong> Applies structured, self-correcting playbooks across physical or cloud environments.</li>
            </ul>
        </div>
    </section>

    <section class="dual-mode-section">
        <h2>🌐 Architectural Modes: Sandbox vs. Live</h2>
        <p>ASIMP dynamically shifts between two deployment schemes to adapt to modern unprivileged or fully-privileged targets:</p>

        <div class="mode-grid">
            <div class="mode-card sandbox">
                <h3>Unprivileged Mocking</h3>
                <p class="tech-detail">Google Jules Sandbox Mode</p>
                <code class="code-snippet">bash tools/mock-asimp.sh</code>
                <p>Simulates policy enforcement and records diagnostic databases inside the <code>data/asimp_mock/</code> subdirectory.</p>
            </div>

            <div class="mode-card production">
                <h3>Target VM Provisioning</h3>
                <p class="tech-detail">Production Host VM Mode</p>
                <code class="code-snippet">ansible-playbook -i inventory/hosts.yml playbooks/bootstrap_node.yml --become</code>
                <p>Directly locks down operating system options (disabling compiler access, securing SSH services, updating sysctl network values).</p>
            </div>
        </div>
    </section>

    <section class="integration-details">
        <h2>🛠️ Sovereign Memory Channels</h2>
        <p>While performing audits, the AI engine processes its telemetry via several specialized flat files:</p>
        <div class="memory-channels-list" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-top: 20px;">
            <div class="channel-item" style="background: var(--glass-bg); padding: 15px; border-radius: 8px; border: 1px solid var(--border-color);">
                <h3 style="color: var(--dsom-purple); margin-top: 0;">📜 Core Rules</h3>
                <p style="color: #d1d5db; font-size: 0.9rem; margin-bottom: 0;">The master rulebook <strong>.agents/AGENTS.md</strong> governs sovereign software ideals, FOSS constraints, and strict UK English phrasing guidelines.</p>
            </div>
            <div class="channel-item" style="background: var(--glass-bg); padding: 15px; border-radius: 8px; border: 1px solid var(--border-color);">
                <h3 style="color: var(--dsom-purple); margin-top: 0;">📋 Active Tasks</h3>
                <p style="color: #d1d5db; font-size: 0.9rem; margin-bottom: 0;">The local <strong>.agents/brain/task.md</strong> catalogues current system remediations, scan timelines, and overall target security indexes.</p>
            </div>
            <div class="channel-item" style="background: var(--glass-bg); padding: 15px; border-radius: 8px; border: 1px solid var(--border-color);">
                <h3 style="color: var(--dsom-purple); margin-top: 0;">⏳ Session Walkthroughs</h3>
                <p style="color: #d1d5db; font-size: 0.9rem; margin-bottom: 0;">The episodic <strong>.agents/brain/walkthrough.md</strong> tracks sequential terminal history, execution outputs, and audit validation marks.</p>
            </div>
            <div class="channel-item" style="background: var(--glass-bg); padding: 15px; border-radius: 8px; border: 1px solid var(--border-color);">
                <h3 style="color: var(--dsom-purple); margin-top: 0;">📂 Spatial Indexes</h3>
                <p style="color: #d1d5db; font-size: 0.9rem; margin-bottom: 0;">The master <strong>.agents/brain/palace_registry.md</strong> connects automation and indexing spiders directly to generated compliance reports.</p>
            </div>
        </div>
    </section>

    <nav class="footer-nav">
        <p>Finished reviewing the ASIMP control parameters? Continue with our core policies.</p>
        <div class="btn-group">
            <a href="security-policy.php" class="btn btn-secondary">🛡️ Security Policy</a>
            <a href="index.php" class="btn btn-primary">Return Home</a>
        </div>
    </nav>
</article>

<style>
    :root {
        --dsom-purple: #818cf8;
        --asimp-teal: #0d9488;
        --border-color: #374151;
        --glass-bg: rgba(31, 41, 55, 0.4);
    }

    .ai-asimp-guide { max-width: 900px; margin: 0 auto; line-height: 1.6; }
    .guide-header h1 { color: var(--asimp-teal); border-bottom: 3px solid var(--asimp-teal); padding-bottom: 10px; }
    .subtitle { font-size: 1.2rem; color: #9ca3af; font-style: italic; }

    .intro-box { background: var(--glass-bg); padding: 20px; border-radius: 8px; border-left: 5px solid var(--asimp-teal); margin: 25px 0; border-top: 1px solid var(--border-color); border-right: 1px solid var(--border-color); border-bottom: 1px solid var(--border-color); }

    .workflow-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 40px; }
    .workflow-card { padding: 20px; border: 1px solid var(--border-color); border-radius: 12px; background: var(--glass-bg); transition: transform 0.2s; }
    .workflow-card:hover { transform: translateY(-5px); box-shadow: 0 4px 15px rgba(0,0,0,0.3); }
    .workflow-card h2 { margin-top: 0; }
    .role { font-weight: bold; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 1px; margin-bottom: 15px; }

    .dsom h2 { color: var(--dsom-purple); }
    .dsom .role { color: var(--dsom-purple); }
    .asimp h2 { color: var(--asimp-teal); }
    .asimp .role { color: var(--asimp-teal); }

    .mode-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px; margin-top: 20px; }
    .mode-card { background: var(--glass-bg); padding: 15px; border-radius: 8px; border: 1px solid var(--border-color); }
    .code-snippet { display: block; background: #111827; color: #f3f4f6; padding: 8px; border-radius: 4px; font-size: 0.85rem; margin: 10px 0; font-family: 'Courier New', monospace; border: 1px solid #4b5563; }

    .footer-nav { margin-top: 50px; text-align: center; border-top: 1px solid var(--border-color); padding-top: 30px; }
    .btn-group { display: flex; gap: 10px; justify-content: center; margin-top: 15px; }
    .btn { padding: 12px 24px; border-radius: 6px; text-decoration: none; font-weight: bold; transition: background-color 0.2s; }
    .btn-primary { background: var(--asimp-teal); color: white; }
    .btn-primary:hover { background: #0f766e; }
    .btn-secondary { background: #374151; color: #f3f4f6; }
    .btn-secondary:hover { background: #4b5563; }

    @media (max-width: 600px) {
        .workflow-grid { grid-template-columns: 1fr; }
    }
</style>