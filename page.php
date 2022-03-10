<?php
/**
 * The template for displaying all single page
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Golden_Cat
 */

get_header();

/* Start the Loop */
while ( have_posts() ) :
	the_post();

	?>
	<div class="entry-content">
		<?php
		the_content();

		wp_link_pages(
			array(
				'before'   => '<nav class="page-links" aria-label="' . esc_attr__( 'Page', 'goldencat' ) . '">',
				'after'    => '</nav>',
				/* translators: %: Page number. */
				'pagelink' => esc_html__( 'Page %', 'goldencat' ),
			)
		);
		?>
	</div><!-- .entry-content -->
	<?php

	
endwhile; // End of the loop.

get_footer();
