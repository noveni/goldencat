<?php
/**
 * Custom Settings Page
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class GoldenCatThemeMenu
{
    public function __construct()
    {
        // add_filter( 'walker_nav_menu_start_el', [ $this, 'addSubMenuToggleAttribute'], 10, 4 );
        // add_filter( 'nav_menu_item_args', [ $this, 'addMenuDescriptionArgs'], 10, 3 );
    }

    public function addSubMenuToggleAttribute( $output, $item, $depth, $args )
    {
        if ( 0 === $depth && in_array( 'menu-item-has-children', $item->classes, true ) ) {

            // Add toggle button.
            $output .= '<span class="sub-menu-toggle" aria-expanded="false">';
            $output .= '</span>';
        }
        return $output;
    }

    /**
     * Filters the arguments for a single nav menu item.
     *
     * @param stdClass $args  An object of wp_nav_menu() arguments.
     * @param WP_Post  $item  Menu item data object.
     * @param int      $depth Depth of menu item. Used for padding.
     *
     * @return stdClass
     */
    public function addMenuDescriptionArgs( $args, $item, $depth ) {
        $args->link_after = '';
        if ( 0 === $depth && isset( $item->description ) && $item->description ) {
            // The extra <span> element is here for styling purposes: Allows the description to not be underlined on hover.
            $args->link_after = '<p class="menu-item-description"><span>' . $item->description . '</span></p>';
        }
        return $args;
    }

}
