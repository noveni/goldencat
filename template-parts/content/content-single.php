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

	<header class="entry-header is-layout-constrained">
		<?php
		if ( is_singular() ) :
			the_title( '<h1 class="entry-title">', '</h1>' );
		else :
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		endif;

		if ( 'post' === get_post_type() ) :
			?>
			<?php goldencat_social_share_render_icon( get_the_ID() ); ?>
			<div class="entry-meta">
				<?php
				
				// goldencat_posted_on();
				// goldencat_posted_by();
				?>
			</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->

	<div class="entry-content is-layout-constrained">
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

	<footer class="entry-footer is-layout-constrained">
		<div>
			<?php

			goldencat_posted_on();
			goldencat_posted_by();

			?>
		</div>

		<div class="excerpt-term">
		<?php echo goldencat_get_the_term_list( 'category', null, ', '); ?>
		</div>

	</footer><!-- .entry-footer -->

	<?php if ( ! is_singular( 'attachment' ) ) : ?>
		<?php get_template_part( 'template-parts/post/author-bio' ); ?>
	<?php endif; ?>

</article><!-- #post-<?php the_ID(); ?> -->
