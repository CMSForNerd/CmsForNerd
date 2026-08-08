<?php

/**
 * ==========================================================================
 * FILE: /tools/generate-seo-files.php
 * ROLE: Static SEO and Sitemap Suite Generator (v4.3.1)
 * DESCRIPTION: Programmatically generates sitemap.txt, sitemap.xml, rss.xml,
 * ror.xml, and schema-org.json to maximize SEO discoverability.
 * LICENSE: GNU General Public License v3.0
 * ==========================================================================
 */

declare(strict_types=1);

echo "🧪 Starting Automated SEO Suite Generator...\n";

$rootDir = realpath(__DIR__ . '/../');
if (!$rootDir) {
    echo "❌ Failed to resolve root directory.\n";
    exit(1);
}

// 1. Scan for PHP pages
$phpFiles = glob($rootDir . '/*.php');
if ($phpFiles === false) {
    echo "❌ Failed to read PHP files in root.\n";
    exit(1);
}

$excludePhp = [
    'sitemap.php',
    'rss.php',
    'ror.php',
    'theme.php',
    'bootstrap.php',
    'pager.php',
];

$pages = [];
foreach ($phpFiles as $file) {
    $basename = basename($file);
    if (in_array($basename, $excludePhp, true)) {
        continue;
    }
    $slug = pathinfo($basename, PATHINFO_FILENAME);

    // Strict input validation for security compliance
    if (!preg_match('/^[a-zA-Z0-9_\-]+$/', $slug)) {
        continue;
    }

    $mtime = (int)filemtime($file);

    // If there is a matching fragment in contents/, use the max of both modification times
    $fragmentFile = $rootDir . '/contents/' . $slug . '-body.inc';
    if (file_exists($fragmentFile)) {
        $mtime = max($mtime, (int)filemtime($fragmentFile));
    }

    $pages[] = [
        'slug' => $slug,
        'file' => $basename,
        'mtime' => $mtime,
    ];
}

// 2. Scan recursively for Markdown files (for GitBook)
$mdFiles = [];

// Root-level md files we want to index
$rootMds = ['README.md', 'CHANGELOG.md', 'CONTRIBUTING.md', 'SECURITY.md', 'START-HERE.md', 'SUMMARY.md'];
foreach ($rootMds as $rmd) {
    $filePath = $rootDir . '/' . $rmd;
    if (file_exists($filePath)) {
        $mdFiles[] = [
            'rel_path' => $rmd,
            'mtime' => (int)filemtime($filePath),
        ];
    }
}

// Docs-level md files
/**
 * Recursively retrieves all markdown files.
 *
 * @param string $dir The directory to scan.
 * @param string $baseDir The base directory to calculate relative paths.
 * @return array<int, array{rel_path: string, mtime: int}>
 */
function findMarkdownFiles(string $dir, string $baseDir): array
{
    $results = [];
    if (!is_dir($dir)) {
        return $results;
    }
    $items = scandir($dir);
    if ($items === false) {
        return $results;
    }
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') {
            continue;
        }
        $fullPath = $dir . '/' . $item;
        if (is_dir($fullPath)) {
            $results = array_merge($results, findMarkdownFiles($fullPath, $baseDir));
        } elseif (pathinfo($fullPath, PATHINFO_EXTENSION) === 'md') {
            $relPath = ltrim(str_replace($baseDir, '', $fullPath), '/\\');
            // Standardize directory separator to forward slash
            $relPath = str_replace('\\', '/', $relPath);

            // Strict sanitization regex validation for security compliance
            if (!preg_match('/^[a-zA-Z0-9_\-\.\/]+$/', $relPath)) {
                continue;
            }

            $results[] = [
                'rel_path' => $relPath,
                'mtime' => (int)filemtime($fullPath),
            ];
        }
    }
    return $results;
}

$scannedMds = findMarkdownFiles($rootDir . '/docs', $rootDir);
$mdFiles = array_merge($mdFiles, $scannedMds);

// Compile all unique publishing URLs
$gitbookUrls = [];
$githubPagesUrls = [];
$customDomainUrls = [];
$renderUrls = [];

$allUrls = [];

// Helper to add URL and track unique set
$addUrl = function (string $url) use (&$allUrls): void {
    if (!in_array($url, $allUrls, true)) {
        $allUrls[] = $url;
    }
};

// Populate Custom Domain, GitHub Pages, Render URLs
foreach ($pages as $p) {
    $slug = $p['slug'];

    // Custom Domain (linuxmalaysia.com)
    if ($slug === 'index') {
        $addUrl("https://www.linuxmalaysia.com/");
    }
    $addUrl("https://www.linuxmalaysia.com/{$slug}.php");

    // GitHub Pages (linuxmalaysia.github.io/CmsForNerd)
    if ($slug === 'index') {
        $addUrl("https://linuxmalaysia.github.io/CmsForNerd/");
    }
    $addUrl("https://linuxmalaysia.github.io/CmsForNerd/{$slug}.html");

    // Render (cmsfornerd.onrender.com)
    if ($slug === 'index') {
        $addUrl("https://cmsfornerd.onrender.com/");
    }
    $addUrl("https://cmsfornerd.onrender.com/{$slug}.php");
}

