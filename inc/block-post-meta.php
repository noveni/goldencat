<?php
/**
 * Register post meta.
 *
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( function_exists( 'register_meta' ) ) {
	/**
	 * Register block styles.
	 *
	 */
	function goldencat_register_meta() {

        register_meta(
			'post',
			'_goldencat_title_hidden',
			array(
				'show_in_rest'  => true,
				'single'        => true,
				'type'          => 'boolean',
				'auth_callback' => function() {
					return current_user_can( 'edit_posts' );
				},
			)
		);

		// Quote User Name
		register_post_meta('gc_temoignage', '_goldencat_quotes_user_name', array(
			'show_in_rest' => true,
			'type' => 'string',
			'single' => true,
			'auth_callback' => function() {
				return current_user_can( 'edit_posts' );
			}
		));
    }
    
    add_filter( 'init', 'goldencat_register_meta' );
}
