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
function goldencat_logo(  ) {
	return GoldenCatThemeIcons::get_svg('brand', 'logo', false);
}
