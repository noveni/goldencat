<?php
/**
 * WooCommerce Theme Product Meta Fields
 * Managing Product Settings Tabs and Meta
 * 
 *
 * @package GoldenCat
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;


/**
 * Settings Fields Array
 * 
 * Type can be: text_area, date, checkbox, related_product
 */
function goldencat_product_custom_tab_info( $tab_id = '' )
{
	$product_meta_data = array( 
		'goldencat_product_livraison' => array(
			'label' => 'Livraison',
			'product_panel_field_description' => 'Informations complémentaires sur la livraison',
			'type' => 'text_area',
			'tab' => 'goldencat_panel_advanced_product_tab'
		),
		'goldencat_product_entretien' => array(
			'label' => 'Entretien',
			'product_panel_field_description' => 'Informations complémentaires sur les entretiens',
			'type' => 'text_area',
			'tab' => 'goldencat_panel_advanced_product_tab'
		),
		// 'goldencat_advanced_event_date' => array(
		// 	'label' => 'Date de l’évènement',
		// 	'product_panel_field_description' => 'Date de l\événement',
		// 	'type' => 'date',
		// 	'tab' => 'goldencat_panel_advanced_product_tab',
		// ),
		'goldencat_product_comingsoon' => array(
			'label' => 'Coming Soon',
			'product_panel_field_description' => 'Produit pas encore disponible, il est visible mais pas achetable.',
			'type' => 'checkbox',
			'tab' => 'advanced_product_data',
		),
		'goldencat_product_var_links' => array(
			'label' => __( 'Même produit, autre taille', 'goldencat' ),
			'product_panel_field_description' => __( 'Upsells are products which you recommend instead of the currently viewed product, for example, products that are more profitable or better quality or more expensive.', 'woocommerce' ),
			'type' => 'related_product',
			'tab' => 'linked_product_data'

		)

	);

	if ( !empty( $tab_id ) ) {
		return array_filter( $product_meta_data, function( $field ) use ( $tab_id ) {
			return $field['tab'] === $tab_id;
		});
	}

	return $product_meta_data;
}

/**
 * Add New Tabs on Product Settings
 */
if ( ! function_exists( 'goldencat_product_settings_tabs' ) ) {
	function goldencat_product_settings_tabs( $tabs ) {
		$tabs['goldencat_panel_advanced_product_tab'] = array(
			'label'    => 'Onglets',
			'target'   => 'goldencat_panel_advanced_product_data',
			// 'class'    => array('show_if_virtual'),
			'priority' => 21,
		);
		return $tabs;
	}
}
add_filter( 'woocommerce_product_data_tabs', 'goldencat_product_settings_tabs' );


/**
 * Populate Panel Custom Tab with meta fields on Product Settings 
 */
if ( ! function_exists( 'goldencat_product_settings_panels' ) ) {
	function goldencat_product_settings_panels() {

		$tab_fields = goldencat_product_custom_tab_info( 'goldencat_panel_advanced_product_tab' );

		echo '<div id="goldencat_panel_advanced_product_data" class="panel woocommerce_options_panel hidden">';

		foreach( $tab_fields as $key_field => $field) {
			goldencat_product_meta_field($key_field, $field['type'], $field['label'], $field['product_panel_field_description']);
		}

		echo '</div>';
	}
}
add_action( 'woocommerce_product_data_panels', 'goldencat_product_settings_panels' );

/**
 * Populate Panel Advanced Tabs with meta fields on Product Settings 
 */
if ( ! function_exists( 'goldencat_product_settings_options_advanced' ) ) {
	function goldencat_product_settings_options_advanced() {

		$tab_fields = goldencat_product_custom_tab_info( 'advanced_product_data' );

		echo '<div class="options_group">';

		foreach( $tab_fields as $key_field => $field) {
			goldencat_product_meta_field($key_field, $field['type'], $field['label'], $field['product_panel_field_description']);
		}

		echo '</div>';
	}
}
add_action( 'woocommerce_product_options_advanced', 'goldencat_product_settings_options_advanced');
/**
 * Populate Panel Related Tabs with meta fields on Product Settings 
 */
if ( ! function_exists( 'goldencat_product_settings_options_related' ) ) {
	function goldencat_product_settings_options_related() {

		$tab_fields = goldencat_product_custom_tab_info( 'linked_product_data' );

		// echo '<div class="options_group">';

		foreach( $tab_fields as $key_field => $field) {
			goldencat_product_meta_field($key_field, $field['type'], $field['label'], $field['product_panel_field_description']);
		}

		// echo '</div>';
	}
}
add_action( 'woocommerce_product_options_related', 'goldencat_product_settings_options_related');

/**
 * Display Meta Field on product settings panel
 */
function goldencat_product_meta_field($field_id, $type, $title, $description = '')
{
	$field = array(
		'id'          => $field_id,
		'value'       => get_post_meta( get_the_ID(), $field_id, true ),
		'label'       => $title,
	);
	if ($description && $description !== '') {
		$field['desc_tip'] = true;
		$field['description'] = $description;
	}

	// echo '<div class="options_group">';
	switch ($type) {
		case 'textarea':
		case 'text_area':
			goldencat_wc_product_meta_wysiwyg( $field_id, $field['label'], $field['value']);
			break;
		case 'checkbox':
			woocommerce_wp_checkbox($field);
			break;
		case 'date':
			goldencat_wc_product_meta_datefield( $field_id, $field['label'] . ':', $field['value']);
			break;
		case 'related_product': 
			goldencat_wc_product_meta_related_product( $field_id, $field );
			break;
	}
	// echo '</div>';
}

