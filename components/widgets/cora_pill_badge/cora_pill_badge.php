<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Icons_Manager;

if (!defined('ABSPATH')) exit;

class Cora_Pill_Badge extends Base_Widget
{
    public function get_name() { return 'cora_pill_badge'; }
    public function get_title() { return __('Cora Pill Badge', 'cora-builder'); }
    public function get_icon() { return 'eicon-button'; }

    protected function register_controls()
    {
        // --- TAB 1: CONTENT ---
        $this->start_controls_section('section_content', ['label' => 'Badge Content']);

        $this->add_control('text', [
            'label' => 'Label',
            'type' => Controls_Manager::TEXT,
            'default' => 'Solutions',
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('link', [
            'label' => 'Link',
            'type' => Controls_Manager::URL,
            'dynamic' => ['active' => true],
            'placeholder' => 'https://your-link.com',
        ]);

        $this->add_control('icon_type', [
            'label' => 'Icon Type',
            'type' => Controls_Manager::SELECT,
            'default' => 'icon',
            'options' => [
                'none' => 'None',
                'icon' => 'Icon Library',
                'custom' => 'SVG Code',
            ],
        ]);

        $this->add_control('selected_icon', [
            'label' => 'Icon',
            'type' => Controls_Manager::ICONS,
            'default' => ['value' => 'fas fa-check', 'library' => 'fa-solid'],
            'condition' => ['icon_type' => 'icon'],
        ]);

        $this->add_control('icon_svg', [
            'label' => 'SVG Code',
            'type' => Controls_Manager::TEXTAREA,
            'rows' => 5,
            'placeholder' => '<svg>...</svg>',
            'condition' => ['icon_type' => 'custom'],
        ]);

        $this->add_control('icon_position', [
            'label' => 'Icon Position',
            'type' => Controls_Manager::SELECT,
            'default' => 'before',
            'options' => [
                'before' => 'Before Text',
                'after' => 'After Text',
            ],
            'condition' => ['icon_type!' => 'none'],
        ]);

        $this->end_controls_section();

        // --- TAB 2: STYLE ---
        
        // 1. Badge Container
        $this->start_controls_section('style_badge', ['label' => 'Badge Style', 'tab' => Controls_Manager::TAB_STYLE]);

        $this->add_responsive_control('align', [
            'label' => 'Alignment',
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'left' => [ 'title' => 'Left', 'icon' => 'eicon-text-align-left' ],
                'center' => [ 'title' => 'Center', 'icon' => 'eicon-text-align-center' ],
                'right' => [ 'title' => 'Right', 'icon' => 'eicon-text-align-right' ],
            ],
            'default' => 'center',
            'selectors' => [ '{{WRAPPER}} .cora-pill-wrapper' => 'text-align: {{VALUE}};' ],
        ]);

        $this->add_control('pill_bg', [
            'label' => 'Background Color',
            'type' => Controls_Manager::COLOR,
            'default' => '#ffffff',
            'selectors' => [ '{{WRAPPER}} .cora-pill' => 'background-color: {{VALUE}};' ],
        ]);

        $this->add_control('pill_bg_hover', [
            'label' => 'Hover Background',
            'type' => Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .cora-pill:hover' => 'background-color: {{VALUE}};' ],
        ]);

        $this->add_responsive_control('pill_padding', [
            'label' => 'Padding',
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', 'em' ],
            'default' => [ 'top' => 8, 'right' => 20, 'bottom' => 8, 'left' => 20, 'unit' => 'px' ],
            'selectors' => [ '{{WRAPPER}} .cora-pill' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
        ]);

        $this->add_group_control(Group_Control_Border::get_type(), [
            'name' => 'pill_border',
            'selector' => '{{WRAPPER}} .cora-pill',
            'defaults' => [ 'width' => 1, 'color' => '#EAEAEA' ], // Default from design
        ]);

        $this->add_responsive_control('pill_radius', [
            'label' => 'Border Radius',
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%' ],
            'default' => [ 'top' => 50, 'right' => 50, 'bottom' => 50, 'left' => 50, 'unit' => 'px' ],
            'selectors' => [ '{{WRAPPER}} .cora-pill' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
        ]);

        $this->add_group_control(Group_Control_Box_Shadow::get_type(), [
            'name' => 'pill_shadow',
            'selector' => '{{WRAPPER}} .cora-pill',
        ]);

        $this->add_control('hover_animation', [
            'label' => 'Hover Animation',
            'type' => Controls_Manager::SELECT,
            'default' => 'translate',
            'options' => [
                'none' => 'None',
                'translate' => 'Lift Up',
                'scale' => 'Scale Up',
            ],
        ]);

        $this->end_controls_section();

        // 2. Icon Style
        $this->start_controls_section('style_icon', ['label' => 'Icon', 'tab' => Controls_Manager::TAB_STYLE, 'condition' => ['icon_type!' => 'none']]);

        $this->add_control('icon_color', [
            'label' => 'Color',
            'type' => Controls_Manager::COLOR,
            'default' => '#101828',
            'selectors' => [ '{{WRAPPER}} .cora-pill-icon' => 'color: {{VALUE}}; fill: {{VALUE}};' ],
        ]);

        $this->add_responsive_control('icon_size', [
            'label' => 'Size',
            'type' => Controls_Manager::SLIDER,
            'range' => [ 'px' => [ 'min' => 10, 'max' => 50 ] ],
            'default' => [ 'size' => 14 ],
            'selectors' => [ '{{WRAPPER}} .cora-pill-icon i' => 'font-size: {{SIZE}}px;', '{{WRAPPER}} .cora-pill-icon svg' => 'width: {{SIZE}}px; height: {{SIZE}}px;' ],
        ]);

        $this->add_responsive_control('icon_gap', [
            'label' => 'Gap',
            'type' => Controls_Manager::SLIDER,
            'default' => [ 'size' => 8 ],
            'selectors' => [ '{{WRAPPER}} .cora-pill' => 'gap: {{SIZE}}px;' ],
        ]);

        $this->end_controls_section();

        // 3. Typography
        $this->start_controls_section('style_text', ['label' => 'Label', 'tab' => Controls_Manager::TAB_STYLE]);

        $this->add_control('text_color', [
            'label' => 'Color',
            'type' => Controls_Manager::COLOR,
            'default' => '#101828',
            'selectors' => [ '{{WRAPPER}} .cora-pill-text' => 'color: {{VALUE}};' ],
        ]);

        $this->add_control('text_color_hover', [
            'label' => 'Hover Color',
            'type' => Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .cora-pill:hover .cora-pill-text' => 'color: {{VALUE}};' ],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'text_typo',
            'selector' => '{{WRAPPER}} .cora-pill-text',
        ]);

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        
        $wrapper_tag = 'div';
        $wrapper_attrs = 'class="cora-pill"';
        
        // Link Logic
        if ( ! empty( $settings['link']['url'] ) ) {
            $wrapper_tag = 'a';
            $this->add_link_attributes( 'badge_link', $settings['link'] );
            $wrapper_attrs .= ' ' . $this->get_render_attribute_string( 'badge_link' );
        }

        // Hover Effect Class
        $hover_class = '';
        if ($settings['hover_animation'] === 'translate') $hover_class = 'h-lift';
        if ($settings['hover_animation'] === 'scale') $hover_class = 'h-scale';

        ?>
        <style>
            .cora-pill-wrapper { width: 100%; }
            
            .cora-pill {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                text-decoration: none;
                transition: all 0.3s ease;
                /* Default sizing handled by controls */
                border: 1px solid transparent;
            }

            /* --- Hover Animations --- */
            .cora-pill.h-lift:hover { transform: translateY(-3px); }
            .cora-pill.h-scale:hover { transform: scale(1.05); }

            /* --- Icon --- */
            .cora-pill-icon {
                display: flex; align-items: center; justify-content: center;
                line-height: 1; flex-shrink: 0;
            }
            
            /* --- Text --- */
            .cora-pill-text {
                line-height: 1;
                font-size: 14px; font-weight: 600; /* Defaults */
            }
        </style>

        <div class="cora-unit-container cora-pill-wrapper">
            <<?php echo $wrapper_tag; ?> <?php echo $wrapper_attrs; ?> class="cora-pill <?php echo $hover_class; ?>">
                
                <?php 
                // RENDER ICON BEFORE
                if ($settings['icon_position'] === 'before' && $settings['icon_type'] !== 'none') : 
                ?>
                    <div class="cora-pill-icon">
                        <?php if ('custom' === $settings['icon_type']) : ?>
                            <?php echo $settings['icon_svg']; ?>
                        <?php else : ?>
                            <?php Icons_Manager::render_icon($settings['selected_icon'], ['aria-hidden' => 'true']); ?>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <span class="cora-pill-text"><?php echo esc_html($settings['text']); ?></span>

                <?php 
                // RENDER ICON AFTER
                if ($settings['icon_position'] === 'after' && $settings['icon_type'] !== 'none') : 
                ?>
                    <div class="cora-pill-icon">
                        <?php if ('custom' === $settings['icon_type']) : ?>
                            <?php echo $settings['icon_svg']; ?>
                        <?php else : ?>
                            <?php Icons_Manager::render_icon($settings['selected_icon'], ['aria-hidden' => 'true']); ?>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

            </<?php echo $wrapper_tag; ?>>
        </div>
        <?php
    }
}