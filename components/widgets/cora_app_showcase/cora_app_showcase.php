<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_App_Showcase extends Base_Widget {

    public function get_name() { return 'cora_app_showcase'; }
    public function get_title() { return __( 'Cora App Showcase', 'cora-builder' ); }

    protected function register_controls() {
        
        // --- TAB: CONTENT - DEVICE SCREENS ---
        $this->start_controls_section('content', [ 'label' => __( 'App Screens', 'cora-builder' ) ]);
        $this->add_control('center_screen', [ 'label' => 'Main Center Screen', 'type' => Controls_Manager::MEDIA ]);
        $this->add_control('left_screen', [ 'label' => 'Left Accent Screen', 'type' => Controls_Manager::MEDIA ]);
        $this->add_control('right_screen', [ 'label' => 'Right Accent Screen', 'type' => Controls_Manager::MEDIA ]);
        $this->end_controls_section();

        // --- TAB: CONTENT - FEATURE MARQUEE ---
        $this->start_controls_section('features_sec', [ 'label' => __( 'Feature Marquee', 'cora-builder' ) ]);
        $repeater = new Repeater();
        $repeater->add_control('f_icon', [ 'label' => 'Icon', 'type' => Controls_Manager::ICONS ]);
        $repeater->add_control('f_label', [ 'label' => 'Feature Label', 'type' => Controls_Manager::TEXT, 'default' => 'Time Tracking' ]);

        $this->add_control('features', [
            'label' => 'Feature List',
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                ['f_label' => 'Time Tracking'],
                ['f_label' => 'Chat'],
                ['f_label' => 'Dashboard'],
            ],
        ]);
        $this->end_controls_section();

        $this->register_common_spatial_controls(); 
    }

    protected function render() {
    $settings = $this->get_settings_for_display();
    
    // Fetch URLs for the Mockups
    $left_url   = !empty($settings['left_screen']['url']) ? $settings['left_screen']['url'] : '';
    $center_url = !empty($settings['center_screen']['url']) ? $settings['center_screen']['url'] : '';
    $right_url  = !empty($settings['right_screen']['url']) ? $settings['right_screen']['url'] : '';
    
    $features = $settings['features'];
    ?>
    <div class="cora-unit-container app-showcase-wrapper">
        <div class="device-mockup-stack">
            <div class="device side left"><img src="<?php echo esc_url($left_url); ?>"></div>
            <div class="device center"><img src="<?php echo esc_url($center_url); ?>"></div>
            <div class="device side right"><img src="<?php echo esc_url($right_url); ?>"></div>
        </div>

        <div class="feature-marquee-bar">
            <div class="marquee-inner">
                <?php 
                // Render the list twice for a seamless infinite loop
                for ($i = 0; $i < 2; $i++) : 
                    foreach ($features as $f) : ?>
                        <div class="marquee-item">
                            <div class="m-icon">
                                <?php \Elementor\Icons_Manager::render_icon( $f['f_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                            </div>
                            <span><?php echo esc_html($f['f_label']); ?></span>
                        </div>
                    <?php endforeach; 
                endfor; ?>
            </div>
        </div>
    </div>
    <?php
}
}