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


/*
 * Replace WP default login error messages
 */
function goldencat_custom_login_error_msg( $error )
{

    // we will override only the above errors and not anything else
    if ( is_int( strpos( $error, 'le mot de passe que vous avez saisi pour') ) || 
        is_int( strpos( $error, 'Adresse e-mail inconnue' ) ) || 
        is_int( strpos( $error, 'Identifiant inconnu' ) ) 
    ) {
        $error = '<strong>Erreur:</strong> Oops. Informations de connexion incorrectes.<br /><a href="' . wp_lostpassword_url() . '">Mot de passe perdu ?</a>';
    }

    return $error;
}


/**
 * Activate WordPress Maintenance Mode
 */
function goldencat_maintenance_mode() {
    if(!current_user_can('edit_themes') || !is_user_logged_in()){
        $site_title = get_bloginfo( 'name' );
        wp_die('<div style="text-align:center"><h1 style="color:black">' . $site_title . '</h1><p>Nous effectuons une maintenance. Nous serons de retour en ligne sous peu!</p></div>');
    }
}


/**
 * Redirect always to https
 */
function goldencat_redirect() {
    if (!is_ssl()) {
        wp_redirect('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], 301);
        exit();
    }
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
