<?php
/**
 * WooCommerce Theme Product functions
 *
 * @package GoldenCat
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;


/**
 * 
 * 
 * Template Product Stuff
 * 
 * 
 */
function goldencat_wc_product_related_products_heading() {
	return __( 'Vous aimeriez aussi', 'goldencat' );
}

add_filter( 'woocommerce_product_related_products_heading', 'goldencat_wc_product_related_products_heading' );


function goldencat_change_on_sale_badge( $badge_html, $post ) {
	echo '<span class="onsale round-badge">' . esc_html__( 'Sale!', 'woocommerce' ) . '</span>';
}
add_filter( 'woocommerce_sale_flash', 'goldencat_change_on_sale_badge', 10, 2 );

remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );
add_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 20 );


add_filter('woocommerce_is_purchasable', 'goldencat_wc_product_purchasable', 10, 2);
function goldencat_wc_product_purchasable($is_purchasable, $product) {
	// if (has_term( array( 'menu-patisseries', 'patisseries-sur-commande' ), 'product_cat', $product->get_ID() )) {
	// 	return false;
	// }

	return $is_purchasable;
}

