<?php
/**
 * includes/floating.php
 */
if (!defined('ABSPATH')) exit;

function cta_pro_floating_buttons() {
    if (is_admin()) return;

    $settings = cta_pro_get_settings();
    if (($settings['floating'] ?? '1') !== '1') return;

    $buttons = cta_pro_get_buttons();
    if (empty($buttons)) return;

    include CTA_PRO_PATH . 'templates/floating-buttons.php';
}
add_action('wp_footer', 'cta_pro_floating_buttons', 100);
