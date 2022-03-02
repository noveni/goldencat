<?php
/**
 * Custom Settings Page
 * 
 * Inspired and doc
 * https://wholesomecode.ltd/wordpress/create-settings-page-wordpress-gutenberg-components/
 * https://www.codeinwp.com/blog/plugin-options-page-gutenberg/
 * https://wpshop.io/blog/wp-shopify-3-0-progress-update-2-admin-settings/
 *
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class GoldenCatThemeSettings
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $settings;

    public function __construct() {
        add_action( 'admin_menu', [$this, 'add_menu_item']);
        add_action( 'admin_init', [$this, 'register_settings'], 10);
        add_action( 'rest_api_init', [$this, 'register_settings']);
        // add_action( 'admin_enqueue_scripts', [$this, 'enqueuePageAssets'], 10 );
    }

    public function add_menu_item()
    {
        $page_hook_suffix = add_menu_page(
            'Theme Settings', // page <title>Title</title>
            'Theme Settings', // menu link text
            'manage_options', // capability to access the page
            'goldencat-theme-settings', // page URL slug
            array( $this, 'settings_page' ), // callback function with content
        );

        add_action( "admin_print_scripts-{$page_hook_suffix}", [$this, 'enqueuePageAssets'] );

    }

    /**
	 * Load settings page content.
	 *
	 * @return void
	 */
    public function settings_page()
    {
        // $this->settings = get_option( 'goldencat-settings-option' );

        ?>
        <div id="goldencat-theme-settings"></div>
        <?php
    }

    /**
     * Enqueue Script and Style for the Admin
     */
    public function enqueuePageAssets()
    {
        // wp_enqueue_editor();
        GoldenCatThemeScripts::toEnqueueScript('theme-admin-settings');
        // GoldenCatThemeScripts::toEnqueueStyle('theme-admin-settings');
        wp_enqueue_style('theme-admin-settings', get_template_directory_uri() . '/assets/theme-admin-settings.css', ['wp-components']);
    }

    /**
	 * Register plugin settings
	 *
	 * @return void
	 */
	public function register_settings() {

        // get_option( $option:string, $default:mixed );

		register_setting(
			'goldencat_theme_settings',
			'goldencat_theme_maintenance_on',
			array(
				'type'         => 'boolean',
				'show_in_rest' => true,
				'default'      => false,
			)
		);
		register_setting(
			'goldencat_theme_settings',
			'goldencat_theme_opengraph_on',
			array(
				'type'         => 'boolean',
				'show_in_rest' => true,
				'default'      => true,
			)
		);
		register_setting(
			'goldencat_theme_settings',
			'goldencat_theme_ga_measurement_id',
			array(
				'type'         => 'string',
				'show_in_rest' => true,
				'default'      => '',
			)
		);
		register_setting(
			'goldencat_theme_settings',
			'goldencat_theme_show_admin_bar_on',
			array(
				'type'         => 'boolean',
				'show_in_rest' => true,
				'default'      => true,
			)
		);
		register_setting(
			'goldencat_theme_settings',
			'goldencat_theme_posttype_faq_on',
			array(
				'type'         => 'boolean',
				'show_in_rest' => true,
				'default'      => true,
			)
		);
		register_setting(
			'goldencat_theme_settings',
			'goldencat_theme_posttype_quote_on',
			array(
				'type'         => 'boolean',
				'show_in_rest' => true,
				'default'      => true,
			)
		);

		register_setting(
			'goldencat_theme_settings',
			'goldencat_theme_coming_soon_on',
			array(
				'type'         => 'boolean',
				'show_in_rest' => true,
				'default'      => false,
			)
		);

		register_setting(
			'goldencat_theme_settings',
			'goldencat_theme_cookie_settings',
			array(
				'type'         => 'object',
				'default'      => array(
					'goldencat_cookie_settings_active' => false,
					'goldencat_cookie_settings_msg' => 'Nous utilisons des cookies pour nous assurer que nous vous offrons la meilleure expérience sur notre site Web. Si vous continuez à utiliser ce site, nous supposerons que vous en êtes satisfait.',
					'goldencat_cookie_settings_btn' => 'Accepter',
					'goldencat_cookie_settings_duration' => 3600 //1 heure
				),
				'show_in_rest' => array(
					'schema' => array(
						'type' => 'object',
						'properties' => array(
							'goldencat_cookie_settings_active' => array(
								'type' => 'boolean',
								'default' => false
							),
							'goldencat_cookie_settings_msg' => array(
								'type' => 'string'
							),
							'goldencat_cookie_settings_btn' => array(
								'type' => 'string'
							),
							'goldencat_cookie_settings_duration' => array(
								'type' => 'integer'
							),
						)
					)
				),
			)
		);
	}
}
