<?php
/**
 * includes/fixed-bar.php
 */
if (!defined('ABSPATH')) exit;

function cta_pro_fixed_bottom_bar() {
    if (is_admin()) return;

    $settings = cta_pro_get_settings();
    if (($settings['fixed_bar'] ?? '1') !== '1') return;

    $selected = $settings['fixed_bar_buttons'] ?? [];
    if (empty($selected) || !is_array($selected)) {
        $all = cta_pro_get_buttons();
        $selected = [];
        foreach ($all as $b) {
            if (in_array($b['type'] ?? '', ['phone', 'whatsapp', 'location'], true)) {
                $selected[] = $b['id'];
                if (count($selected) >= 2) break;
            }
        }
    }

    if (empty($selected)) return;

    include CTA_PRO_PATH . 'templates/fixed-bottom-bar.php';
}
add_action('wp_footer', 'cta_pro_fixed_bottom_bar', 90);
