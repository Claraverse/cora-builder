<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Icons_Manager;

if (!defined('ABSPATH')) exit;

class Cora_Home_Feature_Card extends Base_Widget
{
    public function get_name() { return 'cora_home_feature_card'; }
    public function get_title() { return __('Cora Home Feature Card', 'cora-builder'); }
    public function get_icon() { return 'eicon-info-box'; }

    protected function register_controls()
    {
        // --- TAB 1: CONTENT ---
        $this->start_controls_section('section_content', ['label' => __('Content', 'cora-builder')]);

        $this->add_control('icon_source', [
            'label' => 'Icon Source',
            'type' => Controls_Manager::SELECT,
            'default' => 'library',
            'options' => [ 'library' => 'Icon Library', 'custom'  => 'Paste SVG Code' ],
        ]);

        $this->add_control('icon', [
            'label' => 'Icon',
            'type' => Controls_Manager::ICONS,
            'default' => ['value' => 'fas fa-code', 'library' => 'solid'],
            'condition' => ['icon_source' => 'library'],
        ]);

        $this->add_control('custom_svg', [
            'label' => 'SVG Code',
            'type' => Controls_Manager::TEXTAREA,
            'rows' => 10,
            'placeholder' => '<svg...>...</svg>',
            'condition' => ['icon_source' => 'custom'],
        ]);

        $this->add_control('title', [ 
            'label' => 'Title', 
            'type' => Controls_Manager::TEXT, 
            'default' => 'Develop', 
            'dynamic' => ['active' => true],
            'separator' => 'before',
        ]);

        $this->add_control('desc', [ 
            'label' => 'Description', 
            'type' => Controls_Manager::TEXTAREA, 
            'default' => 'Build high-performance websites, funnels, and systems tailored for growth.', 
            'dynamic' => ['active' => true] 
        ]);

        $this->add_control('link', [ 'label' => 'Link', 'type' => Controls_Manager::URL, 'dynamic' => ['active' => true] ]);

        $this->end_controls_section();

        // --- TAB 2: STYLE ---
        
        // 1. Layout & Container
        $this->start_controls_section('style_layout', [ 'label' => 'Layout & Container', 'tab' => Controls_Manager::TAB_STYLE ]);
        
        $this->add_control('card_bg', [
            'label' => 'Card Background',
            'type' => Controls_Manager::COLOR,
            'default' => '#E9F8EE', /* The Light Mint Green */
            'selectors' => [ '{{WRAPPER}} .cora-feature-card' => 'background-color: {{VALUE}};' ],
        ]);

        $this->add_group_control(Group_Control_Box_Shadow::get_type(), [
            'name' => 'card_shadow',
            'selector' => '{{WRAPPER}} .cora-feature-card',
        ]);

        $this->add_responsive_control('card_padding', [
            'label' => 'Padding',
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', 'em' ],
            'default' => [ 'top' => 32, 'right' => 32, 'bottom' => 32, 'left' => 32, 'unit' => 'px' ],
            'selectors' => [ '{{WRAPPER}} .cora-feature-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
        ]);
        
        $this->add_responsive_control('card_radius', [
            'label' => 'Border Radius',
            'type' => Controls_Manager::DIMENSIONS,
            'default' => [ 'top' => 24, 'right' => 24, 'bottom' => 24, 'left' => 24, 'unit' => 'px' ],
            'selectors' => [ '{{WRAPPER}} .cora-feature-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
        ]);

        $this->end_controls_section();

        // 2. Icon Box Style
        $this->start_controls_section('style_icon', [ 'label' => 'Icon Style', 'tab' => Controls_Manager::TAB_STYLE ]);
        
        $this->add_control('icon_box_bg', [
            'label' => 'Box Background',
            'type' => Controls_Manager::COLOR,
            'default' => 'transparent', /* Transparent to match the image */
            'selectors' => [ '{{WRAPPER}} .feature-icon' => 'background-color: {{VALUE}};' ],
        ]);

        $this->add_control('icon_color', [
            'label' => 'Icon Color',
            'type' => Controls_Manager::COLOR,
            'default' => '#103E28', /* Dark Forest Green */
            'selectors' => [ '{{WRAPPER}} .feature-icon' => 'color: {{VALUE}}; fill: {{VALUE}}; stroke: {{VALUE}};' ],
        ]);

        $this->add_responsive_control('icon_size', [
            'label' => 'Icon Size',
            'type' => Controls_Manager::SLIDER,
            'range' => [ 'px' => [ 'min' => 20, 'max' => 100 ] ],
            'default' => [ 'size' => 54 ], /* Large Icon */
            'selectors' => [ '{{WRAPPER}} .feature-icon i' => 'font-size: {{SIZE}}px;', '{{WRAPPER}} .feature-icon svg' => 'width: {{SIZE}}px; height: {{SIZE}}px;' ],
        ]);

        // Gap between Icon and Text
        $this->add_responsive_control('icon_gap', [
            'label' => 'Gap to Text',
            'type' => Controls_Manager::SLIDER,
            'default' => [ 'size' => 24 ],
            'selectors' => [ '{{WRAPPER}} .cora-feature-card' => 'gap: {{SIZE}}px;' ],
        ]);

        $this->end_controls_section();

        // 3. Typography
        $this->start_controls_section('style_content', [ 'label' => 'Typography', 'tab' => Controls_Manager::TAB_STYLE ]);
        
        $this->add_control('title_color', [
            'label' => 'Title Color',
            'type' => Controls_Manager::COLOR,
            'default' => '#103E28', /* Matching Dark Green */
            'selectors' => [ '{{WRAPPER}} .feature-title' => 'color: {{VALUE}};' ],
        ]);
        
        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'title_typo',
            'selector' => '{{WRAPPER}} .feature-title',
            'fields_options' => [
                'typography' => [ 'default' => 'custom' ],
                'font_weight' => [ 'default' => '800' ], // Extra Bold
                'font_size' => [ 'default' => [ 'size' => 26, 'unit' => 'px' ] ],
            ],
        ]);

        $this->add_control('desc_color', [
            'label' => 'Description Color',
            'type' => Controls_Manager::COLOR,
            'default' => '#4D6458', /* Muted Green-Grey */
            'selectors' => [ '{{WRAPPER}} .feature-desc' => 'color: {{VALUE}};' ],
            'separator' => 'before',
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'desc_typo',
            'selector' => '{{WRAPPER}} .feature-desc',
            'fields_options' => [
                'font_size' => [ 'default' => [ 'size' => 16, 'unit' => 'px' ] ],
                'line_height' => [ 'default' => [ 'size' => 1.5, 'unit' => 'em' ] ],
            ],
        ]);

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        
        $wrapper_tag = 'div';
        $wrapper_attrs = 'class="cora-unit-container cora-feature-card"';
        
        if ( ! empty( $settings['link']['url'] ) ) {
            $wrapper_tag = 'a';
            $this->add_link_attributes( 'card_link', $settings['link'] );
            $wrapper_attrs .= ' ' . $this->get_render_attribute_string( 'card_link' );
        }
        ?>
        <style>
            .cora-feature-card {
                display: flex;
                flex-direction: row; /* Force Row for Desktop */
                align-items: flex-start;
                /* Gap controlled by JS */
                text-decoration: none;
                transition: transform 0.3s ease, box-shadow 0.3s ease;
                height: 100%;
                /* BG, Padding, Radius set by Controls */
            }

            .cora-feature-card:hover {
                transform: translateY(-4px);
            }

            /* --- Icon Container --- */
            .feature-icon {
                flex-shrink: 0;
                display: flex;
                align-items: flex-start; /* Align icon to top of text */
                justify-content: center;
                line-height: 1;
            }

            /* --- Content Body --- */
            .feature-body {
                display: flex;
                flex-direction: column;
                gap: 8px;
                flex: 1;
            }

            .feature-title {
                margin: 0 0 6px 0 !important;
                line-height: 1.1;
                /* Typography set by Controls */
            }

            .feature-desc {
                margin: 0 !important;
            }

            /* --- Mobile Stack --- */
            @media (max-width: 767px) {
                .cora-feature-card {
                    flex-direction: column;
                    gap: 20px;
                }
            }
        </style>

        <<?php echo $wrapper_tag; ?> <?php echo $wrapper_attrs; ?>>
            
            <div class="feature-icon">
                <?php if ( 'custom' === $settings['icon_source'] && ! empty( $settings['custom_svg'] ) ) : ?>
                    <?php echo $settings['custom_svg']; ?>
                <?php else : ?>
                    <?php Icons_Manager::render_icon($settings['icon'], ['aria-hidden' => 'true']); ?>
                <?php endif; ?>
            </div>

            <div class="feature-body">
                <h3 class="feature-title"><?php echo esc_html($settings['title']); ?></h3>
                <div class="feature-desc"><?php echo esc_html($settings['desc']); ?></div>
            </div>

        </<?php echo $wrapper_tag; ?>>
        <?php
    }
}