<?php
namespace Cora_Builder\components;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Logo_Marquee extends Widget_Base {

    public function get_name() { return 'cora_logo_marquee'; }
    public function get_title() { return __( 'Cora Logo Marquee', 'cora-builder' ); }
    public function get_icon() { return 'eicon-slider-push'; }
    public function get_categories() { return [ 'cora-components' ]; }

    protected function register_controls() {

        // --- HEADLINE ---
        $this->start_controls_section('content_section', ['label' => 'Headline & Logos']);

        $this->add_control('text_prefix', [
            'label' => 'Text Prefix',
            'type' => Controls_Manager::TEXT,
            'default' => 'Trusted by',
        ]);

        $this->add_control('text_accent', [
            'label' => 'Green Accent',
            'type' => Controls_Manager::TEXT,
            'default' => '50+ Shopify Stores',
        ]);

        $this->add_control('text_suffix', [
            'label' => 'Text Suffix',
            'type' => Controls_Manager::TEXT,
            'default' => 'Worldwide',
        ]);

        // --- LOGO REPEATER ---
        $repeater = new Repeater();

        $repeater->add_control('logo_image', [
            'label' => 'Logo Image',
            'type' => Controls_Manager::MEDIA,
            'default' => [ 'url' => Utils::get_placeholder_image_src() ],
        ]);

        $this->add_control('logos', [
            'label' => 'Client Logos',
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                ['logo_image' => ['url' => Utils::get_placeholder_image_src()]],
                ['logo_image' => ['url' => Utils::get_placeholder_image_src()]],
                ['logo_image' => ['url' => Utils::get_placeholder_image_src()]],
                ['logo_image' => ['url' => Utils::get_placeholder_image_src()]],
                ['logo_image' => ['url' => Utils::get_placeholder_image_src()]],
            ],
        ]);

        $this->end_controls_section();

        // --- SETTINGS ---
        $this->start_controls_section('style_section', ['label' => 'Animation Settings']);

        $this->add_control('scroll_speed', [
            'label' => 'Animation Duration (s)',
            'type' => Controls_Manager::NUMBER,
            'default' => 30,
            'min' => 5,
            'max' => 100,
            'description' => 'Higher number = Slower speed',
            'selectors' => [ '{{WRAPPER}} .cm-track' => 'animation-duration: {{VALUE}}s;' ],
        ]);

        $this->add_control('logo_height', [
            'label' => 'Logo Height',
            'type' => Controls_Manager::SLIDER,
            'range' => [ 'px' => [ 'min' => 20, 'max' => 100 ] ],
            'default' => [ 'size' => 32, 'unit' => 'px' ],
            'selectors' => [ '{{WRAPPER}} .cm-logo img' => 'height: {{SIZE}}{{UNIT}};' ],
        ]);

        $this->add_control('grayscale', [
            'label' => 'Grayscale Logos?',
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        // We duplicate the logos array to create the seamless loop effect
        $logos = $settings['logos'];
        $merged_logos = array_merge($logos, $logos); 
        
        $grayscale_class = ('yes' === $settings['grayscale']) ? 'cm-gray' : '';
        ?>

        <style>
            .cm-wrapper {
                width: 100%;
                text-align: center;
                padding: 40px 0;
                overflow: hidden; /* Hide scrollbars */
                font-family: 'Inter', sans-serif;
            }

            /* Headline */
            .cm-headline {
                font-size: clamp(20px, 4vw, 24px);
                font-weight: 700;
                color: #0f172a;
                margin-bottom: 40px;
            }
            .cm-accent {
                color: #22c55e; /* Shopify Green */
            }

            /* Marquee Container */
            .cm-marquee {
                position: relative;
                width: 100%;
                max-width: 100%;
                display: flex;
                overflow: hidden;
                /* Fade masks on edges */
                mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);
                -webkit-mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);
            }

            /* The Scrolling Track */
            .cm-track {
                display: flex;
                align-items: center;
                gap: 60px; /* Space between logos */
                white-space: nowrap;
                animation: cm-scroll linear infinite;
                /* Duration set by control */
            }

            /* Individual Logo */
            .cm-logo {
                flex-shrink: 0;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .cm-logo img {
                width: auto;
                /* Height set by control */
                object-fit: contain;
                display: block;
            }

            /* Grayscale Option */
            .cm-gray img {
                filter: grayscale(100%) opacity(0.8);
                transition: filter 0.3s;
            }
            .cm-gray img:hover {
                filter: grayscale(0%) opacity(1);
            }

            /* Keyframes */
            @keyframes cm-scroll {
                0% { transform: translateX(0); }
                100% { transform: translateX(-50%); } /* Move half way because content is doubled */
            }

            /* Pause on Hover */
            .cm-marquee:hover .cm-track {
                animation-play-state: paused;
            }
            
            @media (max-width: 768px) {
                .cm-track { gap: 40px; }
            }
        </style>

        <div class="cm-wrapper">
            
            <h3 class="cm-headline">
                <?php echo esc_html($settings['text_prefix']); ?> 
                <span class="cm-accent"><?php echo esc_html($settings['text_accent']); ?></span> 
                <?php echo esc_html($settings['text_suffix']); ?>
            </h3>

            <div class="cm-marquee">
                <div class="cm-track <?php echo $grayscale_class; ?>">
                    <?php foreach ( $merged_logos as $index => $logo ) : ?>
                        <?php if ( ! empty( $logo['logo_image']['url'] ) ) : ?>
                            <div class="cm-logo elementor-repeater-item-<?php echo $logo['_id']; ?>">
                                <img src="<?php echo esc_url($logo['logo_image']['url']); ?>" alt="Client Logo">
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>
        <?php
    }
}