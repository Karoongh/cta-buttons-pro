<?php
// templates/floating-buttons.php
if (!defined('ABSPATH')) exit;

if (get_option('cta_pro_floating') !== '1') return;

$channels = ['phone','whatsapp','telegram','email','instagram','location'];
$active_channels = array_filter($channels, fn($ch) => !empty(get_option("cta_pro_$ch")));

if (empty($active_channels)) return;

$position = in_array(get_option('cta_pro_floating_position','left'), ['left','right']) 
    ? get_option('cta_pro_floating_position','left') : 'left';
?>

<div class="cta-floating <?php echo esc_attr($position); ?>">
    <div class="cta-floating-menu">
        <?php foreach ($active_channels as $channel):
            $value = get_option("cta_pro_$channel");
            $href = $title = $target = '';

            switch ($channel) {
                case 'phone':     $href = 'tel:' . esc_attr($value); $title = __('تماس تلفنی', 'cta-buttons-pro'); break;
                case 'whatsapp':  $href = 'https://wa.me/' . esc_attr(preg_replace('/\D/', '', $value)); $title = __('واتس‌اپ', 'cta-buttons-pro'); $target = 'target="_blank" rel="noopener"'; break;
                case 'telegram':  $tg = strpos($value,'http')===0 ? $value : 'https://t.me/'.ltrim($value,'@'); $href = esc_url($tg); $title = __('تلگرام', 'cta-buttons-pro'); $target = 'target="_blank" rel="noopener"'; break;
                case 'email':     $href = 'mailto:' . esc_attr($value); $title = __('ایمیل', 'cta-buttons-pro'); break;
                case 'instagram': $ig = strpos($value,'http')===0 ? $value : 'https://instagram.com/'.ltrim($value,'@'); $href = esc_url($ig); $title = __('اینستاگرام', 'cta-buttons-pro'); $target = 'target="_blank" rel="noopener"'; break;
                case 'location':  $href = esc_url($value); $title = __('لوکیشن', 'cta-buttons-pro'); $target = 'target="_blank" rel="noopener"'; break;
            }
        ?>
            <a href="<?php echo $href; ?>" class="cta-floating-item cta-<?php echo esc_attr($channel); ?>" <?php echo $target; ?> title="<?php echo esc_attr($title); ?>">
                <?php echo cta_pro_get_icon($channel, 28); ?>
            </a>
        <?php endforeach; ?>
    </div>

    <div class="cta-floating-toggle" title="<?php esc_attr_e('راه‌های ارتباطی', 'cta-buttons-pro'); ?>">
        <svg width="32" height="32" viewBox="0 0 24 24" fill="white">
            <path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/>
        </svg>
    </div>
</div>
