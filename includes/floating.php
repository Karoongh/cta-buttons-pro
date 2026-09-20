<?php
// includes/floating.php
if (!defined('ABSPATH')) exit;

function cta_pro_floating_buttons() {
    if (is_admin() || get_option('cta_pro_floating') !== '1') return;

    $active = false;
    foreach (['phone','whatsapp','telegram','email','instagram','location'] as $ch) {
        if (get_option("cta_pro_$ch")) { $active = true; break; }
    }
    if (!$active) return;

    include CTA_PRO_PATH . 'templates/floating-buttons.php';
}
add_action('wp_footer', 'cta_pro_floating_buttons', 100);
