<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Golden_Cat
 */

if ( ! is_active_sidebar( 'header-notice-bar' ) ) {
	return;
}
?>

<div id="header-notice-bar" class="widget-area">
	<?php dynamic_sidebar( 'header-notice-bar' ); ?>
</div><!-- #header-notice-bar -->

