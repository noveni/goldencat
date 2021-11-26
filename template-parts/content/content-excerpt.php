<?php
/**
 * Template part for displaying post archives and search results
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Golden_Cat
 */

?>

<article id="post-<?php the_ID(); ?>" class="goldencat-grid__col-4 post-excerpt">
    <a href="<?php the_permalink(); ?>">
		<?php goldencat_post_thumbnail(); ?>
		<?php the_title( '<h4 class="">', '</h4>' ); ?>
	</a>
	<div class="excerpt-content">
		<?php the_excerpt(); ?>
		<?php goldencat_block_button( array( 'href' => get_the_permalink() ) ); ?>
	</div><!-- .entry-content -->

	<div class="excerpt-footer">
		<?php

			goldencat_posted_on();
			goldencat_posted_by();
		?>
		<div class="excerpt-term">
			<?php echo goldencat_get_the_term_list( 'category', null, ', '); ?>
		</div>
	</div>
</article><!-- #post-${ID} -->
