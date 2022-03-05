<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Block Patterns
 *
 * @link https://developer.wordpress.org/reference/functions/register_block_pattern/
 * @link https://developer.wordpress.org/reference/functions/register_block_pattern_category/
 *
 */

/**
 * Register Block Pattern Category.
 */
if ( function_exists( 'register_block_pattern_category' ) ) {

	register_block_pattern_category(
		'goldencat',
		array( 'label' => esc_html__( 'Thème', 'goldencat' ) )
	);
}

/**
 * Register Block Patterns.
 */
if ( function_exists( 'register_block_pattern' ) ) {

	register_block_pattern(
		'goldencat/card',
		array(
			'title'       => __( 'Card', 'goldencat' ),
			'categories'  => array( 'goldencat' ),
			'description' => _x( 'A Card".', 'Block pattern description', 'goldencat' ),
			'content'     => '<!-- wp:group {"layout":{"wideSize":"400px"}} -->
			<div class="wp-block-group"><!-- wp:image {"align":"center","id":6,"sizeSlug":"medium","linkDestination":"none"} -->
			<div class="wp-block-image"><figure class="aligncenter size-medium"><img src="https://starter.localhost/wp-content/uploads/woocommerce-placeholder-300x300.png" alt="" class="wp-image-6"/></figure></div>
			<!-- /wp:image -->
			
			<!-- wp:paragraph {"align":"center"} -->
			<p class="has-text-align-center">Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit...</p>
			<!-- /wp:paragraph -->
			
			<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
			<div class="wp-block-buttons"><!-- wp:button -->
			<div class="wp-block-button"><a class="wp-block-button__link">Découvrir</a></div>
			<!-- /wp:button --></div>
			<!-- /wp:buttons --></div>
			<!-- /wp:group -->'
		)
	);
}
