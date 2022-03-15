<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! function_exists( 'goldencat_render_block_link' ) ) {

    function goldencat_render_block_link( $block_content, $block )
    {
        
        if ( isset( $block['blockName'] ) && ( in_array( $block['blockName'], array( 'core/group', 'core/column', 'core/cover' ) ) ) ) {
            $attributes = $block['attrs'];
            if ( isset( $attributes['goldencatBlockLink_href'] ) && ! empty( $attributes['goldencatBlockLink_href'] ) ) {
                $linked = '<a href="' . esc_attr( $attributes['goldencatBlockLink_href'] ) . '" class="goldencat-block-link"';
                $rel    = 'rel="';

                if ( isset( $attributes['goldencatBlockLink_opensInNewTab'] ) && $attributes['goldencatBlockLink_opensInNewTab'] ) {
                    $linked .= ' target="_blank"';
                    $rel    .= ' noreferrer noopener';
                }

                if ( isset( $attributes['goldencatBlockLink_linkNoFollow'] ) && $attributes['goldencatBlockLink_linkNoFollow'] ) {
                    $rel .= ' nofollow';
                }
                $rel    .= '"';
                $linked .= $rel;

                $linked .= '></a>';

                $reg   = '~(.*)</div>~su';
                $subst = '${1}' . $linked . '</div>';

                $block_content = preg_replace( $reg, $subst, $block_content );
            }
        }

        return $block_content;
    }
    if ( ! is_admin() ) {
        add_filter( 'render_block', 'goldencat_render_block_link', 10, 2 );
    }

}


if ( ! function_exists('goldencat_hide_block')) {

	function goldencat_hide_block($block_content, $block)
	{
		if ( ! is_admin() && ! is_search() && in_the_loop() && ( strpos( esc_url( $_SERVER[ 'REQUEST_URI' ] ), '/wp-json/' ) === false ) ) {
			$hide_block = isset( $block['attrs']['goldencatHideBlock'] )
			? $block['attrs']['goldencatHideBlock']
			: null;
	
			if ( isset( $hide_block ) ) {
				
				if ($hide_block == true) {
					return null;
				} else {
					return $block_content;
				}
			}
		}
		return $block_content;
	}

	add_filter( 'render_block', 'goldencat_hide_block', 10, 2 );
}

if ( ! function_exists( 'goldencat_hide_title' ) ) {

	/**
	 * Replace title with blank
	 *
	 * @param string $title The post title.
	 * @param int    $id The post id.
	 *
	 * @return string Returns the new title.
	 */
	function goldencat_hide_title( $title, $id = null ) {
		// phpcs:ignore
		if ( ! is_admin() && ! is_search() && in_the_loop() && ( strpos( esc_url( $_SERVER[ 'REQUEST_URI' ] ), '/wp-json/' ) === false ) ) {

			$hidden = get_post_meta( $id, '_goldencat_title_hidden', true );
			if ( $hidden ) {

				return '';
			}
		}

		return $title;
	}
	add_filter( 'the_title', 'goldencat_hide_title', 90, 2 );
}

/**
 * Print the first instance of a block in the content, and then break away.
 *
 * @param string      $block_name The full block type name, or a partial match.
 *                                Example: `core/image`, `core-embed/*`.
 * @param string|null $content    The content to search in. Use null for get_the_content().
 * @param int         $instances  How many instances of the block will be printed (max). Defaults to 1.
 *
 * @return bool Returns true if a block was located & printed, otherwise false.
 */
function goldencat_print_first_instance_of_block( $block_name, $content = null, $instances = 1 ) {
	$instances_count = 0;
	$blocks_content  = '';

	if ( ! $content ) {
		$content = get_the_content();
	}

	// Parse blocks in the content.
	$blocks = parse_blocks( $content );

	// Loop blocks.
	foreach ( $blocks as $block ) {

		// Sanity check.
		if ( ! isset( $block['blockName'] ) ) {
			continue;
		}

		// Check if this the block matches the $block_name.
		$is_matching_block = false;

		// If the block ends with *, try to match the first portion.
		if ( '*' === $block_name[-1] ) {
			$is_matching_block = 0 === strpos( $block['blockName'], rtrim( $block_name, '*' ) );
		} else {
			$is_matching_block = $block_name === $block['blockName'];
		}

		if ( $is_matching_block ) {
			// Increment count.
			$instances_count++;

			// Add the block HTML.
			$blocks_content .= render_block( $block );

			// Break the loop if the $instances count was reached.
			if ( $instances_count >= $instances ) {
				break;
			}
		}
	}

	if ( $blocks_content ) {
		echo apply_filters( 'the_content', $blocks_content ); // phpcs:ignore WordPress.Security.EscapeOutput
		return true;
	}

	return false;
}




/**
 * 
 * @param mixed $page_slug Question arguments.
 * @param int|string $page_slug   Page slug or page ID. Passed by reference.
 */
function goldencat_print_page_blocks( $page_slug, $content )
{

	// Find the first instance of block

	if (is_numeric( $page_slug )) {
		$page = get_post( $page_slug );
	} else {
		$page = get_page_by_path( $page_slug );
	}
	
	if ($page) {
		$blocks = parse_blocks( $page->post_content );

		$instances = 1;
		$instances_count = 0;
		$blocks_content  = '';
		$block_name = 'ecrannoir/theme-loop-content-block';

		// $block = ecrannoir_twenty_one_print_first_instance_of_block('ecrannoir/theme-loop-content-block', $page->post_content, 1, false);
		foreach( $blocks as $block ) {
			// Sanity check.
			if ( ! isset( $block['blockName'] ) ) {
				continue;
			}

			// Check if this the block matches the $block_name.
			$is_matching_block = false;

			// If the block ends with *, try to match the first portion.
			if ( '*' === $block_name[-1] ) {
				$is_matching_block = 0 === strpos( $block['blockName'], rtrim( $block_name, '*' ) );
			} else {
				$is_matching_block = $block_name === $block['blockName'];
			}

			if ( $is_matching_block ) {
				// Increment count.
				$instances_count++;

				// Add the block HTML.
				$blocks_content .= $content;

				// // Break the loop if the $instances count was reached.
				// if ( $instances_count >= $instances ) {
				// 	break;
				// }
			} else {
				$blocks_content .= render_block( $block );
			}
			
			// // If we find the loop-content element
			// if( 'core/paragraph' === $block['blockName'] &&  strpos($block['innerHTML'], '[loop-content]')) {
			// 	echo $content;
			// } else {
			// 	echo render_block( $block );
			// }
		}

		if ( isset( $is_matching_block ) && $is_matching_block === false ) {
			$blocks_content .= $content;
		}

		if ( $blocks_content ) {
			$the_block_content = $blocks_content; //apply_filters( 'the_content', $blocks_content );
			echo $the_block_content; // phpcs:ignore WordPress.Security.EscapeOutput
			// } else {
			// 	return $the_block_content;
			// }
			// return true;
		}
	} else {
		echo $content;
	}
}
