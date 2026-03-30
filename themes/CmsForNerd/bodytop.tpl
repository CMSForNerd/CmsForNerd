<!-- 
==========================================================================
FILE: themes/CmsForNerd/bodytop.tpl
ROLE: Layout Opener (The "Upper Bun")
==========================================================================

EDUCATIONAL NOTE:
This file is an HTML fragment. It contains "unclosed" <div> tags.
This is intentional in procedural PHP templating. 
We open the structural containers here, inject content in the middle,
and close them in 'bodyfooter.tpl'.
-->

<!-- 
[CONTAINER START] 
This div wraps the entire 3-column grid defined in style.css 
-->
<div id="container">

    <!-- 
    [GRID AREA: HEADER] 
    Loads the banner component.
    -->
    <div id="top">
        <?php include "themes/{$ctx->themeName}/header.tpl"; ?>
    </div>

    <!-- 
    [GRID AREA: LEFT NAV] 
    Dynamic include checking. We check if the file exists before including
    to prevent fatal PHP errors if a module is missing.
    -->
    <div id="left">
        <?php 
        if(file_exists("contents/left-side.inc")) {
            include("contents/left-side.inc"); 
        } else {
            // Fallback for empty labs
            echo "<h3>Menu</h3><p><em>Nav module not loaded.</em></p>";
        }
        ?>
    </div>

    <!-- 
    [GRID AREA: RIGHT NAV] 
    Usually reserves for widgets, stats, or ads.
    -->
    <div id="right">
        <?php 
        if(file_exists("contents/right-side.inc")) {
            include("contents/right-side.inc"); 
        } else {
             echo "<h3>Info</h3><p><em>Widget module not loaded.</em></p>";
        }
        ?>
    </div>

    <!-- 
    [GRID AREA: MAIN CONTENT - START] 
    IMPORTANT: We open these divs but DO NOT close them here.
    The 'pagecontent()' function will output data immediately after this line.
    -->
    <div id="content">
        <main class="content-body">
