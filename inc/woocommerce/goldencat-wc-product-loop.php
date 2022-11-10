<?php
/**
 * WooCommerce Theme Product Loop functions
 *
 * @package GoldenCat
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Products Loop.
 *
 * @see woocommerce_result_count()
 * @see woocommerce_catalog_ordering()
 */
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
/**
 * Product Loop Items.
 *
 * @see woocommerce_template_loop_product_link_open()
 * @see woocommerce_template_loop_product_link_close()
 * @see woocommerce_template_loop_add_to_cart()
 * @see woocommerce_template_loop_product_thumbnail()
 * @see woocommerce_template_loop_product_title()
 * @see woocommerce_template_loop_category_link_open()
 * @see woocommerce_template_loop_category_title()
 * @see woocommerce_template_loop_category_link_close()
 * @see woocommerce_template_loop_price()
 * @see woocommerce_template_loop_rating()
 */
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_upsell_display', 15 );
add_filter( 'woocommerce_product_loop_start', 'goldencat_product_loop_start' );
add_filter( 'woocommerce_product_loop_end', 'goldencat_product_loop_end' );

if ( ! function_exists( 'goldencat_product_loop_start' ) ) {
	function goldencat_product_loop_start() {
		return '<div class="products-grid-wrapper alignwide"><ul class="products columns goldencat-grid">';
	}
}

if ( ! function_exists( 'goldencat_product_loop_end' ) ) {
	function goldencat_product_loop_end( $fragments ) {
		return '</ul></div>';
	}
}

add_filter( 'woocommerce_post_class', 'goldencat_make_product_grid_size', 10, 2);

function goldencat_make_product_grid_size( $classes, $product ) {

	$classes[] = 'goldencat-grid__col-4';

	return $classes;
}


// Remove classic Button
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
// Add Je découvre Link
function goldencat_wc_get_product_label( $product_id = null ) {

	$label = goldencat_label( 'btn-product-shop');

	if ( !$label ) {
		return 'Je découvre';
	}
	
	return $label;
	
}

/**
 * Add Custom button to product in archive loop
 */
add_action( 'woocommerce_after_shop_loop_item', 'goldencat_add_product_item_link_to_btn', 10 );
if ( ! function_exists( 'goldencat_add_product_item_link_to_btn' ) ) {
	function goldencat_add_product_item_link_to_btn() {
		global $product;

		$label = goldencat_wc_get_product_label($product->get_id());

		$link = apply_filters( 'woocommerce_loop_product_link', get_the_permalink(), $product );
		
		
		goldencat_block_button( array(
			'href' => esc_url( $link ),
			'label' => $label,
			'style' => 'goldencat-button-arrow',
			'parent_class' => 'woocommerce-loop-product__link'

		));
	}
}

/**
 * Add Custom button to product in product grid block
 */
add_filter('woocommerce_blocks_product_grid_item_html', 'goldencat_add_link_to_product_grid_item', 10, 3);
function goldencat_add_link_to_product_grid_item($product_grid_html, $data, $product) {
	
	$new_product_grid_html = '';
	$label = goldencat_wc_get_product_label($product->get_id());
	
	$link = apply_filters( 'woocommerce_loop_product_link', $product->get_permalink(), $product );
	if ($data->button) {
		ob_start();
		goldencat_block_button( array(
			'href' => esc_url( $link ),
			'label' => $label,
			'parent_class' => 'woocommerce-loop-product__link'
		) );
		$btn = ob_get_clean();
		return str_replace($data->button, $btn, $product_grid_html);
	} else {
		ob_start();
		goldencat_block_button( array(
			'href' => esc_url( $link ),
			'label' => $label,
			'parent_class' => 'woocommerce-loop-product__link'
		) );
		$btn = ob_get_clean();
		return str_replace('</li>', $btn . '</li>', $product_grid_html);
	}
}




/**
 * Change display of title in archive loop
 * 
 */
remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
add_action( 'woocommerce_shop_loop_item_title', 'goldencat_woocommerce_template_loop_product_title', 10 );
if ( ! function_exists( 'goldencat_woocommerce_template_loop_product_title' ) ) {
	/**
	 * Show the product title in the product loop. By default this is an H2.
	 */
	function goldencat_woocommerce_template_loop_product_title() {
		echo '<h3 class=" ' . esc_attr( apply_filters( 'woocommerce_product_loop_title_classes', 'woocommerce-loop-product__title' ) ) . '">' . get_the_title() . '</h3>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}

/**
 * Change display of product grid block
 * 
 */
if ( ! function_exists( 'goldencat_wc_product_grid_item_html_remodel' ) ) {
	function goldencat_wc_product_grid_item_html_remodel( $product_grid_html, $data, $product ) {

		$new_product_grid_html = $product_grid_html;

		// add image class
		if ( $data->image ) {
			$new_image = str_replace( 'wc-block-grid__product-image', 'wc-block-grid__product-image woocommerce-loop-product__img-wrapper', $data->image );
			$new_product_grid_html = str_replace( $data->image, $new_image, $new_product_grid_html ); 
		}

		// Change tag element of title wrapper
		if ( $data->title ) {
			$new_title = str_replace( 'div', 'h3', $data->title );
			$new_title = str_replace( 'wc-block-grid__product-title', 'wc-block-grid__product-title h3', $new_title );
			$new_product_grid_html = str_replace( $data->title, $new_title, $new_product_grid_html ); 
		}

		// Add Price in product link
		if ( $data->price ) {
			$price_html = $data->price;

			$title_html_to_search = $data->title;
			if ( isset( $new_title)  ) {
				$title_html_to_search = $new_title;
			}
			$new_product_grid_html = str_replace( $data->price, '', $new_product_grid_html ); 

			$new_product_grid_html = str_replace( $title_html_to_search, $title_html_to_search . "\n" . $price_html, $new_product_grid_html ); 
		}

		return $new_product_grid_html;
	}
}
add_filter('woocommerce_blocks_product_grid_item_html', 'goldencat_wc_product_grid_item_html_remodel', 10, 3);

/**
 * Add Term Tag above title
 */
function goldencat_add_tag_flash() {
	global $product;
	$taxonomy = 'product_tag';
	$has_term = has_term( '', $taxonomy, $product->get_id() );
	$first_term = $has_term ? goldencat_get_the_term_list( $taxonomy, $product->get_id(), '', 1 ) : false;

	echo $first_term;
}
add_action( 'woocommerce_before_shop_loop_item_title', 'goldencat_add_tag_flash', 9 );

/**
 * Add Term Tag Above title 
 */
function goldencat_add_term_tag_to_product_grid_item($product_grid_html, $data, $product) {
	
	// $label = goldencat_wc_get_product_label($product->get_id());

	$taxonomy = 'product_tag';
	$has_term = has_term( '', $taxonomy, $product->get_id() );
	$first_term = $has_term ? goldencat_get_the_term_list( $taxonomy, $product->get_id(), '', 1 ) : false;
	
	// $link = apply_filters( 'woocommerce_loop_product_link', $product->get_permalink(), $product );
	if ($data->title) {
		$tag = $first_term;
		return str_replace($data->title, $tag . $data->title, $product_grid_html);
	} 
}
add_action( 'woocommerce_blocks_product_grid_item_html', 'goldencat_add_term_tag_to_product_grid_item', 10, 3 );



// Wrap Image Product in Loop
// add_action( 'woocommerce_before_shop_loop_item_title', 'goldencat_wc_loop_product_wrap_start', 9 );
// add_action( 'woocommerce_before_shop_loop_item_title', 'goldencat_wc_loop_product_wrap_end', 11 );

if ( ! function_exists( 'goldencat_wc_loop_product_wrap_start' ) ) {
	function goldencat_wc_loop_product_wrap_start() {
		
		?>
		<div class="woocommerce-loop-product__img-wrapper">
		<?php
		return;
	}
}

if ( ! function_exists( 'goldencat_wc_loop_product_wrap_end' ) ) {
	function goldencat_wc_loop_product_wrap_end() {
		?>
		</div>
		<?php
		return;
	}
}
