<?php


function goldencat_render_term_of_taxonomy( $attributes ) {
    
    // ec_dump($attributes['termsToShow']);
    $terms = get_terms( array(
        'number'            => $attributes['termsToShow'],
        'taxonomy'         => $attributes['taxonomy'],
        'order'            => $attributes['order'],
		'orderby'          => $attributes['orderBy'],
        'hide_empty'       => $attributes['hideEmpty'],
    ));

    $class = 'wp-block-ecrannoirtwentyone-term-of-taxonomy';

    if ( isset( $attributes['align'] ) ) {
		$class .= ' align' . $attributes['align'];
	}
	if ( isset( $attributes['postLayout'] ) && 'grid' === $attributes['postLayout'] ) {
		$class .= ' is-grid';
	}
    if ( isset( $attributes['themeApparitionEffect'] ) ) {
		$class .= ' theme-anim-' . $attributes['themeApparitionEffect'];
	}

    $term_queried_slug = false;
    $has_term = false;
    if ( !is_admin()) {
        $term_queried = get_queried_object();
        if ($term_queried->taxonomy === 'product_tag')  {
            $has_term = true;
            $term_queried_slug = $term_queried->slug;
        }
    }
    // if (is_tax('product_cat', 'patisseries-sur-commande')) {
        // Remove book stuff
        $terms = array_filter($terms, function($term) {
            if ($term->slug ==  'fiche-recette') {
                return false;
            }
            if ($term->slug ==  'ebook') {
                return false;
            }
            if ($term->slug ==  'autres') {
                return false;
            }
            return true;
            // return $term->slug !=  'fiche-recette' || $term->slug !=  'ebook';
        });
    // }
    // ec_dump($terms);
    // Remove 
    
    ob_start(); ?>
    <div class="<?php echo $class; ?> aligncenter"> 
        <div class="term-of-taxonomy-row aligncenter">
            <a class="term-filter-tag <?php echo !$has_term ? 'active' : '' ?>" href="<?php the_permalink(); ?>"><span>Tous</span></a>
            <?php
            foreach ( $terms as $term ) :
                $term_link = esc_url( get_term_link( $term ) );
                $title = $term->name;
                if ( ! $title ) {
                    $title = __( '(no title)' );
                }
                $class = '';
                if ($term->slug === $term_queried_slug) {
                    $class .= ' active';
                }

                ?>
                <a class="term-filter-tag<?php echo $class; ?>" href="<?php echo $term_link; ?>"><span><?php echo $title; ?></span></a>
                <?php
            endforeach;
            ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Registers the `core/latest-posts` block on server.
 */
function goldencat_register_block_term_of_taxonomy() {
	register_block_type(
		'ecrannoirtwentyone/term-of-taxonomy',
		array(
            'attributes'      => array(
                'taxonomy'                      => array(
                    'type'      => 'string',
                    'default'      => 'category',
                ),
                'termsToShow'                   => array(
                    'type'      => 'number',
                    'default'   => 9,
                ),
                'displayDescription'            => array(
                    'type'      => 'boolean',
                    'default'   => true,
                ),
                'descriptionLength'             => array(
                    'type'      => 'number',
                    'default'   => 55,
                ),
                'displayImage'           => array(
                    'type'      => 'boolean',
                    'default'   => false,
                ),
                'showPostCounts'                   => array(
                    'type'      => 'boolean',
                    'default'   => false,
                ),
                'hideEmpty'         => array(
                    'type'    => 'boolean',
                    'default' => false,
                ),
                'postLayout'              => array(
                    'type'    => 'string',
                    'default' => 'list',
                ),
                'order'                   => array(
                    'type'    => 'string',
                    'default' => 'desc',
                ),
                'orderBy'                 => array(
                    'type'    => 'string',
                    'default' => 'date',
                )
            ),
			'render_callback' => 'goldencat_render_term_of_taxonomy',
		)
	);
}
add_action( 'init', 'goldencat_register_block_term_of_taxonomy' );
