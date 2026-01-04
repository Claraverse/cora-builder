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
        ]);

        $this->add_control('role', [
            'label' => 'Job Title',
            'type' => Controls_Manager::TEXT,
            'default' => 'Senior Shopify Developer',
        ]);

        $this->add_control('stats', [
            'label' => 'Stats / Bio',
            'type' => Controls_Manager::TEXTAREA,
            'default' => '📈 50+ Shopify Projects | 🚀 $20M+ Revenue Optimized',
            'description' => 'You can copy-paste emojis here.',
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
                font-family: 'Inter', sans-serif; /* Fallback to Inter */
            }

            .cora-profile-card {
                background-color: <?php echo esc_attr($card_bg); ?>;
                border-radius: 40px;
                padding: 40px;
                display: flex;
                align-items: center;
                gap: 30px;
                position: relative;
                box-shadow: 0 20px 40px rgba(0,0,0,0.03);
            }

            /* Avatar Wrapper */
            .cp-avatar-box {
                flex-shrink: 0;
                width: 140px;
                height: 140px;
                border-radius: 50%;
                border: 5px solid <?php echo esc_attr($accent); ?>; /* The Green Border */
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
                gap: 8px;
            }

            .cp-name {
                font-family: 'Recoleta', 'Cooper', 'Inter', serif; /* Trying to match that soft serif look */
                font-size: 36px;
                font-weight: 800;
                color: <?php echo esc_attr($accent); ?>;
                line-height: 1.1;
                margin: 0;
            }

            .cp-role {
                font-size: 18px;
                font-weight: 600;
                color: #22c55e; /* Lighter Green standard */
                margin: 0 0 8px 0;
                opacity: 0.9;
            }
            /* Override role color with accent but slightly transparent if needed, 
               or keep specific green. The image shows a lighter green for title. */
            .cp-role { color: <?php echo esc_attr($accent); ?>; opacity: 0.7; }

            .cp-stats {
                font-size: 16px;
                color: #374151; /* Dark Gray for readability */
                font-weight: 500;
                line-height: 1.5;
                margin: 0;
            }

            /* Floating Badge */
            .cp-badge {
                background: #ffffff;
                padding: 12px 20px;
                border-radius: 50px;
                display: flex;
                align-items: center;
                gap: 15px;
                box-shadow: 0 10px 20px rgba(0,0,0,0.08);
                margin-left: auto; /* Push to right */
                flex-shrink: 0;
            }

            .cp-badge img {
                width: 28px;
                height: 28px;
                object-fit: contain;
                display: block;
            }

            /* Responsive */
            @media (max-width: 768px) {
                .cora-profile-card {
                    flex-direction: column;
                    text-align: center;
                    padding: 30px 20px;
                }
                .cp-badge {
                    margin: 10px auto 0 auto; /* Center badge */
                }
                .cp-avatar-box {
                    width: 120px;
                    height: 120px;
                }
                .cp-name { font-size: 28px; }
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