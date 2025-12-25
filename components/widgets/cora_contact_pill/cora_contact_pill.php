<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Contact_Pill extends Base_Widget {

    public function get_name() { return 'cora_contact_pill'; }
    public function get_title() { return __( 'Cora Contact Pill', 'cora-builder' ); }

    protected function register_controls() {
        
        // --- TAB: CONTENT ---
        $this->start_controls_section('content', [ 'label' => __( 'Contact Info', 'cora-builder' ) ]);
        
        $this->add_control('label', [
            'label'   => 'Action Label',
            'type'    => Controls_Manager::TEXT,
            'default' => 'You can email us here',
            'dynamic' => ['active' => true]
        ]);

        $this->add_control('email', [
            'label'   => 'Email Address',
            'type'    => Controls_Manager::TEXT,
            'default' => 'hello@claraverse.in',
            'dynamic' => ['active' => true]
        ]);

        $this->add_control('link', [
            'label'   => 'Mailto Link',
            'type'    => Controls_Manager::URL,
            'placeholder' => 'mailto:hello@claraverse.in',
            'dynamic' => ['active' => true]
        ]);

        $this->end_controls_section();

        // Style & Layout Core
        $this->register_text_styling_controls('email_style', 'Email Typography', '{{WRAPPER}} .action-email');
        $this->register_common_spatial_controls(); 
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="cora-unit-container contact-pill-wrapper">
            <a <?php echo $this->get_render_attribute_string('link'); ?> class="contact-pill-inner">
                
                <div class="pill-icon-box">
                    <i class="far fa-envelope"></i>
                </div>

                <div class="pill-content">
                    <span class="action-label"><?php echo esc_html($settings['label']); ?></span>
                    <span class="action-email"><?php echo esc_html($settings['email']); ?></span>
                </div>

                <div class="pill-arrow-box">
                    <i class="fas fa-arrow-up"></i>
                </div>

            </a>
        </div>
        <?php
    }
}