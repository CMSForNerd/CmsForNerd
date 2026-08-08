<?php

declare(strict_types=1);

namespace CmsForNerd\Tests;

use PHPUnit\Framework\TestCase;

/**
 * Validates the root `.htaccess` file after the "Apache .htaccess & Docker
 * Alignment (v4.1.9)" fix, which removed `<DirectoryMatch>` and `<Directory>`
 * container directives (illegal inside `.htaccess` and previously caused
 * 500 Internal Server Errors on Render) and replaced them with equivalent
 * `mod_rewrite` based `RewriteRule` directives.
 * Validates the root `.htaccess` file introduced in the "Apache .htaccess &
 * Docker Alignment" (v4.1.9) change set.
 *
 * Apache does not permit `<Directory>` or `<DirectoryMatch>` directives
 * inside directory-level context files (`.htaccess`); using them causes an
 * immediate 500 Internal Server Error. This suite guards against that
 * regression and verifies the replacement `mod_rewrite` rules preserve the
 * original security intent (hidden-file protection, sensitive directory
 * blocking, and PHP execution prevention inside uploads/).
 */
final class HtaccessTest extends TestCase
{
    private string $htaccessPath;
    private string $content;

    protected function setUp(): void
    {
        $this->htaccessPath = dirname(__DIR__) . '/.htaccess';
        $this->content = (string) file_get_contents($this->htaccessPath);
    }

    /**
     * Extracts the quoted pattern from the first RewriteRule line that
     * contains the given needle, e.g. extracting `(^|/)\.(?!well-known/)`
     * from `RewriteRule "(^|/)\.(?!well-known/)" - [F]`.
     */
    private function extractRewriteRulePattern(string $lineContains): string
    {
        foreach (explode("\n", $this->content) as $line) {
            if (str_contains($line, 'RewriteRule') && str_contains($line, $lineContains)) {
                $start = strpos($line, '"');
                $end = strrpos($line, '"');

                if ($start === false || $end === false || $end <= $start) {
                    continue;
                }

                return substr($line, $start + 1, $end - $start - 1);
            }
        }

        $this->fail("Could not locate a RewriteRule line containing '{$lineContains}'.");
    }

    public function testHtaccessFileExists(): void
    {
        $this->assertFileExists($this->htaccessPath);
    }

    public function testHtaccessDoesNotUseDirectoryMatchDirective(): void
    {
        $this->assertStringNotContainsStringIgnoringCase(
            '<DirectoryMatch',
            $this->content,
            'Regression guard: <DirectoryMatch> is not permitted inside .htaccess context and previously caused 500 errors.'
        );
    }

    public function testHtaccessDoesNotUseDirectoryContainerDirective(): void
    {
        $this->assertDoesNotMatchRegularExpression(
            '/<Directory[\s"]/i',
            $this->content,
            'Regression guard: <Directory> containers are not permitted inside .htaccess context and previously caused 500 errors.'
        );
    }

    public function testHtaccessDoesNotUsePhpFlagDirective(): void
    {
        $this->assertStringNotContainsString(
            'php_flag engine off',
            $this->content,
            'The old php_flag-inside-<Directory> approach for disabling PHP execution was removed in favour of a RewriteRule.'
        );
    }

    public function testHtaccessBlocksHiddenFilesAndDirectoriesViaRewriteRule(): void
    {
        $this->assertStringContainsString(
            'RewriteRule "(^|/)\.(?!well-known/)" - [F]',
            $this->content,
            'Hidden files/directories (dotfiles) must be blocked via RewriteRule, mirroring the removed <DirectoryMatch "^\.|\/\."> behaviour, while exempting .well-known/.'
        );
    }

    public function testHtaccessBlocksSensitiveDirectoriesViaRewriteRule(): void
    {
        $this->assertStringContainsString(
            'RewriteRule "^(includes|tests|vendor|\.vscode)(/|$)" - [F]',
            $this->content,
            'includes/tests/vendor/.vscode must remain blocked, mirroring the removed <DirectoryMatch> directive.'
        );
    }

    public function testHtaccessDeclaresRewriteEngineOn(): void
    {
        $this->assertStringContainsString('RewriteEngine On', $this->content);
    }

