<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

function goldencat_icon( $group, $icon, $size = 24) {
	return GoldenCatThemeIcons::get_svg( $group, $icon, $size );
}

/**
 * Display Logo in place of a menu item if it's a logo
 */
function goldencat_logo() {
	return GoldenCatThemeIcons::get_svg('brand', 'logo', false);
}

/**
 * Get Image Placeholder for image replacement
 */
function goldencat_get_image_placeholder( ) {
	$image_placeholder_default_size = [
		'width' => 600,
		'height' => 600,
	];

	$style = '';

	$url = get_template_directory_uri() . '/assets/img/placeholder-color.png';

	$className = 'attachment-post-thumbnail size-post-thumbnail wp-post-image';

	$image = sprintf('<img src="%1$s" class="%2$s" alt="" %3$s>', $url, $className, $style );

	return $image;
}
