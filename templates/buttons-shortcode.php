<?php
// templates/buttons-shortcode.php
if (!defined('ABSPATH')) exit;

$channels = ['phone','whatsapp','telegram','email','instagram','location'];
?>

<div class="cta-pro-buttons" role="group" aria-label="<?php esc_attr_e('دکمه‌های تماس', 'cta-buttons-pro'); ?>">
    <?php foreach ($channels as $channel):
        $value = get_option("cta_pro_$channel");
        if (!$value) continue;

        $href = $label = $target = '';
        switch ($channel) {
            case 'phone':     $href = 'tel:' . esc_attr($value); $label = __('تماس تلفنی', 'cta-buttons-pro'); break;
            case 'whatsapp':  $href = 'https://wa.me/' . esc_attr(preg_replace('/\D/', '', $value)); $label = __('واتس‌اپ', 'cta-buttons-pro'); $target = 'target="_blank" rel="noopener"'; break;
            case 'telegram':  $tg = strpos($value,'http')===0 ? $value : 'https://t.me/'.ltrim($value,'@'); $href = esc_url($tg); $label = __('تلگرام', 'cta-buttons-pro'); $target = 'target="_blank" rel="noopener"'; break;
            case 'email':     $href = 'mailto:' . esc_attr($value); $label = __('ایمیل', 'cta-buttons-pro'); break;
            case 'instagram': $ig = strpos($value,'http')===0 ? $value : 'https://instagram.com/'.ltrim($value,'@'); $href = esc_url($ig); $label = __('اینستاگرام', 'cta-buttons-pro'); $target = 'target="_blank" rel="noopener"'; break;
            case 'location':  $href = esc_url($value); $label = __('لوکیشن', 'cta-buttons-pro'); $target = 'target="_blank" rel="noopener"'; break;
        }
    ?>
        <a href="<?php echo $href; ?>" class="cta-pro-btn cta-<?php echo esc_attr($channel); ?>" <?php echo $target; ?>
           title="<?php echo esc_attr($label); ?>" aria-label="<?php echo esc_attr($label); ?>">
            <?php echo cta_pro_get_icon($channel, 24); ?>
            <span><?php echo esc_html($label); ?></span>
        </a>
    <?php endforeach; ?>
</div>
