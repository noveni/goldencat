<?php
/**
 * WooCommerce Theme Product Meta Fields
 *
 * @package WooCommerce\Functions
 * @version 3.3.0
 */

defined( 'ABSPATH' ) || exit;

// Admin: Product Panel Group
if ( ! function_exists( 'goldencat_product_settings_tabs' ) ) {
	function goldencat_product_settings_tabs( $tabs ) {
		$tabs['goldencat_panel_advanced_product_tab'] = array(
			'label'    => 'More',
			'target'   => 'goldencat_panel_advanced_product_data',
			// 'class'    => array('show_if_virtual'),
			'priority' => 21,
		);
		return $tabs;
	}
}
add_filter( 'woocommerce_product_data_tabs', 'goldencat_product_settings_tabs' );


// Admin: Product Panel fields
if ( ! function_exists( 'goldencat_product_panels' ) ) {
	function goldencat_product_settings_panels() {

		echo '<div id="goldencat_panel_advanced_product_data" class="panel woocommerce_options_panel hidden">';

        woocommerce_wp_text_input( array(
            'id' => 'goldencat_advanced_event_date',
            'label' => 'Date:',
            'value' => get_post_meta( get_the_ID(), 'goldencat_advanced_event_date', true ),
            'wrapper_class' => 'form-field-wide',
			'class' => 'date-picker',
			'style' => 'width:100%',
        ) );

		echo '</div>';
	}
}


add_action( 'woocommerce_product_data_panels', 'goldencat_product_settings_panels' );



function goldencat_product_settings_save_fields( $id, $post ){
 
	//if( !empty( $_POST['super_product'] ) ) {
		update_post_meta( $id, 'goldencat_advanced_event_date', $_POST['goldencat_advanced_event_date'] );
		update_post_meta( $id, 'goldencat_product_comingsoon', $_POST['goldencat_product_comingsoon'] );
	//} else {
	//	delete_post_meta( $id, 'super_product' );
	//}
 
}

add_action( 'woocommerce_process_product_meta', 'goldencat_product_settings_save_fields', 10, 2 );


add_action( 'woocommerce_product_options_advanced', 'goldencat_product_options_advanced_custom_fields');
function goldencat_product_options_advanced_custom_fields(){
 
	echo '<div class="options_group">';
 
	woocommerce_wp_checkbox( array(
		'id'      => 'goldencat_product_comingsoon',
		'value'   => get_post_meta( get_the_ID(), 'goldencat_product_comingsoon', true ),
		'label'   => 'Coming Soon',
		'desc_tip' => true,
		'description' => 'Produit pas encore disponible, il est visible mais pas achetable.',
	) );
 
	echo '</div>';
 
}


// Helper Functions

function goldencat_wc_product_is_comingsoon( $product_id = false ) {
	
	$prod_id = $product_id ? $product_id : get_the_ID();

	$comingsoon = get_post_meta($prod_id, 'goldencat_product_comingsoon', true);

	if ($comingsoon === 'yes') {
		return true;
	}
	return false;
}
function goldencat_wc_product_advanced_date( $product_id = false ) {
	
	$prod_id = $product_id ? $product_id : get_the_ID();

	$date = get_post_meta($prod_id, 'goldencat_advanced_event_date', true);

	if ($date != false) {
		return $date;
	}
	return false;
}



add_filter('woocommerce_is_purchasable', 'goldencat_catalog_mode_on_for_product', 10, 2 );
add_filter( 'woocommerce_variation_is_purchasable', 'goldencat_catalog_mode_on_for_product', 10, 2 );
function goldencat_catalog_mode_on_for_product( $is_purchasable, $product ) {
	if ( $product->is_type('variation') ){
		$parent = wc_get_product( $product->get_parent_id() );
        $is_purchasable = !goldencat_wc_product_is_comingsoon($parent->get_id());
	} else {
		$is_purchasable = !goldencat_wc_product_is_comingsoon($product->get_id());
	}

	return $is_purchasable;
}

add_filter( 'woocommerce_post_class', 'goldencat_add_comingsoon_class', 10, 2);

function goldencat_add_comingsoon_class( $classes, $product ) {

	if( goldencat_wc_product_is_comingsoon($product->get_id())) { // you can set multiple conditions here
		$classes[] = 'comingsoon';
	}

	return $classes;
}
