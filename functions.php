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
    $title = $title ?: __('Ecran Noir &rsaquo; Error', 'ecrannoirtwentyone');
    $footer = '<a href="https://ecrannoir.be/">ecrannoir.be</a>';
    $message = "<h1>{$title}<br><small>{$subtitle}</small></h1><p>{$message}</p><p>{$footer}</p>";
    wp_die($message, $title);
};

// This theme requires WordPress 5.3 or later.
if ( version_compare( $GLOBALS['wp_version'], '5.8', '<' ) ) {
    $en_error(__('You must be using WordPress 5.8.0 or greater.', 'ecrannoirtwentyone'), __('Invalid WordPress version', 'ecrannoirtwentyone'));
}


define( 'GOLDENCAT_THEME_ROOT_DIR', dirname( __DIR__ ) . DIRECTORY_SEPARATOR );
define( 'GOLDENCAT_THEME_ROOT_DIR_THEME', dirname(__FILE__) . DIRECTORY_SEPARATOR);
define( 'GOLDENCAT_THEME_ROOT_URI', get_theme_root_uri('goldencat', 'goldencat') . DIRECTORY_SEPARATOR );
define( 'GOLDENCAT_ECRANNOIR_POST_REVISIONS', 0 );
