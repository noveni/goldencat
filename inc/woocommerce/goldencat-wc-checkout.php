<?php
/**
 * WooCommerce Theme Checkout
 *
 * @package WooCommerce\Functions
 * @version 3.3.0
 */

defined( 'ABSPATH' ) || exit;



/**
 * Review
 * 
 */
// Wrap Order Review in Checkout
add_action( 'woocommerce_checkout_before_order_review_heading', 'goldencat_wrapper_checkout_order_review_before');
add_action( 'woocommerce_checkout_after_order_review', 'goldencat_wrapper_checkout_order_review_after', 10);
function goldencat_wrapper_checkout_order_review_before() {
    ?><div class="wrapper-order-review"><?php
}

function goldencat_wrapper_checkout_order_review_after() {
    ?></div><!-- .wrapper-order-review --><?php
}

// Add new title to order_review
add_action( 'woocommerce_checkout_order_review', 'goldencat_checkout_order_review_title', 5 );
function goldencat_checkout_order_review_title() {
    ?><h4><?php esc_html_e( 'Récapitulatif', 'woocommerce' ); ?></h4><?php
}

// Add Button Order
add_action( 'woocommerce_review_order_after_order_total', 'goldencat_checkout_order_review_add_order_button' );
function goldencat_checkout_order_review_add_order_button() {
    ?>
    <tr>
        <td colspan="2">
            <?php	
                goldencat_block_button("#payments-step", "Commander", '', 'order-review-custom-btn');
            ?>
        </td>
    </tr>
    <?php
}


// Add new title to Billing form
// add_action( 'woocommerce_before_checkout_registration_form', 'goldencat_checkout_billing_title' );

function goldencat_checkout_billing_title() {
    ?>
    <h4 class="goldencat-checkout-numbered-step"><?php esc_html_e( 'Je crée mon compte', 'woocommerce' ); ?></h4>
    <?php
}

/**
 * Layout of Page
 * 
 */
// Wrap Customer Billing and Order Review
add_action( 'woocommerce_checkout_before_customer_details', 'goldencat_checkout_wrapper_row_start');
add_action( 'woocommerce_checkout_after_order_review', 'goldencat_checkout_wrapper_row_end', 11);
add_action( 'woocommerce_checkout_billing', 'goldencat_checkout_billing_title' );

function goldencat_checkout_wrapper_row_start() {
    ?>
    <div class="wrapper-checkout-row">
    <?php
}

function goldencat_checkout_wrapper_row_end() {
    ?>
    </div><!-- .wrapper-checkout-row -->
    <?php
}


// Make a Step #2 Part For checkout. We Can add Delivery Step
add_action( 'woocommerce_checkout_after_order_review', 'goldencat_checkout_step_block_second', 20);
function goldencat_checkout_step_block_second() {
    ?>
    <?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
    <div class="checkout-step-second">
        <h4 id="shipping-step" class="goldencat-checkout-numbered-step"><?php esc_html_e( 'Retrait de la commande', 'woocommerce' ); ?></h4>
        <table class="goldencat-woocommerce-shipping-totals">
        <?php do_action( 'woocommerce_review_order_before_shipping' ); ?>

        <?php wc_cart_totals_shipping_html(); ?>

        <?php do_action( 'woocommerce_review_order_after_shipping' ); ?>
        </table>
        <?php
            do_action( 'goldencat_checkout_step_second' );
        ?>
    </div>
    <?php endif; ?>
    <?php
}

// Make a Step #3 Part For checkout. We Can add Payment
add_action( 'woocommerce_checkout_after_order_review', 'goldencat_checkout_step_block_third', 30);
function goldencat_checkout_step_block_third() {
    ?>
    <div class="checkout-step-third">
        <h4 class="goldencat-checkout-numbered-step" id="payments-step"><?php esc_html_e( 'Paiement', 'woocommerce' ); ?></h4>
        <?php
            do_action( 'goldencat_checkout_step_third' );
        ?>
    </div>
    <?php
}
// Remove payment position and add it to correct place
remove_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20 );
add_action( 'goldencat_checkout_step_third', 'woocommerce_checkout_payment'  );


// Add Btn After shipping address
add_action( 'woocommerce_checkout_shipping', 'goldencat_checkout_add_next_step_button', 100 );

function goldencat_checkout_add_next_step_button() {
    goldencat_block_button("#shipping-step", "Suivant", '', 'order-review-custom-btn');
}


// Remove Coupon Code before checkout
remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
// if ( ! is_ajax() ) {
add_action( 'woocommerce_review_order_after_cart_contents', function($a) {
    ?>
    <tr>
        <td colspan="2"><?php woocommerce_checkout_coupon_form(); ?></td>
    </tr>
    <?php
}, 10);
// }

