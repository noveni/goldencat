<?php
/**
 * Template part for displaying post archives and search results
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Golden_Cat
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('goldencat-grid__col-4'); ?>>
    <a href="<?php the_permalink(); ?>">
		<div>
            <?php the_post_thumbnail( 'goldencat_thumb' ); ?>
        </div>
		<?php the_title( '<h4 class="">', '</h4>' ); ?>
	</a>
	<div class="entry-content">
		<?php the_excerpt(); ?>
	</div><!-- .entry-content -->

</article><!-- #post-${ID} -->
