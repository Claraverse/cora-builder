<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;

if (!defined('ABSPATH'))
    exit;

class Cora_Mobile_Action_Bar extends Base_Widget
{

    public function get_name()
    {
        return 'cora_mobile_action_bar';
    }
    public function get_title()
    {
        return __('Cora Mobile Action Bar', 'cora-builder');
    }
    public function get_icon()
    {
        return 'eicon-anchor';
    }

    protected function register_controls()
    {

        // --- TAB: CONTENT ---
        $this->start_controls_section('content', ['label' => __('Action Content', 'cora-builder')]);
        $this->add_control('label', ['label' => 'Label', 'type' => Controls_Manager::TEXT, 'default' => 'Join 50k+ Entrepreneurs', 'dynamic' => ['active' => true]]);
        $this->add_control('btn_text', ['label' => 'Button Text', 'type' => Controls_Manager::TEXT, 'default' => 'Enroll Now', 'dynamic' => ['active' => true]]);
        $this->add_control('url', ['label' => 'Link', 'type' => Controls_Manager::URL, 'dynamic' => ['active' => true]]);
        $this->end_controls_section();

        // --- TAB: STYLE - FLOATING PILL ---
        $this->start_controls_section('style_pill', ['label' => 'Floating Pill Styling', 'tab' => Controls_Manager::TAB_STYLE]);
        $this->add_control('pill_bg', ['label' => 'Background', 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .cora-sticky-pill' => 'background-color: {{VALUE}};']]);
        $this->add_responsive_control('pill_padding', ['label' => 'Pill Padding', 'type' => Controls_Manager::DIMENSIONS, 'selectors' => ['{{WRAPPER}} .cora-sticky-pill' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']]);
        $this->add_responsive_control('pill_radius', ['label' => 'Pill Radius', 'type' => Controls_Manager::SLIDER, 'selectors' => ['{{WRAPPER}} .cora-sticky-pill' => 'border-radius: {{SIZE}}px;']]);
        $this->end_controls_section();

        // Typography & Spatial Engines
        $this->register_text_styling_controls('label_style', 'Label Typography', '{{WRAPPER}} .sticky-label');
        $this->register_common_spatial_controls();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        // Check if we are in the editor to add a 'placeholder' class
        $is_editor = \Elementor\Plugin::$instance->editor->is_edit_mode();
        $wrapper_class = $is_editor ? 'cora-mobile-sticky-wrapper is-editor' : 'cora-mobile-sticky-wrapper';
        ?>
        <div class="cora-unit-container <?php echo esc_attr($wrapper_class); ?>">
            <?php if ($is_editor): ?>
                <div class="editor-label-tag">Mobile Sticky Preview</div>
            <?php endif; ?>

            <div class="cora-sticky-pill">
                <div class="sticky-label"><?php echo esc_html($settings['label']); ?></div>
                <a <?php echo $this->get_render_attribute_string('url'); ?> class="sticky-cta-btn">
                    <?php echo esc_html($settings['btn_text']); ?> <i class="fas fa-arrow-right" aria-hidden="true"></i>
                </a>
            </div>
        </div>
        <?php
    }
}