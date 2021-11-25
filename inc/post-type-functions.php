<?php
/**
 * Custom Post Type for theme
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

function goldencat_get_default_post_type_config() {
    $goldencat_default_post_type = array(
        'public'                    => true,
        'hierarchical'              => false,
        'exclude_from_search'       => false,
        'publicly_queryable'        => true,
        'show_ui'                   => true,
        'show_in_menu'              => true,
        'show_in_admin_bar'         => true,
        'show_in_rest'              => true,
        'menu_position'             => 5, // 10 - below Media,
        'menu_icon'                 => 'dashicons-admin-post',
        'capability_type'           => 'post',
        'supports'                  => array(
            'title',
            'editor',
            'thumbnail',
            'excerpt',
            'page-attributes',
        ),
        // 'taxonomies'                => array(),
        'has_archive'               => true,
        // 'rewrite'                   => array(
        //     'slug'       => 'custom-post-type',
        //     'with_front' => false,
        // ),
    );

    return $goldencat_default_post_type;
}

// Custom Post Types
$show_faq_type = boolval( get_option( 'goldencat_theme_posttype_faq_on', true ) ?? false);
if ($show_faq_type === true) {
    require get_template_directory() . '/inc/post-type/faq.php';
}
$show_quote_type = boolval( get_option( 'goldencat_theme_posttype_quote_on', true ) ?? false);
if ($show_quote_type === true) {
    require get_template_directory() . '/inc/post-type/temoignage.php';
}
