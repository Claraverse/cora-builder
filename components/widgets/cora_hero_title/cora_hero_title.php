<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;

if (!defined('ABSPATH'))
    exit;

class Cora_Hero_Title extends Base_Widget
{

    public function get_name()
    {
        return 'cora_hero_title';
    }
    public function get_title()
    {
        return __('Cora Hero Title', 'cora-builder');
    }
    public function get_icon()
    {
        return 'eicon-t-letter';
    }

    protected function register_controls()
    {

        // --- TAB 1: CONTENT ---
        $this->start_controls_section('section_content', ['label' => __('Headline Content', 'cora-builder')]);

        $this->add_control('title_pre', [
            'label' => 'Prefix Text',
            'type' => Controls_Manager::TEXT,
            'dynamic' => ['active' => true]
        ]);

        $this->add_control('title_sketch', [
            'label' => 'Sketch Word',
            'type' => Controls_Manager::TEXT,
            'dynamic' => ['active' => true]
        ]);

        $this->add_control('title_mid', [
            'label' => 'Middle Text',
            'type' => Controls_Manager::TEXT,
            'dynamic' => ['active' => true]
        ]);

        $this->add_control('title_highlight_1', [
            'label' => 'Highlight Word',
            'type' => Controls_Manager::TEXT,
            'dynamic' => ['active' => true]
        ]);

        $this->add_control('subtitle', [
            'label' => 'Subtitle',
            'type' => Controls_Manager::TEXTAREA,
            'dynamic' => ['active' => true]
        ]);

        $this->end_controls_section();
        $this->register_layout_geometry('.hero-title-container');

        // --- TAB 2: STYLE (Widget-Specific Aesthetics) ---
       // --- ENRICHED SKETCH & HIGHLIGHT SKIN ---
        $this->start_controls_section('style_sketch_local', [ 
            'label' => 'Sketch & Highlight Skin', 
            'tab'   => Controls_Manager::TAB_STYLE // Note: Using TAB_STYLE for visual consistency
        ]);

        // 1. HIGHLIGHT CONTROLS (Green Word)
        $this->add_control('highlight_heading', [
            'label' => __('Highlight Settings', 'cora-builder'),
            'type'  => Controls_Manager::HEADING,
        ]);

        $this->add_control('highlight_color', [
            'label'     => 'Highlight Color',
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .highlight-word' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'highlight_typo_local',
                'selector' => '{{WRAPPER}} .highlight-word',
            ]
        );

        // 2. SKETCH CONTROLS (Outlined Word)
        $this->add_control('sketch_heading', [
            'label'     => __('Sketch Settings', 'cora-builder'),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('sketch_fill_color', [
            'label'     => 'Inner Fill Color',
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .sketch-word' => 'color: {{VALUE}};'],
            'description' => 'Set to transparent for the classic outline look.',
        ]);

        $this->add_control('sketch_stroke_color', [
            'label'     => 'Stroke Color',
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .sketch-word' => '-webkit-text-stroke-color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('sketch_stroke_width_local', [
            'label'     => 'Stroke Width',
            'type'      => Controls_Manager::SLIDER,
            'range'     => ['px' => ['min' => 0, 'max' => 10]],
            'selectors' => ['{{WRAPPER}} .sketch-word' => '-webkit-text-stroke-width: {{SIZE}}px;'],
        ]);

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'sketch_typo_local',
                'selector' => '{{WRAPPER}} .sketch-word',
            ]
        );

        // 3. ADVANCED EFFECTS
        $this->add_control('sketch_blend_mode', [
            'label'     => 'Blend Mode',
            'type'      => Controls_Manager::SELECT,
            'options'   => [
                'normal'   => 'Normal',
                'multiply' => 'Multiply',
                'overlay'  => 'Overlay',
                'screen'   => 'Screen',
            ],
            'default'   => 'normal',
            'selectors' => ['{{WRAPPER}} .sketch-word' => 'mix-blend-mode: {{VALUE}};'],
        ]);

        $this->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name'     => 'sketch_shadow',
                'selector' => '{{WRAPPER}} .sketch-word',
            ]
        );

        $this->end_controls_section();

        // Load Global Typography Engines into Style Tab
        $this->register_text_styling_controls('main_headline', 'Main Headline Typography', '{{WRAPPER}} .hero-title');
        $this->register_text_styling_controls('sub_headline', 'Subtitle Typography', '{{WRAPPER}} .hero-subtitle');

        // --- TAB 3: GLOBAL (4th Tab - Architectural Design System) ---
        // These call the modular engines from Base_Widget
        $this->register_global_design_controls('.hero-title-container');
        $this->register_surface_styles('.hero-title-container');
        $this->register_common_spatial_controls();

        $this->register_alignment_controls('main_headline', 'Main Headline Typography', '{{WRAPPER}} .hero-title');
        // --- TAB 4: ADVANCED (Interactions & Motion) ---
        // Moves GSAP controls to the Advanced tab as per SaaS standards
        $this->register_interaction_motion();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="cora-unit-container hero-title-container">
            <h1 class="hero-title">
                <span class="regular-word"><?php echo esc_html($settings['title_pre']); ?></span>
                <span class="sketch-word"><?php echo esc_html($settings['title_sketch']); ?></span>
                <span class="regular-word"><?php echo esc_html($settings['title_mid']); ?></span>
                <span class="highlight-word"><?php echo esc_html($settings['title_highlight_1']); ?></span>
            </h1>
            <p class="hero-subtitle"><?php echo esc_html($settings['subtitle']); ?></p>
        </div>
        <?php
    }
}