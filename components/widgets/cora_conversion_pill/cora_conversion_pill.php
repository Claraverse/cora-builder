<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Conversion_Pill extends Base_Widget {

    public function get_name() { return 'cora_conversion_pill'; }
    public function get_title() { return __( 'Cora Conversion Pill', 'cora-builder' ); }

    protected function register_controls() {
        
        // --- TAB: CONTENT - CLIENT PROOF ---
        $this->start_controls_section('content', [ 'label' => __( 'Social Proof', 'cora-builder' ) ]);
        
        $repeater = new Repeater();
        $repeater->add_control('client_img', [ 'label' => 'Avatar', 'type' => Controls_Manager::MEDIA ]);

        $this->add_control('avatars', [
            'label' => 'Client Avatars',
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [ [], [], [] ], // 3 default placeholders
        ]);

        $this->add_control('proof_text', [
            'label' => 'Proof Label',
            'type' => Controls_Manager::TEXT,
            'default' => 'Join 460+ Clients',
            'dynamic' => ['active' => true]
        ]);

        $this->add_control('btn_text', [ 'label' => 'Button Text', 'type' => Controls_Manager::TEXT, 'default' => 'Book Now' ]);
        $this->add_control('btn_link', [ 'label' => 'Button Link', 'type' => Controls_Manager::URL, 'dynamic' => ['active' => true] ]);
        
        $this->end_controls_section();

        $this->register_common_spatial_controls(); 
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="cora-unit-container cora-conv-pill">
            <div class="pill-inner">
                <div class="avatar-stack">
                    <?php foreach ($settings['avatars'] as $avatar) : ?>
                        <div class="avatar-circle">
                            <img src="<?php echo esc_url($avatar['client_img']['url']); ?>">
                        </div>
                    <?php endforeach; ?>
                </div>

                <span class="proof-label"><?php echo esc_html($settings['proof_text']); ?></span>

                <a href="<?php echo esc_url($settings['btn_link']['url']); ?>" class="pill-btn">
                    <?php echo esc_html($settings['btn_text']); ?>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
        <?php
    }
}