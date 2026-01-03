<?php
namespace Cora_Builder\components;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Portfolio_Showcase extends Widget_Base {

    public function get_name() { return 'cora_portfolio_showcase'; }
    public function get_title() { return __( 'Cora Portfolio Showcase', 'cora-builder' ); }
    public function get_icon() { return 'eicon-image'; }
    public function get_categories() { return [ 'cora-components' ]; }

    protected function register_controls() {

        // --- CONTENT ---
        $this->start_controls_section('content_section', ['label' => 'Card Content']);

        $this->add_control('image', [
            'label' => 'Project Image',
            'type' => Controls_Manager::MEDIA,
            'default' => ['url' => Utils::get_placeholder_image_src()],
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('title', [
            'label' => 'Project Name',
            'type' => Controls_Manager::TEXT,
            'default' => 'Real Estate Platforms',
            'label_block' => true,
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('desc', [
            'label' => 'Short Description',
            'type' => Controls_Manager::TEXTAREA,
            'default' => 'Property listings, lead capture, and virtual tour interfaces.',
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('link', [
            'label' => 'Link',
            'type' => Controls_Manager::URL,
            'placeholder' => 'https://your-link.com',
            'default' => [ 'url' => '#' ],
            'dynamic' => ['active' => true],
        ]);

        $this->end_controls_section();

        // --- STYLE ---
        $this->start_controls_section('style_section', ['label' => 'Card Design']);

        $this->add_responsive_control('card_height', [
            'label' => 'Card Height',
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'vh' ],
            'range' => [ 'px' => [ 'min' => 300, 'max' => 900 ] ],
            'default' => [ 'unit' => 'px', 'size' => 550 ],
            'selectors' => [ '{{WRAPPER}} .cora-single-card' => 'height: {{SIZE}}{{UNIT}};' ],
        ]);

        $this->add_control('border_radius', [
            'label' => 'Border Radius',
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range' => [ 'px' => [ 'min' => 0, 'max' => 60 ] ],
            'default' => [ 'unit' => 'px', 'size' => 32 ],
            'selectors' => [ '{{WRAPPER}} .cora-single-card' => 'border-radius: {{SIZE}}{{UNIT}};' ],
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $this->add_render_attribute( 'card_wrapper', 'class', 'cora-single-card' );
        if ( ! empty( $settings['link']['url'] ) ) {
            $this->add_link_attributes( 'card_wrapper', $settings['link'] );
        }
        
        // Tag can be div or a depending on if link exists
        $tag = ! empty( $settings['link']['url'] ) ? 'a' : 'div';
        ?>

        <style>
            .cora-single-card {
                position: relative;
                width: 100%;
                overflow: hidden;
                display: block;
                text-decoration: none;
                background: #f1f5f9;
                /* Shadows for depth */
                box-shadow: 0 10px 30px rgba(0,0,0,0.04);
                transition: transform 0.4s cubic-bezier(0.25, 1, 0.5, 1), box-shadow 0.4s ease;
            }

            .cora-single-card:hover {
                transform: translateY(-8px);
                box-shadow: 0 25px 50px rgba(0,0,0,0.12);
            }

            /* Image Logic */
            .cora-card-bg {
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: transform 0.7s ease;
                display: block;
            }

            .cora-single-card:hover .cora-card-bg {
                transform: scale(1.05); /* Smooth Zoom */
            }

            /* Gradient Overlay */
            .cora-card-overlay {
                position: absolute;
                inset: 0;
                background: linear-gradient(
                    to top, 
                    rgba(0, 0, 0, 0.85) 0%, 
                    rgba(0, 0, 0, 0.5) 35%, 
                    rgba(0, 0, 0, 0) 70%
                );
                display: flex;
                flex-direction: column;
                justify-content: flex-end;
                padding: 20px;
                z-index: 2;
            }

            /* Typography */
            .cora-card-title {
                font-family: 'Inter', sans-serif;
                font-size: 28px;
                font-weight: 850;
                color: #ffffff;
                margin: 0 0 12px 0;
                line-height: 1.1;
                letter-spacing: -0.02em;
                text-shadow: 0 2px 10px rgba(0,0,0,0.2);
            }

            .cora-card-desc {
                font-family: 'Inter', sans-serif;
                font-size: 16px;
                color: rgba(255, 255, 255, 0.9);
                line-height: 1.5;
                font-weight: 500;
                margin: 0;
            }

            /* Mobile Adjustments */
            @media (max-width: 767px) {
                .cora-single-card { height: 450px !important; border-radius: 24px !important; }
                .cora-card-overlay { padding: 30px; }
                .cora-card-title { font-size: 24px; }
            }
        </style>

        <<?php echo $tag; ?> <?php echo $this->get_render_attribute_string( 'card_wrapper' ); ?>>
            <?php if ( !empty($settings['image']['url']) ) : ?>
                <img class="cora-card-bg" src="<?php echo esc_url($settings['image']['url']); ?>" alt="<?php echo esc_attr($settings['title']); ?>">
            <?php endif; ?>
            
            <div class="cora-card-overlay">
                <h3 class="cora-card-title"><?php echo esc_html($settings['title']); ?></h3>
                <p class="cora-card-desc"><?php echo esc_html($settings['desc']); ?></p>
            </div>
        </<?php echo $tag; ?>>

        <?php
    }
}