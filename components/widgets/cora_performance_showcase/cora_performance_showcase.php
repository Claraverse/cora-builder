<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Performance_Showcase extends Base_Widget {

    public function get_name() { return 'cora_performance_showcase'; }
    public function get_title() { return __( 'Cora Performance Showcase', 'cora-builder' ); }

    protected function register_controls() {
        
        // --- TAB: CONTENT ---
        $this->start_controls_section('content', [ 'label' => 'Showcase Content' ]);
        
        $this->add_control('mockup_img', [
            'label' => 'Dashboard Mockup',
            'type' => Controls_Manager::MEDIA,
            'dynamic' => ['active' => true],
            'default' => [ 'url' => Utils::get_placeholder_image_src() ], // Placeholder fallback
        ]);

        $this->add_control('title', [
            'label' => 'Headline',
            'type' => Controls_Manager::TEXT,
            'dynamic' => ['active' => true],
            'default' => 'Built for Sales, Not Just Style',
        ]);

        $this->add_control('description', [
            'label' => 'Subline',
            'type' => Controls_Manager::TEXTAREA,
            'dynamic' => ['active' => true],
            'default' => 'Your store is designed to convert visitors into buyers, blending sleek design with sales power.',
        ]);

        $this->end_controls_section();

        // --- TAB: STYLE - LAYOUT & CANVAS ---
        $this->start_controls_section('style_layout', [ 'label' => 'Layout & Canvas', 'tab' => Controls_Manager::TAB_STYLE ]);
        
        $this->add_responsive_control('section_padding', [
            'label' => 'Section Padding',
            'type' => Controls_Manager::DIMENSIONS,
            'selectors' => ['{{WRAPPER}} .cora-performance-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);

        $this->add_responsive_control('mockup_width', [
            'label' => 'Mockup Max Width',
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'range' => ['px' => ['min' => 200, 'max' => 1400]],
            'selectors' => ['{{WRAPPER}} .performance-canvas' => 'max-width: {{SIZE}}{{UNIT}} !important;'],
        ]);

        $this->add_control('canvas_bg', [
            'label' => 'Mockup Backdrop Color',
            'type' => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .performance-canvas' => 'background-color: {{VALUE}} !important;'],
        ]);

        $this->end_controls_section();

        // --- TAB: STYLE - TYPOGRAPHY ---
        $this->start_controls_section('style_typo', [ 'label' => 'Typography', 'tab' => Controls_Manager::TAB_STYLE ]);
        
        $this->add_group_control(Group_Control_Typography::get_type(), [ 'name' => 'title_typo', 'label' => 'Headline Typo', 'selector' => '{{WRAPPER}} .perf-h2' ]);
        $this->add_responsive_control('title_gap', [ 'label' => 'Headline Bottom Gap', 'type' => Controls_Manager::SLIDER, 'selectors' => ['{{WRAPPER}} .perf-h2' => 'margin-bottom: {{SIZE}}{{UNIT}} !important;'] ]);
        
        $this->add_group_control(Group_Control_Typography::get_type(), [ 'name' => 'desc_typo', 'label' => 'Subline Typo', 'selector' => '{{WRAPPER}} .perf-p' ]);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="cora-performance-wrapper">
            <div class="performance-canvas">
                <img src="<?php echo esc_url($settings['mockup_img']['url']); ?>" alt="Performance Dashboard">
            </div>

            <div class="performance-text-stack">
                <h2 class="perf-h2"><?php echo esc_html($settings['title']); ?></h2>
                <p class="perf-p"><?php echo esc_html($settings['description']); ?></p>
            </div>
        </div>
        <?php
    }
}