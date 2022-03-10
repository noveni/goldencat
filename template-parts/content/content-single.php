<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Golden_Cat
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

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

	<footer class="entry-footer">
		
		<?php

		goldencat_posted_on();
		goldencat_posted_by();

		?>

		<div class="excerpt-term">
		<?php echo goldencat_get_the_term_list( 'category', null, ', '); ?>
		</div>

	</footer><!-- .entry-footer -->

	<?php if ( ! is_singular( 'attachment' ) ) : ?>
		<?php get_template_part( 'template-parts/post/author-bio' ); ?>
	<?php endif; ?>

</article><!-- #post-<?php the_ID(); ?> -->
