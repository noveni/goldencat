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
        add_action( 'woocommerce_after_single_product', [ $this, 'changeVariationToRadio' ] );

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
                    'default_columns' => 4,
                    'default_rows'    => 4,
                    'min_columns'     => 4,
                    'max_columns'     => 4,
                    'min_rows'        => 1,
                ),
            )
        );

        // add_theme_support( 'wc-product-gallery-zoom' );
        // add_theme_support( 'wc-product-gallery-lightbox' );
        add_theme_support( 'wc-product-gallery-slider' );

        /**
         * Add 'ecrannoirtwentyone_woocommerce_setup' action.
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

        $font_path   = WC()->plugin_url() . '/assets/fonts/';
        $inline_font = '@font-face {
			font-family: "star";
			src: url("' . $font_path . 'star.eot");
			src: url("' . $font_path . 'star.eot?#iefix") format("embedded-opentype"),
				url("' . $font_path . 'star.woff") format("woff"),
				url("' . $font_path . 'star.ttf") format("truetype"),
				url("' . $font_path . 'star.svg#star") format("svg");
			font-weight: normal;
			font-style: normal;
		}
        @font-face {
            font-family: WooCommerce;
            src: url("' . $font_path . '/WooCommerce.eot");
            src:
                url("' . $font_path . '/WooCommerce.eot?#iefix") format("embedded-opentype"),
                url("' . $font_path . '/WooCommerce.woff") format("woff"),
                url("' . $font_path . '/WooCommerce.ttf") format("truetype"),
                url("' . $font_path . '/WooCommerce.svg#WooCommerce") format("svg");
            font-weight: 400;
            font-style: normal;
        }';
        wp_add_inline_style( 'goldencat-woocommerce-styles', $inline_font );
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
        global $storefront_version;

        $suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

        GoldenCatThemeScripts::toEnqueueStyle('woocommerce');
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
            'posts_per_page' => 4,
            'columns'        => 4,
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
        $defaults['wrap_before'] = '<div class="ecrannoirtwentyone-breadcrumb"><div class="col-full"><nav class="woocommerce-breadcrumb" aria-label="' . esc_attr__( 'breadcrumbs', 'ecrannoirtwentyone' ) . '">';
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
                'width' => 301,
                'height' => 410,
                'crop' => 1,
            );
        } );
        add_filter( 'woocommerce_get_image_size_single', function( $size ) {
            return array(
                'width' => 727,
                'height' => 685,
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
    public function changeVariationToRadio()
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

        // Inline jQuery
        ?>
        <script>
            jQuery( document ).ready( function( $ ) {
                
                $( ".variations_form" )
                    .on( "wc_variation_form woocommerce_update_variation_values", function() {
                        var product_attr    =   jQuery.parseJSON( $(".variations_form").attr("data-product_variations") )
                        var excluded_variations = <?php echo json_encode($exclude_variation_name_from_tag_conversion) ?>;
                        $( "label.generatedRadios" ).remove();
                        $( "table.variations select" ).each( function() {
                            var selName = $( this ).attr( "name" );
                            if ( !excluded_variations.includes( selName )) {
                                $( "select[name=" + selName + "] option" ).each( function() {
                                    var option = $( this );
                                    var value = option.attr( "value" );
                                    if( value == "" ) { return; }
                                    var label = option.html();
                                    var select = option.parent();
                                    var selected = select.val();
                                    var isSelected = ( selected == value )
                                        ? " checked=\"checked\"" : "";
                                    var selClass = ( selected == value )
                                        ? " selected" : "";
                                    var radHtml
                                        = `<input name="${selName}" type="radio" value="${value}" />`;
                                    var optionHtml
                                        = `<label class="generatedRadios${selClass}">${radHtml} ${label}</label>`;
                                    select.parent().append(
                                        $( optionHtml ).click( function() {
                                            select.val( value ).trigger( "change" );
                                            $('.woocommerce-variation-price').hide();
                                        } )
                                    )

                                    if (isSelected) {
                                        // Move Variation Price up.
                                        $.each( product_attr, function( index, loop_value ) {
                                            if( value === loop_value.attributes[selName] ){
                                                $('.product_title + .price').html( loop_value.price_html );
                                            }
                                        });

                                        // Hide Old Variation Price
                                        $('.woocommerce-variation-price').hide();
                                    }
                                } ).parent().hide();
                            }
                        } );
                    } );
                

            } );
        </script>
        <?php
    }

    /**
     * Handles Product filtering
     */
    public function singleProduct()
    {
        
        // Quantity Label and Plus and Minus Sign
        add_action( 'woocommerce_before_quantity_input_field', [ $this, 'singleProductQuantityAddLabel' ] );
        add_action( 'woocommerce_after_quantity_input_field', [ $this, 'singleProductQuantityAddBtn' ] );
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
			// Else redirect to check out url
			wp_redirect( wc_get_checkout_url(), 302 );
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
