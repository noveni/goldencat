<?php
/**
 * Meta Class
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class GoldenCatThemeMeta {

    /**
     * List of default directories.
     *
     * @since 2.8.0
     * @var array
     */
    public static $default_cpt = ['post'];

    public static function print_meta()
    {
        echo '<meta name="description" content="' . self::description()  . '" />' . "\n";
        self::print_all_OG();
    }
    
    public static function print_all_OG()
    {
        echo '<meta property="og:title" content="'. self::title() .'" />'. "\n";
        echo '<meta property="og:type" content="' . self::ogType() . '" />'. "\n";
        echo '<meta property="og:url" content="' . self::getOGPermalink() . '" />'. "\n";
        echo '<meta property="og:description" content="' . self::description()  . '"/>' . "\n";
        echo '<meta property="og:locale" content="' . get_locale() . '"/>' . "\n";
        echo '<meta property="og:site_name" content="' . get_bloginfo('name') . '"/>' . "\n";
        $ogImages = self::getOgImages();
        self::printOgImages($ogImages);
        
    }

    /**
     * Return the correct permalink of page, archive cp, post, tax
     * 
     * @return String Url of page
     */
    public static function getOGPermalink()
    {
        if ( is_tax() ) { 
            $permalink = get_term_link( get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
        }
        elseif( is_post_type_archive() ) {
            $permalink = get_post_type_archive_link( get_query_var('post_type') );
        }
        elseif (is_home()) {
            if ($home = get_option('page_for_posts', true)) {
                $permalink = get_permalink($home);
            } else {
                $permalink = get_permalink();
            }
        }
        else {
            $permalink = get_permalink();
        }
        return $permalink;
    }
    
    /**
     * Print the meta og tag for image
     * Handle multiple image
     * 
     * @param array $images the images elements
     */
    public static function printOgImages($images) {
        if ( ! $images ) {
            return;
        }
        foreach ($images as $image) {
            echo '<meta property="og:image" content="' . $image['url']. '"/>' . "\n";
            echo '<meta property="og:image:width" content="' . $image['width'] . '"/>' . "\n";
            echo '<meta property="og:image:height" content="' . $image['height'] . '"/>' . "\n";
            echo '<meta property="og:image:type" content="' . $image['type'] . '"/>' . "\n";
        }
    }
    /**
     * Get Post Image or default image and print Tag
     * 
     * @return array Array of images
     */
    public static function getOgImages()
    {
        $default_image = array(
            'url' => get_template_directory_uri() . '/assets/img/logo.svg',
            'height' => 1080,
            'width' => 1080,
            'type' => 'image/png'
        );

        $images = array();

        if(has_post_thumbnail()) {
            $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large');
            if ($thumbnail) {
                $images[] = array(
                    'url' => $thumbnail[0],
                    'height' => $thumbnail[1],
                    'width' => $thumbnail[2],
                    'type' => 'image/jpg',
                );
                $images[] = $default_image;
                return $images;
            }
            
        }
        
        $images[] = $default_image;
        return $images;
    }

    

    public static function title()
    {
        if (is_home()) {
            if ($home = get_option('page_for_posts', true)) {
                return get_the_title($home);
            }
            return __('Latest Posts', 'goldencat');
        }
        if (is_post_type_archive()) {
            return post_type_archive_title( '', false );
        }
        if (is_archive()) {
            return get_the_archive_title();
        }
        if (is_search()) {
            return sprintf(__('Search Results for %s', 'goldencat'), get_search_query());
        }
        if (is_404()) {
            return __('Not Found', 'goldencat');
        }
        return get_the_title();
    }

    /**
     * Short description of site or post or custom post type 
     * 
     * @return String 
     */
    public static function description()
    {
        if (is_singular(self::$default_cpt)) {
            $excerpt = wp_strip_all_tags( get_the_excerpt(), true );
            if ($excerpt) {
                return $excerpt;
            }
            // if (empty($excerpt)) { //If no excerpt we need to get the post content
            //     $des_post = wp_strip_all_tags( get_the_content(), true );
            //     $excerpt = mb_substr( $des_post, 0, 300, 'utf8' );
            // }
            // return $excerpt;
        }
        if ( is_post_type_archive() ) {
            $description_archive =  wp_strip_all_tags(get_the_archive_description(), true);
            if ( $description_archive ) {
                return $description_archive;
            }
        }
        return get_bloginfo( 'description' );
    }

    /**
     * Get the type of content for the Open Graph
     * 
     * @return String 'website' || 'article'
     */
    public static function ogType()
    {
        
        if ( is_singular( self::$default_cpt ) ) { 
            // OG tags gonna go only of specific page
            return 'article';
        }

        return 'website';
    }

    /**
     * Print the article meta
     */
    public static function printOgTypeArticle()
    {
        $author = get_the_author_meta();
        $date = '';
    }

    public static function addAnalytics($ga_measurement_id)
    {
        if (!$ga_measurement_id) {
            return;
        }
        ob_start();
        ?>
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $ga_measurement_id; ?>"></script>
        <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', '<?php echo $ga_measurement_id; ?>');
        </script>
        <?php

        $meta_output = ob_get_clean();
        // If there is meta to output, return it.
        if ( $meta_output ) {
            echo $meta_output;
        }
    }

    /**
     * Generate Favicon Html Head Code
     * 
     * It work with the Webpack config
     * @see webpack.mix.js file 
     */
    public static function printFavicon()
    {
        $base_favicons_dir = '/assets/icons/';
        // Check if favicon Exist
        if (!file_exists(get_stylesheet_directory() . $base_favicons_dir . 'favicon.ico')) {
            return;
        }
        $base_uri = get_stylesheet_directory_uri() . $base_favicons_dir;
        ob_start();
        ?>
        <link rel="apple-touch-icon" sizes="57x57" href="<?php echo $base_uri . 'apple-touch-icon-57x57.png' ?>">
        <link rel="apple-touch-icon" sizes="60x60" href="<?php echo $base_uri . 'apple-touch-icon-60x60.png' ?>">
        <link rel="apple-touch-icon" sizes="72x72" href="<?php echo $base_uri . 'apple-touch-icon-72x72.png' ?>">
        <link rel="apple-touch-icon" sizes="76x76" href="<?php echo $base_uri . 'apple-touch-icon-76x76.png' ?>">
        <link rel="apple-touch-icon" sizes="114x114" href="<?php echo $base_uri . 'apple-touch-icon-114x114.png' ?>">
        <link rel="apple-touch-icon" sizes="120x120" href="<?php echo $base_uri . 'apple-touch-icon-120x120.png' ?>">
        <link rel="apple-touch-icon" sizes="144x144" href="<?php echo $base_uri . 'apple-touch-icon-144x144.png' ?>">
        <link rel="apple-touch-icon" sizes="152x152" href="<?php echo $base_uri . 'apple-touch-icon-152x152.png' ?>">
        <link rel="apple-touch-icon" sizes="167x167" href="<?php echo $base_uri . 'apple-touch-icon-167x167.png' ?>">
        <link rel="apple-touch-icon" sizes="180x180" href="<?php echo $base_uri . 'apple-touch-icon-180x180.png' ?>">
        <link rel="apple-touch-icon" sizes="1024x1024" href="<?php echo $base_uri . 'apple-touch-icon-1024x1024.png' ?>">
        <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $base_uri . 'favicon-16x16.png' ?>">
        <link rel="icon" type="image/png" sizes="32x32" href="<?php echo $base_uri . 'favicon-32x32.png' ?>">
        <link rel="manifest" href="<?php echo $base_uri . 'manifest.json' ?>">
        <link rel="shortcut icon" href="<?php echo $base_uri . 'favicon.ico' ?>">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="apple-mobile-web-app-title">
        <meta name="application-name">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="msapplication-TileColor" content="#fff">
        <meta name="msapplication-TileImage" content="<?php echo $base_uri . 'mstile-144x144.png' ?>">
        <meta name="msapplication-config" content="<?php echo $base_uri . 'browserconfig.xml' ?>">
        <meta name="theme-color" content="#fff">
        <?php
        $meta_output = ob_get_clean();
        // var_dump($meta_output);
        // If there is meta to output, return it.
        if ( $meta_output ) {
            echo $meta_output;
        }
    }
}
    
