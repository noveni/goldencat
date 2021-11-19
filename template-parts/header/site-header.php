<?php
/**
 * Display the site Header
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Golden_Cat
 */


$wrapper_classes  = 'site-header';
$layout_menu = apply_filters( 'goldencat_theme_layout_style', 'left-logo' ); //'central-logo'
// $wrapper_classes .= has_custom_logo() ? ' has-logo' : '';
// $wrapper_classes .= true === get_theme_mod( 'display_title_and_tagline', true ) ? ' has-title-and-tagline' : '';
// $wrapper_classes .= has_nav_menu( 'primary' ) ? ' has-menu' : '';
?>

<header id="masthead" class="<?php echo esc_attr( $wrapper_classes ); ?>" role="banner">

    <?php get_template_part( 'template-parts/header/layout/style', $layout_menu ); ?>

</header>
