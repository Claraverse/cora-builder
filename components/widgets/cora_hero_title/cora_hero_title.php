<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

if (!defined('ABSPATH')) exit;

class Cora_Hero_Title extends Base_Widget
{
    public function get_name() { return 'cora_hero_title'; }
    public function get_title() { return __('Cora Hero Title', 'cora-builder'); }
    public function get_icon() { return 'eicon-t-letter'; }

    // Load Fredoka Font Automatically
    public function get_style_depends() {
        wp_register_style('google-font-fredoka', 'https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&display=swap', [], null);
        return ['google-font-fredoka'];
    }

    protected function register_controls()
    {
        // --- TAB 1: CONTENT ---
        $this->start_controls_section('section_content', ['label' => __('Headline Content', 'cora-builder')]);

        // LINE 1 INPUTS
        $this->add_control('l1_text_start', [ 'label' => 'Line 1: Start ("From")', 'type' => Controls_Manager::TEXT, 'default' => 'From', 'dynamic' => ['active' => true] ]);
        $this->add_control('l1_sketch', [ 'label' => 'Line 1: Sketch Word ("Idea")', 'type' => Controls_Manager::TEXT, 'default' => 'Idea', 'dynamic' => ['active' => true] ]);
        $this->add_control('l1_text_mid', [ 'label' => 'Line 1: Mid ("To")', 'type' => Controls_Manager::TEXT, 'default' => 'To', 'dynamic' => ['active' => true] ]);
        $this->add_control('l1_highlight', [ 'label' => 'Line 1: Green Word ("Brand")', 'type' => Controls_Manager::TEXT, 'default' => 'Brand', 'dynamic' => ['active' => true] ]);

        // LINE 2 INPUTS
        $this->add_control('l2_text_start', [ 'label' => 'Line 2: Start ("That")', 'type' => Controls_Manager::TEXT, 'default' => 'That', 'dynamic' => ['active' => true], 'separator' => 'before' ]);
        $this->add_control('l2_highlight', [ 'label' => 'Line 2: Green Word ("Sells")', 'type' => Controls_Manager::TEXT, 'default' => 'Sells', 'dynamic' => ['active' => true] ]);

        // SUBTITLE
        $this->add_control('subtitle', [
            'label' => 'Subtitle',
            'type' => Controls_Manager::TEXTAREA,
            'default' => 'Launch faster, sell smarter, and scale without the tech stress. Claraverse gives you design, development, hosting, and marketing — all done-for-you under one roof.',
            'dynamic' => ['active' => true],
            'separator' => 'before'
        ]);

        $this->end_controls_section();

        // --- TAB 2: STYLE (Design Reset) ---
        $this->start_controls_section('style_reset', [ 'label' => 'Design Reset', 'tab' => Controls_Manager::TAB_STYLE ]);

        // Visual Status Bar
        $this->add_control('reset_info', [
            'type' => Controls_Manager::RAW_HTML,
            'raw'  => '<div style="background:#f1f4ff; color:#1e2b5e; padding:12px; border-radius:12px; font-weight:bold; text-align:center; border:1px solid #dbeafe;">
                        <i class="eicon-check-circle"></i> Font: Fredoka Active
                      </div>',
        ]);

        $this->add_control('structural_reset', [
            'type' => Controls_Manager::HIDDEN,
            'default' => 'reset',
            'selectors' => [
                // Container Centering
                '{{WRAPPER}} .hero-title-container' => 'display: flex; flex-direction: column; align-items: center; text-align: center; width: 100%; max-width: 900px; margin: 0 auto; gap: 24px;',
                
                // Main Headline Reset (Fredoka Default)
                '{{WRAPPER}} .hero-title' => 'font-family: "Fredoka", sans-serif; font-size: 64px; font-weight: 800; line-height: 1.1; color: #1e293b; margin: 0 !important; letter-spacing: -0.02em;',
                
                // The "Sketch" Word (Kept as cursive placeholder)
                '{{WRAPPER}} .sketch-word' => 'font-family: cursive; position: relative; display: inline-block; color: transparent; -webkit-text-stroke: 1.5px #1e293b; padding: 0 4px;',
                
                // The "Green" Highlight Words
                '{{WRAPPER}} .highlight-word' => 'color: #22c55e; font-weight: 800;',

                // Subtitle Styling (Fredoka Default)
                '{{WRAPPER}} .hero-subtitle' => 'font-family: "Fredoka", sans-serif; font-size: 18px; line-height: 1.6; color: #64748b; margin: 0 !important; max-width: 700px;',

                // Responsive adjustments
                '@media (max-width: 767px)' => [
                    '{{WRAPPER}} .hero-title' => 'font-size: 36px; line-height: 1.2;',
                    '{{WRAPPER}} .sketch-word' => '-webkit-text-stroke: 1px #1e293b;',
                ],
            ],
        ]);
        $this->end_controls_section();

        // --- ELEMENT ENGINES ---

        // 1. Sketch Word Engine
        $this->start_controls_section('sketch_style', ['label' => 'Sketch Word Style', 'tab' => Controls_Manager::TAB_STYLE]);
        $this->add_control('sketch_stroke_color', [ 'label' => 'Outline Color', 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .sketch-word' => '-webkit-text-stroke-color: {{VALUE}};'] ]);
        $this->add_group_control(Group_Control_Typography::get_type(), ['name' => 'sketch_typo', 'selector' => '{{WRAPPER}} .sketch-word', 'label' => 'Sketch Font']);
        $this->end_controls_section();

        // 2. Highlight Word Engine
        $this->start_controls_section('highlight_style', ['label' => 'Highlight (Green) Style', 'tab' => Controls_Manager::TAB_STYLE]);
        $this->add_control('highlight_color', [ 'label' => 'Text Color', 'type' => Controls_Manager::COLOR, 'default' => '#22c55e', 'selectors' => ['{{WRAPPER}} .highlight-word' => 'color: {{VALUE}};'] ]);
        $this->end_controls_section();

        // 3. Global Typography
        $this->register_text_styling_controls('main_typo', 'Headline Typography', '{{WRAPPER}} .hero-title');
        $this->register_text_styling_controls('sub_typo', 'Subtitle Typography', '{{WRAPPER}} .hero-subtitle');

        // 4. Layout
        $this->register_global_design_controls('.hero-title-container');
        $this->register_layout_geometry('.hero-title-container', 'hero_geo', 'Container Layout');
        $this->register_common_spatial_controls();
        $this->register_interaction_motion();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="cora-unit-container hero-title-container">
            <h1 class="hero-title">
                <span class="line-block">
                    <?php if($settings['l1_text_start']) : ?><span class="regular-word"><?php echo esc_html($settings['l1_text_start']); ?></span><?php endif; ?>
                    <?php if($settings['l1_sketch']) : ?><span class="sketch-word"><?php echo esc_html($settings['l1_sketch']); ?></span><?php endif; ?>
                    <?php if($settings['l1_text_mid']) : ?><span class="regular-word"><?php echo esc_html($settings['l1_text_mid']); ?></span><?php endif; ?>
                    <?php if($settings['l1_highlight']) : ?><span class="highlight-word"><?php echo esc_html($settings['l1_highlight']); ?></span><?php endif; ?>
                </span>
                <br>
                <span class="line-block">
                    <?php if($settings['l2_text_start']) : ?><span class="regular-word"><?php echo esc_html($settings['l2_text_start']); ?></span><?php endif; ?>
                    <?php if($settings['l2_highlight']) : ?><span class="highlight-word"><?php echo esc_html($settings['l2_highlight']); ?></span><?php endif; ?>
                </span>
            </h1>
            
            <p class="hero-subtitle"><?php echo esc_html($settings['subtitle']); ?></p>
        </div>
        <?php
    }
}