<?php

function goldencat_render_picked_term( $attributes, $content ) {
    if (!isset($attributes['termId'])) {
        return;
    }

    $term = get_term( $attributes['termId'], $attributes['taxonomy']);


    $style = 'style="';

    $style .= '"';

    $class = 'wp-block-ecrannoirtwentyone-picked-term';

    if ( isset( $attributes['align'] ) ) {
		$class .= ' align' . $attributes['align'];
	}

    if ( isset( $attributes['themeApparitionEffect'] ) ) {
		$class .= ' theme-anim-' . $attributes['themeApparitionEffect'];
	}

    $term_link = esc_url( get_term_link( $term ) );
    $title = $term->name;
        if ( ! $title ) {
            $title = __( '(no title)' );
        }
    
    $term_image = false;
    if ( $attributes['displayImage'] ) {
        $image_id = get_term_meta( $term->term_id, 'ecrannoirtwentyone-img', true );
        if( $image = wp_get_attachment_image_src( $image_id ) ) {
            $term_image = '<img src="' . $image[0] . '" />';
        } else {
            $term_image = goldencat_get_image_placeholder();
        }
    }

    ob_start(); ?>
    <div class="<?php echo $class; ?>" <?php echo $style; ?>>
        <?php
            
        ?>
        <article>
            <?php if ( $term_image ) : ?>
                <a href="<?php echo $term_link; ?>"><figure>
                <?php echo $term_image; ?>
                </figure></a>
            <?php endif; ?> 
            <header>
                <h3 class="post-title h2">
                    <a href="<?php echo $term_link; ?>"><?php echo $title; ?></a>
                </h3>
            </header>
            <?php if ($attributes['displayDescription']): ?>
                <?php echo $term->description; ?>
            <?php endif; ?> 
            <?php goldencat_block_button(array(
                'href' => $term_link,
                'label' => esc_html__('Je découvre', 'goldencat')
            )); ?>
        </article>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Registers the `core/latest-posts` block on server.
 */
function goldencat_register_block_picked_term_block() {
	register_block_type(
		'ecrannoirtwentyone/picked-term',
		array(
            'attributes' => array(
                'termId' =>                 array(
                    'type'      => 'number'
                ),
                'taxonomy' =>               array(
                    'type'      => 'string',
                    'default'   => 'category'
                ),
                'displayDescription'            => array(
                    'type'      => 'boolean',
                    'default'   => true,
                ),
                'descriptionLength'             => array(
                    'type'      => 'number',
                    'default'   => 55,
                ),
                'displayImage' =>   array(
                    'type'      => 'boolean',
                    'default'   => true
                ),
                'showPostCounts' =>   array(
                    'type'      => 'boolean',
                    'default'   => false
                ),
            ),
			'render_callback' => 'goldencat_render_picked_term',
		)
	);
}
add_action( 'init', 'goldencat_register_block_picked_term_block' );
