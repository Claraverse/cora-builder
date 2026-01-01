<?php
namespace Cora_Builder\Components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Utils;

if (!defined('ABSPATH'))
    exit;

class Cora_About_Team_Card extends Base_Widget
{
    public function get_name() { return 'cora_about_team_card'; }
    public function get_title() { return 'Cora About Team Card'; }
    public function get_icon() { return 'eicon-person'; }

    // Load Fonts
    public function get_style_depends() {
        wp_register_style('cora-fonts', 'https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap', [], null);
        return ['cora-fonts'];
    }

    protected function register_controls()
    {
        // --- CONTENT ---
        $this->start_controls_section('section_content', ['label' => 'Profile Info']);

        $this->add_control('photo', [
            'label' => 'Profile Photo',
            'type' => Controls_Manager::MEDIA,
            'default' => [ 'url' => Utils::get_placeholder_image_src() ],
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('vision_title', [
            'label' => 'Heading',
            'type' => Controls_Manager::TEXT,
            'default' => 'Voice Behind the Vision',
            'dynamic' => ['active' => true],
        ]);
        
        $this->add_control('bio', [
            'label' => 'Bio / Statement',
            'type' => Controls_Manager::TEXTAREA,
            'default' => 'I shape content that connects — blending strategy, story, and style...',
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('name', [
            'label' => 'Full Name',
            'type' => Controls_Manager::TEXT,
            'default' => 'Shruti B.',
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('role', [
            'label' => 'Role',
            'type' => Controls_Manager::TEXT,
            'default' => 'Content Head',
            'dynamic' => ['active' => true],
        ]);

        $this->end_controls_section();

        // --- STYLE ---
        $this->start_controls_section('section_style', ['label' => 'Design', 'tab' => Controls_Manager::TAB_STYLE]);

        $this->add_control('card_bg', [
            'label' => 'Card Background',
            'type' => Controls_Manager::COLOR,
            'default' => '#FFFFFF',
            'selectors' => ['{{WRAPPER}} .cora-team-root' => 'background-color: {{VALUE}};'],
        ]);

        // Typography Group
        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'heading_typo',
            'label' => 'Heading Typography',
            'selector' => '{{WRAPPER}} .cora-vision-title',
        ]);

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $id = $this->get_id();
        ?>

        <style>
            .cora-root-<?php echo $id; ?> {
                display: flex;
                flex-direction: row;
                align-items: center;
                background-color: #FFFFFF;
                border: 1px solid #F1F5F9;
                border-radius: 32px;
                padding: 24px;
                gap: 40px;
                width: 100%;
                box-sizing: border-box;
                box-shadow: 0 4px 20px rgba(0,0,0,0.02);
            }

            /* --- Image Layout Fix --- */
            .cora-root-<?php echo $id; ?> .cora-photo-frame {
                flex: 0 0 240px; /* Fixed Width: Prevents squashing */
                height: 300px;   /* Fixed Height */
                border-radius: 20px;
                overflow: hidden;
                position: relative;
            }

            .cora-root-<?php echo $id; ?> .cora-headshot {
                width: 100%;
                height: 100%;
                object-fit: cover; /* Ensures image fills frame without distortion */
                display: block;
            }

            /* --- Content --- */
            .cora-root-<?php echo $id; ?> .cora-content-col {
                flex: 1;
                display: flex;
                flex-direction: column;
                gap: 16px;
                align-items: flex-start;
            }

            .cora-root-<?php echo $id; ?> .cora-vision-title {
                margin: 0;
                font-family: "Fredoka", sans-serif;
                font-size: 28px;
                font-weight: 500;
                color: #0F172A;
                line-height: 1.2;
            }

            .cora-root-<?php echo $id; ?> .cora-bio {
                margin: 0;
                font-family: "Inter", sans-serif;
                font-size: 16px;
                line-height: 1.6;
                color: #64748B;
            }

            .cora-root-<?php echo $id; ?> .cora-meta {
                margin-top: 8px;
                display: flex;
                flex-direction: column;
                gap: 4px;
            }

            .cora-root-<?php echo $id; ?> .cora-name {
                font-family: "Fredoka", sans-serif;
                font-size: 18px;
                font-weight: 500;
                color: #0F172A;
            }

            .cora-root-<?php echo $id; ?> .cora-role {
                font-family: "Fredoka", sans-serif;
                font-size: 15px;
                font-weight: 500;
                color: #94A3B8; /* Muted Blue/Grey */
            }

            /* --- Responsive --- */
            @media (max-width: 768px) {
                .cora-root-<?php echo $id; ?> {
                    flex-direction: column;
                    align-items: flex-start;
                    padding: 20px;
                    gap: 24px;
                }

                .cora-root-<?php echo $id; ?> .cora-photo-frame {
                    width: 100%; /* Full width on mobile */
                    height: 350px; /* Taller on mobile */
                    flex: none;
                }
            }
        </style>

        <div class="cora-unit-container cora-team-root cora-root-<?php echo $id; ?>">
            
            <div class="cora-photo-frame">
                <?php if ( ! empty($settings['photo']['url']) ) : ?>
                    <img src="<?php echo esc_url($settings['photo']['url']); ?>" class="cora-headshot" alt="<?php echo esc_attr($settings['name']); ?>">
                <?php endif; ?>
            </div>

            <div class="cora-content-col">
                <h3 class="cora-vision-title"><?php echo esc_html($settings['vision_title']); ?></h3>
                <p class="cora-bio"><?php echo esc_html($settings['bio']); ?></p>
                
                <div class="cora-meta">
                    <span class="cora-name"><?php echo esc_html($settings['name']); ?></span>
                    <span class="cora-role"><?php echo esc_html($settings['role']); ?></span>
                </div>
            </div>

        </div>
        <?php
    }
}