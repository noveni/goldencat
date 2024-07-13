<?php
/**
 * WooCommerce Theme Cart
 *
 * @package GoldenCat
 * @version 1.0.1
 */

defined( 'ABSPATH' ) || exit;

add_filter( 'woocommerce_cart_item_remove_link', 'goldencat_wc_mini_cart_remove_link', 10, 2);
function goldencat_wc_mini_cart_remove_link( $html, $cart_item_key ) {
    if ( ! (is_checkout() || is_cart()) ) {
        return '';
    }
    return $html;
}

add_filter( 'woocommerce_cart_item_name', 'goldencat_wc_mini_cart_item_name', 10, 3);
function goldencat_wc_mini_cart_item_name( $product_name, $cart_item, $cart_item_key ) {
    $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
    $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
    
    if ( ! (is_checkout() || is_cart()) ) {

        $thumbnail         = goldencat_wc_mini_cart_item_thumbnail( $cart_item, $cart_item_key );
        $product_permalink = $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '';

        $quantity = sprintf(
            '<span class="product-quantity"> &times; %s</span>',
            $cart_item['quantity']
        );

        if ( ! empty( $product_permalink ) ) {
            $product_name = sprintf(
                '<a href="%s">%s</a>%s',
                $product_permalink,
                $product_name,
                $quantity
            );
        }

        return sprintf(
            '<div class="mini-cart-item-wrapper">%s<div class="mini-cart-item-content"><div class="mini-cart-item-name"><span>%s</span></div>%s</div></div>',
            $thumbnail,
            $product_name,
            goldencat_wc_mini_cart_get_item_remove_link($product_id, $_product, $cart_item_key)
        );
    }
    return $product_name;
}


add_filter( 'woocommerce_cart_item_permalink', 'goldencat_wc_mini_cart_permalink', 10, 3);
function goldencat_wc_mini_cart_permalink( $product_permalink, $cart_item, $cart_item_key ) {
    if ( ! (is_checkout() || is_cart()) ) {
        return '';
    }
    return $product_permalink;
}


add_filter('woocommerce_cart_item_thumbnail', 'goldencat_wc_remove_mini_cart_item_thumbnail', 10, 3);
function goldencat_wc_remove_mini_cart_item_thumbnail( $thumbnail, $cart_item, $cart_item_key ) {
    if ( ! (is_checkout() || is_cart()) ) {
        return '';
    }
    return $thumbnail;
}
function goldencat_wc_mini_cart_item_thumbnail( $cart_item, $cart_item_key ) {
    $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
    $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
    $thumbnail  = $_product->get_image();

    if ( ! (is_checkout() || is_cart()) ) {

        $product_permalink = $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '';

        if ( ! empty( $product_permalink ) ) {
            $thumbnail = sprintf(
                '<a href="%s">%s</a>',
                $product_permalink,
                $thumbnail
            );
        }

        return '<figure class="cart-item-thumbnail">' . $thumbnail . '</figure>';
    }

    return $thumbnail; 
}


/**
 * In Widget Cart, Replace quantity x total output by the total price 
 */
add_filter( 'woocommerce_widget_cart_item_quantity', 'goldencat_wc_mini_cart_get_item_quantity', 10, 3);
function goldencat_wc_mini_cart_get_item_quantity( $html, $cart_item, $cart_item_key ) {
    $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

    if ( ! (is_checkout() || is_cart()) ) {

        $product_price     = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
        $product_price     = apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
        return $product_price;
    }

    return $html;
}



function goldencat_wc_mini_cart_get_item_remove_link( $product_id, $product, $cart_item_key) {
    return sprintf(
        '<a href="%s" class="remove remove_from_cart_button" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s">%s</a>',
        esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
        esc_attr__( 'Remove this item', 'woocommerce' ),
        esc_attr( $product_id ),
        esc_attr( $cart_item_key ),
        esc_attr( $product->get_sku() ),
        esc_attr__( 'Remove this item', 'woocommerce' ),
    );
}
