<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Hosting_Price extends Base_Widget {

    public function get_name() { return 'cora_hosting_price'; }
    public function get_title() { return __( 'Cora Hosting Price', 'cora-builder' ); }
    public function get_icon() { return 'eicon-price-table'; }

    protected function register_controls() {
        
        // --- TAB 1: CONTENT ---
        $this->start_controls_section('content_price', [ 'label' => 'Price Identity' ]);
        
        $this->add_control('currency', [ 
            'label' => 'Currency Symbol', 
            'type' => Controls_Manager::TEXT, 
            'default' => '₹', 
            'dynamic' => ['active' => true] 
        ]);

        $this->add_control('price', [ 
            'label' => 'Price Amount', 
            'type' => Controls_Manager::TEXT, 
            'default' => '99.00', 
            'dynamic' => ['active' => true] 
        ]);

        $this->add_control('frequency', [ 
            'label' => 'Billing Frequency', 
            'type' => Controls_Manager::TEXT, 
            'default' => '/mo', 
            'dynamic' => ['active' => true] 
        ]);

        $this->add_control('show_asterisk', [
            'label' => 'Show Asterisk (*)',
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('content_badge', [ 'label' => 'Trust Signal' ]);
        
        $this->add_control('badge_icon', [ 
            'label' => 'Badge Icon', 
            'type' => Controls_Manager::ICONS, 
            'default' => [ 'value' => 'fas fa-shield-alt', 'library' => 'solid' ] 
        ]);

        $this->add_control('badge_text', [ 
            'label' => 'Badge Text', 
            'type' => Controls_Manager::TEXT, 
            'default' => '30-day money back guarantee', 
            'dynamic' => ['active' => true] 
        ]);

        $this->end_controls_section();

        // --- TAB 2: STYLE (Design Reset) ---
        $this->start_controls_section('style_reset', [ 
            'label' => 'Design Reset', 
            'tab'   => Controls_Manager::TAB_STYLE 
        ]);

        // Visual Status Bar
        $this->add_control('reset_info', [
            'type' => Controls_Manager::RAW_HTML,
            'raw'  => '<div style="background:#f1f4ff; color:#1e2b5e; padding:12px; border-radius:12px; font-weight:bold; text-align:center; border:1px solid #dbeafe;">
                        <i class="eicon-check-circle"></i> Cora Price Engine Active
                      </div>',
        ]);

        $this->add_control('structural_reset', [
            'type' => Controls_Manager::HIDDEN,
            'default' => 'reset',
            'selectors' => [
                '{{WRAPPER}} .cora-price-block' => 'display: flex; flex-direction: column; width: fit-content; transition: all 0.3s ease;',
                '{{WRAPPER}} .price-row' => 'display: inline-flex; align-items: baseline; line-height: 1;',
                // Zero Margin Authority
                '{{WRAPPER}} .currency, {{WRAPPER}} .amount, {{WRAPPER}} .frequency' => 'margin: 0 !important; padding: 0; font-weight: 700; color: #1e2b5e;',
                '{{WRAPPER}} .asterisk' => 'font-size: 0.6em; vertical-align: top; margin-left: 2px; color: #1e2b5e;',
                '{{WRAPPER}} .trust-badge' => 'display: flex; align-items: center; gap: 8px; margin-top: 12px; transition: opacity 0.3s;',
                '{{WRAPPER}} .badge-text' => 'font-size: 14px; color: #1e2b5e; margin: 0 !important;',
                '{{WRAPPER}} .badge-icon' => 'font-size: 16px; color: #10b981; display: flex; align-items: center;',
            ],
        ]);
        $this->end_controls_section();

        // --- ELEMENT ENGINES ---

        // 1. PRICE TYPOGRAPHY (Split Control)
        $this->start_controls_section('price_typo_section', ['label' => 'Price Typography', 'tab' => Controls_Manager::TAB_STYLE]);
        
        // Amount (The Big Number)
        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'amount_typo',
            'label' => 'Amount Font',
            'selector' => '{{WRAPPER}} .amount',
        ]);
        $this->add_control('amount_color', [
            'label' => 'Amount Color', 'type' => Controls_Manager::COLOR, 
            'selectors' => ['{{WRAPPER}} .amount' => 'color: {{VALUE}};'] 
        ]);

        // Currency (The Symbol)
        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'currency_typo',
            'label' => 'Currency Font',
            'selector' => '{{WRAPPER}} .currency',
            'separator' => 'before',
        ]);
        $this->add_control('currency_color', [
            'label' => 'Currency Color', 'type' => Controls_Manager::COLOR, 
            'selectors' => ['{{WRAPPER}} .currency' => 'color: {{VALUE}};'] 
        ]);

        // Frequency (The /mo)
        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'freq_typo',
            'label' => 'Frequency Font',
            'selector' => '{{WRAPPER}} .frequency',
            'separator' => 'before',
        ]);
        $this->add_control('freq_color', [
            'label' => 'Frequency Color', 'type' => Controls_Manager::COLOR, 
            'selectors' => ['{{WRAPPER}} .frequency' => 'color: {{VALUE}};'] 
        ]);

        $this->end_controls_section();

        // 2. TRUST BADGE STYLING
        $this->start_controls_section('badge_style_section', ['label' => 'Trust Badge Style', 'tab' => Controls_Manager::TAB_STYLE]);
        
        $this->add_control('badge_icon_color', [
            'label' => 'Icon Color', 'type' => Controls_Manager::COLOR, 'default' => '#10b981',
            'selectors' => ['{{WRAPPER}} .badge-icon' => 'color: {{VALUE}};'] 
        ]);
        
        $this->add_responsive_control('badge_icon_size', [
            'label' => 'Icon Size', 'type' => Controls_Manager::SLIDER,
            'selectors' => ['{{WRAPPER}} .badge-icon' => 'font-size: {{SIZE}}px;'] 
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'badge_text_typo',
            'label' => 'Text Font',
            'selector' => '{{WRAPPER}} .badge-text',
        ]);
        $this->add_control('badge_text_color', [
            'label' => 'Text Color', 'type' => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .badge-text' => 'color: {{VALUE}};'] 
        ]);

        // Badge Container Layout (Gap between Price and Badge)
        $this->add_responsive_control('badge_margin_top', [
            'label' => 'Spacing from Price',
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px', 'em'],
            'range' => ['px' => ['min' => 0, 'max' => 50]],
            'selectors' => ['{{WRAPPER}} .trust-badge' => 'margin-top: {{SIZE}}{{UNIT}};'],
            'separator' => 'before',
        ]);

        $this->end_controls_section();

        // --- TAB 3: GLOBAL CONTAINER ---
        // This handles the main block's Padding, Background, Border, and Hover Effects
        $this->register_global_design_controls('.cora-price-block');
        
        $this->register_layout_geometry('.cora-price-block', 'block_geo', 'Block Layout & Padding');
        $this->register_surface_styles('.cora-price-block', 'block_surface', 'Block Surface');
        
        $this->register_alignment_controls('price_align', '.cora-price-block', '.price-row, .trust-badge');
        $this->register_common_spatial_controls();

        // --- TAB 4: ADVANCED ---
        $this->register_interaction_motion();
        $this->register_transform_engine('.cora-price-block');
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="cora-unit-container cora-price-block">
            <div class="price-row">
                <span class="currency"><?php echo esc_html($settings['currency']); ?></span>
                <span class="amount"><?php echo esc_html($settings['price']); ?></span>
                <?php if ( 'yes' === $settings['show_asterisk'] ) : ?>
                    <span class="asterisk">*</span>
                <?php endif; ?>
                <span class="frequency"><?php echo esc_html($settings['frequency']); ?></span>
            </div>
            
            <div class="trust-badge">
                <div class="badge-icon">
                    <?php Icons_Manager::render_icon( $settings['badge_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                </div>
                <p class="badge-text"><?php echo esc_html($settings['badge_text']); ?></p>
            </div>
        </div>
        <?php
    }
}