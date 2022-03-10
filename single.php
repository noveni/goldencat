<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Golden_Cat
 */

get_header();

/* Start the Loop */
while ( have_posts() ) :
	the_post();

	get_template_part( 'template-parts/content/content-single' );

	// Previous/next post navigation.
	$goldencat_next = is_rtl() ? goldencat_icon( 'ui', 'arrow_left' ) : goldencat_icon( 'ui', 'arrow_right' );
	$goldencat_prev = is_rtl() ? goldencat_icon( 'ui', 'arrow_right' ) : goldencat_icon( 'ui', 'arrow_left' );

	$goldencat_next_label     = esc_html__( 'Article suivant', 'goldencat' );
	$goldencat_previous_label = esc_html__( 'Article précédent', 'goldencat' );

	the_post_navigation(
		array(
			'next_text' => '<span class="nav-subtitle">' . $goldencat_next_label . $goldencat_next . '</span><p class="nav-title">%title</p>',
			'prev_text' => '<span class="nav-subtitle">' . $goldencat_prev . $goldencat_previous_label . '</span><p class="nav-title">%title</p>',
		)
	);
endwhile; // End of the loop.

get_footer();
