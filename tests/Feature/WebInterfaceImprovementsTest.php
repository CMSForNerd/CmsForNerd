<?php

declare(strict_types=1);

use CmsForNerd\SecurityUtils;

// Render a repository fragment with isolated request data.
$renderWebInterfaceFragment = static function (
    string $fragment,
    string $requestMethod,
    array $postData = []
): string {
    $_SERVER['REQUEST_METHOD'] = $requestMethod;
    $_POST = $postData;
    $output = '';

    ob_start();

    try {
        require dirname(__DIR__, 2) . '/contents/' . $fragment;
        $output = ob_get_contents();
    } finally {
        ob_end_clean();
    }

    return is_string($output) ? $output : '';
};

beforeEach(function (): void {
    SecurityUtils::startSecureSession();
    $_SESSION = ['session_created_at' => time()];
    $_POST = [];
    $_SERVER['REQUEST_METHOD'] = 'GET';
});

afterEach(function (): void {
    $_POST = [];
    $_SERVER['REQUEST_METHOD'] = 'GET';
});

it('renders the generated CSRF token without a submission result on GET', function () use (
    $renderWebInterfaceFragment
): void {
    $html = $renderWebInterfaceFragment('ujian-form-body.inc', 'GET');

    $matched = preg_match('/name="csrf_token" value="([a-f0-9]{64})"/', $html, $matches);

    expect($matched)->toBe(1)
        ->and($matches[1])->toBe($_SESSION['csrf_token'])
        ->and($html)->not->toContain('<strong>[PASS]</strong>')
        ->and($html)->not->toContain('<strong>[FAIL]</strong>');
});

it('accepts a POST containing the active session CSRF token', function () use ($renderWebInterfaceFragment): void {
    $token = SecurityUtils::generateCsrfToken();
    $html = $renderWebInterfaceFragment('ujian-form-body.inc', 'POST', ['csrf_token' => $token]);

    expect($html)->toContain('<strong>[PASS]</strong>')
        ->and($html)->not->toContain('<strong>[FAIL]</strong>');
});

it('rejects invalid POST CSRF payloads without a type error', function (array $postData) use (
    $renderWebInterfaceFragment
): void {
    SecurityUtils::generateCsrfToken();
    $html = $renderWebInterfaceFragment('ujian-form-body.inc', 'POST', $postData);

    expect($html)->toContain('<strong>[FAIL]</strong>')
        ->and($html)->toContain('CSRF Validation failed. The token was invalid or missing.')
        ->and($html)->not->toContain('<strong>[PASS]</strong>');
})->with([
    'missing token' => [[]],
    'empty token' => [['csrf_token' => '']],
    'mismatched token' => [['csrf_token' => str_repeat('a', 64)]],
    'null token' => [['csrf_token' => null]],
    'integer token' => [['csrf_token' => 123]],
    'array-shaped token regression' => [['csrf_token' => ['unexpected']]],
]);

it('escapes the session token before placing it in the hidden field', function () use (
    $renderWebInterfaceFragment
): void {
    $_SESSION['csrf_token'] = '"><script>alert("csrf")</script>';

    $html = $renderWebInterfaceFragment('ujian-form-body.inc', 'GET');

    expect($html)->toContain(
        'value="&quot;&gt;&lt;script&gt;alert(&quot;csrf&quot;)&lt;/script&gt;"'
    )->and($html)->not->toContain('value=""><script>');
});

it('renders labelled form controls and a configured Turnstile widget', function () use (
    $renderWebInterfaceFragment
): void {
    $html = $renderWebInterfaceFragment('ujian-form-body.inc', 'GET');

    expect($html)->toContain('label for="test_data"')
        ->and($html)->toContain('id="test_data" name="test_data"')
        ->and($html)->toContain('autocomplete="off"')
        ->and($html)->toContain('data-sitekey="1x00000000000000000000AA"');
});

it('renders an accessible search control and explicit image dimensions', function () use (
    $renderWebInterfaceFragment
): void {
    $html = $renderWebInterfaceFragment('right-side.inc', 'GET');

    expect($html)->toContain('label for="search-input"')
        ->and($html)->toContain('id="search-input" name="q"')
        ->and($html)->toContain('autocomplete="off"')
        ->and($html)->toContain('width="120"')
        ->and($html)->toContain('height="32"')
        ->and($html)->toContain('width="39" height="16"');
});
