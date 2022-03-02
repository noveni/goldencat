<?php
/**
 * The credits section on bottom containing a widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Golden_Cat
 */


if ( ! has_nav_menu( 'footer-legals' ) ) {
	return;
}
?>
<div class="footer-legals-links">
    <p>
        <?php
        $menu_args = array(
                'theme_location' => 'footer-legals',
                'items_wrap'     => '%3$s',
                'container'      => false,
                'echo' 			 => false,
                'depth'          => 0,
                'before'    => '<span>',
                'after'     => "</span>",
                'fallback_cb'    => false,
        );
        echo strip_tags(wp_nav_menu( $menu_args ), '<span><a>' );
        ?>
    </p>
</div>
