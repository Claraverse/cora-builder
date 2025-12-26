<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Utils;

if (!defined('ABSPATH'))
    exit;

class Cora_Integrations_Orbit extends Base_Widget
{

    public function get_name()
    {
        return 'cora_integrations_orbit';
    }
    public function get_title()
    {
        return __('Cora Rotating Orbit', 'cora-builder');
    }

    protected function register_controls()
    {

        // --- TAB: CONTENT ---
        $this->start_controls_section('content', ['label' => 'Headlines & Media']);
        $this->add_control('top_title', ['label' => 'Top Title', 'type' => Controls_Manager::TEXT, 'dynamic' => ['active' => true], 'default' => 'Endless possibilities.']);

        $this->add_control('center_logo', [
            'label' => 'Central Hub Logo',
            'type' => Controls_Manager::MEDIA,
            'dynamic' => ['active' => true],
            'default' => ['url' => Utils::get_placeholder_image_src()], // Placeholder
        ]);

        $repeater = new Repeater();
        $repeater->add_control('icon', [
            'label' => 'Icon',
            'type' => Controls_Manager::MEDIA,
            'dynamic' => ['active' => true],
            'default' => ['url' => Utils::get_placeholder_image_src()], // Placeholder
        ]);

        $this->add_control('orbit_icons', [
            'label' => 'Orbiting Icons',
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [['icon' => ''], ['icon' => ''], ['icon' => ''], ['icon' => '']],
        ]);
        $this->end_controls_section();

        // --- TAB: STYLE - ANIMATION ---
        $this->start_controls_section('style_animation', ['label' => 'Orbit Animation', 'tab' => Controls_Manager::TAB_STYLE]);

        $this->add_control('rotate_speed', [
            'label' => 'Rotation Speed (Seconds)',
            'type' => Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 5, 'max' => 60]],
            'default' => ['size' => 20],
            'selectors' => ['{{WRAPPER}} .orbit-container' => 'animation-duration: {{SIZE}}s;'],
        ]);

        $this->add_control('pause_on_hover', [
            'label' => 'Pause on Hover',
            'type' => Controls_Manager::SWITCHER,
            'label_on' => 'Yes',
            'label_off' => 'No',
            'return_value' => 'paused',
            'selectors' => ['{{WRAPPER}} .orbit-visual-hub:hover .orbit-container, {{WRAPPER}} .orbit-visual-hub:hover .node-inner' => 'animation-play-state: {{VALUE}};'],
        ]);

        $this->end_controls_section();

        // --- TAB: STYLE - LAYOUT ---
        $this->start_controls_section('style_layout', ['label' => 'Layout & Spacing', 'tab' => Controls_Manager::TAB_STYLE]);
        $this->add_responsive_control('orbit_radius', [
            'label' => 'Orbit Radius',
            'type' => Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 100, 'max' => 500]],
            'default' => ['size' => 225],
            'selectors' => ['{{WRAPPER}} .orbit-visual-hub' => '--orbit-radius: {{SIZE}}px !important;'],
        ]);
        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $icon_count = count($settings['orbit_icons']);
        ?>
        <div class="cora-orbit-wrapper">
            <h2 class="orbit-h2-top"><?php echo $settings['top_title']; ?></h2>

            <div class="orbit-visual-hub">
                <div class="orbit-container">
                    <div class="orbit-path-line"></div>

                    <div class="orbit-center-node">
                        <img src="<?php echo esc_url($settings['center_logo']['url']); ?>" alt="Hub">
                    </div>

                    <?php foreach ($settings['orbit_icons'] as $index => $item):
                        $angle = ($index / $icon_count) * 360;
                        ?>
                        <div class="orbit-node" style="--start-angle: <?php echo $angle; ?>deg;">
                            <div class="node-inner">
                                <img src="<?php echo esc_url($item['icon']['url']); ?>" alt="Icon">
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php
    }
}