<?php
/**
 * WooCommerce Theme Product Single functions
 *
 * @package WooCommerce\Functions
 * @version 3.3.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Product Summary Box.
 *
 * @see woocommerce_template_single_title()
 * @see woocommerce_template_single_rating()
 * @see woocommerce_template_single_price()
 * @see woocommerce_template_single_excerpt()
 * @see woocommerce_template_single_meta()
 * @see woocommerce_template_single_sharing()
 */
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );

/**
 * After Single Products Summary Div.
 *
 * @see woocommerce_output_product_data_tabs()
 * @see woocommerce_upsell_display()
 * @see woocommerce_output_related_products()
 */
function goldencat_product_custom_tab( $tabs ) {
    // unset( $tabs['description'] );
    unset( $tabs['additional_information'] );
    unset( $tabs['reviews'] );

    $tabs['description'] = array(
        'title'    => '',
        'callback' => 'goldencat_product_custom_tab_content', // the function name, which is on line 15
        'priority' => 50,
    );

    return $tabs;
}
function goldencat_product_custom_tab_content( $slug, $tab ) {
    // return $tab;
	// the_content();
    // $prod_id = get_the_ID();
    // foreach(goldencat_product_custom_tab_info() as $key_field => $field) {
        // if ($key_field === $slug) {
        //     // echo '<h2>' . $field['tab_title'] . '</h2>';
        //     $content = get_post_meta($prod_id, $key_field, true);
        //     if ($content !== '') {
        //         echo '<p>' . $content . '</p>';
        //     }
        // }
    // }
    // return $slug;

}
// add_filter( 'woocommerce_product_tabs', 'goldencat_product_custom_tab' );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_output_product_data_tabs', 70 );

// remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
// remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
// remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );

/**
 * Wrap Product Summary
 */
add_action( 'woocommerce_before_single_product_summary', 'goldencat_wrap_single_product_top_start', 5 );
add_action( 'woocommerce_after_single_product_summary', 'goldencat_wrap_single_product_top_end', 5 );
add_action( 'woocommerce_before_single_product_summary', 'goldencat_wrap_product_image_start', 19 );
add_action( 'woocommerce_before_single_product_summary', 'goldencat_wrap_product_image_end', 21 );

function goldencat_wrap_single_product_top_start() {
    ?><div class="product-top-wrapper"><?php
}
function goldencat_wrap_single_product_top_end() {
    ?></div><!-- .product-top-wrapper --><?php
}

function goldencat_wrap_product_image_start() {
    ?><div class="product-image-wrapper"><?php
}

function goldencat_wrap_product_image_end() {
    ?></div><!-- .product-image-wrapper --><?php
}

/**
 * Remove quantity field
 */
// add_filter( 'woocommerce_is_sold_individually','__return_true', 10, 2 );
/**
 * Style Quantity Field
 */

/**
 * Remove Reset Button for variations
 */
add_filter('woocommerce_reset_variations_link', '__return_empty_string');


/**
 * Change the add to cart label
 */
function goldencat_wc_product_single_add_to_cart_text() {
    return 'Je commande!';
}
add_filter( 'woocommerce_product_single_add_to_cart_text', 'goldencat_wc_product_single_add_to_cart_text' );
