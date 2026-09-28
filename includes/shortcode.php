<?php
/**
 * includes/shortcode.php - v2.1.4
 * Stable shortcode API — see SHORTCODE-API.md
 */

if (!defined('ABSPATH')) exit;

function cta_pro_shortcode() {
    ob_start();
    include CTA_PRO_PATH . 'templates/buttons-shortcode.php';
    return ob_get_clean();
}
add_shortcode('cta_buttons_pro', 'cta_pro_shortcode');

function cta_pro_single_button_shortcode($atts) {
    $atts = shortcode_atts([
        'channel' => '',
        'id'      => '',
        'label'   => '',
        'size'    => 24,
        'index'   => 0,
    ], $atts, 'cta_button');

    ob_start();
    include CTA_PRO_PATH . 'templates/single-button.php';
    return ob_get_clean();
}
add_shortcode('cta_button', 'cta_pro_single_button_shortcode');

// Legacy channel shortcodes – support index for multiple of same type
// [cta_phone] / [cta_phone index="2"] etc. — frozen API
$channels = ['phone', 'whatsapp', 'telegram', 'email', 'instagram', 'location'];
foreach ($channels as $ch) {
    add_shortcode("cta_{$ch}", function ($atts = []) use ($ch) {
        $atts = shortcode_atts([
            'label' => '',
            'size'  => 24,
            'index' => 0,
            'id'    => '',
        ], $atts);
        $atts['channel'] = $ch;
        return cta_pro_single_button_shortcode($atts);
    });
}

add_shortcode('cta_btn', function ($atts = []) {
    $atts = shortcode_atts(['id' => '', 'label' => '', 'size' => 24], $atts);
    return cta_pro_single_button_shortcode($atts);
});
