<?php
/**
 * Display the site Header Layout with the logo aligned left and menu on right
 *
 * 
 * 
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Golden_Cat
 */


?>

<div class="header-layout header-layout-style-left-logo alignwide">


    <!-- Logo Site -->
    <?php get_template_part( 'template-parts/header/site-branding' ); ?>

    <nav id="site-navigation" class="primary-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Primary menu',  ); ?>">
        <div class="primary-menu-container goldencat-toggle-menu">
        <?php if ( has_nav_menu( 'primary-left' ) ) : ?>
            <?php
                wp_nav_menu(
                    array(
                        'theme_location'  => 'primary-left',
                        'menu_class'      => 'menu-wrapper',
                        'container_class' => 'primary-menu-ul',
                        'items_wrap'      => '<ul class="%2$s">%3$s</ul>',
                        'fallback_cb'     => false,
                    )
                );
            ?>
        <?php else: ?>
            <p class="aligncenter has-rose-background-color">Veuillez définir un menu dans l'administration</p>
        <?php endif; ?>
        </div>
    </nav>

    <div class="search-container">
        <?php get_template_part( 'template-parts/header/inline-search' ); ?>
    </div>

    <div class="menu-button-container menu-button-left">
        <?php get_template_part( 'template-parts/header/wc-menu' ); ?>
    </div>
    <div class="menu-button-container menu-button-right">
        <button id="search-toggle-menu" class="search-header-toggle" data-toggle-target=".search-inline" data-set-focus=".search-inline .search-input" >
            <span class="screen-reader-text"><?php esc_html_e( 'Rechercher',  ); ?></span>
            <?php echo goldencat_icon( 'ui', 'search' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
        </button>
        <button id="primary-toggle-menu" class="button" aria-controls="primary-menu" aria-expanded="false">
            <?php echo goldencat_icon( 'ui', 'menu', 22 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
            <span class="screen-reader-text"><?php esc_html_e( 'Menu', 'goldencat' ); ?></span>
        </button><!-- #primary-mobile-menu -->
    </div>
</div>
