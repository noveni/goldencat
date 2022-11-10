<?php
/**
 * WooCommerce Template Functions.
 *
 * @package GoldenCat
 * @version 1.0.0
 */

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



if ( ! function_exists( 'goldencat_cart_link_fragment' ) ) {
	/**
	 * Cart Fragments
	 * Ensure cart contents update when products are added to the cart via AJAX
	 *
	 * @param  array $fragments Fragments to refresh via AJAX.
	 * @return array            Fragments to refresh via AJAX
	 */
	function goldencat_cart_link_fragment( $fragments ) {
		global $woocommerce;

		ob_start();
		goldencat_cart_link();
		$fragments['a.cart-contents'] = ob_get_clean();

		ob_start();
		goldencat_handheld_footer_bar_cart_link();
		$fragments['a.footer-cart-contents'] = ob_get_clean();

		return $fragments;
	}
}

if ( ! function_exists( 'goldencat_mini_cart_fragment_title' ) ) {
	function goldencat_mini_cart_fragment_title()
	{
		?>
		<h4 class="has-text-align-center"><?php esc_html_e( 'Mon panier', 'storefront' ); ?><span class="count"><?php echo wp_kses_data( sprintf( '&nbsp;(%d)', WC()->cart->get_cart_contents_count() ) ); ?></span></h4>
		<?
	}
}

if ( ! function_exists( 'goldencat_widget_shopping_cart_subtotal' ) ) {
	/**
	 * Output to view cart subtotal.
	 *
	 * @since 3.7.0
	 */
	function goldencat_widget_shopping_cart_subtotal() {
		echo '<strong>' . esc_html__( 'Total Articles:&nbsp;', 'woocommerce' ) . WC()->cart->get_cart_subtotal() . '</strong> '; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}


if ( ! function_exists( 'goldencat_handheld_footer_bar_cart_link' ) ) {
	/**
	 * The cart callback function for the handheld footer bar
	 *
	 * @since 2.0.0
	 */
	function goldencat_handheld_footer_bar_cart_link() {
		if ( ! goldencat_woo_cart_available() ) {
			return;
		}
		?>
			<a class="footer-cart-contents" href="<?php echo esc_url( wc_get_cart_url() ); ?>"><?php esc_html_e( 'Cart', 'storefront' ); ?>
				<span class="count"><?php echo wp_kses_data( WC()->cart->get_cart_contents_count() ); ?></span>
			</a>
		<?php
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
	function goldencat_cart_link( $with_label = false ) {
		if ( ! goldencat_woo_cart_available() ) {
			return;
		}
		$account_button_label_class = $with_label === true ? 'header-button-label' : 'screen-reader-text';
		?>
			<a class="cart-contents wc-header-button" href="<?php echo esc_url( wc_get_checkout_url() ); ?>" title="<?php esc_attr_e( 'View your shopping cart', 'goldencat' ); ?>">
				<div class="svg-icon">
					<?php echo goldencat_icon( 'ui', 'cart' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					<?php /* translators: %d: number of items in cart */ ?>
					<span class="count"><?php echo wp_kses_data( WC()->cart->get_cart_contents_count() ); ?></span>
				</div>
				<span class="<?php echo $account_button_label_class; ?>"><?php _e('Panier', 'goldencat'); ?></span>
			</a>
		<?php
	}
}

if ( ! function_exists( 'goldencat_popular_products' ) ) {
	/**
	 * Display Featured Products
	 * Hooked into the `homepage` action in the homepage template
	 *
	 * @since  1.0.0
	 * @param array $args the product section args.
	 * @return void
	 */
	function goldencat_popular_products( $args = array() ) {
		$args = apply_filters(
			'goldencat_popular_products_args',
			array(
				'limit'      => 4,
				'columns'    => 4,
				'orderby'    => 'rating',
				'order'      => 'desc',
				'title'      => __( 'Vous aimerez également', 'goldencat' ),
			)
		);

		$shortcode_content = goldencat_do_shortcode(
			'products',
			apply_filters(
				'goldencat_popular_products_shortcode_args',
				array(
					'per_page'   => intval( $args['limit'] ),
					'columns'    => intval( $args['columns'] ),
					'orderby'    => esc_attr( $args['orderby'] ),
					'order'      => esc_attr( $args['order'] ),
				)
			)
		);

		/**
		 * Only display the section if the shortcode returns products
		 */
		if ( false !== strpos( $shortcode_content, 'product' ) ) {
			?>
			<div class="wp-block-group alignfull is-style-goldencat-group-horizontal-product-section has-rose-light-background-color has-background">
				<div class="wp-block-group__inner-container">
					<div class="wp-block-columns alignwide">
						<div class="wp-block-column" style="flex-basis:24%">
							<h2 class="section-title"><?php echo wp_kses_post( $args['title'] ); ?></h2>
						</div>
						<div class="wp-block-column" style="flex-basis:73%">
						<?php do_action( 'goldencat_homepage_before_popular_products' ); ?>
						<?php echo $shortcode_content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<?php do_action( 'goldencat_homepage_after_popular_products' ); ?>
						</div>
					</div>
				</div>
			</div>
			<?php
		}
	}
}


if ( ! function_exists( 'goldencat_is_carte_cadeaux' ) ) {
	/**
	 * Check if current product is Carte Cadeaux
	 * 
	 */
	function goldencat_is_carte_cadeaux() {
		global $product;
		
		$slug = $product->get_slug();

		if ($slug == 'cartes-cadeaux') {
			return true;
		}
		return false;
	}
}
