<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Golden_Cat
 */

?>
		</main><!-- #primary -->
	</div><!-- #content -->
	<?php get_template_part( 'template-parts/footer/above-section' ); ?>
	<footer id="colophon" class="site-footer">
		<?php if ( is_active_sidebar('footer-main') ) : ?>
			<div class="footer-main-area">
				<?php dynamic_sidebar( 'footer-main' ); ?>
			</div>
		<?php endif; ?>
		<?php if ( is_active_sidebar('footer-between-main') ) : ?>
			<div class="footer-between-main">
				<?php dynamic_sidebar( 'footer-between-main' ); ?>
			</div>
		<?php endif; ?>
		<div class="footer-bottom aligncenter">
			<?php get_template_part( 'template-parts/footer/credits' ); ?>
			<?php get_template_part( 'template-parts/footer/legals' ); ?>
		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
