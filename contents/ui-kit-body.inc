<article class="ui-lab">
    <header class="lab-header">
        <h1>🧪 Laboratory UI Audit Kit</h1>
        <p class="lead">Technical verification of theme tokens, glassmorphism, and component accessibility for CMSForNerd v3.5.</p>
    </header>

    <section class="ui-section">
        <h2>🎨 Design System: Color Palette</h2>
        <div class="color-grid">
            <div class="color-swatch" style="background: var(--lab-purple); color: white;">
                <span>--lab-purple</span>
                <code>#8e44ad / #a855f7</code>
            </div>
            <div class="color-swatch" style="background: var(--lab-bg); color: var(--lab-text); border: 1px solid var(--lab-border);">
                <span>--lab-bg</span>
                <code>Background Color</code>
            </div>
            <div class="color-swatch" style="background: var(--lab-border); color: var(--lab-text);">
                <span>--lab-border</span>
                <code>#ecf0f1 / #333333</code>
            </div>
            <div class="color-swatch" style="background: var(--lab-text); color: var(--lab-bg);">
                <span>--lab-text</span>
                <code>System Text Color</code>
            </div>
        </div>
    </section>

    <section class="ui-section">
        <h2>🖋️ Typography & Hierarchy</h2>
        <div class="typography-sample">
            <h1>Heading Level 1 (Lab Master)</h1>
            <p>Fundamental body text for technical documentation. Uses system-native stacks for high performance.</p>
            
            <h2>Heading Level 2 (Section Admin)</h2>
            <p>Secondary level for module categorization.</p>
            
            <h3>Heading Level 3 (Fragment Slave)</h3>
            <p>Tertiary level for content snippets and small details.</p>
            
            <code>// Monospaced Code Block Output
printf("CMSForNerd v3.5 Stable\n");</code>
        </div>
    </section>

    <section class="ui-section">
        <h2>✨ Glassmorphism & Surface Effects</h2>
        <div class="glass-container" style="background: linear-gradient(135deg, var(--lab-purple), #3498db); padding: 40px; border-radius: 12px; margin: 20px 0;">
            <div class="glass-card" style="background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); padding: 30px; border-radius: 15px; color: white;">
                <h3>Glass Card (Standard)</h3>
                <p>Verify that the blur effect and border-stroke remain visible against dark and light gradients.</p>
                <a href="#" class="btn" style="background: white; color: var(--lab-purple); width: auto; display: inline-block; padding: 10px 20px;">Glass Button</a>
            </div>
        </div>
    </section>

    <section class="ui-section">
        <h2>🛠️ Components: Buttons & Badges</h2>
        <div class="component-demo">
            <div style="display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 20px;">
                <span class="badge php-version">PHP 8.4</span>
                <span class="badge status-strict">Strict Mode</span>
                <span class="badge status-check">Verified</span>
                <span class="badge status-sec">Secure</span>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div>
                    <a href="#" class="btn">Primary Action Button</a>
                </div>
                <div>
                    <button class="btn" style="opacity: 0.7;">Secondary/Disabled State</button>
                </div>
            </div>
        </div>
    </section>

    <section class="ui-section">
        <h2>📊 Data Visualization: Tables</h2>
        <div class="table-scroll">
            <table class="stack-table">
                <thead>
                    <tr>
                        <th>Metric</th>
                        <th>Standard View</th>
                        <th>AMP View</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Load Time</strong></td>
                        <td>< 200ms</td>
                        <td>< 100ms</td>
                        <td>✅ Optimized</td>
                    </tr>
                    <tr>
                        <td><strong>Interactivity</strong></td>
                        <td>Full (JS/PWA)</td>
                        <td>Limited (AMP)</td>
                        <td>⚖️ Verified</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    <footer class="audit-footer">
        <p>End of UI Audit. If components appear broken, verify <code>themes/CmsForNerd/style.css</code> variables.</p>
    </footer>
</article>

<style>
.ui-lab { max-width: 900px; margin: 0 auto; line-height: 1.6; }
.ui-section { margin-bottom: 3rem; padding-bottom: 2rem; border-bottom: 1px solid var(--lab-border); }
.color-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; }
.color-swatch { padding: 25px; border-radius: 8px; display: flex; flex-direction: column; font-weight: bold; }
.color-swatch code { font-size: 0.8rem; margin-top: 5px; opacity: 0.8; }
.typography-sample { padding: 20px; background: var(--lab-border); border-radius: 8px; }
.table-scroll { overflow-x: auto; }
.audit-footer { text-align: center; font-style: italic; color: var(--lab-muted); margin-top: 2rem; }
@media (max-width: 600px) {
    .color-grid { grid-template-columns: 1fr; }
}
</style>
