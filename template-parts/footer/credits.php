<?php
/**
 * The credits section on bottom containing a widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Golden_Cat
 */


$has_credits = apply_filters( 'goldencat_has_footer_credits', true );
$separator = apply_filters( 'goldencat_footer_separator', '<span class="separator"> | </span>' );
if ( ! $has_credits ) {
	return;
}
?>

<div class="footer-credits">
    <p>
        <span><?php
            printf(
                esc_html__( '&copy; %1$s - %2$s %3$s', 'goldencat' ),
                date_i18n(_x( 'Y', 'copyright date format', 'goldencat' )),
                _x('Copyright', 'credits', 'goldencat'),
                '<a href="' . esc_url( home_url( '/' )  ) . '"><strong>' . get_bloginfo( 'name' ) . '</strong></a>',
            );
        ?></span>
        <span>
            <?php _e('Tous droits réservés', 'goldencat' ); ?>
        </span>
        <span><?php
        printf(
            esc_html__( 'Site créé par %1$s & %2$s.', 'goldencat' ),
            '<a href="' . esc_url( __( 'https://www.instagram.com/tampala_studio/', 'goldencat' ) ) . '" target="_blank" rel="noopener noreferrer"><strong>Tampala Studio</strong></a>',
            '<a href="' . esc_url( __( 'https://ecrannoir.be/', 'goldencat' ) ) . '" target="_blank" rel="noopener noreferrer"><strong>Ecran Noir</strong></a>'
        );
        ?></span>
    </p>
</div>

