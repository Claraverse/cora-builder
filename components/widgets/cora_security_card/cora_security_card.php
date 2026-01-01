<?php
namespace Cora_Builder\Components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Typography;

if (!defined('ABSPATH'))
    exit;

class Cora_Security_Card extends Base_Widget
{
    public function get_name() { return 'cora_security_card'; }
    public function get_title() { return 'Cora Security Card'; }
    public function get_icon() { return 'eicon-info-box'; }

    // Load Fonts
    public function get_style_depends() {
        wp_register_style('cora-fonts', 'https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap', [], null);
        return ['cora-fonts'];
    }

    protected function register_controls()
    {
        // --- CONTENT ---
        $this->start_controls_section('section_content', ['label' => 'Security Details']);

        // Icon Controls
        $this->add_control('icon_source', [
            'label' => 'Icon Source',
            'type' => Controls_Manager::SELECT,
            'default' => 'library',
            'options' => [
                'library' => 'Icon Library',
                'custom'  => 'Custom SVG',
            ],
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

        $this->add_control('status_label', [
            'label' => 'Status Badge',
            'type' => Controls_Manager::TEXT,
            'default' => 'Coming Soon',
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('title', [
            'label' => 'Headline',
            'type' => Controls_Manager::TEXT,
            'default' => 'Auto Security & Backup',
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('desc', [
            'label' => 'Description',
            'type' => Controls_Manager::TEXTAREA,
            'default' => 'Real-time malware scanning, automatic daily backups with 30-day retention...',
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('freq_val', [
            'label' => 'Frequency Value',
            'type' => Controls_Manager::TEXT,
            'default' => 'Daily',
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('scan_val', [
            'label' => 'Scan Value',
            'type' => Controls_Manager::TEXT,
            'default' => '24/7',
            'dynamic' => ['active' => true],
        ]);

        $this->end_controls_section();

        // --- STYLE ---
        $this->start_controls_section('section_style', ['label' => 'Design', 'tab' => Controls_Manager::TAB_STYLE]);

        $this->add_control('card_bg', [
            'label' => 'Card Background',
            'type' => Controls_Manager::COLOR,
            'default' => '#FFFFFF',
            'selectors' => ['{{WRAPPER}} .cora-sec-root' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'title_typo',
            'label' => 'Headline Typography',
            'selector' => '{{WRAPPER}} .sec-title',
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

            /* --- Header: Icon + Badge --- */
            .cora-root-<?php echo $id; ?> .sec-card-header {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                width: 100%;
            }

            .cora-root-<?php echo $id; ?> .sec-icon-box {
                width: 64px;
                height: 64px;
                background-color: #000000;
                color: #FFFFFF;
                border-radius: 16px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 24px;
            }
            .cora-root-<?php echo $id; ?> .sec-icon-box svg {
                width: 24px; height: 24px; fill: currentColor;
            }

            .cora-root-<?php echo $id; ?> .sec-status-badge {
                display: inline-block;
                padding: 6px 14px;
                border: 1px solid #E2E8F0;
                border-radius: 100px;
                font-family: "Inter", sans-serif;
                font-size: 11px;
                font-weight: 600;
                color: #64748B;
                background-color: #FFFFFF;
            }

            /* --- Body --- */
            .cora-root-<?php echo $id; ?> .sec-card-body {
                display: flex;
                flex-direction: column;
                gap: 4px;
            }

            .cora-root-<?php echo $id; ?> .sec-title {
                margin: 0;
                font-family: "Fredoka", sans-serif;
                font-size: 24px;
                font-weight: 500;
                color: #0F172A;
                line-height: 1.3;
            }

            .cora-root-<?php echo $id; ?> .sec-desc {
                margin: 0;
                font-family: "Inter", sans-serif;
                font-size: 15px;
                line-height: 1.6;
                color: #64748B;
            }

            /* --- Metrics Grid --- */
            .cora-root-<?php echo $id; ?> .sec-metric-grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 24px;
                padding-top: 12px;
            }

            .cora-root-<?php echo $id; ?> .metric-unit {
                display: flex;
                flex-direction: column;
                gap: 6px;
            }

            .cora-root-<?php echo $id; ?> .m-val {
                font-family: "Fredoka", sans-serif;
                font-size: 20px;
                font-weight: 500;
                color: #0F172A;
            }

            .cora-root-<?php echo $id; ?> .m-label {
                font-family: "Fredoka", sans-serif;
                font-size: 13px;
                font-weight: 600;
                color: #94A3B8; /* Muted Grey */
            }
        </style>

        <div class="cora-unit-container cora-sec-root cora-root-<?php echo $id; ?>">
            
            <div class="sec-card-header">
                <div class="sec-icon-box">
                    <?php if ( 'custom' === $settings['icon_source'] ) : ?>
                        <?php echo $settings['card_svg']; ?>
                    <?php else : ?>
                        <?php Icons_Manager::render_icon( $settings['card_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                    <?php endif; ?>
                </div>

                <?php if (!empty($settings['status_label'])) : ?>
                    <div class="sec-status-badge">
                        <?php echo esc_html($settings['status_label']); ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="sec-card-body">
                <h3 class="sec-title"><?php echo esc_html($settings['title']); ?></h3>
                <p class="sec-desc"><?php echo esc_html($settings['desc']); ?></p>
            </div>

            <div class="sec-metric-grid">
                <div class="metric-unit">
                    <span class="m-val"><?php echo esc_html($settings['freq_val']); ?></span>
                    <span class="m-label">Backup Frequency</span>
                </div>
                <div class="metric-unit">
                    <span class="m-val"><?php echo esc_html($settings['scan_val']); ?></span>
                    <span class="m-label">Threat Scans</span>
                </div>
            </div>

        </div>
        <?php
    }
}