add_filter('woocommerce_update_order_review_fragments', 'goldencat_order_fragments_split_shipping', 10, 1);
function goldencat_order_fragments_split_shipping($order_fragments) {

    ob_start();?>
    <table class="goldencat-woocommerce-shipping-totals">
	<?php do_action( 'woocommerce_review_order_before_shipping' ); ?>

	<?php wc_cart_totals_shipping_html(); ?>

	<?php do_action( 'woocommerce_review_order_after_shipping' ); ?>
	</table> <?php
	$goldencat_woocommerce_order_review_shipping_split = ob_get_clean();

	$order_fragments['.goldencat-woocommerce-shipping-totals'] = $goldencat_woocommerce_order_review_shipping_split;

	return $order_fragments;

}

// We'll get the template that just has the shipping options that we need for the new table
function goldencat_woocommerce_order_review_shipping_split( $deprecated = false ) {
	wc_cart_totals_shipping_html();
}

function goldencat_move_new_shipping_table() {
	ob_start();?>
    <div class="goldencat-checkout-review-shipping slide-updown show-updown"><?php
    goldencat_woocommerce_order_review_shipping_split();
    ?></div><?php
    $goldencat_woocommerce_order_review_shipping_split = ob_get_clean();
    echo $goldencat_woocommerce_order_review_shipping_split;
}


function goldencat_wc_checkout_fields_add_label_to_placeholder( $field_groups ) {
    $field_groups['billing']['billing_email']['label'] = 'Email';
    $field_groups['order']['order_comments']['placeholder'] = 'Une demande particulière pour votre commande ?';
    $field_groups['account']['account_password']['placeholder'] = 'Mot de passe*';
    foreach ($field_groups as $key_group => $group) {
        foreach ($group as $key_field => $field) {
            $label = isset($field_groups[$key_group][$key_field]['label']) ? $field_groups[$key_group][$key_field]['label'] : '';
            $field_groups[$key_group][$key_field]['label'] = "";
            $placeholder = isset($field_groups[$key_group][$key_field]['placeholder']) ? $field['placeholder'] : '';
            // if (empty($placeholder)) {
                $required = isset($field['required']) && $field['required'] == true ? '*' : '';
                $field_groups[$key_group][$key_field]['placeholder'] = $label . $required;
            // }
        }
    }

    return $field_groups;
}

function goldencat_wc_checkout_fields_remove_fields ( $field_groups ) {
    if ( !WC()->cart->needs_shipping() ) {
        // Remove Country Field
        // unset($field_groups['billing']['billing_country']);
    }

    // Remove Company
    unset($field_groups["billing"]["billing_company"]);
    unset($field_groups["shipping"]["shipping_company"]);
    unset($field_groups["billing"]["billing_address_2"]);
    unset($field_groups["shipping"]["billing_address_2"]);
    
    return $field_groups;
}

function goldencat_wc_checkout_fields_reorder( $field_groups ) {

    
    // Reorder fields
    $field_groups['billing']['billing_email']['priority'] = 4; 
    $field_groups['account']['account_password']['priority'] = 5;
    // $field_groups['account']['billing_email'] = $field_groups['billing']['billing_email'];
    // $field_groups['account']['billing_email']['priority'] = 1;
    // unset( $field_groups['billing']['billing_email'] );
    $field_groups['billing']['billing_country']['priority'] = 91;
    
    return $field_groups;
}

function goldencat_wc_checkout_fields_style( $address_fields ) {
    $address_fields["postcode"]['class'][0] = 'form-row-last';
    $address_fields["city"]['class'][0] = 'form-row-first';
    $address_fields["country"]['class'][0] = 'form-row-first';
	
    return $address_fields;
}

function goldencat_wc_checkout_fields_style_second( $field_groups ) {
    $field_groups['billing']['billing_phone']['class'][0] = 'form-row-last';
	
    return $field_groups;
}

add_filter( 'woocommerce_checkout_fields' , 'goldencat_wc_checkout_fields_add_label_to_placeholder', 9999 );
add_filter( 'woocommerce_checkout_fields' , 'goldencat_wc_checkout_fields_remove_fields', 9999 );
add_filter( 'woocommerce_checkout_fields' , 'goldencat_wc_checkout_fields_reorder', 9999 );
add_filter( 'woocommerce_default_address_fields' , 'goldencat_wc_checkout_fields_style', 9999 );
add_filter( 'woocommerce_checkout_fields' , 'goldencat_wc_checkout_fields_style_second', 9999 );
