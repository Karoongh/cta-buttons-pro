<?php
// uninstall.php - حذف کامل و امن تمام داده‌های افزونه
if (!defined('WP_UNINSTALL_PLUGIN')) exit;

if (!current_user_can('activate_plugins')) return;

$options = [
    'cta_pro_phone', 'cta_pro_whatsapp', 'cta_pro_telegram', 'cta_pro_email',
    'cta_pro_instagram', 'cta_pro_location', 'cta_pro_floating', 'cta_pro_fixed_bar',
    'cta_pro_floating_position', 'cta_pro_glass_effect', 'cta_pro_glass_color',
    'cta_pro_glass_opacity'
];

foreach ($options as $option) {
    delete_option($option);
}

$channels = ['phone', 'whatsapp', 'telegram', 'email', 'instagram', 'location'];
foreach ($channels as $ch) {
    delete_option("cta_pro_clicks_$ch");
    delete_option("cta_pro_last_click_$ch");
    delete_option("cta_pro_icon_$ch");
    delete_option("cta_pro_clicks_detail_$ch");
}
