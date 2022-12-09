<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Golden_Cat
 */

get_header();
?>

<div class="entry-content is-layout-constrained">
	<?php ob_start(); ?>
	<div class="archive-content alignwide">
		<div class="alignwide">
			<div>
				<div class="goldencat-grid filter-content-to-refresh loadmore-content-to-refresh">
					<?php if ( have_posts() ) : ?>
						<?php while ( have_posts() ) : ?>
							<?php the_post(); ?>
							<div class="goldencat-grid__col">
								<?php get_template_part( 'template-parts/content/content-excerpt' ); ?>
							</div>
						<?php endwhile; ?>
					<?php else : ?>
						<p class="aligncenter">Aucun articles, essayez de retirer des filtres.</p>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<div class="aligncenter is-layout-constrained">
			<?php get_template_part( 'template-parts/pagination'); // Previous/next page navigation. ?>
		</div>
	</div>
	<?php $content = ob_get_clean(); ?>
<?php goldencat_print_page_blocks( get_option('page_for_posts'), $content ); ?>
</div>

<?php
get_footer();
