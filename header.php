<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until main
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Golden_Cat
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(''); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'goldencat' ); ?></a>

	<?php get_template_part( 'template-parts/header/notice-bar' ); ?>
	<?php get_template_part( 'template-parts/header/site-header' ); ?>

	<main id="primary" class="site-main" role="main">
	
