<?php

declare(strict_types=1);

namespace CmsForNerd\Tests;

use PHPUnit\Framework\TestCase;

/**
 * Validates the Dockerfile, Containerfile and .dockerignore introduced/updated
 * in the "Resolve Render Blueprint build failure" change set.
 *
 * Render's cloud build stage expects a `Dockerfile`, while local Podman
 * workflows expect a `Containerfile`. Both files must stay in sync and the
 * build context must exclude development-only assets.
 */
final class DockerBuildTest extends TestCase
{
    private string $dockerfilePath;
    private string $containerfilePath;
    private string $dockerignorePath;

    protected function setUp(): void
    {
        $this->dockerfilePath = dirname(__DIR__) . '/Dockerfile';
        $this->containerfilePath = dirname(__DIR__) . '/Containerfile';
        $this->dockerignorePath = dirname(__DIR__) . '/.dockerignore';
    }

    public function testDockerfileExists(): void
    {
        $this->assertFileExists($this->dockerfilePath, 'Root-level Dockerfile is required by render.yaml (dockerfilePath: Dockerfile).');
    }

    public function testContainerfileExists(): void
    {
        $this->assertFileExists($this->containerfilePath, 'Root-level Containerfile must remain for local Podman workflows.');
    }

    public function testDockerfileUsesSupportedPhpApacheBaseImage(): void
    {
        $content = file_get_contents($this->dockerfilePath);

        $this->assertMatchesRegularExpression(
            '/^FROM php:8\.4-apache AS builder$/m',
            $content,
            'Builder stage must be based on the php:8.4-apache image.'
        );
    }

    public function testDockerfileIsMultiStageBuild(): void
    {
        $content = file_get_contents($this->dockerfilePath);

        $fromCount = preg_match_all('/^FROM /m', $content);
        $this->assertSame(2, $fromCount, 'Dockerfile must declare exactly two build stages (builder + runtime).');
        $this->assertMatchesRegularExpression('/^FROM php:8\.4-apache$/m', $content, 'Final runtime stage must be a fresh php:8.4-apache image.');
    }

    public function testDockerfileInstallsComposerFromPinnedOfficialImage(): void
    {
        $content = file_get_contents($this->dockerfilePath);

        $this->assertStringContainsString(
            'COPY --from=composer:2.8 /usr/bin/composer /usr/bin/composer',
            $content,
            'Composer binary must be copied from the pinned composer:2.8 image rather than installed via curl.'
        );
    }

    public function testDockerfileValidatesPlatformRequirementsBeforeInstall(): void
    {
        $content = file_get_contents($this->dockerfilePath);

        $this->assertStringContainsString('composer check-platform-reqs', $content);
        $this->assertStringContainsString(
            'composer install --no-dev --no-scripts --no-progress --optimize-autoloader --no-interaction',
            $content,
            'Production image must install dependencies without dev packages or install-time scripts.'
        );
    }

    public function testDockerfileEnablesRequiredApacheModules(): void
    {
        $content = file_get_contents($this->dockerfilePath);

        $this->assertStringContainsString(
            'a2enmod rewrite headers',
            $content,
            'mod_rewrite (routing) and mod_headers (security headers) must both be enabled.'
        );
    }

    public function testDockerfileAllowsHtaccessOverridesOnDocumentRootOnly(): void
    {
        $content = file_get_contents($this->dockerfilePath);

        $this->assertMatchesRegularExpression(
            '/<Directory \/var\/www\/html>\s*\n\s*AllowOverride All\s*\n\s*Require all granted\s*\n<\/Directory>/',
            $content,
            'AllowOverride must be scoped to /var/www/html so .htaccess security controls are respected.'
        );
        $this->assertStringContainsString('a2enconf document-root-override', $content);
    }

    public function testDockerfileCopiesVendorFromBuilderStage(): void
    {
        $content = file_get_contents($this->dockerfilePath);

        $this->assertStringContainsString(
            'COPY --from=builder /app/vendor /var/www/html/vendor',
            $content,
            'Runtime stage must reuse dependencies built in the builder stage instead of reinstalling them.'
        );
    }

