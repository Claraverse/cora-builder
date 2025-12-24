<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

if (!defined('ABSPATH'))
    exit;

class Cora_Button_Group extends Base_Widget
{

    public function get_name()
    {
        return 'cora_button_group';
    }
    public function get_title()
    {
        return __('Cora Button Group 2', 'cora-builder');
    }
    public function get_icon()
    {
        return 'eicon-button';
    }

    protected function register_controls()
    {

        // --- TAB: CONTENT ---
        $this->start_controls_section('content_pri', ['label' => __('Primary Button', 'cora-builder')]);
        $this->add_control('pri_text', ['label' => 'Text', 'type' => Controls_Manager::TEXT, 'default' => 'Browse All Guides', 'dynamic' => ['active' => true]]);
        $this->add_control('pri_link', ['label' => 'Link', 'type' => Controls_Manager::URL, 'dynamic' => ['active' => true]]);
        $this->add_control('pri_icon', ['label' => 'Icon', 'type' => Controls_Manager::ICONS, 'default' => ['value' => 'fas fa-arrow-right', 'library' => 'solid']]);
        $this->end_controls_section();

        $this->start_controls_section('content_sec', ['label' => __('Secondary Button', 'cora-builder')]);
        $this->add_control('sec_text', ['label' => 'Text', 'type' => Controls_Manager::TEXT, 'default' => 'View Categories', 'dynamic' => ['active' => true]]);
        $this->add_control('sec_link', ['label' => 'Link', 'type' => Controls_Manager::URL, 'dynamic' => ['active' => true]]);
        $this->add_control('sec_icon', ['label' => 'Icon', 'type' => Controls_Manager::ICONS]);
        $this->end_controls_section();

        // --- TAB: STYLE - LAYOUT ---
        $this->start_controls_section('style_layout', ['label' => __('Group Layout', 'cora-builder'), 'tab' => Controls_Manager::TAB_STYLE]);
        $this->add_responsive_control('align', [
            'label' => 'Alignment',
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'flex-start' => ['title' => 'Left', 'icon' => 'eicon-text-align-left'],
                'center' => ['title' => 'Center', 'icon' => 'eicon-text-align-center'],
                'flex-end' => ['title' => 'Right', 'icon' => 'eicon-text-align-right'],
            ],
            'selectors' => ['{{WRAPPER}} .cora-button-group-wrapper' => 'justify-content: {{VALUE}};'],
        ]);
        $this->add_responsive_control('gap', [
            'label' => 'Space Between Buttons',
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px', 'em', 'rem', 'vw'],
            'dynamic' => ['active' => true], //
            'selectors' => ['{{WRAPPER}} .cora-button-group-wrapper' => 'gap: {{SIZE}}{{UNIT}};'],
        ]);
        $this->end_controls_section();

        // --- TAB: STYLE - PRIMARY BUTTON ---
        $this->start_controls_section('style_pri', ['label' => __('Primary Style', 'cora-builder'), 'tab' => Controls_Manager::TAB_STYLE]);
        $this->add_group_control(Group_Control_Typography::get_type(), ['name' => 'pri_typo', 'selector' => '{{WRAPPER}} .btn-pri']);

