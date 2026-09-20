<?php
// includes/reset-details.php
if (!defined('ABSPATH')) exit;

add_action('admin_post_cta_pro_reset_details', 'cta_pro_reset_details_handler');

function cta_pro_reset_details_handler() {
    if (!isset($_POST['cta_pro_reset_details_nonce']) || !wp_verify_nonce($_POST['cta_pro_reset_details_nonce'], 'cta_pro_reset_details')) {
        wp_die(__('خطای امنیتی', 'cta-buttons-pro'));
    }
    if (!current_user_can('manage_options')) {
        wp_die(__('دسترسی غیرمجاز', 'cta-buttons-pro'));
    }

    $channels = ['phone', 'whatsapp', 'telegram', 'email', 'instagram', 'location'];
    foreach ($channels as $ch) {
        delete_option("cta_pro_clicks_detail_$ch");
    }

    wp_redirect(admin_url('admin.php?page=cta-buttons-pro&tab=details&reset_details=1'));
    exit;
}
