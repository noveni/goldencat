<?php
/**
 * Custom Assets Hook and filter Handler
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class GoldenCatThemeAssets
{
    public function __construct()
    {
        add_filter( 'upload_mimes', [ $this, 'allowSvgOnUpload' ] );
        add_filter( 'sanitize_file_name', [ $this, 'sanitizeFileNameMore' ] );
    }

    public function allowSvgOnUpload( $mimes )
    {
        $mimes['svg'] = 'image/svg+xml';
        return $mimes;
    }

    public function sanitizeFileNameMore( $filename )
    {
        $sanitized_filename = remove_accents($filename); // Convert to ASCII

        // Standard replacements
        $invalid = array(
            ' ' => '-',
            '%20' => '-',
            '_' => '-'
        );
        $sanitized_filename = str_replace(array_keys($invalid), array_values($invalid), $sanitized_filename);

        $sanitized_filename = preg_replace('/[^A-Za-z0-9-\. ]/', '', $sanitized_filename); // Remove all non-alphanumeric except .
        $sanitized_filename = preg_replace('/\.(?=.*\.)/', '', $sanitized_filename); // Remove all but last .
        $sanitized_filename = preg_replace('/-+/', '-', $sanitized_filename); // Replace any more than one - in a row
        $sanitized_filename = str_replace('-.', '.', $sanitized_filename); // Remove last - if at the end
        $sanitized_filename = strtolower($sanitized_filename); // Lowercase

        return $sanitized_filename;
    }
}
