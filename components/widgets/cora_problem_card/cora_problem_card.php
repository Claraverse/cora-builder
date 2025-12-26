<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;

if (!defined('ABSPATH')) exit;

class Cora_Problem_Card extends Base_Widget {

    public function get_name() { return 'cora_problem_card'; }
    public function get_title() { return __('Cora Problem Card', 'cora-builder'); }

    protected function register_controls() {
        
        // --- TAB: CONTENT ---
        $this->start_controls_section('content', ['label' => 'Card Content']);
        $this->add_control('card_icon', [
            'label' => 'Icon Asset',
            'type' => Controls_Manager::MEDIA,
            'dynamic' => ['active' => true],
        ]);
        $this->add_control('card_text', [
            'label' => 'Problem Text',
            'type' => Controls_Manager::TEXTAREA,
            'dynamic' => ['active' => true],
            'default' => 'Slow, clunky performance',
        ]);
        $this->end_controls_section();

        // --- TAB: STYLE - CONTAINER ---
        $this->start_controls_section('style_container', ['label' => 'Card Appearance', 'tab' => Controls_Manager::TAB_STYLE]);
        
        $this->start_controls_tabs('container_tabs');
        $this->start_controls_tab('container_normal', ['label' => 'Normal']);
        
        $this->add_group_control(Group_Control_Background::get_type(), [
            'name' => 'bg',
            'selector' => '{{WRAPPER}} .cora-problem-card',
        ]);

        $this->add_responsive_control('card_padding', [
            'label' => 'Internal Padding',
            'type' => Controls_Manager::DIMENSIONS,
            'selectors' => ['{{WRAPPER}} .cora-problem-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);

        $this->add_group_control(Group_Control_Border::get_type(), [
            'name' => 'card_border',
            'selector' => '{{WRAPPER}} .cora-problem-card',
        ]);

        $this->add_control('card_radius', [
            'label' => 'Corner Radius',
            'type' => Controls_Manager::SLIDER,
            'selectors' => ['{{WRAPPER}} .cora-problem-card' => 'border-radius: {{SIZE}}{{UNIT}} !important;'],
        ]);

        $this->add_group_control(Group_Control_Box_Shadow::get_type(), [
            'name' => 'card_shadow',
            'selector' => '{{WRAPPER}} .cora-problem-card',
        ]);

        $this->end_controls_tab();

        $this->start_controls_tab('container_hover', ['label' => 'Hover']);
        $this->add_control('hover_transition', [
            'label' => 'Transition Duration',
            'type' => Controls_Manager::SLIDER,
            'selectors' => ['{{WRAPPER}} .cora-problem-card' => 'transition: all {{SIZE}}s ease-in-out;'],
        ]);
        $this->add_control('hover_y', [
            'label' => 'Vertical Lift (Y)',
            'type' => Controls_Manager::SLIDER,
            'selectors' => ['{{WRAPPER}} .cora-problem-card:hover' => 'transform: translateY({{SIZE}}px);'],
        ]);
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        // --- TAB: STYLE - ICON HUB ---
        $this->start_controls_section('style_icon', ['label' => 'Icon Hub', 'tab' => Controls_Manager::TAB_STYLE]);
        
        $this->add_responsive_control('icon_size', [
            'label' => 'Hub Size',
            'type' => Controls_Manager::SLIDER,
            'selectors' => ['{{WRAPPER}} .problem-icon-hub' => 'width: {{SIZE}}px; height: {{SIZE}}px;'],
        ]);

        $this->add_control('icon_bg_color', [
            'label' => 'Hub Background',
            'type' => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .problem-icon-hub' => 'background-color: {{VALUE}} !important;'],
        ]);

        $this->add_responsive_control('icon_gap', [
            'label' => 'Gap Below Icon',
            'type' => Controls_Manager::SLIDER,
            'selectors' => ['{{WRAPPER}} .problem-icon-hub' => 'margin-bottom: {{SIZE}}{{UNIT}} !important;'],
        ]);

        $this->end_controls_section();

        // --- TAB: STYLE - TEXT ---
        $this->start_controls_section('style_text', ['label' => 'Typography', 'tab' => Controls_Manager::TAB_STYLE]);
        
        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'text_typo',
            'selector' => '{{WRAPPER}} .problem-text-label',
        ]);

        $this->add_control('text_color', [
            'label' => 'Text Color',
            'type' => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .problem-text-label' => 'color: {{VALUE}};'],
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="cora-problem-card">
            <div class="problem-icon-hub">
                <img src="<?php echo esc_url($settings['card_icon']['url']); ?>" alt="Icon">
            </div>
            <p class="problem-text-label"><?php echo esc_html($settings['card_text']); ?></p>
        </div>
        <?php
    }
}