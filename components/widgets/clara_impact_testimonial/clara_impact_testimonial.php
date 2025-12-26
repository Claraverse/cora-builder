<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

class Clara_Impact_Testimonial extends Base_Widget {

    public function get_name() { return 'clara_impact_testimonial'; }
    public function get_title() { return __( 'Clara Impact & Testimonial', 'clara-builder' ); }

    protected function register_controls() {
        
        // --- TAB: CONTENT - IMPACT METRICS ---
        $this->start_controls_section('metrics', [ 'label' => 'Performance Metrics' ]);
        $this->add_control('m1_val', [ 'label' => 'Stat 1 Value', 'type' => Controls_Manager::TEXT, 'default' => '90%' ]);
        $this->add_control('m1_label', [ 'label' => 'Stat 1 Label', 'type' => Controls_Manager::TEXT, 'default' => 'Total Sales Increase' ]);
        $this->add_control('m2_val', [ 'label' => 'Stat 2 Value', 'type' => Controls_Manager::TEXT, 'default' => '5/5' ]);
        $this->add_control('m2_label', [ 'label' => 'Stat 2 Label', 'type' => Controls_Manager::TEXT, 'default' => 'Technical Performance' ]);
        $this->end_controls_section();

        // --- TAB: CONTENT - TESTIMONIAL ---
        $this->start_controls_section('testimonial', [ 'label' => 'Client Feedback' ]);
        $this->add_control('feedback', [ 'label' => 'Quote', 'type' => Controls_Manager::TEXTAREA, 'default' => 'Claraverse delivered a massive 90% increase...' ]);
        $this->add_control('client_img', [ 'label' => 'Client Photo', 'type' => Controls_Manager::MEDIA ]);
        $this->add_control('client_name', [ 'label' => 'Name & Role', 'type' => Controls_Manager::TEXT, 'default' => 'VP of Engineering' ]);
        $this->end_controls_section();

        $this->register_common_spatial_controls(); 
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="cora-unit-container impact-hub-wrapper">
            <div class="impact-grid">
                <div class="metric-card-slate">
                    <div class="m-unit">
                        <strong><?php echo esc_html($settings['m1_val']); ?></strong>
                        <span><?php echo esc_html($settings['m1_label']); ?></span>
                    </div>
                    <div class="m-unit">
                        <strong><?php echo esc_html($settings['m2_val']); ?></strong>
                        <span><?php echo esc_html($settings['m2_label']); ?></span>
                    </div>
                </div>

                <div class="testimonial-canvas">
                    <div class="t-icon"><i class="fas fa-quote-left"></i></div>
                    <p class="t-quote"><?php echo esc_html($settings['feedback']); ?></p>
                    <div class="t-footer">
                        <img src="<?php echo esc_url($settings['client_img']['url']); ?>" class="t-avatar">
                        <span class="t-meta"><?php echo esc_html($settings['client_name']); ?></span>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}