const assert = require('node:assert/strict');
const fs = require('node:fs');
const path = require('node:path');
const test = require('node:test');
const vm = require('node:vm');

const bodyPath = path.resolve(__dirname, '../../contents/reactive-wasm-lab-body.inc');
const body = fs.readFileSync(bodyPath, 'utf8');
const scriptMatch = body.match(/<script nonce=[^\n]*\n([\s\S]*?)\n\s*<\/script>/);

assert.ok(scriptMatch, 'The reactive Wasm laboratory must contain its inline script.');

const probeScript = scriptMatch[1];
const simdProbeBytes = [
    0, 97, 115, 109, 1, 0, 0, 0, 1, 5, 1, 96, 0, 1, 123, 3,
    2, 1, 0, 10, 10, 1, 8, 0, 65, 0, 253, 15, 253, 98, 11,
];

function createElement() {
    let html = '';

    return {
        children: [],
        listeners: {},
        style: { display: 'none' },
        textContent: '',
        get innerHTML() {
            return html;
        },
        set innerHTML(value) {
            html = value;
            if (value === '') {
                this.children = [];
            }
        },
        addEventListener(event, listener) {
            this.listeners[event] = listener;
        },
        appendChild(child) {
            this.children.push(child);
        },
    };
}

function createHarness(options = {}) {
    const elements = {
        'btn-probe-ai': createElement(),
        'ai-probe-output': createElement(),
        'ai-capabilities-list': createElement(),
        'ai-recommendation': createElement(),
    };
    const documentListeners = {};
    const navigator = {};
    let adapterRequests = 0;
    let validatedBytes = null;

    if (options.gpuPresent) {
        navigator.gpu = {
            async requestAdapter() {
                adapterRequests += 1;
                if (options.gpuError) {
                    throw options.gpuError;
                }

                return options.adapter ?? null;
            },
        };
    }

    if (options.deviceMemory !== undefined) {
        navigator.deviceMemory = options.deviceMemory;
    }

    if (options.missingElement) {
        delete elements[options.missingElement];
    }

    const document = {
        readyState: options.readyState ?? 'complete',
        addEventListener(event, listener) {
            documentListeners[event] = listener;
        },
        createElement() {
            return createElement();
        },
        getElementById(id) {
            return elements[id] ?? null;
        },
    };

    const context = vm.createContext({
        Array,
        TextEncoder,
        Uint8Array,
        document,
        navigator,
        self: { crossOriginIsolated: options.crossOriginIsolated ?? false },
        WebAssembly: {
            validate(bytes) {
                validatedBytes = Array.from(bytes);
                if (options.wasmError) {
                    throw options.wasmError;
                }

                return options.wasmSupported ?? false;
            },
        },
    });

    vm.runInContext(probeScript, context, { filename: bodyPath });

    return {
        documentListeners,
        elements,
        get adapterRequests() {
            return adapterRequests;
        },
        get validatedBytes() {
            return validatedBytes;
        },
        async clickProbe() {
            const listener = elements['btn-probe-ai']?.listeners.click;
            assert.equal(typeof listener, 'function', 'The probe button must have a click listener.');
            await listener();
        },
    };
}

function capabilityText(harness, index) {
    return harness.elements['ai-capabilities-list'].children[index].innerHTML;
}

function recommendation(harness) {
    return harness.elements['ai-recommendation'].textContent;
}

test('reports an optimal harness for a hardware adapter at the exact workgroup threshold', async () => {
    const harness = createHarness({
        adapter: {
            isFallbackAdapter: false,
            limits: { maxComputeInvocationsPerWorkgroup: 128 },
        },
        crossOriginIsolated: true,
        deviceMemory: 8,
        gpuPresent: true,
        wasmSupported: true,
    });

    await harness.clickProbe();

    assert.equal(harness.elements['ai-probe-output'].style.display, 'block');
    assert.equal(harness.elements['ai-capabilities-list'].children.length, 4);
    assert.match(capabilityText(harness, 0), /✅ WebGPU Available \(Hardware Adapter Fully Capable\)/);
    assert.match(capabilityText(harness, 1), /✅ Supported/);
    assert.match(capabilityText(harness, 2), /✅ Active \(SharedArrayBuffer multithreading enabled\)/);
    assert.match(capabilityText(harness, 3), /8 GB RAM/);
    assert.match(recommendation(harness), /OPTIMAL AI HARNESS/);
    assert.equal(harness.adapterRequests, 1);
    assert.deepEqual(harness.validatedBytes, simdProbeBytes);
});

