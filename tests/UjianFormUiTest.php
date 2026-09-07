<?php declare(strict_types=1);

namespace CmsForNerd\Tests;

use PHPUnit\Framework\TestCase;

/**
 * Validates UjianForm (Turnstile Bot Trap Verification UI) structure,
 * front controller derivation, and fragment view security controls.
 */
final class UjianFormUiTest extends TestCase
{
    private string $controllerPath;
    private string $fragmentPath;

    protected function setUp(): void
    {
        $this->controllerPath = dirname(__DIR__) . '/ujian-form.php';
        $this->fragmentPath = dirname(__DIR__) . '/contents/ujian-form-body.inc';
    }

    public function testUjianFormFilesExist(): void
    {
        $this->assertFileExists($this->controllerPath);
        $this->assertFileExists($this->fragmentPath);
    }

    public function testUjianFormControllerDerivesPageName(): void
    {
        $controllerContent = (string) file_get_contents($this->controllerPath);
        $this->assertStringContainsString('$pageName = pathinfo(basename(__FILE__), PATHINFO_FILENAME);', $controllerContent);
    }

    public function testUjianFormBodyFragmentContainsSecurityControls(): void
    {
        $fragmentContent = (string) file_get_contents($this->fragmentPath);
        $this->assertStringContainsString('Turnstile Bot-Trap Test &amp; CSRF Validation', $fragmentContent);
        $this->assertStringContainsString('<button type="submit" name="submit_btn">Test POST Security</button>', $fragmentContent);
    }

    public function testUjianFormBodyFragmentContainsCssCustomVariables(): void
    {
        $fragmentContent = (string) file_get_contents($this->fragmentPath);
        $this->assertStringContainsString('var(--lab-box-bg, #fff)', $fragmentContent);
        $this->assertStringContainsString('autocomplete="off"', $fragmentContent);
    }
}
