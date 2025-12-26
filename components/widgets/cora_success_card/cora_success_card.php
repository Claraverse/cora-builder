<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Success_Card extends Base_Widget {

    public function get_name() { return 'cora_success_card'; }
    public function get_title() { return __( 'Cora Success Card', 'cora-builder' ); }

    protected function register_controls() {
        
        // --- TAB: CONTENT ---
        $this->start_controls_section('content', [ 'label' => 'Client Data' ]);
        
        $this->add_control('bg_img', [
            'label' => 'Client/Project Image',
            'type' => Controls_Manager::MEDIA,
            'dynamic' => ['active' => true],
            'default' => [ 'url' => Utils::get_placeholder_image_src() ], // Placeholder
        ]);

        $this->add_control('logo', [
            'label' => 'Brand Logo (White)',
            'type' => Controls_Manager::MEDIA,
            'dynamic' => ['active' => true],
            'default' => [ 'url' => Utils::get_placeholder_image_src() ],
        ]);

        $this->add_control('quote', [
            'label' => 'Testimonial',
            'type' => Controls_Manager::TEXTAREA,
            'dynamic' => ['active' => true],
            'default' => '"We have all of our tasks in one place."',
        ]);

        $this->add_control('name', [ 'label' => 'Client Name', 'type' => Controls_Manager::TEXT, 'dynamic' => ['active' => true], 'default' => 'Briana Conetta' ]);
        $this->add_control('role', [ 'label' => 'Client Role', 'type' => Controls_Manager::TEXT, 'dynamic' => ['active' => true], 'default' => 'Sr. Event Production Manager' ]);

        $this->end_controls_section();

        // --- TAB: STYLE - SPATIAL & VISUAL ---
        $this->start_controls_section('style_card', [ 'label' => 'Card Appearance', 'tab' => Controls_Manager::TAB_STYLE ]);

        $this->add_responsive_control('card_height', [
            'label' => 'Card Height',
            'type' => Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 300, 'max' => 800]],
            'default' => ['size' => 455], // Matching reference
            'selectors' => ['{{WRAPPER}} .cora-success-card' => 'height: {{SIZE}}{{UNIT}} !important;'],
        ]);

        $this->add_responsive_control('card_radius', [
            'label' => 'Corner Radius',
            'type' => Controls_Manager::SLIDER,
            'selectors' => ['{{WRAPPER}} .cora-success-card' => 'border-radius: {{SIZE}}{{UNIT}} !important;'],
            'default' => ['size' => 24],
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="cora-success-card" style="background-image: url('<?php echo esc_url($settings['bg_img']['url']); ?>');">
            <div class="card-overlay"></div>
            <div class="card-content-stack">
                <img src="<?php echo esc_url($settings['logo']['url']); ?>" class="card-brand-logo">
                <p class="card-quote-text"><?php echo esc_html($settings['quote']); ?></p>
                <div class="card-client-meta">
                    <strong><?php echo esc_html($settings['name']); ?></strong>
                    <span><?php echo esc_html($settings['role']); ?></span>
                </div>
            </div>
        </div>
        <?php
    }
}