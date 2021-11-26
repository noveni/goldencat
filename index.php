<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Golden_Cat
 */

get_header();

if ( have_posts() ) :

	if ( ! is_singular() ): ?>
	<div class="alignwide">
		<div class="goldencat-grid">
	<?php endif;
	/* Start the Loop */
	while ( have_posts() ) :
		the_post();
		/*
		* Include the Post-Type-specific template for the content.
		* If you want to override this in a child theme, then include a file
		* called content-___.php (where ___ is the Post Type name) and that will be used instead.
		*/
		get_template_part( 'template-parts/content/content', ! is_singular() ? 'excerpt' : '' );

	endwhile;
	if ( ! is_singular() ): ?>
		</div>
	</div>
	<?php endif;
	if ( ! is_singular() ):
		get_template_part( 'template-parts/pagination' );
	else:
		the_posts_navigation();
	endif;

else :

	get_template_part( 'template-parts/content/content', 'none' );

endif;

get_footer();
