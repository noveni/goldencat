<?php
/**
 * Custom template tags for this theme
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! function_exists( 'goldencat_post_thumbnail' ) ) {
	/**
	 * Displays an optional post thumbnail.
	 *
	 * Wraps the post thumbnail in an anchor element on index views, or a div
	 * element when on single views.
	 */
	function goldencat_post_thumbnail( $with_link = false ) {
		if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
			return;
		}

        if ( is_singular() ) :
            ?>
            
            <div class="post-thumbnail">
				<?php the_post_thumbnail( 'post-thumbnail', array( 'loading' => false ) ); ?>
                <?php if ( wp_get_attachment_caption( get_post_thumbnail_id() ) ) : ?>
					<figcaption class="wp-caption-text"><?php echo wp_kses_post( wp_get_attachment_caption( get_post_thumbnail_id() ) ); ?></figcaption>
				<?php endif; ?>
			</div><!-- .post-thumbnail -->

        <?php else : ?>
            

            <?php if ( $with_link === true ) : ?>
                <a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
            <?php endif; ?>
				<?php
					the_post_thumbnail(
						'goldencat_thumb',
						array(
							'alt' => the_title_attribute(
								array(
									'echo' => false,
								)
							),
						)
					);
				?>
            <?php if ( $with_link ) : ?>
                </a>
            <?php endif; ?>

            <?php
		endif; // End is_singular()
	}
}
