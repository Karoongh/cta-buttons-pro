<?php
// includes/assets.php
if (!defined('ABSPATH')) exit;

function cta_pro_assets() {
    if (is_admin()) return;

    wp_enqueue_style('cta-pro-style', CTA_PRO_URL . 'assets/css/style.css', [], CTA_PRO_VERSION);
    wp_enqueue_script('cta-pro-script', CTA_PRO_URL . 'assets/js/script.js', [], CTA_PRO_VERSION, true);

    if (get_option('cta_pro_glass_effect', '1') === '1') {
        $color = sanitize_hex_color(get_option('cta_pro_glass_color', '#ffffff'));
        $opacity = max(5, min(80, (int)get_option('cta_pro_glass_opacity', 20))) / 100;
        $hover_opacity = min(0.95, $opacity + 0.18);

        $rgb = sscanf(ltrim($color, '#'), '%02x%02x%02x');
        if (!$rgb) $rgb = [255,255,255];

        $custom_css = ":root {
            --cta-glass-r: {$rgb[0]};
            --cta-glass-g: {$rgb[1]};
            --cta-glass-b: {$rgb[2]};
            --cta-glass-opacity: {$opacity};
            --cta-glass-hover-opacity: {$hover_opacity};
        }";
        wp_add_inline_style('cta-pro-style', $custom_css);
    }

    wp_localize_script('cta-pro-script', 'ctaProAjax', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('cta_pro_click_nonce')
    ]);
}
add_action('wp_enqueue_scripts', 'cta_pro_assets');
