<?php
/**
 * Custom template tags for this theme
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly


if ( ! function_exists( 'goldencat_posted_by' ) ) {
	/**
	 * Prints HTML with meta information for the current author.
	 */
	function goldencat_posted_by() {
		$byline = sprintf(
			/* translators: %s: post author. */
			esc_html_x( 'by %s', 'post author', 'goldencat' ),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
		);

		echo '<span class="byline"> ' . $byline . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}
}

if ( ! function_exists( 'goldencat_posted_on' ) ) {
	/**
	 * Prints HTML with meta information for the current post-date/time.
	 */
	function goldencat_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf(
			$time_string,
			esc_attr( get_the_date( DATE_W3C ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( DATE_W3C ) ),
			esc_html( get_the_modified_date() )
		);

		$posted_on = sprintf(
			/* translators: %s: post date. */
			esc_html_x( 'Posted on %s', 'post date', 'goldencat' ),
			'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
		);

		echo '<span class="posted-on">' . $posted_on . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}
}


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

if ( ! function_exists( 'goldencat_get_first_term' ) ) {
	/**
	 * Displays the first term
	 */
	function goldencat_get_first_term( $taxonomy = 'category', $post = null ) {
		
        $has_term = has_term( '', $taxonomy, $post);
        if ( !$has_term) {
            return false;
        }

        $terms = get_the_terms( $post, $taxonomy );

		
		$first_term = $terms[0];
		if ( $first_term->slug === 'non-classe' ) {
			return false;
		}
		return  $first_term;
	}
}

if ( ! function_exists( 'goldencat_the_first_term_tag' ) ) {

	function goldencat_the_first_term_tag( $taxonomy = 'category', $post = null )
	{
		$term = goldencat_get_first_term( $taxonomy, $post );

		if ($term) {

			$classNames = ''; 
			
			// Get the background color
			$color = get_term_meta( $term->term_id, 'background-color-choice', true );
			// Escape value
			if ($color) {
				$classNames .= 'has-' . $color .  '-background-color';
			}

			$class = '';
			if ($classNames) {
				$class = ' class="' . esc_attr( $classNames ) . '"';
			}

			$term_name = apply_filters( 'goldencat_the_first_term_tag_term_name', esc_html($term->name), $taxonomy );
	
			// Markup
			$term = '<span '. $class .'>' . $term_name . '</span>';
			return $term;
		}
		
	}
}

if ( ! function_exists( 'goldencat_get_the_term_list' ) ) {

	function goldencat_get_the_term_list( $taxonomy = 'category', $post_id = null, $separator = '' ) {

		$post = get_post( $post_id );
		$post_id = ! empty( $post ) ? $post->ID : false;
		if ( false === $post_id ) {
			return false;
		}
		
		$terms = get_the_terms( $post_id, $taxonomy );

		if ( empty( $terms ) ) {
			return false;
		}

		$terms_tag = '';
		$i = 0;
		foreach ( $terms as $term ) {

			if ( $term->slug === 'non-classe' ) {
				continue;
			}

			if ( 0 < $i ) {
				$terms_tag .= $separator;
			}

			$classNames = 'term'; 
			
			// Get the background color
			$color = get_term_meta( $term->term_id, 'background-color-choice', true );
			// Escape value
			if ($color) {
				$classNames .= ' has-' . $color .  '-background-color';
			}

			$class = '';
			if ($classNames) {
				$class = ' class="' . esc_attr( $classNames ) . '"';
			}

			$term_name = apply_filters( 'ecrannoir_get_all_tag', esc_html($term->name), $taxonomy );

			// Markup
			$terms_tag .= '<span '. $class .'>' . $term_name . '</span>';
			++$i;
		}

		return $terms_tag;
	}
}

if ( ! function_exists( 'goldencat_block_button' ) ) {
	/**
	 * Displays an block button.
	 *
	 * @return void
	 */
	function goldencat_block_button( $args = array() ) {

		$defaults = array(
			'href' => '#',
			'label' => 'Voir',
			'style' => '',
			'extraClass' => '',
			'parent_class' => ''

		);
		$args = wp_parse_args( $args, $defaults );
		

		if ( '' !== trim( $args[ 'href' ] ) ) {
			$href = 'href="' . esc_url( $args[ 'href' ] ) . '"';
		}

		$class = 'wp-block-button';

		if ( '' !== trim( $args[ 'style' ] ) ) {
			$class .= " is-style-" . esc_attr( $args[ 'style' ] );
		}

		if ( '' !== trim( $args[ 'extraClass' ] ) ) {
			$class .= " ". esc_attr( $args[ 'extraClass' ] );
		}
		
		?>
		<div class="wp-block-buttons <?php echo esc_attr($args['parent_class']);?>">
			<div class="<?php echo esc_attr($class) ?>"><a class="wp-block-button__link" <?php echo $href; ?>><?php echo esc_html( $args[ 'label' ] ); ?></a></div>
		</div>
		<?php
	}
}
