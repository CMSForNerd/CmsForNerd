<?php

/**
 * ==========================================================================
 * FILE: contents/footer.inc
 * ROLE: Global Footer Content & Navigation
 * VERSION: 3.5.1 (Added AMP Dual-View Toggle)
 * ==========================================================================
 */

declare(strict_types=1);

// Load the helper to scan for internal laboratory pages
@include_once __DIR__ . '/../includes/nav-helper.inc.php';
?>

<div class="lab-footer-wrap" style="text-align: center; padding: 40px 20px; border-top: 1px solid var(--lab-border, #e2e8f0); background-color: var(--lab-bg, #f1f5f9); color: var(--lab-text, #1e293b);"> 
    
    <nav style="margin-bottom: 25px;">
        <p style="font-family: 'SF Mono', 'Cascadia Code', 'Source Code Pro', monospace; font-size: 0.7rem; text-transform: uppercase; color: #64748b; letter-spacing: 2px; margin-bottom: 15px; opacity: 0.8;">
            <strong>&lt; Laboratory_Modules /&gt;</strong>
        </p>
        
        <div style="display: flex; justify-content: center; gap: 18px; flex-wrap: wrap; margin-bottom: 10px;">
            <a href="index.php" style="color: var(--lab-highlight, #8e44ad); text-decoration: none; font-weight: 700;">Home</a>
            
            <?php
            if (function_exists('get_site_pages')) {
                foreach (get_site_pages() as $url => $label) {
                    $displayLabel = is_array($label) ? ($label['title'] ?? $url) : $label;
                    $safeUrl = htmlspecialchars((string)$url);
                    $safeLabel = htmlspecialchars((string)$displayLabel);

                    echo "<a href=\"$safeUrl\" style=\"color: inherit; text-decoration: none; font-size: 0.9rem; border-bottom: 1px solid rgba(0,0,0,0.1); padding-bottom: 2px;\">$safeLabel</a>";
                }
            }
            ?>
        </div>
    </nav>

    <hr style="width: 60px; border: 0; border-top: 3px solid var(--lab-highlight, #8e44ad); margin: 25px auto; opacity: 0.6;">

    <?php if (isset($ctx) && $ctx instanceof \CmsForNerd\CmsContext): ?>
    <div style="margin-bottom: 20px;">
        <a href="<?= htmlspecialchars($ctx->scriptName . '.php?view=amp') ?>" 
           style="display: inline-block; padding: 5px 12px; border: 1px solid var(--lab-highlight, #8e44ad); border-radius: 4px; color: var(--lab-highlight, #8e44ad); text-decoration: none; font-size: 0.75rem; font-family: 'SF Mono', monospace; font-weight: bold;">
           ⚡ SWITCH TO AMP MOBILE VIEW
        </a>
    </div>
    <?php endif; ?>

    <div style="font-size: 0.85rem; line-height: 1.8;">
        <span style="opacity: 0.7;">CmsForNerd Infrastructure:</span> 
        <a href="https://www.linuxmalaysia.com" style="color: var(--lab-highlight, #8e44ad); text-decoration: none; font-weight: 600;">linuxmalaysia.com</a>
        • <a href="legal-notice.php" style="color: var(--lab-highlight, #8e44ad); text-decoration: none; font-weight: 600;">Legal Notice & Disclaimer</a>
        <br>
        <span style="font-family: 'SF Mono', monospace; font-size: 0.8rem;">
            Copyright &copy; 2005 - <?= date("Y") ?> Harisfazillah Jamel
        </span>
    </div>
    
    <div style="font-family: 'SF Mono', monospace; font-size: 0.65rem; color: #94a3b8; margin-top: 30px; letter-spacing: 0.5px;">
        [ REL: 3.5.1 ] | [ STD: RFC_9116 ] | [ ENV: PHP_8.4 ] | [ VIEW: STANDARD ]
        <br>
        [ REGULATION: DISCLAIMER ] | [ PURPOSE: TRAINING ] | [ RISK: ASSUMED ]
    </div>
</div>