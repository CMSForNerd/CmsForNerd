<?php

declare(strict_types=1);

namespace CmsForNerd\Tests;

/**
 * Validates UjianForm (Turnstile Bot Trap Verification UI) logic,
 * front controller derivation, and fragment view loading.
 */

test('ujian form controller derives page name and loads body fragment correctly', function (): void {
    $projectRoot = dirname(__DIR__);
    $controllerFile = $projectRoot . '/ujian-form.php';
    $fragmentFile = $projectRoot . '/contents/ujian-form-body.inc';

    expect(file_exists($controllerFile))->toBeTrue();
    expect(file_exists($fragmentFile))->toBeTrue();

    $controllerContent = file_get_contents($controllerFile);
    $fragmentContent = file_get_contents($fragmentFile);

    expect($controllerContent)->toContain('$pageName = pathinfo(basename(__FILE__), PATHINFO_FILENAME);');
    expect($fragmentContent)->toContain('Turnstile Bot-Trap Test &amp; CSRF Validation');
    expect($fragmentContent)->toContain('<button type="submit" name="submit_btn">Test POST Security</button>');
});

test('ujian form route renders correctly under normal execution', function (): void {
    $projectRoot = dirname(__DIR__);
    $_SERVER['HTTP_HOST'] = 'localhost';
    $_SERVER['REQUEST_URI'] = '/ujian-form.php';
    $_SERVER['SCRIPT_NAME'] = '/ujian-form.php';
    $_SERVER['QUERY_STRING'] = '';
    $_SERVER['REQUEST_METHOD'] = 'GET';

    ob_start();
    include $projectRoot . '/ujian-form.php';
    $output = (string) ob_get_clean();

    expect($output)->toBeString();
    expect($output)->toContain('Turnstile Bot-Trap Test');
});

test('ujian form route handles amp view query parameter correctly', function (): void {
    $projectRoot = dirname(__DIR__);
    $_SERVER['HTTP_HOST'] = 'localhost';
    $_SERVER['REQUEST_URI'] = '/ujian-form.php?view=amp';
    $_SERVER['SCRIPT_NAME'] = '/ujian-form.php';
    $_SERVER['QUERY_STRING'] = 'view=amp';
    $_SERVER['REQUEST_METHOD'] = 'GET';

    ob_start();
    include $projectRoot . '/ujian-form.php';
    $output = (string) ob_get_clean();

    expect($output)->toBeString();
    expect($output)->toContain('Turnstile Bot-Trap Test');
});
