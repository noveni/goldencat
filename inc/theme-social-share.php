<?php
/**
 * Meta Class
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly
    
function goldencat_social_share_render_icon( $post_id = null, $args = array() ) {


    if ( is_null( $post_id ) && is_singular() ) {
		$post_id = get_the_ID();
	}

    if ( is_null( $post_id ) ) {
        return;
    }
    $sharing_settings = get_option( 'goldencat_theme_sharing_settings' );
    $default_args = array(
        'post_type' => $sharing_settings && isset( $sharing_settings['goldencat_sharing_posttype'] ) ? $sharing_settings['goldencat_sharing_posttype'] : array( 'post' ),
        'icon' => true,
        'label' => false,
        'service' => $sharing_settings && isset( $sharing_settings['goldencat_sharing_services'] ) ? $sharing_settings['goldencat_sharing_services'] : array( 'facebook' ),
    );

    $social_settings = wp_parse_args( $args, $default_args );

    if ( ! in_array( get_post_type(), $social_settings['post_type'] ) ) {
        return;
    }

    $rel_target_attributes = 'rel="noopener nofollow" target="_blank"';

    ?>

    <div class="goldencat-social-share">
        <span class="menu-text-style">Partager sur </span>
        <ul class="wp-block-social-links is-style-logos-only">
            
        <?php foreach( $social_settings['service'] as $service ): 
            $url = goldencat_social_share_get_share_url( $service, $post_id );
            $label = $service;
            $icon = '';

            if ( function_exists( 'block_core_social_link_get_icon' ) ) {
                $icon = block_core_social_link_get_icon( $service );
            }

            if ( function_exists( 'block_core_social_link_get_name' ) ) {
                $label = block_core_social_link_get_name( $service );
            }

            $service_low = strtolower( $service );
            
            $link  = '<li class="wp-social-link wp-social-link-'. $service_low .' wp-block-social-link">';
            $link .= '<a href="' . esc_url( $url ) . '" ' . $rel_target_attributes . ' class="wp-block-social-link-anchor">';
            $link .= $icon;
            $link .= '<span class="wp-block-social-link-label' . ( $social_settings['label'] ? '' : ' screen-reader-text' ) . '">';
            $link .= esc_html( $label );
            $link .= '</span></a></li>';

            echo $link;
            ?>
            



        <?php endforeach; ?>

        </ul>
    </div>

    <?php
}

function goldencat_social_share_get_share_url( $service, $post_id, $args = array() ) {

    if ( is_null( $post_id) ) {
        return;
    }

    if ( is_null( $service ) ) {
        return;
    }

    // Default Args
	$defaults = array(
		'image'     => wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'full' ),
		'title'     => get_the_title( $post_id ),
	);

	$args = wp_parse_args( $args, $defaults );

    $the_permalink = get_the_permalink( $post_id );

    $encoded_permalink = rawurlencode( $the_permalink );

    $encoded_title = htmlspecialchars(urlencode(html_entity_decode( $args['title'] , ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8');

    if ( $args['image'] ) {
        $encoded_img = urlencode( $args['image'][0] );
    } else {
        $encoded_img = get_stylesheet_directory_uri() . '/assets/img/logo.svg';
    }

    $share_url = '#';

    switch ( $service ) {

        case 'twitter':
            $share_url = add_query_arg(
				array(
					'text' => $encoded_title,
					'url' => $encoded_permalink,
				),
				'https://twitter.com/share'
			);
            break;
        case 'facebook':
            $share_url = add_query_arg(
				array(
					't' => $encoded_title,
					'u' => $encoded_permalink,
				),
				'https://www.facebook.com/sharer.php'
			);
            break;
        case 'linkedin':
            $share_url = add_query_arg(
				array(
					'title' => $encoded_title,
					'url' => $encoded_permalink,
                    'mini'  => 'true',
				),
				'https://www.linkedin.com/shareArticle'
			);
            break;
        case 'pinterest':
            $share_url = add_query_arg(
				array(
					'description' => $encoded_title,
					'url' => $encoded_permalink,
                    'media'  => $encoded_img,
				),
				'https://pinterest.com/pin/create/button/'
			);
            break;

        default:
			break;
    }

    return $share_url;
}

