<?php
/**
 * WooCommerce Theme functions
 *
 * @package WooCommerce\Functions
 * @version 3.3.0
 */

defined( 'ABSPATH' ) || exit;


if ( ! function_exists( 'goldencat_woo_cart_available' ) ) {
	/**
	 * Validates whether the Woo Cart instance is available in the request
	 *
	 * @since 2.6.0
	 * @return bool
	 */
	function goldencat_woo_cart_available() {
		$woo = WC();
		return $woo instanceof \WooCommerce && $woo->cart instanceof \WC_Cart;
	}
}


if ( ! function_exists( 'goldencat_cart_link' ) ) {
	/**
	 * Cart Link
	 * Displayed a link to the cart including the number of items present and the cart total
	 *
	 * @return void
	 * @since  1.0.0
	 */
	function goldencat_cart_link() {
		if ( ! goldencat_woo_cart_available() ) {
			return;
		}
		?>
			<a class="cart-contents wc-header-button" href="<?php echo esc_url( wc_get_checkout_url() ); ?>" title="<?php esc_attr_e( 'View your shopping cart', 'goldencat' ); ?>">
				<?php echo goldencat_icon( 'ui', 'cart' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<?php /* translators: %d: number of items in cart */ ?>
				<span class="count"><?php echo wp_kses_data( WC()->cart->get_cart_contents_count() ); ?></span>
				<span class="screen-reader-text"><?php _e('Panier', 'goldencat'); ?></span>
			</a>
		<?php
	}
}
