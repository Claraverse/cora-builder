<?php
namespace Cora_Builder\Components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

if (!defined('ABSPATH'))
    exit;

class Cora_Protection_Card extends Base_Widget
{
    public function get_name() { return 'cora_protection_card'; }
    public function get_title() { return 'Cora Protection Card'; }
    public function get_icon() { return 'eicon-shield'; }

    // Load Fonts
    public function get_style_depends() {
        wp_register_style('cora-fonts', 'https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap', [], null);
        return ['cora-fonts'];
    }

    protected function register_controls()
    {
        // --- HEADER ---
        $this->start_controls_section('section_header', ['label' => 'Header Info']);

        $this->add_control('icon_source', [
            'label' => 'Icon Source',
            'type' => Controls_Manager::SELECT,
            'default' => 'library',
            'options' => [ 'library' => 'Icon Library', 'custom' => 'Custom SVG' ],
        ]);

        $this->add_control('card_icon', [
            'label' => 'Icon',
            'type' => Controls_Manager::ICONS,
            'default' => [ 'value' => 'fas fa-shield-alt', 'library' => 'solid' ],
            'condition' => ['icon_source' => 'library'],
        ]);

        $this->add_control('card_svg', [
            'label' => 'SVG Code',
            'type' => Controls_Manager::TEXTAREA,
            'rows' => 5,
            'placeholder' => '<svg>...</svg>',
            'condition' => ['icon_source' => 'custom'],
        ]);

        $this->add_control('badge_text', [
            'label' => 'Status Badge',
            'type' => Controls_Manager::TEXT,
            'default' => 'LIVE',
            'dynamic' => ['active' => true],
        ]);

        $this->end_controls_section();

        // --- CONTENT ---
        $this->start_controls_section('section_content', ['label' => 'Main Content']);

        $this->add_control('title', [
            'label' => 'Headline',
            'type' => Controls_Manager::TEXT,
            'default' => 'Fully Protected',
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('desc', [
            'label' => 'Description',
            'type' => Controls_Manager::TEXTAREA,
            'default' => 'Automated patches, firewall, and daily backups included.',
            'dynamic' => ['active' => true],
        ]);

        // --- SERVICE REPEATER ---
        $repeater = new Repeater();
        $repeater->add_control('label', [ 'label' => 'Service Name', 'type' => Controls_Manager::TEXT, 'default' => 'Malware Scanning' ]);
        $repeater->add_control('time', [ 'label' => 'Frequency/Status', 'type' => Controls_Manager::TEXT, 'default' => '24/7' ]);
        
        $this->add_control('services', [
            'label' => 'Security Services',
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                ['label' => 'Malware Scanning', 'time' => '24/7'],
                ['label' => 'Daily Backups', 'time' => 'Daily'],
                ['label' => 'Firewall Rules', 'time' => 'Active'],
            ],
            'separator' => 'before',
        ]);

        // --- FOOTER METRIC ---
        $this->add_control('metric_val', [
            'label' => 'Metric Value',
            'type' => Controls_Manager::TEXT,
            'default' => '99.9%',
            'dynamic' => ['active' => true],
            'separator' => 'before',
        ]);

        $this->add_control('metric_label', [
            'label' => 'Metric Label',
            'type' => Controls_Manager::TEXT,
            'default' => 'Threat Prevention',
            'dynamic' => ['active' => true],
        ]);

        $this->end_controls_section();

        // --- STYLE ---
        $this->start_controls_section('section_style', ['label' => 'Design', 'tab' => Controls_Manager::TAB_STYLE]);

        $this->add_control('card_bg', [
            'label' => 'Card Background',
            'type' => Controls_Manager::COLOR,
            'default' => '#FFFFFF',
            'selectors' => ['{{WRAPPER}} .cora-prot-root' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'title_typo',
            'label' => 'Headline Typography',
            'selector' => '{{WRAPPER}} .prot-title',
        ]);

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $id = $this->get_id();
        ?>

        <style>
            /* Card Container */
            .cora-root-<?php echo $id; ?> {
                display: flex;
                flex-direction: column;
                background-color: #FFFFFF;
                border: 1px solid #F1F5F9;
                border-radius: 24px;
                padding: 32px;
                width: 100%;
                box-sizing: border-box;
                gap: 24px;
                box-shadow: 0 4px 20px rgba(0,0,0,0.02);
            }

            /* --- Header --- */
            .cora-root-<?php echo $id; ?> .prot-header {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
            }

            .cora-root-<?php echo $id; ?> .prot-icon-box {
                width: 64px;
                height: 64px;
                background-color: #0F172A; /* Black/Dark Navy */
                color: #FFFFFF;
                border-radius: 16px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 24px;
            }
            .cora-root-<?php echo $id; ?> .prot-icon-box svg {
                width: 26px; height: 26px; fill: currentColor;
            }

            /* Live Pill */
            .cora-root-<?php echo $id; ?> .prot-live-pill {
                display: flex;
                align-items: center;
                gap: 6px;
                padding: 4px 12px;
                background-color: #F1F5F9;
                border-radius: 100px;
                font-family: "Inter", sans-serif;
                font-size: 11px;
                font-weight: 800;
                color: #0F172A;
                text-transform: uppercase;
                letter-spacing: 0.02em;
            }
            .cora-root-<?php echo $id; ?> .status-dot {
                width: 6px; height: 6px; background-color: #0F172A; border-radius: 50%;
            }

            /* --- Body --- */
            .cora-root-<?php echo $id; ?> .prot-title {
                margin: 0;
                font-family: "Fredoka", sans-serif;
                font-size: 32px;
                font-weight: 500; /* Bold */
                color: #0F172A;
                line-height: 1.2;
            }

            .cora-root-<?php echo $id; ?> .prot-desc {
                margin: 0;
                font-family: "Inter", sans-serif;
                font-size: 16px;
                line-height: 1.5;
                color: #64748B;
            }

            /* --- Service Stack --- */
            .cora-root-<?php echo $id; ?> .prot-service-stack {
                display: flex;
                flex-direction: column;
                gap: 12px;
            }

            .cora-root-<?php echo $id; ?> .prot-service-row {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 12px 16px;
                border: 1px solid #F1F5F9;
                border-radius: 12px;
                font-family: "Inter", sans-serif;
                font-size: 14px;
                font-weight: 600;
                color: #475569;
            }

            .cora-root-<?php echo $id; ?> .prot-time-pill {
                font-size: 12px;
                font-weight: 500;
                background-color: #F1F5F9;
                color: #0F172A;
                padding: 4px 12px;
                border-radius: 100px;
            }

            /* --- Footer Metric --- */
            .cora-root-<?php echo $id; ?> .prot-footer-metric {
                display: flex;
                flex-direction: column;
                gap: 4px;
                margin-top: 8px; /* Extra spacing */
            }

            .cora-root-<?php echo $id; ?> .m-val {
                font-family: "Fredoka", sans-serif;
                font-size: 56px;
                font-weight: 500; /* Extra Bold */
                color: #0F172A;
                line-height: 1;
                letter-spacing: -0.02em;
            }

            .cora-root-<?php echo $id; ?> .m-label {
                font-family: "Fredoka", sans-serif;
                font-size: 14px;
                font-weight: 500;
                color: #94A3B8;
            }
        </style>

        <div class="cora-unit-container cora-prot-root cora-root-<?php echo $id; ?>">
            
            <div class="prot-header">
                <div class="prot-icon-box">
                    <?php if ( 'custom' === $settings['icon_source'] ) : ?>
                        <?php echo $settings['card_svg']; ?>
                    <?php else : ?>
                        <?php Icons_Manager::render_icon( $settings['card_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                    <?php endif; ?>
                </div>

                <?php if (!empty($settings['badge_text'])) : ?>
                    <div class="prot-live-pill">
                        <span class="status-dot"></span>
                        <?php echo esc_html($settings['badge_text']); ?>
                    </div>
                <?php endif; ?>
            </div>

            <h3 class="prot-title"><?php echo esc_html($settings['title']); ?></h3>
            <p class="prot-desc"><?php echo esc_html($settings['desc']); ?></p>

            <div class="prot-service-stack">
                <?php foreach ($settings['services'] as $service) : ?>
                    <div class="prot-service-row">
                        <span><?php echo esc_html($service['label']); ?></span>
                        <span class="prot-time-pill"><?php echo esc_html($service['time']); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="prot-footer-metric">
                <span class="m-val"><?php echo esc_html($settings['metric_val']); ?></span>
                <span class="m-label"><?php echo esc_html($settings['metric_label']); ?></span>
            </div>

        </div>
        <?php
    }
}