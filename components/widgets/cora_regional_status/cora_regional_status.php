<?php
namespace Cora_Builder\Components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

if (!defined('ABSPATH'))
    exit;

class Cora_Regional_Status extends Base_Widget
{
    public function get_name() { return 'cora_regional_status'; }
    public function get_title() { return 'Cora Regional Status'; }
    public function get_icon() { return 'eicon-map-pin'; }

    // Load Fonts
    public function get_style_depends() {
        wp_register_style('cora-fonts', 'https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap', [], null);
        return ['cora-fonts'];
    }

    protected function register_controls()
    {
        // --- CONTENT: HEADER ---
        $this->start_controls_section('section_header', ['label' => 'Header & Status']);

        $this->add_control('status_label', [
            'label' => 'Status Text',
            'type' => Controls_Manager::TEXT,
            'default' => 'OPERATIONAL',
        ]);

        $this->add_control('dc_count', [
            'label' => 'DC Count Badge',
            'type' => Controls_Manager::TEXT,
            'default' => '3 DCs',
        ]);

        $this->end_controls_section();

        // --- CONTENT: REGION ---
        $this->start_controls_section('section_region', ['label' => 'Region & Locations']);

        $this->add_control('region_name', [
            'label' => 'Region Name',
            'type' => Controls_Manager::TEXT,
            'default' => 'India',
            'dynamic' => ['active' => true],
        ]);

        $repeater = new Repeater();
        $repeater->add_control('city', [
            'label' => 'City Name',
            'type' => Controls_Manager::TEXT,
            'default' => 'Mumbai',
        ]);

        $this->add_control('locations', [
            'label' => 'Active Cities',
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                ['city' => 'Mumbai'],
                ['city' => 'Bangalore'],
                ['city' => 'Delhi'],
            ],
            'title_field' => '{{{ city }}}',
        ]);

        $this->end_controls_section();

        // --- CONTENT: METRICS ---
        $this->start_controls_section('section_metrics', ['label' => 'Performance Metrics']);

        $this->add_control('latency', ['label' => 'Latency', 'type' => Controls_Manager::TEXT, 'default' => '< 50ms']);
        $this->add_control('uptime', ['label' => 'Uptime', 'type' => Controls_Manager::TEXT, 'default' => '99.99%']);
        $this->add_control('requests', ['label' => 'Requests', 'type' => Controls_Manager::TEXT, 'default' => '12.4M']);

        $this->end_controls_section();

        // --- STYLE ---
        $this->start_controls_section('section_style', ['label' => 'Design', 'tab' => Controls_Manager::TAB_STYLE]);

        $this->add_control('card_bg', [
            'label' => 'Background Color',
            'type' => Controls_Manager::COLOR,
            'default' => '#FFFFFF',
            'selectors' => ['{{WRAPPER}} .regional-status-card' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'title_typo',
            'label' => 'Region Typography',
            'selector' => '{{WRAPPER}} .reg-title',
        ]);

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $id = $this->get_id();
        ?>

        <style>
            /* --- Card Architecture --- */
            .cora-root-<?php echo $id; ?> {
                background: #FFFFFF;
                border: 1px solid #F1F5F9;
                border-radius: 24px;
                padding: 32px;
                display: flex;
                flex-direction: column;
                gap: 24px;
                width: 100%;
                box-sizing: border-box;
                box-shadow: 0 4px 20px rgba(0,0,0,0.02);
            }

            /* --- Header: Status & DC Badge --- */
            .cora-root-<?php echo $id; ?> .reg-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                width: 100%;
            }

            .cora-root-<?php echo $id; ?> .status-indicator {
                font-family: "Inter", sans-serif;
                font-size: 11px;
                font-weight: 800;
                color: #000000;
                display: flex;
                align-items: center;
                gap: 8px;
                letter-spacing: 0.02em;
            }

            .cora-root-<?php echo $id; ?> .status-dot {
                width: 8px;
                height: 8px;
                background: #000000;
                border-radius: 50%;
            }

