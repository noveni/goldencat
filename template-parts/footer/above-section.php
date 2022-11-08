<?php
/**
 * The above section containing a widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Golden_Cat
 */

if ( ! is_active_sidebar( 'footer-above-section' ) ) {
	return;
}
?>

<div id="footer-above-section" class="widget-area">
	<?php dynamic_sidebar( 'footer-above-section' ); ?>
</div><!-- #footer-above-section -->

