<!-- [SEO] Character encoding. 'utf-8' is the modern standard for supporting all languages. -->
<meta charset="utf-8">

<!-- [MOBILE] Viewport - Makes your site "Responsive" so it looks good on phones and tablets. -->
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- [SEO] Descriptions used by Google. htmlspecialchars() prevents XSS attacks if text contains code. -->
<meta name="description" content="<?= htmlspecialchars($ctx->content['description'] ?? 'CMSForNerd - A lightweight, flat-file CMS') ?>">

<!-- [SEO] Keywords for older search engines (though less important now, still good practice). -->
<meta name="keywords" content="<?= htmlspecialchars($ctx->content['keywords'] ?? 'CMS, PHP, Flat-file') ?>">

<!-- [SEO] Author and Robot instructions. 'index, follow' tells Google to list this page and follow its links. -->
<meta name="author" content="<?= htmlspecialchars($ctx->content['author'] ?? 'CMSForNerd') ?>">
<meta name="robots" content="index, follow">

<!-- Favicon - The small icon you see in the browser tab. -->
<link rel="icon" type="image/x-icon" href="/favicon.ico">

<!-- [SEMANTIC WEB] RDF Metadata - Machine-readable site classification for AI and search engines. -->
<link rel="meta" type="application/rdf+xml" title="Site Metadata" href="/labels.rdf">

<!-- [AI/SEO] Schema.org Image for Rich Cards/Previews -->
<meta itemprop="image" content="<?= $ctx->baseUrl ?>images/cmsfornerd-logo.png">
<meta itemprop="description" content="<?= htmlspecialchars($ctx->content['description'] ?? 'A modern PHP 8.4+ educational CMS environment (v3.5).') ?>">

<?php
// [v3.6] Automated Semantic Detection logic
$isLab = (strpos($ctx->content['title'] ?? '', 'Lab') !== false || strpos($ctx->content['title'] ?? '', 'Module') !== false);
// Scan the associated body fragment for code signatures (PHP or <code> tags)
$bodyPath = __DIR__ . '/../contents/' . $ctx->scriptName . '-body.inc';
$hasCode = false;
if (file_exists($bodyPath)) {
    $bodyContent = file_get_contents($bodyPath);
    $hasCode = (strpos($bodyContent, '<?php') !== false || strpos($bodyContent, '<code>') !== false);
}

$schemaType = 'WebPage';
if ($isLab) {
    $schemaType = 'Course';
} elseif ($hasCode) {
    $schemaType = 'TechArticle';
}
?>

<!-- [AI/SEO] JSON-LD Structured Data - 2025 gold standard for Google, Gemini, and ChatGPT. -->
<script type="application/ld+json" nonce="<?= $ctx->cspNonce ?>">
{
  "@context": "https://schema.org",
  "@type": "<?= $schemaType ?>",
  "name": "<?= htmlspecialchars($ctx->content['title'] ?? 'CMSForNerd Laboratory') ?>",
  "description": "<?= htmlspecialchars($ctx->content['description'] ?? 'A modern PHP 8.4+ educational CMS environment.') ?>",
  "url": "<?= htmlspecialchars($ctx->baseUrl . ($ctx->scriptName . '.php')) ?>",
  <?php if ($schemaType === 'TechArticle'): ?>
  "dependencies": "PHP 8.4, PSR-12, Composer",
  "proficiencyLevel": "Intermediate",
  <?php endif; ?>
  "provider": {
    "@type": "Organization",
    "name": "CMSForNerd Academy",
    "url": "https://www.linuxmalaysia.com",
    "sameAs": "https://github.com/CMSForNerd/CmsForNerd"
  },
  "educationalLevel": "Beginner to Intermediate",
  "teaches": ["PHP 8.4+", "PSR-12 Standards", "Security Engineering", "AI-Assisted Development"],
  "competencyRequired": ["PHP 8.4", "VS Code", "Git", "Composer"],
  "interactivityType": "active",
  "isAccessibleForFree": true,
  "inLanguage": "en",
  "license": "https://www.gnu.org/licenses/gpl-3.0.html"
}
</script>

<!-- [UX/SEO] Modern Typography from Google Fonts. 'preconnect' speeds up loading. -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

<!-- [SECURITY] Content Security Policy (CSP) with Nonce.
     Instead of 'unsafe-inline' (dangerous), we use a cryptographically random nonce that changes every page load.
     Only scripts with this exact nonce are allowed to execute, preventing XSS attacks. -->
<meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self' 'nonce-<?= $ctx->cspNonce ?>' https://challenges.cloudflare.com; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; img-src 'self' data: https:; font-src 'self' https://fonts.gstatic.com data:; connect-src 'self' https://*.ipinfo.io;">

<!-- RSS Feed & Sitemap Discovery -->
<link rel="alternate" type="application/rss+xml" title="CmsForNerd SiteMap Feed" href="<?= $ctx->baseUrl ?>rss.php" />
<link rel="alternate" type="application/rss+xml" title="CmsForNerd ROR Sitemap" href="<?= $ctx->baseUrl ?>ror.php" />
<meta http-equiv="Content-Language" content="EN" />
