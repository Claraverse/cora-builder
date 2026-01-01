<?php
namespace Cora_Builder\Components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

if (!defined('ABSPATH'))
    exit;

class Cora_Status_Pill extends Base_Widget
{
    public function get_name() { return 'cora_status_pill'; }
    public function get_title() { return 'Cora Status Pill'; }
    public function get_icon() { return 'eicon-info-circle'; }

    // Load Fonts
    public function get_style_depends() {
        wp_register_style('cora-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap', [], null);
        return ['cora-fonts'];
    }

    protected function register_controls()
    {
        // --- CONTENT ---
        $this->start_controls_section('section_content', ['label' => 'Content']);

        $this->add_control('status_text', [
            'label' => 'Status Label',
            'type' => Controls_Manager::TEXT,
            'default' => 'All Systems Operational',
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('show_dot', [
            'label' => 'Show Status Dot',
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('enable_pulse', [
            'label' => 'Dot Pulse Effect',
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
            'condition' => ['show_dot' => 'yes'],
        ]);

        $this->add_control('show_divider', [
            'label' => 'Show Divider',
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('store_count', [
            'label' => 'Secondary Label / Count',
            'type' => Controls_Manager::TEXT,
            'default' => '2,847 stores',
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('pill_link', [
            'label' => 'Link',
            'type' => Controls_Manager::URL,
            'dynamic' => ['active' => true],
        ]);

        $this->end_controls_section();

        // --- STYLE ---
        $this->start_controls_section('section_style', ['label' => 'Design', 'tab' => Controls_Manager::TAB_STYLE]);

        $this->add_control('pill_bg_color', [
            'label' => 'Background Color',
            'type' => Controls_Manager::COLOR,
            'default' => '#FFFFFF',
            'selectors' => ['{{WRAPPER}} .status-pill-inner' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Border::get_type(), [
            'name' => 'pill_border',
            'selector' => '{{WRAPPER}} .status-pill-inner',
            'fields_options' => [
                'border' => ['default' => 'solid'],
                'width' => ['default' => ['top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1, 'isLinked' => true]],
                'color' => ['default' => '#E2E8F0'],
            ],
        ]);

        $this->add_group_control(Group_Control_Box_Shadow::get_type(), [
            'name' => 'pill_shadow',
            'selector' => '{{WRAPPER}} .status-pill-inner',
        ]);

        $this->add_responsive_control('pill_padding', [
            'label' => 'Padding',
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'default' => ['top' => 12, 'right' => 24, 'bottom' => 12, 'left' => 24, 'unit' => 'px', 'isLinked' => false],
            'selectors' => ['{{WRAPPER}} .status-pill-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        // Dot Style
        $this->add_control('dot_style_heading', ['label' => 'Dot Styling', 'type' => Controls_Manager::HEADING, 'separator' => 'before', 'condition' => ['show_dot' => 'yes']]);

        $this->add_control('dot_color', [
            'label' => 'Dot Color',
            'type' => Controls_Manager::COLOR,
            'default' => '#000000',
            'selectors' => ['{{WRAPPER}} .status-dot' => 'background-color: {{VALUE}};'],
            'condition' => ['show_dot' => 'yes'],
        ]);

        // Text Style
        $this->add_control('text_style_heading', ['label' => 'Typography', 'type' => Controls_Manager::HEADING, 'separator' => 'before']);

        $this->add_control('text_color', [
            'label' => 'Text Color',
            'type' => Controls_Manager::COLOR,
            'default' => '#0F172A',
            'selectors' => ['{{WRAPPER}} .status-label, {{WRAPPER}} .count-label' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'text_typo',
            'selector' => '{{WRAPPER}} .status-label, {{WRAPPER}} .count-label',
        ]);

        // Divider Style
        $this->add_control('divider_style_heading', ['label' => 'Divider', 'type' => Controls_Manager::HEADING, 'separator' => 'before', 'condition' => ['show_divider' => 'yes']]);

        $this->add_control('divider_color', [
            'label' => 'Divider Color',
            'type' => Controls_Manager::COLOR,
            'default' => '#E2E8F0',
            'selectors' => ['{{WRAPPER}} .status-divider' => 'background-color: {{VALUE}};'],
            'condition' => ['show_divider' => 'yes'],
        ]);

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $id = $this->get_id();
        $has_link = !empty($settings['pill_link']['url']);
        $tag = $has_link ? 'a' : 'div';
        
        $this->add_render_attribute('pill', 'class', 'status-pill-inner');
        if ($has_link) {
            $this->add_link_attributes('pill', $settings['pill_link']);
        }

        // Pulse logic
        $pulse_class = ('yes' === $settings['enable_pulse']) ? 'pulse-active' : '';
        ?>

        <style>
            /* Container */
            .cora-root-<?php echo $id; ?> {
                display: flex;
                justify-content: flex-start; /* Default alignment */
            }

            /* Pill Box */
            .cora-root-<?php echo $id; ?> .status-pill-inner {
                display: inline-flex;
                align-items: center;
                gap: 16px;
                background-color: #FFFFFF;
                border: 1px solid #E2E8F0;
                border-radius: 100px;
                padding: 12px 24px;
                text-decoration: none;
                transition: transform 0.2s ease, box-shadow 0.2s ease;
                box-sizing: border-box;
            }

            .cora-root-<?php echo $id; ?> .status-pill-inner:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            }

            /* Status Dot (Fixed) */
            .cora-root-<?php echo $id; ?> .status-dot {
                width: 8px;
                height: 8px;
                background-color: #000000;
                border-radius: 50%;
                position: relative;
                flex-shrink: 0; /* Prevents squashing */
                display: block; /* Ensures visibility */
            }

            /* Pulse Animation */
            .cora-root-<?php echo $id; ?> .pulse-active .status-dot::after {
                content: '';
                position: absolute;
                top: 50%; left: 50%;
                transform: translate(-50%, -50%);
                width: 100%; height: 100%;
                border-radius: 50%;
                background-color: inherit; /* Matches parent color */
                opacity: 0.4;
                animation: cora-pill-pulse 2s infinite;
                z-index: 0;
            }

            @keyframes cora-pill-pulse {
                0% { transform: translate(-50%, -50%) scale(1); opacity: 0.4; }
                100% { transform: translate(-50%, -50%) scale(3); opacity: 0; }
            }

            /* Text */
            .cora-root-<?php echo $id; ?> .status-label, 
            .cora-root-<?php echo $id; ?> .count-label {
                margin: 0;
                font-family: "Inter", sans-serif;
                font-size: 14px;
                font-weight: 600;
                color: #0F172A;
                white-space: nowrap;
                line-height: 1;
            }

            /* Vertical Divider */
            .cora-root-<?php echo $id; ?> .status-divider {
                width: 1px;
                height: 20px;
                background-color: #E2E8F0;
                flex-shrink: 0;
            }

            /* Responsive */
            @media (max-width: 768px) {
                .cora-root-<?php echo $id; ?> .status-pill-inner {
                    padding: 10px 20px;
                    gap: 12px;
                    width: 100%; /* Optional: Full width on mobile */
                    justify-content: center;
                }
                .cora-root-<?php echo $id; ?> .status-label, 
                .cora-root-<?php echo $id; ?> .count-label {
                    font-size: 13px;
                }
            }
        </style>

        <div class="cora-unit-container cora-root-<?php echo $id; ?>">
            <<?php echo $tag; ?> <?php echo $this->get_render_attribute_string('pill'); ?> class="<?php echo esc_attr($pulse_class); ?>">
                
                <?php if ('yes' === $settings['show_dot']) : ?>
                    <span class="status-dot"></span>
                <?php endif; ?>
                
                <?php if (!empty($settings['status_text'])) : ?>
                    <span class="status-label"><?php echo esc_html($settings['status_text']); ?></span>
                <?php endif; ?>
                
                <?php if ('yes' === $settings['show_divider']) : ?>
                    <div class="status-divider"></div>
                <?php endif; ?>
                
                <?php if (!empty($settings['store_count'])) : ?>
                    <span class="count-label"><?php echo esc_html($settings['store_count']); ?></span>
                <?php endif; ?>
                
            </<?php echo $tag; ?>>
        </div>
        <?php
    }
}