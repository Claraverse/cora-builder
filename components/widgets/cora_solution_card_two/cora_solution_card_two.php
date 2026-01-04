<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Solution_Card_Two extends Base_Widget {

    public function get_name() { return 'cora_solution_card_two'; }
    public function get_title() { return __( 'Cora Solution Card Two', 'cora-builder' ); }
    public function get_icon() { return 'eicon-device-mobile'; }

    protected function register_controls() {
        
        $this->start_controls_section('content', [ 'label' => __( 'Solution Detail', 'cora-builder' ) ]);
        
        $this->add_control('mockup_img', [ 'label' => 'Mobile Screen Image', 'type' => Controls_Manager::MEDIA ]);
        
        $this->add_control('headline', [ 
            'label' => 'Headline', 
            'type' => Controls_Manager::TEXTAREA, 
            'default' => 'Direct Booking Funnel with Instant Table Reservations',
            'rows' => 2,
        ]);
        
        $this->add_control('desc', [ 
            'label' => 'Description', 
            'type' => Controls_Manager::TEXTAREA, 
            'default' => 'We built a high-converting web funnel with a sticky WhatsApp/reserve button that increased direct bookings by 40%.',
        ]);

        $this->end_controls_section();

        // --- Responsive Height Controls ---
        $this->start_controls_section('style_responsiveness', [ 'label' => 'Layout Adjustments', 'tab' => Controls_Manager::TAB_STYLE ]);

        $this->add_responsive_control('canvas_height', [
            'label' => 'Mockup Canvas Height',
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'vh' ],
            'range' => [ 'px' => [ 'min' => 200, 'max' => 600 ] ],
            'default' => [ 'unit' => 'px', 'size' => 380 ],
            'selectors' => [ '{{WRAPPER}} .solution-canvas' => 'height: {{SIZE}}{{UNIT}};' ],
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <style>
            /* --- Card Container --- */
            .solution-card {
                background: #ffffff;
                border: 1px solid #f1f5f9;
                border-radius: 32px;
                padding: clamp(12px, 3vw, 16px); /* Fluid padding */
                display: flex;
                flex-direction: column;
                gap: 32px;
                overflow: hidden;
                transition: transform 0.3s ease, box-shadow 0.3s ease;
                height: 100%;
            }

            .solution-card:hover { 
                transform: translateY(-5px); 
                box-shadow: 0 20px 40px -10px rgba(0,0,0,0.08);
            }

            /* --- Mockup Canvas --- */
            .solution-canvas {
                background: #f1f7ff; /* Soft Blue/Indigo Tint */
                background-image: radial-gradient(#cbd5e1 1px, transparent 1px);
                background-size: 20px 20px;
                border-radius: 24px;
                /* Height set by control, default 380px */
                display: flex;
                justify-content: center;
                align-items: flex-end; /* Mockup sits on the bottom edge */
                overflow: hidden;
                padding: 0 20px;
                position: relative;
            }

            /* --- iPhone Hardware Frame --- */
            .iphone-frame {
                width: 100%;
                max-width: 320px; /* Constrain width on large screens */
                border: 6px solid #1e293b; /* Slightly thinner border for elegance */
                border-bottom: none;
                border-radius: 32px 32px 0 0;
                background: #fff;
                box-shadow: 0 20px 50px rgba(0,0,0,0.12);
                line-height: 0; /* Remove image gap */
                position: relative;
                bottom: -2px; /* Hide bottom border edge cleanly */
            }

            .iphone-frame img {
                width: 100%;
                height: auto;
                display: block;
                border-radius: 26px 26px 0 0;
                object-fit: cover;
            }

            /* --- Text Content --- */
            .solution-copy { 
                padding: 0 16px 24px 16px; 
                text-align: center; 
            }

            .solution-h3 { 
                margin: 0 0 12px 0 !important; 
                font-family: 'Inter', sans-serif;
                font-size: clamp(20px, 5vw, 26px); /* Fluid Type: 20px -> 26px */
                font-weight: 800; 
                color: #1e293b; 
                line-height: 1.25;
                letter-spacing: -0.02em;
            }

            .solution-p { 
                margin: 0 !important; 
                font-family: 'Inter', sans-serif;
                font-size: clamp(14px, 4vw, 16px); /* Fluid Type: 14px -> 16px */
                color: #64748b; 
                line-height: 1.6; 
            }

            /* --- Mobile Optimizations --- */
            @media (max-width: 480px) {
                .solution-card {
                    border-radius: 24px;
                }
                .iphone-frame {
                    max-width: 85%; /* Ensure side margins on small phones */
                    border-width: 4px; /* Thinner bezel on mobile */
                    border-radius: 24px 24px 0 0;
                }
                .iphone-frame img {
                    border-radius: 20px 20px 0 0;
                }
            }
        </style>

        <div class="solution-card">
            <div class="solution-canvas">
                <div class="iphone-frame">
                    <?php if ( ! empty( $settings['mockup_img']['url'] ) ) : ?>
                        <img src="<?php echo esc_url($settings['mockup_img']['url']); ?>" alt="Mockup">
                    <?php endif; ?>
                </div>
            </div>

            <div class="solution-copy">
                <h3 class="solution-h3"><?php echo esc_html($settings['headline']); ?></h3>
                <p class="solution-p"><?php echo esc_html($settings['desc']); ?></p>
            </div>
        </div>
        <?php
    }
}