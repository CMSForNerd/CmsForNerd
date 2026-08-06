<?php

/**
 * CMSForNerd Semantic Auditor
 * Verifies Triple-Layer Metadata Compliance (Microdata, JSON-LD, RDF)
 * Usage: php tools/audit-semantics.php [URL]
 * Example: php tools/audit-semantics.php http://cmsfornerd.test
 */

declare(strict_types=1);

// Determine the URL to audit - Defaulting to our Lab Port 8000
$url = $argv[1] ?? 'http://localhost:8000';

echo "🔍 CMSForNerd Semantic Auditor v4.3.0\n";
echo str_repeat("=", 60) . "\n";
echo "Auditing: {$url}\n";
echo str_repeat("-", 60) . "\n\n";

// Fetch the HTML content
$html = @file_get_contents($url);

if ($html === false) {
    echo "❌ ERROR: Could not reach the site.\n";
    echo "   Common fixes:\n";
    echo "   - Ensure your PHP dev server is running: php -S localhost:8000\n";
    echo "   - Check firewall settings\n";
    exit(1);
}

// Define the compliance checks
$results = [
    'Microdata (HTML tag)' => [
        'check' => str_contains($html, 'itemscope') && str_contains($html, 'itemtype'),
        'fix' => 'Ensure pageheader() in includes/common.inc.php outputs itemscope/itemtype'
    ],
    'JSON-LD Block' => [
        'check' => str_contains($html, 'application/ld+json'),
        'fix' => 'Add JSON-LD script tag in contents/common-headertag.inc'
    ],
    'RDF Metadata Link' => [
        'check' => str_contains($html, 'rel="meta"') && str_contains($html, 'labels.rdf'),
        'fix' => 'Create labels.rdf and link it in common-headertag.inc'
    ],
    'Security.txt (RFC 9116)' => [
        'check' => str_contains($html, 'Contact:') || str_contains($html, '.well-known/security.txt'),
        'fix' => 'Create .well-known/security.txt with security contact'
    ],
    'CSP Nonce' => [
        'check' => str_contains($html, 'nonce-'),
        'fix' => 'Ensure CSP header includes nonce and scripts have nonce attribute'
    ],
];

// Run the audit
$passed = 0;
$failed = 0;

foreach ($results as $check => $data) {
    if ($data['check']) {
        echo "✅ PASSED: {$check}\n";
        $passed++;
    } else {
        echo "❌ FAILED: {$check}\n";
        echo "   Fix: {$data['fix']}\n";
        $failed++;
    }
}

// Calculate compliance score
$total = $passed + $failed;
$percentage = round(($passed / $total) * 100);

echo "\n" . str_repeat("-", 60) . "\n";
echo "📊 Compliance Score: {$passed}/{$total} ({$percentage}%)\n";
echo str_repeat("=", 60) . "\n\n";

// Final verdict
if ($failed === 0) {
    echo "🎯 RESULT: 100% Nerd Compliant!\n";
    echo "   Your site is fully AI-Ready and SEO-optimized.\n";
    echo "   Triple Threat Semantic Stack: ✅ COMPLETE\n\n";
    exit(0);
} else {
    echo "⚠️  RESULT: Compliance issues detected ({$failed} failures)\n";
    echo "   Review the failures above and apply the suggested fixes.\n";
    echo "   This page MUST NOT be deployed until all checks pass.\n\n";
    exit(1);
}
