<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly


/**
 * Check if WooCommerce is activated
 */
if ( ! function_exists( 'goldencat_is_woocommerce_activated' ) ) {
	/**
	 * Query WooCommerce activation
	 */
	function goldencat_is_woocommerce_activated() {
		if ( class_exists( 'woocommerce' ) ) { return true; } else { return false; }
	}
}



/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 *
 * @return array
 */
function goldencat_body_classes( $classes ) {

	/** Add page slug if it doesn't exist */
    if (is_single() || is_page() && !is_front_page()) {
        if (!in_array(basename(get_permalink()), $classes)) {
            $classes[] = basename(get_permalink());
        }
    }

	// Helps detect if JS is enabled or not.
	$classes[] = 'no-js';

	// Adds `singular` to singular pages, and `hfeed` to all other pages.
	$classes[] = is_singular() ? 'singular' : 'hfeed';


	// Remove unnecessary classes
	$home_id_class = 'page-id-' . get_option('page_on_front');
	$remove_classes = [
		'page-template-default',
		'page-id-' . get_option('page_on_front')
	];
	$classes = array_diff($classes, $remove_classes);
	return $classes;
}
add_filter( 'body_class', 'goldencat_body_classes' );

/**
 * Creates continue reading text
 */
function goldencat_continue_reading_text() {
	$continue_reading = sprintf(
		/* translators: %s: Name of current post. */
		esc_html__( 'Voir plus %s', 'goldencat' ),
		the_title( '<span class="screen-reader-text">', '</span>', false )
	);

	return $continue_reading;
}

/**
 * Create the continue reading link for excerpt.
 */
function goldencat_continue_reading_link_excerpt() {
	if ( ! is_admin() ) {

		if ( !is_single() ) {
			return '&hellip; <a class="more-link" href="' . esc_url( get_permalink() ) . '">' . goldencat_continue_reading_text() . '</a>';
		}
	}
}
add_filter( 'excerpt_more', 'goldencat_continue_reading_link_excerpt' );

/**
 * Create the continue reading link.
 */
function goldencat_continue_reading_link() {
	if ( ! is_admin() ) {
		return '<div class="more-link-container"><a class="more-link" href="' . esc_url( get_permalink() ) . '#more-' . esc_attr( get_the_ID() ) . '">' . goldencat_continue_reading_text() . '</a></div>';
	}
}
add_filter( 'the_content_more_link', 'goldencat_continue_reading_link' );

/**
 * Excerpt Length
 */
function goldencat_filter_excerpt_length($length) {
    if ( is_admin() ) {
        return $length;
    }

	if ( is_single() ) {
		return -1;
	}
    return 10;
}
// Filter the excerpt length.
add_filter('excerpt_length', 'goldencat_filter_excerpt_length');

/**
 * Filters the trimmed excerpt string.
 *
 * @since 2.8.0
 *
 * @param string $text        The trimmed text.
 * @param string $raw_excerpt The text prior to trimming.
 */
function goldencat_force_trim_excerpt( $text, $raw_excerpt ) {

	if ( '' !== trim( $raw_excerpt ) ) {

		$excerpt_length = (int) apply_filters( 'excerpt_length', 55 );
		$excerpt_more = apply_filters( 'excerpt_more', ' ' . '[&hellip;]' );
		$text = wp_trim_words( $text, $excerpt_length, $excerpt_more );
	}
	return $text;
}
add_filter( 'wp_trim_excerpt', 'goldencat_force_trim_excerpt', 10, 2);

/**
 * Filter the number of result for query
 */
function ecrannoir_twenty_one_limit_type_post( $query ) {
    // check if the user is requesting an admin page 
	// or current query is not the main query
    if ( is_admin() || !$query->is_main_query() ){
        return;
	}

	if ( is_archive() ) {
		$query->set('posts_per_page', 3);
	} elseif ( is_search() ) {
		$query->set('posts_per_page', 3);
	} else {
		$query->set('posts_per_page', 3);
	}
}
add_action( 'pre_get_posts', 'ecrannoir_twenty_one_limit_type_post' );
