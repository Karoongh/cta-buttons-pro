<?php
// includes/export-details.php
if (!defined('ABSPATH')) exit;

add_action('admin_post_cta_pro_export_details_csv', 'cta_pro_export_details_csv_handler');

function cta_pro_export_details_csv_handler() {
    if (!isset($_POST['cta_pro_export_details_nonce']) || !wp_verify_nonce($_POST['cta_pro_export_details_nonce'], 'cta_pro_export_details')) {
        wp_die(__('خطای امنیتی', 'cta-buttons-pro'));
    }
    if (!current_user_can('manage_options')) {
        wp_die(__('دسترسی غیرمجاز', 'cta-buttons-pro'));
    }

    $channels = ['phone', 'whatsapp', 'telegram', 'email', 'instagram', 'location'];
    $all_clicks = [];

    foreach ($channels as $ch) {
        $details = get_option("cta_pro_clicks_detail_$ch", []);
        foreach ($details as $click) {
            $click['channel'] = match($ch) {
                'whatsapp' => 'واتساپ',
                'telegram' => 'تلگرام',
                'instagram' => 'اینستاگرام',
                default => ucfirst($ch)
            };
            $all_clicks[] = $click;
        }
    }

    usort($all_clicks, function($a, $b) {
        return $b['time'] - $a['time'];
    });

    $filename = 'cta-clicks-details-' . date('Y-m-d_H-i') . '.csv';
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Pragma: no-cache');
    header('Expires: 0');

    echo "\xEF\xBB\xBF";

    $fp = fopen('php://output', 'w');

    fputcsv($fp, ['کانال', 'زمان کلیک', 'آدرس IP', 'آدرس صفحه', 'عنوان صفحه']);

    foreach ($all_clicks as $click) {
        fputcsv($fp, [
            $click['channel'],
            wp_date('d F Y - H:i:s', $click['time']),
            $click['ip'],
            $click['page_url'],
            $click['page_title']
        ]);
    }

    fclose($fp);
    exit;
}
