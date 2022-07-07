<?php
/**
 * Server-side rendering of the `goldencat/covers-slider` block.
 *
 * @package WordPress
 */

/**
 * Renders the `goldencat/covers-slider` block on the server.
 *
 * @param array    $attributes Block attributes.
 * @param string   $content    Block default content.
 * @param WP_Block $block      Block instance.
 *
 * @return string Returns the covers slider
 */

function goldencat_render_covers_slider( $attributes, $content, $block ) {
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

    $class = 'wp-block-goldencat-covers-slider';

    if ( isset( $attributes['align'] ) ) {
		$class .= ' align' . $attributes['align'];
	}

    if ( isset( $attributes['themeApparitionEffect'] ) ) {
		$class .= ' theme-anim-' . $attributes['themeApparitionEffect'];
	}

    ob_start(); ?>
    <div class="<?php echo $class; ?>" <?php echo $style; ?>>
        <div class="wp-block-cover-slider__inner-container">
            <?php echo $content; ?>
        </div>
        </div>
    <?php
    return ob_get_clean();
}

function goldencat_register_block_core_covers_slider() {
	register_block_type(
		'goldencat/covers-slider',
		array(
            'attributes' => array(
                'minHeight'     => array(
                    'type'          => 'number'
                ),
                'minHeightUnit' => array(
                    'type'          => 'string',
                    'default'       => 'px'
                )
            ),
			'render_callback' => 'goldencat_render_covers_slider',
		)
	);
}
add_action( 'init', 'goldencat_register_block_core_covers_slider' );
