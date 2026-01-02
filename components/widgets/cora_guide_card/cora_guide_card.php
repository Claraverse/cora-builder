<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Icons_Manager;

if (!defined('ABSPATH')) exit;

class Cora_Guide_Card extends Base_Widget
{
    public function get_name() { return 'cora_guide_card'; }
    public function get_title() { return __('Cora Guide Card', 'cora-builder'); }
    public function get_icon() { return 'eicon-card'; }

    protected function register_controls()
    {
        // --- TAB 1: CONTENT ---
        $this->start_controls_section('section_content', ['label' => __('Guide Content', 'cora-builder')]);

        $this->add_control('icon', [
            'label' => 'Icon',
            'type' => Controls_Manager::ICONS,
            'default' => ['value' => 'fas fa-dollar-sign', 'library' => 'solid']
        ]);

        $this->add_control('title', [
            'label' => 'Title',
            'type' => Controls_Manager::TEXT,
            'default' => 'Marketing & Sales',
            'dynamic' => ['active' => true],
            'label_block' => true,
        ]);

        $this->add_control('desc', [
            'label' => 'Description',
            'type' => Controls_Manager::TEXTAREA,
            'default' => 'Strategies to boost your revenue',
            'dynamic' => ['active' => true]
        ]);

        $this->add_control('guide_count', [
            'label' => 'Footer Text',
            'type' => Controls_Manager::TEXT,
            'default' => '98 guides',
            'dynamic' => ['active' => true]
        ]);

        $this->add_control('card_link', [
            'label' => __('Link', 'cora-builder'),
            'type' => Controls_Manager::URL,
            'dynamic' => ['active' => true],
            'placeholder' => __('https://your-link.com', 'cora-builder'),
        ]);

        // Optional Tags (Hidden by default based on your screenshot, but kept for power users)
        $this->add_control('show_tags', [
            'label' => 'Show Tags?',
            'type' => Controls_Manager::SWITCHER,
            'default' => '',
        ]);

        $repeater = new Repeater();
        $repeater->add_control('tag_text', ['label' => 'Tag Label', 'type' => Controls_Manager::TEXT]);
        $this->add_control('tags', [
            'label' => 'Feature Tags',
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [['tag_text' => 'Strategy'], ['tag_text' => 'Growth']],
            'condition' => ['show_tags' => 'yes'],
        ]);

        $this->end_controls_section();

        // --- TAB 2: STYLE ---
        
        // 1. CONTAINER STYLE
        $this->start_controls_section('style_container', ['label' => 'Container', 'tab' => Controls_Manager::TAB_STYLE]);
        
        $this->add_control('card_bg', [
            'label' => 'Card Background',
            'type' => Controls_Manager::COLOR,
            'default' => '#ffffff',
            'selectors' => ['{{WRAPPER}} .cora-guide-card' => 'background-color: {{VALUE}};']
        ]);

        $this->add_responsive_control('card_padding', [
            'label' => 'Padding',
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'selectors' => ['{{WRAPPER}} .cora-guide-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
        ]);

        $this->add_responsive_control('card_radius', [
            'label' => 'Border Radius',
            'type' => Controls_Manager::DIMENSIONS,
            'selectors' => ['{{WRAPPER}} .cora-guide-card' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;']
        ]);

        $this->add_group_control(Group_Control_Box_Shadow::get_type(), [
            'name' => 'card_shadow',
            'selector' => '{{WRAPPER}} .cora-guide-card',
        ]);
        
        $this->add_group_control(Group_Control_Border::get_type(), [
            'name' => 'card_border',
            'selector' => '{{WRAPPER}} .cora-guide-card',
        ]);

        $this->end_controls_section();

        // 2. ICON STYLE
        $this->start_controls_section('style_icon', ['label' => 'Icon Styling', 'tab' => Controls_Manager::TAB_STYLE]);

        $this->add_control('icon_bg', [
            'label' => 'Box Background',
            'type' => Controls_Manager::COLOR,
            'default' => '#ECFDF5', // Light green
            'selectors' => ['{{WRAPPER}} .card-icon-box' => 'background-color: {{VALUE}};']
        ]);

        $this->add_control('icon_color', [
            'label' => 'Icon Color',
            'type' => Controls_Manager::COLOR,
            'default' => '#10B981', // Green
            'selectors' => ['{{WRAPPER}} .card-icon-box' => 'color: {{VALUE}};']
        ]);

        $this->add_responsive_control('icon_box_size', [
            'label' => 'Box Size',
            'type' => Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 30, 'max' => 100]],
            'default' => ['size' => 48],
            'selectors' => ['{{WRAPPER}} .card-icon-box' => 'width: {{SIZE}}px; height: {{SIZE}}px;']
        ]);

        $this->add_responsive_control('icon_size', [
            'label' => 'Icon Size',
            'type' => Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 10, 'max' => 50]],
            'default' => ['size' => 24],
            'selectors' => ['{{WRAPPER}} .card-icon-box i' => 'font-size: {{SIZE}}px;', '{{WRAPPER}} .card-icon-box svg' => 'width: {{SIZE}}px; height: {{SIZE}}px;']
        ]);

        $this->add_responsive_control('icon_radius', [
            'label' => 'Border Radius',
            'type' => Controls_Manager::DIMENSIONS,
            'selectors' => ['{{WRAPPER}} .card-icon-box' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;']
        ]);

        $this->end_controls_section();

        // 3. TYPOGRAPHY
        $this->start_controls_section('style_typo', ['label' => 'Typography', 'tab' => Controls_Manager::TAB_STYLE]);

        $this->add_control('title_heading', ['label' => 'Title', 'type' => Controls_Manager::HEADING]);
        $this->add_control('title_color', [
            'label' => 'Color',
            'type' => Controls_Manager::COLOR,
            'default' => '#0f172a',
            'selectors' => ['{{WRAPPER}} .card-title' => 'color: {{VALUE}};']
        ]);
        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'title_typo',
            'selector' => '{{WRAPPER}} .card-title',
        ]);

        $this->add_control('desc_heading', ['label' => 'Description', 'type' => Controls_Manager::HEADING, 'separator' => 'before']);
        $this->add_control('desc_color', [
            'label' => 'Color',
            'type' => Controls_Manager::COLOR,
            'default' => '#64748b',
            'selectors' => ['{{WRAPPER}} .card-desc' => 'color: {{VALUE}};']
        ]);
        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'desc_typo',
            'selector' => '{{WRAPPER}} .card-desc',
        ]);

        $this->add_control('footer_heading', ['label' => 'Footer (Count)', 'type' => Controls_Manager::HEADING, 'separator' => 'before']);
        $this->add_control('footer_color', [
            'label' => 'Color',
            'type' => Controls_Manager::COLOR,
            'default' => '#64748b',
            'selectors' => ['{{WRAPPER}} .count-text' => 'color: {{VALUE}};']
        ]);
        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'footer_typo',
            'selector' => '{{WRAPPER}} .count-text',
        ]);

        $this->end_controls_section();

        // 4. LAYOUT AUTHORITY
        $this->start_controls_section('layout_reset', ['label' => 'Layout Logic', 'tab' => Controls_Manager::TAB_STYLE]);
        $this->add_control('css_reset', [
            'type' => Controls_Manager::HIDDEN,
            'default' => 'reset',
            'selectors' => [
                // Card Main
                '{{WRAPPER}} .cora-guide-card' => 'display: flex; flex-direction: column; height: 100%; position: relative; transition: all 0.3s ease; box-sizing: border-box; overflow: hidden; padding: 24px;',
                '{{WRAPPER}} .cora-guide-card:hover' => 'transform: translateY(-4px);',
                
                // Link Mask (Clickable Area)
                '{{WRAPPER}} .card-link-mask' => 'position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 5; cursor: pointer;',

                // Icon Box
                '{{WRAPPER}} .card-icon-box' => 'display: flex; align-items: center; justify-content: center; margin-bottom: 20px; flex-shrink: 0;',

                // Content
                '{{WRAPPER}} .card-body' => 'display: flex; flex-direction: column; gap: 8px; margin-bottom: 24px;',
                '{{WRAPPER}} .card-title' => 'margin: 0 !important; font-size: 18px; font-weight: 700; line-height: 1.2;',
                '{{WRAPPER}} .card-desc' => 'margin: 0 !important; font-size: 15px; line-height: 1.5;',

                // Tags (Optional)
                '{{WRAPPER}} .tag-cloud' => 'display: flex; flex-wrap: wrap; gap: 8px; margin-top: 12px;',
                '{{WRAPPER}} .tag-item' => 'background: #f1f5f9; color: #475569; padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 600;',

                // Footer (Auto-Push Bottom)
                '{{WRAPPER}} .card-footer' => 'margin-top: auto; display: flex; justify-content: space-between; align-items: center; width: 100%;',
                '{{WRAPPER}} .count-text' => 'font-size: 13px; font-weight: 500;',
            ],
        ]);
        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $has_link = !empty($settings['card_link']['url']);

        if ($has_link) {
            $this->add_link_attributes('wrapper_link', $settings['card_link']);
        }
        ?>
        <div class="cora-unit-container cora-guide-card">
            <?php if ($has_link): ?>
                <a <?php echo $this->get_render_attribute_string('wrapper_link'); ?> class="card-link-mask"></a>
            <?php endif; ?>

            <div class="card-icon-box">
                <?php \Elementor\Icons_Manager::render_icon($settings['icon'], ['aria-hidden' => 'true']); ?>
            </div>

            <div class="card-body">
                <h3 class="card-title"><?php echo esc_html($settings['title']); ?></h3>
                <p class="card-desc"><?php echo esc_html($settings['desc']); ?></p>
                
                <?php if ('yes' === $settings['show_tags'] && !empty($settings['tags'])): ?>
                <div class="tag-cloud">
                    <?php foreach ($settings['tags'] as $tag): ?>
                        <span class="tag-item"><?php echo esc_html($tag['tag_text']); ?></span>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>

            <div class="card-footer">
                <span class="count-text"><?php echo esc_html($settings['guide_count']); ?></span>
            </div>
        </div>
        <?php
    }
}