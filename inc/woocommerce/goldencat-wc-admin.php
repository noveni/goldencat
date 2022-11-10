<?php
/**
 * WooCommerce Theme Admin
 *
 * @package GoldenCat
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Create the section beneath the products tab
 **/
add_filter( 'woocommerce_get_sections_products', 'goldencat_wc_product_add_section' );
function goldencat_wc_product_add_section( $sections ) {
	
	$sections['goldencat_wc_product'] = __( 'Restrictions', 'goldencat' );
	return $sections;
	
}


/**
 * Add settings to the specific section we created before
 */
add_filter( 'woocommerce_get_settings_products', 'goldencat_wc_product_all_settings', 10, 2 );
function goldencat_wc_product_all_settings( $settings, $current_section ) {
	/**
	 * Check the current section is what we want
	 **/
	if ( $current_section == 'goldencat_wc_product' ) {
		$settings_goldencat_wc_product = array();
		// Add Title to the Settings
		$settings_goldencat_wc_product[] = array( 
            'name' => __( 'Restrictions Settings', 'goldencat' ), 
            'type' => 'title', 
            'desc' => __( 'Les options suivantes sont utilisées pour configurer les restrictions', 'goldencat' ), 
            'id' => 'goldencat_wc_product'
        );
		// Add first checkbox option
		$settings_goldencat_wc_product[] = array(
			'title'     => __( 'Désactiver les ventes', 'goldencat' ),
			'desc' => __( 'Désactiver la vente des produits de la catégorie Traiteur ("patisseries-sur-commande")', 'goldencat' ),
			'id'       => 'goldencat_wc_product_disable_purchase_of_traiteur',
            'default'   => 'no',
			'type'     => 'checkbox',
		);
		
		$settings_goldencat_wc_product[] = array( 'type' => 'sectionend', 'id' => 'goldencat_wc_product' );
		return $settings_goldencat_wc_product;
	
	/**
	 * If not, return the standard settings
	 **/
	} else {
		return $settings;
	}
}

/**
 * @snippet       Shipping Class ID Column
 * @author        Misha Rudrastyh
 * @url           https://rudrastyh.com/woocommerce/how-to-get-shipping-class-id.html
 */
add_filter( 'woocommerce_shipping_classes_columns', 'goldencat_add_shipping_class_column' );

function goldencat_add_shipping_class_column( $shipping_class_columns ) {

	$shipping_class_columns = array_slice( $shipping_class_columns, 0, 2 ) + array( 'id' => 'ID' ) + array_slice( $shipping_class_columns, 2, 3 );
	return $shipping_class_columns;

}

// woocommerce_shipping_classes_column_{COLUMN ID}
add_action( 'woocommerce_shipping_classes_column_id', 'goldencat_populate_shipping_class_column' );

function goldencat_populate_shipping_class_column() {

	echo '{{ data.term_id }}';

}
