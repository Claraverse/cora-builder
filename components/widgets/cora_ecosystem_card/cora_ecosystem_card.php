<?php
namespace Cora_Builder\Components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Ecosystem_Card extends Base_Widget {

    public function get_name() { return 'cora_ecosystem_card'; }
    public function get_title() { return __( 'Cora – Ecosystem Card', 'cora-builder' ); }
    public function get_icon() { return 'eicon-product-box'; }

    public function get_style_depends() {
        wp_register_style('cora-fonts', 'https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&display=swap', [], null);
        return ['cora-fonts'];
    }

    protected function register_controls() {
        
        // --- CONTENT TAB ---
        $this->start_controls_section('section_content', [ 'label' => __( 'Card Content', 'cora-builder' ) ]);
        
        $this->add_control('icon_source', [
            'label' => 'Icon Source',
            'type' => Controls_Manager::SELECT,
            'default' => 'library',
            'options' => [ 'library' => 'Icon Library', 'custom'  => 'Custom SVG' ],
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
            'description' => 'Paste raw SVG code here.',
            'condition' => [ 'icon_source' => 'custom' ],
        ]);

        $this->add_control('title', [
            'label' => 'Title',
            'type' => Controls_Manager::TEXT,
            'default' => 'e-Commerce',
            'label_block' => true,
            'dynamic' => ['active' => true]
        ]);

        $this->add_control('desc', [
            'label' => 'Description',
            'type' => Controls_Manager::TEXTAREA,
            'default' => 'We help you build your e-commerce stores and digital platforms.',
            'dynamic' => ['active' => true]
        ]);

        $this->add_control('link', [
            'label' => 'Link',
            'type' => Controls_Manager::URL,
            'placeholder' => 'https://your-link.com',
            'dynamic' => ['active' => true],
        ]);

        $this->end_controls_section();

        // --- STYLE: CONTAINER ---
        $this->start_controls_section('section_style_container', [ 'label' => __( 'Container', 'cora-builder' ), 'tab' => Controls_Manager::TAB_STYLE ]);

        $this->add_control('hover_effect', [
            'label' => 'Hover Animation',
            'type' => Controls_Manager::SELECT,
            'default' => 'none',
            'options' => [
                'none' => 'None',
                'shadow' => 'Subtle Shadow',
                'lift' => 'Lift Up',
            ],
        ]);

        $this->add_responsive_control('text_align', [
            'label' => 'Alignment',
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'left' => [ 'title' => 'Left', 'icon' => 'eicon-text-align-left' ],
                'center' => [ 'title' => 'Center', 'icon' => 'eicon-text-align-center' ],
                'right' => [ 'title' => 'Right', 'icon' => 'eicon-text-align-right' ],
            ],
            'default' => 'left',
            'selectors' => [
                '{{WRAPPER}} .cora-card' => 'text-align: {{VALUE}};',
                '{{WRAPPER}} .cora-icon-box' => 'margin-left: {{VALUE} == "center" ? "auto" : ({{VALUE}} == "right" ? "auto" : "0")}; margin-right: {{VALUE} == "center" ? "auto" : "0"};',
            ],
        ]);

