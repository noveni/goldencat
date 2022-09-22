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

<div class="header-layout header-layout-style-central-logo-multiline">


    <div class="header-multiline-line header-multiline-line__top">

        <?php if ( is_active_sidebar('header-contact-bar') ) : ?>
            <div class="header-contact-bar-area menu-button-container menu-button-left">
                <?php dynamic_sidebar( 'header-contact-bar' ); ?>
            </div>
        <?php endif; ?>
        <!-- Logo Site -->
        <?php get_template_part( 'template-parts/header/site-branding' ); ?>
        <div class="menu-button-container menu-button-wc">
            <?php get_template_part( 'template-parts/header/wc-menu', null, array('with_label' => true) ); ?>
        </div>
        <div class="menu-button-container menu-button-right">
            <!-- <button id="search-toggle-menu" class="search-header-toggle" data-toggle-target=".search-inline" data-set-focus=".search-inline .search-input" >
                <span class="screen-reader-text"><?php esc_html_e( 'Rechercher', 'goldencat' ); ?></span>
                <?php echo goldencat_icon( 'ui', 'search' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
            </button> -->
            <button id="primary-toggle-menu" class="button" aria-controls="primary-menu" aria-expanded="false">
                <?php echo goldencat_icon( 'ui', 'menu', 22 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
                <span class="screen-reader-text"><?php esc_html_e( 'Menu', 'goldencat' ); ?></span>
            </button><!-- #primary-mobile-menu -->
        </div>


    </div>
    <div class="header-multiline-line header-multiline-line__nav">
        <nav id="site-navigation" class="primary-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Primary menu',  ); ?>">
            <div class="primary-menu-container goldencat-toggle-menu">
            <?php if ( has_nav_menu( 'primary' ) ) : ?>
                <?php
                    wp_nav_menu(
                        array(
                            'theme_location'  => 'primary',
                            'menu_class'      => 'menu-wrapper',
                            'container_class' => 'primary-menu-ul',
                            'items_wrap'      => '<ul class="%2$s">%3$s</ul>',
                            'fallback_cb'     => false,
                        )
                    );
                ?>
            <?php else: ?>
                <ul class="primary-menu-ul menu-wrapper">
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
            </div>
        </nav>
    </div>

    <!-- <div class="search-container">
        <?php //get_template_part( 'template-parts/header/inline-search' ); ?>
    </div> -->
   
</div>
