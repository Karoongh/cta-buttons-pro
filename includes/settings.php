<?php
// includes/settings.php - نسخه نهایی 100% بدون پاک شدن تنظیمات
if (!defined('ABSPATH')) exit;

function cta_pro_admin_menu() {
    add_menu_page('CTA Buttons Pro', 'CTA Buttons Pro', 'manage_options', 'cta-buttons-pro', 'cta_pro_settings_page', 'dashicons-phone', 58);
}
add_action('admin_menu', 'cta_pro_admin_menu');

// ذخیره تنظیمات (همه تب‌ها در یک فرم)
add_action('admin_post_cta_pro_save', 'cta_pro_save_handler');
function cta_pro_save_handler() {
    if (!isset($_POST['cta_pro_nonce']) || !wp_verify_nonce($_POST['cta_pro_nonce'], 'cta_pro_save_settings')) {
        wp_die('خطای امنیتی');
    }
    if (!current_user_can('manage_options')) {
        wp_die('دسترسی غیرمجاز');
    }

    // --- ذخیره اطلاعات تماس ---
    $fields = ['phone','whatsapp','telegram','email','instagram','location'];
    foreach ($fields as $f) {
        update_option("cta_pro_$f", sanitize_text_field($_POST[$f] ?? ''));
    }

    // --- ذخیره آیکون‌ها ---
    foreach ($fields as $f) {
        $icon_id = absint($_POST["icon_$f"] ?? 0);
        if ($icon_id && wp_get_attachment_image($icon_id)) {
            update_option("cta_pro_icon_$f", $icon_id);
        } else {
            delete_option("cta_pro_icon_$f");
        }
    }

    // --- ذخیره ظاهر ---
    update_option('cta_pro_floating', isset($_POST['floating']) ? '1' : '0');
    update_option('cta_pro_fixed_bar', isset($_POST['fixed_bar']) ? '1' : '0');
    update_option('cta_pro_floating_position', in_array($_POST['position'] ?? '', ['left','right']) ? $_POST['position'] : 'left');
    update_option('cta_pro_glass_effect', isset($_POST['glass']) ? '1' : '0');
    update_option('cta_pro_glass_color', sanitize_hex_color($_POST['glass_color'] ?? '#ffffff'));
    update_option('cta_pro_glass_opacity', absint($_POST['opacity'] ?? 20));

    wp_redirect(admin_url('admin.php?page=cta-buttons-pro&saved=1'));
    exit;
}

