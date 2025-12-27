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
        return __('Cora Button Group (SaaS Pro)', 'cora-builder');
    }
    public function get_icon()
    {
        return 'eicon-button';
    }
    protected function register_controls()
    {

        // --- TAB 1: CONTENT ---
        $this->start_controls_section('content_pri', ['label' => 'Primary Button']);
        $this->add_control('pri_text', ['label' => 'Text', 'type' => Controls_Manager::TEXT, 'default' => 'Browse All Guides']);
        $this->add_control('pri_link', ['label' => 'Link', 'type' => Controls_Manager::URL]);
        $this->add_control('pri_icon', ['label' => 'Icon', 'type' => Controls_Manager::ICONS, 'default' => ['value' => 'fas fa-arrow-right', 'library' => 'solid']]);
        $this->end_controls_section();

        $this->start_controls_section('content_sec', ['label' => 'Secondary Button']);
        $this->add_control('sec_text', ['label' => 'Text', 'type' => Controls_Manager::TEXT, 'default' => 'View Categories']);
        $this->add_control('sec_link', ['label' => 'Link', 'type' => Controls_Manager::URL]);
        $this->add_control('sec_icon', ['label' => 'Icon', 'type' => Controls_Manager::ICONS]);
        $this->end_controls_section();

        // --- TAB 2: STYLE (Button Skins) ---
        $this->register_button_skin('pri', 'Primary Button Skin', '.btn-pri');
        $this->register_button_skin('sec', 'Secondary Button Skin', '.btn-sec');

        // --- TAB 3: GLOBAL (4th Tab - Architecture & Layout) ---
        // Replacing style.css flexbox with the Layout Geometry Engine
        $this->register_global_design_controls('.cora-button-group-wrapper');
        $this->register_layout_geometry('.cora-button-group-wrapper');
        $this->register_surface_styles('.cora-button-group-wrapper');
        $this->register_common_spatial_controls();

        // --- TAB 4: ADVANCED (Interactions) ---
        $this->register_interaction_motion();
        $this->register_transform_engine('.cora-btn'); // State-aware movement per button
    }
    protected function register_button_skin($id, $label, $selector)
    {
        $this->start_controls_section('skin_' . $id, ['label' => $label, 'tab' => Controls_Manager::TAB_STYLE]);

        // Base behavior (Free from CSS file)
        $this->add_responsive_control($id . '_base_css', [
            'type' => Controls_Manager::HIDDEN,
            'default' => 'inline-flex',
            'selectors' => ['{{WRAPPER}} ' . $selector => 'display: inline-flex; align-items: center; justify-content: center; text-decoration: none; font-weight: 600;']
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), ['name' => $id . '_typo', 'selector' => '{{WRAPPER}} ' . $selector]);

        $this->start_controls_tabs($id . '_state_tabs');

        // NORMAL
        $this->start_controls_tab($id . '_n', ['label' => 'Normal']);
        $this->add_control($id . '_bg', ['label' => 'Background', 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} ' . $selector => 'background-color: {{VALUE}};']]);
        $this->add_control($id . '_color', ['label' => 'Text Color', 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} ' . $selector => 'color: {{VALUE}};']]);
        $this->add_group_control(Group_Control_Border::get_type(), ['name' => $id . '_border', 'selector' => '{{WRAPPER}} ' . $selector]);
        $this->end_controls_tab();

        // HOVER
        $this->start_controls_tab($id . '_h', ['label' => 'Hover']);
        $this->add_control($id . '_bg_h', ['label' => 'Background', 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} ' . $selector . ':hover' => 'background-color: {{VALUE}};']]);
        $this->add_control($id . '_color_h', ['label' => 'Text Color', 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} ' . $selector . ':hover' => 'color: {{VALUE}};']]);
        $this->add_control($id . '_trans', ['label' => 'Transition (ms)', 'type' => Controls_Manager::NUMBER, 'default' => 300, 'selectors' => ['{{WRAPPER}} ' . $selector => 'transition: all {{VALUE}}ms cubic-bezier(0.4, 0, 0.2, 1);']]);
        $this->end_controls_tab();

        // ACTIVE (The Click State)
        $this->start_controls_tab($id . '_a', ['label' => 'Active']);
        $this->add_control($id . '_scale', ['label' => 'Click Scale', 'type' => Controls_Manager::SLIDER, 'range' => ['px' => ['min' => 0.8, 'max' => 1.2, 'step' => 0.01]], 'selectors' => ['{{WRAPPER}} ' . $selector . ':active' => 'transform: scale({{SIZE}});']]);
        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control($id . '_padding', ['label' => 'Padding', 'type' => Controls_Manager::DIMENSIONS, 'selectors' => ['{{WRAPPER}} ' . $selector => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']]);
        $this->add_responsive_control($id . '_radius', ['label' => 'Corner Radius', 'type' => Controls_Manager::DIMENSIONS, 'selectors' => ['{{WRAPPER}} ' . $selector => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']]);

        $this->end_controls_section();
    }
    protected function render()
    {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="cora-unit-container cora-button-group-wrapper">
            <a href="<?php echo esc_url($settings['pri_link']['url']); ?>" class="cora-btn btn-pri">
                <span><?php echo esc_html($settings['pri_text']); ?></span>
                <?php if (!empty($settings['pri_icon']['value'])): ?>
                    <span class="btn-icon"
                        style="margin-left: 10px; display: flex; align-items: center;"><?php \Elementor\Icons_Manager::render_icon($settings['pri_icon']); ?></span>
                <?php endif; ?>
            </a>
            <a href="<?php echo esc_url($settings['sec_link']['url']); ?>" class="cora-btn btn-sec">
                <span><?php echo esc_html($settings['sec_text']); ?></span>
                <?php if (!empty($settings['sec_icon']['value'])): ?>
                    <span class="btn-icon"
                        style="margin-left: 10px; display: flex; align-items: center;"><?php \Elementor\Icons_Manager::render_icon($settings['sec_icon']); ?></span>
                <?php endif; ?>
            </a>
        </div>
        <?php
    }
}