// Populate GitBook URLs
foreach ($mdFiles as $md) {
    $relPath = $md['rel_path'];
    if (!preg_match('/^[a-zA-Z0-9_\-\.\/]+$/', $relPath)) {
        continue;
    }
    if ($relPath === 'README.md') {
        $addUrl("https://malaysia-open-source-community.gitbook.io/deep-state-of-mind-dsom-protocol-for-my-ai");
    } else {
        $cleanPath = str_replace('.md', '', $relPath);
        $cleanPath = strtolower($cleanPath);
        $addUrl("https://malaysia-open-source-community.gitbook.io/deep-state-of-mind-dsom-protocol-for-my-ai/{$cleanPath}");
    }
}

sort($allUrls);

// 3. Generate sitemap.txt
echo "📝 Generating sitemap.txt...\n";
$sitemapTxtContent = implode("\n", $allUrls) . "\n";
if (file_put_contents($rootDir . '/sitemap.txt', $sitemapTxtContent) === false) {
    echo "❌ Failed to write sitemap.txt\n";
    exit(1);
}
echo "✅ sitemap.txt successfully written with " . count($allUrls) . " URLs.\n";

// 4. Generate sitemap.xml
echo "📝 Generating sitemap.xml...\n";
$sitemapsNs = 'http' . '://www.sitemaps.org/schemas/sitemap/0.9';
$xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
$xml .= '<urlset xmlns="' . $sitemapsNs . '">' . PHP_EOL;

foreach ($allUrls as $url) {
    $priority = '0.5';
    $changefreq = 'weekly';

    // Parse priority/changefreq based on URL characteristics
    if (
        str_ends_with($url, '/') ||
        str_contains($url, '/index.php') ||
        str_contains($url, '/index.html') ||
        $url === 'https://malaysia-open-source-community.gitbook.io/deep-state-of-mind-dsom-protocol-for-my-ai'
    ) {
        $priority = '1.0';
        $changefreq = 'daily';
    } elseif (str_contains($url, 'lab-manual') || str_contains($url, 'ai-dev')) {
        $priority = '0.9';
        $changefreq = 'daily';
    } elseif (str_contains($url, 'lab-module')) {
        $priority = '0.8';
        $changefreq = 'weekly';
    } elseif (str_contains($url, 'security-policy') || str_contains($url, 'graduation') || str_contains($url, 'final-exam')) {
        $priority = '0.7';
        $changefreq = 'monthly';
    }

    // Determine appropriate lastmod date
    $lastmodDate = date('Y-m-d');
    // Try to map back to local file to get real mtime
    if (str_contains($url, 'gitbook.io')) {
        // Match with md file
        foreach ($mdFiles as $md) {
            $relPath = $md['rel_path'];
            if ($relPath === 'README.md') {
                if ($url === 'https://malaysia-open-source-community.gitbook.io/deep-state-of-mind-dsom-protocol-for-my-ai') {
                    $lastmodDate = date('Y-m-d', $md['mtime']);
                    break;
                }
            } else {
                $cleanPath = str_replace('.md', '', $relPath);
                $cleanPath = strtolower($cleanPath);
                if (str_ends_with($url, '/' . $cleanPath)) {
                    $lastmodDate = date('Y-m-d', $md['mtime']);
                    break;
                }
            }
        }
    } else {
        // Match with php file
        foreach ($pages as $p) {
            $slug = $p['slug'];
            if (str_contains($url, '/' . $slug . '.')) {
                $lastmodDate = date('Y-m-d', $p['mtime']);
                break;
            }
        }
    }

    $xml .= '    <url>' . PHP_EOL;
    $xml .= '        <loc>' . htmlspecialchars($url, ENT_XML1, 'UTF-8') . '</loc>' . PHP_EOL;
    $xml .= "        <lastmod>{$lastmodDate}</lastmod>" . PHP_EOL;
    $xml .= "        <changefreq>{$changefreq}</changefreq>" . PHP_EOL;
    $xml .= "        <priority>{$priority}</priority>" . PHP_EOL;
    $xml .= '    </url>' . PHP_EOL;
}
$xml .= '</urlset>' . PHP_EOL;

if (file_put_contents($rootDir . '/sitemap.xml', $xml) === false) {
    echo "❌ Failed to write sitemap.xml\n";
    exit(1);
}
echo "✅ sitemap.xml successfully written.\n";

