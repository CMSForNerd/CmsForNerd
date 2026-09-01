<!-- 
==========================================================================
FILE: themes/CmsForNerd/header.tpl
ROLE: Visual Header Component
==========================================================================
-->
<table class="header-table">
  <tr> 
    <td style="text-align: left;">
        <!-- 
        BRANDING:
        Using text-based branding instead of images reduces HTTP requests
        and improves load speed—a core tenet of "Zero-Debt" engineering.
        -->
        <div style="font-family: 'SF Mono', monospace; font-weight: 900; font-size: 24px;">
            CMSForNerd <span style="color: var(--lab-highlight)">::</span> LAB
        </div>
        <div style="font-size: 12px; opacity: 0.8; letter-spacing: 1px;">
            PHP 8.4 LABORATORY // STRICT MODE
        </div>
    </td>
    <td style="text-align: right; vertical-align: middle;">
        <div class="theme-switcher-container" style="display: inline-flex; align-items: center; gap: 8px; font-family: 'SF Mono', monospace; font-size: 11px;">
            <span style="opacity: 0.7; font-weight: bold; text-transform: uppercase; color: var(--lab-text);">MODE:</span>
            <div class="theme-btn-group" style="display: inline-flex; border: 1px solid var(--lab-border); border-radius: 6px; overflow: hidden; background: var(--lab-bg);">
                <button id="theme-btn-light" aria-label="Switch to light theme" style="background: none; border: none; color: var(--lab-text); min-height: 44px; min-width: 44px; touch-action: manipulation; padding: 5px 10px; cursor: pointer; font-family: inherit; font-size: inherit; font-weight: bold; transition: background 0.2s;">☀️ LIGHT</button>
                <button id="theme-btn-dark" aria-label="Switch to dark theme" style="background: none; border: none; color: var(--lab-text); min-height: 44px; min-width: 44px; touch-action: manipulation; padding: 5px 10px; cursor: pointer; font-family: inherit; font-size: inherit; font-weight: bold; border-left: 1px solid var(--lab-border); transition: background 0.2s;">🌙 DARK</button>
                <button id="theme-btn-auto" aria-label="Switch to automatic system theme" style="background: none; border: none; color: var(--lab-text); min-height: 44px; min-width: 44px; touch-action: manipulation; padding: 5px 10px; cursor: pointer; font-family: inherit; font-size: inherit; font-weight: bold; border-left: 1px solid var(--lab-border); transition: background 0.2s;">💻 AUTO</button>
            </div>
        </div>

        <script nonce="<?= htmlspecialchars($ctx->cspNonce ?? '', ENT_QUOTES, 'UTF-8') ?>">
        function setLaboratoryTheme(theme) {
            document.documentElement.classList.remove('theme-light', 'theme-dark');

            if (theme === 'dark') {
                document.documentElement.classList.add('theme-dark');
                localStorage.setItem('theme', 'dark');
            } else if (theme === 'light') {
                document.documentElement.classList.add('theme-light');
                localStorage.setItem('theme', 'light');
            } else {
                localStorage.removeItem('theme');
            }

            updateActiveThemeButtons(theme);
        }

        function updateActiveThemeButtons(theme) {
            if (!theme) {
                theme = localStorage.getItem('theme') || 'auto';
            }

            const btnLight = document.getElementById('theme-btn-light');
            const btnDark = document.getElementById('theme-btn-dark');
            const btnAuto = document.getElementById('theme-btn-auto');

            if (!btnLight || !btnDark || !btnAuto) return;

            [btnLight, btnDark, btnAuto].forEach(btn => {
                btn.style.background = 'none';
                btn.style.color = 'var(--lab-text)';
                btn.style.opacity = '0.6';
            });

            let activeBtn;
            if (theme === 'dark') {
                activeBtn = btnDark;
            } else if (theme === 'light') {
                activeBtn = btnLight;
            } else {
                activeBtn = btnAuto;
            }

            if (activeBtn) {
                activeBtn.style.background = 'var(--lab-purple)';
                activeBtn.style.color = '#ffffff';
                activeBtn.style.opacity = '1';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const btnLight = document.getElementById('theme-btn-light');
            const btnDark = document.getElementById('theme-btn-dark');
            const btnAuto = document.getElementById('theme-btn-auto');

            if (btnLight) {
                btnLight.addEventListener('click', function() { setLaboratoryTheme('light'); });
            }
            if (btnDark) {
                btnDark.addEventListener('click', function() { setLaboratoryTheme('dark'); });
            }
            if (btnAuto) {
                btnAuto.addEventListener('click', function() { setLaboratoryTheme('auto'); });
            }

            updateActiveThemeButtons();
        });
        </script>
    </td>
  </tr>
</table>
