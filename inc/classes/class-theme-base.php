<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly



class GoldenCatThemeBase
{

     /**
     * The theme configuration
     */
    protected $theme_configuration = array();

    /**
     * Is the theme need to be clean
     */
    private $clean_wordpress = false;


    /**
     * Is the theme need to disable comment
     */
    private $disable_comment = false;

    /**
     * Determines whether a class has already been instanciated.
     *
     * @access private
     */
	private static $instance = null;

    public function __construct( array $theme_configuration = array())
    {

        $defaults = array(
            'theme_content_width' => 780,
            'disable_comment' => false,
            'clean' => false,
            'menus' => array(
                'primary'   => __( 'Header Menu', 'ecrannoirtwentyone' ),
                'mobile'    => __( 'Mobile Menu', 'ecrannoirtwentyone' ),
            ),
            'widgets' => array(),
            'theme_json_config' => array()
        );

        $this->theme_configuration = wp_parse_args( $theme_configuration, $defaults );

        $this->init();
    }

    public static function instance()
	{
		$class = get_called_class();
        if ( ! isset(self::$instance[$class]) ) {
            self::$instance[$class] = new $class();
        }

        return self::$instance[$class];
    }

    public function init() {

        add_action('template_redirect', 'goldencat_redirect' );

        // Clean All Useless Stuff
        if ($this->theme_configuration['clean'] === true) {
            add_action('after_setup_theme', [ $this, 'cleanWordpressAction' ]);
        }

        // Check the maintenance Mode
        $this->checkMaintenanceMode();


        // Setup Admin
        $this->setupAdmin();
        
        add_action( 'after_setup_theme', [ $this, 'themeSetupAction'] );
        
    }

    /**
     * Remove Script, style feed rss etc
     */
    public function cleanWordpressAction()
    {
        remove_action('wp_head', 'feed_links_extra', 3);
        add_action('wp_head', 'ob_start', 1, 0);
        add_action('wp_head', function () {
            $pattern = '/.*' . preg_quote(esc_url(get_feed_link('comments_' . get_default_feed())), '/') . '.*[\r\n]+/';
            echo preg_replace($pattern, '', ob_get_clean());
        }, 3, 0);
        remove_action('wp_head', 'rsd_link');
        remove_action('wp_head', 'wlwmanifest_link');
        remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10);
        remove_action('wp_head', 'wp_generator');
        remove_action('wp_head', 'wp_shortlink_wp_head', 10);
        // Emojis
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('admin_print_scripts', 'print_emoji_detection_script');
        remove_action('wp_print_styles', 'print_emoji_styles');
        remove_action('admin_print_styles', 'print_emoji_styles');
        remove_action('wp_head', 'wp_oembed_add_discovery_links');
        remove_action('wp_head', 'wp_oembed_add_host_js');
        remove_action('wp_head', 'rest_output_link_wp_head', 10);
        remove_filter('the_content_feed', 'wp_staticize_emoji');
        remove_filter('comment_text_rss', 'wp_staticize_emoji');
        remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
    }

    /**
     * Setup the theme default and register support
     * 
     * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
     * 
     */
    public function themeSetupAction()
    {
        /**
         * Loads our translations before loading anything else
         */
        if( is_dir( get_stylesheet_directory() . '/languages' ) ) {
            $path = get_stylesheet_directory() . '/languages';
        } else {
            $path = get_template_directory() . '/languages'; 
        }
        load_theme_textdomain('goldencat', $path);

        /*
        * Let WordPress manage the document title.
        * This theme does not use a hard-coded <title> tag in the document head,
        * WordPress will provide it for us.
        */
        add_theme_support( 'title-tag' );
    }


    private function setupAdmin()
    {

        // if (is_admin()) {
        new GoldenCatThemeSettings();
            // require get_template_directory() . '/inc/theme-admin.php';
		// }
    }

    /**
     * Check wether the maintenance mode is On
     */
    private function checkMaintenanceMode()
    {
        
        $maintenance_mode = boolval( get_option( 'goldencat_theme_maintenance_on', false ) ?? false);
		if ($maintenance_mode === true) {
			add_action('get_header', 'goldencat_maintenance_mode');
		}
    }



}
