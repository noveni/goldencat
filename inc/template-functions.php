<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly


/**
 * Check if WooCommerce is activated
 */
if ( ! function_exists( 'goldencat_is_woocommerce_activated' ) ) {
	/**
	 * Query WooCommerce activation
	 */
	function goldencat_is_woocommerce_activated() {
		if ( class_exists( 'woocommerce' ) ) { return true; } else { return false; }
	}
}



/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 *
 * @return array
 */
function goldencat_body_classes( $classes ) {

	/** Add page slug if it doesn't exist */
    if (is_single() || is_page() && !is_front_page()) {
        if (!in_array(basename(get_permalink()), $classes)) {
            $classes[] = basename(get_permalink());
        }
    }

	// Helps detect if JS is enabled or not.
	$classes[] = 'no-js';

	// Adds `singular` to singular pages, and `hfeed` to all other pages.
	$classes[] = is_singular() ? 'singular' : 'hfeed';


	// Remove unnecessary classes
	$home_id_class = 'page-id-' . get_option('page_on_front');
	$remove_classes = [
		'page-template-default',
		'page-id-' . get_option('page_on_front')
	];
	$classes = array_diff($classes, $remove_classes);
	return $classes;
}
add_filter( 'body_class', 'goldencat_body_classes' );
