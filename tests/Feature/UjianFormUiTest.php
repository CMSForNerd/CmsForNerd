<?php

declare(strict_types=1);

use CmsForNerd\SecurityUtils;

$projectRoot = dirname(__DIR__, 2);

/**
 * Render the Turnstile/CSRF body fragment for a simulated request.
 *
 * @param array<string, mixed> $postData
 */
$renderForm = static function (
    string $requestMethod,
    array $postData = [],
    ?string $sessionToken = null
) use ($projectRoot): string {
    SecurityUtils::generateCsrfToken();
    $_SESSION = ['session_created_at' => time()];

    if ($sessionToken !== null) {
        $_SESSION['csrf_token'] = $sessionToken;
    }

    $_SERVER['REQUEST_METHOD'] = $requestMethod;
    $_POST = $postData;

    ob_start();

    try {
        require $projectRoot . '/contents/ujian-form-body.inc';
        $output = ob_get_contents();

        return is_string($output) ? $output : '';
    } finally {
        ob_end_clean();
    }
};

afterEach(function (): void {
    $_SERVER['REQUEST_METHOD'] = 'GET';
    $_POST = [];
    $_SESSION = ['session_created_at' => time()];
});

it('renders a fresh CSRF-protected form without a request status on GET', function () use ($renderForm): void {
    $output = $renderForm('GET');

    expect($output)
        ->toContain('<form method="POST">')
        ->toMatch('/name="csrf_token" value="[a-f0-9]{64}"/')
        ->not->toContain('<strong>[PASS]</strong>')
        ->not->toContain('<strong>[FAIL]</strong>');
});

it(
    'shows the success status only when the submitted CSRF token matches the session',
    function () use ($renderForm): void {
        $token = str_repeat('a', 64);
        $output = $renderForm('POST', ['csrf_token' => $token], $token);

        expect($output)
            ->toContain('<strong>[PASS]</strong>')
            ->toContain('name="csrf_token" value="' . $token . '"')
            ->not->toContain('<strong>[FAIL]</strong>');
    }
);

it(
    'fails closed for absent, mismatched, and non-string CSRF input',
    function (array $postData) use ($renderForm): void {
        $sessionToken = str_repeat('b', 64);
        $output = $renderForm('POST', $postData, $sessionToken);

        expect($output)
            ->toContain('<strong>[FAIL]</strong>')
            ->toContain('The token was invalid or missing.')
            ->not->toContain('<strong>[PASS]</strong>');
    }
)->with([
    'missing token' => [[]],
    'mismatched token' => [['csrf_token' => str_repeat('c', 64)]],
    'array-shaped token' => [['csrf_token' => ['unexpected']]],
]);

it('escapes the session token before placing it in the hidden input', function () use ($renderForm): void {
    $unsafeToken = '"><script>alert(1)</script>';
    $output = $renderForm('GET', [], $unsafeToken);

    expect($output)
        ->toContain('value="&quot;&gt;&lt;script&gt;alert(1)&lt;/script&gt;"')
        ->not->toContain($unsafeToken);
});

it('keeps the test-data field labelled and resistant to browser autofill', function () use ($renderForm): void {
    $output = $renderForm('GET');

    expect($output)
        ->toContain('<label for="test_data"')
        ->toContain('id="test_data" name="test_data"')
        ->toContain('autocomplete="off"')
        ->toContain('placeholder="Enter test data…"')
        ->toContain('data-sitekey="1x00000000000000000000AA"');
});

it('keeps the sidebar search and image dimensions accessible', function () use ($projectRoot): void {
    $sidebar = (string) file_get_contents($projectRoot . '/contents/right-side.inc');

    expect($sidebar)
        ->toContain('<label for="search-input"')
        ->toContain('id="search-input" name="q"')
        ->toContain('autocomplete="off" placeholder="Search…"')
        ->toMatch('/alt="Google"\s+width="120"\s+height="32"/')
        ->toMatch('/alt="Validated by HTML Validator \(based on Tidy\)" width="39" height="16"/');
});

it('retains the front-controller pair-logic contract for the Turnstile page', function () use ($projectRoot): void {
    $controller = (string) file_get_contents($projectRoot . '/ujian-form.php');

    expect($controller)
        ->toContain("require_once __DIR__ . '/includes/bootstrap.php';")
        ->toContain("'schemaType'  => \"WebPage\"")
        ->toContain('$ctx = createCmsContext(')
        ->toContain('pager($ctx);')
        ->toContain("header('HTTP/1.1 500 Internal Server Error');");
});

it('publishes each new web-design document exactly once in both sitemap formats', function () use ($projectRoot): void {
    $textSitemap = (string) file_get_contents($projectRoot . '/sitemap.txt');
    $xmlSitemap = (string) file_get_contents($projectRoot . '/sitemap.xml');
    $paths = [
        'docs/explanation/web-design-guidelines-skill',
        'docs/explanation/web-interface-improvements',
        'docs/skills/web-design-guidelines-skill',
        'docs/web-design-guidelines',
    ];

    foreach ($paths as $path) {
        expect(substr_count($textSitemap, $path))->toBe(1)
            ->and(substr_count($xmlSitemap, $path))->toBe(1);
    }
});
