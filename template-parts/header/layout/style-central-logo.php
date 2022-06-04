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

<div class="header-layout header-layout-style-central-logo">
    <?php get_template_part( 'template-parts/header/site-branding' ); ?>
    <nav id="site-navigation" class="primary-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Primary menu', 'ecrannoirtwentyone' ); ?>">
        <div class="primary-menu-container goldencat-toggle-menu">
        <?php if ( has_nav_menu( 'primary-left' ) && has_nav_menu( 'primary-right' ) ) : ?>
            <div class="header-left-part">
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
            </div>
            <?php get_template_part( 'template-parts/header/site-branding' ); ?>
            <div class="header-right-part">
            <?php
                wp_nav_menu(
                    array(
                        'theme_location'  => 'primary-right',
                        'menu_class'      => 'menu-wrapper',
                        'container_class' => 'primary-menu-ul',
                        'items_wrap'      => '<ul class="%2$s">%3$s</ul>',
                        'fallback_cb'     => false,
                    )
                );
            ?>
            <?php if ( is_active_sidebar('header-menu-social') ) : ?>
                <div class="header-menu-social">
                    <?php dynamic_sidebar( 'header-menu-social' ); ?>
                </div>
            <?php endif; ?>
            </div>
        <?php else: ?>
            <ul class="primary-menu-ul">
            <?php 
                wp_list_pages(
                    array(
                        'match_menu_classes' => true,
                        'show_sub_menu_icons' => true,
                        'title_li' => false,
                    )
                );
            ?>
            </ul>
        <?php endif; ?>
    </nav>

    <div class="menu-button-container menu-button-left">
        <button id="primary-toggle-menu" class="button" aria-controls="primary-menu" aria-expanded="false">
            <?php echo goldencat_icon( 'ui', 'menu', 22 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
                        <span class="header-button-label"><?php esc_html_e( 'Menu', 'goldencat' ); ?></span>
        </button><!-- #primary-mobile-menu -->
        <button class="search-header-toggle" data-toggle-target=".search-inline" data-set-focus=".search-inline .search-input" >
            <span class="screen-reader-text"><?php esc_html_e( 'Rechercher', 'ecrannoirtwentyone' ); ?></span>
            <?php echo goldencat_icon( 'ui', 'search' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
        </button>
        <div class="search-container">
            <?php get_template_part( 'template-parts/header/inline-search' ); ?>
        </div>
    </div>
    <div class="menu-button-container menu-button-right">
        <?php get_template_part( 'template-parts/header/wc-menu' ); ?>
    </div>
</div>
