<?php
/**
 * includes/reset-stats.php - v2.1.4
 * Resets ALL click stats found in database (including orphaned).
 */
if (!defined('ABSPATH')) exit;

add_action('admin_post_cta_pro_reset_stats', 'cta_pro_reset_stats_handler');

function cta_pro_reset_stats_handler() {
    if (!isset($_POST['cta_pro_reset_nonce']) || !wp_verify_nonce($_POST['cta_pro_reset_nonce'], 'cta_pro_reset_stats')) {
        wp_die(__('خطای امنیتی', 'cta-buttons-pro'));
    }
    if (!current_user_can('manage_options')) {
        wp_die(__('دسترسی غیرمجاز', 'cta-buttons-pro'));
    }

    global $wpdb;

    $rows = $wpdb->get_col(
        "SELECT option_name FROM {$wpdb->options}
         WHERE option_name LIKE 'cta\_pro\_clicks\_%'
         AND option_name NOT LIKE 'cta\_pro\_clicks\_detail\_%'"
    );
    foreach ($rows as $name) {
        update_option($name, 0);
    }

    $lasts = $wpdb->get_col(
        "SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE 'cta\_pro\_last\_click\_%'"
    );
    foreach ($lasts as $name) {
        delete_option($name);
    }

    wp_redirect(admin_url('admin.php?page=cta-buttons-pro&tab=stats&reset=1'));
    exit;
}
