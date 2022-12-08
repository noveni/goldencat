<?php
/**
 * WooCommerce Theme Product Single functions
 *
 * @package GoldenCat
 * @version 1.0.0
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
    unset( $tabs['additional_information'] );
    unset( $tabs['reviews'] );
    // // unset( $tabs['description'] );

    // $tabs['description'] = array(
    //     'title'    => '',
    //     'callback' => 'goldencat_product_custom_tab_content', // the function name, which is on line 15
    //     'priority' => 50,
    // );

    $product_id = get_the_ID();
    foreach(goldencat_product_custom_tab_info(  'goldencat_panel_advanced_product_tab' ) as $key_field => $field) {
        $content = get_post_meta($product_id, $key_field, true);
        if ($content !== '') {
            $tabs[$key_field] = array(
                'title'    => $field['label'],
                'callback' => 'goldencat_product_custom_tab_content', // the function name, which is on line 15
                'priority' => 50,
            );
        }
    }

    return $tabs;
}
function goldencat_product_custom_tab_content( $slug, $tab ) {
    $product_id = get_the_ID();
    foreach(goldencat_product_custom_tab_info( 'goldencat_panel_advanced_product_tab' ) as $key_field => $field) {
        if ($key_field === $slug) {
            // echo '<h2>' . $field['tab_title'] . '</h2>';
            $content = get_post_meta($product_id, $key_field, true);
            if ($content !== '' && apply_filters( 'goldencat_product_custom_tab_content_' . $slug . '_default_content', true, $product_id )) {
                echo wpautop(htmlspecialchars_decode($content));
            }
            do_action( 'goldencat_product_custom_tab_content_' . $slug . '_after', $product_id);
        }
    }

}
add_filter( 'woocommerce_product_tabs', 'goldencat_product_custom_tab' );
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

if ( ! function_exists( 'goldencat_wrap_product_image_start' ) ) {
    function goldencat_wrap_product_image_start() {
        if ( !goldencat_is_carte_cadeaux() ) {
            ?><div class="product-image-wrapper"><?php
        }
    }
}

if ( ! function_exists( 'goldencat_wrap_product_image_end' ) ) {
    function goldencat_wrap_product_image_end() {
        if ( !goldencat_is_carte_cadeaux() ) {
            ?></div><!-- .product-image-wrapper --><?php
        }
    }
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


/**
 * Change 
 */
function goldencat_wc_product_single_keep_default_variations( $do_change, $product ) {

    return $do_change;
}
add_filter( 'goldencat_wc_change_variation_to_radio', 'goldencat_wc_product_single_keep_default_variations', 10, 2);
