<?php
// includes/stats.php
if (!defined('ABSPATH')) exit;

$channels = ['phone','whatsapp','telegram','email','instagram','location'];
$total = 0;
?>

<div class="card">
    <h3>آمار کلیک‌ها</h3>
    <table class="widefat fixed striped">
        <thead><tr><th>کانال</th><th>کلیک</th><th>آخرین کلیک</th></tr></thead>
        <tbody>
            <?php foreach ($channels as $ch):
                $clicks = (int)get_option("cta_pro_clicks_$ch", 0);
                $last = get_option("cta_pro_last_click_$ch", '');
                $total += $clicks;
                $name = match($ch) {
                    'whatsapp' => 'واتساپ',
                    'telegram' => 'تلگرام',
                    'instagram' => 'اینستاگرام',
                    default => ucfirst($ch)
                };
            ?>
                <tr>
                    <td><strong><?php echo $name; ?></strong></td>
                    <td><?php echo number_format_i18n($clicks); ?></td>
                    <td><?php echo $last ? wp_date('d F Y - H:i', strtotime($last)) : '—'; ?></td>
                </tr>
            <?php endforeach; ?>
            <tr><td><strong>مجموع</strong></td><td colspan="2"><strong><?php echo number_format_i18n($total); ?></strong></td></tr>
        </tbody>
    </table>

    <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
		<input type="hidden" name="action" value="cta_pro_reset_stats">
		<?php wp_nonce_field('cta_pro_reset_stats', 'cta_pro_reset_nonce'); ?>
		<p class="submit">
			<button type="submit" class="button button-secondary" style="background:#d63638;color:white;">
				ریست کامل آمار کلیک‌ها
			</button>
		</p>
	</form>
</div>
