<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Golden_Cat
 */


get_header();

if ( have_posts() ) :

	while ( have_posts() ) :
		the_post();

		get_template_part( 'template-parts/content/content', 'page' );

	endwhile; // End of the loop.

else :

	get_template_part( 'template-parts/content/content', 'none' );

endif;

get_footer();
