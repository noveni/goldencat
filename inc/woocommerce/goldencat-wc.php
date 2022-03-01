<?php
/**
 * WooCommerce Theme functions
 *
 * @package WooCommerce\Functions
 * @version 3.3.0
 */

defined( 'ABSPATH' ) || exit;




require get_template_directory() . '/inc/woocommerce/goldencat-wc-account.php';
require get_template_directory() . '/inc/woocommerce/goldencat-wc-checkout.php';
require get_template_directory() . '/inc/woocommerce/goldencat-wc-layout.php';
require get_template_directory() . '/inc/woocommerce/goldencat-wc-login.php';

require get_template_directory() . '/inc/woocommerce/goldencat-wc-product-loop.php';
require get_template_directory() . '/inc/woocommerce/goldencat-wc-product.php';
require get_template_directory() . '/inc/woocommerce/goldencat-wc-product-single.php';
require get_template_directory() . '/inc/woocommerce/goldencat-wc-product-meta-fields.php';
require get_template_directory() . '/inc/woocommerce/goldencat-wc-admin.php';

add_filter('woocommerce_cart_item_thumbnail', 'goldencat_wc_cart_item_thumbnail', 10, 3);

function goldencat_wc_cart_item_thumbnail( $thumbnail, $cart_item, $cart_item_key ) {
    return '<div class="cart-item-main-wrapper"><figure class="cart-item-thumbnail">' . $thumbnail . '</figure>';
    
}
add_filter('woocommerce_cart_item_name', 'goldencat_wc_cart_item_name', 10, 3);

function goldencat_wc_cart_item_name( $name, $cart_item, $cart_item_key ) {
    return '<h5>' . $name . '</h5></div>';
    
}
