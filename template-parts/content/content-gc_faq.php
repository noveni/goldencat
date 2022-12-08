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
		<?php the_content(); ?>
	</div><!-- .entry-content -->
</article><!-- #post-${ID} -->
