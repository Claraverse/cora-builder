<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit;

class Clara_Impact_Testimonial extends Base_Widget {

    public function get_name() { return 'clara_impact_testimonial'; }
    public function get_title() { return __( 'Clara Impact & Testimonial', 'clara-builder' ); }
    public function get_icon() { return 'eicon-blockquote'; }

    protected function register_controls() {
        
        // --- METRICS SECTION (Left Card) ---
        $this->start_controls_section('metrics_section', [ 'label' => 'Metrics (Left Card)' ]);
        
        $this->add_control('m1_val', [ 'label' => 'Stat 1 Value', 'type' => Controls_Manager::TEXT, 'default' => '90%' ]);
        $this->add_control('m1_label', [ 'label' => 'Stat 1 Label', 'type' => Controls_Manager::TEXT, 'default' => 'Task completion rate' ]);
        $this->add_control('m1_desc', [ 'label' => 'Stat 1 Desc', 'type' => Controls_Manager::TEXTAREA, 'default' => 'Our team’s efficiency improved dramatically after using Stickky.' ]);
        
        $this->add_control('m2_val', [ 'label' => 'Stat 2 Value', 'type' => Controls_Manager::TEXT, 'default' => '5/5' ]);
        $this->add_control('m2_label', [ 'label' => 'Stat 2 Label', 'type' => Controls_Manager::TEXT, 'default' => 'Team satisfaction score' ]);
        $this->add_control('m2_desc', [ 'label' => 'Stat 2 Desc', 'type' => Controls_Manager::TEXTAREA, 'default' => 'Our team loves the simplified workflow and communication features.' ]);
        
        $this->end_controls_section();

        // --- TESTIMONIAL SECTION (Right Card) ---
        $this->start_controls_section('testimonial_section', [ 'label' => 'Testimonial (Right Card)' ]);
        
        $this->add_control('company_logo', [ 'label' => 'Company Logo', 'type' => Controls_Manager::MEDIA, 'default' => [ 'url' => Utils::get_placeholder_image_src() ] ]);
        
        $this->add_control('quote_title', [ 
            'label' => 'Main Quote', 
            'type' => Controls_Manager::TEXTAREA, 
            'default' => 'Claraverse helped us recover 25% of abandoned carts and launch a mobile-first store.' 
        ]);
        
        $this->add_control('quote_body', [ 
            'label' => 'Detailed Feedback', 
            'type' => Controls_Manager::TEXTAREA, 
            'default' => 'As a growing startup, we needed a tool that could scale with us. The advanced features made our transition seamless.' 
        ]);

        $this->add_control('client_img', [ 'label' => 'Client Photo', 'type' => Controls_Manager::MEDIA, 'default' => [ 'url' => Utils::get_placeholder_image_src() ] ]);
        $this->add_control('client_name', [ 'label' => 'Client Name', 'type' => Controls_Manager::TEXT, 'default' => 'Ivy' ]);
        $this->add_control('client_role', [ 'label' => 'Client Role/Loc', 'type' => Controls_Manager::TEXT, 'default' => 'Ivy Boutique, Florida' ]);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <style>
            /* --- WRAPPER --- */
            .cit-wrapper {
                padding: 20px 0;
                font-family: 'Inter', sans-serif;
            }

            .cit-grid {
                display: grid;
                grid-template-columns: 0.7fr 1.3fr; /* Slate card is narrower */
                gap: 30px;
                align-items: stretch;
            }

            /* --- LEFT CARD: SLATE METRICS --- */
            .cit-metric-card {
                background-color: #5c6b7f; /* Matching the Slate Blue */
                border-radius: 32px;
                padding: 48px;
                display: flex;
                flex-direction: column;
                justify-content: center;
                gap: 40px;
                color: #ffffff;
            }

            .cit-stat-block {
                display: flex;
                flex-direction: column;
                gap: 12px;
            }

            .cit-val {
                font-size: clamp(42px, 4vw, 56px);
                font-weight: 700;
                line-height: 1;
            }

            .cit-label {
                font-size: 18px;
                font-weight: 700;
                opacity: 0.95;
            }

            .cit-desc {
                font-size: 15px;
                line-height: 1.5;
                opacity: 0.8;
                max-width: 90%;
            }

            /* Divider Line */
            .cit-divider {
                width: 100%;
                height: 1px;
                background-color: rgba(255,255,255,0.15);
            }

            /* --- RIGHT CARD: CREAM TESTIMONIAL --- */
            .cit-testi-card {
                background-color: #fdfbf7; /* Matching Cream/Beige */
                border-radius: 32px;
                padding: 20px;
                display: flex;
                flex-direction: column;
                justify-content: start;
                gap: 40px;
                position: relative;
                overflow: hidden;
            }

            /* Subtle Logo Header */
            .cit-logo {
                height: 32px;
                width: auto;
                object-fit: contain;
                align-self: flex-start;
                margin-bottom: 20px;
            }

            /* Typography */
            .cit-quote-main {
                font-family: 'Inter', sans-serif;
                font-size: clamp(22px, 2.5vw, 32px); /* Responsive scaling */
                font-weight: 500;
                color: #5c6b7f; /* Slate text color */
                margin: 0;
                padding: 0;
                line-height: 1.3;
                letter-spacing: -0.02em;
            }

            .cit-quote-body {
                font-size: 18px;
                margin: 0;
                line-height: 1.6;
                color: #4b5563;
            }

            /* Footer Profile */
            .cit-profile {
                display: flex;
                align-items: center;
                gap: 16px;
                margin-top: 0;
            }
            .elementor img {
    border: none;
    border-radius: 100px;
    box-shadow: none;
    height: auto;
    max-width: 100%;
}
            .cit-avatar {
                width: 56px;
                height: 56px;
                border-radius: 100px;
                object-fit: cover;
                border: 2px solid #5c6b7f;
            }
            .cit-meta {
                display: flex;
                flex-direction: column;
                line-height: 1.4;
            }
            .cit-name { font-weight: 700; color: #5c6b7f; font-size: 16px; }
            .cit-role { font-size: 14px; color: #64748b; }

            /* --- RESPONSIVE LOGIC --- */

            /* Tablet Breakpoint (Stack Vertically) */
            @media (max-width: 1024px) {
                .cit-grid {
                    grid-template-columns: 1fr; /* Stack cards */
                    gap: 30px;
                }

                .cit-metric-card {
                    /* On tablet, show metrics side-by-side to save height */
                    flex-direction: row;
                    align-items: flex-start;
                    gap: 40px;
                    padding: 40px;
                }
                
                .cit-divider {
                    width: 1px;
                    height: auto; /* Vertical divider */
                    align-self: stretch;
                }
            }

            /* Mobile Breakpoint */
            @media (max-width: 767px) {
                .cit-metric-card {
                    flex-direction: column; /* Stack metrics again on phone */
                    gap: 30px;
                    padding: 32px;
                }
                
                .cit-divider {
                    width: 100%;
                    height: 1px; /* Horizontal divider again */
                }

                .cit-testi-card {
                    padding: 32px;
                }

                .cit-quote-main { font-size: 20px; }
                .cit-quote-body { font-size: 15px; }
            }
        </style>

        <div class="cora-unit-container cit-wrapper">
            <div class="cit-grid">
                
                <div class="cit-metric-card">
                    <div class="cit-stat-block">
                        <div class="cit-val"><?php echo esc_html($settings['m1_val']); ?></div>
                        <div class="cit-label"><?php echo esc_html($settings['m1_label']); ?></div>
                        <div class="cit-desc"><?php echo esc_html($settings['m1_desc']); ?></div>
                    </div>

                    <div class="cit-divider"></div>

                    <div class="cit-stat-block">
                        <div class="cit-val"><?php echo esc_html($settings['m2_val']); ?></div>
                        <div class="cit-label"><?php echo esc_html($settings['m2_label']); ?></div>
                        <div class="cit-desc"><?php echo esc_html($settings['m2_desc']); ?></div>
                    </div>
                </div>

                <div class="cit-testi-card">
                    <?php if(!empty($settings['company_logo']['url'])) : ?>
                        <img class="cit-logo" src="<?php echo esc_url($settings['company_logo']['url']); ?>" alt="Company">
                    <?php endif; ?>

                    <h3 class="cit-quote-main"><?php echo esc_html($settings['quote_title']); ?></h3>
                    
                    <p class="cit-quote-body"><?php echo esc_html($settings['quote_body']); ?></p>

                    <div class="cit-profile">
                        <img class="cit-avatar" src="<?php echo esc_url($settings['client_img']['url']); ?>" alt="Client">
                        <div class="cit-meta">
                            <span class="cit-name"><?php echo esc_html($settings['client_name']); ?></span>
                            <span class="cit-role"><?php echo esc_html($settings['client_role']); ?></span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <?php
    }
}