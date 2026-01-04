<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Deliverable_Card extends Base_Widget {

    public function get_name() { return 'cora_deliverable_card'; }
    public function get_title() { return __( 'Cora Deliverable Card', 'cora-builder' ); }
    public function get_icon() { return 'eicon-info-box'; }

    protected function register_controls() {
        
        $this->start_controls_section('content', [ 'label' => __( 'Deliverable Info', 'cora-builder' ) ]);
        
        $this->add_control('feature_icon', [ 'label' => 'Icon', 'type' => Controls_Manager::ICONS ]);
        
        $this->add_control('title', [ 
            'label' => 'Headline', 
            'type' => Controls_Manager::TEXT, 
            'default' => 'Booking Funnel (Wix)',
            'dynamic' => ['active' => true] 
        ]);
        
        $this->add_control('desc', [ 
            'label' => 'Subline', 
            'type' => Controls_Manager::TEXTAREA, 
            'default' => 'Mobile-optimized, direct booking site with sticky CTAs',
            'dynamic' => ['active' => true]
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <style>
            /* --- Card Container --- */
            .deliverable-card {
                display: flex;
                flex-direction: column;
                align-items: center;
                text-align: center;
                /* Reduced Padding: Compact 20px on mobile -> 32px on desktop */
                padding: clamp(20px, 4vw, 32px);
                background: #ffffff;
                border-radius: 24px;
                box-shadow: 0 4px 6px rgba(0,0,0,0.02); /* Subtle base shadow */
                transition: transform 0.3s ease, box-shadow 0.3s ease;
                height: 100%;
                border: 1px solid transparent;
            }

            .deliverable-card:hover {
                transform: translateY(-4px);
                box-shadow: 0 15px 30px -5px rgba(0,0,0,0.06);
                border-color: #f1f5f9;
            }

            /* --- The Icon Canvas --- */
            .deliverable-icon-box {
                /* Reduced Size: 50px mobile -> 64px desktop */
                width: clamp(50px, 6vw, 64px);
                height: clamp(50px, 6vw, 64px);
                background: #d9f99d; /* Signature Lime Green */
                border-radius: 16px;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-bottom: clamp(20px, 3vw, 24px);
                transition: transform 0.3s ease;
            }
            
            .deliverable-card:hover .deliverable-icon-box {
                transform: scale(1.05) rotate(3deg);
            }

            .deliverable-icon-box i, 
            .deliverable-icon-box svg {
                /* Smaller Icon: 20px -> 26px */
                font-size: clamp(20px, 2.5vw, 26px);
                color: #1e293b;
                width: 32px;
                height: 32px;
                fill: #1e293b;
            }

            /* --- Typography --- */
            .deliverable-copy { width: 100%; }

            .deliverable-h3 { 
                margin: 0 !important; 
                /* Significantly Smaller Headline: 18px -> 22px */
                font-size: clamp(18px, 2.5vw, 22px);
                font-weight: 800; 
                color: #1e293b; 
                letter-spacing: -0.01em;
                line-height: 1.25;
            }

            .deliverable-p { 
                margin: 8px 0 0 0 !important; 
                /* Standard Body Size: 13px -> 15px */
                font-size: clamp(13px, 1.5vw, 15px); 
                color: #64748b; 
                line-height: 1.5;
                font-weight: 500;
                max-width: 90%; /* Prevent text stretching too wide */
                margin-left: auto !important;
                margin-right: auto !important;
            }
        </style>

        <div class="cora-unit-container deliverable-card">
            <div class="deliverable-icon-box">
                <?php \Elementor\Icons_Manager::render_icon( $settings['feature_icon'], [ 'aria-hidden' => 'true' ] ); ?>
            </div>

            <div class="deliverable-copy">
                <h3 class="deliverable-h3"><?php echo esc_html($settings['title']); ?></h3>
                <p class="deliverable-p"><?php echo esc_html($settings['desc']); ?></p>
            </div>
        </div>
        <?php
    }
}