<?php
namespace Cora_Builder\Components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Utils;

if (!defined('ABSPATH'))
    exit;

class Cora_Service_Banner extends Base_Widget
{
    public function get_name() { return 'cora_service_banner'; }
    public function get_title() { return 'Cora Service Banner'; }
    public function get_icon() { return 'eicon-banner'; }

    // Load fonts
    public function get_style_depends() {
        wp_register_style('cora-fonts', 'https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap', [], null);
        return ['cora-fonts'];
    }

    protected function register_controls()
    {
        // --- CONTENT ---
        $this->start_controls_section('section_content', ['label' => 'Content']);

        $this->add_control('logo_image', [
            'label' => 'Brand Logo',
            'type' => Controls_Manager::MEDIA,
            'default' => [ 'url' => Utils::get_placeholder_image_src() ],
        ]);

        $this->add_control('title', [
            'label' => 'Heading',
            'type' => Controls_Manager::TEXTAREA,
            'default' => 'High-performance E-Commerce Hosting',
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('desc', [
            'label' => 'Description',
            'type' => Controls_Manager::TEXTAREA,
            'default' => 'Work smarter with tasks that can live in your whiteboards, chat, calendar—anywhere you work.',
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('btn_text', [
            'label' => 'Button Text',
            'type' => Controls_Manager::TEXT,
            'default' => 'Get Started for Free',
        ]);

        $this->add_control('btn_link', [
            'label' => 'Button Link',
            'type' => Controls_Manager::URL,
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('hero_image', [
            'label' => 'Hero Image',
            'type' => Controls_Manager::MEDIA,
            'default' => [ 'url' => Utils::get_placeholder_image_src() ],
            'dynamic' => ['active' => true],
        ]);

        $this->end_controls_section();

        // --- STYLE ---
        $this->start_controls_section('section_style', ['label' => 'Design', 'tab' => Controls_Manager::TAB_STYLE]);

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'banner_background',
                'label' => 'Banner Background',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .cora-banner-root',
                'fields_options' => [
                    'background' => [
                        'default' => 'gradient',
                    ],
                    'gradient' => [
                        'default' => [
                            'angle' => [ 'size' => 90, 'unit' => 'deg' ],
                            'color' => '#000000',
                            'color_b' => '#4A1D96', // Dark Purple
                        ],
                    ],
                ],
            ]
        );

        $this->add_control('title_color', [
            'label' => 'Title Color',
            'type' => Controls_Manager::COLOR,
            'default' => '#FFFFFF',
            'selectors' => ['{{WRAPPER}} .cora-title' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'title_typo',
            'label' => 'Title Typography',
            'selector' => '{{WRAPPER}} .cora-title',
        ]);

        $this->add_control('btn_gradient', [
            'label' => 'Button Gradient',
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('btn_bg_css', [
            'label' => 'Button Gradient CSS',
            'type' => Controls_Manager::TEXT,
            'default' => 'linear-gradient(90deg, #3B82F6 0%, #D946EF 100%)',
            'selectors' => ['{{WRAPPER}} .cora-btn' => 'background: {{VALUE}};'],
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
        ?>

        <style>
            .cora-root-<?php echo $id; ?> {
                display: flex;
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
                border-radius: 24px;
                padding: 48px;
                overflow: hidden;
                width: 100%;
                min-height: 380px;
                position: relative;
                gap: 40px;
                box-sizing: border-box;
                /* Background handled by Group Control */
            }

            /* --- Left Column: Content --- */
            .cora-root-<?php echo $id; ?> .cora-content-col {
                flex: 1;
                display: flex;
                flex-direction: column;
                align-items: flex-start;
                gap: 24px;
                z-index: 2;
                max-width: 550px;
            }

            .cora-root-<?php echo $id; ?> .cora-logo {
                height: 28px;
                width: auto;
                object-fit: contain;
                display: block;
            }

            .cora-root-<?php echo $id; ?> .cora-title {
                margin: 0;
                font-family: "Fredoka", sans-serif;
                font-size: 42px;
                font-weight: 500;
                line-height: 1.1;
                color: #FFFFFF;
            }

            .cora-root-<?php echo $id; ?> .cora-desc {
                margin: 0;
                font-family: "Inter", sans-serif;
                font-size: 16px;
                line-height: 1.6;
                color: rgba(255, 255, 255, 0.9);
                max-width: 90%;
            }

            /* --- Button: Vivid Gradient --- */
            .cora-root-<?php echo $id; ?> .cora-btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                padding: 14px 32px;
                border-radius: 100px;
                text-decoration: none;
                color: #FFFFFF;
                font-family: "Fredoka", sans-serif;
                font-size: 16px;
                font-weight: 600;
                transition: transform 0.2s ease, box-shadow 0.2s ease;
                border: none;
                cursor: pointer;
            }

            .cora-root-<?php echo $id; ?> .cora-btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 20px rgba(0,0,0,0.25);
            }

            /* --- Right Column: Image with Glass Frame --- */
            .cora-root-<?php echo $id; ?> .cora-media-col {
                flex: 1;
                display: flex;
                justify-content: flex-end;
                position: relative;
                height: 100%;
                min-height: 300px;
            }

            /* The Glass/Glow Frame Container */
            .cora-root-<?php echo $id; ?> .cora-img-frame {
                position: relative;
                border-radius: 20px;
                padding: 6px; /* Width of the border effect */
                background: linear-gradient(135deg, rgba(255,255,255,0.4) 0%, rgba(255,255,255,0.05) 100%);
                box-shadow: 0 20px 50px rgba(0,0,0,0.3);
                width: 100%;
                max-width: 450px;
                height: 100%;
            }

            .cora-root-<?php echo $id; ?> .cora-hero-img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                border-radius: 16px; /* Inner radius */
                display: block;
            }

            /* --- Responsive --- */
            @media (max-width: 1024px) {
                .cora-root-<?php echo $id; ?> {
                    flex-direction: column;
                    padding: 32px;
                    text-align: center;
                    height: auto;
                }
                
                .cora-root-<?php echo $id; ?> .cora-content-col {
                    align-items: center; /* Center text on mobile */
                    max-width: 100%;
                }

                .cora-root-<?php echo $id; ?> .cora-title {
                    font-size: 32px;
                }

                .cora-root-<?php echo $id; ?> .cora-media-col {
                    width: 100%;
                    justify-content: center;
                    min-height: 250px;
                }
                
                .cora-root-<?php echo $id; ?> .cora-img-frame {
                    width: 100%;
                }
            }
        </style>

        <div class="cora-unit-container cora-banner-root cora-root-<?php echo $id; ?>">
            
            <div class="cora-content-col">
                <?php if (!empty($settings['logo_image']['url'])) : ?>
                    <img src="<?php echo esc_url($settings['logo_image']['url']); ?>" class="cora-logo" alt="Logo">
                <?php endif; ?>

                <h2 class="cora-title"><?php echo esc_html($settings['title']); ?></h2>
                <p class="cora-desc"><?php echo esc_html($settings['desc']); ?></p>

                <?php if (!empty($settings['btn_text'])) : ?>
                    <a <?php echo $btn_attrs; ?> class="cora-btn">
                        <?php echo esc_html($settings['btn_text']); ?>
                    </a>
                <?php endif; ?>
            </div>

            <?php if (!empty($settings['hero_image']['url'])) : ?>
                <div class="cora-media-col">
                    <div class="cora-img-frame">
                        <img src="<?php echo esc_url($settings['hero_image']['url']); ?>" class="cora-hero-img" alt="Hero">
                    </div>
                </div>
            <?php endif; ?>

        </div>
        <?php
    }
}