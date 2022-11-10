<?php
/**
 * WooCommerce Theme Account
 *
 * @package GoldenCat
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;


/**
 * MyAccount
 */
// Remove Useless Link
add_filter( 'woocommerce_account_menu_items', 'goldencat_remove_my_account_links' );
if ( ! function_exists( 'goldencat_remove_my_account_links' ) ) {
	function goldencat_remove_my_account_links($menu_links) {
		unset( $menu_links['dashboard'] ); // Remove Dashboard
		//unset( $menu_links['payment-methods'] ); // Remove Payment Methods
		//unset( $menu_links['orders'] ); // Remove Orders
		// unset( $menu_links['downloads'] ); // Disable Downloads
		//unset( $menu_links['edit-account'] ); // Remove Account details tab
		// unset( $menu_links['customer-logout'] ); // Remove Logout link
		// unset( $menu_links['edit-address'] ); // Addresses
		$user = wp_get_current_user();
		if ( isset($menu_links['request-quote']) && !wc_user_has_role($user, 'customer-b2b') ) {
			unset($menu_links['request-quote']);
		}
		
		return $menu_links;
	}
}

add_filter( 'woocommerce_account_menu_items', 'goldencat_rename_my_account_links' );
if ( ! function_exists( 'goldencat_rename_my_account_links' ) ) {
	function goldencat_rename_my_account_links($menu_links) {
		$menu_links['orders'] = 'Mes commandes';
		$menu_links['edit-address'] = 'Mes coordonnées';
		$menu_links['edit-account'] = 'Détails du compte';
		$menu_links['customer-logout'] = 'Se déconnecter';
		
		return $menu_links;
	}
}

add_filter ( 'woocommerce_account_menu_items', 'goldencat_custom_links' );
function goldencat_custom_links( $menu_links ){
 
	// we will hook "ec-myaccount-faq" later
	$new = array( 'ec-myaccount-faq' => 'Foire aux questions' );
 
	// or in case you need 2 links
	// $new = array( 'link1' => 'Link 1', 'link2' => 'Link 2' );
 
	// array_slice() is good when you want to add an element between the other ones
	$position = 4; // Ex: fourth element => position = 3
	$menu_links = array_slice( $menu_links, 0, $position, true )
	+ $new 
	+ array_slice( $menu_links, $position, NULL, true );
 
	return $menu_links;

}

add_filter( 'woocommerce_get_endpoint_url', 'goldencat_hook_endpoint', 10, 4 );
function goldencat_hook_endpoint( $url, $endpoint, $value, $permalink ) {
 
	if( $endpoint === 'ec-myaccount-faq' ) {

		// ok, here is the place for your custom URL, it could be external
		$page_id   = get_page_by_path( 'faq' );
		if ($page_id) {

			$url = get_permalink( $page_id->ID );
		} else {
			$url = '#';
		}

	}
	return $url;
}

add_filter( 'woocommerce_account_menu_item_classes', 'goldencat_myaccount_change_logout_class', 10, 2 );
function goldencat_myaccount_change_logout_class($classes, $endpoint) {
	if ( 'customer-logout' === $endpoint ) {
		$classes[] = 'button-link-wrapper';
	}
	return $classes;
}

add_action('template_redirect', 'goldencat_redirect_to_orders_from_dashboard' );
function goldencat_redirect_to_orders_from_dashboard() {
	global $wp;
	if ( is_account_page()) {

		if (empty( WC()->query->get_current_endpoint()) && !array_key_exists('request-quote', $wp->query_vars) ){
			wp_safe_redirect( wc_get_account_endpoint_url( 'orders' ) );
			exit;
		}
	}
}

