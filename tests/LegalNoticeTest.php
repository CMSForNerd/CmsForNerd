<?php

declare(strict_types=1);

namespace CmsForNerd\Tests;

use PHPUnit\Framework\TestCase;

final class LegalNoticeTest extends TestCase
{
    /**
     * Test existence of Legal Notice files
     */
    public function testLegalNoticeFilesExist(): void
    {
        $controller = dirname(__DIR__) . '/legal-notice.php';
        $body = dirname(__DIR__) . '/contents/legal-notice-body.inc';

        $this->assertFileExists($controller, 'Page controller legal-notice.php must exist.');
        $this->assertFileExists($body, 'Content body contents/legal-notice-body.inc must exist.');
    }

    /**
     * Test that legal-notice.php has declare(strict_types=1);
     */
    public function testStrictTypesInLegalNoticeController(): void
    {
        $controller = dirname(__DIR__) . '/legal-notice.php';
        $content = (string) file_get_contents($controller);

        $this->assertStringContainsString(
            'declare(strict_types=1);',
            $content,
            'legal-notice.php must declare strict types.'
        );
    }

    /**
     * Test contents of legal-notice-body.inc to ensure all key terms/phrases are included
     */
    public function testLegalNoticeBodyContainsRequiredTexts(): void
    {
        $body = dirname(__DIR__) . '/contents/legal-notice-body.inc';
        $content = (string) file_get_contents($body);

        // Required text checks from the user query
        $this->assertStringContainsString('All costs, designs, unit amounts, and scenarios', $content);
        $this->assertStringContainsString('based entirely on assumptions', $content);
        $this->assertStringContainsString('strictly for training, educational, and planning proposal purposes', $content);
        $this->assertStringContainsString('Use at your own risk', $content);
        $this->assertStringContainsString('The project contributors, authors, and organisations shall not be held liable or responsible', $content);
        $this->assertStringContainsString('We have done our best to protect anyone and organisation', $content);
        $this->assertStringContainsString('We are not going to be responsible', $content);
    }

    /**
     * Test that footers reference the Legal Notice page
     */
    public function testFootersContainLegalNoticeLinks(): void
    {
        $footerFile = dirname(__DIR__) . '/contents/footer.inc';
        $footerContent = (string) file_get_contents($footerFile);

        $this->assertStringContainsString('legal-notice.php', $footerContent);
        $this->assertStringContainsString('[ REGULATION: DISCLAIMER ] | [ PURPOSE: TRAINING ] | [ RISK: ASSUMED ]', $footerContent);

        $pagerFile = dirname(__DIR__) . '/themes/CmsForNerd/pager.php';
        $pagerContent = (string) file_get_contents($pagerFile);

        $this->assertStringContainsString('legal-notice.php?view=amp', $pagerContent);
    }
}
