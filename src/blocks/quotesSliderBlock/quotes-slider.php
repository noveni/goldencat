<?php


function goldencat_render_quotes_slider( $attributes ) {
    if (is_admin()) {
        return;
    }

    $args = array(
		'posts_per_page'   => $attributes['postsToShow'],
		'post_status'      => 'publish',
		'suppress_filters' => false,
        'order'            => 'asc',
        'post_type'        => $attributes['postType'],
	);

    $recent_posts = get_posts( $args );

    $class = 'wp-block-ecrannoirtwentyone-quotes-slider alignfull';

    if ( isset( $attributes['align'] ) ) {
		$class .= ' align' . $attributes['align'];
	}

    if ( isset( $attributes['themeApparitionEffect'] ) ) {
		$class .= ' theme-anim-' . $attributes['themeApparitionEffect'];
	}

    
    ob_start(); ?>
    <div class="<?php echo $class; ?>">
        <div class="quotes-slider">
            <div class="glide__track" data-glide-el="track">
                <div class="glide__slides">
                <?php
                foreach ( $recent_posts as $post ) :

                    $user_name = get_post_meta( $post->ID, '_ecrannoirtwentyone_quotes_user_name', true );

                    ?>

                    <div class="glide__slide is-style-ecrannoirtwentyone-testimony aligncenter">
                        <div class="quote-content"><?php echo get_the_content( null, false, $post ); ?></div>
                        <p class="quote-customer-name is-sous-titre"><?php echo $user_name; ?></p>
                    </div>
                    <?php
                endforeach;
                ?>
                </div><!-- .glide__slides -->
            </div><!-- .glide__track -->
            <div class="glide__bullets" data-glide-el="controls[nav]">
                <?php 
                $post_count = count($recent_posts);
                for ($i=0; $i < $post_count; $i++): ?>
                <button class="glide__bullet" data-glide-dir="=<?php echo $i; ?>"></button>
                <?php endfor; ?>
            </div>
        </div><!-- .quotes-slider -->
    </div><!-- .wp-block-ecrannoirtwentyone-quotes-slider -->
    <?php
    return ob_get_clean();
}

/**
 * Registers the `core/latest-posts` block on server.
 */
function goldencat_register_block_core_quotes_slider() {
	register_block_type(
		'ecrannoirtwentyone/quotes-slider',
		array(
            'attributes'      => array(
                'postType'                => array(
                    'type'  => 'string',
                    'default' => 'ec_temoignage',
                ),
                'postsToShow'             => array(
                    'type'    => 'number',
                    'default' => -1,
                ),
                'align'             => array(
                    'type'    => 'string',
                    'default' => "full",
                )
            ),
			'render_callback' => 'goldencat_render_quotes_slider',
		)
	);
}
add_action( 'init', 'goldencat_register_block_core_quotes_slider' );
