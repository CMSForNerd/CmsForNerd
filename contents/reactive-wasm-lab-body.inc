<div class="content-body" itemscope itemtype="https://schema.org/TechArticle">
    <h1>⚡ Reactive UI, WebAssembly (Wasm) & WebGPU Laboratory</h1>

    <p>
        The <strong>CmsForNerd v4.3.0 Laboratory</strong> explores lightweight reactive frontend architectures (HTMX and Alpine.js) combined with high-performance <strong>WebAssembly (Wasm)</strong> and <strong>WebGPU</strong> for client-side cryptographic processing, document analysis, and hardware-accelerated AI inferencing, seamlessly operating alongside CmsForNerd's <strong>Zero-Global PHP 8.4</strong> engine architecture.
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
            <h3 style="margin-top:0;">🤖 Client-Side AI Inferencing</h3>
            <p>
                <strong>WebGPU</strong> compute shaders and <strong>Wasm 128-bit SIMD</strong> execute Small Language Models (SLMs like Phi-3-mini, Llama 3) and vector embeddings entirely on client hardware, eliminating backend server GPU costs and keeping user prompts completely private.
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

    <h2>4. Interactive Demonstration: WebGPU & Wasm SIMD AI Inferencing Readiness Probe</h2>
    <p>
        Execute a live diagnostic probe to assess your client device's hardware readiness for running Small Language Models (SLMs) and vector embeddings locally via WebGPU and WebAssembly 128-bit SIMD vectorization.
    </p>

    <div class="scenario-box" style="background: #f8f9fa; border: 1px solid #dee2e6; padding: 20px; border-radius: 8px;">
        <button type="button" id="btn-probe-ai" class="btn btn-primary" style="background: #2980b9; color: #fff; padding: 10px 18px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">
            🤖 Probe Hardware WebGPU & Wasm SIMD AI Readiness
        </button>

        <div id="ai-probe-output" style="margin-top: 15px; display: none; background: #ebf5fb; border: 1px solid #2980b9; padding: 12px; border-radius: 4px; font-family: monospace; line-height: 1.6;">
            <strong>Device Capabilities Summary:</strong>
            <ul id="ai-capabilities-list" style="margin-top: 8px; margin-bottom: 8px; padding-left: 20px;"></ul>
            <small id="ai-recommendation" style="color: #1f618d; display: block; font-weight: bold;"></small>
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
                        }).catch(function(err) {
                            result.textContent = 'Error computing hash: ' + (err.message || 'Cryptographic operation failed');
                            output.style.display = 'block';
                        });
                    });
                }
            }

            function initAiProbe() {
                var btn = document.getElementById('btn-probe-ai');
                var output = document.getElementById('ai-probe-output');
                var list = document.getElementById('ai-capabilities-list');
                var rec = document.getElementById('ai-recommendation');

                if (btn && output && list && rec) {
                    btn.addEventListener('click', async function() {
                        list.innerHTML = '';
                        output.style.display = 'block';

                        // 1. WebGPU check
                        var hasWebGpu = false;
                        var isFullyCapableGpu = false;
                        var gpuInfo = 'WebGPU API Not Detected in Browser';
                        if (navigator.gpu) {
                            try {
                                var adapter = await navigator.gpu.requestAdapter();
                                if (adapter) {
                                    hasWebGpu = true;
                                    var isFallback = adapter.isFallbackAdapter || (adapter.info && adapter.info.isFallbackAdapter) || false;
                                    var maxInvocations = (adapter.limits && adapter.limits.maxComputeInvocationsPerWorkgroup) ? adapter.limits.maxComputeInvocationsPerWorkgroup : 0;
                                    if (!isFallback && maxInvocations >= 128) {
                                        isFullyCapableGpu = true;
                                        gpuInfo = 'WebGPU Available (Hardware Adapter Fully Capable)';
                                    } else {
                                        gpuInfo = 'WebGPU Available (Fallback Software or Limited Adapter)';
                                    }
                                } else {
                                    gpuInfo = 'WebGPU API Present but No Hardware Adapter Found';
                                }
                            } catch (e) {
                                gpuInfo = 'WebGPU Probe Exception: ' + (e.message || e);
                            }
                        }

                        // 2. Wasm SIMD check
                        var hasWasmSimd = false;
                        try {
                            // Minimal WebAssembly 128-bit SIMD module opcode probe bytes
                            var simdBytes = new Uint8Array([0, 97, 115, 109, 1, 0, 0, 0, 1, 5, 1, 96, 0, 1, 123, 3, 2, 1, 0, 10, 10, 1, 8, 0, 65, 0, 253, 15, 253, 98, 11]);
                            hasWasmSimd = WebAssembly.validate(simdBytes);
                        } catch (e) {
                            hasWasmSimd = false;
                        }

                        // 3. Cross-Origin Isolation (SharedArrayBuffer)
                        var isCoopCoep = !!self.crossOriginIsolated;

                        // 4. Memory estimation
                        var devMem = navigator.deviceMemory ? navigator.deviceMemory + ' GB RAM' : 'RAM level unknown';

                        // Render list
                        var li1 = document.createElement('li');
                        li1.innerHTML = '<strong>WebGPU Status:</strong> ' + (hasWebGpu ? '✅ ' : '⚠️ ') + gpuInfo;
                        list.appendChild(li1);

                        var li2 = document.createElement('li');
                        li2.innerHTML = '<strong>WebAssembly SIMD (128-bit):</strong> ' + (hasWasmSimd ? '✅ Supported' : '❌ Not Supported');
                        list.appendChild(li2);

                        var li3 = document.createElement('li');
                        li3.innerHTML = '<strong>Cross-Origin Isolation (COOP/COEP):</strong> ' + (isCoopCoep ? '✅ Active (SharedArrayBuffer multithreading enabled)' : 'ℹ️ Standard (Single-threaded Wasm)');
                        list.appendChild(li3);

                        var li4 = document.createElement('li');
                        li4.innerHTML = '<strong>Estimated Hardware Memory:</strong> ' + devMem;
                        list.appendChild(li4);

                        if (isFullyCapableGpu) {
                            rec.textContent = '🚀 OPTIMAL AI HARNESS: Your device is fully capable of hardware-accelerated WebGPU Small Language Model (SLM) inferencing (WebLLM / Transformers.js v3).';
                        } else if (hasWebGpu) {
                            rec.textContent = '⚠️ LIMITED WEBGPU: WebGPU adapter detected, but features/limits indicate software fallback or reduced capabilities; Wasm SIMD recommended.';
                        } else if (hasWasmSimd) {
                            rec.textContent = '⚡ FAST CPU FALLBACK: WebGPU unavailable, but Wasm SIMD is enabled for CPU-vectorized client-side AI inferencing.';
                        } else {
                            rec.textContent = 'ℹ️ STANDARD COMPATIBILITY: Standard scalar browser environment; recommend lightweight server-assisted AI or fallback models.';
                        }
                    });
                }
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', function() {
                    initCrypto();
                    initAiProbe();
                });
            } else {
                initCrypto();
                initAiProbe();
            }
        })();
    </script>

    <div class="error-box" style="margin-top: 30px; border-color: #2980b9; background: #ebf5fb; color: #1f618d;">
        <strong>ZERO-GLOBAL BACKEND INTEGRATION:</strong> When client-side processing finishes, the resulting hash or signature can be dispatched to CmsForNerd's PHP 8.4 backend via HTMX (e.g. <code>hx-post="reactive-wasm-lab.php"</code>). Because browser-produced cryptographic outputs are client-computed, untrusted values, any backend HTMX endpoint must recompute or verify received hashes/signatures against server-received raw bytes before using them for integrity, identity, or authorization—preserving server efficiency while maintaining strict <code>CmsContext</code> immutability and security boundaries. Read the detailed technical specification in <a href="docs/explanation/webgpu-wasm-ai-inferencing-architecture.md">WebGPU & Wasm SIMD Architecture Guide</a>.
    </div>
</div>
