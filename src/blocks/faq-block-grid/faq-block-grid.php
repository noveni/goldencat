<?php

// Action ecrannoir_ajax_filter_gc_faq_cat_action

function ecrannoir_faq_filter_category( $taxonomy = 'gc_faq_cat', $post_type = 'gc_faq' ) {

    $js_selector = 'ecrannoir_ajax_filter_' . $taxonomy;
    $label_all_item = 'All';
    $block_filter_wrapper_class = 'ecrannoir-list-filter ecrannoir-faq-filter';

    $terms = get_terms( array(
		'taxonomy' => 'gc_faq_cat',
		'orderby'    => 'count',
		'order' => 'DESC',
		'hide_empty' => true,
	));

    if ( empty( $terms ) ) {
		return false;
	}

    // First Item "All"
    $input_id_all = $taxonomy . '-' . 'all';
    $slug_all = 'all';
    $input_name = $taxonomy . '-term-query-filter';

    ob_start(); ?>
    <div class="wp-block-group aligncenter <?php echo $block_filter_wrapper_class; ?>" data-taxonomy="<?php echo $taxonomy; ?>" data-post-type="<?php echo $post_type; ?>" data-selector="<?php echo $js_selector; ?>" data-action="<?php echo $js_selector; ?>_action">
        <input type="hidden" class="taxo-collecteur" id="collected-<?php echo $taxonomy; ?>" />
        <ul>
            <li>
                <input type="checkbox" id="<?php echo $input_id_all; ?>" checked class="<?php echo $js_selector; ?>" value="all" name="<?php echo $input_name; ?>" data-taxonomy="<?php echo $taxonomy ?>" data-slug="<?php echo $slug_all; ?>">
                <label class="" for="<?php echo $taxonomy; ?>-all">
                    <?php echo $label_all_item; ?>
                </label>
            </li>
        <?php foreach ( $terms as $term ): 

            $input_id = $taxonomy . '-' . $term->term_id;
            $slug = $taxonomy . '-' . $term->slug;
            
            ?>
            <li>
                <input type="checkbox" id="<?php echo $input_id ?>" class="<?php echo $js_selector; ?>" value="<?php echo $term->term_id ?>" name="<?php echo $input_name ?>" data-taxonomy="<?php echo $taxonomy ?>" data-slug="<?php echo $slug; ?>">
                <label for="<?php echo $input_id ?>">
					<?php echo $term->name; ?>
				</label>
            </li>
        <?php endforeach; ?>
        </ul>
    </div>
    <?php echo ob_get_clean();
}


function ecrannoir_render_faq_block_grid( $attributes ) {
    if (is_admin()) {
        return;
    }

    $args = array(
		'posts_per_page'   => -1, //$attributes['postsToShow'],
		'post_status'      => 'publish',
		'suppress_filters' => false,
        'order'            => 'asc',
        'post_type'        => $attributes['postType'],
	);

    $post_query = new WP_Query($args);

    // $recent_posts = get_posts( $args );

    $class = 'wp-block-ecrannoir-faq-block-grid';

    if ( isset( $attributes['align'] ) ) {
		$class .= ' align' . $attributes['align'];
	}

    if ( isset( $attributes['themeApparitionEffect'] ) ) {
		$class .= ' theme-anim-' . $attributes['themeApparitionEffect'];
	}

    $showFilter = isset( $attributes['showFilter'] ) ? $attributes['showFilter'] : false;

    $is_grid = false;
    
    // $post_to_display = $recent_posts;


    // We need to display the cat
    
    ob_start(); ?>
    <div class="<?php echo $class; ?>">
        <?php if ($showFilter): ?>
            <?php ecrannoir_faq_filter_category( 'gc_faq_cat', $attributes['postType'] );
        endif;
        ?>
        <div class="faq-block-grid wp-block-group <?php echo $showFilter ? 'filter-content-to-refresh' : ''; ?> loadmore-content-to-refresh">
            <?php
            if($post_query->have_posts()) :
                while($post_query->have_posts()) : $post_query->the_post();
                ?>
                <div class="faq-block-item">
                    <?php the_content( null, false, get_the_ID() ); ?>
                </div>

                <?php
                endwhile;
            endif;
            ?>

        </div><!-- .faq-block-grid -->
        <?php if (  $post_query->max_num_pages > 1 ):
            goldencat_block_button(array(
                'href' => '',
                'label' => 'Charger plus d’articles',
                'extraClass' => 'theme-loadmore-button',
                'parentClass' => 'aligncenter'
            ));
        endif; ?>
    </div><!-- .wp-block-ecrannoir-faq-block-grid -->
    <?php
    return ob_get_clean();
}

/**
 * Registers the `core/latest-posts` block on server.
 */
function ecrannoir_register_block_core_faq_block_grid() {
	register_block_type(
		'ecrannoir/faq-block-grid',
		array(
            'attributes'      => array(
                'postType'                => array(
                    'type'  => 'string',
                    'default' => 'gc_faq',
                ),
                'postsToShow'             => array(
                    'type'    => 'number',
                    'default' => -1,
                ),
                'align'             => array(
                    'type'    => 'string',
                    'default' => "default",
                ),
                'postLayout'             => array(
                    'type'    => 'string',
                    'default' => "list",
                ),
                'showFilter'      => array(
                    'type'  => 'boolean',
                    'default' => true,
                )

            ),
			'render_callback' => 'ecrannoir_render_faq_block_grid',
		)
	);
}
add_action( 'init', 'ecrannoir_register_block_core_faq_block_grid' );


function ecrannoir_faq_block_grid_ajax () {
    $taxonomies = $_POST['the_taxos'];
    $the_selected_taxo = $_POST['the_selected'];
    $post_type = $_POST['postType'];
    $paged = ($_POST['paged'])? $_POST['paged'] : 1;

    $args = [
		'post_type' => $post_type,
		'posts_per_page' => -1,
		'post_status'  => 'publish',
        'suppress_filters' => false,
		'orderby'        => 'date',
        'order'          => 'desc',
        // 'paged'          => $paged
	];

    foreach ($the_selected_taxo as $taxo_name => $taxo_ids) {
        $taxonomy_query[] = [
            'taxonomy'      => $taxo_name,
            'field'		    => 'term_id',
            'terms'         => $taxo_ids,
            'operator'      => 'IN'
        ];
    }

    if (count($taxonomy_query) > 1) {
        $taxonomy_query['relation'] = 'OR';
    } 
    $args['tax_query'] = $taxonomy_query;



    // Variable to call WP_Query. 
	$post_query = new WP_Query( $args ); 
	
	if ( $post_query->have_posts() ) : 
        ob_start();
		// Start the Loop 
		while ( $post_query->have_posts() ) : $post_query->the_post(); 
			?>
			<div class="faq-block-item">
                <?php the_content( null, false, get_the_ID() ); ?>
            </div>
			<?php
		// End the Loop 
        endwhile;
        $output = ob_get_contents();
        ob_end_clean();
	else: 
        // If no posts match this query, output this text. 
        $output = '<p class="has-text-align-center">' . __( 'Désolé, aucun article ne correspond relatifs', 'ecrannoir' ) . '</p>';
	endif; 

    $result = [
        'html' => $output,
    ];
    
    echo json_encode($result);
    wp_reset_postdata();
    exit;
}

add_action( 'wp_ajax_ecrannoir_ajax_filter_gc_faq_cat_action', 'ecrannoir_faq_block_grid_ajax' );
add_action( 'wp_ajax_nopriv_ecrannoir_ajax_filter_gc_faq_cat_action', 'ecrannoir_faq_block_grid_ajax' );
