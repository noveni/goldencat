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

		register_setting(
			'goldencat_theme_settings',
			'goldencat_theme_global_settings',
			array(
				'type'         => 'object',
				'default'      => array(
					'maintenance_active' => false,
					'show_admin_bar_active' => true,
					'coming_soon_active' => false,
					'sticky_header_active' => false
				),
				'show_in_rest' => array(
					'schema' => array(
						'type' => 'object',
						'properties' => array(
							'maintenance_active' => array(
								'type'         => 'boolean',
								'default'      => false
							),
							'show_admin_bar_active' => array(
								'type'         => 'boolean',
								'default'      => true
							),
							'coming_soon_active' => array(
								'type'         => 'boolean',
								'default'      => false
							),
							'sticky_header_active' => array(
								'type'         => 'boolean',
								'default'      => false
							),
						)
					)
				)
			)
		);

		register_setting(
			'goldencat_theme_settings',
			'goldencat_theme_metrics_settings',
			array(
				'type'         => 'object',
				'default'      => array(
				),
				'show_in_rest' => array(
					'schema' => array(
						'type' => 'object',
						'properties' => array(
							'ga_measurement_id' => array(
								'type' => 'string'
							)
						)
					)
				)
			)
		);

		register_setting(
			'goldencat_theme_settings',
			'goldencat_theme_posttype_settings',
			array(
				'type'         => 'object',
				'default'      => array(
					'posttype_faq_on' => true,
					'posttype_quote_on' => false,
				),
				'show_in_rest' => array(
					'schema' => array(
						'type' => 'object',
						'properties' => array(
							'posttype_faq_on' => array(
								'type' => 'boolean',
								'default' => false
							),
							'posttype_quote_on' => array(
								'type' => 'boolean',
								'default' => false
							),
						)
					)
				)
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

		register_setting(
			'goldencat_theme_settings',
			'goldencat_theme_label_settings',
			array(
				'type'         => 'array',
				'default'      => array(
					array(
						'id' => 'shop_button',
						'location' => 'shop',
						'type'	=> 'button',
						'label'	=> 'Découvrir'
					),
					array(
						'id' => 'blog_button',
						'location' => 'blog',
						'type'	=> 'button',
						'label'	=> 'Découvrir l\'article'
					)
				),
				'show_in_rest' => array(
					'schema' => array(
						'items' => array(
							'type' => 'object',
							'properties' => array(
								'id' => array(
									'type' => 'string'
								),
								'location' => array(
									'type' => 'string'
								),
								'type' => array(
									'type' => 'string'
								),
								'label' => array(
									'type' => 'string'
								)
							)
						)
					)
				),
			)
		);

		register_setting(
			'goldencat_theme_settings',
			'goldencat_theme_sharing_settings',
			array(
				'type'         => 'object',
				'default'      => array(
					'goldencat_sharing_services' => array( 'facebook' ),
					'goldencat_sharing_posttype' =>  array( 'post' ),
					'goldencat_sharing_opengraph_active' => true
				),
				'show_in_rest' => array(
					'schema' => array(
						'type' => 'object',
						'properties' => array(
							'goldencat_sharing_services' => array(
								'type' => 'array',
								'items' => array(
									'type'   => 'string',
								)
							),
							'goldencat_sharing_posttype' => array(
								'type' => 'array',
								'items' => array(
									'type'   => 'string',
								)
							),
							'goldencat_sharing_opengraph_active' => array(
								'type' => 'boolean',
								'default' => true
							)
						)
					)
				),
			)
		);
	}

	public static function getSettingsLabel( $type = 'btn-product-shop' ) {
		$settings = get_option( 'goldencat_theme_label_settings', [] );

		if ( empty( $settings ) ) {
			return false;
		}

		$settings_slug = false;

		switch ($type) {
			case 'btn-product-shop':
				$settings_slug = 'goldencat_label_btn_product_shop';
				$settings_label_id = 'shop_button';
				break;
		}

		$labels = array_filter( $settings, function( $label_item ) use ($settings_label_id) {
			return $label_item['id'] === $settings_label_id;
		} );
		if ( !empty($labels) && isset($labels[0]['label']) && isset($labels[0]['label']) != '' ) {
			return $labels[0]['label'];
		}

		return false;
	}

	public static function getPostTypeActive( $type ) {
		if ( !$type || $type == '' ) {
			return false;
		}

		$settings = get_option( 'goldencat_theme_posttype_settings', [] );
		
		if ( empty( $settings ) ) {
			return false;
		}

		if ( isset( $settings[$type] ) && $settings[$type] == true ) {
			return true;
		}
		return false;
	}

	public static function showAdminBar() {

		$settings = get_option( 'goldencat_theme_global_settings', [] );
		
		if ( empty( $settings ) ) {
			return false;
		}

		if ( isset( $settings['show_admin_bar_active'] ) && $settings['show_admin_bar_active'] == true ) {
			return true;
		}
		return false;
	}

	public static function isActiveMaintenanceMode() {

		$settings = get_option( 'goldencat_theme_global_settings', [] );
		
		if ( empty( $settings ) ) {
			return false;
		}

		if ( isset( $settings['maintenance_active'] ) && $settings['maintenance_active'] == true ) {
			return true;
		}
		return false;
	}

	public static function isActiveComingSoon() {

		$settings = get_option( 'goldencat_theme_global_settings', [] );
		
		if ( empty( $settings ) ) {
			return false;
		}

		if ( isset( $settings['coming_soon_active'] ) && $settings['coming_soon_active'] == true ) {
			return true;
		}
		return false;
	}

	public static function hasStickyHeader() {

		$settings = get_option( 'goldencat_theme_global_settings', [] );
		
		if ( empty( $settings ) ) {
			return false;
		}

		if ( isset( $settings['sticky_header_active'] ) && $settings['sticky_header_active'] == true ) {
			return true;
		}
		return false;
	}
}
