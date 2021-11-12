<?php
/**
 * Custom Scripts Handler
 *
 */

class GoldenCatThemeScripts
{

    private static $assets_path = '/assets/';

    public static function toEnqueueScript($scriptName, $customHandleScriptName = '') {

        $base_uri = get_template_directory_uri() . self::$assets_path . 'js/';
        $base_dir = get_template_directory() . self::$assets_path . 'js/';

        $script_path = $base_dir . $scriptName . '.js';

        if (!file_exists($script_path)) {
            return;
        }

        $script_asset_path = $base_dir . $scriptName . '.asset.php';
        $script_asset = file_exists($script_asset_path) ? require($script_asset_path) : array('dependencies' => array(), 'version' => filemtime( $script_path ));
        
        $handleFileName = $customHandleScriptName !== '' ? $customHandleScriptName : 'ecrannoirtwentyone-' . $scriptName . '-scripts';
        // Enqueue Scripts
        wp_enqueue_script($handleFileName, $base_uri . $scriptName . '.js', $script_asset['dependencies'], $script_asset['version'], true);
    }

    public static function toEnqueueStyle($styleName, $customHandleStyleName = '', $media = 'all') {

        $base_uri = get_template_directory_uri() . self::$assets_path . '';
        $base_dir = get_template_directory() . self::$assets_path . '';

        $style_path = $base_dir . $styleName . '.css';

        if (!file_exists($style_path)) {
            return;
        }

        $script_asset_path = get_template_directory() . self::$assets_path . 'js/' . $styleName . '.asset.php';
        // If an php file for the js part exist
        if ( !file_exists(get_template_directory() . self::$assets_path . 'js/' . $styleName . '.asset.php')) {
            $script_asset_path = get_template_directory() . '/assets/js/theme.asset.php';
        }

        $script_asset = file_exists($script_asset_path) ? require($script_asset_path) : array('dependencies' => array(), 'version' => filemtime( $style_path ));
        
        $handleFileName = $customHandleStyleName !== '' ? $customHandleStyleName : 'ecrannoirtwentyone-' . $styleName . '-styles';
        // Enqueue Style
        wp_enqueue_style($handleFileName, $base_uri . $styleName . '.css', $script_asset['dependencies'], $script_asset['version'], $media);
    }
    
    public static function toRegisterScript($scriptName, $customHandleScriptName) {

        $script_asset_path = get_template_directory() . '/assets/js/' . $scriptName . '.asset.php';
        $script_asset = file_exists($script_asset_path) ? require($script_asset_path) : array('dependencies' => array(), 'version' => filemtime( $script_path ));
        
        wp_register_script($customHandleScriptName, get_template_directory_uri() . '/assets/js/' . $scriptName . '.js', $script_asset['dependencies'], $script_asset['version']);
    }
}
