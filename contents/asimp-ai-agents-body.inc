<article class="ai-asimp-guide" itemscope itemtype="https://schema.org/TechArticle">
    <header class="guide-header">
        <h1 itemprop="headline">🛡️ ASIMP for AI Agents: Metacognitive Auditing</h1>
        <p class="subtitle">Enforcing Sovereign Security with "Measure, Harden, Re-Measure" loops</p>
    </header>

    <div class="intro-box" itemprop="description">
        <p>
            Within the <strong>CMSForNerd v4.3.1</strong> ecosystem, security is not an afterthought.
            The <strong>Ansible System Integrity Management Platform (ASIMP)</strong> is a host-based,
            automated security hardening and compliance framework. Following the <strong>Deep State of Mind (DSOM)</strong>
            principles of <strong>My AI Protocol</strong>, AI Agents leverage ASIMP to execute safe, idempotent system-wide integrity modifications.
        </p>
    </div>

    <section class="workflow-grid">
        <div class="workflow-card dsom">
            <h2>🧠 Deep State of Mind (DSOM)</h2>
            <p class="role">The Cognitive Framework</p>
            <ul>
                <li><strong>Sovereign Rulebook:</strong> Driven by core constitutional laws in <code>AGENTS.md</code>.</li>
                <li><strong>Spatial Memory:</strong> Uses externalised local state in <code>.agents/brain/</code>.</li>
                <li><strong>Progressive Disclosure:</strong> Indexes knowledge with zero-cost metadata.</li>
            </ul>
        </div>

        <div class="workflow-card asimp">
            <h2>🛡️ ASIMP Controls</h2>
            <p class="role">The Execution System</p>
            <ul>
                <li><strong>Multi-Engine Scans:</strong> Leverages Lynis and OpenSCAP compliance tests.</li>
                <li><strong>Sandbox Isolation:</strong> Bypasses unprivileged locks via mock scripts.</li>
                <li><strong>Idempotency:</strong> Declarative state enforcement using Ansible.</li>
            </ul>
        </div>
    </section>

    <section class="dual-mode-section">
        <h2>🌐 Execution Modes: Sandbox vs. Production</h2>
        <p>To support unprivileged environments, ASIMP separates runtime modes cleanly:</p>

        <div class="mode-grid">
            <div class="mode-card sandbox">
                <h3>Unprivileged Sandbox</h3>
                <p class="tech-detail">Google Jules Workspace</p>
                <code class="code-snippet">bash tools/mock-asimp.sh</code>
                <p>Simulates scans and writes JSON results to unprivileged paths under <code>data/asimp_mock/</code>.</p>
            </div>

            <div class="mode-card production">
                <h3>Production Server</h3>
                <p class="tech-detail">Real target host Virtual Machines</p>
                <code class="code-snippet">ansible-playbook -i ... --become</code>
                <p>Applies real system-level hardening (sysctl adjustments, SSH lockdowns, OpenSCAP remediation).</p>
            </div>
        </div>
    </section>

    <section class="integration-details">
        <h2>🛠️ Memory Channels & Metacognitive Integration</h2>
        <p>When executing an ASIMP cycle, the AI Agent reads and updates specific components:</p>
        <table class="compliance-table">
            <thead>
                <tr>
                    <th>Memory File</th>
                    <th>ASIMP Alignment</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><code>AGENTS.md</code></td>
                    <td>Sovereign rulebook governing legal compliance, standard UK English, and FOSS priorities.</td>
                </tr>
                <tr>
                    <td><code>.agents/brain/task.md</code></td>
                    <td>Contains current ASIMP targets (e.g., Target Lynis Hardening Index of 85+).</td>
                </tr>
                <tr>
                    <td><code>.agents/brain/walkthrough.md</code></td>
                    <td>Persistent history of audit outputs, remediation actions, and test exit codes.</td>
                </tr>
                <tr>
                    <td><code>.agents/brain/palace_registry.md</code></td>
                    <td>Spatial index pointing to stored compliance reports and security logs.</td>
                </tr>
            </tbody>
        </table>
    </section>

    <nav class="footer-nav">
        <p>Understand the security integration? Explore further guidelines.</p>
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

    .compliance-table { width: 100%; border-collapse: collapse; margin-top: 20px; background: var(--glass-bg); border-radius: 8px; overflow: hidden; border: 1px solid var(--border-color); }
    .compliance-table th, .compliance-table td { padding: 12px 15px; text-align: left; border-bottom: 1px solid var(--border-color); }
    .compliance-table th { background-color: rgba(13, 148, 136, 0.2); color: #fff; font-weight: bold; }
    .compliance-table td { color: #d1d5db; }

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
