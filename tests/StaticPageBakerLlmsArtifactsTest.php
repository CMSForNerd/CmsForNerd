<?php

declare(strict_types=1);

namespace CmsForNerd\Tests;

use PHPUnit\Framework\TestCase;

/**
 * Validates that the static page baker (tools/bake-static-pages.php) is
 * configured to publish the LLM-facing context artifacts (llms.txt,
 * llms-full.txt, llms.xml) into the static build output, and that those
 * generated artifacts exist at the repository root and are structurally
 * consistent with their `llms.txt` source of truth.
 */
final class StaticPageBakerLlmsArtifactsTest extends TestCase
{
    private string $bakerCode;
    private string $llmsTxtPath;
    private string $llmsFullPath;
    private string $llmsXmlPath;

    protected function setUp(): void
    {
        $root = dirname(__DIR__);
        $this->bakerCode = (string) file_get_contents($root . '/tools/bake-static-pages.php');
        $this->llmsTxtPath = $root . '/llms.txt';
        $this->llmsFullPath = $root . '/llms-full.txt';
        $this->llmsXmlPath = $root . '/llms.xml';
    }

    /**
     * Extracts the `$filesToCopy` array literal from the baker source so
     * assertions can be made against its contents without relying on brittle
     * substring positions within the whole file.
     */
    private function extractFilesToCopyArray(): array
    {
        $matched = preg_match(
            '/\$filesToCopy\s*=\s*\[(.*?)\];/s',
            $this->bakerCode,
            $matches
        );
        $this->assertSame(1, $matched, 'Expected to find a single $filesToCopy = [...]; declaration.');

        $entries = [];
        foreach (explode(',', $matches[1]) as $rawEntry) {
            $trimmed = trim($rawEntry);
            if ($trimmed === '') {
                continue;
            }
            $entries[] = trim($trimmed, "'\"");
        }

        return $entries;
    }

    public function testBakerSourceExists(): void
    {
        $this->assertNotSame('', $this->bakerCode);
    }

    public function testFilesToCopyIncludesAllThreeLlmsArtifacts(): void
    {
        $entries = $this->extractFilesToCopyArray();

        $this->assertContains('llms.txt', $entries, '$filesToCopy must include llms.txt.');
        $this->assertContains('llms-full.txt', $entries, '$filesToCopy must include llms-full.txt.');
        $this->assertContains('llms.xml', $entries, '$filesToCopy must include llms.xml.');
    }

    public function testLlmsArtifactsAreAppendedAfterPreExistingSchemaOrgEntry(): void
    {
        $entries = $this->extractFilesToCopyArray();

        $schemaOrgIndex = array_search('schema-org.json', $entries, true);
        $llmsTxtIndex = array_search('llms.txt', $entries, true);
        $llmsFullIndex = array_search('llms-full.txt', $entries, true);
        $llmsXmlIndex = array_search('llms.xml', $entries, true);

        $this->assertNotFalse($schemaOrgIndex, 'Pre-existing schema-org.json entry must remain.');
        $this->assertNotFalse($llmsTxtIndex);
        $this->assertNotFalse($llmsFullIndex);
        $this->assertNotFalse($llmsXmlIndex);

        $this->assertGreaterThan($schemaOrgIndex, $llmsTxtIndex);
        $this->assertGreaterThan($llmsTxtIndex, $llmsFullIndex);
        $this->assertGreaterThan($llmsFullIndex, $llmsXmlIndex);
    }

    public function testFilesToCopyLoopWouldCopyEachRegisteredLlmsFileIfPresent(): void
    {
        // The baker only copies files that exist via file_exists(); confirm
        // the copy step's guard is still intact so newly-registered files
        // are not unconditionally copied (which would emit warnings when
        // missing in fresh checkouts before generation).
        $this->assertMatchesRegularExpression(
            '/foreach\s*\(\s*\$filesToCopy\s+as\s+\$fileName\s*\)\s*\{\s*if\s*\(\s*file_exists\(\$fileName\)\s*\)/s',
            $this->bakerCode
        );
    }

    public function testGeneratedLlmsTxtExistsAtRepositoryRoot(): void
    {
        $this->assertFileExists($this->llmsTxtPath);
    }

    public function testGeneratedLlmsFullTxtExistsAndIsNonEmpty(): void
    {
        $this->assertFileExists($this->llmsFullPath);
        $content = (string) file_get_contents($this->llmsFullPath);
        $this->assertNotSame('', trim($content));
        $this->assertStringContainsString('- Full Consolidated Documentation', $content);
    }

    public function testGeneratedLlmsXmlExistsAndIsWellFormed(): void
    {
        $this->assertFileExists($this->llmsXmlPath);
        $content = (string) file_get_contents($this->llmsXmlPath);
        $this->assertNotSame('', trim($content));

        $this->assertStringStartsWith('<project ', $content);
        $this->assertStringEndsWith("</project>\n", $content);

        // Confirm the document is parseable XML (structurally well-formed).
        $previousSetting = libxml_use_internal_errors(true);
        $xml = simplexml_load_string($content);
        $errors = libxml_get_errors();
        libxml_clear_errors();
        libxml_use_internal_errors($previousSetting);

        $this->assertNotFalse($xml, 'llms.xml must be parseable as well-formed XML.');
        $this->assertEmpty($errors, 'llms.xml must not contain XML parsing errors/warnings.');
    }

    public function testEverySectionHeadingInLlmsTxtIsRepresentedInBothArtifacts(): void
    {
        $sourceContent = (string) file_get_contents($this->llmsTxtPath);
        $matchCount = preg_match_all('/^##\s+(.+)$/m', $sourceContent, $sectionMatches);
        $this->assertGreaterThan(0, $matchCount, 'llms.txt must declare at least one ## section heading.');

        $fullContent = (string) file_get_contents($this->llmsFullPath);
        $xmlContent = (string) file_get_contents($this->llmsXmlPath);

        foreach ($sectionMatches[1] as $sectionTitle) {
            $sectionTitle = trim($sectionTitle);
            $this->assertStringContainsString(
                '## Section: ' . $sectionTitle,
                $fullContent,
                "llms-full.txt must contain a consolidated section for '{$sectionTitle}'."
            );
            $this->assertStringContainsString(
                'title="' . htmlspecialchars($sectionTitle, ENT_XML1 | ENT_QUOTES) . '"',
                $xmlContent,
                "llms.xml must contain a <section> tag for '{$sectionTitle}'."
            );
        }
    }

    public function testLlmsXmlProjectTitleMatchesLlmsTxtHeading(): void
    {
        $sourceContent = (string) file_get_contents($this->llmsTxtPath);
        $matched = preg_match('/^#\s+(.+)$/m', $sourceContent, $titleMatches);
        $this->assertSame(1, $matched, 'llms.txt must declare a top-level # title heading.');
        $expectedTitle = trim($titleMatches[1]);

        $xmlContent = (string) file_get_contents($this->llmsXmlPath);
        $this->assertStringContainsString(
            'title="' . htmlspecialchars($expectedTitle, ENT_XML1 | ENT_QUOTES) . '"',
            $xmlContent
        );
    }
}