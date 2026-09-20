<?php
/**
 * Plugin Name:       CTA Buttons Pro
 * Plugin URI:        https://ghobadi.ir
 * Description:       دکمه‌های تماس شناور و ثابت حرفه‌ای با آمار کلیک، شورت‌کد و پشتیبانی کامل المنتور
 * Version:           1.8.0
 * Author:            Ghobadi Karoon
 * Author URI:        https://ghobadi.ir
 * License:           GPL v2 or later
 * Text Domain:       cta-buttons-pro
 * Domain Path:       /languages
 * Requires PHP:      7.4
 * Requires at least: 5.6
 * Tested up to:      6.7.1
 */

if (!defined('ABSPATH')) exit;

define('CTA_PRO_VERSION', '1.8.0');
define('CTA_PRO_PATH', plugin_dir_path(__FILE__));
define('CTA_PRO_URL', plugin_dir_url(__FILE__));
define('CTA_PRO_FILE', __FILE__);

register_activation_hook(CTA_PRO_FILE, function () {
    $defaults = [
        'cta_pro_floating'          => '1',
        'cta_pro_floating_position' => 'left',
        'cta_pro_fixed_bar'         => '1',
        'cta_pro_glass_effect'      => '1',
        'cta_pro_glass_opacity'     => 20,
        'cta_pro_glass_color'       => '#ffffff',
    ];
    foreach ($defaults as $key => $value) {
        if (get_option($key) === false) {
            update_option($key, $value);
        }
    }
});

add_action('init', function () {
    load_plugin_textdomain('cta-buttons-pro', false, dirname(plugin_basename(__FILE__)) . '/languages');
});

add_action('plugins_loaded', 'cta_pro_init');
function cta_pro_init() {
    require_once CTA_PRO_PATH . 'includes/helpers.php';
    require_once CTA_PRO_PATH . 'includes/shortcode.php';
    require_once CTA_PRO_PATH . 'includes/assets.php';
    require_once CTA_PRO_PATH . 'includes/floating.php';
    require_once CTA_PRO_PATH . 'includes/fixed-bar.php';
    require_once CTA_PRO_PATH . 'includes/ajax.php';
    require_once CTA_PRO_PATH . 'includes/reset-details.php';
    require_once CTA_PRO_PATH . 'includes/reset-stats.php';

    if (is_admin()) {
        require_once CTA_PRO_PATH . 'includes/settings.php';
        require_once CTA_PRO_PATH . 'includes/admin-assets.php';
    }

    if (did_action('elementor/loaded')) {
        require_once CTA_PRO_PATH . 'includes/elementor-widget.php';
    }
}
