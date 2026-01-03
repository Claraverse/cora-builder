<?php
namespace Cora_Builder\Components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Infinite_Carousel extends Base_Widget {

    public function get_name() { return 'cora_infinite_carousel'; }
    public function get_title() { return __( 'Cora Infinite Carousel', 'cora-builder' ); }
    public function get_icon() { return 'eicon-autoplay'; }

    protected function register_controls() {
        
        // --- CONTENT: SLIDES ---
        $this->start_controls_section('content', [ 'label' => 'Carousel Items' ]);

        $repeater = new Repeater();
        $repeater->add_control('image', [
            'label' => 'Gallery Image',
            'type' => Controls_Manager::MEDIA,
            'dynamic' => ['active' => true],
            'default' => [ 'url' => Utils::get_placeholder_image_src() ],
        ]);

        $this->add_control('slides', [
            'label' => 'Showcase Slides',
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [ ['image' => ''], ['image' => ''], ['image' => ''], ['image' => ''] ],
            'title_field' => 'Slide Item',
        ]);

        $this->end_controls_section();

        // --- STYLE: MOTION ENGINE ---
        $this->start_controls_section('style_motion', [ 'label' => 'Motion & Playback', 'tab' => Controls_Manager::TAB_STYLE ]);
        
        $this->add_control('scroll_speed', [
            'label' => 'Animation Speed (s)',
            'type' => Controls_Manager::NUMBER,
            'min' => 2, 'max' => 100, 'default' => 30,
            'selectors' => ['{{WRAPPER}} .carousel-track' => 'animation-duration: {{VALUE}}s;'],
        ]);

        $this->add_control('reverse_direction', [
            'label' => 'Reverse Direction',
            'type' => Controls_Manager::SWITCHER,
            'default' => 'no',
            'label_on' => 'Right',
            'label_off' => 'Left',
            'return_value' => 'reverse',
            'selectors' => ['{{WRAPPER}} .carousel-track' => 'animation-direction: {{VALUE}};'],
        ]);

        $this->add_control('pause_on_hover', [
            'label' => 'Pause on Hover',
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
            'selectors' => ['{{WRAPPER}} .cora-carousel-container:hover .carousel-track' => 'animation-play-state: paused;'],
        ]);

        $this->end_controls_section();

        // --- STYLE: SPATIAL ENGINE ---
        $this->start_controls_section('style_spatial', [ 'label' => 'Layout & Sizing', 'tab' => Controls_Manager::TAB_STYLE ]);

        $this->add_control('edge_to_edge', [
            'label' => 'Force Edge-to-Edge',
            'type' => Controls_Manager::SWITCHER,
            'default' => 'no',
            'description' => 'Forces the carousel to span the full width of the screen (100vw) even inside a boxed section.',
        ]);

        $this->add_responsive_control('item_width', [
            'label' => 'Item Width',
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px', 'vw'],
            'range' => ['px' => ['min' => 100, 'max' => 800]],
            'default' => ['size' => 450, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .carousel-item' => 'width: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('item_height', [
            'label' => 'Item Height',
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 50, 'max' => 600]],
            'default' => ['size' => 300, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .carousel-item' => 'height: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('item_gap', [
            'label' => 'Gap Between Items',
            'type' => Controls_Manager::SLIDER,
            'default' => ['size' => 40],
            'selectors' => ['{{WRAPPER}} .carousel-track' => 'gap: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('card_radius', [
            'label' => 'Corner Radius',
            'type' => Controls_Manager::SLIDER,
            'default' => ['size' => 24],
            'selectors' => ['{{WRAPPER}} .carousel-item img' => 'border-radius: {{SIZE}}{{UNIT}};'],
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $slides = $settings['slides'];
        $id = $this->get_id();
        
        $is_full_width = 'yes' === $settings['edge_to_edge'];

        if ( empty( $slides ) ) return;
        ?>

        <style>
            .cora-carousel-container-<?php echo $id; ?> {
                /* Layout Logic */
                <?php if ($is_full_width) : ?>
                width: 100vw;
                margin-left: calc(50% - 50vw);
                margin-right: calc(50% - 50vw);
                max-width: 100vw;
                <?php else : ?>
                width: 100%;
                <?php endif; ?>
                
                overflow: hidden;
                padding: 40px 0;
                background: transparent;
                position: relative;
                left: 0;
            }

            .cora-carousel-container-<?php echo $id; ?> .carousel-track {
                display: flex;
                width: max-content;
                animation: cora-infinite-scroll linear infinite;
                animation-duration: <?php echo esc_attr($settings['scroll_speed']); ?>s;
            }

            .cora-carousel-container-<?php echo $id; ?> .carousel-item {
                flex-shrink: 0;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .cora-carousel-container-<?php echo $id; ?> .carousel-item img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                border: 1px solid #f1f5f9;
                box-shadow: 0 10px 30px rgba(0,0,0,0.05);
                transition: transform 0.3s ease;
                /* Border Radius handled by control */
            }

            @keyframes cora-infinite-scroll {
                from { transform: translateX(0); }
                to { transform: translateX(-50%); }
            }

            /* --- Responsive Defaults (Overrideable by sliders) --- */
            @media (max-width: 1024px) {
                .cora-carousel-container-<?php echo $id; ?> .carousel-item { 
                    /* Fallbacks if sliders are unset, though sliders set inline styles */
                    width: 350px; 
                    height: 230px; 
                }
            }
            @media (max-width: 767px) {
                .cora-carousel-container-<?php echo $id; ?> .carousel-item { 
                    width: 280px; 
                    height: 180px; 
                }
            }
        </style>

        <div class="cora-unit-container cora-carousel-container cora-carousel-container-<?php echo $id; ?>">
            <div class="carousel-track">
                <?php 
                // Double loop to ensure smooth infinite scroll
                for ($i = 0; $i < 2; $i++) :
                    foreach ( $slides as $slide ) : ?>
                        <div class="carousel-item" <?php echo ($i > 0) ? 'aria-hidden="true"' : ''; ?>>
                            <?php if ( !empty($slide['image']['url']) ) : ?>
                                <img src="<?php echo esc_url($slide['image']['url']); ?>" alt="Showcase">
                            <?php endif; ?>
                        </div>
                    <?php endforeach;
                endfor; ?>
            </div>
        </div>
        <?php
    }
}