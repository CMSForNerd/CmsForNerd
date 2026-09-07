<?php
declare(strict_types=1);

namespace CmsForNerd\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
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
    private const CUSTOM_DOMAIN_URL = 'https://www.linuxmalaysia.com/reactive-wasm-lab.php';
    private const GITHUB_PAGES_URL = 'https://linuxmalaysia.github.io/CmsForNerd/reactive-wasm-lab.html';
    private const RENDER_URL = 'https://cmsfornerd.onrender.com/reactive-wasm-lab.php';

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

    public function testControllerRoutesOnlyTheCanonicalSlugIntoCmsContext(): void
    {
        $content = $this->read($this->controllerPath);

        $this->assertStringContainsString(
            '\CmsForNerd\SecurityUtils::resolvePageName(pathinfo(basename(__FILE__), PATHINFO_FILENAME))',
            $content
        );
        $this->assertStringContainsString("\$content['data'] = \$pageName;", $content);
        $this->assertStringContainsString('pageName: $pageName,', $content);
        $this->assertStringContainsString('dataFile: $dataFile,', $content);
    }

    public function testControllerPreservesZeroGlobalAndMissingPagerGuards(): void
    {
        $content = $this->read($this->controllerPath);

        $this->assertDoesNotMatchRegularExpression('/\bglobal\s+\$/', $content);
        $this->assertStringNotContainsString('$GLOBALS', $content);
        $this->assertStringContainsString('if (file_exists($pagerPath)) {', $content);
        $this->assertStringContainsString("header('HTTP/1.1 500 Internal Server Error');", $content);
        $this->assertStringContainsString('Fatal Error: Theme engine (pager.php) missing', $content);
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

    /**
     * @return array<string, array{string, string}>
     */
    public static function nonceProvider(): array
    {
        return [
            'ordinary nonce' => ['safe-token_123', 'safe-token_123'],
            'attribute-breakout attempt' => [
                'token" onmouseover="alert(1)',
                'token&quot; onmouseover=&quot;alert(1)',
            ],
            'HTML metacharacters' => ["<>&'", '&lt;&gt;&amp;&#039;'],
        ];
    }

    #[DataProvider('nonceProvider')]
    public function testBodyIncEscapesTheRuntimeCspNonce(string $nonce, string $expected): void
    {
        $rendered = $this->renderBodyWithNonce($nonce);

        $this->assertStringContainsString('nonce="' . $expected . '"', $rendered);
        $this->assertSame(1, substr_count($rendered, '<script nonce="'));

        if ($nonce !== $expected) {
            $this->assertStringNotContainsString('nonce="' . $nonce . '"', $rendered);
        }
    }

    public function testInteractiveControlsHaveUniqueIdsAndAnAssociatedLabel(): void
    {
        $content = $this->read($this->bodyPath);
        $matched = preg_match_all('/\sid="([^"]+)"/', $content, $matches);

        $this->assertSame(4, $matched);
        $this->assertSame(
            ['crypto-input', 'btn-compute-hash', 'crypto-output', 'hash-result'],
            $matches[1]
        );
        $this->assertCount(count(array_unique($matches[1])), $matches[1], 'Interactive element IDs must be unique.');
        $this->assertStringContainsString('<label for="crypto-input"', $content);
        $this->assertStringContainsString('<button type="button" id="btn-compute-hash"', $content);
    }

    public function testHashSuccessPathEncodesInputAndRendersPaddedHexOutput(): void
    {
        $script = $this->interactiveScript();

        $this->assertMatchesRegularExpression(
            "/new TextEncoder\(\).*?encoder\.encode\(input\.value\).*?" .
            "digest\('SHA-256', data\).*?new Uint8Array\(buffer\).*?" .
            "padStart\(2, '0'\).*?result\.textContent = hashHex;.*?" .
            "output\.style\.display = 'block';/s",
            $script
        );
        $this->assertStringNotContainsString('result.innerHTML', $script);
    }

    public function testHashFailurePathReportsBothSpecificAndFallbackErrors(): void
    {
        $script = $this->interactiveScript();

        $this->assertMatchesRegularExpression(
            "/\.catch\(function\(err\)\s*\{.*?" .
            "result\.textContent = 'Error computing hash: ' \+ " .
            "\(err\.message \|\| 'Cryptographic operation failed'\);.*?" .
            "output\.style\.display = 'block';.*?\}\);/s",
            $script
        );
    }

    public function testHashInitialisesBeforeAndAfterDomContentLoaded(): void
    {
        $script = $this->interactiveScript();

        $this->assertStringContainsString("document.readyState === 'loading'", $script);
        $this->assertStringContainsString("document.addEventListener('DOMContentLoaded', initCrypto);", $script);
        $this->assertMatchesRegularExpression('/else\s*\{\s*initCrypto\(\);\s*\}/s', $script);
    }

    public function testHashScriptDoesNotTransmitInputOverTheNetwork(): void
    {
        $script = $this->interactiveScript();

        foreach (['fetch(', 'XMLHttpRequest', 'sendBeacon', 'WebSocket'] as $networkApi) {
            $this->assertStringNotContainsString($networkApi, $script);
        }
    }

    public function testBackendTrustBoundaryTreatsBrowserHashesAsUntrusted(): void
    {
        $content = $this->read($this->bodyPath);

        $this->assertStringContainsString('client-computed, untrusted values', $content);
        $this->assertStringContainsString('must recompute or verify received hashes/signatures', $content);
        $this->assertStringContainsString('against server-received raw bytes', $content);
        $this->assertStringContainsString('integrity, identity, or authorization', $content);
        $this->assertStringNotContainsString('pre-verified hashes', strtolower($content));
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

    public function testSitemapTxtRegistersEachReactiveWasmPublishingTargetExactlyOnce(): void
    {
        $content = $this->read($this->root . '/sitemap.txt');

        foreach ([self::CUSTOM_DOMAIN_URL, self::GITHUB_PAGES_URL, self::RENDER_URL] as $url) {
            $this->assertSame(1, substr_count($content, $url), "sitemap.txt must list '{$url}' exactly once.");
        }
    }

    public function testSitemapXmlRegistersExpectedPoliciesForEachReactiveWasmUrl(): void
    {
        $xml = simplexml_load_string($this->read($this->root . '/sitemap.xml'));
        $this->assertNotFalse($xml, 'sitemap.xml must remain valid XML.');

        $actual = [];
        $matchedLocations = [];
        foreach ($xml->url as $urlNode) {
            $location = (string) $urlNode->loc;
            if (!in_array($location, [self::CUSTOM_DOMAIN_URL, self::GITHUB_PAGES_URL, self::RENDER_URL], true)) {
                continue;
            }

            $matchedLocations[] = $location;
            $actual[$location] = [
                (string) $urlNode->lastmod,
                (string) $urlNode->changefreq,
                (string) $urlNode->priority,
            ];
        }

        ksort($actual);
        $expected = [
            self::CUSTOM_DOMAIN_URL => ['2026-08-01', 'weekly', '0.5'],
            self::GITHUB_PAGES_URL => ['2026-08-01', 'weekly', '0.5'],
            self::RENDER_URL => ['2026-08-01', 'weekly', '0.5'],
        ];
        ksort($expected);

        sort($matchedLocations);
        $expectedLocations = array_keys($expected);
        sort($expectedLocations);

        $this->assertSame($expectedLocations, $matchedLocations, 'Each publishing URL must occur exactly once.');
        $this->assertSame($expected, $actual);
    }

    public function testRssAndRorRegisterOneCompleteReactiveWasmItem(): void
    {
        $rss = simplexml_load_string($this->read($this->root . '/rss.xml'));
        $ror = simplexml_load_string($this->read($this->root . '/ror.xml'));
        $this->assertNotFalse($rss, 'rss.xml must remain valid XML.');
        $this->assertNotFalse($ror, 'ror.xml must remain valid XML.');

        $rssItems = $rss->xpath('/rss/channel/item[link="' . self::CUSTOM_DOMAIN_URL . '"]');
        $rorItems = $ror->xpath('/rss/channel/item[link="' . self::CUSTOM_DOMAIN_URL . '"]');
        $this->assertIsArray($rssItems);
        $this->assertIsArray($rorItems);
        $this->assertCount(1, $rssItems, 'RSS must contain exactly one reactive Wasm item.');
        $this->assertCount(1, $rorItems, 'ROR must contain exactly one reactive Wasm item.');

        $rssItem = $rssItems[0];
        $this->assertSame('Reactive wasm lab', (string) $rssItem->title);
        $this->assertSame(self::CUSTOM_DOMAIN_URL, (string) $rssItem->guid);
        $this->assertSame('true', (string) $rssItem->guid['isPermaLink']);
        $this->assertSame('Static details for the Reactive wasm lab module.', (string) $rssItem->description);

        $rorItem = $rorItems[0];
        $rorMetadata = $rorItem->children('http' . '://www.rorweb.com/0.1/');
        $this->assertSame('Reactive wasm lab', (string) $rorItem->title);
        $this->assertSame('resource', (string) $rorMetadata->type);
        $this->assertSame('2026-08-01', (string) $rorMetadata->updated);
    }

    // ---------------------------------------------------------------
    // Helper
    // ---------------------------------------------------------------

    private function interactiveScript(): string
    {
        $matched = preg_match('/<script nonce="[^"]*">(.*?)<\/script>/s', $this->read($this->bodyPath), $matches);
        $this->assertSame(1, $matched, 'Expected exactly one CSP-nonced interactive script.');

        return $matches[1];
    }

    private function renderBodyWithNonce(string $nonce): string
    {
        $ctx = (object) ['cspNonce' => $nonce];
        $rendered = false;
        ob_start();

        try {
            require $this->bodyPath;
            $rendered = ob_get_contents();
        } finally {
            ob_end_clean();
        }

        $this->assertIsString($rendered);

        return $rendered;
    }

    private function assertPhpFileLintsCleanly(string $path): void
    {
        if (!function_exists('exec')) {
            self::markTestSkipped('exec() is unavailable.');
        }

        $disabled = array_map('trim', explode(',', (string) ini_get('disable_functions')));
        if (in_array('exec', $disabled, true)) {
            self::markTestSkipped('exec() disabled.');
        }

        $output = [];
        $exitCode = 0;
        exec(escapeshellarg(PHP_BINARY) . ' -l ' . escapeshellarg($path) . ' 2>&1', $output, $exitCode);

        $this->assertSame(0, $exitCode, "'{$path}' failed 'php -l' syntax validation.");
    }
}
