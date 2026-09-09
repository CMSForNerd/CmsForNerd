<?php
declare(strict_types=1);

namespace CmsForNerd\Tests;

use PHPUnit\Framework\TestCase;

/**
 * Validates the "WebGPU & WebAssembly SIMD Client-Side AI Inferencing Architecture"
 * specification, interactive probe laboratory elements, and navigation registrations:
 *
 * - docs/explanation/webgpu-wasm-ai-inferencing-architecture.md
 * - contents/reactive-wasm-lab-body.inc: AI hardware probe script & UI components
 * - START-HERE.md (Entry Point 18)
 * - SUMMARY.md
 * - mkdocs.yml
 * - llms.txt
 */
final class WebGpuWasmAiInferencingTest extends TestCase
{
    private string $root;
    private string $archDocPath;
    private string $bodyPath;

    protected function setUp(): void
    {
        $this->root = dirname(__DIR__);
        $this->archDocPath = $this->root . '/docs/explanation/webgpu-wasm-ai-inferencing-architecture.md';
        $this->bodyPath = $this->root . '/contents/reactive-wasm-lab-body.inc';
    }

    private function read(string $path): string
    {
        $this->assertFileExists($path, "Expected '{$path}' to exist.");

        return (string) file_get_contents($path);
    }

    // ---------------------------------------------------------------
    // Architecture Specification Document Tests
    // ---------------------------------------------------------------

    public function testArchitectureDocExists(): void
    {
        $this->assertFileExists($this->archDocPath);
    }

    public function testArchitectureDocHasCompliantOkfFrontmatter(): void
    {
        $content = $this->read($this->archDocPath);

        $this->assertStringStartsWith('---', $content);
        $this->assertStringContainsString('okf_version: 0.1', $content);
        $this->assertStringContainsString('type: explanation', $content);
        $this->assertStringContainsString('topics: [webgpu, wasm, simd, client-side-ai, slm, cmsfornerd, zero-global]', $content);
        $this->assertMatchesRegularExpression('/timestamp: "?\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}Z"?/', $content);
    }

    public function testArchitectureDocCoversKeyAiInferencingConcepts(): void
    {
        $content = $this->read($this->archDocPath);

        $this->assertStringContainsString('WebGPU', $content);
        $this->assertStringContainsString('WebAssembly SIMD', $content);
        $this->assertStringContainsString('Small Language Models (SLMs)', $content);
        $this->assertStringContainsString('Zero Server GPU Infrastructure Costs', $content);
        $this->assertStringContainsString('WebLLM', $content);
        $this->assertStringContainsString('Transformers.js', $content);
        $this->assertStringContainsString('Cross-Origin-Opener-Policy: same-origin', $content);
        $this->assertStringContainsString('Cross-Origin-Embedder-Policy: require-corp', $content);
    }

    public function testArchitectureDocHasDsomSignatureFooter(): void
    {
        $content = $this->read($this->archDocPath);

        $this->assertStringContainsString('Harisfazillah Jamel (LinuxMalaysia)', $content);
    }

    // ---------------------------------------------------------------
    // Interactive Laboratory AI Hardware Probe Tests
    // ---------------------------------------------------------------

    public function testBodyIncIncludesAiProbeElements(): void
    {
        $content = $this->read($this->bodyPath);

        $this->assertStringContainsString('btn-probe-ai', $content);
        $this->assertStringContainsString('ai-probe-output', $content);
        $this->assertStringContainsString('ai-capabilities-list', $content);
        $this->assertStringContainsString('navigator.gpu', $content);
        $this->assertStringContainsString('WebAssembly.validate', $content);
        $this->assertStringContainsString('self.crossOriginIsolated', $content);
        $this->assertStringContainsString('docs/explanation/webgpu-wasm-ai-inferencing-architecture.md', $content);
    }

    // ---------------------------------------------------------------
    // Navigation & Sitemap Registration Tests
    // ---------------------------------------------------------------

    public function testStartHereRegistersEntryPoint18(): void
    {
        $content = $this->read($this->root . '/START-HERE.md');

        $this->assertStringContainsString('The 18 Defined Entry Points', $content);
        $this->assertStringContainsString('**18**', $content);
        $this->assertStringContainsString('docs/explanation/webgpu-wasm-ai-inferencing-architecture.md', $content);
    }

    public function testSummaryRegistersWebGpuDoc(): void
    {
        $content = $this->read($this->root . '/SUMMARY.md');

        $this->assertStringContainsString('docs/explanation/webgpu-wasm-ai-inferencing-architecture.md', $content);
    }

    public function testMkdocsRegistersWebGpuDoc(): void
    {
        $content = $this->read($this->root . '/mkdocs.yml');

        $this->assertStringContainsString('docs/explanation/webgpu-wasm-ai-inferencing-architecture.md', $content);
    }

    public function testLlmsTxtRegistersWebGpuDoc(): void
    {
        $content = $this->read($this->root . '/llms.txt');

        $this->assertStringContainsString('docs/explanation/webgpu-wasm-ai-inferencing-architecture.md', $content);
    }
}
