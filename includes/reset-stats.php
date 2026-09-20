<?php
// includes/reset-stats.php
if (!defined('ABSPATH')) exit;

add_action('admin_post_cta_pro_reset_stats', 'cta_pro_reset_stats_handler');

function cta_pro_reset_stats_handler() {
    if (!isset($_POST['cta_pro_reset_nonce']) || !wp_verify_nonce($_POST['cta_pro_reset_nonce'], 'cta_pro_reset_stats')) {
        wp_die(__('خطای امنیتی', 'cta-buttons-pro'));
    }
    if (!current_user_can('manage_options')) {
        wp_die(__('دسترسی غیرمجاز', 'cta-buttons-pro'));
    }

    $channels = ['phone', 'whatsapp', 'telegram', 'email', 'instagram', 'location'];
    foreach ($channels as $ch) {
        update_option("cta_pro_clicks_$ch", 0);
        delete_option("cta_pro_last_click_$ch");
    }

    wp_redirect(admin_url('admin.php?page=cta-buttons-pro&tab=stats&reset=1'));
    exit;
}
