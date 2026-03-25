<div class="content-body">
    <h1>📱 Progressive Web App (PWA) Architecture</h1>
    
    <p>
        The <strong>CmsForNerd v3.5 Laboratory</strong> features a meticulously crafted Progressive Web App (PWA) engine that bridges the gap between traditional server-side rendering (SSR) and modern local-first, memory-instant navigation.
    </p>

    <!-- PWA Highlights -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin: 30px 0;">
        
        <div class="scenario-box">
            <h3 style="margin-top:0;">🛡️ Service Worker (`sw.js`)</h3>
            <p>
                Our <strong>Strictly-Purging Service Worker</strong> operates using a <code>Stale-While-Revalidate</code> caching strategy for static assets (CSS, JS, Fonts), while defaulting to a <code>Network-First</code> mechanism for dynamic HTML fragments. It provides robust protection for offline or high-latency scenarios.
            </p>
        </div>

        <div class="scenario-box">
            <h3 style="margin-top:0;">⚡ Instant History (`bfcache`)</h3>
            <p>
                We engineered our SPA Hybrid router (`router.js`) to strictly adhere to the browser's <strong>Back/Forward Cache (bfcache)</strong> specifications. By employing the <code>AbortController</code> on <code>pagehide</code> events, we explicitly sever dangling network operations, enabling instantaneous navigation.
            </p>
        </div>

    </div>

    <h2>1. The SPA-Hybrid Hydration Strategy</h2>
    <p>
        Traditional Multi-Page Applications (MPAs) load slowly due to heavy layout redraws, while Single-Page Applications (SPAs) suffer from massive initial payloads. We deploy a hybrid architecture. When navigating:
    </p>
    <div class="terminal-block">
        <strong>Client:</strong> fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })<br>
        <strong>NGINX/Herd:</strong> Routes to index.php<br>
        <strong>Backend (PHP 8.4):</strong> detectAjax() -> Return fragment bypassing template.<br>
        <strong>Client:</strong> Hydrate `&lt;main&gt;` container via morphdom/innerHTML.
    </div>

    <h2>2. Installation & Resiliency (`manifest.json`)</h2>
    <p>
        Through our <code>manifest.json</code> metadata, CmsForNerd presents itself as a fully installable, native-feeling application on both iOS (PWA Home Screen) and Android. The laboratory ensures:
    </p>
    <ul>
        <li><strong><code>display: standalone</code></strong> - For a chromeless, immersive interface.</li>
        <li><strong>Offline Fallbacks</strong> - Automatic degradation to <code>/offline.php</code> if the network is disconnected and the resource isn't locally cached.</li>
        <li><strong>Security Standard</strong> - By strict requirement, PWA features mandate an HTTPS context (`herd secure cmsfornerd`).</li>
    </ul>

    <h2>3. Content Security Policy (CSP) Synergy</h2>
    <p>
        Most modern PWAs struggle to align with strict CSP profiles without disabling vital security features (like inline execution). CmsForNerd's AMP-compatible router overcomes this by explicitly propagating the <code>$nonce</code> context to all dynamically fetched HTML fragments. This guarantees that script evaluations during SPA-hydration are strictly authorized by the originating server payload.
    </p>

    <div class="error-box" style="margin-top: 30px; border-color: #27ae60; background: #e8f5e9; color: #1e824c;">
        <strong>VERIFICATION:</strong> To monitor PWA intelligence directly in your environment, open your browser's Developer Tools -> Console, and navigate backward/forward. You should witness the distinct <code>[bfcache] Restored from memory! Instant navigation.</code> signal.
    </div>
</div>
