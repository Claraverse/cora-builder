<?php
namespace Cora_Builder\Components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

if (!defined('ABSPATH'))
    exit;

class Brush_Headline extends Base_Widget
{

    public function get_name()
    {
        return 'brush_headline';
    }
    public function get_title()
    {
        return __('Brush Headline', 'cora-builder');
    }
    public function get_icon()
    {
        return 'eicon-t-letter';
    }

    protected function register_controls()
    {

        // --- TAB: CONTENT ---
        $this->start_controls_section('content_section', ['label' => __('Headline Content', 'cora-builder')]);

        $this->add_control('text_main', [
            'label' => __('Main Heading', 'cora-builder'),
            'type' => Controls_Manager::TEXTAREA,
            'default' => __('Master ecommerce with', 'cora-builder'),
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('text_highlight', [
            'label' => __('Highlighted Text', 'cora-builder'),
            'type' => Controls_Manager::TEXT,
            'default' => __('expert guides', 'cora-builder'),
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('text_sub', [
            'label' => __('Subtitle Text', 'cora-builder'),
            'type' => Controls_Manager::TEXTAREA,
            'default' => __('Comprehensive, step-by-step guides for scaling your Shopify business.', 'cora-builder'),
            'dynamic' => ['active' => true],
        ]);

        $this->add_responsive_control('align', [
            'label' => __('Alignment', 'cora-builder'),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'left' => ['title' => 'Left', 'icon' => 'eicon-text-align-left'],
                'center' => ['title' => 'Center', 'icon' => 'eicon-text-align-center'],
                'right' => ['title' => 'Right', 'icon' => 'eicon-text-align-right'],
            ],
            'selectors' => ['{{WRAPPER}} .cora-unit-container' => 'text-align: {{VALUE}};'],
        ]);




        $this->end_controls_section();

        // --- TAB: STYLE ---
        // 1. Load Common Spatial Controls (Gap/Padding/Max-Width)
        $this->register_common_spatial_controls();

        // 2. Typography & Colors
        $this->start_controls_section('style_typo', [
            'label' => __('Typography', 'cora-builder'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'main_typo',
            'selector' => '{{WRAPPER}} .cora-main-title',
        ]);

        $this->add_control('highlight_text_color', [
            'label' => __('Highlight Text Color', 'cora-builder'),
            'type' => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .cora-highlight-text' => 'color: {{VALUE}};'],
        ]);

        $this->end_controls_section();

        // 3. Brush SVG Positioning
        $this->start_controls_section('style_brush', [
            'label' => __('Brush Customizer', 'cora-builder'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('custom_svg', [
            'label' => __('SVG Code', 'cora-builder'),
            'type' => Controls_Manager::TEXTAREA,
            'default' => '<svg viewBox="0 0 500 150" preserveAspectRatio="none"><path d="M7.7,145.6C109,125,299.9,111.2,501,33.2c27.7-10.8,54.4,21.9,28.4,33.3C339.3,143.8,118.8,150.3,7.7,145.6Z"></path></svg>',
        ]);

        $this->add_control('brush_color', [
            'label' => __('Brush Color', 'cora-builder'),
            'type' => Controls_Manager::COLOR,
            'default' => '#000000',
            'selectors' => ['{{WRAPPER}} .brush-svg-wrapper' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('brush_x', [
            'label' => __('Offset X', 'cora-builder'),
            'type' => Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => -200, 'max' => 200]],
            'selectors' => ['{{WRAPPER}} .brush-svg-wrapper' => 'left: calc(50% + {{SIZE}}px);'],
        ]);

        $this->add_responsive_control('brush_y', [
            'label' => __('Offset Y', 'cora-builder'),
            'type' => Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => -100, 'max' => 100]],
            'selectors' => ['{{WRAPPER}} .brush-svg-wrapper' => 'top: calc(55% + {{SIZE}}px);'],
        ]);

        $this->add_responsive_control('brush_scale', [
            'label' => __('Scale', 'cora-builder'),
            'type' => Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 0.5, 'max' => 2, 'step' => 0.1]],
            'selectors' => ['{{WRAPPER}} .brush-svg-wrapper' => 'transform: translate(-50%, -50%) scale({{SIZE}});'],
        ]);

        $this->end_controls_section();
        // 1. Load the Advanced Spatial Controls (Max-Width, Gap, Padding)
        // $this->register_common_spatial_controls();

        // // 2. Add Dynamic Positioning (Fixed, Relative, Viewport)
        // $this->start_controls_section('style_positioning', [
        //     'label' => __('Positioning', 'cora-builder'),
        //     'tab' => Controls_Manager::TAB_STYLE,
        // ]);

        // $this->add_control('pos_mode', [
        //     'label' => __('Position', 'cora-builder'),
        //     'type' => Controls_Manager::SELECT,
        //     'options' => [
        //         'static' => 'Default',
        //         'relative' => 'Relative',
        //         'fixed' => 'Fixed',
        //         'viewport' => 'Viewport (vh/vw)',
        //     ],
        //     'default' => 'static',
        //     'selectors' => ['{{WRAPPER}} .cora-unit-container' => 'position: {{VALUE}};'],
        // ]);

        // $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="cora-unit-container cora-brush-headline-wrapper">
            <h1 class="cora-main-title">
                <?php echo esc_html($settings['text_main']); ?>
                <span class="cora-highlight-wrap">
                    <span class="cora-highlight-text"><?php echo esc_html($settings['text_highlight']); ?></span>
                    <div class="brush-svg-wrapper"><?php echo $settings['custom_svg']; ?></div>
                </span>
            </h1>
            <p class="cora-sub-title"><?php echo esc_html($settings['text_sub']); ?></p>
        </div>
        <?php
    }
}