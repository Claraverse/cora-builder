<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

if (!defined('ABSPATH'))
    exit;

class Metric_Grid extends Base_Widget
{

    public function get_name()
    {
        return 'metric_grid';
    }
    public function get_title()
    {
        return __('Cora Metric Grid', 'cora-builder');
    }
    public function get_icon()
    {
        return 'eicon-inner-section';
    }

    protected function register_controls()
    {

        // --- TAB: CONTENT ---
        $this->start_controls_section('metric_items', ['label' => __('Metrics', 'cora-builder')]);

        $repeater = new Repeater();
        $repeater->add_control('icon', ['label' => 'Icon/Emoji', 'type' => Controls_Manager::ICONS]);
        $repeater->add_control('title', ['label' => 'Title', 'type' => Controls_Manager::TEXT, 'dynamic' => ['active' => true]]);
        $repeater->add_control('subtext', ['label' => 'Subtext', 'type' => Controls_Manager::TEXT, 'dynamic' => ['active' => true]]);

        $this->add_control('items', [
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                ['title' => '100+ Guides', 'subtext' => 'Free access'],
                ['title' => '50K+ Users', 'subtext' => 'Active learners'],
                ['title' => '4.9/5 Rating', 'subtext' => 'Trusted quality'],
            ],
            'title_field' => '{{{ title }}}',
        ]);

        $this->end_controls_section();

        // // --- TAB: STYLE - GRID LAYOUT ---
        // $this->start_controls_section('style_grid', ['label' => __('Grid Layout', 'cora-builder'), 'tab' => Controls_Manager::TAB_STYLE]);

        // $this->add_control('grid_outline', [
        //     'label' => __('Grid Outline', 'cora-builder'),
        //     'type' => Controls_Manager::SWITCHER,
        //     'selectors' => ['{{WRAPPER}} .cora-metric-item' => 'outline: 1px dashed #ddd;'],
        // ]);

        // $this->add_responsive_control('columns', [
        //     'label' => __('Columns', 'cora-builder'),
        //     'type' => Controls_Manager::SLIDER,
        //     'size_units' => ['fr', 'px'],
        //     'range' => ['fr' => ['min' => 1, 'max' => 6]],
        //     'selectors' => ['{{WRAPPER}} .cora-unit-container' => 'grid-template-columns: repeat({{SIZE}}, 1fr);'],
        // ]);

        // $this->add_responsive_control('gaps', [
        //     'label' => __('Gaps', 'cora-builder'),
        //     'type' => Controls_Manager::GAPS,
        //     'selectors' => ['{{WRAPPER}} .cora-unit-container' => 'column-gap: {{COLUMN}}{{UNIT}}; row-gap: {{ROW}}{{UNIT}};'],
        // ]);