    public function testDockerfileGrantsWriteAccessOnlyToDataDirectory(): void
    {
        $content = file_get_contents($this->dockerfilePath);

        $this->assertMatchesRegularExpression(
            '/^RUN chown -R www-data:www-data \/var\/www\/html\/data$/m',
            $content,
            'Only the runtime data directory should be chowned to www-data; the rest of the tree stays root-owned for defence in depth.'
        );
        $this->assertDoesNotMatchRegularExpression(
            '/chown -R www-data:www-data \/var\/www\/html\s*$/m',
            $content,
            'Ownership must not be granted recursively across the entire webroot.'
        );
    }

    public function testDockerfileExposesPort80(): void
    {
        $content = file_get_contents($this->dockerfilePath);

        $this->assertMatchesRegularExpression('/^EXPOSE 80$/m', $content);
    }

    public function testDockerfileExposesBothStandardAndLegacyPorts(): void
    {
        $content = file_get_contents($this->dockerfilePath);

        preg_match_all('/^EXPOSE (\d+)$/m', $content, $matches);

        $this->assertSame(
            ['80', '8080'],
            $matches[1],
            'Regression guard: adding EXPOSE 80 must not remove the pre-existing EXPOSE 8080 that Apache is configured to listen on inside the container.'
        );
    }

    public function testDockerfileAndContainerfileAreFunctionallyIdentical(): void
    {
        $dockerfileContent = file_get_contents($this->dockerfilePath);
        $containerfileContent = file_get_contents($this->containerfilePath);

        // The only intentional difference between the two files is the
        // human-readable label naming which artefact they describe.
        $normalizedContainerfile = str_replace('Containerfile', 'Dockerfile', $containerfileContent);

        $this->assertSame(
            $dockerfileContent,
            $normalizedContainerfile,
            'Dockerfile and Containerfile must stay in sync so both Podman and Render/Docker builds behave identically.'
        );
    }

    public function testDockerignoreExists(): void
    {
        $this->assertFileExists($this->dockerignorePath);
    }

