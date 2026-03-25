<!-- 
==========================================================================
FILE: themes/CmsForNerd/bodyfooter.tpl
ROLE: Layout Closer (The "Lower Bun")
==========================================================================

EDUCATIONAL NOTE:
This file closes the tags opened in 'bodytop.tpl'. 
If you miss a closing </div> here, the entire layout grid will break.
-->

        </main> <!-- [CLOSE] .content-body -->
    </div> <!-- [CLOSE] #content (End of Main Grid Area) -->

    <!-- 
    [GRID AREA: FOOTER] 
    -->
    <div id="footer">
        <!-- Include global footer links (e.g., Privacy, Terms) -->
        <?php include "contents/footer.inc"; ?>
        
        <div class="sitemap-link" style="margin-top:10px;">
            <a href="sitemap.php" target="_blank">[XML Sitemap]</a>
        </div>
        
        <!-- 
        [PERFORMANCE METRICS] 
        Visible metrics help developers optimize code.
        memory_get_usage() shows real-time RAM footprint of this request.
        -->
        <div style="margin-top: 5px; font-size: 0.7em; opacity: 0.5;">
            Rendered: <?= date('Y-m-d H:i:s') ?> | 
            MEM: <?= round(memory_get_usage()/1024, 2) ?> KB
        </div>
    </div>

</div> <!-- [CLOSE] #container (End of CSS Grid) -->

<!-- 
[PWA FOUNDATION] 
Registers the Service Worker to enable offline capability and caching.
-->
<script nonce="<?= htmlspecialchars($ctx->cspNonce ?? '', ENT_QUOTES, 'UTF-8') ?>">
if ('serviceWorker' in navigator) {
    window.addEventListener('load', function() {
        navigator.serviceWorker.register('/sw.js').then(function(registration) {
            console.log('[PWA] ServiceWorker registration successful with scope: ', registration.scope);
        }, function(err) {
            console.log('[PWA] ServiceWorker registration failed: ', err);
        });
    });
}
</script>

<!-- 
[PWA HYDRATION ROUTER] 
Overrides clicks to dynamically fetch layout fragments without refreshing.
-->
<script src="/assets/pwa/router.js" defer></script>
