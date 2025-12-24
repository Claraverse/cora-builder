<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

if (!defined('ABSPATH'))
    exit;

class Cora_Feature_Card extends Base_Widget
{

    public function get_name()
    {
        return 'cora_feature_card';
    }
    public function get_title()
    {
        return __('Cora Feature Card', 'cora-builder');
    }
    public function get_icon()
    {
        return 'eicon-post-list';
    }

    protected function register_controls()
    {

        // --- TAB: CONTENT ---
        $this->start_controls_section('section_content', ['label' => __('Card Content', 'cora-builder')]);
        $this->add_control('icon', ['label' => 'Icon', 'type' => Controls_Manager::ICONS, 'default' => ['value' => 'fas fa-book-open', 'library' => 'solid']]);
        $this->add_control('title', ['label' => 'Title', 'type' => Controls_Manager::TEXT, 'default' => 'Online Learning', 'dynamic' => ['active' => true]]);
        $this->add_control('desc', ['label' => 'Description', 'type' => Controls_Manager::TEXTAREA, 'default' => 'Engage in interactive, real-time sessions led by industry experts...', 'dynamic' => ['active' => true]]);

        $this->end_controls_section();

        // --- TAB: STYLE - CONTAINER ---
        $this->start_controls_section('style_container', ['label' => 'Card Container', 'tab' => Controls_Manager::TAB_STYLE]);
        $this->add_control('bg_color', ['label' => 'Background', 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .cora-card-wrap' => 'background-color: {{VALUE}};']]);
        $this->add_group_control(Group_Control_Border::get_type(), ['name' => 'border', 'selector' => '{{WRAPPER}} .cora-card-wrap']);
        $this->add_responsive_control('radius', ['label' => 'Radius', 'type' => Controls_Manager::DIMENSIONS, 'size_units' => ['px', '%'], 'selectors' => ['{{WRAPPER}} .cora-card-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']]);
        $this->add_group_control(Group_Control_Box_Shadow::get_type(), ['name' => 'shadow', 'selector' => '{{WRAPPER}} .cora-card-wrap']);
        $this->add_responsive_control('padding', ['label' => 'Padding', 'type' => Controls_Manager::DIMENSIONS, 'size_units' => ['px', '%'], 'selectors' => ['{{WRAPPER}} .cora-card-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']]);

        $this->end_controls_section();

        // --- TAB: STYLE - ICON BOX ---
        $this->start_controls_section('style_icon_box', ['label' => 'Icon Box', 'tab' => Controls_Manager::TAB_STYLE]);
        $this->add_responsive_control('box_size', ['label' => 'Box Size', 'type' => Controls_Manager::SLIDER, 'selectors' => ['{{WRAPPER}} .icon-box' => 'width: {{SIZE}}px; height: {{SIZE}}px;']]);
        $this->add_control('box_bg', ['label' => 'Box Background', 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .icon-box' => 'background-color: {{VALUE}};']]);
        $this->add_responsive_control('box_radius', ['label' => 'Box Radius', 'type' => Controls_Manager::DIMENSIONS, 'selectors' => ['{{WRAPPER}} .icon-box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']]);
        $this->add_control('icon_color', ['label' => 'Icon Color', 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .icon-box' => 'color: {{VALUE}};']]);
        $this->add_responsive_control('icon_size', ['label' => 'Icon Size', 'type' => Controls_Manager::SLIDER, 'selectors' => ['{{WRAPPER}} .icon-box i, {{WRAPPER}} .icon-box svg' => 'font-size: {{SIZE}}px;']]);
        $this->end_controls_section();

        // --- TAB: STYLE - TEXT ENGINE ---
        $this->register_text_styling_controls('title_style', 'Title Styling', '{{WRAPPER}} .card-title');
        $this->register_text_styling_controls('desc_style', 'Description Styling', '{{WRAPPER}} .card-desc');

        // Spacing Core
        $this->register_common_spatial_controls();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="cora-unit-container cora-feature-card-wrapper">
            <div class="cora-card-wrap">
                <div class="icon-box">
                    <?php \Elementor\Icons_Manager::render_icon($settings['icon'], ['aria-hidden' => 'true']); ?></div>
                <div class="content-box">
                    <h3 class="card-title"><?php echo esc_html($settings['title']); ?></h3>
                    <div class="card-desc"><?php echo esc_html($settings['desc']); ?></div>
                </div>
            </div>
        </div>
        <?php
    }
}