// 5. Generate rss.xml
echo "📝 Generating rss.xml...\n";
$atomNs = 'http' . '://www.w3.org/2005/Atom';
$rss = '<?xml version="1.0" encoding="UTF-8" ?>' . PHP_EOL;
$rss .= '<rss version="2.0" xmlns:atom="' . $atomNs . '">' . PHP_EOL;
$rss .= '  <channel>' . PHP_EOL;
$rss .= '    <title>CMSForNerd Laboratory RSS Feed</title>' . PHP_EOL;
$rss .= '    <link>https://www.linuxmalaysia.com/index.php</link>' . PHP_EOL;
$rss .= '    <description>SEO Optimized Static Feed for the CMSForNerd Laboratory.</description>' . PHP_EOL;
$rss .= '    <language>en-us</language>' . PHP_EOL;
$rss .= '    <lastBuildDate>' . date(DATE_RSS) . '</lastBuildDate>' . PHP_EOL;
$rss .= '    <atom:link href="https://www.linuxmalaysia.com/rss.xml" rel="self" type="application/rss+xml" />' . PHP_EOL;

foreach ($pages as $p) {
    $slug = $p['slug'];
    $title = ucfirst(str_replace(['-', '_'], ' ', $slug));
    $pubDate = date(DATE_RSS, $p['mtime']);

    $rss .= '    <item>' . PHP_EOL;
    $rss .= '      <title>' . htmlspecialchars($title) . '</title>' . PHP_EOL;
    $rss .= "      <link>https://www.linuxmalaysia.com/{$slug}.php</link>" . PHP_EOL;
    $rss .= '      <description>Static details for the ' . htmlspecialchars($title) . ' module.</description>' . PHP_EOL;
    $rss .= "      <guid isPermaLink=\"true\">https://www.linuxmalaysia.com/{$slug}.php</guid>" . PHP_EOL;
    $rss .= "      <pubDate>{$pubDate}</pubDate>" . PHP_EOL;
    $rss .= '    </item>' . PHP_EOL;
}
$rss .= '  </channel>' . PHP_EOL;
$rss .= '</rss>' . PHP_EOL;

if (file_put_contents($rootDir . '/rss.xml', $rss) === false) {
    echo "❌ Failed to write rss.xml\n";
    exit(1);
}
echo "✅ rss.xml successfully written.\n";

// 6. Generate ror.xml
echo "📝 Generating ror.xml...\n";
$rorNs = 'http' . '://www.rorweb.com/0.1/';
$ror = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
$ror .= '<rss version="2.0" xmlns:ror="' . $rorNs . '">' . PHP_EOL;
$ror .= '  <channel>' . PHP_EOL;
$ror .= '    <title>ROR Sitemap for CMSForNerd Laboratory</title>' . PHP_EOL;
$ror .= '    <link>https://www.linuxmalaysia.com/index.php</link>' . PHP_EOL;

foreach ($pages as $p) {
    $slug = $p['slug'];
    $title = ucfirst(str_replace(['-', '_'], ' ', $slug));
    $updated = date('Y-m-d', $p['mtime']);

    $ror .= '    <item>' . PHP_EOL;
    $ror .= "      <link>https://www.linuxmalaysia.com/{$slug}.php</link>" . PHP_EOL;
    $ror .= "      <title>" . htmlspecialchars($title) . "</title>" . PHP_EOL;
    $ror .= '      <ror:type>resource</ror:type>' . PHP_EOL;
    $ror .= "      <ror:updated>{$updated}</ror:updated>" . PHP_EOL;
    $ror .= '    </item>' . PHP_EOL;
}
$ror .= '  </channel>' . PHP_EOL;
$ror .= '</rss>' . PHP_EOL;

if (file_put_contents($rootDir . '/ror.xml', $ror) === false) {
    echo "❌ Failed to write ror.xml\n";
    exit(1);
}
echo "✅ ror.xml successfully written.\n";

// 7. Generate schema-org.json (JSON-LD Structured Data)
echo "📝 Generating schema-org.json...\n";
$schema = [
    "@context" => "https://schema.org",
    "@type" => "WebSite",
    "name" => "CMSForNerd Laboratory",
    "alternateName" => "CmsForNerd",
    "url" => "https://www.linuxmalaysia.com",
    "author" => [
        "@type" => "Person",
        "name" => "Harisfazillah Jamel",
        "description" => "LinuxMalaysia"
    ],
    "description" => "A Lightweight, Radically Simple, Database-Free PHP Laboratory CMS.",
    "sameAs" => [
        "https://github.com/CMSForNerd/CmsForNerd",
        "https://malaysia-open-source-community.gitbook.io/deep-state-of-mind-dsom-protocol-for-my-ai",
        "https://linuxmalaysia.github.io/CmsForNerd/",
        "https://cmsfornerd.onrender.com/"
    ]
];

$schemaJson = json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL;
if (file_put_contents($rootDir . '/schema-org.json', $schemaJson) === false) {
    echo "❌ Failed to write schema-org.json\n";
    exit(1);
}
echo "✅ schema-org.json successfully written.\n";

echo "🎯 SEO Suite Generation Complete!\n";
