<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Project_Challenge extends Base_Widget {

    public function get_name() { return 'cora_project_challenge'; }
    public function get_title() { return __( 'Cora Project Challenge', 'cora-builder' ); }

    protected function register_controls() {
        
        // --- TAB: CONTENT - NARRATIVE ---
        $this->start_controls_section('content', [ 'label' => __( 'Project Narrative', 'cora-builder' ) ]);
        $this->add_control('headline', [ 
            'label' => 'Headline', 
            'type' => Controls_Manager::TEXT, 
            'default' => 'Reimagining a New York Dining Experience...',
            'dynamic' => ['active' => true] 
        ]);
        $this->add_control('description', [ 
            'label' => 'Project Description', 
            'type' => Controls_Manager::TEXTAREA, 
            'default' => 'Nestled in the heart of Manhattan...',
            'dynamic' => ['active' => true]
        ]);
        $this->end_controls_section();

        // --- TAB: CONTENT - PACKAGE CARD ---
        $this->start_controls_section('card', [ 'label' => __( 'Package Card', 'cora-builder' ) ]);
        $this->add_control('package_img', [ 'label' => 'Package Preview Image', 'type' => Controls_Manager::MEDIA ]);
        $this->add_control('package_name', [ 'label' => 'Package Name', 'type' => Controls_Manager::TEXT, 'default' => 'Cora Restaurant Booster' ]);
        $this->end_controls_section();

        $this->register_common_spatial_controls(); 
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="cora-unit-container project-challenge-wrapper">
            <div class="narrative-col">
                <h2 class="narrative-h2"><?php echo esc_html($settings['headline']); ?></h2>
                <p class="narrative-p"><?php echo esc_html($settings['description']); ?></p>
            </div>

            <div class="package-card-col">
                <div class="package-media-wrapper">
                    <img src="<?php echo esc_url($settings['package_img']['url']); ?>" alt="Selected Package">
                    <div class="package-glass-overlay">
                        <div class="glass-text">
                            <span class="label">Selected Package</span>
                            <span class="val"><?php echo esc_html($settings['package_name']); ?></span>
                        </div>
                        <a href="#" class="glass-btn">Book Now</a>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}