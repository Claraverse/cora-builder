<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Guide_Header extends Base_Widget {

    public function get_name() { return 'cora_guide_header'; }
    public function get_title() { return __( 'Cora Guide Header', 'cora-builder' ); }

    protected function register_controls() {
        
        // --- TAB: CONTENT ---
        $this->start_controls_section('badges', [ 'label' => 'Header Badges' ]);
        $this->add_control('category', [ 'label' => 'Category Badge', 'type' => Controls_Manager::TEXT, 'default' => 'Marketing & Sales' ]);
        $this->add_control('level', [ 'label' => 'Difficulty Level', 'type' => Controls_Manager::TEXT, 'default' => 'Intermediate' ]);
        $this->add_control('rating', [ 'label' => 'Rating Value', 'type' => Controls_Manager::TEXT, 'default' => '4.8' ]);
        $this->end_controls_section();

        $this->start_controls_section('intro', [ 'label' => 'Guide Identity' ]);
        $this->add_control('title', [ 'label' => 'Guide Title', 'type' => Controls_Manager::TEXT, 'default' => 'Optimizing your checkout flow', 'dynamic' => ['active' => true] ]);
        $this->add_control('description', [ 'label' => 'Description', 'type' => Controls_Manager::TEXTAREA, 'default' => 'Reduce cart abandonment by streamlining your checkout process with these proven techniques.', 'dynamic' => ['active' => true] ]);
        $this->end_controls_section();

        $this->start_controls_section('stats', [ 'label' => 'Guide Metrics' ]);
        $this->add_control('duration', [ 'label' => 'Duration (min)', 'type' => Controls_Manager::TEXT, 'default' => '15 min' ]);
        $this->add_control('views', [ 'label' => 'Total Views', 'type' => Controls_Manager::TEXT, 'default' => '12.5K' ]);
        $this->add_control('steps', [ 'label' => 'Total Steps', 'type' => Controls_Manager::TEXT, 'default' => '5 Steps' ]);
        $this->add_control('chapters', [ 'label' => 'Total Chapters', 'type' => Controls_Manager::TEXT, 'default' => '5' ]);
        $this->end_controls_section();

        // --- TAB: STYLE ---
        $this->start_controls_section('style_layout', [ 'label' => 'Spatial Controls', 'tab' => Controls_Manager::TAB_STYLE ]);
        $this->add_responsive_control('section_padding', [
            'label' => 'Header Padding',
            'type' => Controls_Manager::DIMENSIONS,
            'selectors' => ['{{WRAPPER}} .guide-header-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="guide-header-container">
            <div class="guide-badge-row">
                <span class="badge-cat"><?php echo esc_html($settings['category']); ?></span>
                <span class="badge-dot">•</span>
                <span class="badge-lvl"><?php echo esc_html($settings['level']); ?></span>
                <span class="badge-rat"><i class="fas fa-star"></i> <?php echo esc_html($settings['rating']); ?> rating</span>
            </div>

            <div class="guide-identity-stack">
                <h1 class="guide-h1"><?php echo esc_html($settings['title']); ?></h1>
                <p class="guide-p"><?php echo esc_html($settings['description']); ?></p>
            </div>

            <div class="guide-metric-matrix">
                <div class="guide-metric-card">
                    <span class="metric-top"><i class="far fa-clock"></i> DURATION</span>
                    <span class="metric-val"><?php echo esc_html($settings['duration']); ?></span>
                </div>
                <div class="guide-metric-card">
                    <span class="metric-top"><i class="far fa-eye"></i> VIEWS</span>
                    <span class="metric-val"><?php echo esc_html($settings['views']); ?></span>
                </div>
                <div class="guide-metric-card">
                    <span class="metric-top"><i class="far fa-file-alt"></i> STEPS</span>
                    <span class="metric-val"><?php echo esc_html($settings['steps']); ?></span>
                </div>
                <div class="guide-metric-card">
                    <span class="metric-top"><i class="fas fa-layer-group"></i> CHAPTERS</span>
                    <span class="metric-val"><?php echo esc_html($settings['chapters']); ?></span>
                </div>
            </div>
        </div>
        <?php
    }
}