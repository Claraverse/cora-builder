<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Repeater; 
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;

if (!defined('ABSPATH'))
    exit;

class Feature_Card_Grid extends Base_Widget
{

    public function get_name()
    {
        return 'feature_card_grid';
    }
    public function get_title()
    {
        return __('Cora Feature Cards', 'cora-builder');
    }
    public function get_icon()
    {
        return 'eicon-gallery-grid';
    }

    protected function register_controls()
    {

        // --- TAB: CONTENT ---
        $this->start_controls_section('content_section', ['label' => __('Cards', 'cora-builder')]);
        $repeater = new Repeater();
        $repeater->add_control('icon', ['label' => 'Icon', 'type' => Controls_Manager::ICONS]);
        $repeater->add_control('title', ['label' => 'Title', 'type' => Controls_Manager::TEXT, 'dynamic' => ['active' => true]]);
        $repeater->add_control('desc', ['label' => 'Description', 'type' => Controls_Manager::TEXTAREA, 'dynamic' => ['active' => true]]);
        $this->add_control('cards', [
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                ['title' => '100+ Guides', 'desc' => 'Comprehensive resources'],
                ['title' => '50K+ Users', 'desc' => 'Active community'],
            ],
            'title_field' => '{{{ title }}}',
        ]);
        $this->end_controls_section();

        // --- TAB: STYLE - GRID LAYOUT ---
        $this->start_controls_section('style_grid', ['label' => __('Grid Layout', 'cora-builder'), 'tab' => Controls_Manager::TAB_STYLE]);
        $this->add_responsive_control('columns', [
            'label' => __('Columns', 'cora-builder'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['fr', 'px'],
            'range' => ['fr' => ['min' => 1, 'max' => 6]],
            'selectors' => ['{{WRAPPER}} .cora-feature-grid-wrapper' => 'grid-template-columns: repeat({{SIZE}}, 1fr);'],
        ]);
        $this->add_responsive_control('gaps', [
            'label' => __('Gaps', 'cora-builder'),
            'type' => Controls_Manager::GAPS,
            'selectors' => ['{{WRAPPER}} .cora-feature-grid-wrapper' => 'column-gap: {{COLUMN}}{{UNIT}}; row-gap: {{ROW}}{{UNIT}};'],
        ]);
        $this->add_responsive_control('justify_items', [
            'label' => __('Justify Items', 'cora-builder'),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'start' => ['title' => 'Left', 'icon' => 'eicon-text-align-left'],
                'center' => ['title' => 'Center', 'icon' => 'eicon-text-align-center'],
                'end' => ['title' => 'Right', 'icon' => 'eicon-text-align-right'],
            ],
            'selectors' => ['{{WRAPPER}} .cora-feature-grid-wrapper' => 'justify-items: {{VALUE}};'],
        ]);
        $this->end_controls_section();

        // --- TAB: STYLE - INDIVIDUAL CARD STYLING ---
        $this->start_controls_section('style_card_container', ['label' => __('Card Container', 'cora-builder'), 'tab' => Controls_Manager::TAB_STYLE]);
        $this->add_control('card_bg', ['label' => 'Background', 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .cora-feature-card' => 'background-color: {{VALUE}};']]);
        $this->add_group_control(Group_Control_Border::get_type(), ['name' => 'card_border', 'selector' => '{{WRAPPER}} .cora-feature-card']);
        $this->add_responsive_control('card_radius', ['label' => 'Radius', 'type' => Controls_Manager::DIMENSIONS, 'size_units' => ['px', '%'], 'selectors' => ['{{WRAPPER}} .cora-feature-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']]);
        $this->add_group_control(Group_Control_Box_Shadow::get_type(), ['name' => 'card_shadow', 'selector' => '{{WRAPPER}} .cora-feature-card']);
        $this->add_responsive_control('card_padding', ['label' => 'Internal Padding', 'type' => Controls_Manager::DIMENSIONS, 'size_units' => ['px', '%', 'em', 'rem'], 'selectors' => ['{{WRAPPER}} .cora-feature-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']]);
        $this->end_controls_section();

        // --- TAB: STYLE - ICON BOX STYLING ---
        $this->start_controls_section('style_icon_box', ['label' => __('Icon Box Styling', 'cora-builder'), 'tab' => Controls_Manager::TAB_STYLE]);
        $this->add_responsive_control('icon_box_size', ['label' => 'Box Size', 'type' => Controls_Manager::SLIDER, 'size_units' => ['px', 'vw'], 'selectors' => ['{{WRAPPER}} .card-icon-box' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};']]);
        $this->add_responsive_control('icon_box_padding', ['label' => 'Internal Padding', 'type' => Controls_Manager::DIMENSIONS, 'size_units' => ['px', '%', 'em', 'rem'], 'selectors' => ['{{WRAPPER}} .card-icon-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']]);
        $this->add_control('icon_box_bg', ['label' => 'Box Background', 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .card-icon-box' => 'background-color: {{VALUE}};']]);
        $this->add_control('icon_color', ['label' => 'Icon Color', 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .card-icon-box' => 'color: {{VALUE}};']]);
        $this->add_responsive_control('icon_size', ['label' => 'Icon Size', 'type' => Controls_Manager::SLIDER, 'selectors' => ['{{WRAPPER}} .card-icon-box i, {{WRAPPER}} .card-icon-box svg' => 'font-size: {{SIZE}}px;']]);
        $this->add_responsive_control('icon_box_radius', ['label' => 'Radius', 'type' => Controls_Manager::DIMENSIONS, 'size_units' => ['px', '%'], 'selectors' => ['{{WRAPPER}} .card-icon-box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']]);
        $this->add_responsive_control('icon_box_margin', ['label' => 'Bottom Spacing', 'type' => Controls_Manager::SLIDER, 'selectors' => ['{{WRAPPER}} .card-icon-box' => 'margin-bottom: {{SIZE}}px;']]);
        $this->end_controls_section();

        // --- TAB: STYLE - TYPOGRAPHY ---
        $this->start_controls_section('style_typo', ['label' => __('Title & Description', 'cora-builder'), 'tab' => Controls_Manager::TAB_STYLE]);
        $this->add_control('title_color', ['label' => 'Title Color', 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .card-title' => 'color: {{VALUE}};']]);
        $this->add_group_control(Group_Control_Typography::get_type(), ['name' => 'title_typo', 'selector' => '{{WRAPPER}} .card-title']);
        $this->add_responsive_control('title_margin', ['label' => 'Title Bottom Spacing', 'type' => Controls_Manager::SLIDER, 'selectors' => ['{{WRAPPER}} .card-title' => 'margin-bottom: {{SIZE}}px;']]);
        $this->add_control('desc_color', ['label' => 'Description Color', 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .card-desc' => 'color: {{VALUE}};']]);
        $this->add_group_control(Group_Control_Typography::get_type(), ['name' => 'desc_typo', 'selector' => '{{WRAPPER}} .card-desc']);
        $this->end_controls_section();

        // Global Layout & Spacing Core
        $this->register_common_spatial_controls();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="cora-unit-container cora-feature-grid-wrapper">
            <?php foreach ($settings['cards'] as $card): ?>
                <div class="cora-feature-card">
                    <div class="card-icon-box"><?php \Elementor\Icons_Manager::render_icon($card['icon']); ?></div>
                    <div class="card-content">
                        <h3 class="card-title"><?php echo esc_html($card['title']); ?></h3>
                        <p class="card-desc"><?php echo esc_html($card['desc']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
    }
}