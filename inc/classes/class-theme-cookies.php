<?php
/**
 * Cookie Management
 * 
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly



class GoldenCatThemeCookies
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $settings = array();

    private $cookie_name = 'goldencat_cookie_accepted';

    public function __construct() {

        $this->settings = get_option( 'goldencat_theme_cookie_settings' );
        
        add_action( 'init', array( $this, 'init' ) );

    }

    /**
	 * Init frontend.
	 */
	public function init() {

        global $pagenow;

        
        if ( $this->settings && !is_admin() && $pagenow !== 'widgets.php' && !isset( $_GET['legacy-widget-preview'] ) ) {
            $cookie_setting_is_active = $this->settings["goldencat_cookie_settings_active"];
            if ( $cookie_setting_is_active ) {
                add_action( 'wp_enqueue_scripts', [$this, 'wp_enqueue_cookies_scripts'] );
                add_action( 'wp_footer', [ $this, 'add_cookie_cookie_panel' ], 1000 );
            }
        }
    }

    /**
	 * Load notice scripts and styles - frontend.
	 */
	public function wp_enqueue_cookies_scripts() {
        global $wp_query; 

        GoldenCatThemeScripts::toRegisterScript('cookie-settings', 'goldencat-cookie-scripts');
        wp_localize_script( 'goldencat-cookie-scripts', 'goldencat_cookies_args', array(
            'ajaxurl' => site_url() . '/wp-admin/admin-ajax.php', // WordPress AJAX
            'nonce'					=> wp_create_nonce( 'goldencat_save_cases' ),
            'cookieTime' => $this->settings["goldencat_cookie_settings_duration"],
            'cookieName' => $this->cookie_name,
            'cookiePath'			=> ( defined( 'COOKIEPATH' ) ? (string) COOKIEPATH : '' ),
            'cookieDomain'			=> ( defined( 'COOKIE_DOMAIN' ) ? (string) COOKIE_DOMAIN : '' ),

        ) );
        // GoldenCatThemeScripts::toEnqueueScript('theme', 'goldencat-front-scripts');
        wp_enqueue_script('goldencat-cookie-scripts');
        GoldenCatThemeScripts::toEnqueueStyle('cookie');
    }

    /**
	 * Cookie notice output.
	 * 
	 * @return mixed
	 */
	public function add_cookie_cookie_panel() {

        $msg = $this->settings["goldencat_cookie_settings_msg"];
		$btn_label = $this->settings["goldencat_cookie_settings_btn"];
        ob_start(); ?>
        <!-- Theme Cookie Notice -->
        <div id="goldencat-cookie" class="cookie-hidden">
            <div class="goldencat-cookie-content wide-max-width">
                <p>
                    <span><?php echo $msg; ?></span>
                </p>
                <button class="wp-block-button__link goldencat-cookie-btn cookie-accept"><?php echo $btn_label; ?></button>
            </div>
        </div>
        <!-- / Theme Cookie Notice -->
        <?php
        $content = ob_get_clean();
        echo $content;
    }


    public function cookies_set() {
        $result = isset( $_COOKIE[$this->cookie_name] );

		return $result;
	}

    public function cookies_accepted() {
        $result = isset( $_COOKIE[$this->cookie_name] ) && $_COOKIE[$this->cookie_name] === 'true';

        return $result;
    }

}
