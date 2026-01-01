<?php
namespace Cora_Builder\Components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Typography;

if (!defined('ABSPATH'))
    exit;

class Cora_Hosting_Price extends Base_Widget
{
    public function get_name() { return 'cora_hosting_price'; }
    public function get_title() { return 'Cora Hosting Price'; }
    public function get_icon() { return 'eicon-price-table'; }

    // Load Fredoka Font
    public function get_style_depends() {
        wp_register_style('cora-fonts', 'https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&display=swap', [], null);
        return ['cora-fonts'];
    }

    protected function register_controls()
    {
        // --- CONTENT ---
        $this->start_controls_section('section_content', ['label' => 'Price Content']);

        $this->add_control('currency', [
            'label' => 'Currency Symbol',
            'type' => Controls_Manager::TEXT,
            'default' => '₹',
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('price', [
            'label' => 'Price Amount',
            'type' => Controls_Manager::TEXT,
            'default' => '99.00',
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('show_asterisk', [
            'label' => 'Show Asterisk (*)',
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('frequency', [
            'label' => 'Frequency',
            'type' => Controls_Manager::TEXT,
            'default' => '/mo',
            'dynamic' => ['active' => true],
        ]);

        $this->end_controls_section();

        // --- BADGE ---
        $this->start_controls_section('section_badge', ['label' => 'Trust Badge']);

        $this->add_control('badge_text', [
            'label' => 'Badge Text',
            'type' => Controls_Manager::TEXT,
            'default' => '30-day money Back guarantee',
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('icon_source', [
            'label' => 'Icon Source',
            'type' => Controls_Manager::SELECT,
            'default' => 'library',
            'options' => [
                'library' => 'Icon Library',
                'custom'  => 'Custom SVG',
            ],
        ]);

        $this->add_control('badge_icon', [
            'label' => 'Select Icon',
            'type' => Controls_Manager::ICONS,
            'default' => ['value' => 'fas fa-shield-alt', 'library' => 'solid'],
            'condition' => ['icon_source' => 'library'],
        ]);

        $this->add_control('badge_svg', [
            'label' => 'SVG Code',
            'type' => Controls_Manager::TEXTAREA,
            'rows' => 5,
            'placeholder' => '<svg>...</svg>',
            'description' => 'Paste raw SVG code here.',
            'condition' => ['icon_source' => 'custom'],
        ]);

        $this->end_controls_section();

        // --- STYLE ---
        $this->start_controls_section('section_style', ['label' => 'Typography', 'tab' => Controls_Manager::TAB_STYLE]);

        $this->add_control('base_color', [
            'label' => 'Price Color',
            'type' => Controls_Manager::COLOR,
            'default' => '#1e2b5e', // Dark Blue from ref
            'selectors' => ['{{WRAPPER}} .cora-price-wrapper' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'price_typo',
            'label' => 'Amount Font',
            'selector' => '{{WRAPPER}} .cora-amount',
        ]);

        $this->add_control('badge_style_heading', ['label' => 'Badge Style', 'type' => Controls_Manager::HEADING, 'separator' => 'before']);

        $this->add_control('badge_text_color', [
            'label' => 'Badge Text Color',
            'type' => Controls_Manager::COLOR,
            'default' => '#1e2b5e',
            'selectors' => ['{{WRAPPER}} .cora-badge-text' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('badge_icon_color', [
            'label' => 'Badge Icon Color',
            'type' => Controls_Manager::COLOR,
            'default' => '#10B981', // Green
            'selectors' => [
                '{{WRAPPER}} .cora-badge-icon i' => 'color: {{VALUE}};',
                '{{WRAPPER}} .cora-badge-icon svg' => 'fill: {{VALUE}}; stroke: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'badge_typo',
            'label' => 'Badge Font',
            'selector' => '{{WRAPPER}} .cora-badge-text',
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
                align-items: flex-start;
                gap: 12px;
            }

            /* --- Price Row Container --- */
            .cora-root-<?php echo $id; ?> .cora-price-wrapper {
                display: flex;
                align-items: flex-start; /* Aligns top of elements */
                line-height: 1;
                font-family: "Fredoka", sans-serif;
                color: #1e2b5e;
                position: relative;
            }

            /* Currency Symbol */
            .cora-root-<?php echo $id; ?> .cora-currency {
                font-size: 24px; /* Scaled relative to Amount */
                font-weight: 400;
                margin-right: 4px;
                margin-top: 0.1em; /* Slight nudge down */
            }

            /* Main Amount */
            .cora-root-<?php echo $id; ?> .cora-amount {
                font-size: 84px; /* Matches the large reference */
                font-weight: 700;
                letter-spacing: -0.02em;
                line-height: 0.9;
            }

            /* Asterisk */
            .cora-root-<?php echo $id; ?> .cora-asterisk {
                font-size: 0.5em;
                font-weight: 700;
                margin-left: 4px;
                margin-top: 0.1em;
            }

            /* Frequency (/mo) */
            .cora-root-<?php echo $id; ?> .cora-freq {
                align-self: flex-end; /* Pushes to bottom */
                font-size: 24px;
                font-weight: 600;
                margin-left: 4px;
                margin-bottom: 0.15em; /* Baseline alignment tweak */
                opacity: 0.9;
            }

            /* --- Trust Badge --- */
            .cora-root-<?php echo $id; ?> .cora-badge {
                display: flex;
                align-items: center;
                gap: 10px;
                padding-left: 6px; /* Align visually with the price weight */
            }

            .cora-root-<?php echo $id; ?> .cora-badge-icon {
                font-size: 20px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #10B981;
            }

            .cora-root-<?php echo $id; ?> .cora-badge-icon svg {
                width: 1em;
                height: 1em;
                fill: currentColor;
            }

            .cora-root-<?php echo $id; ?> .cora-badge-text {
                margin: 0;
                font-family: "Fredoka", sans-serif;
                font-size: 16px;
                font-weight: 500;
                color: #1e2b5e;
            }
        </style>

        <div class="cora-unit-container cora-root-<?php echo $id; ?>">
            
            <div class="cora-price-wrapper">
                <span class="cora-currency"><?php echo esc_html($settings['currency']); ?></span>
                <span class="cora-amount"><?php echo esc_html($settings['price']); ?></span>
                <?php if ( 'yes' === $settings['show_asterisk'] ) : ?>
                    <span class="cora-asterisk">*</span>
                <?php endif; ?>
                <span class="cora-freq"><?php echo esc_html($settings['frequency']); ?></span>
            </div>

            <div class="cora-badge">
                <div class="cora-badge-icon">
                    <?php if ( 'custom' === $settings['icon_source'] ) : ?>
                        <?php echo $settings['badge_svg']; // SVG Output ?>
                    <?php else : ?>
                        <?php Icons_Manager::render_icon( $settings['badge_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                    <?php endif; ?>
                </div>
                <p class="cora-badge-text"><?php echo esc_html($settings['badge_text']); ?></p>
            </div>

        </div>
        <?php
    }
}