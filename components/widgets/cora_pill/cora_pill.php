<?php
namespace Cora_Builder\Components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;




if (!defined('ABSPATH'))
    exit;

class Cora_Pill extends Base_Widget
{

    public function get_name()
    {
        return 'cora_pill';
    }
    public function get_title()
    {
        return __('Cora Pill', 'cora-builder');
    }
    public function get_icon()
    {
        return 'eicon-info-box';
    }

    protected function register_controls()
    {

        // --- CONTENT SECTION ---
        $this->start_controls_section('pill_content', [
            'label' => __('Pill Content', 'cora-builder'),
        ]);

        $this->add_control('text', [
            'label' => __('Badge Text', 'cora-builder'),
            'type' => Controls_Manager::TEXT,
            'default' => __('New Resources Added daily!!', 'cora-builder'),
            'dynamic' => ['active' => true], // Requirement: Dynamic Tagging
        ]);

        $this->add_responsive_control('align', [
            'label' => __('Alignment', 'cora-builder'),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'left' => ['title' => 'Left', 'icon' => 'eicon-text-align-left'],
                'center' => ['title' => 'Center', 'icon' => 'eicon-text-align-center'],
                'right' => ['title' => 'Right', 'icon' => 'eicon-text-align-right'],
            ],
            'selectors' => ['{{WRAPPER}}' => 'text-align: {{VALUE}};'],
            'devices' => ['desktop', 'tablet', 'mobile'], // Requirement: Viewport level control
        ]);

        $this->end_controls_section();

        // --- STYLE SECTION ---
        $this->start_controls_section('pill_style', [
            'label' => __('Pill Style', 'cora-builder'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'typography',
                'selector' => '{{WRAPPER}} .cora-catalyst-pill',
            ]
        );

        $this->add_control('dot_color', [
            'label' => __('Pulse Dot Color', 'cora-builder'),
            'type' => Controls_Manager::COLOR,
            'default' => '#94a3b8',
            'selectors' => ['{{WRAPPER}} .pulse-dot' => 'background-color: {{VALUE}};'],
        ]);

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="cora-catalyst-pill">
            <span class="pulse-dot"></span>
            <?php echo esc_html($settings['text']); ?>
        </div>
        <?php
    }
}