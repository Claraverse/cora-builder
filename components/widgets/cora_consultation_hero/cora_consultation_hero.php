<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Consultation_Hero extends Base_Widget {

    public function get_name() { return 'cora_consultation_hero'; }
    public function get_title() { return __( 'Cora Consultation Hero', 'cora-builder' ); }

    protected function register_controls() {
        
        // --- TAB: CONTENT - COPY ---
        $this->start_controls_section('content', [ 'label' => __( 'Hero Copy', 'cora-builder' ) ]);
        $this->add_control('headline', [ 'label' => 'Headline', 'type' => Controls_Manager::TEXT, 'default' => 'Is your business struggling...' ]);
        $this->add_control('subline', [ 'label' => 'Subline', 'type' => Controls_Manager::TEXTAREA, 'default' => 'Book a free consultation...' ]);
        
        $this->add_control('clients_val', [ 'label' => 'Client Count', 'type' => Controls_Manager::TEXT, 'default' => '58+', 'dynamic' => ['active' => true] ]);
        $this->add_control('rating_val', [ 'label' => 'Rating', 'type' => Controls_Manager::TEXT, 'default' => '4.7', 'dynamic' => ['active' => true] ]);
        $this->end_controls_section();

        // --- TAB: CONTENT - MOCKUPS ---
        $this->start_controls_section('media', [ 'label' => __( 'Device Screens', 'cora-builder' ) ]);
        $this->add_control('front_screen', [ 'label' => 'Main Front Phone', 'type' => Controls_Manager::MEDIA ]);
        $this->add_control('back_screen', [ 'label' => 'Accent Back Phone', 'type' => Controls_Manager::MEDIA ]);
        $this->end_controls_section();

        $this->register_common_spatial_controls(); 
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="cora-unit-container consultation-hero-wrapper">
            <div class="hero-bg-glow"></div>

            <div class="hero-inner-split">
                <div class="hero-copy-col">
                    <h2 class="hero-h2"><?php echo esc_html($settings['headline']); ?></h2>
                    <p class="hero-p"><?php echo esc_html($settings['subline']); ?></p>

                    <div class="hero-metrics-row">
                        <div class="m-box">
                            <strong><?php echo esc_html($settings['clients_val']); ?></strong>
                            <span>Clients served</span>
                        </div>
                        <div class="m-box">
                            <strong><?php echo esc_html($settings['rating_val']); ?></strong>
                            <span>Rating out of 5</span>
                        </div>
                    </div>

                    <div class="hero-cta-group">
                        <a href="#" class="btn-black">Book Appointment <i class="fas fa-arrow-right"></i></a>
                        <a href="#" class="btn-white-outline">Know more</a>
                    </div>
                </div>

                <div class="hero-mockup-col">
                    <div class="phone-stack">
                        <div class="phone back"><img src="<?php echo esc_url($settings['back_screen']['url']); ?>"></div>
                        <div class="phone front"><img src="<?php echo esc_url($settings['front_screen']['url']); ?>"></div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}