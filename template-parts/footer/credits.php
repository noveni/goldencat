<?php
/**
 * The credits section on bottom containing a widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Golden_Cat
 */


$has_credits = apply_filters( 'goldencat_has_footer_credits', true );
$separator = apply_filters( 'goldencat_footer_separator', '<span class="separator">. </span>' );
if ( ! $has_credits ) {
	return;
}
?>

<div class="footer-credits alignwide">
    <p>
        <span><?php
            printf(
                '<a href="' . esc_url( home_url( '/' )  ) . '"><strong>' . get_bloginfo( 'name' ) . '.</strong></a>'
            );
        ?></span>&nbsp;
        <span><?php echo __('Tous droits réservés.', 'goldencat' ); ?></span>&nbsp;
        <span><?php
        printf(
            esc_html__( 'Site créé par %1$s & %2$s.', 'goldencat' ),
            '<a href="' . esc_url( __( 'https://www.instagram.com/tampala_studio/', 'goldencat' ) ) . '" target="_blank" rel="noopener noreferrer"><strong>Tampala Studio</strong></a>',
            '<a href="' . esc_url( __( 'https://ecrannoir.be/', 'goldencat' ) ) . '" target="_blank" rel="noopener noreferrer"><strong>Ecran Noir</strong></a>'
        );
        ?></span>
    </p>
</div>

