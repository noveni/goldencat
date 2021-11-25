<?php


function ecrannoir_twenty_one_render_cover_slider( $attributes, $content ) {
    if (is_admin()) {
        return;
    }

    $style = 'style="';

    if ( isset ($attributes['minHeight'])) {
        $minHeightUnit = $attributes['minHeightUnit'];
        $minHeightProp = $attributes['minHeight'];
        $minHeight = $minHeightUnit
            ? $minHeightProp . $minHeightUnit
            : $minHeightProp;
        if ($minHeight) {
            $style .= "min-height: $minHeight;";
        }
    }

    $style .= '"';

    $class = 'wp-block-ecrannoirtwentyone-cover-slider';

    if ( isset( $attributes['align'] ) ) {
		$class .= ' align' . $attributes['align'];
	}

    if ( isset( $attributes['themeApparitionEffect'] ) ) {
		$class .= ' theme-anim-' . $attributes['themeApparitionEffect'];
	}

    if ( isset( $attributes['ecrannoirtwentyoneNoPadding'] ) ) {
		$class .= $attributes['ecrannoirtwentyoneNoPadding'] === true ? ' ecrannoirtwentyone-no-padding' : '';
	}

    
    ob_start(); ?>
    <div class="<?php echo $class; ?>" <?php echo $style; ?>>
        <div class="wp-block-cover-slider__inner-container">
            <?php echo $content; ?>
        </div>
        <div class="block-cover-slider-wrapper">
        <?php if( !empty($attributes['imageIds'])) :?>
            <div class="block-cover-slider-gallery">
                <div class="glide__track" data-glide-el="track">
                    <div class="glide__slides">
                        <?php foreach ($attributes['imageIds'] as $id): ?>
                        <div class="glide__slide block-cover-slider-image">
                            <figure>
                                <img src="<?php echo wp_get_attachment_url($id); ?>" />
                            </figure>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div><!-- .glide__track -->
            </div>
        <?php endif; ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Registers the `core/latest-posts` block on server.
 */
function ecrannoir_twenty_one_register_block_core_cover_slider() {
	register_block_type(
		'ecrannoirtwentyone/cover-slider',
		array(
            'attributes' => array(
                'imageIds'  => array(
                    'type'          => 'array',
                ),
                'minHeight'     => array(
                    'type'          => 'number'
                ),
                'minHeightUnit' => array(
                    'type'          => 'string',
                    'default'       => 'px'
                )
            ),
			'render_callback' => 'ecrannoir_twenty_one_render_cover_slider',
		)
	);
}
add_action( 'init', 'ecrannoir_twenty_one_register_block_core_cover_slider' );