        $this->start_controls_tabs('pri_tabs');
        $this->start_controls_tab('pri_normal', ['label' => 'Normal']);
        $this->add_control('pri_color', ['label' => 'Text Color', 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .btn-pri' => 'color: {{VALUE}};']]);
        $this->add_control('pri_bg', ['label' => 'Background', 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .btn-pri' => 'background-color: {{VALUE}};']]);
        $this->end_controls_tab();

        $this->start_controls_tab('pri_hover', ['label' => 'Hover']);
        $this->add_control('pri_color_h', ['label' => 'Text Color', 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .btn-pri:hover' => 'color: {{VALUE}};']]);
        $this->add_control('pri_bg_h', ['label' => 'Background', 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .btn-pri:hover' => 'background-color: {{VALUE}};']]);
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_responsive_control('pri_padding', ['label' => 'Padding', 'type' => Controls_Manager::DIMENSIONS, 'selectors' => ['{{WRAPPER}} .btn-pri' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']]);
        $this->add_responsive_control('pri_radius', ['label' => 'Radius', 'type' => Controls_Manager::DIMENSIONS, 'selectors' => ['{{WRAPPER}} .btn-pri' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']]);
        $this->end_controls_section();

        // --- TAB: STYLE - SECONDARY BUTTON ---
        $this->start_controls_section('style_sec', ['label' => __('Secondary Style', 'cora-builder'), 'tab' => Controls_Manager::TAB_STYLE]);
        $this->add_group_control(Group_Control_Typography::get_type(), ['name' => 'sec_typo', 'selector' => '{{WRAPPER}} .btn-sec']);

        $this->start_controls_tabs('sec_tabs');
        $this->start_controls_tab('sec_normal', ['label' => 'Normal']);
        $this->add_control('sec_color', ['label' => 'Text Color', 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .btn-sec' => 'color: {{VALUE}};']]);
        $this->add_control('sec_bg', ['label' => 'Background', 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .btn-sec' => 'background-color: {{VALUE}};']]);
        $this->add_group_control(Group_Control_Border::get_type(), ['name' => 'sec_border', 'selector' => '{{WRAPPER}} .btn-sec']);
        $this->end_controls_tab();

        $this->start_controls_tab('sec_hover', ['label' => 'Hover']);
        $this->add_control('sec_color_h', ['label' => 'Text Color', 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .btn-sec:hover' => 'color: {{VALUE}};']]);
        $this->add_control('sec_bg_h', ['label' => 'Background', 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .btn-sec:hover' => 'background-color: {{VALUE}};']]);
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_responsive_control('sec_padding', ['label' => 'Padding', 'type' => Controls_Manager::DIMENSIONS, 'selectors' => ['{{WRAPPER}} .btn-sec' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']]);
        $this->add_responsive_control('sec_radius', ['label' => 'Radius', 'type' => Controls_Manager::DIMENSIONS, 'selectors' => ['{{WRAPPER}} .btn-sec' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']]);
        $this->end_controls_section();


        // --- TAB: STYLE - DYNAMIC FLEX ENGINE ---
        $this->start_controls_section('style_flex_engine', [
            'label' => __('Cora Flex Engine', 'cora-builder'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('flex_direction', [
            'label' => __('Direction', 'cora-builder'),
            'type' => Controls_Manager::SELECT,
            'options' => [
                'row' => 'Horizontal (Row)',
                'row-reverse' => 'Horizontal Reverse',
                'column' => 'Vertical (Column)',
                'column-reverse' => 'Vertical Reverse',
            ],
            'default' => 'row',
            'selectors' => ['{{WRAPPER}} .cora-button-group-wrapper' => 'flex-direction: {{VALUE}};'],
        ]);

        $this->add_responsive_control('flex_wrap', [
            'label' => __('Wrap Behaviour', 'cora-builder'),
            'type' => Controls_Manager::SELECT,
            'options' => [
                'nowrap' => 'No Wrap',
                'wrap' => 'Wrap',
            ],
            'default' => 'wrap',
            'selectors' => ['{{WRAPPER}} .cora-button-group-wrapper' => 'flex-wrap: {{VALUE}};'],
        ]);

        $this->add_responsive_control('justify_content', [
            'label' => __('Justify Content', 'cora-builder'),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'flex-start' => ['title' => 'Start', 'icon' => 'eicon-text-align-left'],
                'center' => ['title' => 'Center', 'icon' => 'eicon-text-align-center'],
                'flex-end' => ['title' => 'End', 'icon' => 'eicon-text-align-right'],
                'space-between' => ['title' => 'Space Between', 'icon' => 'eicon-justify-contents-space-between'],
            ],
            'selectors' => ['{{WRAPPER}} .cora-button-group-wrapper' => 'justify-content: {{VALUE}};'],
        ]);

        $this->add_responsive_control('align_items', [
            'label' => __('Align Items', 'cora-builder'),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'flex-start' => ['title' => 'Top', 'icon' => 'eicon-v-align-top'],
                'center' => ['title' => 'Middle', 'icon' => 'eicon-v-align-middle'],
                'flex-end' => ['title' => 'Bottom', 'icon' => 'eicon-v-align-bottom'],
                'stretch' => ['title' => 'Stretch', 'icon' => 'eicon-v-align-stretch'],
            ],
            'selectors' => ['{{WRAPPER}} .cora-button-group-wrapper' => 'align-items: {{VALUE}};'],
        ]);

        $this->end_controls_section();
        $this->register_common_spatial_controls();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="cora-unit-container cora-button-group-wrapper">
            <a href="<?php echo esc_url($settings['pri_link']['url']); ?>" class="cora-btn btn-pri">
                <span><?php echo esc_html($settings['pri_text']); ?></span>
                <?php if (!empty($settings['pri_icon']['value'])): ?>
                    <span class="btn-icon"><?php \Elementor\Icons_Manager::render_icon($settings['pri_icon']); ?></span>
                <?php endif; ?>
            </a>
            <a href="<?php echo esc_url($settings['sec_link']['url']); ?>" class="cora-btn btn-sec">
                <span><?php echo esc_html($settings['sec_text']); ?></span>
                <?php if (!empty($settings['sec_icon']['value'])): ?>
                    <span class="btn-icon"><?php \Elementor\Icons_Manager::render_icon($settings['sec_icon']); ?></span>
                <?php endif; ?>
            </a>
        </div>
        <?php
    }
}