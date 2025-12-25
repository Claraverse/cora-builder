<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Testimonial_Block extends Base_Widget {

    public function get_name() { return 'cora_testimonial_block'; }
    public function get_title() { return __( 'Cora Testimonial Block', 'cora-builder' ); }

    protected function register_controls() {
        
        // --- TAB: CONTENT ---
        $this->start_controls_section('content', [ 'label' => __( 'Client Feedback', 'cora-builder' ) ]);
        
        $this->add_control('bg_image', [ 'label' => 'Client Photo', 'type' => Controls_Manager::MEDIA ]);
        $this->add_control('category', [ 'label' => 'Industry/Category', 'type' => Controls_Manager::TEXT, 'default' => 'Personal Care' ]);
        
        $this->add_control('testimonial', [
            'label' => 'Testimonial Text',
            'type' => Controls_Manager::TEXTAREA,
            'default' => 'Beautiful new site—simplified bookings and guests love the serene online vibe!',
            'dynamic' => ['active' => true]
        ]);

        $this->add_control('client_name', [ 'label' => 'Client Name', 'type' => Controls_Manager::TEXT, 'default' => 'Dr. Omar Khalid' ]);
        $this->add_control('client_role', [ 'label' => 'Client Role', 'type' => Controls_Manager::TEXT, 'default' => 'Marketing Manager' ]);

        $this->end_controls_section();

        // Style Engines: Typo & Layout
        $this->register_text_styling_controls('cat_style', 'Category Typography', '{{WRAPPER}} .testi-category');
        $this->register_common_spatial_controls(); 
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="cora-unit-container cora-testimonial-wrapper" style="background-image: url('<?php echo esc_url($settings['bg_image']['url']); ?>');">
            <div class="testimonial-glass-card">
                <h4 class="testi-category"><?php echo esc_html($settings['category']); ?></h4>
                <p class="testi-content"><?php echo esc_html($settings['testimonial']); ?></p>
                <div class="testi-meta">
                    <span class="name"><?php echo esc_html($settings['client_name']); ?></span>
                    <span class="role"><?php echo esc_html($settings['client_role']); ?></span>
                </div>
            </div>
        </div>
        <?php
    }
}