add_action('woocommerce_before_account_navigation', 'goldencat_add_hello_msg_on_my_account');
function goldencat_add_hello_msg_on_my_account() {
	if ( is_account_page() && is_user_logged_in() ) {
		$current_user = get_user_by( 'id', get_current_user_id() );
		$allowed_html = array(
			'a' => array(
				'href' => array(),
			),
			'br' => array(),
		);
		?>
		<div class="goldencat-myaccount-hello-msg">
			<p class="has-text-align-center">
				<?php
				printf(
					/* translators: 1: user display name 2: logout url */
					wp_kses( __( 'Bonjour %1$s,<br> (vous n\'êtes pas %1$s? <a href="%2$s">Déconnexion</a>)', 'woocommerce' ), $allowed_html ),
					'<strong>' . esc_html( $current_user->display_name ) . '</strong>',
					esc_url( wc_logout_url() )
				);
				?>
			</p>
		</div>
		<?php
	}
}


function goldencat_my_account_my_orders_columns_manage( $columns ) {

	$order_date = array('order-date' => $columns['order-date']);
	$order_status = array('order-status' => $columns['order-status']);
	unset($columns['order-date'], $columns['order-status']);
	$columns = $order_date
		+ array_slice( $columns, 0, 2, true)
		+ $order_status
		+ array_slice( $columns, 2, null, true);
 
	return $columns;
}
add_filter( 'woocommerce_my_account_my_orders_columns', 'goldencat_my_account_my_orders_columns_manage' );


function goldencat_my_account_my_orders_column_manage_order_date( $order ) {
	// sprintf(_('Commande du %s', 'goldencat'), wc_format_datetime( $order->get_date_created() ))
	?>
	<time datetime="<?php echo esc_attr( $order->get_date_created()->date( 'c' ) ); ?>"><?php echo esc_html( sprintf(_('Commande du %s', 'goldencat'), wc_format_datetime( $order->get_date_created() )) ); ?></time>
	<?php

}
add_action( 'woocommerce_my_account_my_orders_column_order-date', 'goldencat_my_account_my_orders_column_manage_order_date' );


function goldencat_my_account_my_orders_column_manage_order_number( $order ) {
	?>
	<a href="<?php echo esc_url( $order->get_view_order_url() ); ?>">
		<?php esc_html_e( sprintf('N° de commande %s', $order->get_order_number()), 'goldencat'); ?>
	</a>
	&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;
	<?php

}
add_action( 'woocommerce_my_account_my_orders_column_order-number', 'goldencat_my_account_my_orders_column_manage_order_number' );


function goldencat_my_account_my_orders_column_manage_order_total( $order ) {
	$item_count = $order->get_item_count() - $order->get_item_count_refunded();
	?>
	<span>
	<?php
	/* translators: 1: formatted order total 2: total order items */
	echo wp_kses_post( sprintf( _n( 'Total de la commande %1$s pour %2$s article', 'Total de la commande %1$s pour %2$s articles', $item_count, 'woocommerce' ), $order->get_formatted_order_total(), $item_count ) );
	?>
	</span>
	&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;
	<?php

}
add_action( 'woocommerce_my_account_my_orders_column_order-total', 'goldencat_my_account_my_orders_column_manage_order_total' );


function goldencat_my_account_my_orders_column_manage_order_actions( $order ) {
	?>
	<?php
	$actions = wc_get_account_orders_actions( $order );

	if ( ! empty( $actions ) ) {
		foreach ( $actions as $key => $action ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
			if ( 'view' === $key ) {
				echo '<a href="' . esc_url( $action['url'] ) . '" class="woocommerce-button ' . sanitize_html_class( $key ) . '" title="' . esc_html( $action['name'] ) . '"><span class="screen-reader-text">' . esc_html( $action['name'] ) . '</span>' . '</a>';
			} else {
				echo '<a href="' . esc_url( $action['url'] ) . '" class="woocommerce-button ' . sanitize_html_class( $key ) . '" title="' . esc_html( $action['name'] ) . '"><span class="screen-reader-text">' . esc_html( $action['name'] ) . '</span>' . esc_html( $action['name'] ) . '</a>';
			}
		}
	}
}
add_action( 'woocommerce_my_account_my_orders_column_order-actions', 'goldencat_my_account_my_orders_column_manage_order_actions' );


add_filter( 'the_title', function($title, $id) {
	if ( is_account_page() && !is_user_logged_in() && $title == 'Mon compte') {
		$title = 'Identification';
	}
	return $title;
	
}, 10, 2 );
