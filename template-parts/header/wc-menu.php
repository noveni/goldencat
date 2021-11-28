<?php
/**
 * Display Menu With Account User Button
 * 
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Golden_Cat
 */

if ( ! goldencat_is_woocommerce_activated() ) {
    return;
}

$account_button_permalink = get_permalink( get_option('woocommerce_myaccount_page_id', '') );
$account_button_label = _('Connexion');
$account_button_title = _('Connexion / Inscription');
$with_label = isset( $args['with_label'] ) ? (bool) $args['with_label'] : false;
if ( is_user_logged_in() ) {
    $account_button_label = _('Compte');
    $account_button_title = _('Mon compte');
}
$account_button_label_class = $with_label === true ? 'header-button-label' : 'screen-reader-text';
?>


<div class="wc-header-wrapper<?php echo $with_label === true ? ' with-label' : ''; ?>">
    <!-- Account -->
    <a class="header-account-button wc-header-button" href="<?php echo $account_button_permalink; ?>" class="" title="<?php echo $account_button_title; ?>">
        <?php echo goldencat_icon( 'ui', 'user' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
        <span class="<?php echo $account_button_label_class; ?>"><?php echo $account_button_label; ?></span>
    </a>
    <div class="site-header-cart">
        <?php goldencat_cart_link( $with_label ); ?>
        <?php the_widget( 'WC_Widget_Cart', 'title=' ); ?>
    </div>
</div>
