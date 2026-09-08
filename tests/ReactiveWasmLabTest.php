<?php
declare(strict_types=1);

namespace CmsForNerd\Tests;

use PHPUnit\Framework\TestCase;

/**
 * Validates the "Reactive UI & WebAssembly Cryptography" Laboratory page and architecture:
 *
 * - reactive-wasm-lab.php: Page controller.
 * - contents/reactive-wasm-lab-body.inc: UI fragment with Wasm & reactive demo logic.
 * - docs/explanation/reactive-wasm-architecture.md: Architecture explanation document.
 * - Navigation & sitemap registrations across START-HERE.md, SUMMARY.md, mkdocs.yml, llms.txt,
 *   .llms/index.md, sitemap.txt, sitemap.xml, rss.xml, and ror.xml.
 */
final class ReactiveWasmLabTest extends TestCase
{
    private string $root;
    private string $controllerPath;
    private string $bodyPath;
    private string $archDocPath;

    protected function setUp(): void
    {
        $this->root = dirname(__DIR__);
        $this->controllerPath = $this->root . '/reactive-wasm-lab.php';
        $this->bodyPath = $this->root . '/contents/reactive-wasm-lab-body.inc';
        $this->archDocPath = $this->root . '/docs/explanation/reactive-wasm-architecture.md';
    }

    private function read(string $path): string
    {
        $this->assertFileExists($path, "Expected '{$path}' to exist.");

        return (string) file_get_contents($path);
    }

    // ---------------------------------------------------------------
    // reactive-wasm-lab.php Controller Tests
    // ---------------------------------------------------------------

    public function testControllerFileExists(): void
    {
        $this->assertFileExists($this->controllerPath, 'Page controller reactive-wasm-lab.php must exist.');
    }

    public function testControllerDeclaresStrictTypes(): void
    {
        $content = $this->read($this->controllerPath);

        $this->assertStringContainsString('declare(strict_types=1);', $content);
    }

    public function testControllerDeclaresExpectedMetadata(): void
    {
        $content = $this->read($this->controllerPath);

        $this->assertStringContainsString('Reactive UI & WebAssembly (Wasm) Cryptography Lab | CmsForNerd', $content);
        $this->assertStringContainsString('Harisfazillah Jamel & CmsForNerd Team', $content);
        $this->assertStringContainsString('HTMX, Alpine.js, WebAssembly, Wasm, Client-Side Cryptography', $content);
        $this->assertStringContainsString("'schemaType'  => \"TechArticle\"", $content);
    }

    public function testControllerBootstrapsAndDispatchesThroughPager(): void
    {
        $content = $this->read($this->controllerPath);

        $this->assertStringContainsString("require_once __DIR__ . '/includes/bootstrap.php';", $content);
        $this->assertStringContainsString('\CmsForNerd\SecurityUtils::resolvePageName(', $content);
        $this->assertStringContainsString('createCmsContext(', $content);
        $this->assertStringContainsString('pager($ctx);', $content);
    }

    public function testControllerIsSyntacticallyValidPhp(): void
    {
        $this->assertPhpFileLintsCleanly($this->controllerPath);
    }

    // ---------------------------------------------------------------
    // contents/reactive-wasm-lab-body.inc UI Fragment Tests
    // ---------------------------------------------------------------

    public function testBodyIncFileExists(): void
    {
        $this->assertFileExists($this->bodyPath, 'Content body contents/reactive-wasm-lab-body.inc must exist.');
    }

    public function testBodyIncContainsMainHeadingAndMicrodata(): void
    {
        $content = $this->read($this->bodyPath);

        $this->assertStringContainsString('itemtype="https://schema.org/TechArticle"', $content);
        $this->assertStringContainsString('Reactive UI & WebAssembly (Wasm) Laboratory', $content);
    }

    public function testBodyIncExplainsHtmxAndAlpineReactivity(): void
    {
        $content = $this->read($this->bodyPath);

        $this->assertStringContainsString('HTMX', $content);
        $this->assertStringContainsString('Alpine.js', $content);
        $this->assertStringContainsString('X-Requested-With', $content);
        $this->assertStringContainsString('HX-Request', $content);
        $this->assertStringContainsString('Zero-Global PHP 8.4', $content);
    }

