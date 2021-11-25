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
