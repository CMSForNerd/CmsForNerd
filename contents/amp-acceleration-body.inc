<article class="amp-lab">
    <header class="lab-header">
        <h1>⚡ AMP Acceleration Engineering</h1>
        <p class="lead">Understanding the dual-view architecture and mobile performance optimization in CMSForNerd v3.5.</p>
    </header>

    <section class="ui-section">
        <h2>🚀 The Mission: Instant Mobile Load</h2>
        <p>CMSForNerd leverages Google's <strong>Accelerated Mobile Pages (AMP)</strong> framework to provide a near-instant loading experience on mobile devices. Our implementation focuses on three pillars:</p>
        <ol>
            <li><strong>HTML Restriction</strong>: Stripping away heavy JavaScript in favor of AMP components.</li>
            <li><strong>CSS Efficiency</strong>: Enforcing a strict 75KB inline style limit.</li>
            <li><strong>Pre-rendering</strong>: Allowing search engines to safely cache and pre-render the entire page.</li>
        </ol>
    </section>

    <section class="ui-section">
        <h2>🛠️ Dual-View Controller Logic</h2>
        <p>The core engine uses a "Shared State" pattern. A single page controller (like <code>index.php</code>) handles both standard and AMP requests by detecting the <code>view=amp</code> query parameter.</p>
        <div class="code-compare" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin: 20px 0;">
            <div style="background: var(--lab-border, #f1f5f9); padding: 15px; border-radius: 8px;">
                <strong>Standard View</strong>
                <p style="font-size: 0.8rem;">Loads PWA features, Service Workers, and full CSS (<code>style.css</code>).</p>
            </div>
            <div style="background: var(--lab-border, #f1f5f9); padding: 15px; border-radius: 8px;">
                <strong>⚡ AMP View</strong>
                <p style="font-size: 0.8rem;">Loads <code>amp.css</code>, AMP components, and enforces strict validation.</p>
            </div>
        </div>
    </section>

    <section class="ui-section">
        <h2>📐 Managing the CSS Byte-Budget</h2>
        <p>AMP requires all CSS to be inline and under 75KB. CMSForNerd automates this by:</p>
        <ul>
            <li><strong>Variable Synchronization</strong>: Using the same CSS variables (<code>--lab-purple</code>, etc.) in both <code>style.css</code> and <code>amp.css</code>.</li>
            <li><strong>Component Stripping</strong>: Removing desktop-only styles (like complex interactions) from the AMP payload.</li>
            <li><strong>Inline Injection</strong>: The <code>pager.php</code> engine dynamically injects the optimized CSS into the <code>&lt;style amp-custom&gt;</code> tag.</li>
        </ul>
    </section>

    <section class="ui-section">
        <h2>📱 Interactive Components</h2>
        <p>Even though AMP limits custom JavaScript, we maintain interactivity using AMP-specific components:</p>
        <table class="stack-table">
            <thead>
                <tr>
                    <th>Component</th>
                    <th>Role in Laboratory</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><code>amp-sidebar</code></td>
                    <td>Handles mobile navigation without blocking the main thread.</td>
                </tr>
                <tr>
                    <td><code>amp-bind</code></td>
                    <td>Enables state management for features like the Laboratory Dimmer (Dark Mode).</td>
                </tr>
                <tr>
                    <td><code>amp-img</code></td>
                    <td>Ensures layout stability (Cumulative Layout Shift) by requiring aspect ratios.</td>
                </tr>
            </tbody>
        </table>
    </section>

    <footer class="audit-footer">
        <p>View this page in AMP mode: <a href="?view=amp">⚡ Switch to AMP</a></p>
    </footer>
</article>

<style>
.amp-lab { max-width: 900px; margin: 0 auto; line-height: 1.6; }
.ui-section { margin-bottom: 3rem; padding-bottom: 2rem; border-bottom: 1px solid var(--lab-border); }
.code-compare strong { color: var(--lab-highlight, #8e44ad); display: block; margin-bottom: 5px; }
.stack-table { width: 100%; border-collapse: collapse; margin: 20px 0; }
.stack-table th, .stack-table td { text-align: left; padding: 12px; border-bottom: 1px solid var(--lab-border); }
.audit-footer { text-align: center; font-style: italic; margin-top: 2rem; }
</style>
