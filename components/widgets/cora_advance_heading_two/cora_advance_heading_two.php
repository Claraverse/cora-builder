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
        $this->start_controls_section('style_section', [ 'label' => __( 'Typography & Colors', 'cora-builder' ), 'tab' => Controls_Manager::TAB_STYLE ]);

        $this->add_control('title_color', [
            'label'     => 'Title Color',
            'type'      => Controls_Manager::COLOR,
            'default'   => '#1e293b',
            'selectors' => [ '{{WRAPPER}} .cora-adv-title' => 'color: {{VALUE}};' ],
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typo',
                'label'    => 'Title Typography',
                'selector' => '{{WRAPPER}} .cora-adv-title',
            ]
        );

        $this->add_control('subtitle_color', [
            'label'     => 'Subtitle Color',
            'type'      => Controls_Manager::COLOR,
            'default'   => '#475569',
            'selectors' => [ '{{WRAPPER}} .cora-adv-desc' => 'color: {{VALUE}};' ],
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $id = $this->get_id();

        // Link Logic
        if ( ! empty( $settings['btn_link']['url'] ) ) {
            $this->add_link_attributes( 'btn_attr', $settings['btn_link'] );
        }
        ?>

        <style>
            /* Main Wrapper */
            .cora-adv-heading-<?php echo $id; ?> {
                display: flex;
                align-items: flex-end; /* Aligns text bottom with button */
                justify-content: space-between;
                width: 100%;
                gap: 40px;
                text-align: left;
            }

            /* Content Group (Title + Subtitle) */
            .cora-adv-content-<?php echo $id; ?> {
                display: flex;
                flex-direction: column;
                gap: 16px;
                max-width: 650px;
            }

            /* Typography Defaults (Matches Screenshot) */
            .cora-adv-title {
                font-family: "Inter", sans-serif;
                font-size: 48px;
                font-weight: 800;
                line-height: 1.1;
                margin: 0;
                color: #1e293b;
                letter-spacing: -0.02em;
            }

            .cora-adv-desc {
                font-family: "Inter", sans-serif;
                font-size: 18px;
                line-height: 1.6;
                margin: 0;
                color: #475569;
            }

            /* Button Styling (Pill Shape) */
            .cora-adv-btn-<?php echo $id; ?> {
                display: inline-flex;
                align-items: center;
                background-color: #F8FAFC;
                border: 1px solid #E2E8F0;
                border-radius: 100px;
                padding: 12px 24px;
                font-family: "Inter", sans-serif;
                font-size: 15px;
                font-weight: 600;
                color: #0F172A;
                text-decoration: none;
                white-space: nowrap;
                transition: all 0.2s ease;
                gap: 8px;
                flex-shrink: 0; /* Prevents button from squishing */
            }

            .cora-adv-btn-<?php echo $id; ?>:hover {
                background-color: #FFFFFF;
                box-shadow: 0 4px 12px rgba(0,0,0,0.05);
                transform: translateY(-2px);
                color: #0F172A;
            }

            .cora-adv-icon {
                font-size: 14px;
                transition: transform 0.2s ease;
            }

            .cora-adv-btn-<?php echo $id; ?>:hover .cora-adv-icon {
                transform: translate(2px, -2px);
            }

            /* --- RESPONSIVE BREAKPOINT (Mobile Stack) --- */
            @media (max-width: 768px) {
                .cora-adv-heading-<?php echo $id; ?> {
                    flex-direction: column;
                    align-items: flex-start;
                    gap: 24px;
                }
                
                .cora-adv-title {
                    font-size: 36px; /* Smaller Title on Mobile */
                    text-align: center;
                }
                .cora-adv-desc {
                     text-align: center;
                }

                .cora-adv-btn-<?php echo $id; ?> {
                    width: 100%; /* Full width button on mobile */
                    justify-content: center;
                }
            }
        </style>

        <div class="cora-unit-container cora-adv-heading-<?php echo $id; ?>">
            
            <div class="cora-adv-content-<?php echo $id; ?>">
                <h2 class="cora-adv-title"><?php echo esc_html($settings['title']); ?></h2>
                <p class="cora-adv-desc"><?php echo esc_html($settings['subtitle']); ?></p>
            </div>

            <?php if ( ! empty( $settings['btn_text'] ) ) : ?>
                <a <?php echo $this->get_render_attribute_string( 'btn_attr' ); ?> class="cora-adv-btn-<?php echo $id; ?>">
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