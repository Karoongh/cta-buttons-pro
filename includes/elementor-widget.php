<?php
// includes/elementor-widget.php
if (!defined('ABSPATH')) exit;

function cta_pro_register_elementor_widget() {
    if (!did_action('elementor/loaded') || !class_exists('\Elementor\Widget_Base')) return;

    class CTA_Buttons_Pro_Elementor_Widget extends \Elementor\Widget_Base {
        public function get_name() { return 'cta-buttons-pro'; }
        public function get_title() { return 'دکمه‌های تماس پرو'; }
        public function get_icon() { return 'eicon-button'; }
        public function get_categories() { return ['basic']; }

        protected function render() {
            if (\Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode()) {
                echo '<div style="padding:40px;background:#f0f8ff;text-align:center;border:2px dashed #0073aa;border-radius:12px;">
                        <h3>دکمه‌های تماس CTA Buttons Pro</h3>
                        <p>در سایت اصلی نمایش داده می‌شود</p>
                      </div>';
                return;
            }
            echo do_shortcode('[cta_buttons_pro]');
        }
    }

    \Elementor\Plugin::instance()->widgets_manager->register(new CTA_Buttons_Pro_Elementor_Widget());
}
add_action('elementor/widgets/register', 'cta_pro_register_elementor_widget');
