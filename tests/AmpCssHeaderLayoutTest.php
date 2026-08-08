<?php

declare(strict_types=1);

namespace CmsForNerd\Tests;

use PHPUnit\Framework\TestCase;

/**
 * Regression coverage for the AMP header layout fix ("themes: fix AMP
 * header layout for sidebar toggle"). Prior to this change the primary
 * `.amp-header` rule in amp.css used a column-based flex layout
 * (`flex-direction: column`) with taller vertical padding and a smaller
 * gap, which visually mismatched the later "FIX: Header Layout for
 * Sidebar Toggle" rule declared further down in the same stylesheet.
 * This change makes the row-based layout, padding, and gap consistent
 * across both `.amp-header` declarations in the file.
 */
final class AmpCssHeaderLayoutTest extends TestCase
{
    private string $ampCssPath;
    private string $content;

    protected function setUp(): void
    {
        $this->ampCssPath = dirname(__DIR__) . '/themes/CmsForNerd/css/amp.css';
        $this->content = (string) file_get_contents($this->ampCssPath);
    }

    public function testAmpCssFileExists(): void
    {
        $this->assertFileExists($this->ampCssPath);
    }

    public function testAmpHeaderRuleUsesUpdatedPadding(): void
    {
        $this->assertStringContainsString('padding: 10px 20px;', $this->content);
    }

    public function testAmpHeaderRuleNoLongerUsesTheStaleTallerPadding(): void
    {
        $this->assertStringNotContainsString('padding: 15px 20px;', $this->content);
    }

    public function testAmpHeaderRuleUsesRowFlexDirection(): void
    {
        $this->assertStringContainsString('flex-direction: row;', $this->content);
    }

    public function testAmpHeaderRuleNoLongerUsesColumnFlexDirection(): void
    {
        $this->assertStringNotContainsString('flex-direction: column;', $this->content);
    }

    public function testAmpHeaderRuleAlignsItemsToCenter(): void
    {
        $this->assertStringContainsString('align-items: center;', $this->content);
    }

    public function testAmpHeaderRuleJustifiesContentToFlexStart(): void
    {
        $this->assertStringContainsString('justify-content: flex-start;', $this->content);
    }

    public function testAmpHeaderRuleUsesFifteenPixelGap(): void
    {
        $this->assertStringContainsString('gap: 15px;', $this->content);
    }

    public function testAmpHeaderRuleNoLongerUsesTheStaleFivePixelGap(): void
    {
        $this->assertStringNotContainsString('gap: 5px;', $this->content);
    }

    /**
     * The stylesheet declares `.amp-header { ... }` twice: the original
     * rule near the top of the file, and a later "FIX" rule that layers
     * glassmorphism styling on top. Both declarations must now agree on
     * the flex layout so the header renders identically regardless of
     * which rule "wins" the cascade.
     */
    public function testBothAmpHeaderRuleBlocksShareTheSameFlexLayout(): void
    {
        $blocks = $this->extractAmpHeaderBlocks();

        $this->assertCount(
            2,
            $blocks,
            'Expected exactly two top-level `.amp-header { ... }` rule blocks in amp.css.'
        );

        foreach ($blocks as $index => $block) {
            $this->assertStringContainsString(
                'flex-direction: row',
                $block,
                "Block #{$index} must use a row flex-direction."
            );
            $this->assertStringContainsString(
                'align-items: center',
                $block,
                "Block #{$index} must center-align items."
            );
            $this->assertStringContainsString(
                'justify-content: flex-start',
                $block,
                "Block #{$index} must justify content to flex-start."
            );
            $this->assertStringContainsString(
                'gap: 15px',
                $block,
                "Block #{$index} must use a 15px gap."
            );
            $this->assertStringContainsString(
                'padding: 10px 20px',
                $block,
                "Block #{$index} must use 10px 20px padding."
            );
            $this->assertStringNotContainsString(
                'flex-direction: column',
                $block,
                "Block #{$index} must not regress back to a column flex-direction."
            );
        }
    }

    public function testAmpHeaderDeclarationStillIncludesRequiredBaseStyling(): void
    {
        // Guard against the layout fix accidentally dropping unrelated
        // properties that were already present on the primary rule.
        $this->assertStringContainsString('background: var(--lab-bg);', $this->content);
        $this->assertStringContainsString('border-bottom: 2px solid var(--lab-purple);', $this->content);
        $this->assertStringContainsString('display: flex;', $this->content);
    }

    public function testAmpHeaderAnchorStylingIsUnaffectedByTheLayoutFix(): void
    {
        // `.amp-header a` was not part of this change and must remain intact.
        $this->assertMatchesRegularExpression(
            '/\.amp-header\s+a\s*\{[^}]*color:\s*var\(--lab-purple\);[^}]*\}/s',
            $this->content
        );
    }

    /**
     * @return string[] The inner contents of each top-level `.amp-header { ... }`
     *                   rule block (excluding descendant selectors such as
     *                   `.amp-header a` or `.amp-header button`).
     */
    private function extractAmpHeaderBlocks(): array
    {
        preg_match_all('/\.amp-header\s*\{([^}]*)\}/s', $this->content, $matches);

        return $matches[1];
    }
}
