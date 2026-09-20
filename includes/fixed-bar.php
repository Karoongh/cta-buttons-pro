<?php
// includes/fixed-bar.php
if (!defined('ABSPATH')) exit;

function cta_pro_fixed_bottom_bar() {
    if (is_admin() || get_option('cta_pro_fixed_bar') !== '1') return;

    $active = false;
    foreach (['phone','whatsapp'] as $ch) {
        if (get_option("cta_pro_$ch")) { $active = true; break; }
    }
    if (!$active) return;

    include CTA_PRO_PATH . 'templates/fixed-bottom-bar.php';
}
add_action('wp_footer', 'cta_pro_fixed_bottom_bar', 90);