test('classifies an adapter below the workgroup threshold as limited', async () => {
    const harness = createHarness({
        adapter: {
            isFallbackAdapter: false,
            limits: { maxComputeInvocationsPerWorkgroup: 127 },
        },
        gpuPresent: true,
        wasmSupported: true,
    });

    await harness.clickProbe();

    assert.match(capabilityText(harness, 0), /Fallback Software or Limited Adapter/);
    assert.match(recommendation(harness), /LIMITED WEBGPU/);
});

test('does not classify a fallback adapter as fully capable despite high limits', async () => {
    const harness = createHarness({
        adapter: {
            isFallbackAdapter: true,
            limits: { maxComputeInvocationsPerWorkgroup: 256 },
        },
        gpuPresent: true,
    });

    await harness.clickProbe();

    assert.match(capabilityText(harness, 0), /✅ WebGPU Available \(Fallback Software or Limited Adapter\)/);
    assert.match(recommendation(harness), /LIMITED WEBGPU/);
});

test('uses the SIMD CPU fallback when the WebGPU API returns no adapter', async () => {
    const harness = createHarness({
        adapter: null,
        gpuPresent: true,
        wasmSupported: true,
    });

    await harness.clickProbe();

    assert.match(capabilityText(harness, 0), /⚠️ WebGPU API Present but No Hardware Adapter Found/);
    assert.match(recommendation(harness), /FAST CPU FALLBACK/);
});

test('reports adapter probe failures without preventing remaining capability checks', async () => {
    const harness = createHarness({
        gpuError: new Error('permission denied'),
        gpuPresent: true,
        wasmSupported: true,
    });

    await harness.clickProbe();

    assert.match(capabilityText(harness, 0), /WebGPU Probe Exception: permission denied/);
    assert.match(capabilityText(harness, 1), /✅ Supported/);
    assert.match(recommendation(harness), /FAST CPU FALLBACK/);
});

test('uses standard compatibility when WebGPU is absent and SIMD validation throws', async () => {
    const harness = createHarness({
        wasmError: new Error('invalid module'),
    });

    await harness.clickProbe();

    assert.match(capabilityText(harness, 0), /⚠️ WebGPU API Not Detected in Browser/);
    assert.match(capabilityText(harness, 1), /❌ Not Supported/);
    assert.match(capabilityText(harness, 2), /Standard \(Single-threaded Wasm\)/);
    assert.match(capabilityText(harness, 3), /RAM level unknown/);
    assert.match(recommendation(harness), /STANDARD COMPATIBILITY/);
});

test('clears old capability rows before every repeated probe', async () => {
    const harness = createHarness({
        adapter: {
            isFallbackAdapter: false,
            limits: { maxComputeInvocationsPerWorkgroup: 128 },
        },
        gpuPresent: true,
    });

    await harness.clickProbe();
    await harness.clickProbe();

    assert.equal(harness.elements['ai-capabilities-list'].children.length, 4);
    assert.equal(harness.adapterRequests, 2);
});

test('waits for DOMContentLoaded before initialising when the document is loading', async () => {
    const harness = createHarness({ readyState: 'loading' });

    assert.equal(harness.elements['btn-probe-ai'].listeners.click, undefined);
    assert.equal(typeof harness.documentListeners.DOMContentLoaded, 'function');

    harness.documentListeners.DOMContentLoaded();
    await harness.clickProbe();

    assert.match(recommendation(harness), /STANDARD COMPATIBILITY/);
});

test('does not bind a partial probe UI when a required element is missing', () => {
    const harness = createHarness({ missingElement: 'ai-recommendation' });

    assert.equal(harness.elements['btn-probe-ai'].listeners.click, undefined);
});
