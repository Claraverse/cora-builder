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
            'options' => [
                'library' => 'Icon Library',
                'custom'  => 'Paste SVG Code',
            ],
        ]);

        $this->add_control('icon', [
            'label' => 'Icon',
            'type' => Controls_Manager::ICONS,
            'default' => ['value' => 'fas fa-book-open', 'library' => 'solid'],
            'condition' => ['icon_source' => 'library'],
        ]);

        $this->add_control('custom_svg', [
            'label' => 'SVG Code',
            'type' => Controls_Manager::TEXTAREA,
            'rows' => 10,
            'placeholder' => '<svg viewBox="0 0 24 24">...</svg>',
            'condition' => ['icon_source' => 'custom'],
        ]);

        $this->add_control('title', [ 
            'label' => 'Title', 
            'type' => Controls_Manager::TEXT, 
            'default' => 'Online Learning', 
            'dynamic' => ['active' => true],
            'separator' => 'before',
        ]);

        $this->add_control('desc', [ 
            'label' => 'Description', 
            'type' => Controls_Manager::TEXTAREA, 
            'default' => 'Engage in interactive, real-time sessions led by industry experts.', 
            'dynamic' => ['active' => true] 
        ]);

        $this->add_control('link', [ 'label' => 'Link', 'type' => Controls_Manager::URL, 'dynamic' => ['active' => true] ]);

        $this->end_controls_section();

        // --- TAB 2: STYLE ---
        
        // 1. Layout & Container
        $this->start_controls_section('style_layout', [ 'label' => 'Layout & Container', 'tab' => Controls_Manager::TAB_STYLE ]);
        
        $this->add_responsive_control('mobile_layout', [
            'label' => 'Mobile Layout',
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'row' => [ 'title' => 'Horizontal', 'icon' => 'eicon-h-align-left' ],
                'column' => [ 'title' => 'Stacked', 'icon' => 'eicon-v-align-top' ],
            ],
            'default' => 'column',
            'selectors' => [
                '{{WRAPPER}} .cora-feature-card' => 'flex-direction: {{VALUE}};',
            ],
            'devices' => ['mobile'],
        ]);

        $this->add_control('card_bg', [
            'label' => 'Card Background',
            'type' => Controls_Manager::COLOR,
            'default' => '#ffffff',
            'selectors' => [ '{{WRAPPER}} .cora-feature-card' => 'background-color: {{VALUE}};' ],
        ]);

        $this->add_responsive_control('card_padding', [
            'label' => 'Padding',
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', 'em' ],
            'selectors' => [ '{{WRAPPER}} .cora-feature-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
        ]);

        $this->add_responsive_control('card_radius', [
            'label' => 'Border Radius',
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%' ],
            'selectors' => [ '{{WRAPPER}} .cora-feature-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
        ]);

        $this->add_group_control(Group_Control_Box_Shadow::get_type(), [
            'name' => 'card_shadow',
            'selector' => '{{WRAPPER}} .cora-feature-card',
        ]);

        $this->end_controls_section();

        // 2. Icon Box Style
        $this->start_controls_section('style_icon', [ 'label' => 'Icon Box', 'tab' => Controls_Manager::TAB_STYLE ]);
        
        $this->add_control('icon_box_bg', [
            'label' => 'Box Background',
            'type' => Controls_Manager::COLOR,
            'default' => '#E2E4E9', // Grey from screenshot
            'selectors' => [ '{{WRAPPER}} .feature-icon' => 'background-color: {{VALUE}};' ],
        ]);

        $this->add_control('icon_color', [
            'label' => 'Icon Color',
            'type' => Controls_Manager::COLOR,
            'default' => '#0f172a',
            'selectors' => [ '{{WRAPPER}} .feature-icon' => 'color: {{VALUE}}; fill: {{VALUE}};' ],
        ]);

        $this->add_responsive_control('icon_box_size', [
            'label' => 'Box Size',
            'type' => Controls_Manager::SLIDER,
            'range' => [ 'px' => [ 'min' => 30, 'max' => 100 ] ],
            'default' => [ 'size' => 64 ],
            'selectors' => [ '{{WRAPPER}} .feature-icon' => 'width: {{SIZE}}px; height: {{SIZE}}px;' ],
        ]);

        $this->add_responsive_control('icon_size', [
            'label' => 'Icon Size',
            'type' => Controls_Manager::SLIDER,
            'range' => [ 'px' => [ 'min' => 10, 'max' => 50 ] ],
            'default' => [ 'size' => 24 ],
            'selectors' => [ '{{WRAPPER}} .feature-icon i' => 'font-size: {{SIZE}}px;', '{{WRAPPER}} .feature-icon svg' => 'width: {{SIZE}}px; height: {{SIZE}}px;' ],
        ]);

        $this->add_responsive_control('icon_box_radius', [
            'label' => 'Border Radius',
            'type' => Controls_Manager::DIMENSIONS,
            'selectors' => [ '{{WRAPPER}} .feature-icon' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;' ],
        ]);

        $this->end_controls_section();

        // 3. Typography
        $this->start_controls_section('style_content', [ 'label' => 'Content Style', 'tab' => Controls_Manager::TAB_STYLE ]);
        
        $this->add_control('title_color', [
            'label' => 'Title Color',
            'type' => Controls_Manager::COLOR,
            'default' => '#0f172a',
            'selectors' => [ '{{WRAPPER}} .feature-title' => 'color: {{VALUE}};' ],
        ]);
        
        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'title_typo',
            'selector' => '{{WRAPPER}} .feature-title',
        ]);

        $this->add_control('desc_color', [
            'label' => 'Description Color',
            'type' => Controls_Manager::COLOR,
            'default' => '#64748b',
            'selectors' => [ '{{WRAPPER}} .feature-desc' => 'color: {{VALUE}};' ],
            'separator' => 'before',
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'desc_typo',
            'selector' => '{{WRAPPER}} .feature-desc',
        ]);

        $this->end_controls_section();

        // 4. Structural Layout (Hidden Authority)
        $this->start_controls_section('layout_reset', [ 'label' => 'Structural Layout', 'tab' => Controls_Manager::TAB_STYLE ]);
        $this->add_control('css_reset', [
            'type' => Controls_Manager::HIDDEN,
            'default' => 'reset',
            'selectors' => [
                '{{WRAPPER}} .cora-feature-card' => 'display: flex; align-items: flex-start; padding: 24px; border-radius: 16px; gap: 20px; text-decoration: none; border: 1px solid #f1f5f9; transition: transform 0.2s;',
                '{{WRAPPER}} .cora-feature-card:hover' => 'transform: translateY(-2px);',
                
                // Icon Box
                '{{WRAPPER}} .feature-icon' => 'flex-shrink: 0; display: flex; align-items: center; justify-content: center; border-radius: 12px;',
                
                // Content Body
                '{{WRAPPER}} .feature-body' => 'display: flex; flex-direction: column; gap: 8px; flex: 1; min-width: 0;',
                '{{WRAPPER}} .feature-title' => 'margin: 0 !important; font-size: 18px; font-weight: 700; line-height: 1.2;',
                '{{WRAPPER}} .feature-desc' => 'margin: 0 !important; font-size: 15px; line-height: 1.5;',

                // Default Mobile Behavior (Can be overridden by the control above)
                '@media (max-width: 767px)' => [
                    '{{WRAPPER}} .cora-feature-card' => 'flex-direction: column; align-items: flex-start; gap: 16px;',
                    '{{WRAPPER}} .feature-icon' => 'width: 56px; height: 56px;',
                ],
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
                <p class="feature-desc"><?php echo esc_html($settings['desc']); ?></p>
            </div>

        </<?php echo $wrapper_tag; ?>>
        <?php
    }
}