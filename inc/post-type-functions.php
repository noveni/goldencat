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
$show_faq_type = GoldenCatThemeSettings::getPostTypeActive('posttype_faq_on');
if ($show_faq_type === true) {
    require get_template_directory() . '/inc/post-type/faq.php';
}
$show_quote_type = GoldenCatThemeSettings::getPostTypeActive('posttype_quote_on');
if ($show_quote_type === true) {
    require get_template_directory() . '/inc/post-type/temoignage.php';
}


function goldencat_register_block_post_template() {

    $block_template = [
        [ 'core/group', [ 'layout' => [ "inherit" => true ] ], [
            [ 'core/post-title', [ 'textAlign' => 'center', 'level' => 1 ] ],
            [ 'core/post-excerpt', [ 'textAlign' => 'center', 'moreText' => '', 'showMoreOnNewLine' => false, 'className' => 'is-style-goldencat-p-sous-titre' ] ]
        ]],
        [ 'core/paragraph', [ 'placeholder' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris ut massa sodales, sodales orci id, accumsan lacus. Praesent finibus convallis volutpat. Aliquam dictum nibh et elit rhoncus efficitur.' ] ],
        [ 'core/buttons', [ 'layout' => [ 'type' => 'flex', 'justifyContent' => 'center' ]], [
            [ 'core/button', [ 'className' => 'is-style-outline', 'url' => site_url() . '/le-blog/', 'text' => '<strong>Découvrir les autres articles</strong>', 'lock' => [ 'remove' => false, 'move' => false ] ] ]
        ]]
    ];

    $post_type_object                = get_post_type_object( 'post' );
	$post_type_object->template      = $block_template;
    // $post_type_object->template_lock = 'all';
    
}
add_action('init', 'goldencat_register_block_post_template');
