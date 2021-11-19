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



<nav id="site-navigation" class="primary-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Primary menu', 'ecrannoirtwentyone' ); ?>">
    <div class="primary-menu-container">
    <?php if ( has_nav_menu( 'primary-left' ) && has_nav_menu( 'primary-right' ) ) : ?>
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
        <?php get_template_part( 'template-parts/header/site-branding' ); ?>
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

<div class="menu-button-container">
    <button id="primary-menu-button" class="button" aria-controls="primary-menu" aria-expanded="false">
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
<?php get_template_part( 'template-parts/header/wc-menu' ); ?>
