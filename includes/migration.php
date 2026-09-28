<?php
/**
 * includes/migration.php
 * Safe, non-destructive migration + auto-recovery for CTA Buttons Pro
 * NEVER deletes any existing options.
 */

if (!defined('ABSPATH')) exit;

/**
 * Run migration / recovery.
 * Called from activation and plugins_loaded.
 * Also re-runs recovery if buttons are empty but legacy data exists.
 */
function cta_pro_run_migration() {
    $current = get_option('cta_pro_db_version', '0');
    $needs_full = version_compare($current, CTA_PRO_DB_VERSION, '<');

    // Always try to recover buttons if empty (even after version is set)
    cta_pro_recover_buttons_from_legacy();

    if (!$needs_full) {
        return;
    }

    // ---- 1. Ensure core options exist without overwriting ----
    if (get_option('cta_pro_buttons') === false) {
        update_option('cta_pro_buttons', []);
    }

    // ---- 2. Build / merge settings array (never overwrite existing values) ----
    $settings = get_option('cta_pro_settings', []);
    if (!is_array($settings)) {
        $settings = [];
    }

    $defaults = [
        'floating'             => get_option('cta_pro_floating', '1'),
        'fixed_bar'            => get_option('cta_pro_fixed_bar', '1'),
        'floating_position'    => get_option('cta_pro_floating_position', 'left'),
        'glass_effect'         => get_option('cta_pro_glass_effect', '1'),
        'glass_color'          => get_option('cta_pro_glass_color', '#ffffff'),
        'glass_opacity'        => (int) get_option('cta_pro_glass_opacity', 20),
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

    foreach ($defaults as $key => $value) {
        if (!array_key_exists($key, $settings)) {
            $settings[$key] = $value;
        }
    }

    if (empty($settings['sizes']) || !is_array($settings['sizes'])) {
        $settings['sizes'] = $defaults['sizes'];
    } else {
        foreach (['desktop', 'tablet', 'mobile'] as $bp) {
            if (empty($settings['sizes'][$bp]) || !is_array($settings['sizes'][$bp])) {
                $settings['sizes'][$bp] = $defaults['sizes'][$bp];
            }
        }
    }

    update_option('cta_pro_settings', $settings);

    // ---- 3. Auto-populate fixed_bar_buttons if empty ----
    if (empty($settings['fixed_bar_buttons'])) {
        $buttons = get_option('cta_pro_buttons', []);
        $auto = [];
        if (is_array($buttons)) {
            foreach ($buttons as $btn) {
                if (in_array($btn['type'] ?? '', ['phone', 'whatsapp', 'location'], true)) {
                    $auto[] = $btn['id'];
                    if (count($auto) >= 2) break;
                }
            }
        }
        if (!empty($auto)) {
            $settings['fixed_bar_buttons'] = $auto;
            update_option('cta_pro_settings', $settings);
        }
    }

    // ---- 4. Mark migration complete ----
    update_option('cta_pro_db_version', CTA_PRO_DB_VERSION);
}

/**
 * Recover dynamic buttons from legacy options (cta_pro_phone, etc.)
 * Runs every time if current buttons list is empty.
 * NEVER overwrites existing non-empty buttons list.
 */
function cta_pro_recover_buttons_from_legacy() {
    $existing = get_option('cta_pro_buttons', []);
    if (is_array($existing) && count($existing) > 0) {
        return; // already have buttons – do not touch
    }

    $legacy_channels = ['phone', 'whatsapp', 'telegram', 'email', 'instagram', 'location'];
    $recovered = [];

    foreach ($legacy_channels as $ch) {
        $value = get_option("cta_pro_$ch", '');
        if ($value === '' || $value === false || $value === null) {
            continue;
        }

        $icon_id = absint(get_option("cta_pro_icon_$ch", 0));

        $recovered[] = [
            'id'          => 'btn_legacy_' . $ch,
            'type'        => $ch,
            'value'       => sanitize_text_field($value),
            'label'       => '',
            'show_number' => true,
            'icon'        => $icon_id,
            'order'       => count($recovered),
        ];
    }

    if (!empty($recovered)) {
        update_option('cta_pro_buttons', array_slice($recovered, 0, 15));
    }
}
