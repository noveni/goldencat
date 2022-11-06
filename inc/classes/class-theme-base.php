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

          
        $this->checkComingSoon();

        new GoldenCatThemeCookies();


        
        // Setup Admin
        new GoldenCatThemeAdmin();
        // Setup theme
        add_action( 'after_setup_theme', [ $this, 'themeSetupAction'] );
        // Setup Widget
        add_action( 'widgets_init', [ $this, 'widgetSetupAction'] );
        
        // Handle Theme menu Hook and filter
        new GoldenCatThemeMenu();
        // Handle Assets Hook and filter
        new GoldenCatThemeAssets();

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
        add_action('wp_head', 'ob_start', 1, 0);
        add_action('wp_head', function () {
            $pattern = '/.*' . preg_quote(esc_url(get_feed_link('comments_' . get_default_feed())), '/') . '.*[\r\n]+/';
            echo preg_replace($pattern, '', ob_get_clean());
        }, 3, 0);

        /**
         * XML RPC Handler
         * 
         */
        add_filter( 'xmlrpc_enabled', '__return_false' );


        /**
         * PingBack Trackbacks
         */
        // Disable pingback XMLRPC method to be sure
        add_filter( 'xmlrpc_methods', function( $methods ) {
            unset($methods['pingback.ping']);
            return $methods;
        });
        // Remove pingback headers.
        add_filter( 'wp_headers', function( $headers ) {
            unset($headers['X-Pingback']);
            return $headers;
        });
        // Remove bloginfo('pingback_url').
        add_filter( 'bloginfo_url', function( $output, $show ) {
            return $show === 'pingback_url' ? '' : $output;
        }, 10, 2);
        // Disable XMLRPC pingback action.
        add_filter( 'xmlrpc_call', function( $action ) {
            if ($action === 'pingback.ping') {
                wp_die('Pingbacks are not supported', 'Not Allowed!', ['response' => 403]);
            }
        });
        // Remove trackback rewrite rules.
        add_filter( 'rewrite_rules_array', function( $rules ) {
            foreach (array_keys($rules) as $rule) {
                if (preg_match('/trackback\/\?\$$/i', $rule)) {
                    unset($rules[$rule]);
                }
            }
            return $rules;
        });

        
        // RSS Feeds
        add_filter('feed_links_show_comments_feed', '__return_false');
        remove_action('wp_head', 'feed_links_extra', 3);
        remove_action('wp_head', 'feed_links', 3);
        
        // hide and/or suppress WordPress information.
        add_filter('get_bloginfo_rss', function( $bloginfo ) {
            $default_tagline = __('Just another WordPress site'); // phpcs:ignore WordPress.WP.I18n.MissingArgDomain
            return ($bloginfo === $default_tagline) ? '' : $bloginfo;
        });
        add_filter('the_generator', '__return_false');
        remove_action('wp_head', 'rsd_link');
        remove_action('wp_head', 'wlwmanifest_link');
        remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10);
        remove_action('wp_head', 'wp_generator');
        remove_action('wp_head', 'wp_shortlink_wp_head', 10);
        remove_action('wp_head', 'rest_output_link_wp_head', 10);
        remove_action('wp_head', 'wp_oembed_add_discovery_links');
        remove_action('wp_head', 'wp_oembed_add_host_js');

        // Disable WordPress Emojis
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('admin_print_scripts', 'print_emoji_detection_script');
        remove_action('wp_print_styles', 'print_emoji_styles');
        remove_action('admin_print_styles', 'print_emoji_styles');
        remove_filter('the_content_feed', 'wp_staticize_emoji');
        remove_filter('comment_text_rss', 'wp_staticize_emoji');
        remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
    }

    /**
     * Check wether the maintenance mode is On
     */
    private function checkMaintenanceMode()
    {
        $maintenance_mode = boolval( GoldenCatThemeSettings::isActiveMaintenanceMode() );
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


    private function  checkComingSoon()
    {
        $comingsoon_mode = boolval( GoldenCatThemeSettings::isActiveComingSoon() );
        if ( $comingsoon_mode === true ) {
            add_action( 'template_redirect', [ $this, 'doComingSoon' ] );
            add_filter( 'template_include', [ $this, 'doComingSoonTemplate' ], 99 );

            remove_action( 'wp_body_open', 'wp_global_styles_render_svg_filters' );

             /**
             * Enqueue Coming Soon Assets
             */
            add_action('wp_enqueue_scripts', function ( $hook ) {

                if ( is_page( 'coming-soon' )  ) {

                    global $wp_query; 

                    GoldenCatThemeScripts::toRegisterScript('coming-soon', 'goldencat-coming-soon-scripts');
                    
                    GoldenCatThemeScripts::toEnqueueScript('coming-soon', 'goldencat-coming-soon-scripts');
                    GoldenCatThemeScripts::toEnqueueStyle('coming-soon');

                    wp_dequeue_style( 'sb_instagram_styles' );
                    wp_dequeue_style( 'wc-blocks-vendors-style' );
                    wp_dequeue_style( 'wc-blocks-style' );
                    wp_dequeue_style( 'coopcycle' );
                    wp_dequeue_style( 'wc-gc-css' );
                    wp_dequeue_style( 'wcsatt-css' );
                    wp_dequeue_style( 'goldencat-style-styles' );
                    wp_deregister_style( 'goldencat-style-styles' );
                    wp_dequeue_style( 'goldencat-woocommerce-styles' );
                    wp_deregister_style( 'goldencat-woocommerce-styles' );
                    wp_dequeue_script( 'google-recaptcha-js' );
                    wp_deregister_script( 'google-recaptcha-js' );
                    wp_dequeue_script( 'wpcf7-recaptcha-js' );
                    wp_deregister_script( 'wpcf7-recaptcha-js' );
                    wp_dequeue_script( 'goldencat-front-scripts' );
                    wp_deregister_script( 'goldencat-front-scripts' );
                };
            }, 9999);
		}


    }

    /**
     * Apply the Coming Soon mode and display message
     */
    public function doComingSoon()
    {
        global $pagenow;
	
        if( !is_user_logged_in() && !is_page("login") && !is_page("coming-soon") && $pagenow != "wp-login.php" )
        {
            wp_redirect( site_url('coming-soon') );
            exit();
        }
        
    }

    /**
     * Apply the Coming Soon mode and display message
     */
    public function doComingSoonTemplate( $template )
    {
        if ( is_page( 'coming-soon' )  ) {
            remove_action('wp_enqueue_scripts', [WC_Frontend_Scripts::class, 'load_scripts']);
            remove_action('wp_print_scripts', [WC_Frontend_Scripts::class, 'localize_printed_scripts'], 5);
            remove_action('wp_print_footer_scripts', [WC_Frontend_Scripts::class, 'localize_printed_scripts'], 5);
            $new_template = locate_template( array( 'coming-soon.php' ) );
            if ( '' != $new_template ) {
                return $new_template ;
            }
        }
        return $template;
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
        remove_action( 'wp_body_open', 'wp_global_styles_render_svg_filters' );
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
            if ( get_current_screen()->base === 'post' ) {
                // Block Editor - Post (Build separately because wp dependency @wordpress/editor cannot be enqueued within the widget block editor)
                GoldenCatThemeScripts::toEnqueueScript( 'post-editor' );
            }
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
                    max-width: 320px;
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
        $sharing_settings = get_option( 'goldencat_theme_sharing_settings' );
        $metrics_settings = get_option( 'goldencat_theme_metrics_settings' );
        $open_graph_is_active = $sharing_settings && isset( $sharing_settings['goldencat_sharing_opengraph_active'] ) ? $sharing_settings['goldencat_sharing_opengraph_active'] : false;
        $ga_id = $metrics_settings && isset( $metrics_settings['ga_measurement_id'] ) ? $metrics_settings['ga_measurement_id'] : false;

		if ($open_graph_is_active === true) {
            add_action( 'wp_head', [GoldenCatThemeMeta::class, 'print_meta'], 5);
		}
		add_action( 'wp_head', [GoldenCatThemeMeta::class, 'printFavicon'], 101);

		if ( $ga_id && $ga_id != '' ) {
			add_action( 'wp_head', function() use ( $ga_id ){
				GoldenCatThemeMeta::addAnalytics($ga_id);
			}, 102);
		}

        if ( GoldenCatThemeSettings::getHeadScripts() ) {
            $headScripts = GoldenCatThemeSettings::getHeadScripts();
            add_action( 'wp_head', function() use ( $headScripts ) {
                echo $headScripts, "\n";
            }, 110);
        }
    }
}
