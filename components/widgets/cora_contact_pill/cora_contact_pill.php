<?php
namespace Cora_Builder\Components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Typography;

if (!defined('ABSPATH'))
    exit;

class Cora_Contact_Pill extends Base_Widget
{
    public function get_name() { return 'cora_contact_pill'; }
    public function get_title() { return 'Cora Contact Pill'; }
    public function get_icon() { return 'eicon-mail'; }

    // Load Fonts
    public function get_style_depends() {
        wp_register_style('cora-fonts', 'https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap', [], null);
        return ['cora-fonts'];
    }

    protected function register_controls()
    {
        // --- CONTENT ---
        $this->start_controls_section('section_content', ['label' => 'Contact Info']);

        $this->add_control('icon', [
            'label' => 'Icon',
            'type' => Controls_Manager::ICONS,
            'default' => [ 'value' => 'far fa-envelope', 'library' => 'regular' ],
        ]);

        $this->add_control('label_text', [
            'label' => 'Top Label',
            'type' => Controls_Manager::TEXT,
            'default' => 'You can email us here',
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('contact_text', [
            'label' => 'Contact Detail',
            'type' => Controls_Manager::TEXT,
            'default' => 'hello@claraverse.in',
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('link', [
            'label' => 'Link',
            'type' => Controls_Manager::URL,
            'placeholder' => 'mailto:hello@claraverse.in',
            'dynamic' => ['active' => true],
        ]);

        $this->end_controls_section();

        // --- STYLE ---
        $this->start_controls_section('section_style', ['label' => 'Design', 'tab' => Controls_Manager::TAB_STYLE]);

        $this->add_control('card_bg', [
            'label' => 'Card Background',
            'type' => Controls_Manager::COLOR,
            'default' => '#FFFFFF',
            'selectors' => ['{{WRAPPER}} .cora-contact-root' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_control('border_color', [
            'label' => 'Border Color',
            'type' => Controls_Manager::COLOR,
            'default' => '#94A3B8', // Slate Grey Border
            'selectors' => ['{{WRAPPER}} .cora-contact-root' => 'border-color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'detail_typo',
            'label' => 'Contact Text Typography',
            'selector' => '{{WRAPPER}} .cora-contact-detail',
        ]);

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $id = $this->get_id();

        // Link Logic (Entire Card Clickable)
        $wrapper_tag = 'div';
        $wrapper_attrs = '';
        if ( ! empty($settings['link']['url']) ) {
            $wrapper_tag = 'a';
            $this->add_link_attributes('card_link', $settings['link']);
            $wrapper_attrs = $this->get_render_attribute_string('card_link');
        }
        ?>

        <style>
            .cora-root-<?php echo $id; ?> {
                display: flex;
                align-items: center;
                justify-content: space-between;
                background-color: #FFFFFF;
                border: 1.5px solid #64748B; /* Matches visual reference grey/blue border */
                border-radius: 12px;
                padding: 16px 24px;
                width: 100%;
                max-width: 450px; /* Constrain width like a pill */
                box-sizing: border-box;
                gap: 20px;
                text-decoration: none; /* Remove underline from link wrapper */
                transition: transform 0.2s ease, box-shadow 0.2s ease;
                cursor: pointer;
            }

            .cora-root-<?php echo $id; ?>:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            }

            /* --- Left Icon Box --- */
            .cora-root-<?php echo $id; ?> .cora-icon-box {
                width: 48px;
                height: 48px;
                display: flex;
                align-items: center;
                justify-content: center;
                border: 1px solid #E2E8F0;
                border-radius: 12px;
                color: #0F172A;
                font-size: 20px;
                flex-shrink: 0;
            }

            /* --- Middle Text --- */
            .cora-root-<?php echo $id; ?> .cora-text-group {
                flex: 1;
                display: flex;
                flex-direction: column;
                gap: 4px;
                text-align: left;
            }

            .cora-root-<?php echo $id; ?> .cora-label {
                font-family: "Inter", sans-serif;
                font-size: 14px;
                color: #64748B;
                margin: 0;
                line-height: 1.2;
            }

            .cora-root-<?php echo $id; ?> .cora-contact-detail {
                font-family: "Fredoka", sans-serif;
                font-size: 16px;
                font-weight: 600;
                color: #0F172A;
                margin: 0;
                line-height: 1.2;
                text-decoration: underline;
                text-decoration-color: #CBD5E1; /* Subtle underline */
                text-underline-offset: 4px;
            }

            /* --- Right Action Button (Arrow) --- */
            .cora-root-<?php echo $id; ?> .cora-action-btn {
                width: 40px;
                height: 40px;
                display: flex;
                align-items: center;
                justify-content: center;
                border: 1px solid #E2E8F0;
                border-radius: 50%; /* Circle */
                color: #0F172A;
                font-size: 14px;
                flex-shrink: 0;
                transition: background-color 0.2s ease;
            }

            .cora-root-<?php echo $id; ?>:hover .cora-action-btn {
                background-color: #F8FAFC;
                border-color: #CBD5E1;
            }

            /* --- Responsive --- */
            @media (max-width: 480px) {
                .cora-root-<?php echo $id; ?> {
                    padding: 16px;
                    gap: 12px;
                }
                .cora-root-<?php echo $id; ?> .cora-icon-box {
                    width: 40px; height: 40px; font-size: 18px;
                }
                .cora-root-<?php echo $id; ?> .cora-contact-detail {
                    font-size: 15px;
                }
            }
        </style>

        <<?php echo $wrapper_tag; ?> class="cora-unit-container cora-contact-root cora-root-<?php echo $id; ?>" <?php echo $wrapper_attrs; ?>>
            
            <div class="cora-icon-box">
                <?php Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] ); ?>
            </div>

            <div class="cora-text-group">
                <p class="cora-label"><?php echo esc_html($settings['label_text']); ?></p>
                <h4 class="cora-contact-detail"><?php echo esc_html($settings['contact_text']); ?></h4>
            </div>

            <div class="cora-action-btn">
                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1 13L13 1M13 1H4.5M13 1V9.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>

        </<?php echo $wrapper_tag; ?>>
        <?php
    }
}