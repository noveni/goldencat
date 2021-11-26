<?php
/**
 * WooCommerce Theme Login
 *
 * @package WooCommerce\Functions
 * @version 3.3.0
 */

defined( 'ABSPATH' ) || exit;




function goldencat_wrap_customer_login_form_start() {
	?>
	<div class="goldencat-login-wrapper alignwide">
	<?php
}
add_action( 'woocommerce_before_customer_login_form', 'goldencat_wrap_customer_login_form_start' );


function goldencat_wrap_customer_login_form_end() {
	?>
	</div> <!-- .woocommerce-login-wrapper -->
	<?php
}
add_action( 'woocommerce_after_customer_login_form', 'goldencat_wrap_customer_login_form_end' );