    public function testHtaccessHiddenFileAndSensitiveDirectoryRulesAreWrappedInModRewriteIfModule(): void
    {
        $ifModuleOpen = strpos($this->content, '<IfModule mod_rewrite.c>');
        $this->assertNotFalse($ifModuleOpen, 'A mod_rewrite IfModule guard must exist.');

        $rewriteEngineOn = strpos($this->content, 'RewriteEngine On', $ifModuleOpen);
        $hiddenRule = strpos(
            $this->content,
            'RewriteRule "(^|/)\.(?!well-known/)" - [F]',
            $ifModuleOpen
        );
        $sensitiveRule = strpos(
            $this->content,
            'RewriteRule "^(includes|tests|vendor|\.vscode)(/|$)" - [F]',
            $ifModuleOpen
        );
        $ifModuleClose = strpos($this->content, '</IfModule>', $ifModuleOpen);

        $this->assertNotFalse($rewriteEngineOn, 'RewriteEngine On must follow the IfModule guard.');
        $this->assertNotFalse($hiddenRule, 'The hidden-file RewriteRule must follow the IfModule guard.');
        $this->assertNotFalse($sensitiveRule, 'The sensitive-directory RewriteRule must follow the IfModule guard.');
        $this->assertNotFalse($ifModuleClose, 'The IfModule guard must be closed.');

        $this->assertTrue(
            $ifModuleOpen < $rewriteEngineOn
            && $rewriteEngineOn < $hiddenRule
            && $hiddenRule < $sensitiveRule
            && $sensitiveRule < $ifModuleClose,
            'RewriteEngine On and both directory-protection RewriteRules must be declared, in order, inside the ' .
            'same <IfModule mod_rewrite.c> ... </IfModule> guard.'
        );
    }

    public function testHtaccessRetainsSecurityTxtException(): void
    {
        $filesOpen = strpos($this->content, '<Files "security.txt">');
        $this->assertNotFalse($filesOpen, 'security.txt must remain publicly reachable per RFC 9116.');

        $filesClose = strpos($this->content, '</Files>', $filesOpen);
        $this->assertNotFalse($filesClose, 'The security.txt Files block must be closed.');

        $block = substr($this->content, $filesOpen, $filesClose - $filesOpen);
        $this->assertStringContainsString(
            'Require all granted',
            $block,
            'security.txt must be explicitly allowed even though sibling hidden files are blocked.'
        );
    }

    public function testHtaccessBlocksPhpExecutionInUploadsDirectoryViaRewriteRule(): void
    {
        $this->assertStringContainsString(
            'RewriteRule "^uploads/.*\.php" - [F,NC]',
            $this->content,
            'PHP execution inside an uploads/ directory must be blocked via RewriteRule instead of the removed <Directory "*/uploads/*"> block.'
        );
    }

    public function testHtaccessUploadsPhpRewriteRuleIsWrappedInModRewriteIfModule(): void
    {
        $uploadsRule = strpos($this->content, 'RewriteRule "^uploads/.*\.php" - [F,NC]');
        $this->assertNotFalse($uploadsRule, 'Uploads PHP-blocking RewriteRule must exist.');

        $precedingContent = substr($this->content, 0, $uploadsRule);
        $nearestIfModuleOpen = strrpos($precedingContent, '<IfModule mod_rewrite.c>');
        $this->assertNotFalse(
            $nearestIfModuleOpen,
            'The uploads RewriteRule must be preceded by an <IfModule mod_rewrite.c> guard.'
        );

        $ifModuleClose = strpos($this->content, '</IfModule>', $uploadsRule);
        $this->assertNotFalse($ifModuleClose, 'The uploads RewriteRule guard must be closed with </IfModule>.');

        $nearestClosePriorToRule = strpos($precedingContent, '</IfModule>', $nearestIfModuleOpen);
        $this->assertFalse(
            $nearestClosePriorToRule,
            'The uploads RewriteRule must be directly enclosed by its nearest <IfModule mod_rewrite.c> guard, not nested outside it.'
        );
    }

    public function testHtaccessHasBalancedIfModuleTags(): void
    {
        $opens = preg_match_all('/<IfModule\b/', $this->content);
        $closes = preg_match_all('/<\/IfModule>/', $this->content);

        $this->assertSame($opens, $closes, '<IfModule> tags must be balanced after replacing <DirectoryMatch>/<Directory> blocks.');
        $this->assertGreaterThanOrEqual(2, $opens, 'At least the two mod_rewrite guards introduced in this fix must be present.');
    }

