<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Infinite_Carousel extends Base_Widget {

    public function get_name() { return 'cora_infinite_carousel'; }
    public function get_title() { return __( 'Cora Infinite Carousel', 'cora-builder' ); }

    protected function register_controls() {
        
        $this->start_controls_section('content', [ 'label' => 'Carousel Items' ]);

        $repeater = new Repeater();
        $repeater->add_control('image', [
            'label' => 'Gallery Image',
            'type' => Controls_Manager::MEDIA,
            'dynamic' => ['active' => true],
            'default' => [ 'url' => Utils::get_placeholder_image_src() ], // Placeholder
        ]);

        $this->add_control('slides', [
            'label' => 'Showcase Slides',
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [ ['image' => ''], ['image' => ''], ['image' => ''], ['image' => ''] ],
        ]);

        $this->end_controls_section();

        // --- TAB: STYLE - MOTION & SPATIAL ---
        $this->start_controls_section('style_motion', [ 'label' => 'Motion & Layout', 'tab' => Controls_Manager::TAB_STYLE ]);
        
        $this->add_control('scroll_speed', [
            'label' => 'Scroll Duration (Seconds)',
            'type' => Controls_Manager::NUMBER,
            'min' => 5, 'max' => 60, 'default' => 30,
            'selectors' => ['{{WRAPPER}} .carousel-track' => 'animation-duration: {{VALUE}}s;'],
        ]);

        $this->add_responsive_control('item_gap', [
            'label' => 'Gap Between Items',
            'type' => Controls_Manager::SLIDER,
            'selectors' => ['{{WRAPPER}} .carousel-track' => 'gap: {{SIZE}}{{UNIT}} !important;'],
        ]);

        $this->add_responsive_control('card_radius', [
            'label' => 'Corner Radius',
            'type' => Controls_Manager::SLIDER,
            'selectors' => ['{{WRAPPER}} .carousel-item img' => 'border-radius: {{SIZE}}{{UNIT}} !important;'],
            'default' => ['size' => 24],
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $slides = $settings['slides'];
        ?>
        <div class="cora-infinite-carousel-wrapper">
            <div class="carousel-track">
                <?php 
                // Render original slides
                foreach ( $slides as $slide ) : ?>
                    <div class="carousel-item">
                        <img src="<?php echo esc_url($slide['image']['url']); ?>" alt="Showcase">
                    </div>
                <?php endforeach; ?>
                
                <?php 
                // Duplicate slides for infinite loop logic
                foreach ( $slides as $slide ) : ?>
                    <div class="carousel-item" aria-hidden="true">
                        <img src="<?php echo esc_url($slide['image']['url']); ?>" alt="Showcase Dupe">
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
}