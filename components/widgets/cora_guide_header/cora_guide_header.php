<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Guide_Header extends Base_Widget {

    public function get_name() { return 'cora_guide_header'; }
    public function get_title() { return __( 'Cora Guide Header', 'cora-builder' ); }
    public function get_icon() { return 'eicon-ban-title'; }

    protected function register_controls() {
        
        // --- TAB: CONTENT - BADGES ---
        $this->start_controls_section('section_badges', [ 'label' => 'Header Badges' ]);
        
        $this->add_control('badge_cat', [ 
            'label' => 'Category Text', 
            'type' => Controls_Manager::TEXT, 
            'default' => 'Marketing & Sales',
            'dynamic' => ['active' => true]
        ]);

        $this->add_control('badge_level', [ 
            'label' => 'Level Text', 
            'type' => Controls_Manager::TEXT, 
            'default' => 'Intermediate',
            'dynamic' => ['active' => true]
        ]);

        $this->add_control('badge_rating', [ 
            'label' => 'Rating Text', 
            'type' => Controls_Manager::TEXT, 
            'default' => '4.8 rating',
            'dynamic' => ['active' => true]
        ]);
        
        $this->end_controls_section();

        // --- TAB: CONTENT - INTRO ---
        $this->start_controls_section('section_intro', [ 'label' => 'Guide Identity' ]);
        
        $this->add_control('title', [ 
            'label' => 'Guide Title', 
            'type' => Controls_Manager::TEXTAREA, 
            'default' => 'Optimizing your checkout flow', 
            'dynamic' => ['active' => true] 
        ]);

        $this->add_control('description', [ 
            'label' => 'Description', 
            'type' => Controls_Manager::TEXTAREA, 
            'default' => 'Reduce cart abandonment by streamlining your checkout process with these proven techniques.', 
            'dynamic' => ['active' => true] 
        ]);
        
        $this->end_controls_section();

        // --- TAB: CONTENT - METRICS ---
        $this->start_controls_section('section_metrics', [ 'label' => 'Guide Metrics' ]);
        
        $this->add_control('metric_1_label', [ 'label' => 'Metric 1 Label', 'type' => Controls_Manager::TEXT, 'default' => 'DURATION' ]);
        $this->add_control('metric_1_val', [ 'label' => 'Metric 1 Value', 'type' => Controls_Manager::TEXT, 'default' => '15 min', 'dynamic' => ['active' => true] ]);
        
        $this->add_control('metric_2_label', [ 'label' => 'Metric 2 Label', 'type' => Controls_Manager::TEXT, 'default' => 'VIEWS' ]);
        $this->add_control('metric_2_val', [ 'label' => 'Metric 2 Value', 'type' => Controls_Manager::TEXT, 'default' => '12.5K', 'dynamic' => ['active' => true] ]);
        
        $this->add_control('metric_3_label', [ 'label' => 'Metric 3 Label', 'type' => Controls_Manager::TEXT, 'default' => 'STEPS' ]);
        $this->add_control('metric_3_val', [ 'label' => 'Metric 3 Value', 'type' => Controls_Manager::TEXT, 'default' => '5 Steps', 'dynamic' => ['active' => true] ]);
        
        $this->add_control('metric_4_label', [ 'label' => 'Metric 4 Label', 'type' => Controls_Manager::TEXT, 'default' => 'CHAPTERS' ]);
        $this->add_control('metric_4_val', [ 'label' => 'Metric 4 Value', 'type' => Controls_Manager::TEXT, 'default' => '5', 'dynamic' => ['active' => true] ]);
        
        $this->end_controls_section();

        // --- TAB: STYLE - LAYOUT ---
        $this->start_controls_section('style_layout', [ 'label' => 'Container', 'tab' => Controls_Manager::TAB_STYLE ]);
        
        $this->add_responsive_control('grid_columns', [
            'label' => 'Metric Columns',
            'type' => Controls_Manager::SELECT,
            'default' => '4',
            'tablet_default' => '2',
            'mobile_default' => '2',
            'options' => [ '1'=>'1', '2'=>'2', '3'=>'3', '4'=>'4' ],
            'selectors' => [ '{{WRAPPER}} .guide-metric-matrix' => 'grid-template-columns: repeat({{VALUE}}, 1fr);' ],
        ]);

        $this->add_responsive_control('grid_gap', [
            'label' => 'Grid Gap',
            'type' => Controls_Manager::SLIDER,
            'range' => [ 'px' => [ 'min' => 0, 'max' => 60 ] ],
            'default' => [ 'size' => 24, 'unit' => 'px' ],
            'mobile_default' => [ 'size' => 12, 'unit' => 'px' ],
            'selectors' => [ '{{WRAPPER}} .guide-metric-matrix' => 'gap: {{SIZE}}{{UNIT}};' ],
        ]);

        $this->add_control('bg_color', [
            'label' => 'Background',
            'type' => Controls_Manager::COLOR,
            'default' => '#F9FAFB',
            'selectors' => ['{{WRAPPER}} .guide-header-container' => 'background-color: {{VALUE}};']
        ]);

        $this->add_responsive_control('padding', [
            'label' => 'Padding',
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'selectors' => ['{{WRAPPER}} .guide-header-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        // --- TAB: STYLE - BADGES ---
        $this->start_controls_section('style_badges', [ 'label' => 'Badges', 'tab' => Controls_Manager::TAB_STYLE ]);
        
        // Category Badge
        $this->add_control('cat_badge_bg', [ 'label' => 'Category BG', 'type' => Controls_Manager::COLOR, 'default' => '#DCFCE7', 'selectors' => ['{{WRAPPER}} .badge-cat' => 'background-color: {{VALUE}};'] ]);
        $this->add_control('cat_badge_text', [ 'label' => 'Category Color', 'type' => Controls_Manager::COLOR, 'default' => '#166534', 'selectors' => ['{{WRAPPER}} .badge-cat' => 'color: {{VALUE}};'] ]);
        
        // Rating Badge
        $this->add_control('rat_badge_bg', [ 'label' => 'Rating BG', 'type' => Controls_Manager::COLOR, 'default' => '#FFEDD5', 'selectors' => ['{{WRAPPER}} .badge-rat' => 'background-color: {{VALUE}};'] ]);
        $this->add_control('rat_badge_text', [ 'label' => 'Rating Color', 'type' => Controls_Manager::COLOR, 'default' => '#9A3412', 'selectors' => ['{{WRAPPER}} .badge-rat' => 'color: {{VALUE}};'] ]);

        $this->end_controls_section();

        // --- TAB: STYLE - METRIC CARDS ---
        $this->start_controls_section('style_metrics', [ 'label' => 'Metric Cards', 'tab' => Controls_Manager::TAB_STYLE ]);
        
        $this->add_control('card_bg', [ 'label' => 'Card Background', 'type' => Controls_Manager::COLOR, 'default' => '#FFFFFF', 'selectors' => ['{{WRAPPER}} .guide-metric-card' => 'background-color: {{VALUE}};'] ]);
        
        $this->add_group_control(Group_Control_Border::get_type(), [ 
            'name' => 'card_border', 
            'selector' => '{{WRAPPER}} .guide-metric-card',
            'fields_options' => [
                'border' => [ 'default' => 'solid' ],
                'width' => [ 'default' => [ 'top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1, 'isLinked' => true ] ],
                'color' => [ 'default' => '#F1F5F9' ],
            ]
        ]);

        $this->add_responsive_control('card_radius', [
            'label' => 'Border Radius',
            'type' => Controls_Manager::DIMENSIONS,
            'default' => [ 'top' => 12, 'right' => 12, 'bottom' => 12, 'left' => 12, 'unit' => 'px' ],
            'selectors' => ['{{WRAPPER}} .guide-metric-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_group_control(Group_Control_Box_Shadow::get_type(), [ 'name' => 'card_shadow', 'selector' => '{{WRAPPER}} .guide-metric-card' ]);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <style>
            .guide-header-container {
                width: 100%;
                /* Fluid Padding: 60px mobile -> 100px desktop */
                padding: clamp(60px, 8vw, 100px) 0;
                display: flex;
                flex-direction: column;
                gap: 48px;
                box-sizing: border-box;
                overflow: hidden;
            }

            /* --- Badges --- */
            .guide-badge-row {
                display: flex;
                align-items: center;
                gap: 12px;
                flex-wrap: wrap; /* Wrap on mobile */
            }
            .badge-item {
                padding: 6px 14px;
                border-radius: 100px;
                font-size: 13px;
                font-weight: 700;
                white-space: nowrap;
            }
            .badge-lvl { color: #1E293B; font-weight: 600; }
            .badge-dot { color: #CBD5E1; font-size: 8px; }
            .badge-rat { display: flex; align-items: center; gap: 6px; }

            /* --- Identity --- */
            .guide-identity-stack {
                display: flex;
                flex-direction: column;
                gap: 24px;
                max-width: 900px;
            }
            .guide-h1 {
                margin: 0 !important;
                /* Fluid Type: 42px -> 72px */
                font-size: clamp(42px, 5vw, 72px);
                font-weight: 850;
                color: #111827;
                line-height: 1.1;
                letter-spacing: -0.03em;
            }
            .guide-p {
                margin: 0 !important;
                /* Fluid Type: 18px -> 24px */
                font-size: clamp(18px, 2vw, 24px);
                color: #64748b;
                line-height: 1.5;
                max-width: 800px;
            }

            /* --- Metrics Grid --- */
            .guide-metric-matrix {
                display: grid;
                width: 100%;
                /* Grid Columns set by Widget Controls */
            }

            .guide-metric-card {
                /* Padding reduced slightly for mobile density */
                padding: clamp(20px, 3vw, 30px);
                display: flex;
                flex-direction: column;
                gap: 8px;
                transition: transform 0.2s, box-shadow 0.2s;
                height: 100%;
            }
            .guide-metric-card:hover {
                transform: translateY(-3px);
            }

            .metric-top {
                display: flex; align-items: center; gap: 8px;
                font-size: 11px; font-weight: 700; 
                color: #94a3b8; letter-spacing: 1px; text-transform: uppercase;
            }
            .metric-val {
                /* Fluid Type: 24px -> 32px */
                font-size: clamp(24px, 3vw, 32px);
                font-weight: 750;
                color: #111827;
                line-height: 1.2;
            }

            /* --- Mobile Optimizations --- */
            @media (max-width: 767px) {
                .guide-header-container { gap: 32px; padding-left: 20px; padding-right: 20px; }
                .guide-identity-stack { gap: 16px; }
                
                /* Adjust Metric Card density for 2-column mobile layout */
                .guide-metric-card { padding: 16px; }
                .metric-val { font-size: 20px; }
                .metric-top { font-size: 10px; gap: 6px; }
            }
        </style>

        <div class="guide-header-container">
            
            <div class="guide-badge-row">
                <?php if ( ! empty( $settings['badge_cat'] ) ) : ?>
                    <span class="badge-item badge-cat"><?php echo esc_html($settings['badge_cat']); ?></span>
                <?php endif; ?>

                <?php if ( ! empty( $settings['badge_level'] ) ) : ?>
                    <i class="fas fa-circle badge-dot"></i>
                    <span class="badge-item badge-lvl"><?php echo esc_html($settings['badge_level']); ?></span>
                <?php endif; ?>

                <?php if ( ! empty( $settings['badge_rating'] ) ) : ?>
                    <span class="badge-item badge-rat">
                        <i class="fas fa-star" style="color: #F59E0B;"></i> 
                        <?php echo esc_html($settings['badge_rating']); ?>
                    </span>
                <?php endif; ?>
            </div>

            <div class="guide-identity-stack">
                <h1 class="guide-h1"><?php echo esc_html($settings['title']); ?></h1>
                <p class="guide-p"><?php echo esc_html($settings['description']); ?></p>
            </div>

            <div class="guide-metric-matrix">
                <div class="guide-metric-card">
                    <span class="metric-top"><i class="far fa-clock"></i> <?php echo esc_html($settings['metric_1_label']); ?></span>
                    <span class="metric-val"><?php echo esc_html($settings['metric_1_val']); ?></span>
                </div>
                <div class="guide-metric-card">
                    <span class="metric-top"><i class="far fa-eye"></i> <?php echo esc_html($settings['metric_2_label']); ?></span>
                    <span class="metric-val"><?php echo esc_html($settings['metric_2_val']); ?></span>
                </div>
                <div class="guide-metric-card">
                    <span class="metric-top"><i class="far fa-file-alt"></i> <?php echo esc_html($settings['metric_3_label']); ?></span>
                    <span class="metric-val"><?php echo esc_html($settings['metric_3_val']); ?></span>
                </div>
                <div class="guide-metric-card">
                    <span class="metric-top"><i class="fas fa-layer-group"></i> <?php echo esc_html($settings['metric_4_label']); ?></span>
                    <span class="metric-val"><?php echo esc_html($settings['metric_4_val']); ?></span>
                </div>
            </div>

        </div>
        <?php
    }
}