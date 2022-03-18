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

// add_filter('woocommerce_cart_item_thumbnail', 'goldencat_wc_cart_item_thumbnail', 10, 3);

function goldencat_wc_cart_item_thumbnail( $thumbnail, $cart_item, $cart_item_key ) {
    return '<div class="cart-item-main-wrapper"><figure class="cart-item-thumbnail">' . $thumbnail . '</figure>';
    
}
add_filter( 'woocommerce_cart_item_name', 'goldencat_wc_cart_item_name', 100, 3);

function goldencat_wc_cart_item_name( $name, $cart_item, $cart_item_key ) {
    
    if ( is_checkout() ) {

        $start_div = '<div class="cart-item-product-content">';
        $start_div_info = '<div class="cart-item-product-content-info">';
        $product = $cart_item['data'];
        $img = $product->get_image();
        return $start_div . $img . $start_div_info . '<span>' . $name . '</span>';
    }

    return $name;
    
}

function goldencat_wc_checkout_cart_item_quantity( $html, $cart_item, $cart_item_key ) {
    if ( is_checkout() ) {
        $product = $cart_item['data'];
        $qty = '<div class="product-quantity">' . __( 'Quantité', 'goldencat' ) . '&nbsp;:&nbsp;' . $cart_item['quantity'] . '</div>';
        $product_total = apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
        $product_delete = goldencat_wc_checkout_cart_remove_link( $product, $cart_item, $cart_item_key );
        $product_delete_icon = goldencat_wc_checkout_cart_remove_icon( $product, $cart_item, $cart_item_key );
        $end_div_info = "</div> <!-- .cart-item-product-content-info -->";
        $end_div = "</div> <!-- .cart-item-product-content -->";
        return $qty . $product_total . $product_delete . $product_delete_icon . $end_div_info . $end_div;
    }
    return $html;
}

add_filter( 'woocommerce_checkout_cart_item_quantity', 'goldencat_wc_checkout_cart_item_quantity', 100, 3);


function goldencat_wc_checkout_cart_remove_link( $product, $cart_item, $cart_item_key ) {
    return apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        'woocommerce_cart_item_remove_link',
        sprintf(
            '<div><a href="%s" class="remove remove_from_cart_button" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s">%s</a></div>',
            esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
            esc_attr__( 'Remove this item', 'woocommerce' ),
            esc_attr( $product->get_id() ),
            esc_attr( $cart_item_key ),
            esc_attr( $product->get_sku() ),
            esc_attr__( 'Supprimer cet article', 'woocommerce' ),
        ),
        $cart_item_key
    );
}

function goldencat_wc_checkout_cart_remove_icon( $product, $cart_item, $cart_item_key ) {
    return apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        'woocommerce_cart_item_remove_link',
        sprintf(
            '<div class="remove_link_icon"><a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">&#x2715;</a></div>',
            esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
            esc_html__( 'Remove this item', 'woocommerce' ),
            esc_attr( $product->get_id() ),
            esc_attr( $product->get_sku() )
        ),
        $cart_item_key
    );
}