        // $this->end_controls_section();
        // --- TAB: STYLE - GRID LAYOUT ---
        $this->start_controls_section('style_grid', [
            'label' => __('Grid Layout', 'cora-builder'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('grid_outline', [
            'label' => __('Grid Outline', 'cora-builder'),
            'type' => Controls_Manager::SWITCHER,
            'selectors' => ['{{WRAPPER}} .cora-metric-item' => 'outline: 1px dashed #ddd;'],
        ]);

        $this->add_responsive_control('columns', [
            'label' => __('Columns', 'cora-builder'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['fr', 'px'],
            'range' => ['fr' => ['min' => 1, 'max' => 6]],
            'selectors' => ['{{WRAPPER}} .cora-unit-container' => 'grid-template-columns: repeat({{SIZE}}, 1fr);'],
        ]);

        $this->add_responsive_control('gaps', [
            'label' => __('Gaps', 'cora-builder'),
            'type' => Controls_Manager::GAPS,
            'selectors' => ['{{WRAPPER}} .cora-unit-container' => 'column-gap: {{COLUMN}}{{UNIT}}; row-gap: {{ROW}}{{UNIT}};'],
        ]);
        $this->add_responsive_control('justify_items', [
            'label' => __('Justify Items', 'cora-builder'),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'start' => ['title' => 'Left', 'icon' => 'eicon-text-align-left'],
                'center' => ['title' => 'Center', 'icon' => 'eicon-text-align-center'],
                'end' => ['title' => 'Right', 'icon' => 'eicon-text-align-right'],
                'stretch' => ['title' => 'Stretch', 'icon' => 'eicon-text-align-justify'],
            ],
            'selectors' => ['{{WRAPPER}} .cora-unit-container' => 'justify-items: {{VALUE}};'],
            'default' => 'start', // Requirement: Match reference left-alignment
        ]);

        $this->add_responsive_control('align_items', [
            'label' => __('Align Items', 'cora-builder'),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'start' => ['title' => 'Top', 'icon' => 'eicon-v-align-top'],
                'center' => ['title' => 'Middle', 'icon' => 'eicon-v-align-middle'],
                'end' => ['title' => 'Bottom', 'icon' => 'eicon-v-align-bottom'],
            ],
            'selectors' => ['{{WRAPPER}} .cora-unit-container' => 'align-items: {{VALUE}};'],
            'default' => 'center', // Requirement: Vertical centering
        ]);

        $this->end_controls_section();

        // --- TAB: STYLE - INDIVIDUAL ELEMENT CONTROLS ---

        // 1. Icon Controls
        $this->start_controls_section('style_icon', ['label' => __('Icon Styling', 'cora-builder'), 'tab' => Controls_Manager::TAB_STYLE]);

        $this->add_control('icon_color', [
            'label' => __('Color', 'cora-builder'),
            'type' => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .metric-icon' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('icon_size', [
            'label' => __('Size', 'cora-builder'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px', 'em', 'rem', 'vw'],
            'dynamic' => ['active' => true],
            'selectors' => ['{{WRAPPER}} .metric-icon' => 'font-size: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('icon_gap', [
            'label' => __('Space After Icon', 'cora-builder'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px', 'em', 'rem', 'vw'],
            'dynamic' => ['active' => true],
            'selectors' => ['{{WRAPPER}} .cora-metric-item' => 'gap: {{SIZE}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        // 2. Title Controls
        $this->start_controls_section('style_title', ['label' => __('Title Styling', 'cora-builder'), 'tab' => Controls_Manager::TAB_STYLE]);

        $this->add_control('title_color', [
            'label' => __('Color', 'cora-builder'),
            'type' => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .metric-title' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), ['name' => 'title_typo', 'selector' => '{{WRAPPER}} .metric-title']);

        $this->add_responsive_control('title_gap', [
            'label' => __('Space After Title', 'cora-builder'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px', 'em', 'rem'],
            'dynamic' => ['active' => true],
            'selectors' => ['{{WRAPPER}} .metric-text' => 'gap: {{SIZE}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        // 3. Subtext Controls
        $this->start_controls_section('style_subtext', ['label' => __('Subtext Styling', 'cora-builder'), 'tab' => Controls_Manager::TAB_STYLE]);

        $this->add_control('subtext_color', [
            'label' => __('Color', 'cora-builder'),
            'type' => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .metric-subtext' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), ['name' => 'subtext_typo', 'selector' => '{{WRAPPER}} .metric-subtext']);

        $this->end_controls_section();


        // Load Global Layout & Variable Controls (Max-Width, Padding, Mode)
        $this->register_common_spatial_controls();
        $this->register_advanced_positioning_controls();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="cora-unit-container cora-metric-grid-wrapper">
            <?php foreach ($settings['items'] as $item): ?>
                <div class="cora-metric-item">
                    <div class="metric-icon"><?php \Elementor\Icons_Manager::render_icon($item['icon']); ?></div>
                    <div class="metric-text">
                        <span class="metric-title"><?php echo esc_html($item['title']); ?></span>
                        <span class="metric-subtext"><?php echo esc_html($item['subtext']); ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
    }
}