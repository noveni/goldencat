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



$theme = new GoldenCatThemeBase(
    array(
        'disable_comment' => true,
        'clean' => true,
        'widgets' => array(
            array(
                'name'          => __('Header Line', 'goldencat'),
                'id'            => 'header-line',
                'description'   => __( 'Add Widgets above the header.', 'goldencat' ),
            ),
            array(
                'name'          => __('Big Footer', 'goldencat'),
                'id'            => 'footer-main',
                'description'   => __( 'Add Widgets here to appear in the main footer.', 'goldencat' ),
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
