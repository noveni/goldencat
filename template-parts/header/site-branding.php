<?php
/**
 * The Logo
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Golden_Cat
 */

$blog_info    = get_bloginfo( 'name' );
$header_class = 'site-logo';

ob_start(); 
?>
<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
	<?php echo goldencat_logo(); ?><span class="screen-reader-text"><?php echo esc_html( $blog_info ); ?></span>
</a>
<?php
$logo = ob_get_clean();

?>

<div class="site-branding">
	<?php if ( $blog_info ) : ?>
		<?php if ( is_front_page() || is_home() ) : ?>
			<h1 class="<?php echo esc_attr( $header_class ); ?>">
				<?php echo $logo; ?>
			</h1>
		<?php else : ?>
			<div class="<?php echo esc_attr( $header_class ); ?>">
				<?php echo $logo; ?>
			</div>
		<?php endif; ?>
	<?php endif; ?>
</div>