    private function getDockerignoreEntries(): array
    {
        $lines = file($this->dockerignorePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        return array_map('trim', $lines);
    }

    public function testDockerignoreExcludesVersionControlAndEditorArtefacts(): void
    {
        $entries = $this->getDockerignoreEntries();

        $this->assertContains('.git', $entries);
        $this->assertContains('.github', $entries);
        $this->assertContains('.vscode', $entries);
    }

    public function testDockerignoreExcludesTestingAndTooling(): void
    {
        $entries = $this->getDockerignoreEntries();

        foreach (['tests', 'tools', 'playbooks', 'inventory', 'technical-resources', '.phpunit.cache'] as $expected) {
            $this->assertContains($expected, $entries, "Expected .dockerignore to exclude '{$expected}'.");
        }
    }

    public function testDockerignoreExcludesVendorDirectory(): void
    {
        $entries = $this->getDockerignoreEntries();

        $this->assertContains(
            'vendor',
            $entries,
            'vendor/ is (re)installed inside the image by Composer and should not be copied from the host.'
        );
    }

    public function testDockerignoreExcludesContainerAndCiDefinitionFiles(): void
    {
        $entries = $this->getDockerignoreEntries();

        foreach (['Dockerfile', 'Containerfile', 'render.yaml'] as $expected) {
            $this->assertContains($expected, $entries, "Expected .dockerignore to exclude '{$expected}' from the build context.");
        }
    }

    public function testDockerignoreExcludesLintingAndTestConfigFiles(): void
    {
        $entries = $this->getDockerignoreEntries();

        foreach (['phpstan.neon', 'phpunit.xml', 'phpcs.xml'] as $expected) {
            $this->assertContains($expected, $entries, "Expected .dockerignore to exclude '{$expected}'.");
        }
    }

    public function testDockerignoreExcludesLogFilesByGlob(): void
    {
        $entries = $this->getDockerignoreEntries();

        $this->assertContains('*.log', $entries);
    }

    public function testDockerignoreDoesNotExcludeApplicationSourceDirectories(): void
    {
        $entries = $this->getDockerignoreEntries();

        foreach (['src', 'includes', 'data', 'composer.json', 'composer.lock'] as $mustBeShipped) {
            $this->assertNotContains(
                $mustBeShipped,
                $entries,
                "'{$mustBeShipped}' must be copied into the image and must not be present in .dockerignore."
            );
        }
    }

    public function testDockerignoreHasNoDuplicateEntries(): void
    {
        $entries = $this->getDockerignoreEntries();

        $this->assertSame(
            count($entries),
            count(array_unique($entries)),
            '.dockerignore should not contain duplicate patterns.'
        );
    }

    public function testDockerignoreHeaderBannerSeparatorLinesAreNotVerbatimDuplicates(): void
    {
        $lines = file($this->dockerignorePath, FILE_IGNORE_NEW_LINES);
        $headerBannerLines = array_slice($lines, 0, 10);

        $separatorLines = array_values(array_filter(
            $headerBannerLines,
            static fn (string $line): bool => (bool) preg_match('/^#\s*[=\-]+$/', trim($line))
        ));

        $this->assertNotEmpty($separatorLines, 'Expected the DSOM header banner to contain at least one comment separator line.');
        $this->assertSame(
            count($separatorLines),
            count(array_unique($separatorLines)),
            'Regression guard: the header banner previously repeated an identical "# ====...." separator line twice, ' .
            'which failed the file-wide duplicate-entry check.'
        );
    }
    public function testDockerignoreHeaderSeparatorLinesAreNotIdentical(): void
    {
        // Regression guard: the header banner previously repeated the exact
        // same "====...====" separator line twice, which violated the
        // uniqueness constraint enforced by testDockerignoreHasNoDuplicateEntries().
        $entries = $this->getDockerignoreEntries();
        $separatorLines = array_values(array_filter(
            $entries,
            static fn (string $line): bool => (bool) preg_match('/^#\s*[=-]{10,}$/', $line)
        ));

        $this->assertSame(
            count($separatorLines),
            count(array_unique($separatorLines)),
            'Header banner separator comment lines must not be byte-for-byte duplicates of one another.'
        );
    }

    public function testDockerfileExposesBothPort80AndPort8080(): void
    {
        $content = file_get_contents($this->dockerfilePath);

        $this->assertMatchesRegularExpression(
            '/^EXPOSE 80$/m',
            $content,
            'Port 80 must be documented as exposed for Render/Docker runtime compatibility.'
        );
        $this->assertMatchesRegularExpression(
            '/^EXPOSE 8080$/m',
            $content,
            'Port 8080 must remain exposed, since Apache is configured to listen on the unprivileged port 8080.'
        );

        $exposeCount = preg_match_all('/^EXPOSE /m', $content);
        $this->assertSame(2, $exposeCount, 'Exactly two EXPOSE directives (80 and 8080) are expected.');
    }

    public function testContainerfileGrantsWriteAccessOnlyToDataDirectory(): void
    {
        $content = file_get_contents($this->containerfilePath);

        $this->assertMatchesRegularExpression(
            '/^RUN chown -R www-data:www-data \/var\/www\/html\/data$/m',
            $content,
            'Containerfile must mirror the Dockerfile: only the runtime data directory should be chowned to www-data.'
        );
        $this->assertDoesNotMatchRegularExpression(
            '/chown -R www-data:www-data \/var\/www\/html\s*$/m',
            $content,
            'Containerfile must not grant ownership recursively across the entire webroot.'
        );
    }

    public function testContainerfileExposesBothPort80AndPort8080(): void
    {
        $content = file_get_contents($this->containerfilePath);

        $this->assertMatchesRegularExpression('/^EXPOSE 80$/m', $content);
        $this->assertMatchesRegularExpression('/^EXPOSE 8080$/m', $content);
    }
}
