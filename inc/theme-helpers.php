<?php
/**
 * Functions which enhance the theme settings
 *
 */

/**
 * Debug nice output
 */
function ec_dump($var, $die = false)
{
    echo "<pre>";
    var_dump($var);
    echo "</pre>";
    if ($die === true)
        wp_die();
}


/**
 * Call a shortcode function by tag name.
 *
 * @since  1.4.6
 *
 * @param string $tag     The shortcode whose function to call.
 * @param array  $atts    The attributes to pass to the shortcode function. Optional.
 * @param array  $content The shortcode's content. Default is null (none).
 *
 * @return string|bool False on failure, the result of the shortcode on success.
 */
function ecrannoirtwentyone_do_shortcode( $tag, array $atts = array(), $content = null ) {
	global $shortcode_tags;

	if ( ! isset( $shortcode_tags[ $tag ] ) ) {
		return false;
	}

	return call_user_func( $shortcode_tags[ $tag ], $atts, $content, $tag );
}


function ecrannoir_get_theme_color() {


    $theme_json = WP_Theme_JSON_Resolver::get_theme_data();
    $editor_settings['__experimentalFeatures'] = $theme_json->get_settings();
	// These settings may need to be updated based on data coming from theme.json sources.
	if ( isset( $editor_settings['__experimentalFeatures']['color']['palette'] ) ) {
		$colors_by_origin          = $editor_settings['__experimentalFeatures']['color']['palette'];
		$editor_settings['colors'] = isset( $colors_by_origin['user'] ) ?
			$colors_by_origin['user'] : (
				isset( $colors_by_origin['theme'] ) ?
					$colors_by_origin['theme'] :
					$colors_by_origin['core']
			);
	}

    if ( isset($editor_settings['colors']) ) {
        return $editor_settings['colors'];
    }
}
