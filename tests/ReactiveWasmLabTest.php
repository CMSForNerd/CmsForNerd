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

    public function testControllerBootstrapsBeforeStartingCompressionBuffer(): void
    {
        $content = $this->read($this->controllerPath);
        $bootstrapPosition = strpos($content, "require_once __DIR__ . '/includes/bootstrap.php';");
        $bufferPosition = strpos($content, 'ob_start("ob_gzhandler")');

        $this->assertNotFalse($bootstrapPosition, 'The controller must load the CMS bootstrap.');
        $this->assertNotFalse($bufferPosition, 'The controller must start its compression buffer.');
        $this->assertLessThan(
            $bufferPosition,
            $bootstrapPosition,
            'Bootstrap initialisation must complete before controller output buffering begins.'
        );
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

    public function testRenderedBodyPreservesSafeNonceOnItsOnlyExecutableScript(): void
    {
        $nonce = '0123456789abcdef0123456789abcdef';
        $rendered = $this->renderBody($nonce);

        $this->assertSame(1, substr_count($rendered, '<script'));
        $this->assertSame(1, substr_count($rendered, '</script>'));
        $this->assertStringContainsString('script nonce="' . $nonce . '"', $rendered);
    }

    public function testRenderedBodyEscapesAnUntrustedNonceAttribute(): void
    {
        $nonce = 'nonce"><img src=x onerror=alert(1)>';
        $rendered = $this->renderBody($nonce);

        $this->assertStringContainsString(
            'nonce="nonce&quot;&gt;&lt;img src=x onerror=alert(1)&gt;"',
            $rendered
        );
        $this->assertStringNotContainsString($nonce, $rendered);
    }

    public function testHashDemoUsesCanonicalSha256HexEncodingPipeline(): void
    {
        $content = $this->read($this->bodyPath);

        $this->assertStringContainsString('new TextEncoder()', $content);
        $this->assertStringContainsString('encoder.encode(input.value)', $content);
        $this->assertStringContainsString("crypto.subtle.digest('SHA-256', data)", $content);
        $this->assertStringContainsString('Array.from(new Uint8Array(buffer))', $content);
        $this->assertStringContainsString("b.toString(16).padStart(2, '0')", $content);
        $this->assertStringContainsString("}).join('')", $content);
        $this->assertStringContainsString('result.textContent = hashHex', $content);
        $this->assertStringNotContainsString('result.innerHTML', $content);
    }

    public function testHashDemoRevealsAUsefulErrorWhenDigestRejects(): void
    {
        $content = $this->read($this->bodyPath);

        $this->assertStringContainsString('.catch(function(err)', $content);
        $this->assertStringContainsString("err.message || 'Cryptographic operation failed'", $content);
        $this->assertSame(
            2,
            substr_count($content, "output.style.display = 'block';"),
            'Both successful and rejected digest operations must reveal their result.'
        );
    }

    public function testInteractiveHashScriptDoesNotTransmitPlaintext(): void
    {
        $script = $this->extractInteractiveScript($this->renderBody('test-nonce'));

        $this->assertStringNotContainsString('fetch(', $script);
        $this->assertStringNotContainsString('XMLHttpRequest', $script);
        $this->assertStringNotContainsString('sendBeacon', $script);
        $this->assertStringNotContainsString('WebSocket', $script);
        $this->assertStringNotContainsString('hx-post', $script);
    }

    public function testHashControlsAreExplicitlyAssociatedAndDoNotSubmitForms(): void
    {
        $content = $this->read($this->bodyPath);

        $this->assertStringContainsString('<label for="crypto-input"', $content);
        $this->assertStringContainsString('<input type="text" id="crypto-input"', $content);
        $this->assertStringContainsString('<button type="button" id="btn-compute-hash"', $content);
        $this->assertStringContainsString('<span id="hash-result"></span>', $content);
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
        $this->assertStringContainsString(
            'topics: [htmx, alpinejs, webassembly, wasm, cryptography, zero-global]',
            $content
        );
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

    private function renderBody(string $nonce): string
    {
        $ctx = (object) ['cspNonce' => $nonce];

        ob_start();
        include $this->bodyPath;
        $rendered = ob_get_clean();

        $this->assertIsString($rendered);

        return $rendered;
    }

    private function extractInteractiveScript(string $markup): string
    {
        $openingTagPosition = strpos($markup, '<script nonce=');
        $this->assertNotFalse($openingTagPosition, 'Expected the rendered fragment to contain a nonce-bearing script.');

        $scriptPosition = strpos($markup, '>', $openingTagPosition);
        $this->assertNotFalse($scriptPosition, 'Expected the script opening tag to be complete.');

        $scriptEndPosition = strpos($markup, '</script>', $scriptPosition);
        $this->assertNotFalse($scriptEndPosition, 'Expected the rendered script to have a closing tag.');

        return substr($markup, $scriptPosition + 1, $scriptEndPosition - $scriptPosition - 1);
    }

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
