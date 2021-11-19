<?php
/**
 * Custom Scripts Handler
 *
 */

class GoldenCatThemeIcons
{

    private static $assets_path = '/assets/img/';

    /**
     * Brand SVG
     * 
     */
    public static $brand_svg = array(
        'logo'			=> ['path' => 'logo.svg'],
        'logo-theme-author'			=> ['path' => 'logo-theme-author.svg'],
    );

    /**
	 * User Interface icons – svg sources.
	 *
	 * @access protected
	 *
	 * @var array
	 */
	protected static $icons = array(
		'arrow_right' => '<svg xmlns="http://www.w3.org/2000/svg" width="19" height="34" viewBox="0 0 19 34"><path fill-rule="evenodd" d="m.90160863 33c-.49703989.0008943-.90067913-.3874262-.90160863-.8672073-.00042047-.2312984.094752-.4532307.2644256-.6166055l15.5642416-15.0219297-15.5642416-15.02201115c-.34533767-.34507428-.33538169-.89505932.02210566-1.2284058.34879696-.32512073.90169161-.32512073 1.25040419 0l16.19956845 15.63706915c.3513281.3392104.3513281.889114 0 1.2284058l-16.19956845 15.6370692c-.16857676.1623975-.39705817.2536153-.63532682.2536153z" transform="translate(.834 .552)"/></svg>',
		'arrow_left'  => '<svg xmlns="http://www.w3.org/2000/svg" width="19" height="34" viewBox="0 0 19 34"><path fill-rule="evenodd" d="m.90160863 33c-.49703989.0008943-.90067913-.3874262-.90160863-.8672073-.00042047-.2312984.094752-.4532307.2644256-.6166055l15.5642416-15.0219297-15.5642416-15.02201115c-.34533767-.34507428-.33538169-.89505932.02210566-1.2284058.34879696-.32512073.90169161-.32512073 1.25040419 0l16.19956845 15.63706915c.3513281.3392104.3513281.889114 0 1.2284058l-16.19956845 15.6370692c-.16857676.1623975-.39705817.2536153-.63532682.2536153z" transform="matrix(-1 0 0 1 18.834 .552)"/></svg>',
		'close'       => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 10.9394L5.53033 4.46973L4.46967 5.53039L10.9393 12.0001L4.46967 18.4697L5.53033 19.5304L12 13.0607L18.4697 19.5304L19.5303 18.4697L13.0607 12.0001L19.5303 5.53039L18.4697 4.46973L12 10.9394Z" fill="currentColor"/></svg>',
		'menu'        => [ 'path' => 'hamburger.svg' ],
		'search'       => [ 'path' => 'search.svg' ],
		'user'       => [ 'path' => 'user.svg' ],
		'cart'       => [ 'path' => 'cart.svg' ],
	);

    /**
	 * Gets the SVG code for a given icon.
	 *
	 * @static
	 *
	 * @access public
	 *
	 * @param string $group the icon group.
	 * @param string $icon The icon.
	 * @param int    $size The icon-size in pixels.
	 *
	 * @return string
	 */
	public static function get_svg( $group, $icon, $size, $additionnais_class = '' ) {

		if ( 'ui' === $group ) {
			$arr = self::$icons;
		} elseif ( 'brand' === $group ) {
            $arr = self::$brand_svg;
		} else {
			$arr = array();
		}

		/**
		 * Filters Theme array of icons.
		 *
		 * The dynamic portion of the hook name, `$group`, refers to
		 * the name of the group of icons, either "ui".
		 *
		 * @param array $arr Array of icons.
		 */
		$arr = apply_filters( "goldencat_svg_icons_{$group}", $arr );

		$class = 'svg-icon';

		if ($additionnais_class != '' )  {
			$class .= " $additionnais_class";
		}

		if ($size) {
			$class .= " svg-has-size $additionnais_class";
		}

		$svg = '';
		if ( array_key_exists( $icon, $arr ) ) {
            $icon_content = $arr[ $icon ];
            if (is_array($arr[ $icon ])) {
                if (array_key_exists('path', $arr[$icon])) {
                    $icon_content = file_get_contents( get_template_directory() . self::$assets_path . $arr[$icon]['path'], true);
                }
			}
			if ($size) {
				$repl = sprintf( '<svg class="' . $class . '" width="%d" height="%d" aria-hidden="true" role="img" focusable="false" ', $size, $size );
			} else {
				$repl = '<svg class="' . $class . '" aria-hidden="true" role="img" focusable="false" ';
			}

			$svg = preg_replace( '/^<svg /', $repl, trim( $icon_content ) ); // Add extra attributes to SVG code.
		}

		// @phpstan-ignore-next-line.
		return $svg;
	}
}
