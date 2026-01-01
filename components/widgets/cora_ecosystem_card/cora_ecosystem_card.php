<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Ecosystem_Card extends Base_Widget {

    public function get_name() { return 'cora_ecosystem_card'; }
    public function get_title() { return __( 'Cora Ecosystem Card', 'cora-builder' ); }
    public function get_icon() { return 'eicon-card'; }

    // Load Fredoka Font Automatically
    public function get_style_depends() {
        wp_register_style('cora-google-fredoka', 'https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&display=swap', [], null);
        return ['cora-google-fredoka'];
    }

    protected function register_controls() {
        
        // --- CONTENT TAB ---
        $this->start_controls_section('section_content', [ 'label' => __( 'Card Content', 'cora-builder' ) ]);
        
        // Icon Source Switcher
        $this->add_control('icon_source', [
            'label' => 'Icon Source',
            'type' => Controls_Manager::SELECT,
            'default' => 'library',
            'options' => [
                'library' => 'Icon Library',
                'custom'  => 'Custom SVG',
            ],
        ]);

        $this->add_control('icon', [
            'label' => 'Icon',
            'type' => Controls_Manager::ICONS,
            'default' => [ 'value' => 'fas fa-shopping-cart', 'library' => 'solid' ],
            'condition' => [ 'icon_source' => 'library' ],
        ]);

        $this->add_control('custom_svg', [
            'label' => 'SVG Code',
            'type' => Controls_Manager::TEXTAREA,
            'rows' => 5,
            'placeholder' => '<svg>...</svg>',
            'description' => 'Paste raw SVG code here.',
            'condition' => [ 'icon_source' => 'custom' ],
        ]);

        $this->add_control('title', [
            'label' => 'Title',
            'type' => Controls_Manager::TEXT,
            'default' => 'e-Commerce',
            'dynamic' => ['active' => true]
        ]);

        $this->add_control('desc', [
            'label' => 'Description',
            'type' => Controls_Manager::TEXTAREA,
            'default' => 'We help you build your e-commerce stores and digital platforms.',
            'dynamic' => ['active' => true]
        ]);

        $this->end_controls_section();

        // --- STYLE TAB ---
        $this->start_controls_section('section_style', [ 'label' => __( 'Design', 'cora-builder' ), 'tab' => Controls_Manager::TAB_STYLE ]);

        // Icon Box Styling
        $this->add_control('icon_bg_color', [
            'label' => 'Icon Background',
            'type' => Controls_Manager::COLOR,
            'default' => '#F3F4FD',
            'selectors' => ['{{WRAPPER}} .cora-icon-box' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_control('icon_color', [
            'label' => 'Icon Color',
            'type' => Controls_Manager::COLOR,
            'default' => '#7C3AED',
            'selectors' => [
                '{{WRAPPER}} .cora-icon-box i' => 'color: {{VALUE}};',
                '{{WRAPPER}} .cora-icon-box svg' => 'fill: {{VALUE}}; stroke: {{VALUE}};', // Target both fill/stroke for SVG
            ],
        ]);

        $this->add_control('icon_size', [
            'label' => 'Icon Size',
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'em' ],
            'range' => [ 'px' => [ 'min' => 10, 'max' => 50 ] ],
            'default' => [ 'unit' => 'px', 'size' => 24 ],
            'selectors' => [
                '{{WRAPPER}} .cora-icon-box' => 'font-size: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .cora-icon-box svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
            ],
        ]);

        // Typography
        $this->add_control('title_color', [
            'label' => 'Title Color',
            'type' => Controls_Manager::COLOR,
            'default' => '#0F172A',
            'selectors' => ['{{WRAPPER}} .cora-title' => 'color: {{VALUE}};'],
            'separator' => 'before',
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'title_typo',
            'label' => 'Title Typography',
            'selector' => '{{WRAPPER}} .cora-title',
        ]);

        $this->add_control('desc_color', [
            'label' => 'Description Color',
            'type' => Controls_Manager::COLOR,
            'default' => '#475569',
            'selectors' => ['{{WRAPPER}} .cora-desc' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'desc_typo',
            'label' => 'Desc Typography',
            'selector' => '{{WRAPPER}} .cora-desc',
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $id = $this->get_id();
        ?>

        <style>
            .cora-eco-<?php echo $id; ?> {
                display: flex;
                flex-direction: column;
                align-items: flex-start;
                gap: 20px;
                width: 100%;
                text-align: left;
            }

            /* Icon Box: Fixed Size, Rounded, Centered Content */
            .cora-eco-<?php echo $id; ?> .cora-icon-box {
                width: 64px;
                height: 64px;
                border-radius: 16px;
                display: flex;
                align-items: center;
                justify-content: center;
                
                color: #7C3AED;
                transition: transform 0.3s ease;
                flex-shrink: 0;
                line-height: 1; /* Prevents line-height from offsetting icon */
            }

            /* Ensure SVG scales correctly inside the flex box */
            .cora-eco-<?php echo $id; ?> .cora-icon-box svg {
                width: 64px; /* Default size match */
                height: 64px;
                fill: currentColor; /* Inherits color control */
                display: block;
            }

             

            /* Typography Defaults (Fredoka Match) */
            .cora-eco-<?php echo $id; ?> .cora-title {
                margin: 0;
                font-family: "Fredoka", sans-serif;
                font-size: 24px;
                font-weight: 600;
                color: #0F172A;
                line-height: 1.2;
            }

            .cora-eco-<?php echo $id; ?> .cora-desc {
                margin: 0;
                font-family: "Fredoka", sans-serif;
                font-size: 16px;
                font-weight: 400;
                color: #475569;
                line-height: 1.5;
                max-width: 300px;
            }
        </style>

        <div class="cora-unit-container cora-eco-<?php echo $id; ?>">
            <div class="cora-icon-box">
                <?php if ( 'custom' === $settings['icon_source'] ) : ?>
                    <?php echo $settings['custom_svg']; // Output raw SVG ?>
                <?php else : ?>
                    <?php Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] ); ?>
                <?php endif; ?>
            </div>
            
            <h3 class="cora-title"><?php echo esc_html($settings['title']); ?></h3>
            <p class="cora-desc"><?php echo esc_html($settings['desc']); ?></p>
        </div>
        <?php
    }
}