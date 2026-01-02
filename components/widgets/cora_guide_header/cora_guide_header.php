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

        // --- LAYOUT ENGINE (CSS AUTHORITY) ---
        $this->start_controls_section('layout_engine', [ 'label' => 'Layout Engine', 'tab' => Controls_Manager::TAB_STYLE ]);
        $this->add_control('css_reset', [
            'type' => Controls_Manager::HIDDEN,
            'default' => 'reset',
            'selectors' => [
                '{{WRAPPER}} .guide-header-container' => 'width: 100%; display: flex; flex-direction: column; gap: 48px; box-sizing: border-box; overflow: hidden; padding: 60px 0;',
                
                // Badges Row
                '{{WRAPPER}} .guide-badge-row' => 'display: flex; align-items: center; gap: 12px; flex-wrap: wrap;',
                '{{WRAPPER}} .badge-item' => 'padding: 6px 14px; border-radius: 100px; font-size: 13px; font-weight: 700; white-space: nowrap;',
                '{{WRAPPER}} .badge-cat' => 'color: #166534; background: #DCFCE7;',
                '{{WRAPPER}} .badge-lvl' => 'color: #1E293B; font-weight: 600;',
                '{{WRAPPER}} .badge-rat' => 'color: #9A3412; background: #FFEDD5; display: flex; align-items: center; gap: 6px;',
                '{{WRAPPER}} .badge-dot' => 'color: #CBD5E1; font-size: 8px;',

                // Identity Stack
                '{{WRAPPER}} .guide-identity-stack' => 'display: flex; flex-direction: column; gap: 24px; max-width: 800px;',
                '{{WRAPPER}} .guide-h1' => 'margin: 0 !important; font-size: clamp(40px, 5vw, 64px); font-weight: 800; color: #111827; line-height: 1.1; letter-spacing: -0.02em;',
                '{{WRAPPER}} .guide-p' => 'margin: 0 !important; font-size: clamp(18px, 2vw, 20px); color: #6B7280; line-height: 1.6;',

                // Metrics Matrix (Grid)
                '{{WRAPPER}} .guide-metric-matrix' => 'display: grid; width: 100%; grid-template-columns: repeat(4, 1fr); gap: 24px;',
                
                // Metric Card
                '{{WRAPPER}} .guide-metric-card' => 'background: #fff; border: 1px solid #f1f5f9; border-radius: 12px; padding: 24px; display: flex; flex-direction: column; gap: 8px; transition: transform 0.2s;',
                '{{WRAPPER}} .guide-metric-card:hover' => 'transform: translateY(-3px); box-shadow: 0 10px 30px rgba(0,0,0,0.03);',
                
                '{{WRAPPER}} .metric-top' => 'display: flex; align-items: center; gap: 8px; font-size: 11px; font-weight: 700; color: #94A3B8; letter-spacing: 0.5px; text-transform: uppercase;',
                '{{WRAPPER}} .metric-val' => 'font-size: 24px; font-weight: 700; color: #111827; line-height: 1.2;',

                // --- RESPONSIVE BREAKPOINTS ---
                
                // Tablet (Max 1024px) -> 2 Columns
                '@media (max-width: 1024px)' => [
                    '{{WRAPPER}} .guide-metric-matrix' => 'grid-template-columns: repeat(2, 1fr);',
                    '{{WRAPPER}} .guide-h1' => 'font-size: 48px;',
                ],

                // Mobile (Max 767px) -> 1 Column (or 2 if tight)
                '@media (max-width: 767px)' => [
                    '{{WRAPPER}} .guide-header-container' => 'padding: 40px 20px;',
                    '{{WRAPPER}} .guide-identity-stack' => 'gap: 16px;',
                    
                    // Force 2 columns on mobile for better data density, or change to 1fr for stacking
                    '{{WRAPPER}} .guide-metric-matrix' => 'grid-template-columns: repeat(2, 1fr); gap: 12px;',
                    
                    '{{WRAPPER}} .guide-metric-card' => 'padding: 16px;',
                    '{{WRAPPER}} .metric-val' => 'font-size: 20px;',
                ],
            ],
        ]);
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
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