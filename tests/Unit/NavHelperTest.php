<?php

declare(strict_types=1);

require_once __DIR__ . '/../../includes/nav-helper.inc.php';

test('get_site_pages returns an array of strings', function () {
    $pages = get_site_pages();
    expect($pages)->toBeArray();
    foreach ($pages as $file => $label) {
        expect($file)->toBeString();
        expect($label)->toBeString();
    }
});

test('get_site_pages excludes specific files', function () {
    $pages = get_site_pages();
    $exclude = [
        'template.php',
        'index.php',
        'audit-semantics.php',
        'sitemap-page.php',
        'sitemap.php',
        'rss.php',
        'ror.php'
    ];

    foreach ($exclude as $file) {
        expect($pages)->not->toHaveKey($file);
    }
});

test('get_site_pages handles specific labels', function () {
    $pages = get_site_pages();

    if (isset($pages['ansible-lab.php'])) {
        expect($pages['ansible-lab.php'])->toBe('🤖 Ansible Lab');
    }

    if (isset($pages['pwa-architecture.php'])) {
        expect($pages['pwa-architecture.php'])->toBe('📱 PWA Architecture');
    }
});
