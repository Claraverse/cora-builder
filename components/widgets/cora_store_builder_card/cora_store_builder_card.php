<?php
namespace Cora_Builder\Components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;

if (!defined('ABSPATH'))
    exit;

class Cora_Store_Builder_Card extends Base_Widget
{
    public function get_name() { return 'cora_store_builder_card'; }
    public function get_title() { return 'Cora Store Builder Card'; }
    public function get_icon() { return 'eicon-gallery-grid'; }

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
        $this->add_control('icon_heading', ['label' => 'Icon', 'type' => Controls_Manager::HEADING]);
        
        $this->add_control('icon_source', [
            'label' => 'Icon Source',
            'type' => Controls_Manager::SELECT,
            'default' => 'library',
            'options' => [ 'library' => 'Icon Library', 'custom' => 'Custom SVG' ],
        ]);

        $this->add_control('card_icon', [
            'label' => 'Select Icon',
            'type' => Controls_Manager::ICONS,
            'default' => [ 'value' => 'fas fa-layer-group', 'library' => 'solid' ], // Placeholder shape
            'condition' => ['icon_source' => 'library'],
        ]);

        $this->add_control('card_svg', [
            'label' => 'SVG Code',
            'type' => Controls_Manager::TEXTAREA,
            'rows' => 5,
            'placeholder' => '<svg>...</svg>',
            'condition' => ['icon_source' => 'custom'],
        ]);

        // Badge
        $this->add_control('badge_text', [
            'label' => 'Badge Text',
            'type' => Controls_Manager::TEXT,
            'default' => 'Coming Soon',
            'dynamic' => ['active' => true],
        ]);

        // Text
        $this->add_control('text_heading', ['label' => 'Text', 'type' => Controls_Manager::HEADING, 'separator' => 'before']);
        
