<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Service_Card extends Base_Widget {

    public function get_name() { return 'cora_service_card'; }
    public function get_title() { return __( 'Cora Service Card (Clara)', 'cora-builder' ); }
    public function get_icon() { return 'eicon-info-box'; }

    protected function register_controls() {
        
        // --- TAB 1: CONTENT ---
        $this->start_controls_section('section_badge', [ 'label' => __( 'Badge Settings', 'cora-builder' ) ]);

        $this->add_control('show_badge', [
            'label' => 'Show Badge',
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('badge_text', [
            'label' => 'Badge Label',
            'type' => Controls_Manager::TEXT,
            'default' => 'Design',
            'dynamic' => ['active' => true],
            'condition' => ['show_badge' => 'yes'],
        ]);

        $this->add_control('icon_source', [
            'label' => 'Icon Source',
            'type' => Controls_Manager::SELECT,
            'default' => 'library',
            'options' => [
                'library' => 'Icon Library',
                'custom'  => 'Custom SVG',
            ],
            'condition' => ['show_badge' => 'yes'],
        ]);

        $this->add_control('badge_icon', [
            'label' => 'Icon',
            'type' => Controls_Manager::ICONS,
            'default' => [ 'value' => 'fas fa-fire', 'library' => 'solid' ],
            'condition' => ['show_badge' => 'yes', 'icon_source' => 'library'],
        ]);

        $this->add_control('custom_svg', [
            'label' => 'SVG Code',
            'type' => Controls_Manager::TEXTAREA,
            'rows' => 3,
            'description' => 'Paste raw SVG code here.',
            'condition' => ['show_badge' => 'yes', 'icon_source' => 'custom'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_content', [ 'label' => __( 'Card Content', 'cora-builder' ) ]);

        $this->add_control('title', [
            'label' => 'Title',
            'type' => Controls_Manager::TEXTAREA,
            'default' => 'Clara – UI/UX & Prototype',
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('desc', [
            'label' => 'Description',
            'type' => Controls_Manager::TEXTAREA,
            'default' => 'Get conversion-focused branding, UI, and systems tailored to your business goals – not likes.',
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('card_image', [
            'label' => 'Preview Image',
            'type' => Controls_Manager::MEDIA,
            'default' => [ 'url' => Utils::get_placeholder_image_src() ],
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('card_link', [
            'label' => 'Card Link',
            'type' => Controls_Manager::URL,
            'placeholder' => 'https://...',
            'dynamic' => ['active' => true],
        ]);

        $this->end_controls_section();

        // --- TAB 2: STYLE ---
        $this->start_controls_section('style_section', [ 'label' => __( 'Design & Colors', 'cora-builder' ), 'tab' => Controls_Manager::TAB_STYLE ]);

        $this->add_control('card_bg', [
            'label' => 'Card Background',
            'type' => Controls_Manager::COLOR,
            'default' => '#FFFFFF',
            'selectors' => ['{{WRAPPER}} .cora-svc-card' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_control('border_color', [
            'label' => 'Border Color',
            'type' => Controls_Manager::COLOR,
            'default' => '#E2E8F0',
            'selectors' => ['{{WRAPPER}} .cora-svc-card' => 'border-color: {{VALUE}};'],
        ]);

        $this->add_control('title_color', [
            'label' => 'Title Color',
            'type' => Controls_Manager::COLOR,
            'default' => '#0F172A',
            'selectors' => ['{{WRAPPER}} .cora-svc-title' => 'color: {{VALUE}};'],
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $id = $this->get_id();

        // Wrapper Tag logic
        $wrapper_tag = 'div';
        $wrapper_attrs = '';
        if ( ! empty( $settings['card_link']['url'] ) ) {
            $wrapper_tag = 'a';
            $this->add_link_attributes( 'card_link', $settings['card_link'] );
            $wrapper_attrs = $this->get_render_attribute_string( 'card_link' );
        }
        ?>

        <style>
            .cora-svc-card-<?php echo $id; ?> {
                display: flex;
                min-width: 320px;
                flex-direction: column;
                background-color: #FFFFFF;
                border: 1px solid #E2E8F0;
                border-radius: 24px; /* Matches Ref */
                padding:  20px ; /* Bottom padding 0 so image hits bottom */
                box-sizing: border-box;
                gap: 20px;
                transition: transform 0.3s ease, box-shadow 0.3s ease;
                text-decoration: none;
                position: relative;
                overflow: hidden;
                min-height: 400px; /* Ensures nice height */
            }

            .cora-svc-card-<?php echo $id; ?>:hover {
                transform: translateY(-6px);
                box-shadow: 0 20px 40px rgba(0,0,0,0.06);
            }

            /* --- Badge --- */
            .cora-svc-badge-<?php echo $id; ?> {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 8px 16px;
                border: 1.5px solid #0F172A; /* Dark distinct border */
                border-radius: 100px;
                width: fit-content;
                color: #0F172A;
                font-family: "Inter", sans-serif;
                font-weight: 700;
                font-size: 14px;
                line-height: 1;
                margin-bottom: 4px;
            }

            .cora-svc-icon-<?php echo $id; ?> {
                display: flex;
                align-items: center;
                font-size: 14px;
            }
            .cora-svc-icon-<?php echo $id; ?> svg {
                width: 1em; height: 1em; fill: currentColor;
            }

            /* --- Typography --- */
            .cora-svc-content-<?php echo $id; ?> {
                display: flex;
                flex-direction: column;
                gap: 12px;
                z-index: 2;
            }

            .cora-svc-title-<?php echo $id; ?> {
                font-family: "Inter", sans-serif;
                font-size: 28px; /* Desktop Size */
                font-weight: 800;
                color: #0F172A;
                margin: 0;
                line-height: 1.2;
                letter-spacing: -0.02em;
            }

            .cora-svc-desc-<?php echo $id; ?> {
                font-family: "Inter", sans-serif;
                font-size: 16px;
                color: #64748B;
                line-height: 1.6;
                margin: 0;
                max-width: 90%;
            }

            /* --- Image Frame --- */
            .cora-svc-img-frame-<?php echo $id; ?> {
                width: 100%;
                margin-top: auto; /* Pushes image to bottom */
                border-radius: 16px;
                overflow: hidden;
                background: #F1F5F9;
                position: relative;
                /* Optional: Add a subtle shadow to the internal image frame if desired */
                box-shadow: 0 -4px 20px rgba(0,0,0,0.02); 
            }

            .cora-svc-img-<?php echo $id; ?> {
                width: 100%;
                height: auto;
                display: block;
                object-fit: cover;
                transition: transform 0.5s ease;
            }

            .cora-svc-card-<?php echo $id; ?>:hover .cora-svc-img-<?php echo $id; ?> {
                transform: scale(1.03); /* Subtle zoom on hover */
            }

            /* --- RESPONSIVE --- */
            @media (max-width: 768px) {
                .cora-svc-card-<?php echo $id; ?> {
                    padding: 24px 24px 0 24px;
                    min-height: auto; /* Let height flow naturally on mobile */
                }
                .cora-svc-title-<?php echo $id; ?> {
                    font-size: 24px; /* Smaller title on mobile */
                }
                .cora-svc-desc-<?php echo $id; ?> {
                    font-size: 15px;
                }
            }
        </style>

        <<?php echo $wrapper_tag; ?> class="cora-unit-container cora-svc-card-<?php echo $id; ?> cora-svc-card" <?php echo $wrapper_attrs; ?>>
            
            <?php if ( 'yes' === $settings['show_badge'] ) : ?>
                <div class="cora-svc-badge-<?php echo $id; ?>">
                    <span class="cora-svc-icon-<?php echo $id; ?>">
                        <?php if ( 'custom' === $settings['icon_source'] ) : ?>
                            <?php echo $settings['custom_svg']; ?>
                        <?php else : ?>
                            <?php Icons_Manager::render_icon( $settings['badge_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                        <?php endif; ?>
                    </span>
                    <span><?php echo esc_html($settings['badge_text']); ?></span>
                </div>
            <?php endif; ?>

            <div class="cora-svc-content-<?php echo $id; ?>">
                <h3 class="cora-svc-title-<?php echo $id; ?> cora-svc-title"><?php echo esc_html($settings['title']); ?></h3>
                <p class="cora-svc-desc-<?php echo $id; ?>"><?php echo esc_html($settings['desc']); ?></p>
            </div>

            <?php if ( ! empty( $settings['card_image']['url'] ) ) : ?>
                <div class="cora-svc-img-frame-<?php echo $id; ?>">
                    <img src="<?php echo esc_url($settings['card_image']['url']); ?>" class="cora-svc-img-<?php echo $id; ?>" alt="<?php echo esc_attr($settings['title']); ?>">
                </div>
            <?php endif; ?>

        </<?php echo $wrapper_tag; ?>>
        <?php
    }
}