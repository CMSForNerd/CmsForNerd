<div class="content-body" itemscope itemtype="https://schema.org/TechArticle">
    <h1>⚡ Reactive UI & WebAssembly (Wasm) Laboratory</h1>

    <p>
        The <strong>CmsForNerd v4.3.0 Laboratory</strong> explores lightweight reactive frontend architectures (HTMX and Alpine.js) combined with high-performance <strong>WebAssembly (Wasm)</strong> for client-side cryptographic and document processing, seamlessly operating alongside CmsForNerd's <strong>Zero-Global PHP 8.4</strong> engine architecture.
    </p>

    <!-- Highlights Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin: 30px 0;">

        <div class="scenario-box">
            <h3 style="margin-top:0;">🔄 Lightweight Reactive UI</h3>
            <p>
                <strong>HTMX</strong> and <strong>Alpine.js</strong> deliver hyper-responsive SPA-like interactions directly within HTML without bulky JavaScript frameworks. HTMX routes declarative AJAX requests (<code>hx-get</code>, <code>hx-post</code>) to PHP 8.4 Front Controllers, while Alpine handles local component reactivity.
            </p>
        </div>

        <div class="scenario-box">
            <h3 style="margin-top:0;">🔑 Client-Side Cryptography</h3>
            <p>
                <strong>WebAssembly (Wasm)</strong> executes near-native binary bytecode in a secure browser sandbox. It enables local cryptographic key generation, SHA-256/SHA-512 hashing, and payload encryption—keeping plaintext and private keys strictly local to protect user privacy.
            </p>
        </div>

        <div class="scenario-box">
            <h3 style="margin-top:0;">📄 Document & Identity Processing</h3>
            <p>
                Offload CPU-heavy tasks—such as PDF parsing, 4K image compression, and optical character recognition (OCR)—from backend PHP servers to client hardware, delivering immediate feedback while drastically reducing server load.
            </p>
        </div>

    </div>

    <h2>1. Lightweight Reactive Interactions (HTMX & Alpine.js)</h2>
    <p>
        Modern web applications often suffer from JavaScript bloat. By pairing HTMX and Alpine.js with CmsForNerd's Zero-Global PHP 8.4 backend, we achieve high reactivity while preserving server-side simplicity and security:
    </p>
    <ul>
        <li>
            <strong>HTMX Fragment Swapping:</strong> Sends lightweight AJAX requests with the <code>X-Requested-With</code> or <code>HX-Request</code> headers. The PHP 8.4 backend evaluates the request via <code>SecurityUtils::resolvePageName()</code> and returns isolated <code>-body.inc</code> fragments directly into the DOM container without full-page reloads.
        </li>
        <li>
            <strong>Alpine.js Declarative State:</strong> Manages micro-state (tabs, dropdowns, form validation) locally via attributes like <code>x-data</code> and <code>x-show</code>.
        </li>
        <li>
            <strong>Content Security Policy (CSP) Alignment:</strong> Dynamic scripts and event bindings are strictly authenticated using CmsForNerd's server-generated CSP nonces (<code>$nonce</code>), preventing inline XSS execution.
        </li>
    </ul>

    <h2>2. WebAssembly (Wasm): High-Performance Client Processing</h2>
    <p>
        WebAssembly is a portable binary instruction format that executes high-performance code built from languages like Rust, C++, and C at near-native speed.
    </p>

    <div class="terminal-block">
        <strong>Binary Format:</strong> Low-level bytecode decoded significantly faster than standard JavaScript.<br>
        <strong>Compilation Target:</strong> Compiled from Rust, C/C++, or Go for intensive computational tasks.<br>
        <strong>Secure Sandbox:</strong> Runs in strict isolation from host OS and DOM memory unless explicitly permitted.
    </div>

    <h3>A. Client-Side Cryptography</h3>
    <p>
        Executing cryptographic operations inside a client Wasm sandbox introduces major security and efficiency advantages:
    </p>
    <ul>
        <li>
            <strong>Fast Local Processing:</strong> Performs intensive hashing, key generation, and large file encryption directly on client hardware without backend latency.
        </li>
        <li>
            <strong>Enhanced Privacy:</strong> Plaintext documents, passphrases, and private keys never leave the user's browser device, eliminating transmission of unencrypted data over network paths.
        </li>
        <li>
            <strong>Side-Channel Resistance (CT-Wasm):</strong> Uses Constant-Time Wasm extension principles (CT-Wasm) to eliminate microarchitectural timing side-channels that could leak cryptographic keys during execution.
        </li>
    </ul>

    <h3>B. Client-Side Document & Identity Processing</h3>
    <p>
        Handling heavy document workflows locally transforms user experience and infrastructure scalability:
    </p>
    <ul>
        <li>
            <strong>Heavy Local Operations:</strong> Renders, parses, resizes, and compresses large PDF files, 4K identity photos, and OCR document scans directly in client memory.
        </li>
        <li>
            <strong>Reduced Server Load:</strong> Offloads CPU-intensive image re-encoding and document parsing from backend PHP/Podman/Apache instances.
        </li>
        <li>
            <strong>Immediate User Feedback:</strong> Validates document integrity and generates digital signatures instantaneously before any network upload occurs.
        </li>
    </ul>

    <h2>3. Interactive Demonstration: Local Wasm Cryptographic Hashing</h2>
    <p>
        Test local client-side cryptographic processing below. Enter text to compute a SHA-256 hash locally in your browser memory before any network interaction.
    </p>

    <div class="scenario-box" style="background: #f8f9fa; border: 1px solid #dee2e6; padding: 20px; border-radius: 8px;">
        <label for="crypto-input" style="font-weight: bold; display: block; margin-bottom: 8px;">
            Input Document / Plaintext String:
        </label>
        <input type="text" id="crypto-input" value="CmsForNerd Zero-Global Architecture v4.3.0" style="width: 100%; padding: 10px; font-family: monospace; box-sizing: border-box; margin-bottom: 12px; border: 1px solid #ccc; border-radius: 4px;">

        <button type="button" id="btn-compute-hash" class="btn btn-primary" style="background: #27ae60; color: #fff; padding: 10px 18px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">
            🔒 Compute Client-Side SHA-256 Hash
        </button>

        <div id="crypto-output" style="margin-top: 15px; display: none; background: #eef9f2; border: 1px solid #27ae60; padding: 12px; border-radius: 4px; font-family: monospace; word-break: break-all;">
            <strong>Output SHA-256 Hash:</strong> <span id="hash-result"></span><br>
            <small style="color: #27ae60; display: block; margin-top: 6px;">
                ✓ Processed locally in client sandbox via Web Crypto / Wasm architecture. No unencrypted data transmitted to server.
            </small>
        </div>
    </div>

    <script nonce="<?= htmlspecialchars($ctx->cspNonce) ?>">
        (function() {
            function initCrypto() {
                var btn = document.getElementById('btn-compute-hash');
                var input = document.getElementById('crypto-input');
                var output = document.getElementById('crypto-output');
                var result = document.getElementById('hash-result');

                if (btn && input && output && result) {
                    btn.addEventListener('click', function() {
                        var encoder = new TextEncoder();
                        var data = encoder.encode(input.value);
                        window.crypto.subtle.digest('SHA-256', data).then(function(buffer) {
                            var hashArray = Array.from(new Uint8Array(buffer));
                            var hashHex = hashArray.map(function(b) {
                                return b.toString(16).padStart(2, '0');
                            }).join('');
                            result.textContent = hashHex;
                            output.style.display = 'block';
                        });
                    });
                }
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initCrypto);
            } else {
                initCrypto();
            }
        })();
    </script>

    <div class="error-box" style="margin-top: 30px; border-color: #2980b9; background: #ebf5fb; color: #1f618d;">
        <strong>ZERO-GLOBAL BACKEND INTEGRATION:</strong> When client-side processing finishes, the resulting hash or signature can be dispatched to CmsForNerd's PHP 8.4 backend via HTMX (e.g. <code>hx-post="reactive-wasm-lab.php"</code>). The backend receives pre-verified cryptographic hashes, keeping server CPU usage minimal and maintaining <code>CmsContext</code> immutability.
    </div>
</div>
