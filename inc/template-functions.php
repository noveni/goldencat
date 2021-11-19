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

