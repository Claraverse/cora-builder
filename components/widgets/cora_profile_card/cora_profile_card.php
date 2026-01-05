<?php
namespace Cora_Builder\components;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Profile_Card extends Widget_Base {

    public function get_name() { return 'cora_profile_card'; }
    public function get_title() { return __( 'Cora Profile Card', 'cora-builder' ); }
    public function get_icon() { return 'eicon-person'; }
    public function get_categories() { return [ 'cora-components' ]; }

    protected function register_controls() {

        // --- CONTENT ---
        $this->start_controls_section('content_section', ['label' => 'Profile Details']);

        $this->add_control('avatar', [
            'label' => 'Profile Photo',
            'type' => Controls_Manager::MEDIA,
            'default' => ['url' => Utils::get_placeholder_image_src()],
        ]);

        $this->add_control('name', [
            'label' => 'Name',
            'type' => Controls_Manager::TEXT,
            'default' => 'Dravya Bansal',
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('role', [
            'label' => 'Job Title',
            'type' => Controls_Manager::TEXT,
            'default' => 'Senior Shopify Developer',
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('stats', [
            'label' => 'Stats / Bio',
            'type' => Controls_Manager::TEXTAREA,
            'default' => '📈 50+ Shopify Projects | 🚀 $20M+ Revenue Optimized',
            'description' => 'You can copy-paste emojis here.',
            'dynamic' => ['active' => true],
        ]);

        $this->end_controls_section();

        // --- TOOLS BADGE ---
        $this->start_controls_section('tools_section', ['label' => 'Platform Icons']);
        
        $this->add_control('tool_icon_1', [
            'label' => 'Icon 1 (e.g. Figma)',
            'type' => Controls_Manager::MEDIA,
        ]);

        $this->add_control('tool_icon_2', [
            'label' => 'Icon 2 (e.g. Shopify)',
            'type' => Controls_Manager::MEDIA,
        ]);

        $this->end_controls_section();

        // --- STYLING ---
        $this->start_controls_section('style_section', ['label' => 'Colors & Style']);

        $this->add_control('card_bg', [
            'label' => 'Card Background',
            'type' => Controls_Manager::COLOR,
            'default' => '#D1F4D1', // Pale Green
        ]);

        $this->add_control('accent_color', [
            'label' => 'Text/Border Color',
            'type' => Controls_Manager::COLOR,
            'default' => '#0F5118', // Dark Green
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        $card_bg = $settings['card_bg'];
        $accent  = $settings['accent_color'];
        ?>

        <style>
            .cora-profile-wrap {
                width: 100%;
                font-family: 'Inter', sans-serif;
            }

            .cora-profile-card {
                background-color: <?php echo esc_attr($card_bg); ?>;
                border-radius: 40px;
                /* Fluid Padding: 24px mobile -> 40px desktop */
                padding: clamp(24px, 4vw, 40px);
                display: flex;
                align-items: center;
                /* Fluid Gap: 20px -> 30px */
                gap: clamp(20px, 3vw, 30px);
                position: relative;
                box-shadow: 0 20px 40px rgba(0,0,0,0.03);
                transition: transform 0.3s ease;
            }

            /* Avatar Wrapper */
            .cp-avatar-box {
                flex-shrink: 0;
                /* Fluid Size: 100px mobile -> 140px desktop */
                width: clamp(100px, 12vw, 140px);
                height: clamp(100px, 12vw, 140px);
                border-radius: 50%;
                border: 5px solid <?php echo esc_attr($accent); ?>;
                overflow: hidden;
                background: #fff;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .cp-avatar-box img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            /* Text Content */
            .cp-content {
                flex: 1;
                display: flex;
                flex-direction: column;
                gap: 6px;
                min-width: 0; /* Prevents text overflow flex issues */
            }

            .cp-name {
                font-family: 'Inter', serif; /* Use system/theme font */
                /* Fluid Font: 26px -> 36px */
                font-size: clamp(26px, 4vw, 36px);
                font-weight: 800;
                color: <?php echo esc_attr($accent); ?>;
                line-height: 1.1;
                margin: 0;
            }

            .cp-role {
                /* Fluid Font: 16px -> 18px */
                font-size: clamp(16px, 2vw, 18px);
                font-weight: 600;
                color: <?php echo esc_attr($accent); ?>;
                opacity: 0.7;
                margin: 0;
            }

            .cp-stats {
                /* Fluid Font: 14px -> 16px */
                font-size: clamp(14px, 1.5vw, 16px);
                color: #374151;
                font-weight: 500;
                line-height: 1.5;
                margin-top: 4px;
            }

            /* Floating Badge */
            .cp-badge {
                background: #ffffff;
                padding: 10px 18px;
                border-radius: 50px;
                display: flex;
                align-items: center;
                gap: 12px;
                box-shadow: 0 10px 20px rgba(0,0,0,0.08);
                margin-left: auto; /* Push to right on Desktop */
                flex-shrink: 0;
            }

            .cp-badge img {
                width: 24px;
                height: 24px;
                object-fit: contain;
                display: block;
            }

            /* --- RESPONSIVE LOGIC --- */
            
            /* Tablet/Mobile Breakpoint (< 768px) */
            @media (max-width: 768px) {
                .cora-profile-card {
                    flex-direction: column;
                    text-align: center;
                    /* Slightly tighter padding on mobile */
                    padding: 32px 24px; 
                    gap: 20px;
                }
                
                .cp-content {
                    width: 100%;
                    gap: 8px;
                }

                .cp-avatar-box {
                    border-width: 4px; /* Thinner border on mobile */
                }

                .cp-badge {
                    margin-left: 0; /* Remove auto margin */
                    margin-top: 8px; /* Add spacing from stats */
                    align-self: center; /* Center horizontally */
                }
            }
        </style>

        <div class="cora-profile-wrap">
            <div class="cora-profile-card">
                
                <div class="cp-avatar-box">
                    <?php if(!empty($settings['avatar']['url'])) : ?>
                        <img src="<?php echo esc_url($settings['avatar']['url']); ?>" alt="Profile">
                    <?php endif; ?>
                </div>

                <div class="cp-content">
                    <h2 class="cp-name"><?php echo esc_html($settings['name']); ?></h2>
                    <p class="cp-role"><?php echo esc_html($settings['role']); ?></p>
                    <div class="cp-stats"><?php echo wp_kses_post($settings['stats']); ?></div>
                </div>

                <?php if ( !empty($settings['tool_icon_1']['url']) || !empty($settings['tool_icon_2']['url']) ) : ?>
                    <div class="cp-badge">
                        <?php if(!empty($settings['tool_icon_1']['url'])) : ?>
                            <img src="<?php echo esc_url($settings['tool_icon_1']['url']); ?>" alt="Tool 1">
                        <?php endif; ?>
                        
                        <?php if(!empty($settings['tool_icon_2']['url'])) : ?>
                            <img src="<?php echo esc_url($settings['tool_icon_2']['url']); ?>" alt="Tool 2">
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

            </div>
        </div>
        <?php
    }
}