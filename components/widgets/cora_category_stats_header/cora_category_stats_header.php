<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Category_Stats_Header extends Base_Widget {

    public function get_name() { return 'cora_category_stats_header'; }
    public function get_title() { return __( 'Cora Category Stats Header', 'cora-builder' ); }
    public function get_icon() { return 'eicon-archive-title'; }

    protected function register_controls() {
        
        // --- TAB: CONTENT ---
        $this->start_controls_section('intro', [ 'label' => 'Header Introduction' ]);
        
        $this->add_control('breadcrumbs', [ 
            'label' => 'Breadcrumbs Text', 
            'type' => Controls_Manager::TEXT, 
            'default' => 'Home > Categories > Customer Service',
            'dynamic' => ['active' => true]
        ]);

        $this->add_control('main_icon', [ 
            'label' => 'Category Icon', 
            'type' => Controls_Manager::ICONS,
            'default' => [ 'value' => 'fas fa-users', 'library' => 'solid' ]
        ]);

        $this->add_control('title', [ 
            'label' => 'Category Title', 
            'type' => Controls_Manager::TEXT, 
            'default' => 'Customer Service',
            'dynamic' => ['active' => true] 
        ]);

        $this->add_control('description', [ 
            'label' => 'Description', 
            'type' => Controls_Manager::TEXTAREA, 
            'default' => 'Deliver exceptional customer experiences that build loyalty. Learn best practices for support, retention, and creating raving fans.',
            'dynamic' => ['active' => true] 
        ]);
        
        $this->end_controls_section();

        // --- TAB: STATS ---
        $this->start_controls_section('stats', [ 'label' => 'Statistics Cards' ]);
        
        $repeater = new Repeater();
        $repeater->add_control('card_icon', [ 
            'label' => 'Icon', 
            'type' => Controls_Manager::ICONS,
            'default' => [ 'value' => 'fas fa-book', 'library' => 'solid' ]
        ]);
        
        $repeater->add_control('card_label', [ 
            'label' => 'Label', 
            'type' => Controls_Manager::TEXT, 
            'default' => 'Total Guides',
            'dynamic' => ['active' => true] 
        ]);
        
        $repeater->add_control('card_value', [ 
            'label' => 'Value', 
            'type' => Controls_Manager::TEXT, 
            'default' => '2',
            'dynamic' => ['active' => true] 
        ]);

        $repeater->add_control('card_icon_color', [
            'label' => 'Icon Color',
            'type' => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} {{CURRENT_ITEM}} .stat-icon i' => 'color: {{VALUE}}', '{{WRAPPER}} {{CURRENT_ITEM}} .stat-icon svg' => 'fill: {{VALUE}}'],
        ]);
        
        $this->add_control('stats_list', [
            'label' => 'Stats List',
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [ 
                ['card_label' => 'Total Guides', 'card_value' => '2'], 
                ['card_label' => 'Avg Rating', 'card_value' => '4.6', 'card_icon' => ['value' => 'fas fa-star', 'library' => 'solid'], 'card_icon_color' => '#F59E0B'], 
                ['card_label' => 'Total Views', 'card_value' => '24.5K', 'card_icon' => ['value' => 'fas fa-eye', 'library' => 'solid'], 'card_icon_color' => '#3B82F6'],
                ['card_label' => 'Difficulty', 'card_value' => 'Intermediate', 'card_icon' => ['value' => 'fas fa-filter', 'library' => 'solid'], 'card_icon_color' => '#8B5CF6'] 
            ],
        ]);
        $this->end_controls_section();

        // --- TAB: STYLE - LAYOUT ---
        $this->start_controls_section('style_layout', [ 'label' => 'Layout & Container', 'tab' => Controls_Manager::TAB_STYLE ]);
        
        $this->add_control('bg_color', [
            'label' => 'Background',
            'type' => Controls_Manager::COLOR,
            'default' => '#F9FAFB',
            'selectors' => ['{{WRAPPER}} .cora-stats-header' => 'background-color: {{VALUE}};']
        ]);

        $this->add_responsive_control('padding', [
            'label' => 'Padding',
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'selectors' => ['{{WRAPPER}} .cora-stats-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);
        
        $this->add_responsive_control('stats_gap', [
            'label' => 'Stats Grid Gap',
            'type' => Controls_Manager::SLIDER,
            'range' => [ 'px' => [ 'min' => 0, 'max' => 100 ] ],
            'default' => [ 'size' => 24 ],
            'selectors' => [ '{{WRAPPER}} .stats-grid' => 'gap: {{SIZE}}px;' ],
        ]);

        $this->end_controls_section();

        // --- TAB: STYLE - IDENTITY ---
        $this->start_controls_section('style_identity', [ 'label' => 'Identity (Icon + Text)', 'tab' => Controls_Manager::TAB_STYLE ]);
        
        $this->add_control('main_icon_bg', [
            'label' => 'Icon Box Background',
            'type' => Controls_Manager::COLOR,
            'default' => '#FCE7F3',
            'selectors' => ['{{WRAPPER}} .identity-icon-box' => 'background-color: {{VALUE}};']
        ]);

        $this->add_control('main_icon_color', [
            'label' => 'Icon Color',
            'type' => Controls_Manager::COLOR,
            'default' => '#EC4899',
            'selectors' => [
                '{{WRAPPER}} .identity-icon-box i' => 'color: {{VALUE}};',
                '{{WRAPPER}} .identity-icon-box svg' => 'fill: {{VALUE}};'
            ]
        ]);
        
        $this->add_control('title_color', [
            'label' => 'Title Color',
            'type' => Controls_Manager::COLOR,
            'default' => '#111827',
            'selectors' => ['{{WRAPPER}} .identity-title' => 'color: {{VALUE}};']
        ]);
        
        $this->add_group_control(Group_Control_Typography::get_type(), [ 'name' => 'title_typo', 'selector' => '{{WRAPPER}} .identity-title' ]);

        $this->add_control('desc_color', [
            'label' => 'Description Color',
            'type' => Controls_Manager::COLOR,
            'default' => '#6B7280',
            'selectors' => ['{{WRAPPER}} .identity-desc' => 'color: {{VALUE}};']
        ]);
        
        $this->add_group_control(Group_Control_Typography::get_type(), [ 'name' => 'desc_typo', 'selector' => '{{WRAPPER}} .identity-desc' ]);

        $this->end_controls_section();

        // --- TAB: STYLE - STAT CARDS ---
        $this->start_controls_section('style_cards', [ 'label' => 'Stat Cards', 'tab' => Controls_Manager::TAB_STYLE ]);
        
        $this->add_control('card_bg', [
            'label' => 'Card Background',
            'type' => Controls_Manager::COLOR,
            'default' => '#FFFFFF',
            'selectors' => ['{{WRAPPER}} .stat-card' => 'background-color: {{VALUE}};']
        ]);

        $this->add_group_control(Group_Control_Border::get_type(), [ 
            'name' => 'card_border', 
            'selector' => '{{WRAPPER}} .stat-card',
            'fields_options' => [
                'border' => [ 'default' => 'solid' ],
                'width' => [ 'default' => [ 'top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1, 'isLinked' => true ] ],
                'color' => [ 'default' => '#E5E7EB' ],
            ]
        ]);

        $this->add_responsive_control('card_radius', [
            'label' => 'Border Radius',
            'type' => Controls_Manager::DIMENSIONS,
            'default' => [ 'top' => 16, 'right' => 16, 'bottom' => 16, 'left' => 16, 'unit' => 'px' ],
            'selectors' => ['{{WRAPPER}} .stat-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_group_control(Group_Control_Box_Shadow::get_type(), [ 'name' => 'card_shadow', 'selector' => '{{WRAPPER}} .stat-card' ]);

        $this->end_controls_section();


        // --- LAYOUT ENGINE (FIXED) ---
        $this->start_controls_section('layout_engine', [ 'label' => 'Layout Engine', 'tab' => Controls_Manager::TAB_STYLE ]);
        $this->add_control('css_reset', [
            'type' => Controls_Manager::HIDDEN,
            'default' => 'reset',
            'selectors' => [
                '{{WRAPPER}} .cora-stats-header' => 'width: 100%; max-width:1200px; display: flex; flex-direction: column; gap: 48px; box-sizing: border-box; overflow: hidden;',
                
                // Breadcrumbs
                '{{WRAPPER}} .breadcrumbs' => 'font-size: 14px; color: #6B7280; font-weight: 500; display: flex; align-items: center; gap: 8px;',
                '{{WRAPPER}} .breadcrumbs i' => 'color: #9CA3AF; font-size: 12px;',

                // Identity Section (Flex)
                '{{WRAPPER}} .identity-wrapper' => 'display: flex; gap: 32px; align-items: center;',
                
                // Icon Box
                '{{WRAPPER}} .identity-icon-box' => 'width: 120px; height: 120px; border-radius: 24px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;',
                '{{WRAPPER}} .identity-icon-box i' => 'font-size: 48px;',
                '{{WRAPPER}} .identity-icon-box svg' => 'width: 48px; height: 48px;',

                // Text Stack
                '{{WRAPPER}} .identity-content' => 'display: flex; flex-direction: column; gap: 12px;',
                '{{WRAPPER}} .identity-title' => 'margin: 0; font-size: clamp(32px, 4vw, 56px); font-weight: 800; line-height: 1.1; letter-spacing: -0.02em;',
                '{{WRAPPER}} .identity-desc' => 'margin: 0; font-size: clamp(16px, 2vw, 20px); line-height: 1.5; max-width: 650px;',

                // Stats Grid (DEFAULT DESKTOP)
                '{{WRAPPER}} .stats-grid' => 'display: grid; width: 100%; grid-template-columns: repeat(4, 1fr); gap:12px;',
                
                // Stat Card
                '{{WRAPPER}} .stat-card' => 'padding: 24px; display: flex; flex-direction: column; gap: 12px; transition: transform 0.2s;',
                '{{WRAPPER}} .stat-card:hover' => 'transform: translateY(-3px);',
                
                '{{WRAPPER}} .stat-header' => 'display: flex; align-items: center; gap: 10px;',
                '{{WRAPPER}} .stat-icon' => 'font-size: 16px; color: #374151;', 
                '{{WRAPPER}} .stat-label' => 'font-size: 15px; color: #6B7280; font-weight: 500;',
                '{{WRAPPER}} .stat-value' => 'font-size: 28px; font-weight: 700; color: #111827; line-height: 1.2;',

                // --- RESPONSIVE BREAKPOINTS ---

                // Tablet (Max 1024px)
                '@media (max-width: 1024px)' => [
                    '{{WRAPPER}} .stats-grid' => 'display: grid; grid-template-columns: repeat(2, 1fr);', // Enforced grid & cols
                    '{{WRAPPER}} .identity-icon-box' => 'width: 100px; height: 100px;',
                    '{{WRAPPER}} .identity-icon-box i' => 'font-size: 40px;',
                ],

                // Mobile (Max 767px)
                '@media (max-width: 767px)' => [
                    '{{WRAPPER}} .identity-wrapper' => 'flex-direction: column; align-items: flex-start; gap: 20px;',
                    '{{WRAPPER}} .identity-icon-box' => 'width: 80px; height: 80px;',
                    '{{WRAPPER}} .identity-icon-box i' => 'font-size: 32px;',
                    
                    // FIXED: Explicitly set display: grid again to override any 'block' interference
                    '{{WRAPPER}} .stats-grid' => 'display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px;', 
                    
                    '{{WRAPPER}} .stat-card' => 'padding: 16px;', 
                    '{{WRAPPER}} .stat-value' => 'font-size: 24px;',
                    '{{WRAPPER}} .cora-stats-header' => 'gap: 32px; padding: 40px 20px;',
                ],
            ],
        ]);
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="cora-stats-header">
            
            <?php if ( ! empty( $settings['breadcrumbs'] ) ) : ?>
            <div class="breadcrumbs">
                <i class="fas fa-home"></i> <?php echo esc_html($settings['breadcrumbs']); ?>
            </div>
            <?php endif; ?>

            <div class="identity-wrapper">
                <div class="identity-icon-box">
                    <?php \Elementor\Icons_Manager::render_icon( $settings['main_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                </div>
                <div class="identity-content">
                    <h1 class="identity-title"><?php echo esc_html($settings['title']); ?></h1>
                    <p class="identity-desc"><?php echo esc_html($settings['description']); ?></p>
                </div>
            </div>

            <div class="stats-grid">
                <?php foreach ( $settings['stats_list'] as $item ) : ?>
                    <div class="stat-card elementor-repeater-item-<?php echo esc_attr( $item['_id'] ); ?>">
                        <div class="stat-header">
                            <span class="stat-icon">
                                <?php \Elementor\Icons_Manager::render_icon( $item['card_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                            </span>
                            <span class="stat-label"><?php echo esc_html($item['card_label']); ?></span>
                        </div>
                        <div class="stat-value"><?php echo esc_html($item['card_value']); ?></div>
                    </div>
                <?php endforeach; ?>
            </div>

        </div>
        <?php
    }
}