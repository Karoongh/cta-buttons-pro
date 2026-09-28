<?php
/**
 * Plugin Name:       CTA Buttons Pro
 * Plugin URI:        https://ghobadi.ir
 * Description:       دکمه‌های تماس کاملاً پویا با امکان افزودن دکمه دلخواه، آمار کلیک پایدار، شورت‌کد، المنتور و تنظیمات پیشرفته ظاهر و اندازه واکنش‌گرا
 * Version:           2.1.4
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

define('CTA_PRO_VERSION', '2.1.4');
define('CTA_PRO_DB_VERSION', '2.1.4');
define('CTA_PRO_PATH', plugin_dir_path(__FILE__));
define('CTA_PRO_URL', plugin_dir_url(__FILE__));
define('CTA_PRO_FILE', __FILE__);

/**
 * Activation – only set defaults if options do not exist.
 * NEVER delete existing data.
 */
register_activation_hook(CTA_PRO_FILE, function () {
    // Ensure buttons option exists
    if (get_option('cta_pro_buttons') === false) {
        update_option('cta_pro_buttons', []);
    }

    // Default settings (only if not present)
    $default_settings = [
        'floating'             => '1',
        'fixed_bar'            => '1',
        'floating_position'    => 'left',
        'glass_effect'         => '1',
        'glass_color'          => '#ffffff',
        'glass_opacity'        => 20,
        'button_text_color'    => '#ffffff',
        'fixed_bar_bg_color'   => '#000000',
        'fixed_bar_text_color' => '#ffffff',
        'animation'            => 'none',
        'enable_stats'         => '1',
        'enable_animation'     => '0',
        'sizes' => [
            'desktop' => ['font_size' => 15, 'padding_y' => 13, 'padding_x' => 26, 'min_width' => 160],
            'tablet'  => ['font_size' => 14, 'padding_y' => 12, 'padding_x' => 22, 'min_width' => 140],
            'mobile'  => ['font_size' => 13, 'padding_y' => 11, 'padding_x' => 18, 'min_width' => 120],
        ],
        'fixed_bar_buttons'    => [],
        'floating_page_rules'  => [],
    ];

    if (get_option('cta_pro_settings') === false) {
        update_option('cta_pro_settings', $default_settings);
    }

    // Legacy defaults (keep for backward compatibility – never overwrite)
    $legacy_defaults = [
        'cta_pro_floating'          => '1',
        'cta_pro_floating_position' => 'left',
        'cta_pro_fixed_bar'         => '1',
        'cta_pro_glass_effect'      => '1',
        'cta_pro_glass_opacity'     => 20,
        'cta_pro_glass_color'       => '#ffffff',
    ];
    foreach ($legacy_defaults as $key => $value) {
        if (get_option($key) === false) {
            update_option($key, $value);
        }
    }

    // Run migration once
    require_once CTA_PRO_PATH . 'includes/migration.php';
    cta_pro_run_migration();
});

/**
 * Load textdomain
 */
add_action('init', function () {
    load_plugin_textdomain('cta-buttons-pro', false, dirname(plugin_basename(__FILE__)) . '/languages');
});

/**
 * Bootstrap
 */
add_action('plugins_loaded', 'cta_pro_init');
function cta_pro_init() {
    // Always load migration early so data is ready
    require_once CTA_PRO_PATH . 'includes/migration.php';
    cta_pro_run_migration();

    require_once CTA_PRO_PATH . 'includes/helpers.php';
    require_once CTA_PRO_PATH . 'includes/shortcode.php';
    require_once CTA_PRO_PATH . 'includes/assets.php';
    require_once CTA_PRO_PATH . 'includes/floating.php';
    require_once CTA_PRO_PATH . 'includes/fixed-bar.php';
    require_once CTA_PRO_PATH . 'includes/ajax.php';
    require_once CTA_PRO_PATH . 'includes/reset-stats.php';
    require_once CTA_PRO_PATH . 'includes/reset-details.php';

    if (is_admin()) {
        require_once CTA_PRO_PATH . 'includes/settings.php';
        require_once CTA_PRO_PATH . 'includes/admin-assets.php';
    }

    if (did_action('elementor/loaded')) {
        require_once CTA_PRO_PATH . 'includes/elementor-widget.php';
    }
}
