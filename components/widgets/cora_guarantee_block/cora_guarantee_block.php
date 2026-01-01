<?php
namespace Cora_Builder\Components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;

if (!defined('ABSPATH'))
    exit;

class Cora_Guarantee_Block extends Base_Widget
{
    public function get_name() { return 'cora_guarantee_block'; }
    public function get_title() { return 'Cora Guarantee Block'; }
    public function get_icon() { return 'eicon-shield-check'; }

    public function get_style_depends() {
        wp_register_style('cora-fonts', 'https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap', [], null);
        return ['cora-fonts'];
    }

    protected function register_controls()
    {
        // --- CONTENT: ANCHOR ---
        $this->start_controls_section('section_anchor', ['label' => 'Guarantee Anchor']);

        $this->add_control('days_val', [
            'label' => 'Days Count',
            'type' => Controls_Manager::TEXT,
            'default' => '30',
        ]);

        $this->add_control('anchor_sub', [
            'label' => 'Subtext',
            'type' => Controls_Manager::TEXTAREA,
            'default' => "Full Money-Back\nGuarantee",
        ]);

        $this->end_controls_section();

        // --- CONTENT: TRUST ITEMS ---
        $this->start_controls_section('section_trust', ['label' => 'Trust Factors']);

        $repeater = new Repeater();

        $repeater->add_control('item_title', [
            'label' => 'Title',
            'type' => Controls_Manager::TEXT,
            'default' => 'Full Refund',
        ]);

        $repeater->add_control('item_desc', [
            'label' => 'Description',
            'type' => Controls_Manager::TEXTAREA,
            'default' => '100% money back within 30 days, no questions asked',
        ]);

        $this->add_control('trust_items', [
            'label' => 'Trust Grid',
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                ['item_title' => 'Full Refund', 'item_desc' => '100% money back within 30 days, no questions asked'],
                ['item_title' => 'Keep Your Data', 'item_desc' => 'All migrated content remains yours forever'],
                ['item_title' => 'Instant Cancellation', 'item_desc' => 'Cancel anytime with a single click from your dashboard'],
                ['item_title' => 'Zero Risk', 'item_desc' => 'No commitments, no hidden fees, completely transparent'],
            ],
            'title_field' => '{{{ item_title }}}',
        ]);

        $this->end_controls_section();

        // --- STYLE ---
        $this->start_controls_section('section_style', ['label' => 'Design', 'tab' => Controls_Manager::TAB_STYLE]);

        $this->add_control('anchor_bg', [
            'label' => 'Anchor Background',
            'type' => Controls_Manager::COLOR,
            'default' => '#000000',
            'selectors' => ['{{WRAPPER}} .guarantee-anchor' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'days_typo',
            'label' => 'Days Font',
            'selector' => '{{WRAPPER}} .d-num',
        ]);

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $id = $this->get_id();
        ?>

        <style>
            .cora-root-<?php echo $id; ?> {
                display: flex;
                align-items: center;
                background: #FFFFFF;
                border: 1px solid #F1F5F9;
                border-radius: 32px;
                padding: 40px;
                gap: 64px;
                width: 100%;
                box-sizing: border-box;
            }

            /* --- Visual Anchor (Black Box) --- */
            .cora-root-<?php echo $id; ?> .guarantee-anchor {
                flex: 0 0 320px;
                background: #000000;
                border-radius: 24px;
                padding: 56px 32px;
                text-align: center;
                color: #ffffff;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                gap: 12px;
            }

            .cora-root-<?php echo $id; ?> .d-num {
                font-family: "Fredoka", sans-serif;
                font-size: 96px;
                font-weight: 500;
                line-height: 0.9;
                display: block;
            }

            .cora-root-<?php echo $id; ?> .d-label {
                font-family: "Fredoka", sans-serif;
                font-size: 22px;
                font-weight: 500;
                letter-spacing: 0.1em;
                display: block;
                margin-bottom: 8px;
            }

            .cora-root-<?php echo $id; ?> .anchor-divider {
                width: 40px;
                height: 3px;
                background: rgba(255,255,255,0.2);
                border-radius: 10px;
                margin: 8px 0;
            }

            .cora-root-<?php echo $id; ?> .anchor-text {
                font-family: "Inter", sans-serif;
                font-size: 16px;
                font-weight: 500;
                line-height: 1.4;
                opacity: 0.9;
                margin: 0;
                white-space: pre-line;
            }

            /* --- Trust Grid --- */
            .cora-root-<?php echo $id; ?> .trust-grid-column {
                flex: 1;
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 48px 40px;
            }

            .cora-root-<?php echo $id; ?> .trust-unit {
                display: flex;
                gap: 20px;
                align-items: flex-start;
            }

            .cora-root-<?php echo $id; ?> .trust-check-icon {
                width: 36px;
                height: 36px;
                background: #F1F5F9;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
                font-size: 16px;
                color: #000000;
            }

            .cora-root-<?php echo $id; ?> .trust-content {
                display: flex;
                flex-direction: column;
                gap: 6px;
            }

            .cora-root-<?php echo $id; ?> .trust-title {
                margin: 0 !important;
                font-family: "Fredoka", sans-serif;
                font-size: 19px;
                font-weight: 500;
                color: #000000;
            }

            .cora-root-<?php echo $id; ?> .trust-desc {
                margin: 0 !important;
                font-family: "Inter", sans-serif;
                font-size: 15px;
                color: #64748B;
                line-height: 1.5;
            }

            /* --- Mobile Support --- */
            @media (max-width: 1100px) {
                .cora-root-<?php echo $id; ?> {
                    flex-direction: column;
                    gap: 40px;
                    padding: 32px;
                }
                .cora-root-<?php echo $id; ?> .guarantee-anchor {
                    width: 100%;
                    flex: none;
                }
            }

            @media (max-width: 600px) {
                .cora-root-<?php echo $id; ?> .trust-grid-column {
                    grid-template-columns: 1fr;
                    gap: 32px;
                }
            }
        </style>

        <div class="cora-unit-container guarantee-split-card cora-root-<?php echo $id; ?>">
            
            <div class="guarantee-anchor">
                <div class="day-stack">
                    <span class="d-num"><?php echo esc_html($settings['days_val']); ?></span>
                    <span class="d-label">DAYS</span>
                </div>
                <div class="anchor-divider"></div>
                <p class="anchor-text"><?php echo esc_html($settings['anchor_sub']); ?></p>
            </div>

            <div class="trust-grid-column">
                <?php foreach($settings['trust_items'] as $item) : ?>
                    <div class="trust-unit">
                        <div class="trust-check-icon">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="trust-content">
                            <h4 class="trust-title"><?php echo esc_html($item['item_title']); ?></h4>
                            <p class="trust-desc"><?php echo esc_html($item['item_desc']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        </div>
        <?php
    }
}