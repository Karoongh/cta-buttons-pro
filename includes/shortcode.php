<?php
// includes/shortcode.php
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
        'label'   => '',
        'size'    => 24
    ], $atts, 'cta_button');

    ob_start();
    include CTA_PRO_PATH . 'templates/single-button.php';
    return ob_get_clean();
}

$channels = ['phone','whatsapp','telegram','email','instagram','location'];
foreach ($channels as $ch) {
    add_shortcode("cta_{$ch}", function($atts = []) use ($ch) {
        $atts = shortcode_atts(['label' => '', 'size' => 24], $atts);
        $atts['channel'] = $ch;
        return cta_pro_single_button_shortcode($atts);
    });
}

add_shortcode('cta_button', 'cta_pro_single_button_shortcode');
