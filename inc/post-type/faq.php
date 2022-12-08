<?php
/**
 * Theme Temoignage
 */

defined( 'ABSPATH' ) || exit;


function goldencat_register_faq() {
    $post_type_name = 'gc_faq';
    
    $labels = array(
        'name'                      => __( 'FAQ', 'goldencat' ),
        'singular_name'             => __( 'FAQ', 'goldencat' ),
        'add_new_item'              => __( 'Ajout une nouvelle faq', 'goldencat' ),
        'view_item'                 => __( 'Voir la faq', 'goldencat' ),
        'edit_item'                 => __( 'Modifier la faq', 'goldencat' ),
        'new_item'                  => __( 'Nouvelle faq', 'goldencat' ),
        'view_items'                => __( 'Voir les faq', 'goldencat' ),
        'search_items'              => __( 'Rechercher des faq', 'goldencat' ),
        'not_found'                 => __( 'Aucun faq trouvée', 'app' ),
        'not_found_in_trash'        => __( 'Aucune faq trouvée dans la corbeille', 'app' ),
        'all_items'                 => __( 'Toutes les faq', 'goldencat'),
    );

    $config = array(
        'label'     => 'Faq',
        'labels'    => $labels,
        'hierarchical'              => false,
        'show_in_rest'              => true,
        'exclude_from_search'       => false,
        'publicly_queryable'        => true,
        'rewrite'             => array(
            'slug'       => 'faq',
            'with_front' => true,
            'feeds'      => true,
        ) ,
        'has_archive'               => 'faq',
        'capability_type'           => 'post',
        'supports'                  => array(
            'title',
            'editor',
            'custom-fields',
        ),
        'taxonomies'                => array( 'gc_faq_cat' ),
        'template_lock'             => false, // Verrouiller le modèle pour empêcher les modifications
        'template'                  => array(
            array( 'core/paragraph', array( 'align' => 'left', 'level' => 4, 'content' => 'Curabitur nec lacus nec metus bibendum volutpat. Phasellus eu cursus ante. Integer pharetra purus ut diam placerat, at pellentesque lacus elementum. Fusce vel pretium elit.'))
        )
    );

    $post_type_args = wp_parse_args( $config, goldencat_get_default_post_type_config() );

    register_post_type( $post_type_name, $post_type_args);
}


function goldencat_register_faq_category() {
    $labels = array(
        'name'                          => __( 'FAQ : Catégories', 'goldencat' ),
        'singular_name'                 => __( 'Catégorie ', 'goldencat' ),
        'search_items'                  => __( 'Rechercher les catégories', 'goldencat' ),
        'all_items'                     => __( 'Toutes les catégories', 'goldencat' ),
        'popular_items'                 => __( 'Catégorie populaire', 'goldencat' ),
        'parent_item'                   => __( 'Catégorie parente', 'goldencat' ),
        'edit_item'                     => __( 'Modifier la catégorie', 'goldencat' ),
        'view_item'                     => __( 'Voir la catégorie', 'goldencat' ),
        'update_item'                   => __( 'Mettre à jour la catégorie', 'goldencat' ),
        'add_new_item'                  => __( 'Ajouter une nouvelle catégorie', 'goldencat' ),
        'new_item_name'                 => __( 'Nouvelle catégorie', 'goldencat' ),
        'menu_name'                     => __( 'Catégories', 'goldencat' ),
        'separate_items_with_commas'    => __( 'Séparer les catégories avec une virgule', 'goldencat'),
        'add_or_remove_items'           => __( 'Ajouter ou supprimer une catégorie', 'goldencat' ),
        'choose_from_most_used'         => __( 'Choisir parmi les catégories les plus utilisée', 'goldencat' ),
        'not_found'                     => __( 'Aucunes catégories trouvées', 'goldencat' ),
        'no_terms'                      => __( 'Aucune catégorie', 'goldencat' ),
        'most_used'                     => __( 'Les plus utilisées', 'goldencat' ),
    );

    $config = array(
        'labels'            => $labels,
        'public'            => true, 
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_in_rest'      => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'categorie-faq' ),
    );

    register_taxonomy( 'gc_faq_cat', 'gc_faq', $config);
}
function goldencat_cpt_faq() {
    goldencat_register_faq();
    goldencat_register_faq_category();
}
add_action('init', 'goldencat_cpt_faq');


function filter_allowed_block_types( $allowed_block_types, $block_editor_context ) {

    
    if ( $block_editor_context->post && $block_editor_context->post->post_type === 'gc_faq' ) {
        $allowed_block_types = [
          'core/paragraph',
        ];
    
      return $allowed_block_types;
    }
    
  
    return $allowed_block_types;
}
add_filter( 'allowed_block_types_all', 'filter_allowed_block_types', 10, 2 );


// /**
//  *   Managing Post Columns
//  * 
//  */
// add_filter( 'manage_gc_temoignage_posts_columns', 'goldencat_gc_temoignage_filter_posts_columns' );
// function goldencat_gc_temoignage_filter_posts_columns( $columns ) {
//     $columns = array(
//         'cb'            => $columns['cb'],
//         'customername'  => __( 'Nom de la personne', 'goldencat' ),
//         'date'          => $columns['date'],
//     );
//   $columns['customername'] = __( 'Nom de la personne', 'goldencat' );
//   return $columns;
// }


// add_action( 'manage_gc_temoignage_posts_custom_column', 'goldencat_gc_temoignage_page_column', 10, 2);
// function goldencat_gc_temoignage_page_column( $column, $post_id ) {
//   if ( 'customername' === $column ) {
//       echo '<strong>
//         <a class="row-title" href="' . get_edit_post_link( $post_id ). '">' . get_post_meta( $post_id, '_goldencat_quotes_user_name', true ). '</a>
//       </strong>';
// 	// echo get_post_meta( $post_id, '_goldencat_quotes_user_name', true );
//   }
// }
