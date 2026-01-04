<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Consultation_Hero extends Base_Widget {

    public function get_name() { return 'cora_consultation_hero'; }
    public function get_title() { return __( 'Cora Consultation Hero', 'cora-builder' ); }
    public function get_icon() { return 'eicon-device-mobile'; }

    protected function register_controls() {
        
        // --- CONTENT: COPY ---
        $this->start_controls_section('content', [ 'label' => __( 'Hero Copy', 'cora-builder' ) ]);
        $this->add_control('headline', [ 
            'label' => 'Headline', 
            'type' => Controls_Manager::TEXTAREA, 
            'default' => 'Is your business struggling with similar problems...?',
            'rows' => 2,
        ]);
        $this->add_control('subline', [ 
            'label' => 'Subline', 
            'type' => Controls_Manager::TEXTAREA, 
            'default' => 'Book a free consultation with our experts and Break out the matrix to scale your business.',
        ]);
        
        $this->add_control('clients_val', [ 'label' => 'Client Count', 'type' => Controls_Manager::TEXT, 'default' => '58+' ]);
        $this->add_control('rating_val', [ 'label' => 'Rating', 'type' => Controls_Manager::TEXT, 'default' => '4.7' ]);
        
        // Buttons
        $this->add_control('btn_primary_text', [ 'label' => 'Primary Button', 'type' => Controls_Manager::TEXT, 'default' => 'Book Appointment' ]);
        $this->add_control('btn_secondary_text', [ 'label' => 'Secondary Button', 'type' => Controls_Manager::TEXT, 'default' => 'Know more' ]);
        $this->end_controls_section();

        // --- CONTENT: MOCKUPS ---
        $this->start_controls_section('media', [ 'label' => __( 'Device Screens', 'cora-builder' ) ]);
        $this->add_control('front_screen', [ 'label' => 'Front Phone Screen', 'type' => Controls_Manager::MEDIA, 'default' => ['url' => Utils::get_placeholder_image_src()] ]);
        $this->add_control('back_screen', [ 'label' => 'Back Phone Screen', 'type' => Controls_Manager::MEDIA, 'default' => ['url' => Utils::get_placeholder_image_src()] ]);
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <style>
            /* --- WRAPPER & ARCHITECTURE --- */
            .cch-wrapper {
                background: #f1f7ff; /* Brand Soft Blue */
                border-radius: 40px;
                padding: clamp(40px, 6vw, 80px); /* Fluid padding */
                position: relative;
                overflow: hidden;
                font-family: 'Inter', sans-serif;
            }

            .cch-bg-glow {
                position: absolute;
                right: -10%; top: 50%; transform: translateY(-50%);
                width: 60vw; height: 60vw;
                background: radial-gradient(circle, rgba(59, 130, 246, 0.15) 0%, rgba(255, 255, 255, 0) 70%);
                z-index: 1;
                pointer-events: none;
            }

            .cch-split {
                display: flex;
                align-items: center;
                gap: clamp(40px, 5vw, 80px);
                position: relative;
                z-index: 5;
            }

            /* --- LEFT: COPY COLUMN --- */
            .cch-copy-col { flex: 1.1; }

            .cch-h2 {
                margin: 0 0 24px 0;
                font-size: clamp(32px, 4vw, 56px); /* Fluid Type */
                font-weight: 850;
                color: #0f172a;
                line-height: 1.1;
                letter-spacing: -0.02em;
            }

            .cch-p {
                margin: 0 0 32px 0;
                font-size: clamp(16px, 1.5vw, 20px);
                color: #64748b;
                line-height: 1.6;
                max-width: 500px;
            }

            /* Metrics */
            .cch-metrics { display: flex; gap: 40px; margin-bottom: 40px; }
            .cch-m-unit strong { display: block; font-size: clamp(32px, 3vw, 42px); font-weight: 800; color: #0f172a; line-height: 1; }
            .cch-m-unit span { font-size: 14px; color: #64748b; font-weight: 600; margin-top: 4px; display: block; }

            /* Buttons */
            .cch-actions { display: flex; gap: 16px; flex-wrap: wrap; }
            
            .cch-btn {
                padding: 16px 32px;
                border-radius: 100px;
                font-weight: 700;
                text-decoration: none;
                display: inline-flex;
                align-items: center;
                gap: 8px;
                transition: transform 0.2s;
                font-size: 15px;
            }
            .cch-btn:hover { transform: translateY(-2px); }

            .cch-btn-dark { background: #0f172a; color: #fff; }
            .cch-btn-light { background: #fff; color: #0f172a; border: 1px solid #e2e8f0; }

            /* --- RIGHT: MOCKUP STACK --- */
            .cch-media-col { flex: 1; position: relative; display: flex; justify-content: center; }
            
            .cch-phone-stack {
                position: relative;
                width: 100%;
                max-width: 500px;
                height: 450px; /* Constrain height for overlapping */
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .cch-phone {
                position: absolute;
                width: clamp(200px, 25vw, 280px); /* Fluid Width */
                aspect-ratio: 9/19;
                border-radius: 36px;
                background: #fff;
                border: 6px solid #1e293b;
                overflow: hidden;
                box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
            }
            .cch-phone img { width: 100%; height: 100%; object-fit: cover; }

            /* Rotations based on design */
            .cch-phone.back { transform: rotate(-8deg) translateX(-40px); z-index: 1; opacity: 0.9; }
            .cch-phone.front { transform: rotate(8deg) translateX(40px); z-index: 2; margin-top: 40px; }

            /* --- RESPONSIVE BREAKPOINTS --- */
            @media (max-width: 1024px) {
                .cch-split { flex-direction: column; text-align: center; gap: 60px; }
                .cch-p { margin-left: auto; margin-right: auto; }
                .cch-metrics { justify-content: center; }
                .cch-actions { justify-content: center; }
                
                .cch-phone-stack { height: 400px; }
            }

            @media (max-width: 600px) {
                .cch-wrapper { padding: 40px 24px; border-radius: 24px; }
                .cch-actions { flex-direction: column; width: 100%; }
                .cch-btn { justify-content: center; width: 100%; }
                
                /* Tweak phones for small mobile */
                .cch-phone { width: 180px; border-width: 4px; border-radius: 24px; }
                .cch-phone.back { transform: rotate(-6deg) translateX(-20px); }
                .cch-phone.front { transform: rotate(6deg) translateX(20px); }
            }
        </style>

        <div class="cora-unit-container cch-wrapper">
            <div class="cch-bg-glow"></div>

            <div class="cch-split">
                
                <div class="cch-copy-col">
                    <h2 class="cch-h2"><?php echo esc_html($settings['headline']); ?></h2>
                    <p class="cch-p"><?php echo esc_html($settings['subline']); ?></p>

                    <div class="cch-metrics">
                        <div class="cch-m-unit">
                            <strong><?php echo esc_html($settings['clients_val']); ?></strong>
                            <span>Clients served</span>
                        </div>
                        <div class="cch-m-unit">
                            <strong><?php echo esc_html($settings['rating_val']); ?></strong>
                            <span>Rating out of 5</span>
                        </div>
                    </div>

                    <div class="cch-actions">
                        <a href="#" class="cch-btn cch-btn-dark">
                            <?php echo esc_html($settings['btn_primary_text']); ?> 
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                        </a>
                        <a href="#" class="cch-btn cch-btn-light">
                            <?php echo esc_html($settings['btn_secondary_text']); ?>
                        </a>
                    </div>
                </div>

                <div class="cch-media-col">
                    <div class="cch-phone-stack">
                        <?php if(!empty($settings['back_screen']['url'])) : ?>
                            <div class="cch-phone back"><img src="<?php echo esc_url($settings['back_screen']['url']); ?>"></div>
                        <?php endif; ?>
                        
                        <?php if(!empty($settings['front_screen']['url'])) : ?>
                            <div class="cch-phone front"><img src="<?php echo esc_url($settings['front_screen']['url']); ?>"></div>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>
        <?php
    }
}