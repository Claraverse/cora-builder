<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;

if (!defined('ABSPATH')) exit;

class Cora_Pricing_Card extends Base_Widget
{
    public function get_name() { return 'cora_pricing_card'; }
    public function get_title() { return __('Cora Pricing Card', 'cora-builder'); }
    public function get_icon() { return 'eicon-price-table'; }

    protected function register_controls()
    {
        // --- TAB 1: CONTENT ---
        $this->start_controls_section('content', ['label' => __('Plan Details', 'cora-builder')]);

        $this->add_control('is_recommended', [
            'label' => 'Mark as Recommended',
            'type' => Controls_Manager::SWITCHER,
            'default' => '',
        ]);
        
        $this->add_control('ribbon_text', [
            'label' => 'Ribbon Text',
            'type' => Controls_Manager::TEXT,
            'default' => 'RECOMMENDED',
            'condition' => ['is_recommended' => 'yes']
        ]);

        $this->add_control('plan_name', ['label' => 'Plan Name', 'type' => Controls_Manager::TEXT, 'default' => 'Starter', 'dynamic' => ['active' => true]]);
        $this->add_control('plan_desc', ['label' => 'Description', 'type' => Controls_Manager::TEXT, 'default' => 'Great for first-time users.', 'dynamic' => ['active' => true]]);

        $this->add_control('old_price', ['label' => 'Strike Price', 'type' => Controls_Manager::TEXT, 'default' => '₹ 199.00', 'dynamic' => ['active' => true]]);
        $this->add_control('current_price', ['label' => 'Monthly Price', 'type' => Controls_Manager::TEXT, 'default' => '99.00', 'dynamic' => ['active' => true]]);
        $this->add_control('save_tag', ['label' => 'Discount Badge', 'type' => Controls_Manager::TEXT, 'default' => 'Save 80%', 'dynamic' => ['active' => true]]);
        $this->add_control('cta_text', ['label' => 'Button Text', 'type' => Controls_Manager::TEXT, 'default' => 'Choose plan']);

        $this->end_controls_section();

        // --- SPECS CONTENT ---
        $this->start_controls_section('specs', ['label' => __('Technical Specs', 'cora-builder')]);
        $this->add_control('storage', ['label' => 'Storage', 'type' => Controls_Manager::TEXT, 'default' => '10 GB NVMe']);
        $this->add_control('bandwidth', ['label' => 'Bandwidth', 'type' => Controls_Manager::TEXT, 'default' => '100 GB/mo']);
        $this->add_control('visits', ['label' => 'Visits', 'type' => Controls_Manager::TEXT, 'default' => '10K visits/mo']);
        $this->add_control('products', ['label' => 'Products', 'type' => Controls_Manager::TEXT, 'default' => 'Up to 100']);
        $this->end_controls_section();

        // --- FEATURES REPEATER ---
        $this->start_controls_section('features_sec', ['label' => __('Feature List', 'cora-builder')]);
        $repeater = new Repeater();
        $repeater->add_control('f_label', ['label' => 'Feature', 'type' => Controls_Manager::TEXT, 'default' => 'Managed WooCommerce']);
        $repeater->add_control('f_icon', ['label' => 'Icon Type', 'type' => Controls_Manager::SELECT, 'default' => 'bolt', 'options' => ['bolt' => 'Bolt Icon', 'check' => 'Check Icon']]);
        $this->add_control('features', ['label' => 'Features', 'type' => Controls_Manager::REPEATER, 'fields' => $repeater->get_controls()]);
        $this->end_controls_section();

        // --- TAB 2: STYLE (Design Reset) ---
        $this->start_controls_section('style_reset', [ 'label' => 'Design Reset', 'tab' => Controls_Manager::TAB_STYLE ]);

        // Visual Status Bar
        $this->add_control('reset_info', [
            'type' => Controls_Manager::RAW_HTML,
            'raw'  => '<div style="background:#f1f4ff; color:#1e2b5e; padding:12px; border-radius:12px; font-weight:bold; text-align:center; border:1px solid #dbeafe;">
                        <i class="eicon-check-circle"></i> Cora Pricing Engine Active
                      </div>',
        ]);

        $this->add_control('structural_reset', [
            'type' => Controls_Manager::HIDDEN,
            'default' => 'reset',
            'selectors' => [
                // Container Architecture
                '{{WRAPPER}} .pricing-card' => 'display: flex; flex-direction: column; position: relative; overflow: hidden; transition: all 0.3s ease;',
                '{{WRAPPER}} .pricing-header' => 'display: flex; flex-direction: column; gap: 8px;',
                
                // Recommended Logic
                '{{WRAPPER}} .pricing-card.recommended' => 'border-color: #3b82f6;', 
                '{{WRAPPER}} .pricing-card.recommended .pricing-header' => 'margin-top: 24px;',
                '{{WRAPPER}} .recommended-ribbon' => 'position: absolute; top: 0; left: 0; width: 100%; text-align: center; padding: 8px 0; font-weight: 800; font-size: 12px; letter-spacing: 0.5px;',
                
                // Typography Resets
                '{{WRAPPER}} .plan-title, {{WRAPPER}} .plan-subtitle' => 'margin: 0 !important; padding: 0;',
                
                // Price Row
                '{{WRAPPER}} .price-row' => 'display: flex; align-items: center; justify-content: space-between; margin-top: 16px;',
                '{{WRAPPER}} .main-price-group' => 'display: inline-flex; align-items: baseline; line-height: 1;',
                '{{WRAPPER}} .old-price' => 'text-decoration: line-through; display: block; margin-bottom: 4px;',
                
                // Specs Grid
                '{{WRAPPER}} .specs-box' => 'display: grid; grid-template-columns: 1fr 1fr; gap: 20px; padding: 24px; border-radius: 20px;',
                '{{WRAPPER}} .spec-unit' => 'display: flex; flex-direction: column;',
                
                // Features
                '{{WRAPPER}} .feature-stack' => 'display: flex; flex-direction: column; gap: 12px;',
                '{{WRAPPER}} .f-row' => 'display: flex; align-items: center; gap: 12px;',
                '{{WRAPPER}} .f-icon-box' => 'display: flex; align-items: center; justify-content: center; width: 24px; height: 24px; border-radius: 6px;',
                
                // Button Reset
                '{{WRAPPER}} .plan-cta' => 'display: flex; align-items: center; justify-content: center; text-decoration: none; width: 100%; text-align: center; transition: all 0.2s ease;',
            ],
        ]);
        $this->end_controls_section();

        // --- ELEMENT ENGINES ---

        // 1. Header & Typography
        $this->register_text_styling_controls('header_typo', 'Plan Title', '{{WRAPPER}} .plan-title');
        $this->register_text_styling_controls('desc_typo', 'Description', '{{WRAPPER}} .plan-subtitle');

        // 2. Price Block
        $this->start_controls_section('price_style', ['label' => 'Price Block', 'tab' => Controls_Manager::TAB_STYLE]);
        $this->add_control('price_color', ['label' => 'Price Color', 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .main-price-group' => 'color: {{VALUE}};']]);
        $this->add_group_control(Group_Control_Typography::get_type(), ['name' => 'price_typo', 'selector' => '{{WRAPPER}} .val']);
        $this->add_control('old_price_color', ['label' => 'Strike Price Color', 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .old-price' => 'color: {{VALUE}};']]);
        // Discount Badge
        $this->add_control('badge_heading', ['label' => 'Discount Badge', 'type' => Controls_Manager::HEADING, 'separator' => 'before']);
        $this->add_control('badge_bg', ['label' => 'Badge Background', 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .save-badge' => 'background-color: {{VALUE}}; padding: 4px 8px; border-radius: 4px;']]);
        $this->add_control('badge_text_color', ['label' => 'Badge Text Color', 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .save-badge' => 'color: {{VALUE}};']]);
        $this->end_controls_section();

        // 3. Button Engine
        $this->register_text_styling_controls('btn_typo', 'Button Text', '{{WRAPPER}} .plan-cta');
        $this->register_layout_geometry('.plan-cta', 'btn_geo', 'Button Geometry');
        $this->register_surface_styles('.plan-cta', 'btn_surface', 'Button Skin');

        // 4. Specs Grid Engine
        $this->start_controls_section('specs_style', ['label' => 'Specs Grid', 'tab' => Controls_Manager::TAB_STYLE]);
        $this->add_control('specs_bg', ['label' => 'Box Background', 'type' => Controls_Manager::COLOR, 'default' => '#f8fafc', 'selectors' => ['{{WRAPPER}} .specs-box' => 'background-color: {{VALUE}};']]);
        $this->add_control('specs_label_color', ['label' => 'Label Color', 'type' => Controls_Manager::COLOR, 'default' => '#94a3b8', 'selectors' => ['{{WRAPPER}} .spec-unit span' => 'color: {{VALUE}}; font-size: 11px; font-weight: 700;']]);
        $this->add_control('specs_val_color', ['label' => 'Value Color', 'type' => Controls_Manager::COLOR, 'default' => '#0f172a', 'selectors' => ['{{WRAPPER}} .spec-unit strong' => 'color: {{VALUE}}; font-size: 15px; font-weight: 850;']]);
        $this->end_controls_section();

        // 5. Features List
        $this->start_controls_section('features_style', ['label' => 'Features List', 'tab' => Controls_Manager::TAB_STYLE]);
        $this->add_responsive_control('feat_gap', ['label' => 'Row Gap', 'type' => Controls_Manager::SLIDER, 'selectors' => ['{{WRAPPER}} .feature-stack' => 'gap: {{SIZE}}px;']]);
        $this->add_control('feat_icon_color', ['label' => 'Icon Color (Bolt)', 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .f-icon-box.bolt' => 'color: {{VALUE}};']]);
        $this->add_control('feat_icon_bg', ['label' => 'Icon BG (Bolt)', 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .f-icon-box.bolt' => 'background-color: {{VALUE}};']]);
        $this->end_controls_section();

        // 6. Recommended Ribbon
        $this->start_controls_section('ribbon_style', ['label' => 'Recommended Ribbon', 'tab' => Controls_Manager::TAB_STYLE, 'condition' => ['is_recommended' => 'yes']]);
        $this->add_control('ribbon_bg', ['label' => 'Background', 'type' => Controls_Manager::COLOR, 'default' => '#4f46e5', 'selectors' => ['{{WRAPPER}} .recommended-ribbon' => 'background-color: {{VALUE}};']]);
        $this->add_control('ribbon_color', ['label' => 'Text Color', 'type' => Controls_Manager::COLOR, 'default' => '#ffffff', 'selectors' => ['{{WRAPPER}} .recommended-ribbon' => 'color: {{VALUE}};']]);
        $this->end_controls_section();

        // --- TAB 3: GLOBAL (Card Container) ---
        // Handles padding (40px 32px), border (1px solid #e2e8f0), radius (32px)
        $this->register_global_design_controls('.pricing-card');
        $this->register_layout_geometry('.pricing-card', 'card_geo', 'Card Layout');
        $this->register_surface_styles('.pricing-card', 'card_surface', 'Card Surface');
        $this->register_common_spatial_controls();

        // --- TAB 4: ADVANCED ---
        $this->register_interaction_motion();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $is_rec = 'yes' === $settings['is_recommended'];
        $card_class = $is_rec ? 'pricing-card recommended' : 'pricing-card';
        ?>
        <div class="cora-unit-container <?php echo esc_attr($card_class); ?>">
            <?php if ($is_rec): ?>
                <div class="recommended-ribbon"><?php echo esc_html($settings['ribbon_text']); ?></div>
            <?php endif; ?>

            <div class="pricing-header">
                <h3 class="plan-title"><?php echo esc_html($settings['plan_name']); ?></h3>
                <p class="plan-subtitle"><?php echo esc_html($settings['plan_desc']); ?></p>

                <div class="price-row">
                    <div class="price-val">
                        <?php if($settings['old_price']) : ?><span class="old-price"><?php echo esc_html($settings['old_price']); ?></span><?php endif; ?>
                        <div class="main-price-group">
                            <span class="curr">₹</span>
                            <span class="val"><?php echo esc_html($settings['current_price']); ?></span>
                            <span class="mo">/mo</span>
                        </div>
                    </div>
                    <?php if($settings['save_tag']) : ?><span class="save-badge"><?php echo esc_html($settings['save_tag']); ?></span><?php endif; ?>
                </div>
            </div>

            <a href="#" class="plan-cta"><?php echo esc_html($settings['cta_text']); ?></a>
            <p class="policy-sub" style="font-size: 11px; color:#94a3b8; text-align: center; margin-top: 12px;">Cancel anytime. 100% Refund Policy*</p>

            <div class="specs-box">
                <div class="spec-unit"><span>STORAGE</span><strong><?php echo esc_html($settings['storage']); ?></strong></div>
                <div class="spec-unit"><span>BANDWIDTH</span><strong><?php echo esc_html($settings['bandwidth']); ?></strong></div>
                <div class="spec-unit"><span>VISITS</span><strong><?php echo esc_html($settings['visits']); ?></strong></div>
                <div class="spec-unit"><span>PRODUCTS</span><strong><?php echo esc_html($settings['products']); ?></strong></div>
            </div>

            <div class="feature-stack">
                <?php foreach ($settings['features'] as $f): ?>
                    <div class="f-row">
                        <div class="f-icon-box <?php echo esc_attr($f['f_icon']); ?>">
                            <?php echo ($f['f_icon'] == 'bolt') ? '<i class="fas fa-bolt"></i>' : '<i class="fas fa-check"></i>'; ?>
                        </div>
                        <span><?php echo esc_html($f['f_label']); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
}