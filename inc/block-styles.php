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

		// Button With Arrow.
		register_block_style(
			'core/button',
			array(
				'name'  => 'ecrannoir-button-arrow',
				'label' => esc_html__( 'Bouton fléché', 'ecrannoirtwentyone' ),
			)
		);
		
		// Horizontal List
		register_block_style(
			'core/list',
			array(
				'name'  => 'ecrannoir-list-horizontal',
				'label' => esc_html__( 'Liste Horizontal', 'ecrannoirtwentyone' ),
			)
		);


	}
	add_action( 'init', 'goldencat_register_block_styles' );
}
