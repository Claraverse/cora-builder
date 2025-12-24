<?php
namespace Cora_Builder\Elements;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

if (!defined('ABSPATH'))
    exit;

class Cora_Button extends Base_Widget
{

    public function get_name()
    {
        return 'cora_button';
    }
    public function get_title()
    {
        return __('Cora Buttone', 'cora-builder');
    }
    public function get_icon()
    {
        return 'eicon-button';
    }
    public function get_categories()
    {
        return ['cora_widgets'];
    }

    protected function register_controls()
    {
        $this->start_controls_section('section_button', [
            'label' => __('Button Content', 'cora-builder'),
        ]);

        $this->add_control('text', [
            'label' => __('Button Text', 'cora-builder'),
            'type' => Controls_Manager::TEXT,
            'default' => __('Click Here', 'cora-builder'),
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('link', [
            'label' => __('Link', 'cora-builder'),
            'type' => Controls_Manager::URL,
            'dynamic' => ['active' => true],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_button_style', [
            'label' => __('Style', 'cora-builder'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('bg_color', [
            'label' => __('Background Color', 'cora-builder'),
            'type' => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .cora-btn' => 'background-color: {{VALUE}};'],
        ]);

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $this->add_link_attributes('button_link', $settings['link']);
        ?>
        <div class="cora-button-wrapper">
            <a class="cora-btn" <?php echo $this->get_render_attribute_string('button_link'); ?>>
                <?php echo esc_html($settings['text']); ?>
            </a>
        </div>
        <?php
    }
}