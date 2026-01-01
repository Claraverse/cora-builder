<?php
namespace Cora_Builder\Components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;

if (!defined('ABSPATH'))
    exit;

class Cora_Infra_Scaling extends Base_Widget
{
    public function get_name() { return 'cora_infra_scaling'; }
    public function get_title() { return 'Cora Infra Scaling'; }
    public function get_icon() { return 'eicon-code'; }

    // Load Fonts
    public function get_style_depends() {
        wp_register_style('cora-fonts', 'https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap', [], null);
        return ['cora-fonts'];
    }

    protected function register_controls()
    {
        // --- CONTENT ---
        $this->start_controls_section('section_content', ['label' => 'Card Content']);

        // Icon
        $this->add_control('icon_heading', ['label' => 'Main Icon', 'type' => Controls_Manager::HEADING]);
        $this->add_control('icon_source', [
            'label' => 'Source',
            'type' => Controls_Manager::SELECT,
            'default' => 'library',
            'options' => [ 'library' => 'Icon Library', 'custom' => 'Custom SVG' ],
        ]);
        $this->add_control('main_icon', [
            'label' => 'Icon',
            'type' => Controls_Manager::ICONS,
            'default' => [ 'value' => 'fas fa-bolt', 'library' => 'solid' ],
            'condition' => ['icon_source' => 'library'],
        ]);
        $this->add_control('main_svg', [
            'label' => 'SVG Code',
            'type' => Controls_Manager::TEXTAREA,
            'rows' => 5,
            'condition' => ['icon_source' => 'custom'],
        ]);

        // Header Info
        $this->add_control('title', [
            'label' => 'Title',
            'type' => Controls_Manager::TEXT,
            'default' => 'AI-Powered Performance Engine',
            'dynamic' => ['active' => true],
            'separator' => 'before',
        ]);

        $this->add_control('status_text', [
            'label' => 'Status Label',
            'type' => Controls_Manager::TEXT,
            'default' => 'Active',
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('desc', [
            'label' => 'Description',
            'type' => Controls_Manager::TEXTAREA,
            'default' => 'Machine learning algorithms continuously analyze and optimize your store\'s performance.',
            'dynamic' => ['active' => true],
        ]);

        // Tags Repeater
        $repeater = new Repeater();
        $repeater->add_control('tag_label', [
            'label' => 'Tag Label',
            'type' => Controls_Manager::TEXT,
            'default' => 'Global CDN',
        ]);
        $this->add_control('tags_list', [
            'label' => 'Feature Tags',
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                ['tag_label' => 'Global CDN'],
                ['tag_label' => 'Smart Caching'],
                ['tag_label' => 'Image Optimization'],
                ['tag_label' => 'Database Tuning'],
            ],
            'separator' => 'before',
        ]);

        // Metric Badge
        $this->add_control('metric_heading', ['label' => 'Performance Metric', 'type' => Controls_Manager::HEADING, 'separator' => 'before']);
        
        $this->add_control('metric_val', [
            'label' => 'Value',
            'type' => Controls_Manager::TEXT,
            'default' => '327%',
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('metric_label', [
            'label' => 'Label',
            'type' => Controls_Manager::TEXT,
            'default' => 'faster on avg',
            'dynamic' => ['active' => true],
        ]);

        $this->end_controls_section();

        // --- STYLE ---
        $this->start_controls_section('section_style', ['label' => 'Design', 'tab' => Controls_Manager::TAB_STYLE]);

        $this->add_control('card_bg', [
            'label' => 'Card Background',
            'type' => Controls_Manager::COLOR,
            'default' => '#FFFFFF',
            'selectors' => ['{{WRAPPER}} .cora-infra-root' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'title_typo',
            'label' => 'Title Typography',
            'selector' => '{{WRAPPER}} .cora-title',
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
                flex-direction: row;
                align-items: flex-start;
                background-color: #FFFFFF;
                border: 1px solid #F1F5F9;
                border-radius: 24px;
                padding: 32px;
                gap: 24px;
                width: 100%;
                box-sizing: border-box;
                /* Subtle shadow for depth */
                box-shadow: 0 4px 12px rgba(0,0,0,0.02);
            }

            /* --- Left Column: Icon --- */
            .cora-root-<?php echo $id; ?> .cora-icon-box {
                flex-shrink: 0;
                width: 56px;
                height: 56px;
                background-color: #000000;
                border-radius: 16px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #FFFFFF;
                font-size: 24px;
            }
            .cora-root-<?php echo $id; ?> .cora-icon-box svg {
                width: 24px; height: 24px; fill: currentColor;
            }

            /* --- Right Column: Content --- */
            .cora-root-<?php echo $id; ?> .cora-content-col {
                flex: 1;
                display: flex;
                flex-direction: column;
                gap: 16px;
                width: 100%; /* Ensure tags wrap correctly */
            }

            /* Header Row: Title + Status */
            .cora-root-<?php echo $id; ?> .cora-header-row {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                width: 100%;
                gap: 16px;
            }

            .cora-root-<?php echo $id; ?> .cora-title {
                margin: 0;
                font-family: "Fredoka", sans-serif;
                font-size: 20px;
                font-weight: 600;
                color: #0F172A;
                line-height: 1.3;
            }

            /* Status Pill */
            .cora-root-<?php echo $id; ?> .cora-status {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                background-color: #F8FAFC;
                border: 1px solid #E2E8F0;
                padding: 4px 10px;
                border-radius: 100px;
                font-family: "Inter", sans-serif;
                font-size: 11px;
                font-weight: 600;
                color: #0F172A;
                white-space: nowrap;
            }
            .cora-root-<?php echo $id; ?> .cora-status-dot {
                width: 6px; height: 6px; background-color: #0F172A; border-radius: 50%;
            }

            /* Description */
            .cora-root-<?php echo $id; ?> .cora-desc {
                margin: 0;
                font-family: "Inter", sans-serif;
                font-size: 15px;
                line-height: 1.6;
                color: #64748B;
            }

            /* Tags Row */
            .cora-root-<?php echo $id; ?> .cora-tags-wrapper {
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
            }
            .cora-root-<?php echo $id; ?> .cora-tag {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                border: 1px solid #E2E8F0;
                background-color: #FFFFFF; /* Or very light grey */
                padding: 6px 12px;
                border-radius: 8px;
                font-family: "Inter", sans-serif;
                font-size: 12px;
                font-weight: 500;
                color: #475569;
            }
            .cora-root-<?php echo $id; ?> .cora-tag-dot {
                color: #94A3B8; font-size: 8px;
            }

            /* Metric Badge */
            .cora-root-<?php echo $id; ?> .cora-metric {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                background-color: #000000;
                padding: 8px 16px;
                border-radius: 10px;
                width: fit-content;
                color: #FFFFFF;
                font-family: "Fredoka", sans-serif;
                font-size: 13px;
                margin-top: 4px;
            }
            .cora-root-<?php echo $id; ?> .cora-metric-val {
                font-weight: 500; font-size: 15px;
            }
            .cora-root-<?php echo $id; ?> .cora-metric-label {
                opacity: 0.8; font-weight: 400;
            }

            /* Responsive */
            @media (max-width: 768px) {
                .cora-root-<?php echo $id; ?> {
                    flex-direction: column;
                }
                .cora-root-<?php echo $id; ?> .cora-header-row {
                    flex-direction: column-reverse; /* Put title under status? Or just wrap */
                    align-items: flex-start;
                    gap: 12px;
                }
                .cora-root-<?php echo $id; ?> .cora-status {
                    align-self: flex-start;
                }
            }
        </style>

        <div class="cora-unit-container cora-infra-root cora-root-<?php echo $id; ?>">
            
            <div class="cora-icon-box">
                <?php if ( 'custom' === $settings['icon_source'] ) : ?>
                    <?php echo $settings['main_svg']; ?>
                <?php else : ?>
                    <?php Icons_Manager::render_icon( $settings['main_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                <?php endif; ?>
            </div>

            <div class="cora-content-col">
                
                <div class="cora-header-row">
                    <h3 class="cora-title"><?php echo esc_html($settings['title']); ?></h3>
                    <?php if (!empty($settings['status_text'])) : ?>
                        <div class="cora-status">
                            <span class="cora-status-dot"></span>
                            <?php echo esc_html($settings['status_text']); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <p class="cora-desc"><?php echo esc_html($settings['desc']); ?></p>

                <?php if (!empty($settings['tags_list'])) : ?>
                    <div class="cora-tags-wrapper">
                        <?php foreach ($settings['tags_list'] as $tag) : ?>
                            <div class="cora-tag">
                                <i class="fas fa-circle cora-tag-dot" style="font-size: 4px;"></i>
                                <?php echo esc_html($tag['tag_label']); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($settings['metric_val'])) : ?>
                    <div class="cora-metric">
                        <span class="cora-metric-val"><?php echo esc_html($settings['metric_val']); ?></span>
                        <span class="cora-metric-label"><?php echo esc_html($settings['metric_label']); ?></span>
                    </div>
                <?php endif; ?>

            </div>

        </div>
        <?php
    }
}