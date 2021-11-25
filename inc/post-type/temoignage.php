<?php
/**
 * Theme Temoignage
 */

defined( 'ABSPATH' ) || exit;


function goldencat_register_temoignage() {
    $post_type_name = 'gc_temoignage';
    
    $labels = array(
        'name'                      => __( 'Témoignages', 'goldencat' ),
        'singular_name'             => __( 'Témoignage', 'goldencat' ),
        'add_new_item'              => __( 'Ajout un nouveau témoignage', 'goldencat' ),
        'view_item'                 => __( 'Voir le témoignage', 'goldencat' ),
        'edit_item'                 => __( 'Modifier le témoignage', 'goldencat' ),
        'new_item'                  => __( 'Nouveau témoignage', 'goldencat' ),
        'view_items'                => __( 'Voir les témoignages', 'goldencat' ),
        'search_items'              => __( 'Rechercher des témoignages', 'goldencat' ),
        'not_found'                 => __( 'Aucun témoignage trouvée', 'app' ),
        'not_found_in_trash'        => __( 'Aucun témoignage trouvée dans la corbeille', 'app' ),
        'all_items'                 => __( 'Tous les témoignages', 'ecrannoir'),
    );

    $config = array(
        'label'     => 'Témoignages',
        'labels'    => $labels,
        'exclude_from_search'       => true,
        'publicly_queryable'        => false,
        'rewrite'                   => false,
        'has_archive'               => false,
        'supports'                  => array(
            'editor',
            'custom-fields',
        ),
        'template_lock'             => 'all', // Verrouiller le modèle pour empêcher les modifications
        'template'                  => array(
            array( 'core/quote', 
                array(
                    'placeholder' => '«Témoignage»',
                    'quote' => '«Témoignage»',
                )
            ),
            array( 'goldencat/quote-meta', array())
        )
    );

    $post_type_args = wp_parse_args( $config, goldencat_get_default_post_type_config() );

    register_post_type( $post_type_name, $post_type_args);
}
function ecrannoir_cpt_temoignage() {
    goldencat_register_temoignage();
}
add_action('init', 'ecrannoir_cpt_temoignage');



/**
 *   Managing Post Columns
 * 
 */
add_filter( 'manage_gc_temoignage_posts_columns', 'goldencat_gc_temoignage_filter_posts_columns' );
function goldencat_gc_temoignage_filter_posts_columns( $columns ) {
    $columns = array(
        'cb'            => $columns['cb'],
        'customername'  => __( 'Nom de la personne', 'goldencat' ),
        'date'          => $columns['date'],
    );
  $columns['customername'] = __( 'Nom de la personne', 'ecrannoir' );
  return $columns;
}


add_action( 'manage_gc_temoignage_posts_custom_column', 'goldencat_gc_temoignage_page_column', 10, 2);
function goldencat_gc_temoignage_page_column( $column, $post_id ) {
  if ( 'customername' === $column ) {
      echo '<strong>
        <a class="row-title" href="' . get_edit_post_link( $post_id ). '">' . get_post_meta( $post_id, '_goldencat_quotes_user_name', true ). '</a>
      </strong>';
	// echo get_post_meta( $post_id, '_goldencat_quotes_user_name', true );
  }
}