    public function testHtaccessStillProtectsConfigurationFilesViaFilesMatch(): void
    {
        $this->assertStringContainsString(
            '<FilesMatch "(composer\.(json|lock)|\.git.*|\.env.*|\.cursorrules|\.gitattributes)$">',
            $this->content,
            'Unrelated FilesMatch-based configuration file protection must remain intact.'
        );
    }

    public function testHtaccessStillDisablesDirectoryBrowsing(): void
    {
        $this->assertStringContainsString('Options -Indexes', $this->content);
    }

    public function testHtaccessStillDeclaresSecurityHeaders(): void
    {
        $this->assertStringContainsString('X-Frame-Options', $this->content);
        $this->assertStringContainsString('X-Content-Type-Options', $this->content);
        $this->assertStringContainsString('Referrer-Policy', $this->content);
    }

    public function testHtaccessStillDeclaresCustomErrorDocuments(): void
    {
        $this->assertStringContainsString('ErrorDocument 403', $this->content);
        $this->assertStringContainsString('ErrorDocument 404', $this->content);
        $this->assertStringContainsString('ErrorDocument 500', $this->content);
    }

    public function testHtaccessDoesNotUseDirectoryMatchTag(): void
    {
        $this->assertStringNotContainsString(
            '<DirectoryMatch',
            $this->content,
            '<DirectoryMatch> is not permitted in .htaccess files and causes 500 Internal Server Errors.'
        );
        $this->assertStringNotContainsString('</DirectoryMatch>', $this->content);
    }

    public function testHtaccessDoesNotUseDirectoryTag(): void
    {
        $this->assertDoesNotMatchRegularExpression(
            '/<Directory[\s">]/',
            $this->content,
            '<Directory> is not permitted in .htaccess files and causes 500 Internal Server Errors.'
        );
        $this->assertStringNotContainsString('</Directory>', $this->content);
    }

    public function testHtaccessNoLongerContainsLegacyBrokenDirectives(): void
    {
        // Regression guard: the exact directives that used to trigger 500s.
        $this->assertStringNotContainsString('<DirectoryMatch "^\.|\/\.">', $this->content);
        $this->assertStringNotContainsString(
            '<DirectoryMatch "^/(includes|tests|vendor|\.vscode|\.well-known)">',
            $this->content
        );
        $this->assertStringNotContainsString('<Directory "*/uploads/*">', $this->content);
    }

    public function testHtaccessWrapsRewriteRulesInModRewriteIfModule(): void
    {
        $this->assertStringContainsString('<IfModule mod_rewrite.c>', $this->content);
        $this->assertStringContainsString('RewriteEngine On', $this->content);

        $openCount = substr_count($this->content, '<IfModule mod_rewrite.c>');

        $this->assertGreaterThanOrEqual(
            2,
            $openCount,
            'Expected at least two mod_rewrite.c blocks (directory protection + upload PHP block).'
        );
    }

    public function testHtaccessIfModuleTagsAreBalanced(): void
    {
        $openCount = preg_match_all('/<IfModule\b[^>]*>/', $this->content);
        $closeCount = preg_match_all('/<\/IfModule>/', $this->content);

        $this->assertSame($openCount, $closeCount, '<IfModule> blocks must be balanced with matching closing tags.');
    }

    public function testHtaccessDeclaresSensitiveDirectoryRewriteRule(): void
    {
        $this->assertStringContainsString(
            'RewriteRule "^(includes|tests|vendor|\.vscode)(/|$)" - [F]',
            $this->content
        );
    }

    public function testHtaccessDeclaresHiddenFileRewriteRule(): void
    {
        $this->assertStringContainsString(
            'RewriteRule "(^|/)\.(?!well-known/)" - [F]',
            $this->content
        );
    }

    public function testHtaccessDeclaresUploadsPhpExecutionBlockRewriteRule(): void
    {
        $this->assertStringContainsString(
            'RewriteRule "^uploads/.*\.php" - [F,NC]',
            $this->content
        );
    }

