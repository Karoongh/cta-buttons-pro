<?php
// templates/fixed-bottom-bar.php
if (!defined('ABSPATH')) exit;

$phone = get_option('cta_pro_phone');
$whatsapp = get_option('cta_pro_whatsapp');
?>

<div class="cta-fixed-bar">
    <?php if ($phone): ?>
        <a href="tel:<?php echo esc_attr($phone); ?>" class="cta-fixed-btn cta-phone">
            <?php echo cta_pro_get_icon('phone', 26); ?>
            <span><?php _e('تماس سریع', 'cta-buttons-pro'); ?></span>
        </a>
    <?php endif; ?>

    <?php if ($whatsapp): ?>
        <a href="https://wa.me/<?php echo esc_attr(preg_replace('/\D/', '', $whatsapp)); ?>" 
           target="_blank" rel="noopener" class="cta-fixed-btn cta-whatsapp">
            <?php echo cta_pro_get_icon('whatsapp', 26); ?>
            <span><?php _e('واتساپ', 'cta-buttons-pro'); ?></span>
        </a>
    <?php endif; ?>
</div>
