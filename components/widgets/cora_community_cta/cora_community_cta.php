<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Community_CTA extends Base_Widget {

    public function get_name() { return 'cora_community_cta'; }
    public function get_title() { return __( 'Cora Community CTA', 'cora-builder' ); }
    public function get_icon() { return 'eicon-call-to-action'; }

    protected function register_controls() {
        
        // --- TAB: CONTENT - COPY ---
        $this->start_controls_section('content', [ 'label' => __( 'Community Copy', 'cora-builder' ) ]);
        $this->add_control('headline', [ 
            'label' => 'Headline', 
            'type' => Controls_Manager::TEXT, 
            'default' => 'Join the Community of Successful Ecommerce Businesses',
            'dynamic' => ['active' => true]
        ]);
        $this->add_control('subline', [ 
            'label' => 'Subline', 
            'type' => Controls_Manager::TEXTAREA, 
            'default' => 'Get instant access to 2,000+ expert guides, proven strategies, and a supportive community.',
            'dynamic' => ['active' => true]
        ]);
        $this->end_controls_section();

        // --- TAB: CONTENT - ACTIONS ---
        $this->start_controls_section('actions', [ 'label' => __( 'Buttons', 'cora-builder' ) ]);
        $this->add_control('btn_primary_text', [ 'label' => 'Primary Button', 'type' => Controls_Manager::TEXT, 'default' => 'Start Free Trial' ]);
        $this->add_control('btn_secondary_text', [ 'label' => 'Secondary Button', 'type' => Controls_Manager::TEXT, 'default' => 'View Pricing' ]);
        $this->end_controls_section();

        // --- TAB: CONTENT - TRUST BADGES ---
        $this->start_controls_section('trust', [ 'label' => __( 'Trust Badges', 'cora-builder' ) ]);
        $repeater = new Repeater();
        $repeater->add_control('badge_text', [ 'label' => 'Badge Label', 'type' => Controls_Manager::TEXT, 'default' => 'No credit card required' ]);

        $this->add_control('badges', [
            'label' => 'Benefits Row',
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                ['badge_text' => 'No credit card required'],
                ['badge_text' => '14-day free trial'],
                ['badge_text' => 'Cancel anytime'],
            ],
        ]);
        $this->end_controls_section();

        $this->register_common_spatial_controls(); 
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <style>
            /* --- Community Block Architecture --- */
            .community-cta-wrapper {
                background: #020617; /* Deepest Slate/Black */
                /* Fluid Padding: 60px mobile -> 120px desktop */
                padding: clamp(60px, 10vw, 120px) 20px;
                border-radius: 40px;
                position: relative;
                overflow: hidden;
                text-align: center;
                color: #ffffff;
                isolation: isolate; /* Create stacking context for glows */
            }

            .cta-inner-content { 
                position: relative; 
                z-index: 10; 
                max-width: 800px; 
                margin: 0 auto; 
            }

            /* --- Background Glows --- */
            .glow-top-left, .glow-bottom-right {
                position: absolute; 
                width: 60vw; height: 60vw; /* Responsive size */
                max-width: 600px; max-height: 600px;
                background: radial-gradient(circle, rgba(59, 130, 246, 0.12) 0%, rgba(2, 6, 23, 0) 70%);
                border-radius: 50%;
                pointer-events: none;
                z-index: 1;
            }
            .glow-top-left { top: -20%; left: -20%; }
            .glow-bottom-right { bottom: -20%; right: -20%; }

            /* --- Typography Reset --- */
            .community-h2 { 
                margin: 0 !important; 
                /* Fluid Font: 32px -> 48px */
                font-size: clamp(32px, 5vw, 48px); 
                font-weight: 850; 
                line-height: 1.1; 
                letter-spacing: -0.03em;
                color: #ffffff;
            }

            .community-p { 
                margin: 20px auto 40px auto !important; 
                /* Fluid Font: 16px -> 20px */
                font-size: clamp(16px, 2vw, 20px); 
                opacity: 0.7; 
                line-height: 1.6; 
                max-width: 600px;
            }

            /* --- Action Hub --- */
            .community-btn-row { 
                display: flex; 
                justify-content: center; 
                align-items: center;
                gap: 16px; 
                margin-bottom: 48px;
                flex-wrap: wrap; /* Allow wrapping on small screens */
            }

            /* Buttons */
            .btn-base {
                padding: 16px 32px; 
                border-radius: 100px; 
                font-weight: 700; 
                text-decoration: none; 
                display: inline-flex; 
                align-items: center; 
                justify-content: center;
                gap: 10px;
                font-size: 15px;
                transition: transform 0.2s, box-shadow 0.2s;
            }
            .btn-base:hover { transform: translateY(-2px); }

            .btn-primary-light { 
                background: #f8fafc; color: #020617; 
                box-shadow: 0 10px 20px rgba(255,255,255,0.1);
            }
            
            .btn-secondary-dark { 
                background: rgba(255, 255, 255, 0.05); color: #fff; 
                border: 1px solid rgba(255, 255, 255, 0.1); 
            }
            .btn-secondary-dark:hover { background: rgba(255, 255, 255, 0.1); }

            /* --- Trust Row --- */
            .community-trust-row { 
                display: flex; 
                justify-content: center; 
                gap: 24px 32px; 
                opacity: 0.8; 
                flex-wrap: wrap; /* Essential for mobile */
            }
            .trust-unit { 
                display: flex; 
                align-items: center; 
                gap: 8px; 
                font-size: 14px; 
                font-weight: 600; 
                white-space: nowrap;
            }
            .trust-unit i { color: #4ade80; /* Success Green */ font-size: 16px; }

            /* --- Mobile Breakpoint (< 600px) --- */
            @media (max-width: 600px) {
                .community-cta-wrapper {
                    padding: 60px 24px;
                    border-radius: 24px;
                }

                .community-btn-row {
                    flex-direction: column; /* Stack buttons */
                    width: 100%;
                    gap: 12px;
                }

                .btn-base {
                    width: 100%; /* Full width buttons */
                }

                .community-trust-row {
                    flex-direction: column; /* Stack trust items */
                    gap: 12px;
                    align-items: center;
                }
            }
        </style>

        <div class="cora-unit-container community-cta-wrapper">
            <div class="glow-top-left"></div>
            <div class="glow-bottom-right"></div>

            <div class="cta-inner-content">
                <h2 class="community-h2"><?php echo esc_html($settings['headline']); ?></h2>
                <p class="community-p"><?php echo esc_html($settings['subline']); ?></p>

                <div class="community-btn-row">
                    <?php if(!empty($settings['btn_primary_text'])) : ?>
                        <a href="#" class="btn-base btn-primary-light">
                            <?php echo esc_html($settings['btn_primary_text']); ?> 
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    <?php endif; ?>
                    
                    <?php if(!empty($settings['btn_secondary_text'])) : ?>
                        <a href="#" class="btn-base btn-secondary-dark">
                            <?php echo esc_html($settings['btn_secondary_text']); ?>
                        </a>
                    <?php endif; ?>
                </div>

                <div class="community-trust-row">
                    <?php foreach ($settings['badges'] as $badge) : ?>
                        <div class="trust-unit">
                            <i class="fas fa-check-circle"></i>
                            <span><?php echo esc_html($badge['badge_text']); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php
    }
}