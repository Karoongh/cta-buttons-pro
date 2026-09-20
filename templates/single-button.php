<?php
// templates/single-button.php
if (!defined('ABSPATH')) exit;

$channel = $atts['channel'] ?? '';
$label   = $atts['label'] ?? '';
$size    = max(16, min(64, intval($atts['size'] ?? 24)));

if (!$channel || !in_array($channel, ['phone','whatsapp','telegram','email','instagram','location'])) return '';

$value = get_option("cta_pro_$channel");
if (!$value) return '';

$href = $default_label = $target = '';
switch ($channel) {
    case 'phone':     $href = 'tel:' . esc_attr($value); $default_label = __('تماس تلفنی', 'cta-buttons-pro'); break;
    case 'whatsapp':  $href = 'https://wa.me/' . esc_attr(preg_replace('/\D/', '', $value)); $default_label = __('واتس‌اپ', 'cta-buttons-pro'); $target = 'target="_blank" rel="noopener"'; break;
    case 'telegram':  $tg = strpos($value,'http')===0 ? $value : 'https://t.me/'.ltrim($value,'@'); $href = esc_url($tg); $default_label = __('تلگرام', 'cta-buttons-pro'); $target = 'target="_blank" rel="noopener"'; break;
    case 'email':     $href = 'mailto:' . esc_attr($value); $default_label = __('ایمیل', 'cta-buttons-pro'); break;
    case 'instagram': $ig = strpos($value,'http')===0 ? $value : 'https://instagram.com/'.ltrim($value,'@'); $href = esc_url($ig); $default_label = __('اینستاگرام', 'cta-buttons-pro'); $target = 'target="_blank" rel="noopener"'; break;
    case 'location':  $href = esc_url($value); $default_label = __('لوکیشن', 'cta-buttons-pro'); $target = 'target="_blank" rel="noopener"'; break;
}

$final_label = !empty($label) ? wp_kses_post($label) : $default_label;
?>

<a href="<?php echo $href; ?>" class="cta-pro-btn cta-single cta-<?php echo esc_attr($channel); ?>" <?php echo $target; ?>
   title="<?php echo esc_attr($final_label); ?>" aria-label="<?php echo esc_attr($final_label); ?>">
    <?php echo cta_pro_get_icon($channel, $size); ?>
    <span><?php echo $final_label; ?></span>
</a>