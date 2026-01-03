<?php
namespace Cora_Builder\Components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Utils;

if (!defined('ABSPATH'))
    exit;

class Cora_Testimonial_Block extends Base_Widget
{
    public function get_name() { return 'cora_testimonial_block'; }
    public function get_title() { return 'Cora – Testimonial Block'; }
    public function get_icon() { return 'eicon-testimonial'; }

    // Load fonts
    public function get_style_depends() {
        wp_register_style('cora-fonts', 'https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap', [], null);
        return ['cora-fonts'];
    }

    protected function register_controls()
    {
        // --- CONTENT ---
        $this->start_controls_section('section_content', ['label' => 'Content']);

        $this->add_control('image', [
            'label' => 'Background Image',
            'type' => Controls_Manager::MEDIA,
            'default' => [ 'url' => Utils::get_placeholder_image_src() ],
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('category', [
            'label' => 'Category (Serif)',
            'type' => Controls_Manager::TEXT,
            'default' => 'Personal Care',
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('testimonial', [
            'label' => 'Testimonial',
            'type' => Controls_Manager::TEXTAREA,
            'default' => 'Beautiful new site—simplified bookings and guests love the serene online vibe!',
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('name', [
            'label' => 'Client Name',
            'type' => Controls_Manager::TEXT,
            'default' => 'Dr. Omar Khalid',
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('role', [
            'label' => 'Client Role',
            'type' => Controls_Manager::TEXT,
            'default' => 'Marketing Manager',
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('btn_text', [
            'label' => 'Button Text',
            'type' => Controls_Manager::TEXT,
            'default' => 'Read Story',
        ]);

        $this->add_control('btn_link', [
            'label' => 'Link',
            'type' => Controls_Manager::URL,
            'dynamic' => ['active' => true],
        ]);

        $this->end_controls_section();

        // --- STYLE ---
        $this->start_controls_section('section_style', ['label' => 'Design', 'tab' => Controls_Manager::TAB_STYLE]);

        $this->add_responsive_control('card_height', [
            'label' => 'Card Height',
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px', 'vh', 'em'],
            'range' => [ 'px' => [ 'min' => 300, 'max' => 900 ] ],
            'default' => [ 'unit' => 'px', 'size' => 550 ],
            'selectors' => [
                '{{WRAPPER}} .cora-testi-root' => 'height: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('overlay_color', [
            'label' => 'Glass Color',
            'type' => Controls_Manager::COLOR,
            'default' => 'rgba(20, 20, 20, 0.85)',
            'selectors' => ['{{WRAPPER}} .cora-glass-card' => '--glass-bg: {{VALUE}};'],
        ]);

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $id = $this->get_id();
        
        $btn_attrs = '';
        if (!empty($settings['btn_link']['url'])) {
            $this->add_link_attributes('btn_link', $settings['btn_link']);
            $btn_attrs = $this->get_render_attribute_string('btn_link');
        }

        $bg_url = !empty($settings['image']['url']) ? $settings['image']['url'] : '';
        ?>

        <style>
            /* Main Container */
            .cora-root-<?php echo $id; ?> {
                position: relative;
                width: 100%;
                /* Height handled by controls */
                border-radius: 24px;
                overflow: hidden;
                background-image: url('<?php echo esc_url($bg_url); ?>');
                background-size: cover;
                background-position: center;
                display: flex;
                align-items: flex-end; /* Push content to bottom */
                padding: 10px; /* Small gap for the floating look */
                box-sizing: border-box;
                z-index: 1;
            }

            /* Dark Glass Overlay */
            .cora-root-<?php echo $id; ?> .cora-glass-card {
                width: 100%;
                /* CSS Variable for easy color control */
                --glass-bg: rgba(20, 20, 20, 0.85);
                background: linear-gradient(180deg, var(--glass-bg) 0%, rgba(0, 0, 0, 0.95) 100%);
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
                border-top: 1px solid rgba(255, 255, 255, 0.15);
                border-radius: 20px;
                padding: 24px;
                box-sizing: border-box;
                color: #FFFFFF;
                display: flex;
                flex-direction: column;
                gap: 16px;
                transition: transform 0.3s ease;
            }

            .cora-root-<?php echo $id; ?>:hover .cora-glass-card {
                transform: translateY(-4px);
            }

            /* Typography */
            .cora-root-<?php echo $id; ?> .cora-category {
                font-family: "Playfair Display", serif;
                font-style: italic;
                /* Responsive Font Size */
                font-size: clamp(24px, 5vw, 32px);
                font-weight: 400;
                margin: 0;
                line-height: 1;
                color: #FFFFFF;
            }

            .cora-root-<?php echo $id; ?> .cora-quote {
                font-family: "Fredoka", sans-serif;
                font-size: 16px;
                font-weight: 500;
                line-height: 1.5;
                margin: 0;
                color: rgba(255, 255, 255, 0.9);
            }

            /* Footer Row */
            .cora-root-<?php echo $id; ?> .cora-card-footer {
                display: flex;
                align-items: flex-end;
                justify-content: space-between;
                width: 100%;
                gap: 16px;
                margin-top: 4px;
                flex-wrap: wrap; /* Critical for responsiveness */
            }

            .cora-root-<?php echo $id; ?> .cora-meta {
                display: flex;
                flex-direction: column;
                gap: 2px;
                min-width: 120px; /* Ensure name doesn't squish too much */
            }

            .cora-root-<?php echo $id; ?> .cora-name {
                font-family: "Fredoka", sans-serif;
                font-size: 15px;
                font-weight: 700;
                color: #FFFFFF;
            }

            .cora-root-<?php echo $id; ?> .cora-role {
                font-family: "Fredoka", sans-serif;
                font-size: 13px;
                opacity: 0.8;
                font-weight: 400;
                color: #FFFFFF;
            }

            /* Ghost Button (Pill) */
            .cora-root-<?php echo $id; ?> .cora-btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                padding: 8px 20px;
                border: 1px solid rgba(255, 255, 255, 0.8);
                border-radius: 100px;
                background: transparent;
                color: #FFFFFF;
                text-decoration: none;
                font-family: "Fredoka", sans-serif;
                font-size: 13px;
                font-weight: 500;
                transition: all 0.2s ease;
                white-space: nowrap;
                cursor: pointer;
            }

            .cora-root-<?php echo $id; ?> .cora-btn:hover {
                background: #FFFFFF;
                color: #000000;
                border-color: #FFFFFF;
            }

            /* --- RESPONSIVE MOBILE FIXES --- */
            @media (max-width: 767px) {
                .cora-root-<?php echo $id; ?> {
                    /* On mobile, unset fixed height to prevent cutoff if text is long */
                    height: auto !important; 
                    min-height: 400px; 
                }

                .cora-root-<?php echo $id; ?> .cora-glass-card {
                    padding: 20px;
                    gap: 12px;
                }
                
                .cora-root-<?php echo $id; ?> .cora-btn {
                    /* On very small screens, make button full width for easier tapping */
                    flex-grow: 1;
                    text-align: center;
                }
            }
        </style>

        <div class="cora-unit-container cora-testi-root cora-root-<?php echo $id; ?>">
            <div class="cora-glass-card">
                <h4 class="cora-category"><?php echo esc_html($settings['category']); ?></h4>
                <p class="cora-quote"><?php echo esc_html($settings['testimonial']); ?></p>
                
                <div class="cora-card-footer">
                    <div class="cora-meta">
                        <span class="cora-name"><?php echo esc_html($settings['name']); ?></span>
                        <span class="cora-role"><?php echo esc_html($settings['role']); ?></span>
                    </div>

                    <?php if ( ! empty($settings['btn_text']) ) : ?>
                        <a <?php echo $btn_attrs; ?> class="cora-btn">
                            <?php echo esc_html($settings['btn_text']); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
    }
}