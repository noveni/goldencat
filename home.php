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

<?php if ( have_posts() ) : ?>

	<header class="page-header">
		<?php
		the_archive_title( '<h1 class="page-title">', '</h1>' );
		?>
	</header><!-- .page-header -->
	<div class="alignwide">
		<div class="goldencat-grid">
		<?php
		/* Start the Loop */
		while ( have_posts() ) :
			the_post();

			get_template_part( 'template-parts/content/content', 'excerpt' );

		endwhile;
		?>
		</div>
	</div>
	<?php
	get_template_part( 'template-parts/pagination' );

else :

	get_template_part( 'template-parts/content/content', 'none' );

endif;
?>

<?php
get_footer();
