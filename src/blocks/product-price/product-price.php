<?php
/**
 * Server-side rendering of the `goldencat/product-price` block.
 *
 * @package WordPress
 */

/**
 * Renders the `goldencat/product-price` block on the server.
 *
 * @param array    $attributes Block attributes.
 * @param string   $content    Block default content.
 * @param WP_Block $block      Block instance.
 *
 * @return string Returns the filtered post title for the current post wrapped inside "h1" tags.
 */
function render_block_goldencat_product_price( $attributes, $content, $block ) {
	if ( ! isset( $block->context['postId'] ) ) {
		return '';
	}

	$post_ID          = $block->context['postId'];
    $product = wc_get_product( $post_ID );
	$tag_name         = 'p';
	$align_class_name = empty( $attributes['textAlign'] ) ? '' : "has-text-align-{$attributes['textAlign']}";

    $price = $product->get_price_html();
	
	$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => $align_class_name ) );

	return sprintf(
		'<%1$s %2$s>%3$s</%1$s>',
		$tag_name,
		$wrapper_attributes,
		$price
	);
}

/**
 * Registers the `core/post-title` block on the server.
 */
function register_block_goldencat_product_price() {
    $dir = __DIR__;// . '/product-price';
	register_block_type_from_metadata(
		$dir,
		array(
			'render_callback' => 'render_block_goldencat_product_price',
		)
	);
}
add_action( 'init', 'register_block_goldencat_product_price' );