        $this->add_responsive_control('padding', [
            'label' => 'Padding',
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'default' => ['top' => 24, 'right' => 24, 'bottom' => 24, 'left' => 24, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .cora-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_control('bg_color', [
            'label' => 'Background Color',
            'type' => Controls_Manager::COLOR,
            'default' => '#FFFFFF', 
            'selectors' => ['{{WRAPPER}} .cora-card' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Border::get_type(), [
            'name' => 'border',
            'selector' => '{{WRAPPER}} .cora-card',
        ]);

        $this->add_control('border_radius', [
            'label' => 'Border Radius',
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'default' => ['top' => 16, 'right' => 16, 'bottom' => 16, 'left' => 16, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .cora-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_group_control(Group_Control_Box_Shadow::get_type(), [
            'name' => 'box_shadow',
            'selector' => '{{WRAPPER}} .cora-card',
            'fields_options' => [
                'box_shadow' => [
                    'default' => [
                        'horizontal' => 0, 'vertical' => 4, 'blur' => 24, 'spread' => 0,
                        'color' => 'rgba(0,0,0,0.04)'
                    ]
                ]
            ]
        ]);

        $this->end_controls_section();

        // --- STYLE: ICON ---
        $this->start_controls_section('section_style_icon', [ 'label' => __( 'Icon Box', 'cora-builder' ), 'tab' => Controls_Manager::TAB_STYLE ]);

        $this->add_responsive_control('icon_box_size', [
            'label' => 'Box Size',
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'em' ],
            'range' => [ 'px' => [ 'min' => 30, 'max' => 100 ] ],
            'default' => [ 'unit' => 'px', 'size' => 64 ],
            'selectors' => [
                '{{WRAPPER}} .cora-icon-box' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; min-width: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('icon_size', [
            'label' => 'Icon Size',
            'type' => Controls_Manager::SLIDER,
            'default' => [ 'unit' => 'px', 'size' => 24 ],
            'selectors' => [
                '{{WRAPPER}} .cora-icon-box' => 'font-size: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .cora-icon-box svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('icon_color', [
            'label' => 'Icon Color',
            'type' => Controls_Manager::COLOR,
            'default' => '#7C3AED',
            'selectors' => [
                '{{WRAPPER}} .cora-icon-box i' => 'color: {{VALUE}};',
                '{{WRAPPER}} .cora-icon-box svg' => 'fill: {{VALUE}}; stroke: {{VALUE}};', 
            ],
        ]);

        $this->add_control('icon_bg_color', [
            'label' => 'Box Background',
            'type' => Controls_Manager::COLOR,
            'default' => '#F3F4FD',
            'selectors' => ['{{WRAPPER}} .cora-icon-box' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('icon_radius', [
            'label' => 'Border Radius',
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'default' => ['top' => 16, 'right' => 16, 'bottom' => 16, 'left' => 16, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .cora-icon-box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('icon_bottom_space', [
            'label' => 'Spacing Below Icon',
            'type' => Controls_Manager::SLIDER,
            'default' => [ 'size' => 20, 'unit' => 'px' ],
            'selectors' => [ '{{WRAPPER}} .cora-icon-box' => 'margin-bottom: {{SIZE}}{{UNIT}};' ],
        ]);

        $this->end_controls_section();

        // --- STYLE: TYPOGRAPHY ---
        $this->start_controls_section('section_style_typo', [ 'label' => __( 'Typography', 'cora-builder' ), 'tab' => Controls_Manager::TAB_STYLE ]);

        // Title
        $this->add_control('title_heading', [ 'type' => Controls_Manager::HEADING, 'label' => 'Title' ]);
        
        $this->add_control('title_color', [
            'label' => 'Color',
            'type' => Controls_Manager::COLOR,
            'default' => '#0F172A',
            'selectors' => ['{{WRAPPER}} .cora-title' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'title_typo',
            'selector' => '{{WRAPPER}} .cora-title',
            'fields_options' => [
                'typography' => [ 'default' => 'custom' ],
                'font_family' => [ 'default' => 'Fredoka' ],
                'font_weight' => [ 'default' => '600' ],
                'font_size'   => [ 'default' => [ 'size' => 28, 'unit' => 'px' ] ],
                'line_height' => [ 'default' => [ 'size' => 1.2, 'unit' => 'em' ] ],
            ],
        ]);

        $this->add_responsive_control('title_space', [
            'label' => 'Spacing Below Title',
            'type' => Controls_Manager::SLIDER,
            'default' => [ 'size' => 12, 'unit' => 'px' ],
            'selectors' => [ '{{WRAPPER}} .cora-title' => 'margin-bottom: {{SIZE}}{{UNIT}};' ],
        ]);

        // Description
        $this->add_control('desc_heading', [ 'type' => Controls_Manager::HEADING, 'label' => 'Description', 'separator' => 'before' ]);

        $this->add_control('desc_color', [
            'label' => 'Color',
            'type' => Controls_Manager::COLOR,
            'default' => '#475569',
            'selectors' => ['{{WRAPPER}} .cora-desc' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'desc_typo',
            'selector' => '{{WRAPPER}} .cora-desc',
            'fields_options' => [
                'typography' => [ 'default' => 'custom' ],
                'font_family' => [ 'default' => 'Fredoka' ],
                'font_weight' => [ 'default' => '400' ],
                'font_size'   => [ 'default' => [ 'size' => 18, 'unit' => 'px' ] ],
                'line_height' => [ 'default' => [ 'size' => 1.5, 'unit' => 'em' ] ],
            ],
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $id = $this->get_id();

        // Handle Link
        $tag = 'div';
        $link_attrs = '';
        if ( ! empty( $settings['link']['url'] ) ) {
            $tag = 'a';
            $link_attrs = 'href="' . esc_url($settings['link']['url']) . '"';
            if ( $settings['link']['is_external'] ) $link_attrs .= ' target="_blank"';
            if ( $settings['link']['nofollow'] ) $link_attrs .= ' rel="nofollow"';
        }
        
        // Handle Animation Class
        $hover_class = 'cora-hover-' . $settings['hover_effect'];
        ?>

        <style>
            /* Base Card Structure */
            .cora-eco-<?php echo $id; ?> {
                display: flex;
                flex-direction: column;
                box-sizing: border-box;
                text-decoration: none;
                transition: transform 0.3s ease, box-shadow 0.3s ease;
                height: 100%; /* Ensures all cards in a row match height */
                width: 100%;
                overflow: hidden; /* Prevents overflow */
            }

            /* Hover Effects based on selection */
            .cora-eco-<?php echo $id; ?>.cora-hover-lift:hover {
                transform: translateY(-5px);
            }
            .cora-eco-<?php echo $id; ?>.cora-hover-shadow:hover {
                box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important;
            }

            /* Icon Box */
            .cora-eco-<?php echo $id; ?> .cora-icon-box {
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0; /* Ensures icon never shrinks/smashes on mobile */
            }

            /* Content Typography */
            .cora-eco-<?php echo $id; ?> .cora-title {
                margin: 0;
                width: 100%;
            }

            .cora-eco-<?php echo $id; ?> .cora-desc {
                margin: 0;
                width: 100%;
                word-wrap: break-word; /* Prevents long words breaking layout */
                max-width: 100%;
            }
        </style>

        <<?php echo $tag; ?> class="cora-card cora-eco-<?php echo $id; ?> <?php echo esc_attr($hover_class); ?>" <?php echo $link_attrs; ?>>
            
            <div class="cora-icon-box">
                <?php if ( 'custom' === $settings['icon_source'] ) : ?>
                    <?php echo $settings['custom_svg']; ?>
                <?php else : ?>
                    <?php Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] ); ?>
                <?php endif; ?>
            </div>
            
            <h3 class="cora-title"><?php echo esc_html($settings['title']); ?></h3>
            <p class="cora-desc"><?php echo esc_html($settings['desc']); ?></p>

        </<?php echo $tag; ?>>
        <?php
    }
}