<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Others_Charge extends Base_Widget {

    public function get_name() { return 'cora_others_charge'; }
    public function get_title() { return __( 'Cora Competitive Card', 'cora-builder' ); }

    protected function register_controls() {
        
        // --- TAB: CONTENT ---
        $this->start_controls_section('content', [ 'label' => 'Comparison Data' ]);
        
        $this->add_control('icon', [ 
            'label' => 'Top Left Icon', 
            'type' => Controls_Manager::MEDIA, 
            'default' => [ 'url' => Utils::get_placeholder_image_src() ],
        ]);

        $this->add_control('title', [ 
            'label' => 'Headline', 
            'type' => Controls_Manager::TEXT, 
            'default' => 'Others Charge...', 
            'label_block' => true
        ]);

        $this->add_control('subline', [ 
            'label' => 'Subline', 
            'type' => Controls_Manager::TEXTAREA, 
            'default' => "You've probably paid too much for something too generic.",
            'rows' => 3
        ]);

        $this->add_control('price_tag', [ 
            'label' => 'Price (Strikethrough)', 
            'type' => Controls_Manager::TEXT, 
            'default' => '$999+', 
        ]);

        $this->add_control('price_desc', [ 
            'label' => 'Price Description', 
            'type' => Controls_Manager::TEXT, 
            'default' => 'Just for a fancy homepage', 
            'label_block' => true
        ]);

        $this->add_control('main_img', [
            'label' => 'Illustration',
            'type' => Controls_Manager::MEDIA,
            'default' => [ 'url' => Utils::get_placeholder_image_src() ],
        ]);

        // --- BLUR FEATURE ---
        $this->add_control('enable_blur', [
            'label' => 'Blur Price Until Hover?',
            'type' => Controls_Manager::SWITCHER,
            'label_on' => 'Yes',
            'label_off' => 'No',
            'return_value' => 'yes',
            'default' => 'no',
            'separator' => 'before',
        ]);

        $this->end_controls_section();

        // --- TAB: STYLE ---
        $this->start_controls_section('style_spatial', [ 'label' => 'Layout & Spacing', 'tab' => Controls_Manager::TAB_STYLE ]);
        
        $this->add_responsive_control('card_padding', [ 
            'label' => 'Container Padding', 
            'type' => Controls_Manager::DIMENSIONS, 
            'size_units' => [ 'px', '%', 'em' ],
            'selectors' => [
                '{{WRAPPER}} .cora-comp-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
            ],
            'default' => [
                'top' => '40', 'right' => '40', 'bottom' => '40', 'left' => '40', 'unit' => 'px', 'isLinked' => true
            ]
        ]);
        
        $this->add_responsive_control('canvas_gap', [ 
            'label' => 'Gap Below Header', 
            'type' => Controls_Manager::SLIDER, 
            'range' => [ 'px' => [ 'min' => 0, 'max' => 100 ] ],
            'default' => [ 'size' => 40, 'unit' => 'px' ],
            'selectors' => ['{{WRAPPER}} .comp-header' => 'margin-bottom: {{SIZE}}{{UNIT}};'] 
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        $icon_url = !empty($settings['icon']['url']) ? $settings['icon']['url'] : '';
        $main_img_url = !empty($settings['main_img']['url']) ? $settings['main_img']['url'] : '';
        $blur_class = ($settings['enable_blur'] === 'yes') ? 'blur-active' : '';
        ?>

        <style>
            /* --- 1. Main Container --- */
            .cora-comp-container {
                width: 100%;
                background: #ffffff;
                    border: 1px solid #e0e0e0;
    border-radius: 20px;
                box-sizing: border-box;
                /* Padding handled by Elementor controls */
            }

            /* --- 2. Header Section --- */
            .comp-header {
                display: flex;
                align-items: flex-start;
                gap: 20px;
                /* Margin bottom handled by Elementor controls */
            }

            .comp-icon-box {
                width: 60px;
                height: 60px;
                background: #f1f5f9;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0; /* Prevents icon squishing */
            }

            .comp-icon-box img {
                width: 50%;
                height: auto;
                display: block;
            }

            .comp-header-text {
                flex: 1;
            }

            /* Typography: Clamp scales font smoothly between viewports */
            .comp-h2 {
                margin: 0 0 8px 0;
                font-family: 'Inter', sans-serif; /* Ensure you have a good font loaded */
                font-size: clamp(24px, 5vw, 32px); 
                font-weight: 800;
                color: #0f172a;
                line-height: 1.1;
                letter-spacing: -0.02em;
            }

            .comp-p {
                margin: 0;
                font-family: 'Inter', sans-serif;
                font-size: clamp(16px, 3vw, 18px);
                color: #475569;
                font-weight: 500;
                line-height: 1.5;
            }

            /* --- 3. Gray Canvas Area --- */
            .comp-canvas {
                position: relative;
                width: 100%;
                background: #f8fafc; /* Very light gray */
                border-radius: 32px;
                min-height: 380px;
                padding: 40px;
                border: 1px solid #f1f5f9;
                box-sizing: border-box;
                
                /* Layout Logic */
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                overflow: hidden;
            }

            /* --- 4. Pricing Typography --- */
            .comp-pricing-stack {
                text-align: center;
                z-index: 10; /* Always above illustration */
                position: relative;
                transition: filter 0.5s ease, opacity 0.5s ease;
            }

            .comp-price {
                margin: 0 0 10px 0;
                font-family: 'Inter', sans-serif;
                font-size: clamp(48px, 12vw, 84px); /* Huge range for impact */
                font-weight: 900;
                color: #0f172a;
                letter-spacing: -3px;
                line-height: 1;
                position: relative;
                display: inline-block;
            }

            /* The Strikethrough Line */
            .comp-price::after {
                content: '';
                position: absolute;
                left: -5%;
                top: 55%; /* Slightly lower than center looks more natural */
                width: 110%;
                height: 5px;
                background: #0f172a;
                transform: rotate(-6deg);
                border-radius: 4px;
            }

            .comp-price-label {
                margin: 0;
                font-family: 'Inter', sans-serif;
                font-size: clamp(18px, 4vw, 24px);
                font-weight: 700;
                color: #0f172a;
                letter-spacing: -0.5px;
            }

            /* --- 5. Illustration Logic --- */
            .comp-illustration {
                position: absolute;
                bottom: 0;
                left: 40px;
                width: 250px;
                z-index: 1; /* Behind text */
                pointer-events: none;
            }

            .comp-illustration img {
                width: 100%;
                height: auto;
                display: block;
            }

            /* --- 6. Blur Effect Logic --- */
            .cora-comp-container.blur-active .comp-pricing-stack {
                filter: blur(12px);
                opacity: 0.6;
            }
            .cora-comp-container.blur-active:hover .comp-pricing-stack {
                filter: blur(0px);
                opacity: 1;
            }

            /* --- 7. RESPONSIVE OPTIMIZATION --- */
            
            /* Tablet (Landscape) & Below < 1024px */
            @media (max-width: 1024px) {
                .comp-canvas {
                    min-height: auto;
                    padding: 50px 20px 50px 20px; /* Top padding, no bottom padding yet */
                    justify-content: flex-start;
                }
                
                .comp-illustration {
                    position: relative; /* Stop floating */
                    left: auto;
                    bottom: auto;
                    width: 200px;
                    margin-top: 30px;
                    margin-bottom: -10px; /* Tuck it into the bottom edge */
                    align-self: center;
                }
            }

            /* Mobile < 767px */
            @media (max-width: 767px) {
                .comp-header {
                    align-items: center; /* Center align items vertically */
                    gap: 15px;
                }
                
                .comp-icon-box {
                    width: 50px;
                    height: 50px;
                }
                
                .comp-canvas {
                    border-radius: 24px; /* Slightly tighter radius on mobile */
                    padding: 40px 20px 50px 20px;
                }

                .comp-price {
                    letter-spacing: -1px; /* Less tight on small screens */
                }

                .comp-price::after {
                    height: 3px; /* Thinner line for smaller text */
                }
            }
        </style>
        <div class="cora-comp-container <?php echo esc_attr($blur_class); ?>">
            
            <div class="comp-header">
                <?php if ($icon_url) : ?>
                <div class="comp-icon-box">
                    <img src="<?php echo esc_url($icon_url); ?>" alt="Icon">
                </div>
                <?php endif; ?>
                
                <div class="comp-header-text">
                    <h2 class="comp-h2"><?php echo esc_html($settings['title']); ?></h2>
                    <p class="comp-p"><?php echo esc_html($settings['subline']); ?></p>
                </div>
            </div>

            <div class="comp-canvas">
                <div class="comp-pricing-stack">
                    <h3 class="comp-price"><?php echo esc_html($settings['price_tag']); ?></h3>
                    <p class="comp-price-label"><?php echo esc_html($settings['price_desc']); ?></p>
                </div>
                
                <?php if ($main_img_url) : ?>
                <div class="comp-illustration">
                    <img src="<?php echo esc_url($main_img_url); ?>" alt="Comparison Visual">
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
}