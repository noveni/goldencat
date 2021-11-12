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
     * Determines whether a class has already been instanciated.
     *
     * @access private
     */
	private static $instance = null;

    public function __construct( array $theme_configuration = array())
    {

        $defaults = array(
            'disable_comment' => false,
            'clean' => false,
            'menus' => array(
                'primary'   => __( 'Primary Menu', 'goldencat' ),
                'footer'    => __( 'Footer Menu', 'goldencat' ),
            ),
            'widgets' => array(),
            'editor_styles' => './assets/editor.css',
            'image_sizes' => array(
                'goldencat_thumb' => array(
                    'nice_name' => __('Vignette du thème'),
                    'width' => 300,
                    'height' => 400,
                    'crop' => true
                )
            )
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

        // Redirect to https
        add_action('template_redirect', [ $this, 'redirectHttps'] );

        // Clean All Useless Stuff
        if ($this->theme_configuration['clean'] === true) {
            add_action('after_setup_theme', [ $this, 'cleanWordpressAction' ]);
        }

        // Check the maintenance Mode
        $this->checkMaintenanceMode();

        
        // Setup Admin
        new GoldenCatThemeAdmin();
        // Setup theme
        add_action( 'after_setup_theme', [ $this, 'themeSetupAction'] );
        // Setup Widget
        add_action( 'widgets_init', [ $this, 'widgetSetupAction'] );

        // Theme global Filter
        $this->globalFilters();

        // Clean All Useless Stuff
        if ($this->theme_configuration['disable_comment'] === true) {
            $this->disableComment();
        }

        $this->enqueueScripts();

        $this->imageSizes();

        $this->addMeta();
        
    }

    /**
     * Redirect always to https
     */
    public function redirectHttps()
    {
        if (!is_ssl()) {
            wp_redirect('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], 301);
            exit();
        }
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
     * Check wether the maintenance mode is On
     */
    private function checkMaintenanceMode()
    {
        $maintenance_mode = boolval( get_option( 'goldencat_theme_maintenance_on', false ) ?? false);
		if ($maintenance_mode === true) {
			add_action( 'get_header', [ $this, 'doMaintenanceMode' ] );
		}
    }

    /**
     * Apply the maintenance mode and display message
     */
    public function doMaintenanceMode()
    {
        if ( !current_user_can('edit_themes') || !is_user_logged_in() ){
            $site_title = get_bloginfo( 'name' );
            wp_die('<div style="text-align:center"><h1 style="color:black">' . $site_title . '</h1><p>Nous effectuons une maintenance. Nous serons de retour en ligne sous peu!</p></div>');
        }
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

        /**
         * Add post-formats support.
         */
        add_theme_support(
            'post-formats',
            array(
                'link',
                'aside',
                'gallery',
                'image',
                'quote',
                'status',
                'video',
                'audio',
                'chat',
            )
        );

        /*
         * Enable support for Post Thumbnails on posts and pages.
         *
         * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
         */
        add_theme_support( 'post-thumbnails' );
        set_post_thumbnail_size( 1568, 9999 );

        register_nav_menus( $this->theme_configuration['menus'] );

        /*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
                'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
				'navigation-widgets',
			)
        );

        // Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		// Add support for Block Styles.
		add_theme_support( 'wp-block-styles' );
        
        // Add support for editor styles.
        add_theme_support( 'editor-styles' );

        add_editor_style(  $this->theme_configuration['editor_styles'] );

        // Add support for responsive embedded content.
        add_theme_support( 'responsive-embeds' );

        remove_theme_support( 'core-block-patterns' );
    }

    public function widgetSetupAction()
    {
        $sidebars = $this->theme_configuration['widgets'];
        $config = [
            'before_title'  => '',
            'after_title'   => '',
            'before_widget' => '<div class="widget %2$s">',
            'after_widget'  => '</div>',
        ];

        if (!empty($sidebars)) {
            foreach ($sidebars as $sidebar) {
                register_sidebar(
                    array_merge(
                        $config,
                        $sidebar,
                    )
                );
            }
        }
    }

    /**
     * Add global Filter
     */
    private function globalFilters()
    {
        add_filter( 'wp_revisions_to_keep', function( $num, $post ) {

			if (defined('GOLDENCAT_POST_REVISIONS')) {
				$num = GOLDENCAT_POST_REVISIONS;// Limit revisions otherwise
			}
			
			return $num;
		}, 10, 2 );
    }

    /**
     * Disable Comment
     */
    private function disableComment()
    {
        add_action( 'widgets_init', function() {

            unregister_widget( 'WP_Widget_Recent_Comments' );
            /**
             * The widget has added a style action when it was constructed - which will
             * still fire even if we now unregister the widget... so filter that out
             */
            add_filter( 'show_recent_comments_widget_style', '__return_false' );
        } );

        add_filter( 'wp_headers', function( $headers ) {
            unset( $headers['X-Pingback'] );
            return $headers;
        } );

        add_action( 'template_redirect', function() {
            if ( is_comment_feed() ) {
                wp_die( __( 'Comments are closed.', 'disable-comments' ), '', array( 'response' => 403 ) );
            }
        }, 9 );   // before redirect_canonical.

        function comment_disable_admin_bar()
        {
            if ( is_admin_bar_showing() ) {
                // Remove comments links from admin bar.
                remove_action( 'admin_bar_menu', 'wp_admin_bar_comments_menu', 60 );
                if ( is_multisite() ) {
                    add_action( 'admin_bar_menu', function( $wp_admin_bar ) {
                        // We have no way to know whether the plugin is active on other sites, so only remove this one.
                        $wp_admin_bar->remove_menu( 'blog-' . get_current_blog_id() . '-c' );
                    }, 500);
                }
            }
        }
        // Admin bar filtering has to happen here since WP 3.6.
        add_action( 'template_redirect', 'comment_disable_admin_bar' );
        add_action( 'admin_init', 'comment_disable_admin_bar' );

        // Disable Comments REST API Endpoint
        add_filter( 'rest_endpoints', function( $endpoints ) {
            unset( $endpoints['comments'] );
            return $endpoints;
        } );

        // Remove Comments Menu if it's disabled
        add_action('wp_before_admin_bar_render', function() {
            global $wp_admin_bar;
            $wp_admin_bar->remove_node('comment');
        }); 

        function filter_admin_menu() {
            global $pagenow;
            if ( $pagenow == 'comment.php' || $pagenow == 'edit-comments.php' )
                wp_die( __( 'Comments are closed.' ), '', array( 'response' => 403 ) );

            remove_menu_page( 'edit-comments.php' );
        }

        function admin_css(){
            echo '<style>
                #dashboard_right_now .comment-count,
                #dashboard_right_now .comment-mod-count,
                #latest-comments,
                #welcome-panel .welcome-comments,
                .user-comment-shortcuts-wrap {
                    display: none !important;
                }
            </style>';
        }
    

        if( is_admin() ) {
            add_action( 'admin_menu', 'filter_admin_menu', 9999 );	// do this as late as possible
            add_action( 'admin_print_styles-index.php', 'admin_css' );
            add_action( 'admin_print_styles-profile.php', 'admin_css' );
            add_action( 'wp_dashboard_setup', function() {
                remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
            } );
            add_filter( 'pre_option_default_pingback_flag', '__return_zero' );
        }
    }

    private function enqueueScripts()
    {
        /**
		 * Enqueue front-end assets.
		 */
		add_action('wp_enqueue_scripts', function ( $hook ) {
            global $wp_query; 

			GoldenCatThemeScripts::toRegisterScript('theme', 'goldencat-front-scripts');
            wp_localize_script( 'goldencat-front-scripts', 'goldencat_theme_params', array(
                'ajaxurl' => site_url() . '/wp-admin/admin-ajax.php', // WordPress AJAX
                'posts' => json_encode( $wp_query->query_vars ), // everything about your loop is here
                'current_page' => get_query_var( 'paged' ) ? get_query_var('paged') : 1,
                'max_page' => $wp_query->max_num_pages
            ) );
            // GoldenCatThemeScripts::toEnqueueScript('theme', 'goldencat-front-scripts');
            wp_enqueue_script('goldencat-front-scripts');
			wp_script_add_data('goldencat-front-scripts', 'async', true );
			GoldenCatThemeScripts::toEnqueueStyle('style');
		});

        
        /**
		 * Enqueue admin assets.
		 */
		add_action('admin_enqueue_scripts', function($hook) {
            if ( ! did_action( 'wp_enqueue_media' ) ) {
                wp_enqueue_media();
            }
			GoldenCatThemeScripts::toEnqueueScript('admin');
			GoldenCatThemeScripts::toEnqueueStyle('admin');
        });
        
        /**
		 * Enqueue editor assets.
		 */
		add_action('enqueue_block_editor_assets', function($hook) {
            GoldenCatThemeScripts::toEnqueueScript( 'editor' );
            GoldenCatThemeScripts::toEnqueueStyle( 'admin-editor' );
        });
        

        /**
		 * Enqueue Login Assets
		 */
		add_action( 'login_enqueue_scripts', function() {

			$style = function() {
				?>
				<style type="text/css">
				#login h1 a, .login h1 a {
					background-image: url(<?php echo get_template_directory_uri() . '/assets/img/logo-theme-author.svg'; ?>);
					background-size: 80%;
					height: 100px;
					width: 100%;
				}
				</style>
				<?php
			};
			$style();
				
		});
    }

    /**
     * Add Image sizes set in config 
     * 
     */
    public function imageSizes()
    {
        $imageSizes = $this->theme_configuration['image_sizes'];

        if ( !empty( $imageSizes ) ) {
            add_action( 'after_setup_theme', [ $this, 'addImageSizes' ] );
            add_filter( 'image_size_names_choose', [ $this, 'addImageSizesNames' ] );
        }
    }

    public function addImageSizes()
    {
        $imageSizes = $this->theme_configuration['image_sizes'];

        foreach ($imageSizes as $slug => $size) {
            $crop = isset( $size['crop'] ) ? $size['crop'] : false; // (default not cropped)
            $width = isset( $size['width'] ) ? $size['width'] : 0;
            $height = isset( $size['height'] ) ? $size['height'] : 0;
            add_image_size( $slug, $width, $height, $crop ); 
        }
    }

    public function addImageSizesNames( $sizes )
    {
        $imageSizes = $this->theme_configuration['image_sizes'];

        $parsed_sizes = []; 
        // array_map( function($size) {
        //     return isset( $size['nice_name'] ) ? $size['nice_name'] : 'theme' ;
        // }, $imageSizes );
        foreach ($imageSizes as $slug => $size) {
            $parsed_sizes[$slug] =  isset( $size['nice_name'] ) ? $size['nice_name'] : $slug;
        }

        return array_merge( $sizes, $parsed_sizes );
    }

     /**
     * Add Meta to Theme
     */
    public function addMeta()
    {
        $open_graph = boolval( get_option( 'goldencat_theme_opengraph_on', true ) ?? false);
		if ($open_graph === true) {
            add_action( 'wp_head', [GoldenCatThemeMeta::class, 'print_meta'], 5);
		}
		add_action( 'wp_head', [GoldenCatThemeMeta::class, 'printFavicon'], 101);

        $ga_id = get_option( 'goldencat_theme_ga_measurement_id', false);
		if ( $ga_id && $ga_id != '' ) {
			add_action( 'wp_head', function() use ( $ga_id ){
				GoldenCatThemeMeta::addAnalytics($ga_id);
			}, 102);
		}
    }
}
