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

if ( is_user_logged_in() ) {
    $account_button_label = _('Compte');
    $account_button_title = _('Mon compte');
}
?>

<div class="wc-header-wrapper">
    <!-- Account -->
    <a class="header-account-button wc-header-button" href="<?php echo $account_button_permalink; ?>" class="" title="<?php echo $account_button_title; ?>">
        <?php echo goldencat_icon( 'ui', 'user' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
        <span class="screen-reader-text"><?php echo $account_button_label; ?></span>
    </a>
    <?php goldencat_cart_link(); ?>
    <div class="site-header-cart">
        <?php the_widget( 'WC_Widget_Cart', 'title=' ); ?>
    </div>
</div>
