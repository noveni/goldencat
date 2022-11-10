<?php
/**
 * WooCommerce Theme Layout functions
 *
 * @package GoldenCat
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
remove_action( 'woocommerce_archive_description', 'woocommerce_product_archive_description', 10 );

add_action( 'woocommerce_before_main_content', 'goldencat_before_content', 10 );
if ( ! function_exists( 'goldencat_before_content' ) ) {
	/**
	 * Before Content
	 * Wraps all WooCommerce content in wrappers which match the theme markup
	 */
	function goldencat_before_content() {
		?>
		<div id="goldencat-wc-main" class="entry-content">
		<?php
	}
}

add_action( 'woocommerce_after_main_content', 'goldencat_after_content', 10 );
if ( ! function_exists( 'goldencat_after_content' ) ) {
	/**
	 * After Content
	 * Closes the wrapping divs
	 */
	function goldencat_after_content() {
		?>
		</div><!-- #goldencat-wc-main -->
		<?php
	}
}
