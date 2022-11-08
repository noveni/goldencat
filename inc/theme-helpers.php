<?php
/**
 * Functions which enhance the theme settings
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

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
function goldencat_do_shortcode( $tag, array $atts = array(), $content = null ) {
	global $shortcode_tags;

	if ( ! isset( $shortcode_tags[ $tag ] ) ) {
		return false;
	}

	return call_user_func( $shortcode_tags[ $tag ], $atts, $content, $tag );
}


function goldencat_get_theme_color() {


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

function goldencat_label( $label_type = 'btn-product-shop' ) {
	
	$label_text = GoldenCatThemeSettings::getSettingsLabel( $label_type );

	if ( $label_text ) {
		return $label_text;
	} else {
		return false;
	}
}

function goldencat_has_sticky() {
	return GoldenCatThemeSettings::hasStickyHeader();
}


function goldencat_child_load_style_file( $styleName, $parentStyleName = '', $dependencies = array() ) {

    $style_path = get_stylesheet_directory() . '/assets/' . $styleName . '.css';
    $script_asset_path = get_stylesheet_directory() . '/assets/js/theme.asset.php';

    $script_asset_path = get_stylesheet_directory() . '/assets/js/' . $styleName . '.asset.php';
    // If an php file for the js part exist
    if ( !file_exists($script_asset_path ) ) {
        $script_asset_path = get_stylesheet_directory() . '/assets/js/theme.asset.php';
    }

    $script_asset = file_exists($script_asset_path) ? require($script_asset_path) : array('dependencies' => array(), 'version' => filemtime( $style_path ));

    // $script_asset['dependencies'] = wp_parse_args( $dependencies, $script_asset['dependencies'] );

    if ( !empty($parentStyleName) ) {
        $dependencies[] = $parentStyleName;
    }

    wp_enqueue_style( 'goldencat-child-' . $styleName . '-styles',
        get_stylesheet_directory_uri() . '/assets/' . $styleName . '.css',
		$dependencies,
		$script_asset['version'], // This only works if you have Version defined in the style header.
        'all'
	);
}

function goldencat_child_load_scripts_file( $scriptName, $parentScriptName = '', $dependencies = array() ) {

    $script_asset_path = get_stylesheet_directory() . '/assets/js/' . $scriptName . '.asset.php';

    $script_asset = file_exists($script_asset_path) ? require($script_asset_path) : array('dependencies' => array(), 'version' => 1);

    $script_asset['dependencies'] = wp_parse_args( $dependencies, $script_asset['dependencies'] );

    if ( !empty($parentScriptName) ) {
        $script_asset['dependencies'][] = $parentScriptName;
    }
    wp_register_script(
        'goldencat-child-' . $scriptName . '-script',
        get_stylesheet_directory_uri() . '/assets/js/' . $scriptName . '.js', 
        $script_asset['dependencies'], 
        $script_asset['version'], 
        true
    );
    wp_enqueue_script( 'goldencat-child-' . $scriptName . '-script' );
}