function cta_pro_settings_page() {
    $tab = $_GET['tab'] ?? 'contact';
    $tabs = ['contact','icons','style','stats','details','help'];
    if (!in_array($tab, $tabs)) $tab = 'contact';
    ?>
    <div class="wrap">
        <h1>تنظیمات CTA Buttons Pro</h1>
        <?php if (isset($_GET['saved'])): ?>
            <div class="notice notice-success is-dismissible"><p>تنظیمات با موفقیت ذخیره شد.</p></div>
        <?php endif; ?>
        <?php if (isset($_GET['reset'])): ?>
            <div class="notice notice-success is-dismissible"><p>آمار ریست شد.</p></div>
        <?php endif; ?>

        <nav class="nav-tab-wrapper">
            <?php foreach ($tabs as $t): ?>
                <a href="?page=cta-buttons-pro&tab=<?php echo esc_attr($t); ?>" class="nav-tab <?php echo $tab === $t ? 'nav-tab-active' : ''; ?>">
                    <?php
                    echo esc_html(match($t) {
                        'contact' => 'اطلاعات تماس',
                        'icons' => 'آیکون سفارشی',
                        'style' => 'ظاهر',
                        'stats' => 'آمار',
                        'details' => 'جزئیات کلیک‌ها',
                        'help' => 'راهنما',
                        default => ucfirst($t)
                    });
                    ?>
                </a>
            <?php endforeach; ?>
        </nav>

        <!-- یک فرم واحد که همه فیلدها را دارد -->
        <form method="post" action="<?php echo admin_url('admin-post.php?action=cta_pro_save'); ?>">
            <?php wp_nonce_field('cta_pro_save_settings', 'cta_pro_nonce'); ?>

            <!-- همه فیلدها همیشه در فرم هستند (حتی مخفی) -->
            <?php
            $all_fields = ['phone','whatsapp','telegram','email','instagram','location'];
            foreach ($all_fields as $f): ?>
                <input type="hidden" name="<?php echo $f; ?>" value="<?php echo esc_attr(get_option("cta_pro_$f")); ?>">
            <?php endforeach; ?>

            <?php foreach ($all_fields as $f):
                $icon_id = get_option("cta_pro_icon_$f"); ?>
                <input type="hidden" name="icon_<?php echo $f; ?>" value="<?php echo esc_attr($icon_id); ?>">
            <?php endforeach; ?>

            <input type="hidden" name="floating" value="<?php echo get_option('cta_pro_floating','1'); ?>">
            <input type="hidden" name="fixed_bar" value="<?php echo get_option('cta_pro_fixed_bar','1'); ?>">
            <input type="hidden" name="position" value="<?php echo get_option('cta_pro_floating_position','left'); ?>">
            <input type="hidden" name="glass" value="<?php echo get_option('cta_pro_glass_effect','1'); ?>">
            <input type="hidden" name="glass_color" value="<?php echo esc_attr(get_option('cta_pro_glass_color','#ffffff')); ?>">
            <input type="hidden" name="opacity" value="<?php echo esc_attr(get_option('cta_pro_glass_opacity',20)); ?>">

            <?php if ($tab === 'contact'): ?>
                <table class="form-table">
                    <?php foreach ($all_fields as $f): ?>
                        <tr>
                            <th><?php echo ucfirst(str_replace(['whatsapp','telegram','instagram'], ['واتساپ','تلگرام','اینستاگرام'], $f)); ?></th>
                            <td><input name="<?php echo $f; ?>" type="text" class="regular-text" value="<?php echo esc_attr(get_option("cta_pro_$f")); ?>"></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>

            <?php if ($tab === 'icons'): ?>
                <table class="form-table">
                    <?php foreach ($all_fields as $ch):
                        $icon_id = get_option("cta_pro_icon_$ch");
                        $icon_url = $icon_id ? wp_get_attachment_image_url($icon_id, 'thumbnail') : CTA_PRO_URL . 'assets/icons/default-' . $ch . '.png';
                    ?>
                        <tr>
                            <th><?php echo ucfirst(str_replace(['whatsapp','telegram','instagram'], ['واتساپ','تلگرام','اینستاگرام'], $ch)); ?></th>
                            <td>
                                <div class="cta-icon-uploader" style="display:flex;align-items:center;gap:15px;">
                                    <img src="<?php echo esc_url($icon_url); ?>" data-src="<?php echo CTA_PRO_URL . 'assets/icons/default-' . $ch . '.png'; ?>" width="60" height="60" style="border-radius:12px;">
                                    <div>
                                        <input type="hidden" name="icon_<?php echo $ch; ?>" value="<?php echo esc_attr($icon_id); ?>">
                                        <button type="button" class="button upload-icon-btn">انتخاب</button>
                                        <button type="button" class="button remove-icon-btn" style="<?php echo !$icon_id ? 'display:none' : ''; ?>">حذف</button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>

            <?php if ($tab === 'style'): ?>
                <table class="form-table">
                    <tr><th>دکمه شناور</th><td><label><input type="checkbox" name="floating" value="1" <?php checked(get_option('cta_pro_floating','1'),'1'); ?>> فعال</label></td></tr>
                    <tr><th>نوار پایین (موبایل)</th><td><label><input type="checkbox" name="fixed_bar" value="1" <?php checked(get_option('cta_pro_fixed_bar','1'),'1'); ?>> فعال</label></td></tr>
                    <tr><th>موقعیت</th><td>
                        <select name="position">
                            <option value="left" <?php selected(get_option('cta_pro_floating_position','left'),'left'); ?>>چپ</option>
                            <option value="right" <?php selected(get_option('cta_pro_floating_position','left'),'right'); ?>>راست</option>
                        </select>
                    </td></tr>
                    <tr><th>افکت شیشه‌ای</th><td><label><input type="checkbox" name="glass" value="1" <?php checked(get_option('cta_pro_glass_effect','1'),'1'); ?>> فعال</label></td></tr>
                    <tr><th>رنگ</th><td><input type="color" name="glass_color" value="<?php echo esc_attr(get_option('cta_pro_glass_color','#ffffff')); ?>"></td></tr>
                    <tr><th>شفافیت</th><td><input type="range" name="opacity" min="5" max="80" value="<?php echo esc_attr(get_option('cta_pro_glass_opacity',20)); ?>"><span class="opacity-value"><?php echo get_option('cta_pro_glass_opacity',20); ?>%</span></td></tr>
                </table>
            <?php endif; ?>

            <?php if ($tab === 'stats'): ?>
                <?php if (isset($_GET['reset'])): ?>
                    <div class="notice notice-success is-dismissible">
                        <p><?php _e('آمار کلیک‌ها با موفقیت ریست شد!', 'cta-buttons-pro'); ?></p>
                    </div>
                <?php endif; ?>
                <?php include CTA_PRO_PATH . 'includes/stats.php'; ?>
            <?php endif; ?>

            <?php if ($tab === 'help'): ?>
                <div class="card">
                    <h3>شورت‌کدهای افزونه CTA Buttons Pro</h3>
                    <p>برای نمایش همه دکمه‌ها در یک مکان: <code>[cta_buttons_pro]</code></p>
                    <h4>شورت‌کدهای اختصاصی هر کانال</h4>
                    <table class="widefat fixed striped">
                        <thead>
                            <tr>
                                <th><strong>کانال</strong></th>
                                <th><strong>شورت‌کد اصلی (کوتاه و راحت)</strong></th>
                                <th><strong>شورت‌کد کامل (قابل تنظیم)</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td>تلفن</td><td><code>[cta_phone]</code></td><td><code>[cta_button channel="phone" label="تماس با ما"]</code></td></tr>
                            <tr><td>واتساپ</td><td><code>[cta_whatsapp]</code></td><td><code>[cta_button channel="whatsapp" label="چت در واتساپ"]</code></td></tr>
                            <tr><td>تلگرام</td><td><code>[cta_telegram]</code></td><td><code>[cta_button channel="telegram" label="تلگرام"]</code></td></tr>
                            <tr><td>ایمیل</td><td><code>[cta_email]</code></td><td><code>[cta_button channel="email" label="ارسال ایمیل"]</code></td></tr>
                            <tr><td>اینستاگرام</td><td><code>[cta_instagram]</code></td><td><code>[cta_button channel="instagram" label="اینستاگرام"]</code></td></tr>
                            <tr><td>لوکیشن (نقشه)</td><td><code>[cta_location]</code></td><td><code>[cta_button channel="location" label="آدرس ما"]</code></td></tr>
                        </tbody>
                    </table>
                    <p><strong>نکته:</strong> در شورت‌کد کامل، می‌توانید پارامترهای <code>label</code> (متن دکمه) و <code>size</code> (اندازه آیکون، پیش‌فرض ۲۴) را سفارشی کنید.</p>
                </div>
            <?php endif; ?>

            <?php if ($tab === 'details'): ?>
                <div class="card">
                    <h3>جزئیات پیشرفته کلیک‌ها</h3>
                    <p>در این بخش جزئیات دقیق هر کلیک (زمان، IP، صفحه منبع و عنوان صفحه) نمایش داده می‌شود.</p>

                    <?php
                    $channels = ['phone','whatsapp','telegram','email','instagram','location'];
                    $has_details = false;

                    foreach ($channels as $ch) {
                        $details = get_option("cta_pro_clicks_detail_$ch", []);
                        if (!empty($details)) {
                            $has_details = true;
                            $name = match($ch) {
                                'whatsapp' => 'واتساپ',
                                'telegram' => 'تلگرام',
                                'instagram' => 'اینستاگرام',
                                default => ucfirst($ch)
                            };
                            ?>
                            <h4><?php echo $name; ?> (<?php echo count($details); ?> کلیک)</h4>
                            <table class="widefat fixed striped">
                                <thead>
                                    <tr>
                                        <th><strong>زمان (به وقت سایت)</strong></th>
                                        <th><strong>آدرس IP</strong></th>
                                        <th><strong>صفحه منبع (URL)</strong></th>
                                        <th><strong>عنوان صفحه</strong></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach (array_reverse($details) as $click): ?>
                                        <tr>
                                            <td><?php echo wp_date('d F Y - H:i:s', $click['time']); ?></td>
                                            <td><?php echo esc_html($click['ip']); ?></td>
                                            <td><a href="<?php echo esc_url($click['page_url']); ?>" target="_blank" rel="noopener"><?php echo esc_html($click['page_url']); ?></a></td>
                                            <td><?php echo esc_html($click['page_title']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <br>
                            <?php
                        }
                    }

                    if (!$has_details): ?>
                        <p>هنوز هیچ کلیک پیشرفته‌ای ثبت نشده است.</p>
                    <?php endif; ?>

                    <?php if ($has_details): ?>
                        <form method="post" action="<?php echo admin_url('admin-post.php'); ?>" onsubmit="return confirm('آیا از ریست کامل جزئیات کلیک‌ها مطمئن هستید؟ این عمل غیرقابل بازگشت است.');">
                            <input type="hidden" name="action" value="cta_pro_reset_details">
                            <?php wp_nonce_field('cta_pro_reset_details', 'cta_pro_reset_details_nonce'); ?>
                            <p class="submit">
                                <button type="submit" class="button button-secondary" style="background:#d63638;color:white;">
                                    ریست کامل جزئیات کلیک‌ها
                                </button>
                            </p>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- دکمه ذخیره تغییرات - همیشه در انتها و خارج از تب‌ها -->
            <p class="submit">
                <input type="submit" class="button-primary" value="ذخیره همه تغییرات">
            </p>
        </form>
    </div>
    <?php
}
