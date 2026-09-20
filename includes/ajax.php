<?php
// includes/ajax.php
if (!defined('ABSPATH')) exit;

add_action('wp_ajax_cta_pro_record_click', 'cta_pro_record_click');
add_action('wp_ajax_nopriv_cta_pro_record_click', 'cta_pro_record_click');

function cta_pro_record_click() {
    if (!check_ajax_referer('cta_pro_click_nonce', 'nonce', false)) {
        wp_send_json_error(['message' => 'Invalid nonce']);
    }

    $channel = sanitize_key($_POST['channel'] ?? '');
    $allowed = ['phone','whatsapp','telegram','email','instagram','location'];

    if (!in_array($channel, $allowed, true)) {
        wp_send_json_error(['message' => 'Invalid channel']);
    }

    $ip = sanitize_text_field($_SERVER['REMOTE_ADDR'] ?? 'unknown');
    $page_url = esc_url_raw($_SERVER['HTTP_REFERER'] ?? home_url());
    $page_title = esc_html(get_the_title(url_to_postid($page_url)) ?: 'نامشخص');
    $timestamp = current_time('timestamp', 1);

    $clicks = get_option("cta_pro_clicks_detail_$channel", []);
    $clicks[] = [
        'time' => $timestamp,
        'ip' => $ip,
        'page_url' => $page_url,
        'page_title' => $page_title,
    ];

    update_option("cta_pro_clicks_detail_$channel", array_slice($clicks, -100));

    $total_clicks = get_option("cta_pro_clicks_$channel", 0) + 1;
    update_option("cta_pro_clicks_$channel", $total_clicks);
    update_option("cta_pro_last_click_$channel", current_time('mysql', 1));

    wp_send_json_success(['clicks' => $total_clicks]);
}
