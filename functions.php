<?php
/**
 * Golden Cat functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Golden_Cat
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

/**
 * Helper function for prettying up errors
 * @param string $message
 * @param string $subtitle
 * @param string $title
 */
$en_error = function ($message, $subtitle = '', $title = '') {
    $title = $title ?: __('Ecran Noir &rsaquo; Error', 'goldencat');
    $footer = '<a href="https://ecrannoir.be/">ecrannoir.be</a>';
    $message = "<h1>{$title}<br><small>{$subtitle}</small></h1><p>{$message}</p><p>{$footer}</p>";
    wp_die($message, $title);
};

// This theme requires WordPress 5.3 or later.
if ( version_compare( $GLOBALS['wp_version'], '5.8', '<' ) ) {
    $en_error(__('You must be using WordPress 5.8.0 or greater.', 'goldencat'), __('Invalid WordPress version', 'goldencat'));
}


define( 'GOLDENCAT_THEME_ROOT_DIR', dirname( __DIR__ ) . DIRECTORY_SEPARATOR );
define( 'GOLDENCAT_THEME_ROOT_DIR_THEME', dirname(__FILE__) . DIRECTORY_SEPARATOR);
define( 'GOLDENCAT_THEME_ROOT_URI', get_theme_root_uri('goldencat', 'goldencat') . DIRECTORY_SEPARATOR );
define( 'GOLDENCAT_POST_REVISIONS', 0 );


require get_template_directory() . '/inc/theme-helpers.php';
require get_template_directory() . '/inc/classes/class-theme-base.php';
require get_template_directory() . '/inc/classes/class-theme-admin.php';
require get_template_directory() . '/inc/classes/class-theme-settings.php';
require get_template_directory() . '/inc/classes/class-theme-scripts.php';
require get_template_directory() . '/inc/classes/class-theme-meta.php';
require get_template_directory() . '/inc/classes/class-theme-icons.php';
require get_template_directory() . '/inc/classes/class-theme-menu.php';
require get_template_directory() . '/inc/classes/class-theme-assets.php';
require get_template_directory() . '/inc/classes/class-theme-cookies.php';

require get_template_directory() . '/inc/icons-functions.php';
require get_template_directory() . '/inc/template-functions.php';
require get_template_directory() . '/inc/template-tags.php';


// Custom Post Types
require get_template_directory() . '/inc/post-type-functions.php';
// Register Meta
require get_template_directory() . '/inc/block-post-meta.php';
// Block template functions
require get_template_directory() . '/inc/blocks-functions.php';
// Block Patterns.
require get_template_directory() . '/inc/block-patterns.php';
// Block Styles.
require get_template_directory() . '/inc/block-styles.php';


$theme = new GoldenCatThemeBase(
    array(
        'disable_comment' => true,
        'clean' => true,
        'menus' => array(
            'primary-left'   => __( 'Primary Menu Left', 'goldencat' ),
            'primary-right'   => __( 'Primary Menu Right', 'goldencat' ),
            'footer-legals'    => __( 'Legals Menu', 'goldencat' ),
        ),
        'widgets' => array(
            array(
                'name'          => __('Header Line', 'goldencat'),
                'id'            => 'header-notice-bar',
                'description'   => __( 'Add Widgets above the header.', 'goldencat' ),
            ),
            array(
                'name'          => __('Big Footer', 'goldencat'),
                'id'            => 'footer-main',
                'description'   => __( 'Add Widgets here to appear in the main footer.', 'goldencat' ),
            ),
            array(
                'name'          => __('Header-Menu Social', 'goldencat'),
                'id'            => 'header-menu-social',
                'description'   => __( 'Add Social menu here to appear in the header.', 'goldencat' ),
            ),
        ),
        'image_sizes' => array(
            'goldencat_thumb' => array(
                'nice_name' => __('Vignette du thème'),
                'width' => 300,
                'height' => 400,
                'crop' => true
            )
        )
    )
);

if ( goldencat_is_woocommerce_activated() ) {
	require get_template_directory() . '/inc/classes/class-theme-woocommerce.php';
    
    $theme_woocommerce = new GoldenCatThemeWooCommerce();

    require  get_template_directory() . '/inc/woocommerce/goldencat-wc.php';
    require  get_template_directory() . '/inc/woocommerce/goldencat-wc-template-functions.php';
}


// Contact Form 7
if ( class_exists('WPCF7') ) {
    add_filter( 'wpcf7_load_js', '__return_false' );
    add_filter( 'wpcf7_load_css', '__return_false' );

    function load_wpcf7_js() {
        if (is_page( 'contact' )){
            wpcf7_enqueue_scripts();
            wpcf7_enqueue_styles();
        }
    }
    add_action( 'wp_footer', 'load_wpcf7_js' );
}



add_action( 'init', function() {
    $color_theme = goldencat_get_theme_color();
    $theme_taxonomy_fields = array(
        array(
            'taxonomy' => array( 'category', 'post_tag' ),
            'fields' => array(
                array(
                    'id' => 'global_field_color',
                    'label' => 'Select Color',
                    'type' => 'theme_color',
                    'choices' => $color_theme,
                    'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam'
                ),
            )
        ),
        array(
            'taxonomy' => array( 'category' ),
            'fields' => array(
                array(
                    'id' => 'only-category-image',
                    'label' => 'Image',
                    'type' => 'image',
                    'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam'
                ),
             )
        )
    );
    if (is_admin() && $theme_taxonomy_fields) {
        require get_template_directory() . '/inc/classes/class-taxonomy-meta-field.php';
        $taxo_meta_field = new GoldenCatTaxonomyMetaField( $theme_taxonomy_fields );
    }
}, 99);





require get_template_directory() . '/assets/blocks/cover-slider-block.php';
require get_template_directory() . '/assets/blocks/faq-block-grid.php';
require get_template_directory() . '/assets/blocks/latest-posts.php';
require get_template_directory() . '/assets/blocks/picked-post-block.php';
require get_template_directory() . '/assets/blocks/picked-term.php';
require get_template_directory() . '/assets/blocks/product-price.php';
require get_template_directory() . '/assets/blocks/quotes-slider.php';
require get_template_directory() . '/assets/blocks/term-of-taxonomy.php';

add_filter( 'goldencat_theme_layout_menu_style', function() {
    return 'central-logo';
}, 100);
