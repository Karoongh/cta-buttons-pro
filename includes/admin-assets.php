<?php
/**
 * includes/admin-assets.php
 */
if (!defined('ABSPATH')) exit;

function cta_pro_admin_assets($hook) {
    if ($hook !== 'toplevel_page_cta-buttons-pro') return;

    wp_enqueue_media();
    wp_enqueue_script('cta-pro-admin', CTA_PRO_URL . 'assets/js/admin-script.js', ['jquery'], CTA_PRO_VERSION, true);
    wp_localize_script('cta-pro-admin', 'ctaProAdmin', [
        'ajax_url' => admin_url('admin-ajax.php'),
    ]);
}
add_action('admin_enqueue_scripts', 'cta_pro_admin_assets');
