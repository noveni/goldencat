<?php


function ecrannoir_twenty_one_render_latest_posts( $attributes ) {
    if (is_admin()) {
        return;
    }

    $args = array(
		'posts_per_page'   => $attributes['postsToShow'],
		'post_status'      => 'publish',
		'order'            => $attributes['order'],
		'orderby'          => $attributes['orderBy'],
		'suppress_filters' => false,
        'post_type'        => $attributes['postType'],
	);

    if ( isset( $attributes['categories'] ) ) {
		$args['category__in'] = array_column( $attributes['categories'], 'id' );
	}

    $recent_posts = get_posts( $args );

    $class = 'wp-block-ecrannoirtwentyone-latest-posts';

    if ( isset( $attributes['align'] ) ) {
		$class .= ' align' . $attributes['align'];
	}
	if ( isset( $attributes['postLayout'] ) && 'grid' === $attributes['postLayout'] ) {
		$class .= ' is-grid';
	}
    if ( isset( $attributes['themeApparitionEffect'] ) ) {
		$class .= ' theme-anim-' . $attributes['themeApparitionEffect'];
	}

    
    ob_start(); ?>
    <div class="<?php echo $class; ?>"> 
    <?php
    foreach ( $recent_posts as $post ) :
        $post_link = esc_url( get_permalink( $post ) );
        $title = get_the_title( $post );
        if ( ! $title ) {
            $title = __( '(no title)' );
        }
        
        $featured_image = false;
        if ( $attributes['displayFeaturedImage'] ) {
            if (has_post_thumbnail( $post )) {
                $featured_image = get_the_post_thumbnail( $post, 'post-thumbnail' );
            } else {
                $featured_image = ecrannoir_twenty_one_get_image_placeholder();
            }
        }
        ?>
        <article>
            <?php if ( $featured_image ) : ?>
                <a href="<?php echo $post_link; ?>"><figure>
                <?php echo $featured_image; ?>
                </figure></a>
            <?php endif; ?> 
            <header>
                <h3 class="post-title h2">
                    <a href="<?php echo $post_link; ?>"><?php echo $title; ?></a>
                </h3>
            </header>
            <?php ecrannoir_twenty_one_block_button($post_link, esc_html__('Je découvre', 'ecrannoirtwentyone')); ?>
        </article>
        <?php
    endforeach;
    ?>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Registers the `core/latest-posts` block on server.
 */
function ecrannoir_twenty_one_register_block_core_latest_posts() {
	register_block_type(
		'ecrannoirtwentyone/latest-posts',
		array(
            'attributes'      => array(
                'categories'              => array(
                    'type' => 'array',
                ),
                'postType'                => array(
                    'type'  => 'string',
                    'default' => 'post',
                ),
                'postsToShow'             => array(
                    'type'    => 'number',
                    'default' => 5,
                ),
                'displayPostContent'      => array(
                    'type'    => 'boolean',
                    'default' => false,
                ),
                'excerptLength'           => array(
                    'type'    => 'number',
                    'default' => 55,
                ),
                'displayAuthor'         => array(
                    'type'    => 'boolean',
                    'default' => false,
                ),
                'displayPostDate'         => array(
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
                ),
                'displayFeaturedImage'      => array(
                    'type'  => 'boolean',
                    'default' => true,
                )
            ),
			'render_callback' => 'ecrannoir_twenty_one_render_latest_posts',
		)
	);
}
add_action( 'init', 'ecrannoir_twenty_one_register_block_core_latest_posts' );
