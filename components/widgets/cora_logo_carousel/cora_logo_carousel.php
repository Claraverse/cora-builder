<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Logo_Carousel extends Base_Widget {

    public function get_name() { return 'cora_logo_carousel'; }
    public function get_title() { return __( 'Cora Logo Carousel', 'cora-builder' ); }

    protected function register_controls() {
        
        // --- TAB: CONTENT - LOGO HUB ---
        $this->start_controls_section('content', [ 'label' => __( 'Client Logos', 'cora-builder' ) ]);
        
        $repeater = new Repeater();
        $repeater->add_control('client_logo', [ 'label' => 'Logo Image', 'type' => Controls_Manager::MEDIA ]);
        $repeater->add_control('client_name', [ 'label' => 'Client Name', 'type' => Controls_Manager::TEXT, 'default' => 'Client' ]);

        $this->add_control('logos', [
            'label' => 'Logo List',
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                ['client_name' => 'Spectrum'],
                ['client_name' => 'Enigma'],
                ['client_name' => 'Synergy'],
            ],
        ]);

        $this->add_control('speed', [
            'label' => 'Scroll Speed (Seconds)',
            'type' => Controls_Manager::NUMBER,
            'default' => 40,
        ]);

        $this->end_controls_section();
        $this->register_common_spatial_controls(); 
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $logos = $settings['logos'];
        ?>
        <div class="cora-unit-container logo-carousel-wrapper">
            <div class="logo-track" style="animation-duration: <?php echo esc_attr($settings['speed']); ?>s;">
                <?php 
                // Render the loop twice for a seamless infinite effect
                for ($i = 0; $i < 2; $i++) : 
                    foreach ($logos as $logo) : ?>
                        <div class="logo-item">
                            <img src="<?php echo esc_url($logo['client_logo']['url']); ?>" alt="<?php echo esc_attr($logo['client_name']); ?>">
                            <span class="logo-text"><?php echo esc_html($logo['client_name']); ?></span>
                        </div>
                    <?php endforeach; 
                endfor; ?>
            </div>
        </div>
        <?php
    }
}