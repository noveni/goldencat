<?php

function goldencat_render_picked_post( $attributes, $content ) {
    if (is_admin()) {
        return;
    }

    if (!$attributes['postId']) {
        return;
    }

    $post = get_post( $attributes['postId']);


    $style = 'style="';

    $style .= '"';

    $class = 'wp-block-goldencat-picked-post wp-block-group';
    $thumbnail_size = 'thumbnail';

    if ( isset( $attributes['align'] ) ) {
		$class .= ' align' . $attributes['align'];
	}

    if ( isset( $attributes['themeApparitionEffect'] ) ) {
		$class .= ' theme-anim-' . $attributes['themeApparitionEffect'];
	}

    $post_link = esc_url( get_permalink( $post ) );
    $title = get_the_title( $post );
    if ( ! $title ) {
        $title = __( '(no title)' );
    }
    
    $featured_image = false;
    if ( $attributes['displayFeaturedImage'] ) {
        if (has_post_thumbnail( $post )) {
            $featured_image = get_the_post_thumbnail( $post, $thumbnail_size );
        } else {
            $featured_image = goldencat_get_image_placeholder();
        }
    }

    $excerpt = wp_trim_words( get_the_excerpt($post), 30, '&hellip;' );

    ob_start(); ?>
    <div class="<?php echo $class; ?>" <?php echo $style; ?>>
        <?php
            
        ?>
        <?php if ( $featured_image ) : ?>
            <a href="<?php echo $post_link; ?>"><figure>
            <?php echo $featured_image; ?>
            </figure></a>
        <?php endif; ?> 
        <header>
            <h3 class="post-title">
                <a href="<?php echo $post_link; ?>"><?php echo $title; ?></a>
            </h3>
        </header>
        <p><?php echo $excerpt; ?></p>
        <?php goldencat_block_button(array(
            'href' => $post_link,
            'label' => esc_html__('Je découvre', 'goldencat'),
            'style' => 'goldencat-button-link'
        )); ?>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Registers the `core/latest-posts` block on server.
 */
function goldencat_register_block_picked_post_block() {
	register_block_type(
		'goldencat/picked-post',
		array(
            'attributes' => array(
                'postId' =>                 array(
                    'type'      => 'number'
                ),
                'postType' =>               array(
                    'type'      => 'string',
                    'default'   => 'post'
                ),
                'displayPostContent' =>     array(
                    'type'      => 'boolean',
                    'default'   => true
                ),
                'excerptLength' =>          array(
                    'type'      => 'number',
                    'default'   => 55
                ),
                'displayAuthor' =>          array(
                    'type'      => 'boolean',
                    'default'   => false
                ),
                'displayPostDate' =>        array(
                    'type'      => 'boolean',
                    'default'   => true
                ),
                'displayFeaturedImage' =>   array(
                    'type'      => 'boolean',
                    'default'   => true
                ),
            ),
			'render_callback' => 'goldencat_render_picked_post',
		)
	);
}
add_action( 'init', 'goldencat_register_block_picked_post_block' );
