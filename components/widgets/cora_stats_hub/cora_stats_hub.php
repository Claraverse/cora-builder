<?php
namespace Cora_Builder\components;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Stats_Hub extends Widget_Base {

    public function get_name() { return 'cora_stats_hub'; }
    public function get_title() { return __( 'Cora Stats Hub', 'cora-builder' ); }
    public function get_icon() { return 'eicon-number'; }
    public function get_categories() { return [ 'cora-components' ]; }

    protected function register_controls() {

        // --- STATS CONFIG ---
        $this->start_controls_section('stats_section', ['label' => 'Statistics (Left Grid)']);
        
        // Stat 1 (Top)
        $this->add_control('s1_val', [ 'label' => 'Top Stat Value', 'type' => Controls_Manager::TEXT, 'default' => '16k+' ]);
        $this->add_control('s1_label', [ 'label' => 'Top Stat Label', 'type' => Controls_Manager::TEXT, 'default' => 'Total Users' ]);

        // Stat 2 (Left)
        $this->add_control('s2_val', [ 'label' => 'Left Stat Value', 'type' => Controls_Manager::TEXT, 'default' => '1800+' ]);
        $this->add_control('s2_label', [ 'label' => 'Left Stat Label', 'type' => Controls_Manager::TEXT, 'default' => 'Courses' ]);

        // Stat 3 (Right)
        $this->add_control('s3_val', [ 'label' => 'Right Stat Value', 'type' => Controls_Manager::TEXT, 'default' => '16+' ]);
        $this->add_control('s3_label', [ 'label' => 'Right Stat Label', 'type' => Controls_Manager::TEXT, 'default' => 'Years Experience' ]);

        // Stat 4 (Bottom)
        $this->add_control('s4_val', [ 'label' => 'Bottom Stat Value', 'type' => Controls_Manager::TEXT, 'default' => '381+' ]);
        $this->add_control('s4_label', [ 'label' => 'Bottom Stat Label', 'type' => Controls_Manager::TEXT, 'default' => 'Team Members' ]);

        $this->end_controls_section();

        // --- CONTENT CONFIG ---
        $this->start_controls_section('content_section', ['label' => 'Content (Right Side)']);
        
        $this->add_control('badge', [ 'label' => 'Badge Text', 'type' => Controls_Manager::TEXT, 'default' => '#Claraverse' ]);
        $this->add_control('heading', [ 'label' => 'Heading', 'type' => Controls_Manager::TEXT, 'default' => 'Impressive Figures and Facts' ]);
        $this->add_control('desc', [ 'label' => 'Description', 'type' => Controls_Manager::TEXTAREA, 'default' => 'Explore our key statistics and milestones that showcase our growth, impact, and success in transforming the learning experience for users.' ]);
        
        // Buttons
        $this->add_control('btn_primary_text', [ 'label' => 'Primary Button Text', 'type' => Controls_Manager::TEXT, 'default' => 'Get Started Now' ]);
        $this->add_control('btn_primary_link', [ 'label' => 'Primary Button Link', 'type' => Controls_Manager::URL, 'default' => [ 'url' => '#' ] ]);
        
        $this->add_control('btn_secondary_text', [ 'label' => 'Secondary Button Text', 'type' => Controls_Manager::TEXT, 'default' => 'Contact Us' ]);
        $this->add_control('btn_secondary_link', [ 'label' => 'Secondary Button Link', 'type' => Controls_Manager::URL, 'default' => [ 'url' => '#' ] ]);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>

        <style>
            .csh-wrap {
                display: flex;
                align-items: center;
                gap: 80px;
                padding: 60px 0;
                font-family: 'Inter', sans-serif;
            }

            /* --- Left Side: Diamond Grid --- */
            .csh-grid-container {
                flex: 1;
                display: grid;
                grid-template-areas: 
                    ". top ."
                    "left . right"
                    ". bottom .";
                grid-template-columns: 1fr 20px 1fr; /* Gap control via columns */
                gap: 20px;
                justify-items: center;
            }

            /* Helper function for cards */
            .csh-card {
                background-color: #f8fafc; /* Very light blue-grey */
                border: 1px solid #e2e8f0;
                border-radius: 20px;
                width: 180px;
                height: 120px;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                box-shadow: 0 10px 30px rgba(0,0,0,0.03);
                /* Dot Pattern Background */
                background-image: radial-gradient(#cbd5e1 1px, transparent 1px);
                background-size: 10px 10px;
            }

            .csh-card strong {
                font-size: 28px;
                font-weight: 800;
                color: #0f172a;
                line-height: 1;
                margin-bottom: 6px;
            }
            .csh-card span {
                font-size: 14px;
                color: #64748b;
                font-weight: 500;
            }

            /* Grid Placement */
            .csh-c-top { grid-area: top; transform: translateY(20px); }
            .csh-c-left { grid-area: left; }
            .csh-c-right { grid-area: right; }
            .csh-c-bottom { grid-area: bottom; transform: translateY(-20px); }


            /* --- Right Side: Content --- */
            .csh-content {
                flex: 1;
                max-width: 550px;
            }

            .csh-badge {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                background: #f1f5f9;
                padding: 8px 16px;
                border-radius: 50px;
                font-size: 14px;
                font-weight: 600;
                color: #475569;
                margin-bottom: 24px;
            }
            .csh-badge i { font-size: 16px; } /* Placeholder for icon if needed */

            .csh-h2 {
                font-size: 48px;
                font-weight: 800;
                color: #0f172a;
                line-height: 1.1;
                margin: 0 0 24px 0;
            }

            .csh-desc {
                font-size: 16px;
                line-height: 1.6;
                color: #64748b;
                margin-bottom: 40px;
            }

            /* Buttons */
            .csh-actions {
                display: flex;
                gap: 16px;
            }
            
            .csh-btn {
                padding: 14px 28px;
                border-radius: 50px;
                font-weight: 600;
                text-decoration: none;
                transition: transform 0.2s ease;
                font-size: 15px;
            }
            .csh-btn:hover { transform: translateY(-2px); }

            .csh-btn-primary {
                background: #475569;
                color: #fff;
            }
            .csh-btn-secondary {
                background: #f1f5f9;
                color: #334155;
            }

            /* --- Responsive --- */
            @media (max-width: 1024px) {
                .csh-wrap { flex-direction: column-reverse; text-align: center; gap: 50px; }
                .csh-actions { justify-content: center; }
                
                /* Reset Grid for Tablet/Mobile to simple 2x2 */
                .csh-grid-container {
                    grid-template-areas: 
                        "left right"
                        "top bottom";
                    grid-template-columns: 1fr 1fr;
                    gap: 20px;
                }
                /* Reset transforms */
                .csh-c-top, .csh-c-bottom { transform: none; }
            }

            @media (max-width: 600px) {
                .csh-grid-container {
                    grid-template-areas: 
                        "top"
                        "left" 
                        "right" 
                        "bottom";
                    grid-template-columns: 1fr;
                }
                .csh-card { width: 100%; max-width: 280px; }
            }
        </style>

        <div class="csh-wrap">
            
            <div class="csh-grid-container">
                <div class="csh-card csh-c-top">
                    <strong><?php echo esc_html($settings['s1_val']); ?></strong>
                    <span><?php echo esc_html($settings['s1_label']); ?></span>
                </div>
                <div class="csh-card csh-c-left">
                    <strong><?php echo esc_html($settings['s2_val']); ?></strong>
                    <span><?php echo esc_html($settings['s2_label']); ?></span>
                </div>
                <div class="csh-card csh-c-right">
                    <strong><?php echo esc_html($settings['s3_val']); ?></strong>
                    <span><?php echo esc_html($settings['s3_label']); ?></span>
                </div>
                <div class="csh-card csh-c-bottom">
                    <strong><?php echo esc_html($settings['s4_val']); ?></strong>
                    <span><?php echo esc_html($settings['s4_label']); ?></span>
                </div>
            </div>

            <div class="csh-content">
                <div class="csh-badge">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="12" r="3"></circle></svg>
                    <?php echo esc_html($settings['badge']); ?>
                </div>
                
                <h2 class="csh-h2"><?php echo esc_html($settings['heading']); ?></h2>
                <p class="csh-desc"><?php echo esc_html($settings['desc']); ?></p>

                <div class="csh-actions">
                    <?php if(!empty($settings['btn_primary_text'])) : ?>
                        <a href="<?php echo esc_url($settings['btn_primary_link']['url']); ?>" class="csh-btn csh-btn-primary">
                            <?php echo esc_html($settings['btn_primary_text']); ?>
                        </a>
                    <?php endif; ?>

                    <?php if(!empty($settings['btn_secondary_text'])) : ?>
                        <a href="<?php echo esc_url($settings['btn_secondary_link']['url']); ?>" class="csh-btn csh-btn-secondary">
                            <?php echo esc_html($settings['btn_secondary_text']); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>

        </div>
        <?php
    }
}