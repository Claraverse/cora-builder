<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Project_Challenge extends Base_Widget {

    public function get_name() { return 'cora_project_challenge'; }
    public function get_title() { return __( 'Cora Project Challenge', 'cora-builder' ); }
    public function get_icon() { return 'eicon-featured-image'; }

    protected function register_controls() {
        
        // --- CONTENT: NARRATIVE ---
        $this->start_controls_section('content', [ 'label' => __( 'Project Narrative', 'cora-builder' ) ]);
        
        $this->add_control('headline', [ 
            'label' => 'Headline', 
            'type' => Controls_Manager::TEXTAREA, 
            'default' => 'Reimagining a New York Dining Experience',
            'dynamic' => ['active' => true] 
        ]);
        
        $this->add_control('description', [ 
            'label' => 'Project Description', 
            'type' => Controls_Manager::TEXTAREA, 
            'default' => 'Nestled in the heart of Manhattan, this project required a complete digital overhaul to match the physical elegance of the dining space. We focused on seamless reservations and visual storytelling.',
            'dynamic' => ['active' => true]
        ]);
        
        $this->end_controls_section();

        // --- CONTENT: PACKAGE CARD ---
        $this->start_controls_section('card', [ 'label' => __( 'Package Card', 'cora-builder' ) ]);
        
        $this->add_control('package_img', [ 
            'label' => 'Package Image', 
            'type' => Controls_Manager::MEDIA,
            'default' => ['url' => Utils::get_placeholder_image_src()]
        ]);
        
        $this->add_control('package_label', [ 
            'label' => 'Label Text', 
            'type' => Controls_Manager::TEXT, 
            'default' => 'Selected Package' 
        ]);

        $this->add_control('package_name', [ 
            'label' => 'Package Name', 
            'type' => Controls_Manager::TEXT, 
            'default' => 'Cora Restaurant Booster' 
        ]);

        $this->add_control('btn_text', [ 
            'label' => 'Button Text', 
            'type' => Controls_Manager::TEXT, 
            'default' => 'Book Now' 
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <style>
            /* --- Wrapper & Layout --- */
            .cpc-wrapper {
                display: flex;
                align-items: center;
                gap: clamp(40px, 5vw, 80px); /* Fluid Gap */
                padding: clamp(40px, 5vw, 80px) 0;
                font-family: 'Inter', sans-serif;
            }

            .cpc-col-text { 
                flex: 1.5; 
            }
            
            .cpc-col-card { 
                flex: 1; 
                display: flex; 
                justify-content: flex-end; 
            }

            /* --- Typography --- */
            .cpc-h2 { 
                margin: 0 0 24px 0;
                font-size: clamp(26px, 3vw, 36px); /* Fluid Type */
                font-weight: 850; 
                color: #0f172a; 
                line-height: 1.2;
                position: relative;
                display: inline-block;
               
            }

            /* Signature Underline */
           

            .cpc-p { 
                margin: 0; 
                font-size: clamp(15px, 1.5vw, 17px); 
                color: #475569; 
                line-height: 1.7; 
                max-width: 650px;
            }

            /* --- Package Glass Card --- */
            .cpc-card-wrap {
                width: 100%;
                max-width: 420px;
                aspect-ratio: 4/3; /* Consistent shape */
                position: relative;
                border-radius: 24px;
                overflow: hidden;
                box-shadow: 0 25px 50px -12px rgba(0,0,0,0.15);
                background: #e2e8f0; /* Fallback color */
            }

            .cpc-bg-img { 
                width: 100%; 
                height: 100%; 
                object-fit: cover; 
                transition: transform 0.5s ease;
            }
            .cpc-card-wrap:hover .cpc-bg-img { transform: scale(1.05); }

            .cpc-glass-overlay {
                position: absolute;
                bottom: 16px; left: 16px; right: 16px;
                background: rgba(15, 23, 42, 0.6); /* Darker slate for contrast */
                backdrop-filter: blur(12px);
                -webkit-backdrop-filter: blur(12px);
                border: 1px solid rgba(255, 255, 255, 0.1);
                border-radius: 16px;
                padding: 16px 20px;
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 12px;
                color: #fff;
            }

            .cpc-g-text { flex: 1; }
            .cpc-g-label { display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; opacity: 0.7; margin-bottom: 2px; letter-spacing: 0.5px; }
            .cpc-g-val { display: block; font-size: 14px; font-weight: 700; line-height: 1.3; }

            .cpc-g-btn {
                background: rgba(255, 255, 255, 0.15);
                border: 1px solid rgba(255, 255, 255, 0.2);
                padding: 10px 18px;
                border-radius: 100px;
                color: #fff;
                font-size: 12px;
                font-weight: 700;
                text-decoration: none;
                white-space: nowrap;
                transition: background 0.3s;
            }
            .cpc-g-btn:hover { background: rgba(255, 255, 255, 0.3); }

            /* --- RESPONSIVE LOGIC --- */
            
            /* Tablet & Mobile Stack */
            @media (max-width: 1024px) {
                .cpc-wrapper { 
                    flex-direction: column; 
                    gap: 40px; 
                    text-align: center;
                }
                
                .cpc-col-text, .cpc-col-card { width: 100%; }
                
                .cpc-h2 { margin-bottom: 20px; }
                .cpc-h2::after { left: 50%; transform: translateX(-50%); width: 60px; } /* Center underline */
                
                .cpc-p { margin: 0 auto; }
                
                .cpc-col-card { justify-content: center; }
            }

            /* Small Mobile Adjustments */
            @media (max-width: 480px) {
                .cpc-glass-overlay { 
                    flex-direction: column; 
                    align-items: flex-start; 
                    gap: 12px;
                }
                .cpc-g-btn { width: 100%; text-align: center; }
            }
        </style>

        <div class="cora-unit-container cpc-wrapper">
            
            <div class="cpc-col-text">
                <h2 class="cpc-h2"><?php echo esc_html($settings['headline']); ?></h2>
                <p class="cpc-p"><?php echo esc_html($settings['description']); ?></p>
            </div>

            <div class="cpc-col-card">
                <div class="cpc-card-wrap">
                    <?php if(!empty($settings['package_img']['url'])) : ?>
                        <img class="cpc-bg-img" src="<?php echo esc_url($settings['package_img']['url']); ?>" alt="Package">
                    <?php endif; ?>
                    
                    <div class="cpc-glass-overlay">
                        <div class="cpc-g-text">
                            <span class="cpc-g-label"><?php echo esc_html($settings['package_label']); ?></span>
                            <span class="cpc-g-val"><?php echo esc_html($settings['package_name']); ?></span>
                        </div>
                        <a href="#" class="cpc-g-btn"><?php echo esc_html($settings['btn_text']); ?></a>
                    </div>
                </div>
            </div>

        </div>
        <?php
    }
}