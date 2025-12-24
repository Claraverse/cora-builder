<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;

if (!defined('ABSPATH'))
    exit;

class Brush_Headline_Two extends Base_Widget
{

    public function get_name()
    {
        return 'brush_headline_two';
    }
    public function get_title()
    {
        return __('Cora Brush Headline', 'cora-builder');
    }
    public function get_icon()
    {
        return 'eicon-t-letter';
    }

    protected function register_controls()
    {

        // --- TAB: CONTENT ---
        $this->start_controls_section('content_section', ['label' => __('Headline Content', 'cora-builder')]);

        $this->add_control('text_before', [
            'label' => __('Text Before Highlight', 'cora-builder'),
            'type' => Controls_Manager::TEXT,
            'default' => __('The only ecommerce course you need to be among ', 'cora-builder'),
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('text_highlight', [
            'label' => __('Highlighted Word', 'cora-builder'),
            'type' => Controls_Manager::TEXT,
            'default' => __('top 1%', 'cora-builder'),
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('text_after', [
            'label' => __('Text After Highlight', 'cora-builder'),
            'type' => Controls_Manager::TEXT,
            'default' => __(' entrepreneurs', 'cora-builder'),
            'dynamic' => ['active' => true],
        ]);

        $this->end_controls_section();

        // --- TAB: STYLE - BRUSH CUSTOMIZER ---
        $this->start_controls_section('style_brush', ['label' => __('Brush Styling', 'cora-builder'), 'tab' => Controls_Manager::TAB_STYLE]);

        $this->add_control('custom_svg', [
            'label' => __('SVG Code', 'cora-builder'),
            'type' => Controls_Manager::TEXTAREA,
            'default' => '<svg viewBox="0 0 500 150" preserveAspectRatio="none"><path d="M7.7,145.6C109,125,299.9,111.2,501,33.2c27.7-10.8,54.4,21.9,28.4,33.3C339.3,143.8,118.8,150.3,7.7,145.6Z"></path></svg>',
        ]);

        $this->add_control('brush_color', [
            'label' => __('Brush Color', 'cora-builder'),
            'type' => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .brush-svg-wrapper' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('brush_x_offset', [
            'label' => __('X Position', 'cora-builder'),
            'type' => Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => -100, 'max' => 100]],
            'selectors' => ['{{WRAPPER}} .brush-svg-wrapper' => 'left: calc(50% + {{SIZE}}px);'],
        ]);

        $this->add_responsive_control('brush_y_offset', [
            'label' => __('Y Position', 'cora-builder'),
            'type' => Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => -100, 'max' => 100]],
            'selectors' => ['{{WRAPPER}} .brush-svg-wrapper' => 'top: calc(50% + {{SIZE}}px);'],
        ]);

        $this->add_responsive_control('brush_scale', [
            'label' => __('Brush Scale', 'cora-builder'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 0.5, 'max' => 2.5, 'step' => 0.1]],
            'selectors' => ['{{WRAPPER}} .brush-svg-wrapper svg' => 'transform: translate(-50%, -50%) scale({{SIZE}});'],
        ]);

        $this->end_controls_section();

        // --- TAB: STYLE - EXHAUSTIVE TYPOGRAPHY ENGINE ---

        // 1. Base Headline Styling (Before & After)
        $this->register_text_styling_controls('base_headline', 'Main Headline Styling', '{{WRAPPER}} .cora-brush-title');

        // 2. Highlight Styling (Individual control for the brush-over text)
        $this->register_text_styling_controls('highlight_text', 'Highlighted Word Styling', '{{WRAPPER}} .cora-highlight-text');

        // 3. Spacing Core
        $this->register_common_spatial_controls();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="cora-unit-container cora-brush-headline-wrapper">
            <h2 class="cora-brush-title">
                <?php echo esc_html($settings['text_before']); ?>
                <span class="cora-highlight-wrap">
                    <span class="cora-highlight-text"><?php echo esc_html($settings['text_highlight']); ?></span>
                    <div class="brush-svg-wrapper"><?php echo $settings['custom_svg']; ?></div>
                </span>
                <?php echo esc_html($settings['text_after']); ?>
            </h2>
        </div>
        <?php
    }
}