    public function testHtaccessRetainsSecurityTxtExceptionRegex(): void
    {
        $this->assertMatchesRegularExpression(
            '/<Files "security\.txt">\s*\n\s*Require all granted\s*\n<\/Files>/',
            $this->content,
            'RFC 9116 requires .well-known/security.txt to remain publicly accessible.'
        );
    }

    /**
     * Boundary/negative test: verifies the actual regex semantics of the
     * hidden-file blocking rule, including the well-known negative lookahead
     * exception, by applying the extracted pattern to representative paths.
     */
    public function testHiddenFileRewritePatternBlocksDotPathsExceptWellKnown(): void
    {
        $pattern = $this->extractRewriteRulePattern('well-known');

        foreach (['.env', '.git/config', 'includes/.htpasswd', '.htaccess'] as $blockedPath) {
            $this->assertSame(
                1,
                preg_match('#' . $pattern . '#', $blockedPath),
                "Expected '{$blockedPath}' to be blocked by the hidden-file RewriteRule pattern."
            );
        }

        foreach (['.well-known/security.txt', '.well-known/'] as $allowedPath) {
            $this->assertSame(
                0,
                preg_match('#' . $pattern . '#', $allowedPath),
                "Expected '{$allowedPath}' to remain accessible under the hidden-file RewriteRule pattern (RFC 9116)."
            );
        }

        $this->assertSame(
            0,
            preg_match('#' . $pattern . '#', 'index.php'),
            'Ordinary top-level files without a leading dot must not be blocked.'
        );
    }

    /**
     * Boundary/negative test: verifies the sensitive-directory RewriteRule
     * matches the intended directories exactly and does not over-match
     * similarly-prefixed paths.
     */
    public function testSensitiveDirectoryRewritePatternMatchesOnlyIntendedDirectories(): void
    {
        $pattern = $this->extractRewriteRulePattern('includes|tests|vendor');

        foreach (['includes/config.php', 'tests', 'tests/', 'vendor/autoload.php', '.vscode/settings.json'] as $blockedPath) {
            $this->assertSame(
                1,
                preg_match('#' . $pattern . '#', $blockedPath),
                "Expected '{$blockedPath}' to be blocked by the sensitive-directory RewriteRule pattern."
            );
        }

        foreach (['includesx/file.php', 'assets/includes-like/file.php', 'data/pages/tests.md'] as $allowedPath) {
            $this->assertSame(
                0,
                preg_match('#' . $pattern . '#', $allowedPath),
                "Expected '{$allowedPath}' NOT to be blocked by the sensitive-directory RewriteRule pattern (prefix collision guard)."
            );
        }
    }

    /**
     * Boundary/negative test: verifies the uploads/ PHP execution block only
     * targets PHP files within uploads/, is case-insensitive (NC flag), and
     * does not affect non-PHP assets or files outside uploads/.
     */
    public function testUploadsPhpExecutionRewritePatternIsScopedAndCaseInsensitive(): void
    {
        $pattern = $this->extractRewriteRulePattern('uploads');

        $this->assertSame(1, preg_match('#' . $pattern . '#i', 'uploads/shell.php'));
        $this->assertSame(1, preg_match('#' . $pattern . '#i', 'uploads/evil.PHP'), 'Rule must be case-insensitive to match the [NC] flag.');
        $this->assertSame(0, preg_match('#' . $pattern . '#i', 'uploads/image.jpg'));
        $this->assertSame(0, preg_match('#' . $pattern . '#i', 'other/shell.php'), 'Rule must be anchored to the uploads/ directory only.');
    }

    public function testHtaccessRetainsUnrelatedPreExistingDirectives(): void
    {
        $this->assertStringContainsString('Options -Indexes', $this->content);
        $this->assertStringContainsString(
            '<FilesMatch "(composer\.(json|lock)|\.git.*|\.env.*|\.cursorrules|\.gitattributes)$">',
            $this->content
        );
        $this->assertStringContainsString('Header always set X-Frame-Options "SAMEORIGIN"', $this->content);
        $this->assertStringContainsString('ErrorDocument 403 "Access Denied"', $this->content);
        $this->assertStringContainsString('<IfModule mod_deflate.c>', $this->content);
        $this->assertStringContainsString('<IfModule mod_expires.c>', $this->content);
    }
}
