<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Advance_Heading_Two extends Base_Widget {

    public function get_name() { return 'cora_advance_heading_two'; }
    public function get_title() { return __( 'Cora Advance Heading Two', 'cora-builder' ); }
    public function get_icon() { return 'eicon-heading'; }

    protected function register_controls() {
        
        // --- TAB 1: CONTENT ---
        $this->start_controls_section('content_section', [ 'label' => __( 'Content', 'cora-builder' ) ]);

        $this->add_control('title', [
            'label'       => 'Title',
            'type'        => Controls_Manager::TEXTAREA,
            'default'     => 'Our Ecosystem',
            'dynamic'     => [ 'active' => true ],
        ]);

        $this->add_control('subtitle', [
            'label'       => 'Subtitle',
            'type'        => Controls_Manager::TEXTAREA,
            'default'     => 'Browse from our ever growing collection of components that helps your website stand out.',
            'dynamic'     => [ 'active' => true ],
        ]);

        $this->add_control('btn_text', [
            'label'       => 'Button Text',
            'type'        => Controls_Manager::TEXT,
            'default'     => 'Clara Labs',
            'dynamic'     => [ 'active' => true ],
        ]);

        $this->add_control('btn_link', [
            'label'       => 'Button Link',
            'type'        => Controls_Manager::URL,
            'placeholder' => 'https://...',
            'dynamic'     => [ 'active' => true ],
        ]);

        $this->end_controls_section();

        // --- TAB 2: STYLE ---
        $this->start_controls_section('style_section', [ 'label' => __( 'Style', 'cora-builder' ), 'tab' => Controls_Manager::TAB_STYLE ]);

        // Alignment
        $this->add_responsive_control('text_align', [
            'label' => 'Alignment',
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'left' => [ 'title' => 'Left', 'icon' => 'eicon-text-align-left' ],
                'center' => [ 'title' => 'Center', 'icon' => 'eicon-text-align-center' ],
                'right' => [ 'title' => 'Right', 'icon' => 'eicon-text-align-right' ],
            ],
            'default' => 'left',
            'selectors' => [
                '{{WRAPPER}} .cora-adv-heading' => 'text-align: {{VALUE}};',
                '{{WRAPPER}} .cora-adv-content' => 'align-items: {{VALUE}};', // For flex column alignment
            ],
        ]);

        // 1. Title Style
        $this->add_control('title_heading', [
            'label' => 'Title',
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('title_color', [
            'label'     => 'Color',
            'type'      => Controls_Manager::COLOR,
            'default'   => '#1e293b',
            'selectors' => [ '{{WRAPPER}} .cora-adv-title' => 'color: {{VALUE}};' ],
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typo',
                'selector' => '{{WRAPPER}} .cora-adv-title',
            ]
        );

        // 2. Subtitle Style
        $this->add_control('subtitle_heading', [
            'label' => 'Subtitle',
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('subtitle_color', [
            'label'     => 'Color',
            'type'      => Controls_Manager::COLOR,
            'default'   => '#475569',
            'selectors' => [ '{{WRAPPER}} .cora-adv-desc' => 'color: {{VALUE}};' ],
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'subtitle_typo',
                'selector' => '{{WRAPPER}} .cora-adv-desc',
            ]
        );

        // 3. Button Style
        $this->add_control('btn_heading', [
            'label' => 'Button',
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'btn_typo',
                'selector' => '{{WRAPPER}} .cora-adv-btn',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $id = $this->get_id();

        // Link Logic
        if ( ! empty( $settings['btn_link']['url'] ) ) {
            $this->add_link_attributes( 'btn_attr', $settings['btn_link'] );
        }

        // Helpers for alignment classes (Safe Check)
        $text_align = isset($settings['text_align']) && !empty($settings['text_align']) ? $settings['text_align'] : 'left';
        $align_class = 'align-' . $text_align;
        ?>

        <style>
            /* Main Wrapper */
            .cora-adv-heading-<?php echo $id; ?> {
                display: flex;
                align-items: flex-end; 
                justify-content: space-between;
                width: 100%;
                gap: clamp(24px, 4vw, 40px);
                flex-wrap: wrap; 
            }

            /* Alignment Logic */
            .cora-adv-heading-<?php echo $id; ?>.align-left { text-align: left; }
            .cora-adv-heading-<?php echo $id; ?>.align-center { 
                flex-direction: column; 
                align-items: center; 
                text-align: center; 
            }
            .cora-adv-heading-<?php echo $id; ?>.align-right { 
                flex-direction: row-reverse; 
                text-align: right; 
                justify-content: space-between;
            }

            /* Content Group */
            .cora-adv-content-<?php echo $id; ?> {
                display: flex;
                flex-direction: column;
                gap: 12px;
                max-width: 650px;
                flex: 1 1 400px;
            }

            /* Typography */
            .cora-adv-title {
                font-family: "Inter", sans-serif;
                font-size: clamp(32px, 5vw, 48px);
                font-weight: 800;
                line-height: 1.1;
                margin: 0;
                color: #1e293b;
                letter-spacing: -0.03em;
            }

            .cora-adv-desc {
                font-family: "Inter", sans-serif;
                font-size: clamp(16px, 2vw, 18px);
                line-height: 1.6;
                margin: 0;
                color: #475569;
                max-width: 90%;
            }
            
            /* Alignment Overrides */
            .align-center .cora-adv-desc { margin: 0 auto; }
            .align-right .cora-adv-desc { margin-left: auto; margin-right: 0; }

            /* Button Styling */
            .cora-adv-btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                background-color: #F8FAFC;
                border: 1px solid #E2E8F0;
                border-radius: 100px;
                padding: 12px 28px;
                font-family: "Inter", sans-serif;
                font-size: 15px;
                font-weight: 600;
                color: #0F172A;
                text-decoration: none;
                white-space: nowrap;
                transition: all 0.2s ease;
                gap: 8px;
                flex-shrink: 0; 
            }

            .cora-adv-btn:hover {
                background-color: #FFFFFF;
                box-shadow: 0 4px 12px rgba(0,0,0,0.06);
                transform: translateY(-2px);
                border-color: #cbd5e1;
            }

            .cora-adv-icon {
                font-size: 14px;
                transition: transform 0.2s ease;
            }

            .cora-adv-btn:hover .cora-adv-icon {
                transform: translate(2px, -2px);
            }

            /* --- RESPONSIVE BREAKPOINT (Mobile Stack) --- */
            @media (max-width: 768px) {
                .cora-adv-heading-<?php echo $id; ?> {
                    flex-direction: column !important; /* Force stack on mobile regardless of alignment */
                    align-items: flex-start;
                    gap: 24px;
                }
                
                /* Keep alignment classes active but adjust if needed */
                .cora-adv-heading-<?php echo $id; ?>.align-center { align-items: center; }
                .cora-adv-heading-<?php echo $id; ?>.align-right { align-items: flex-end; }

                .cora-adv-content-<?php echo $id; ?> {
                    width: 100%;
                    max-width: 100%;
                    flex: auto;
                }

                .cora-adv-desc { max-width: 100%; }

                .cora-adv-btn {
                    width: 100%;
                }
            }
        </style>

        <div class="cora-unit-container cora-adv-heading-<?php echo $id; ?> cora-adv-heading <?php echo esc_attr($align_class); ?>">
            
            <div class="cora-adv-content-<?php echo $id; ?> cora-adv-content">
                <h2 class="cora-adv-title"><?php echo esc_html($settings['title']); ?></h2>
                <p class="cora-adv-desc"><?php echo esc_html($settings['subtitle']); ?></p>
            </div>

            <?php if ( ! empty( $settings['btn_text'] ) ) : ?>
                <a <?php echo $this->get_render_attribute_string( 'btn_attr' ); ?> class="cora-adv-btn">
                    <?php echo esc_html($settings['btn_text']); ?>
                    <svg class="cora-adv-icon" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1 11L11 1M11 1H3.5M11 1V8.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
            <?php endif; ?>

        </div>
        <?php
    }
}