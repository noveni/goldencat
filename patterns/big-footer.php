<?php
/**
 * Title: Big Footer
 * slug: goldencat/big-footer
 * Categories: featured
 * Keywords: footer big
 * Block Types: core/group
 */
?>

<!-- wp:group {"layout":{"type":"constrained"}} -->
<div class="wp-block-group">
    <!-- wp:columns {"verticalAlignment":"center","align":"wide"} -->
    <div class="wp-block-columns alignwide are-vertically-aligned-center">
        <!-- wp:column {"verticalAlignment":"center"} -->
        <div class="wp-block-column is-vertically-aligned-center">
            <!-- wp:heading {"level":5} -->
            <h5>Info</h5>
            <!-- /wp:heading -->

            <!-- wp:legacy-widget {"idBase":"nav_menu"} /-->
        </div>
        <!-- /wp:column -->

        <!-- wp:column -->
        <div class="wp-block-column">
            <!-- wp:heading {"level":5} -->
            <h5>Styles</h5>
            <!-- /wp:heading -->

            <!-- wp:legacy-widget {"idBase":"nav_menu"} /-->
        </div>
        <!-- /wp:column -->

        <!-- wp:column -->
        <div class="wp-block-column">
            <!-- wp:paragraph {"align":"center"} -->
            <p class="has-text-align-center">Donec iaculis mi vehicula tellus</p>
            <!-- /wp:paragraph -->

            <!-- wp:paragraph -->
            <p><a href="mailto:hello@ecrannoir.be">hello@ecrannoir.be</a></p>
            <!-- /wp:paragraph -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column -->
        <div class="wp-block-column">
            <!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
            <div class="wp-block-buttons">
                <!-- wp:button -->
                <div class="wp-block-button"><a class="wp-block-button__link wp-element-button">Contact</a></div>
                <!-- /wp:button -->
            </div>
            <!-- /wp:buttons -->

            <!-- wp:social-links {"iconColor":"black","iconColorValue":"#000000","openInNewTab":true,"size":"has-small-icon-size","className":"is-style-logos-only","layout":{"type":"flex","justifyContent":"left","flexWrap":"nowrap"}} -->
            <ul class="wp-block-social-links has-small-icon-size has-icon-color is-style-logos-only">
                <!-- wp:social-link {"url":"https://www.facebook.com/ecrannoirbe","service":"facebook"} /-->
                <!-- wp:social-link {"url":"https://instagram.com/ecrannoir.be","service":"instagram"} /-->
            </ul>
            <!-- /wp:social-links -->
        </div>
        <!-- /wp:column -->
    </div>
    <!-- /wp:columns -->
</div>
<!-- /wp:group -->