        $this->add_control('title', [
            'label' => 'Title',
            'type' => Controls_Manager::TEXT,
            'default' => 'Cora Store Builder',
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('desc', [
            'label' => 'Description',
            'type' => Controls_Manager::TEXTAREA,
            'default' => 'Drag-and-drop, pre-built store templates.',
            'dynamic' => ['active' => true],
        ]);

        $this->end_controls_section();

        // --- STYLE ---
        $this->start_controls_section('section_style', ['label' => 'Design', 'tab' => Controls_Manager::TAB_STYLE]);

        // Container Style
        $this->add_control('card_bg', [
            'label' => 'Card Background',
            'type' => Controls_Manager::COLOR,
            'default' => '#000000', // Deep Black
            'selectors' => ['{{WRAPPER}} .cora-sb-root' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Border::get_type(), [
            'name' => 'card_border',
            'selector' => '{{WRAPPER}} .cora-sb-root',
            'fields_options' => [
                'border' => ['default' => 'solid'],
                'width' => ['default' => ['top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1, 'isLinked' => true]],
                'color' => ['default' => '#1F1F1F'], // Dark Grey Border
            ],
        ]);

        $this->add_responsive_control('card_padding', [
            'label' => 'Padding',
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'default' => ['top' => 32, 'right' => 32, 'bottom' => 32, 'left' => 32, 'unit' => 'px', 'isLinked' => true],
            'selectors' => ['{{WRAPPER}} .cora-sb-root' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        // Icon Box Style
        $this->add_control('icon_box_heading', ['label' => 'Icon Box', 'type' => Controls_Manager::HEADING, 'separator' => 'before']);
        
        $this->add_control('icon_bg', [
            'label' => 'Icon Background',
            'type' => Controls_Manager::COLOR,
            'default' => '#111111',
            'selectors' => ['{{WRAPPER}} .sb-icon-box' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_control('icon_color', [
            'label' => 'Icon Color',
            'type' => Controls_Manager::COLOR,
            'default' => '#FFFFFF',
            'selectors' => ['{{WRAPPER}} .sb-icon-box' => 'color: {{VALUE}}; fill: {{VALUE}};'],
        ]);

        // Typography
        $this->add_control('typo_heading', ['label' => 'Typography', 'type' => Controls_Manager::HEADING, 'separator' => 'before']);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'title_typo',
            'label' => 'Title Font',
            'selector' => '{{WRAPPER}} .sb-title',
        ]);
        
        $this->add_control('title_color', [
            'label' => 'Title Color',
            'type' => Controls_Manager::COLOR,
            'default' => '#FFFFFF',
            'selectors' => ['{{WRAPPER}} .sb-title' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'desc_typo',
            'label' => 'Desc Font',
            'selector' => '{{WRAPPER}} .sb-desc',
        ]);

        $this->add_control('desc_color', [
            'label' => 'Desc Color',
            'type' => Controls_Manager::COLOR,
            'default' => '#94A3B8', // Slate 400
            'selectors' => ['{{WRAPPER}} .sb-desc' => 'color: {{VALUE}};'],
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
                flex-direction: column;
                border-radius: 24px; /* Soft Corners */
                box-sizing: border-box;
                gap: 24px;
                /* Default colors handled by controls, but fallback to dark theme */
                background-color: #000000;
                border: 1px solid #1F1F1F;
                padding: 32px;
                width: 100%;
                min-height: 280px; /* Ensure vertical presence */
            }

            /* --- Header: Icon Left, Badge Right --- */
            .cora-root-<?php echo $id; ?> .sb-header {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                width: 100%;
                margin-bottom: 32px; /* Push content down */
            }

            .cora-root-<?php echo $id; ?> .sb-icon-box {
                width: 64px;
                height: 64px;
                border-radius: 16px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 24px;
                transition: transform 0.3s ease;
            }

            .cora-root-<?php echo $id; ?> .sb-icon-box svg {
                width: 28px; height: 28px; fill: currentColor;
            }

            /* Badge Pill */
            .cora-root-<?php echo $id; ?> .sb-badge {
                padding: 8px 16px;
                background-color: rgba(255, 255, 255, 0.08);
                border: 1px solid #333333;
                border-radius: 100px;
                font-family: "Inter", sans-serif;
                font-size: 12px;
                font-weight: 600;
                color: #FFFFFF;
                white-space: nowrap;
            }

            /* --- Content --- */
            .cora-root-<?php echo $id; ?> .sb-body {
                display: flex;
                flex-direction: column;
                gap: 8px;
            }

            .cora-root-<?php echo $id; ?> .sb-title {
                margin: 0;
                font-family: "Fredoka", sans-serif;
                font-size: 28px;
                font-weight: 500;
                line-height: 1.2;
            }

            .cora-root-<?php echo $id; ?> .sb-desc {
                margin: 0;
                font-family: "Inter", sans-serif;
                font-size: 16px;
                line-height: 1.6;
                color: #94A3B8;
            }

            /* --- Hover Effect --- */
            .cora-root-<?php echo $id; ?>:hover .sb-icon-box {
                transform: scale(1.05);
            }
        </style>

        <div class="cora-unit-container cora-sb-root cora-root-<?php echo $id; ?>">
            
            <div class="sb-header">
                <div class="sb-icon-box">
                    <?php if ( 'custom' === $settings['icon_source'] ) : ?>
                        <?php echo $settings['card_svg']; ?>
                    <?php else : ?>
                        <?php Icons_Manager::render_icon( $settings['card_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                    <?php endif; ?>
                </div>

                <?php if (!empty($settings['badge_text'])) : ?>
                    <span class="sb-badge"><?php echo esc_html($settings['badge_text']); ?></span>
                <?php endif; ?>
            </div>

            <div class="sb-body">
                <h3 class="sb-title"><?php echo esc_html($settings['title']); ?></h3>
                <p class="sb-desc"><?php echo esc_html($settings['desc']); ?></p>
            </div>

        </div>
        <?php
    }
}