            .cora-root-<?php echo $id; ?> .dc-badge {
                font-family: "Inter", sans-serif;
                font-size: 11px;
                font-weight: 800;
                background: #F1F5F9;
                padding: 6px 14px;
                border-radius: 100px;
                color: #0F172A;
            }

            /* --- Region Body --- */
            .cora-root-<?php echo $id; ?> .reg-body {
                display: flex;
                flex-direction: column;
                gap: 16px;
            }

            .cora-root-<?php echo $id; ?> .reg-title {
                margin: 0 !important;
                font-family: "Fredoka", sans-serif;
                font-size: 38px;
                font-weight: 500;
                color: #000000;
                line-height: 1;
            }

            .cora-root-<?php echo $id; ?> .loc-label {
                font-family: "Inter", sans-serif;
                font-size: 11px;
                font-weight: 500;
                color: #94A3B8;
                letter-spacing: 0.1em;
                text-transform: uppercase;
            }

            .cora-root-<?php echo $id; ?> .loc-tag-row {
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
            }

            .cora-root-<?php echo $id; ?> .loc-tag {
                padding: 8px 20px;
                background: #F8FAFC;
                border: 1px solid #E2E8F0;
                border-radius: 10px;
                font-family: "Inter", sans-serif;
                font-size: 14px;
                font-weight: 600;
                color: #475569;
            }

            /* --- Metrics Grid --- */
            .cora-root-<?php echo $id; ?> .reg-metrics-row {
                display: flex;
                justify-content: space-between;
                padding-top: 16px;
                border-top: 1px solid #F1F5F9;
            }

            .cora-root-<?php echo $id; ?> .m-unit {
                display: flex;
                flex-direction: column;
                gap: 4px;
            }

            .cora-root-<?php echo $id; ?> .m-val {
                font-family: "Fredoka", sans-serif;
                font-size: 26px;
                font-weight: 500;
                color: #000000;
            }

            .cora-root-<?php echo $id; ?> .m-sub {
                font-family: "Inter", sans-serif;
                font-size: 10px;
                font-weight: 500;
                color: #94A3B8;
                letter-spacing: 0.05em;
                text-transform: uppercase;
            }

            /* --- RESPONSIVE --- */
            @media (max-width: 768px) {
                .cora-root-<?php echo $id; ?> {
                    padding: 24px;
                }
                .cora-root-<?php echo $id; ?> .reg-metrics-row {
                    flex-direction: column;
                    gap: 20px;
                }
                .cora-root-<?php echo $id; ?> .reg-title {
                    font-size: 32px;
                }
            }
        </style>

        <div class="cora-unit-container regional-status-card cora-root-<?php echo $id; ?>">
            <div class="reg-header">
                <div class="status-indicator">
                    <span class="status-dot"></span> <?php echo esc_html($settings['status_label']); ?>
                </div>
                <div class="dc-badge"><?php echo esc_html($settings['dc_count']); ?></div>
            </div>

            <div class="reg-body">
                <h3 class="reg-title"><?php echo esc_html($settings['region_name']); ?></h3>
                <span class="loc-label">LOCATIONS</span>
                <div class="loc-tag-row">
                    <?php foreach ($settings['locations'] as $loc) : ?>
                        <span class="loc-tag"><?php echo esc_html($loc['city']); ?></span>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="reg-metrics-row">
                <div class="m-unit">
                    <span class="m-val"><?php echo esc_html($settings['latency']); ?></span>
                    <span class="m-sub">LATENCY</span>
                </div>
                <div class="m-unit">
                    <span class="m-val"><?php echo esc_html($settings['uptime']); ?></span>
                    <span class="m-sub">UPTIME</span>
                </div>
                <div class="m-unit">
                    <span class="m-val"><?php echo esc_html($settings['requests']); ?></span>
                    <span class="m-sub">REQ/DAY</span>
                </div>
            </div>
        </div>
        <?php
    }
}