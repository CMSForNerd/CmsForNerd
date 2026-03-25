<?php
/**
 * FILE: themes/CmsForNerd/amp-sidebar.tpl
 * ROLE: Interactive Slide-out navigation for AMP
 */
$navPages = get_site_pages();
$currentFile = ($ctx->scriptName ?? 'index') . '.php';
?>

<amp-sidebar id="sidebar" layout="nodisplay" side="left">
    <div class="sidebar-header">
        <span>LABORATORY</span>
        <button on="tap:sidebar.close" role="button" tabindex="0" class="close-btn">&times;</button>
    </div>
    
    <nav class="sidebar-nav">
        <ul>
            <li>
                <div style="padding: 10px 20px; border-bottom: 1px solid var(--lab-border);">
                    <button class="btn" on="tap:AMP.setState({themeState: themeState == 'theme-dark' ? 'theme-light' : 'theme-dark'})" style="margin:0;">
                        Dimmer Switch 🌓
                    </button>
                </div>
            </li>
            <li>
                <a href="index.php?view=amp" 
                   style="<?= ($currentFile === 'index.php') ? 'background: var(--lab-border); border-left: 5px solid var(--lab-purple);' : '' ?>">
                   🏠 Home
                </a>
            </li>

            <?php foreach ($navPages as $file => $label) : 
                $isActive = ($currentFile === $file);
                $activeStyle = $isActive ? 'background: var(--lab-border); border-left: 5px solid var(--lab-purple); color: var(--lab-purple);' : '';
            ?>
                <li>
                    <a href="<?= htmlspecialchars($file) ?>?view=amp" style="<?= $activeStyle ?>">
                        <?= htmlspecialchars($label) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
</amp-sidebar>
