<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

class Clara_Success_Hub extends Base_Widget {

    public function get_name() { return 'clara_success_hub'; }
    public function get_title() { return __( 'Clara Success Hub', 'clara-builder' ); }

    protected function register_controls() {
        
        // --- TAB: CONTENT - METRICS ---
        $this->start_controls_section('metrics', [ 'label' => 'Performance Stats' ]);
        $this->add_control('m1_val', [ 'label' => 'Metric 1 Value', 'type' => Controls_Manager::TEXT, 'default' => '90%' ]);
        $this->add_control('m1_label', [ 'label' => 'Metric 1 Label', 'type' => Controls_Manager::TEXT, 'default' => 'Task completion rate' ]);
        $this->add_control('m2_val', [ 'label' => 'Metric 2 Value', 'type' => Controls_Manager::TEXT, 'default' => '5/5' ]);
        $this->add_control('m2_label', [ 'label' => 'Metric 2 Label', 'type' => Controls_Manager::TEXT, 'default' => 'Team satisfaction score' ]);
        $this->end_controls_section();

        // --- TAB: CONTENT - TESTIMONIAL ---
        $this->start_controls_section('testimonial', [ 'label' => 'Client Feedback' ]);
        $this->add_control('client_logo', [ 'label' => 'Brand Logo', 'type' => Controls_Manager::MEDIA ]);
        $this->add_control('quote', [ 'label' => 'Testimonial Text', 'type' => Controls_Manager::TEXTAREA, 'default' => 'Claraverse helped us recover 25% of abandoned carts...' ]);
        $this->add_control('client_name', [ 'label' => 'Name', 'type' => Controls_Manager::TEXT, 'default' => 'Ivy' ]);
        $this->add_control('client_role', [ 'label' => 'Role/Location', 'type' => Controls_Manager::TEXT, 'default' => 'Ivy Boutique, Florida' ]);
        $this->end_controls_section();

        $this->register_common_spatial_controls(); 
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="cora-unit-container success-hub-wrapper">
            <div class="success-grid">
                <div class="stat-slate">
                    <div class="stat-unit">
                        <h2 class="stat-h2"><?php echo esc_html($settings['m1_val']); ?></h2>
                        <strong class="stat-strong"><?php echo esc_html($settings['m1_label']); ?></strong>
                        <p class="stat-p">Our team’s efficiency improved dramatically after using Stickky. It is amazing thing!</p>
                    </div>
                    <div class="stat-divider"></div>
                    <div class="stat-unit">
                        <h2 class="stat-h2"><?php echo esc_html($settings['m2_val']); ?></h2>
                        <strong class="stat-strong"><?php echo esc_html($settings['m2_label']); ?></strong>
                        <p class="stat-p">Our team loves the simplified workflow and communication features.</p>
                    </div>
                </div>

                <div class="quote-canvas">
                    <div class="brand-header">
                        <img src="<?php echo esc_url($settings['client_logo']['url']); ?>" class="brand-logo">
                    </div>
                    <p class="quote-text"><?php echo esc_html($settings['quote']); ?></p>
                    <div class="quote-footer">
                        <div class="avatar-circle"></div>
                        <div class="client-meta">
                            <strong><?php echo esc_html($settings['client_name']); ?></strong>
                            <span><?php echo esc_html($settings['client_role']); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}