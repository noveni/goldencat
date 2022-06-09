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
		register_post_meta(
			'gc_temoignage', 
			'_goldencat_quotes_user_name', 
			array(
				'show_in_rest' => true,
				'type' => 'string',
				'single' => true,
				'auth_callback' => function() {
					return current_user_can( 'edit_posts' );
				}
			)
		);

		// Register the post meta field the meta box will save to.
		register_post_meta(
			'',
			'_goldencat_post_icon',
			array(
				'show_in_rest' => true,
				'single'       => true,
				'type'         => 'number',
				'auth_callback' => function() {
					return current_user_can( 'edit_posts' );
				}
			)
		);
    }
    
    add_action( 'init', 'goldencat_register_meta' );
}

function goldencat_get_post_icon( $post_id = null ) {

	$post_id = $post_id ? $post_id : get_the_ID();
	$post_icon = get_post_meta( $post_id, '_goldencat_post_icon', true );
	if ( !$post_icon ) {
		return '';
	} else {
		return wp_get_attachment_image($post_icon, '', true );
	}
}
