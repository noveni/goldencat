<?php
/**
 * Block Styles
 *
 * @link https://developer.wordpress.org/reference/functions/register_block_style/
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( function_exists( 'register_block_style' ) ) {
	/**
	 * Register block styles.
	 *
	 */
	function goldencat_register_block_styles() {

		register_block_style(
			'core/button',
			array(
				'name'  => 'filled',
				'label' => esc_html__( 'Fill', 'goldencat' ),
			)
		);

		// Button With Arrow.
		register_block_style(
			'core/button',
			array(
				'name'  => 'goldencat-button-arrow',
				'label' => esc_html__( 'Bouton fléché', 'goldencat' ),
			)
		);

		// Default Link style 
		register_block_style(
			'core/button',
			array(
				'name'  => 'goldencat-button-link',
				'label' => esc_html__( 'Link', 'goldencat' ),
			)
		);
		
		// Horizontal List
		register_block_style(
			'core/list',
			array(
				'name'  => 'goldencat-list-horizontal',
				'label' => esc_html__( 'Liste Horizontal', 'goldencat' ),
			)
		);

		// Columns Media-Text Like
		register_block_style(
			'core/columns',
			array(
				'name'  => 'goldencat-columns-media-text',
				'label' => esc_html__( 'Column Media Text', 'goldencat' ),
			)
		);

	}
	add_action( 'init', 'goldencat_register_block_styles' );
}
