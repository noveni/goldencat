<?php
/**
 * WooCommerce Compatibility File
 * 
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly


class GoldenCatThemeWooCommerce {

    /**
     * Setup class.
     *
     */
    public function __construct() {

        add_action( 'after_setup_theme', array( $this, 'setup' ) );
        add_filter( 'body_class', [ $this, 'addThemeActiveClass' ] );
        add_action( 'wp_enqueue_scripts', array( $this, 'woocommerce_scripts' ), 20 );
        add_filter( 'woocommerce_output_related_products_args', array( $this, 'related_products_args' ) );
        add_filter( 'woocommerce_product_thumbnails_columns', array( $this, 'thumbnail_columns' ) );
        add_filter( 'woocommerce_breadcrumb_defaults', array( $this, 'change_breadcrumb_delimiter' ) );
        
        // Instead of loading Core CSS files, we only register the font families.
        add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );
        add_filter( 'wp_enqueue_scripts', array( $this, 'addCoreFonts' ), 130 );

        $this->images();

        $this->singleProduct();
        // Product Variations Change it with jQuery
        add_filter( 'woocommerce_dropdown_variation_attribute_options_html', [ $this, 'change_dropdown_to_swatchs' ], 20, 2 );

        $this->globalActions();
    }

    /**
     * Sets up theme defaults and registers support for various WooCommerce features.
     *
     * Note that this function is hooked into the after_setup_theme hook, which
     * runs before the init hook. The init hook is too late for some features, such
     * as indicating support for post thumbnails.
     *
     * @since 2.4.0
     * @return void
     */
    public function setup() {
        add_theme_support(
            'woocommerce',
            array(
                'single_image_width'    => 416,
                'thumbnail_image_width' => 416,
                'gallery_thumbnail_image_width' => 416,
                'product_grid'          => array(
                    'default_columns' => 3,
                    'default_rows'    => 4,
                    'min_columns'     => 3,
                    'max_columns'     => 3,
                    'min_rows'        => 1,
                ),
            )
        );

        // add_theme_support( 'wc-product-gallery-zoom' );
        // add_theme_support( 'wc-product-gallery-lightbox' );
        add_theme_support( 'wc-product-gallery-slider' );

        /**
         * Add 'goldencat_woocommerce_setup' action.
         *
         * @since  2.4.0
         */
        do_action( 'goldencat_woocommerce_setup' );
    }

    /**
     * Add CSS in <head> to register WooCommerce Core fonts.
     *
     * @since 3.4.0
     * @return void
     */
    public function addCoreFonts() {

        $fonts_url = plugins_url( '/woocommerce/assets/fonts' );
        wp_add_inline_style( 
            'goldencat-child-woocommerce-styles', 
            '@font-face {
                font-family: star;
                src: url(' . $fonts_url . 'star.eot);
                src: url(' . $fonts_url . 'star.eot?#iefix) format("embedded-opentype"),
                    url(' . $fonts_url . 'star.woff) format("woff"),
                    url(' . $fonts_url . 'star.ttf) format("truetype"),
                    url(' . $fonts_url . 'star.svg#star) format("svg");
                font-weight: normal;
                font-style: normal;
            }
            @font-face {
                font-family: WooCommerce;
                src: url(' . $fonts_url . '/WooCommerce.eot);
                src:
                    url(' . $fonts_url . '/WooCommerce.eot?#iefix) format("embedded-opentype"),
                    url(' . $fonts_url . '/WooCommerce.woff) format("woff"),
                    url(' . $fonts_url . '/WooCommerce.ttf) format("truetype"),
                    url(' . $fonts_url . '/WooCommerce.svg#WooCommerce) format("svg");
                font-weight: 400;
                font-style: normal;
            }'
        );
    }

    public function addThemeActiveClass( $classes )
    {
        $classes[] = 'woocommerce-active';

	    return $classes;
    }

    /**
     * WooCommerce specific scripts & stylesheets
     *
     * @since 1.0.0
     */
    public function woocommerce_scripts() {

        $do_enqueue_base_wc_style = apply_filters( 'goldencat_theme_enqueue_woocommerce_style', true );
        $do_enqueue_base_wc_script = apply_filters( 'goldencat_theme_enqueue_woocommerce_script', true );

        if ( $do_enqueue_base_wc_style ) {
            GoldenCatThemeScripts::toEnqueueStyle('woocommerce');
        }

        if ( $do_enqueue_base_wc_script ) {
            GoldenCatThemeScripts::toRegisterScript('woocommerce', 'goldencat-wc-scripts');
            wp_enqueue_script('goldencat-wc-scripts');
        }
    }

    /**
     * Related Products Args
     *
     * @param  array $args related products args.
     * @since 1.0.0
     * @return  array $args related products args
     */
    public function related_products_args( $args ) {
        $args = array(
            'posts_per_page' => 3,
            'columns'        => 3,
        );

        return $args;
    }

    /**
     * Product gallery thumbnail columns
     *
     * @return integer number of columns
     * @since  1.0.0
     */
    public function thumbnail_columns() {
        $columns = 4;

        return intval( $columns );
    }

    /**
     * Remove the breadcrumb delimiter
     *
     * @param  array $defaults The breadcrumb defaults.
     * @return array           The breadcrumb defaults.
     * @since 2.2.0
     */
    public function change_breadcrumb_delimiter( $defaults ) {
        $defaults['delimiter']   = '<span class="breadcrumb-separator"> / </span>';
        $defaults['wrap_before'] = '<div class="goldencat-breadcrumb"><div class="col-full"><nav class="woocommerce-breadcrumb" aria-label="' . esc_attr__( 'breadcrumbs', 'goldencat' ) . '">';
        $defaults['wrap_after']  = '</nav></div></div>';
        return $defaults;
    }


    public function images()
    {
        add_filter( 'woocommerce_get_image_size_gallery_thumbnail', function( $size ) {
            return array(
                'width' => 236,
                'height' => 236,
                'crop' => 1,
            );
        } );
        add_filter( 'woocommerce_get_image_size_thumbnail', function( $size ) {
            return array(
                'width' => 600,
                'height' => 754,
                'crop' => 1,
            );
        } );
        add_filter( 'woocommerce_get_image_size_single', function( $size ) {
            return array(
                'width' => 750,
                'height' => 978,
                'crop' => 1,
            );
        } );
        add_filter( 'woocommerce_gallery_image_size', function( $size ) {
            return 'shop_single';
        } );
        
    }

    /**
     * Change Variations Select to Radio
     */
    public function changeVariationToRadio( $html, $args )
    {
        // https://codedcommerce.com/product/change-variation-drop-downs-to-radio-buttons/
        // Variable Products Only
        global $product;
        if( ! $product || ! $product->is_type( 'variable' ) ) {
            return;
        }
    
        $change_variation_to_radio = apply_filters( 'goldencat_wc_change_variation_to_radio', true, $product );
    
        if ( $change_variation_to_radio === false ) {
            return;
        }

        $exclude_variation_name_from_tag_conversion = apply_filters( 'goldencat_wc_change_variation_to_radio_exclude', [], $product );


        $taxonomy = $args[ 'attribute' ];
        $product = $args[ 'product' ];
        $options = $args[ 'options' ];

        // the thing is that we do not remove original dropdown, just hide it
	    $html = '<div style="display:none">' . $html . '</div>';

        $size_class = '';
        if ( count($options) == 4 ) {
            $size_class = 'by-4-item';
        }

        foreach( $options as $option ) {

            $selected = $args[ 'selected' ] === $option ? 'selected' : '';
    
            $radioHtml = sprintf(
                '<input name="%s" type="radio" value="%s" />',
                $taxonomy,
                $option
            );
            $html .= sprintf(
                '<label class="generatedRadios wp-element-button %s %s" data-value="%s">%s</label>',
                $size_class,
                $selected,
                $option,
                $option
            );
        }
    
        return $html;

    }

    /**
     * Handles Product filtering
     */
    public function singleProduct()
    {
        
        // Quantity Label and Plus and Minus Sign
        add_action( 'woocommerce_before_quantity_input_field', [ $this, 'singleProductQuantityAddLabel' ] );
        add_action( 'woocommerce_after_quantity_input_field', [ $this, 'singleProductQuantityAddBtn' ] );

        // Wrap Quantity Input and Add To Cart Button on single product page
        add_action( 'woocommerce_before_add_to_cart_quantity', [ $this, 'wrapSingleProductQuantityAndAddToCartBnBefore' ]);
        add_action( 'woocommerce_after_add_to_cart_button', [ $this, 'wrapSingleProductQuantityAndAddToCartBtnAfter' ]);
    }

    public function singleProductQuantityAddLabel()
    {
        ?>
		<label class="label_quantity">Quantité: </label>
		<?php
    }

    public function singleProductQuantityAddBtn()
    {
        ?>
		<button type="button" class="qty-btn-number" onclick="this.parentNode.querySelector('[type=number]').stepDown();">&#8722;</button>
		<button type="button" class="qty-btn-number" onclick="this.parentNode.querySelector('[type=number]').stepUp();">&#43;</button>
		<?php
    }


    public function wrapSingleProductQuantityAndAddToCartBnBefore() {
        ?>
        <div class="goldencat_wrap_qty_and_add_to_cart">
        <?php
    }

    public function wrapSingleProductQuantityAndAddToCartBtnAfter() {
        ?>
        </div>
        <?php
    }

    public function globalActions()
    {
        add_filter( 'template_redirect', [ $this, 'cartRedirectCheckout' ] );
        add_filter( 'woocommerce_add_to_cart_redirect', [ $this, 'skipCartRedirectCheckout' ] );
        add_action( 'woocommerce_login_redirect', [ $this, 'redirectAfterLogin' ], 9999, 2 );
    }

    public function cartRedirectCheckout()
    {
        if ( !is_cart() ) return;

		global $woocommerce;

		if ( $woocommerce->cart->is_empty() ) {
			// If empty cart redirect to home
			wp_redirect( get_home_url() );
			exit();
		} else {
			do_action( 'woocommerce_check_cart_items' );

			if ( wc_notice_count( 'error' ) > 0 ) {
				wc_clear_notices();
				return;
			} else {
				// Else redirect to check out url
				wp_redirect( wc_get_checkout_url(), 302 );
			}
			return;
		}
		
		exit;
    }

    public function skipCartRedirectCheckout( $url )
    {
        return wc_get_checkout_url();
    }

    public function redirectAfterLogin( $redirect, $user )
    {
        $redirect = wc_get_page_permalink( 'shop' ); // shop page
	    return $redirect;
    }
}
