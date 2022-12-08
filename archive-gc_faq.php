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
	<?php if ( have_posts() ) : ?>
		<?php ob_start(); ?>
		<div class="alignwide">
			<div class="alignwide">
				<div class="goldencat-grid">
				<?php
				/* Start the Loop */
				while ( have_posts() ) :
					the_post(); ?>
					<div class="goldencat-grid__col">
						<?php get_template_part( 'template-parts/content/content', get_post_type() ); ?>
					</div>
					<?php
				endwhile;
				?>
				</div>
			</div>
			<?php
			get_template_part( 'template-parts/pagination' ); ?>
		</div>
		<?php $content = ob_get_clean();
		// echo $content;
		goldencat_print_page_blocks( 'faq', $content );
	else :

		get_template_part( 'template-parts/content/content', 'none' );

	endif; ?>
</div>

<?php
get_footer();