/**
 * Save Product Meta
 */
add_action( 'woocommerce_process_product_meta', 'goldencat_process_product_panels_meta', 10, 2 );
function goldencat_process_product_panels_meta( $id, $post )
{
	$product_meta_ids = goldencat_product_custom_tab_info();
	foreach ( $product_meta_ids as $meta_key => $field_setting ) {
		if( !empty( $_POST[$meta_key] ) ) {

			$value = $_POST[$meta_key];

			switch ($field_setting[ 'type' ]) {
				case 'textarea':
				case 'text_area':
					$value = htmlspecialchars($_POST[$meta_key]);
					break;
				case 'checkbox':
					$value = htmlspecialchars($_POST[$meta_key]);
					break;
				case 'date':
					$value = htmlspecialchars($_POST[$meta_key]);
					break;
				case 'related_product': 
					$value = array_map( function($id) {
						return htmlspecialchars($id);
					}, $_POST[$meta_key] );
					break;
				default: 
					$value = htmlspecialchars($_POST[$meta_key]);
					break;
			}
			
			update_post_meta( $id, $meta_key, $value );
		} else {
			delete_post_meta( $id, $meta_key );
		}
	}
}

function goldencat_wc_product_meta_wysiwyg( $field_id, $label, $content ) {
	$args = array(
		'media_buttons' => false,
		'quicktags' => false,
		'tinymce'       => array(
			'toolbar1'      => 'bold,italic,underline,bullist,separator,alignleft,aligncenter,alignright,separator,link,unlink,undo,redo',
			'toolbar2'      => '',
			'toolbar3'      => '',
		),
	);

	$output_field_html = '<div class="form-field ' . $field_id . '_field form-field-wide">';
	$output_field_html .= '<label>' . $label . ':</label>';
	ob_start();
	wp_editor( htmlspecialchars_decode($content), $field_id, $args );
	$output_field_html .= ob_get_clean();
	$output_field_html .= '</div>';
	echo $output_field_html;
}

function goldencat_wc_product_meta_datefield( $field_id, $label, $value ) {
	woocommerce_wp_text_input( array(
		'id' => $field_id,
		'label' => $label,
		'value' => $value,
		'wrapper_class' => 'form-field-wide',
		'class' => 'date-picker',
		'style' => 'width:100%',
	) );
}

// Display Related Product Field
function goldencat_wc_product_meta_related_product( $field_id, $field ) {
	?>
	<p class="form-field">
		<label for="goldencat_product_var_links"><?php echo esc_html( $field['label'] ); ?></label>
		<select class="wc-product-search" multiple="multiple" style="width: 50%;" id="<?php echo esc_attr( $field_id ); ?>" name="<?php echo esc_attr( $field_id ); ?>[]" data-sortable="true" data-placeholder="<?php esc_attr_e( 'Search for a product&hellip;', 'woocommerce' ); ?>" data-action="woocommerce_json_search_products_and_variations">
			<?php
			$product_ids = array();
			if ( $field[ 'value' ] != '' )  {
				$product_ids = $field[ 'value' ] ;
			}

			foreach ( $product_ids as $product_id ) {
				$product = wc_get_product( $product_id );
				if ( is_object( $product ) ) {
					echo '<option value="' . esc_attr( $product_id ) . '"' . selected( true, true, false ) . '>' . esc_html( wp_strip_all_tags( $product->get_formatted_name() ) ) . '</option>';
				}
			}
			?>
		</select> <?php 
		if ( !empty( $field['description']) ) : 
			echo wc_help_tip( $field['description'] ); // WPCS: XSS ok. 
		endif;
		?>
	</p>
	<?php
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


/**
 * Add css class 'comingsoon' on product archive post or single post
 */
add_filter( 'woocommerce_post_class', 'goldencat_add_comingsoon_class', 10, 2);
function goldencat_add_comingsoon_class( $classes, $product ) {

	if( goldencat_wc_product_is_comingsoon($product->get_id())) { // you can set multiple conditions here
		$classes[] = 'comingsoon';
	}

	return $classes;
}

/**
 * Add css class 'comingsoon' on product class
 */
if ( ! function_exists( 'goldencat_wc_product_grid_item_html_is_comingoon_class' ) ) {
	function goldencat_wc_product_grid_item_html_is_comingoon_class( $product_grid_html, $data, $product ) {

		$new_product_grid_html = $product_grid_html;
		if( goldencat_wc_product_is_comingsoon($product->get_id())) { // you can set multiple conditions here

			$new_product_grid_html = str_replace( 'class="wc-block-grid__product"', 'class="wc-block-grid__product comingsoon"', $new_product_grid_html ); 
	
		}

		return $new_product_grid_html;
	}
}
add_filter('woocommerce_blocks_product_grid_item_html', 'goldencat_wc_product_grid_item_html_is_comingoon_class', 10, 3);
