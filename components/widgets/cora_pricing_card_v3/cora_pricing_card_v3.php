<?php
namespace Cora_Builder\Components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;

if (!defined('ABSPATH'))
    exit;

class Cora_Pricing_Card_V3 extends Base_Widget
{
    public function get_name() { return 'cora_pricing_card_v3'; }
    public function get_title() { return 'Cora Pricing Card v3'; }
    public function get_icon() { return 'eicon-price-table'; }

    public function get_style_depends() {
        wp_register_style('cora-fonts', 'https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap', [], null);
        return ['cora-fonts'];
    }

    protected function register_controls()
    {
        // --- RECOMMENDED SETTINGS ---
        $this->start_controls_section('section_recommended', ['label' => 'Recommended Styling']);
        
        $this->add_control('is_recommended', [
            'label' => 'Make Recommended?',
            'type' => Controls_Manager::SWITCHER,
            'default' => 'no',
        ]);

        $this->add_control('recommended_text', [
            'label' => 'Tag Text',
            'type' => Controls_Manager::TEXT,
            'default' => 'RECOMMENDED',
            'condition' => ['is_recommended' => 'yes'],
        ]);

        $this->add_control('accent_color', [
            'label' => 'Accent Color',
            'type' => Controls_Manager::COLOR,
            'default' => '#4F46E5',
        ]);

        $this->end_controls_section();

        // --- SECTION 1: TOP IDENTITY ---
        $this->start_controls_section('section_top', ['label' => 'Plan Identity']);
        $this->add_control('plan_name', ['label' => 'Plan Name', 'type' => Controls_Manager::TEXT, 'default' => 'Startup']);
        $this->add_control('plan_desc', ['label' => 'Plan Tagline', 'type' => Controls_Manager::TEXT, 'default' => 'For E-commerce Store']);
        $this->add_control('old_price', ['label' => 'Original Price', 'type' => Controls_Manager::TEXT, 'default' => '₹ 499.00']);
        $this->add_control('save_badge', ['label' => 'Save Badge Text', 'type' => Controls_Manager::TEXT, 'default' => 'Save 80%']);
        $this->add_control('currency', ['label' => 'Currency Symbol', 'type' => Controls_Manager::TEXT, 'default' => '₹']);
        $this->add_control('price', ['label' => 'Sale Price', 'type' => Controls_Manager::TEXT, 'default' => '249.00']);
        $this->add_control('freq', ['label' => 'Frequency', 'type' => Controls_Manager::TEXT, 'default' => '/mo']);
        $this->add_control('btn_text', ['label' => 'Button Text', 'type' => Controls_Manager::TEXT, 'default' => 'Choose plan']);
        $this->add_control('policy_text', ['label' => 'Policy Footer', 'type' => Controls_Manager::TEXT, 'default' => 'Cancel anytime. 100% Refund Policy*']);
        $this->end_controls_section();

        // --- SECTION 2: PILL BADGES ---
        $this->start_controls_section('section_badges', ['label' => 'Quick Stats (Pills)']);
        $repeater_badges = new Repeater();
        $repeater_badges->add_control('text', ['label' => 'Stat Text', 'type' => Controls_Manager::TEXT, 'default' => '5 Website']);
        $repeater_badges->add_control('color', ['label' => 'Pill Color', 'type' => Controls_Manager::COLOR, 'default' => '#E0E7FF']);
        $this->add_control('badges_list', [
            'label' => 'Badges',
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater_badges->get_controls(),
            'default' => [['text' => '5 Website', 'color' => '#E0E7FF'], ['text' => '25 Emails', 'color' => '#DBEAFE'], ['text' => 'Daily', 'color' => '#FFEDD5']],
        ]);
        $this->end_controls_section();

        // --- SECTION 3: STORAGE GRID ---
        $this->start_controls_section('section_grid', ['label' => 'Performance Grid']);
        $this->add_control('grid_1_label', ['label' => 'Metric 1 Label', 'type' => Controls_Manager::TEXT, 'default' => 'STORAGE']);
        $this->add_control('grid_1_val', ['label' => 'Metric 1 Value', 'type' => Controls_Manager::TEXT, 'default' => '100 GB NVMe']);
        $this->add_control('grid_2_label', ['label' => 'Metric 2 Label', 'type' => Controls_Manager::TEXT, 'default' => 'BANDWIDTH']);
        $this->add_control('grid_2_val', ['label' => 'Metric 2 Value', 'type' => Controls_Manager::TEXT, 'default' => '1 TB/mo']);
        $this->add_control('grid_3_label', ['label' => 'Metric 3 Label', 'type' => Controls_Manager::TEXT, 'default' => 'VISITS']);
        $this->add_control('grid_3_val', ['label' => 'Metric 3 Value', 'type' => Controls_Manager::TEXT, 'default' => '1M visits/mo']);
        $this->add_control('grid_4_label', ['label' => 'Metric 4 Label', 'type' => Controls_Manager::TEXT, 'default' => 'PRODUCTS']);
        $this->add_control('grid_4_val', ['label' => 'Metric 4 Value', 'type' => Controls_Manager::TEXT, 'default' => 'Up to 5000']);
        $this->end_controls_section();

        // --- SECTION 4: FEATURE LIST ---
        $this->start_controls_section('section_features', ['label' => 'Feature Checklist']);
        $repeater_features = new Repeater();
        $repeater_features->add_control('f_text', ['label' => 'Feature', 'type' => Controls_Manager::TEXT, 'default' => 'Plugin Auto-Updates']);
        $repeater_features->add_control('icon_style', [
            'label' => 'Icon Style',
            'type' => Controls_Manager::SELECT,
            'default' => 'dark',
            'options' => ['dark' => 'Solid Black', 'light' => 'Light Grey'],
        ]);
        $this->add_control('features_list', [
            'label' => 'Features',
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater_features->get_controls(),
            'default' => [['f_text' => 'Everything in Blogger', 'icon_style' => 'light'], ['f_text' => 'Plugin Auto-Updates', 'icon_style' => 'dark']],
        ]);
        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $id = $this->get_id();
        $is_rec = 'yes' === $settings['is_recommended'];
        $accent = $settings['accent_color'];
        ?>

        <style>
            .cora-pricing-wrapper-<?php echo $id; ?> {
                position: relative;
                /* padding-top: <?php echo $is_rec ? '42px' : '0'; ?>; */
                width: 100%;
                max-width: 400px;
                margin: 0 auto;
            }

            .cora-p-v3-<?php echo $id; ?> {
                background: #FFFFFF;
                border: <?php echo $is_rec ? '2.5px solid ' . $accent : '1.5px solid #F1F5F9'; ?>;
                border-radius: 36px;
                position: relative;
                padding: 40px 32px;
                font-family: "Inter", sans-serif;
                display: flex;
                flex-direction: column;
                gap: 28px;
                box-shadow: <?php echo $is_rec ? '0 25px 50px -12px rgba(79, 70, 229, 0.12)' : '0 4px 20px rgba(0,0,0,0.02)'; ?>;
                z-index: 2;
            }

            /* --- Recommended Tag (Floating Above) --- */
            .cora-pricing-wrapper-<?php echo $id; ?> .rec-tag-outside {
                position: absolute;
                top: 0;
                left: 50%;
                transform: translateX(-50%);
                background: <?php echo $accent; ?>;
                color: #FFFFFF;
                padding: 10px 48px;
                font-family: "Fredoka", sans-serif;
                font-size: 13px;
                font-weight: 700;
                letter-spacing: 0.1em;
                border-radius: 12px 12px 0 0;
                z-index: 1;
                width: 85%;
                text-align: center;
            }

            /* --- Plan Header --- */
            .cora-p-v3-<?php echo $id; ?> .plan-name { font-family: "Fredoka", sans-serif; font-size: 32px; font-weight: 700; color: #1e2b5e; margin: 0; }
            .cora-p-v3-<?php echo $id; ?> .plan-desc { font-size: 15px; color: #94a3b8; margin: 4px 0 0 0; font-weight: 500; }
            
            /* --- Price Block --- */
            .cora-p-v3-<?php echo $id; ?> .price-box { margin-top: 12px; position: relative; display: flex; flex-direction: column; }
            .cora-p-v3-<?php echo $id; ?> .old-price { text-decoration: line-through; color: #cbd5e1; font-size: 16px; font-weight: 600; margin-bottom: 4px; }
            .cora-p-v3-<?php echo $id; ?> .save-badge { position: absolute; right: 0; top: 0; background: #DCFCE7; color: #10B981; padding: 8px 16px; border-radius: 10px; font-size: 15px; font-weight: 700; }
            
            .cora-p-v3-<?php echo $id; ?> .main-price { display: flex; align-items: baseline; gap: 2px; color: #1e2b5e; }
            .cora-p-v3-<?php echo $id; ?> .currency { font-size: 20px; font-weight: 700; transform: translateY(-12px); }
            .cora-p-v3-<?php echo $id; ?> .amount { font-size: 64px; font-weight: 700; font-family: "Fredoka", sans-serif; line-height: 1; letter-spacing: -0.02em; }
            .cora-p-v3-<?php echo $id; ?> .freq { font-size: 20px; font-weight: 600; color: #1e2b5e; margin-left: 2px; }

            /* --- Button Styling --- */
            .cora-p-v3-<?php echo $id; ?> .plan-btn { 
                width: 100%; padding: 18px; border-radius: 16px; 
                font-size: 18px; font-weight: 700; cursor: pointer; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                border: 2px solid <?php echo $is_rec ? $accent : '#0F172A'; ?>;
                background: <?php echo $is_rec ? $accent : '#FFFFFF'; ?>;
                color: <?php echo $is_rec ? '#FFFFFF' : '#0F172A'; ?>;
                margin-top: 8px;
            }
            .cora-p-v3-<?php echo $id; ?> .plan-btn:hover { transform: scale(1.02); }
            .cora-p-v3-<?php echo $id; ?> .policy-footer { text-align: center; font-size: 12px; font-weight: 700; color: #0F172A; margin: 4px 0 0 0; }

            /* --- Stat Pills --- */
            .cora-p-v3-<?php echo $id; ?> .badge-row { display: flex; flex-wrap: wrap; gap: 8px; }
            .cora-p-v3-<?php echo $id; ?> .pill-badge { padding: 8px 18px; border-radius: 100px; font-size: 13px; font-weight: 700; color: #6366f1; }

            /* --- Metric Grid (Clean Design) --- */
            .cora-p-v3-<?php echo $id; ?> .metric-grid { background: #F1F5F9; border-radius: 28px; padding: 28px; display: grid; grid-template-columns: 1fr 1fr; gap: 28px; }
            .cora-p-v3-<?php echo $id; ?> .m-label { font-size: 11px; font-weight: 700; color: #94A3B8; text-transform: uppercase; letter-spacing: 0.05em; display: block; }
            .cora-p-v3-<?php echo $id; ?> .m-val { font-size: 16px; font-weight: 700; color: #0F172A; margin-top: 6px; display: block; }

            /* --- Feature List --- */
            .cora-p-v3-<?php echo $id; ?> .feature-stack { display: flex; flex-direction: column; gap: 16px; }
            .cora-p-v3-<?php echo $id; ?> .f-item { display: flex; align-items: center; gap: 12px; font-size: 16px; font-weight: 500; color: #475569; }
            .cora-p-v3-<?php echo $id; ?> .f-icon { width: 28px; height: 28px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 12px; flex-shrink: 0; }
            .cora-p-v3-<?php echo $id; ?> .f-icon.dark { background: #000; color: #FFF; }
            .cora-p-v3-<?php echo $id; ?> .f-icon.light { background: #F1F5F9; color: #94A3B8; }
        </style>

        <div class="cora-pricing-wrapper-<?php echo $id; ?>">
            <!-- <?php if($is_rec): ?>
                <div class="rec-tag-outside"><?php echo esc_html($settings['recommended_text']); ?></div>
            <?php endif; ?> -->

            <div class="cora-p-v3-<?php echo $id; ?>">
                <div class="top-box">
                    <h3 class="plan-name"><?php echo esc_html($settings['plan_name']); ?></h3>
                    <p class="plan-desc"><?php echo esc_html($settings['plan_desc']); ?></p>
                    
                    <div class="price-box">
                        <span class="old-price"><?php echo esc_html($settings['old_price']); ?></span>
                        <span class="save-badge"><?php echo esc_html($settings['save_badge']); ?></span>
                        <div class="main-price">
                            <span class="currency"><?php echo esc_html($settings['currency']); ?></span>
                            <span class="amount"><?php echo esc_html($settings['price']); ?></span>
                            <span class="freq"><?php echo esc_html($settings['freq']); ?></span>
                        </div>
                    </div>

                    <button class="plan-btn"><?php echo esc_html($settings['btn_text']); ?></button>
                    <p class="policy-footer"><?php echo esc_html($settings['policy_text']); ?></p>
                </div>

                <div class="badge-row">
                    <?php foreach($settings['badges_list'] as $badge): ?>
                        <span class="pill-badge" style="background: <?php echo esc_attr($badge['color']); ?>;">
                            <?php echo esc_html($badge['text']); ?>
                        </span>
                    <?php endforeach; ?>
                </div>

                <div class="metric-grid">
                    <div><span class="m-label"><?php echo esc_html($settings['grid_1_label']); ?></span><span class="m-val"><?php echo esc_html($settings['grid_1_val']); ?></span></div>
                    <div><span class="m-label"><?php echo esc_html($settings['grid_2_label']); ?></span><span class="m-val"><?php echo esc_html($settings['grid_2_val']); ?></span></div>
                    <div><span class="m-label"><?php echo esc_html($settings['grid_3_label']); ?></span><span class="m-val"><?php echo esc_html($settings['grid_3_val']); ?></span></div>
                    <div><span class="m-label"><?php echo esc_html($settings['grid_4_label']); ?></span><span class="m-val"><?php echo esc_html($settings['grid_4_val']); ?></span></div>
                </div>

                <div class="feature-stack">
                    <?php foreach($settings['features_list'] as $f): ?>
                        <div class="f-item">
                            <div class="f-icon <?php echo esc_attr($f['icon_style']); ?>">
                                <i class="fas <?php echo ($f['icon_style'] === 'dark') ? 'fa-bolt' : 'fa-check'; ?>"></i>
                            </div>
                            <?php echo esc_html($f['f_text']); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php
    }
}