    public function testBodyIncExplainsWasmCryptographyAndCtWasm(): void
    {
        $content = $this->read($this->bodyPath);

        $this->assertStringContainsString('WebAssembly (Wasm)', $content);
        $this->assertStringContainsString('Client-Side Cryptography', $content);
        $this->assertStringContainsString('Side-Channel Resistance (CT-Wasm)', $content);
        $this->assertStringContainsString('Enhanced Privacy', $content);
    }

    public function testBodyIncExplainsDocumentAndIdentityProcessing(): void
    {
        $content = $this->read($this->bodyPath);

        $this->assertStringContainsString('Document & Identity Processing', $content);
        $this->assertStringContainsString('Reduced Server Load', $content);
        $this->assertStringContainsString('Immediate User Feedback', $content);
    }

    public function testBodyIncIncludesInteractiveScriptWithCspNonce(): void
    {
        $content = $this->read($this->bodyPath);

        $this->assertStringContainsString('nonce="<?= htmlspecialchars($ctx->cspNonce) ?>"', $content);
        $this->assertStringContainsString('btn-compute-hash', $content);
        $this->assertStringContainsString('crypto.subtle.digest', $content);
        $this->assertStringContainsString('SHA-256', $content);
    }

    public function testBodyIncIsSyntacticallyValidPhp(): void
    {
        $this->assertPhpFileLintsCleanly($this->bodyPath);
    }

    // ---------------------------------------------------------------
    // docs/explanation/reactive-wasm-architecture.md Tests
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
        $this->assertStringContainsString('topics: [htmx, alpinejs, webassembly, wasm, cryptography, zero-global]', $content);
        $this->assertMatchesRegularExpression('/timestamp: "?\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}Z"?/', $content);
    }

    public function testArchitectureDocHasDsomSignatureFooter(): void
    {
        $content = $this->read($this->archDocPath);

        $this->assertStringContainsString('Harisfazillah Jamel (LinuxMalaysia)', $content);
    }

    // ---------------------------------------------------------------
    // Navigation & Sitemap Registration Tests
    // ---------------------------------------------------------------

    public function testStartHereRegistersReactiveWasmLab(): void
    {
        $content = $this->read($this->root . '/START-HERE.md');

        $this->assertStringContainsString('reactive-wasm-lab.php', $content);
        $this->assertStringContainsString('docs/explanation/reactive-wasm-architecture.md', $content);
    }

    public function testSummaryRegistersReactiveWasmDoc(): void
    {
        $content = $this->read($this->root . '/SUMMARY.md');

        $this->assertStringContainsString('docs/explanation/reactive-wasm-architecture.md', $content);
    }

    public function testMkdocsRegistersReactiveWasmDoc(): void
    {
        $content = $this->read($this->root . '/mkdocs.yml');

        $this->assertStringContainsString('docs/explanation/reactive-wasm-architecture.md', $content);
    }

    public function testLlmsTxtRegistersReactiveWasmDoc(): void
    {
        $content = $this->read($this->root . '/llms.txt');

        $this->assertStringContainsString('docs/explanation/reactive-wasm-architecture.md', $content);
    }

    public function testLeftNavRegistersReactiveWasmLab(): void
    {
        $content = $this->read($this->root . '/contents/left-side.inc');

        $this->assertStringContainsString('href="reactive-wasm-lab.php"', $content);
    }

    public function testSitemapsRegisterReactiveWasmLab(): void
    {
        $txtContent = $this->read($this->root . '/sitemap.txt');
        $xmlContent = $this->read($this->root . '/sitemap.xml');
        $rssContent = $this->read($this->root . '/rss.xml');
        $rorContent = $this->read($this->root . '/ror.xml');

        $this->assertStringContainsString('reactive-wasm-lab.php', $txtContent);
        $this->assertStringContainsString('reactive-wasm-lab.php', $xmlContent);
        $this->assertStringContainsString('reactive-wasm-lab.php', $rssContent);
        $this->assertStringContainsString('reactive-wasm-lab.php', $rorContent);
    }

    // ---------------------------------------------------------------
    // Helper
    // ---------------------------------------------------------------

    private function assertPhpFileLintsCleanly(string $path): void
    {
        $content = $this->read($path);
        try {
            token_get_all($content, TOKEN_PARSE);
            $this->assertTrue(true);
        } catch (\ParseError $e) {
            $this->fail("'{$path}' failed syntax validation: " . $e->getMessage());
        }
    }
}
