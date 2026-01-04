<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Project_Hero_V2 extends Base_Widget {

    public function get_name() { return 'cora_project_hero_v2'; }
    public function get_title() { return __( 'Cora Project Hero V2', 'cora-builder' ); }
    public function get_icon() { return 'eicon-image-rollover'; }

    protected function register_controls() {
        
        $this->start_controls_section('content', [ 'label' => 'Identity' ]);
        $this->add_control('bg_img', [ 'label' => 'Hero Image', 'type' => Controls_Manager::MEDIA ]);
        $this->add_control('title', [ 'label' => 'Project Name', 'type' => Controls_Manager::TEXT, 'default' => 'Third Space London' ]);
        $this->add_control('excerpt', [ 'label' => 'Excerpt', 'type' => Controls_Manager::TEXTAREA, 'default' => 'Luxury fitness and wellness club...' ]);
        $this->add_control('tags', [ 'label' => 'Tags (Comma separated)', 'type' => Controls_Manager::TEXT, 'default' => 'Design, Dev, WordPress' ]);
        $this->end_controls_section();

        $this->start_controls_section('metrics', [ 'label' => 'Technical Specs' ]);
        $this->add_control('cost', [ 'label' => 'Cost', 'type' => Controls_Manager::TEXT, 'default' => '$1200' ]);
        $this->add_control('loc', [ 'label' => 'Location', 'type' => Controls_Manager::TEXT, 'default' => 'London' ]);
        $this->add_control('dur', [ 'label' => 'Duration', 'type' => Controls_Manager::TEXT, 'default' => '21 Days' ]);
        $this->add_control('service', [ 'label' => 'Service', 'type' => Controls_Manager::TEXT, 'default' => 'Restaurant Booster' ]);
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $tags = explode(',', $settings['tags']);
        
        // Ensure image URL is valid
        $bg_url = !empty($settings['bg_img']['url']) ? $settings['bg_img']['url'] : '';
        ?>
        <style>
            /* --- Hero Container --- */
            .cora-hero-v2 {
                width: 100%;
                min-height: 640px; /* Use min-height to allow content expansion */
                background-size: cover; 
                background-position: center;
                border-radius: 40px; 
                overflow: hidden;
                position: relative; 
                display: flex; 
                align-items: flex-end; 
                padding: 20px;
                font-family: 'Inter', sans-serif;
            }

            /* --- Glassmorphism Bar --- */
            .p-glass-bar {
                width: 100%;
                background: rgba(255, 255, 255, 0.15);
                backdrop-filter: blur(56px);
                -webkit-backdrop-filter: blur(28px);
                border: 1px solid rgba(255, 255, 255, 0.15);
                border-radius: 36px; 
                padding: 20px; /* Fluid padding */
                color: #ffffff;
                box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            }

            /* --- Top Section: Title & Tags --- */
            .p-top-flex { 
                display: flex; 
                justify-content: space-between; 
                align-items: flex-start; 
                margin-bottom: 36px; 
                gap: 20px;
            }

            .p-titles { flex: 1; }

            .p-main-title { 
                margin: 0 !important; 
                font-size: clamp(28px, 4vw, 44px); /* Fluid Type: 28px mobile -> 44px desktop */
                font-weight: 850; 
                letter-spacing: -0.03em; 
                line-height: 1.1;
                color: #fff;
            }
            
            .p-subtext { 
                margin: 12px 0 0 0 !important; 
                font-size: 16px; 
                opacity: 0.85; 
                font-weight: 500; 
                max-width: 600px;
                line-height: 1.5;
            }

            .p-tag-hub { 
                display: flex; 
                gap: 8px; 
                flex-wrap: wrap; /* Allow tags to wrap on mobile */
                justify-content: flex-end;
                max-width: 300px;
            }
            
            .p-pill { 
                background: rgba(255, 255, 255, 0.15); 
                border: 1px solid rgba(255,255,255,0.1);
                padding: 6px 16px; 
                border-radius: 100px; 
                font-size: 12px; 
                font-weight: 700; 
                white-space: nowrap;
                letter-spacing: 0.5px;
            }

            /* --- Metrics Grid --- */
            .p-metrics { 
                display: grid; 
                grid-template-columns: repeat(4, 1fr); /* Default 4 cols */
                gap: 40px; 
                border-top: 1px solid rgba(255,255,255,0.1);
                padding-top: 30px;
            }

            .p-m-unit span { 
                display: block; 
                font-size: 11px; 
                font-weight: 700; 
                opacity: 0.6; 
                margin-bottom: 6px; 
                text-transform: uppercase; 
                letter-spacing: 1px;
            }
            
            .p-m-unit strong { 
                display: block; 
                font-size: 16px; 
                font-weight: 700; 
                white-space: nowrap;
            }

            /* --- RESPONSIVE LOGIC --- */
            
            /* Tablet (Landscape/Portrait) */
            @media screen and (max-width: 1024px) {
                .cora-hero-v2 { min-height: 500px; }
                
                .p-top-flex { 
                    flex-direction: column; 
                    align-items: flex-start; 
                }
                
                .p-tag-hub { 
                    justify-content: flex-start; 
                    margin-top: 10px;
                    max-width: 100%;
                }

                /* Switch metrics to 2x2 Grid */
                .p-metrics { 
                    grid-template-columns: repeat(2, 1fr); 
                    gap: 30px; 
                }
            }

            /* Mobile */
            @media screen and (max-width: 600px) {
                .cora-hero-v2 { min-height: 550px; padding: 10px; }
                
                .p-glass-bar { 
                    border-radius: 28px; 
                    padding: 24px;
                }

                .p-main-title { font-size: 28px; }
                
                /* Keep metrics 2x2 but tighten spacing */
                .p-metrics { gap: 24px; }
            }
        </style>

        <div class="cora-hero-v2" style="background-image: url('<?php echo esc_url($bg_url); ?>');">
            <div class="p-glass-bar">
                <div class="p-top-flex">
                    <div class="p-titles">
                        <h1 class="p-main-title"><?php echo esc_html($settings['title']); ?></h1>
                        <p class="p-subtext"><?php echo esc_html($settings['excerpt']); ?></p>
                    </div>
                    <div class="p-tag-hub">
                        <?php foreach($tags as $tag): ?>
                            <?php if(!empty(trim($tag))): ?>
                                <span class="p-pill"><?php echo esc_html(trim($tag)); ?></span>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="p-metrics">
                    <div class="p-m-unit"><span>Project Cost</span><strong><?php echo esc_html($settings['cost']); ?></strong></div>
                    <div class="p-m-unit"><span>Location</span><strong><?php echo esc_html($settings['loc']); ?></strong></div>
                    <div class="p-m-unit"><span>Duration</span><strong><?php echo esc_html($settings['dur']); ?></strong></div>
                    <div class="p-m-unit"><span>Service</span><strong><?php echo esc_html($settings['service']); ?></strong></div>
                </div>
            </div>
        </div>
        <?php
    }
}