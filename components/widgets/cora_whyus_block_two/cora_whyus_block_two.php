<?php
namespace Cora_Builder\Components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Utils;

if (!defined('ABSPATH'))
    exit;

class Cora_WhyUs_Block_Two extends Base_Widget
{
    public function get_name() { return 'cora_whyus_block_two'; }
    public function get_title() { return 'Cora – Why Us Block Two'; }
    public function get_icon() { return 'eicon-info-box'; }

    // Load Fonts
    public function get_style_depends() {
        wp_register_style('cora-google-fonts', 'https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&family=Inter:wght@400;500&display=swap', [], null);
        return ['cora-google-fonts'];
    }

    protected function register_controls()
    {
        // --- CONTENT ---
        $this->start_controls_section('section_content', ['label' => 'Content']);

        $this->add_control('title', [
            'label' => 'Title',
            'type' => Controls_Manager::TEXTAREA,
            'default' => 'SEO & Local Visibility Setup',
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('desc', [
            'label' => 'Description',
            'type' => Controls_Manager::TEXTAREA,
            'default' => 'Seamlessly plan within SAP for enhanced collaboration and productivity.',
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('link', [
            'label' => 'Link / Action',
            'type' => Controls_Manager::URL,
            'placeholder' => 'https://your-link.com',
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('image', [
            'label' => 'Image',
            'type' => Controls_Manager::MEDIA,
            'default' => [ 'url' => Utils::get_placeholder_image_src() ],
            'dynamic' => ['active' => true],
        ]);

        $this->end_controls_section();

        // --- STYLE: CONTAINER ---
        $this->start_controls_section('section_style_container', ['label' => 'Container', 'tab' => Controls_Manager::TAB_STYLE]);

        $this->add_control('bg_color', [
            'label' => 'Background Color',
            'type' => Controls_Manager::COLOR,
            'default' => '#FFFFFF',
            'selectors' => ['{{WRAPPER}} .cora-why-root' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('box_padding', [
            'label' => 'Padding',
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'default' => [
                'top' => 32, 'right' => 32, 'bottom' => 32, 'left' => 32, 'unit' => 'px',
                'isLinked' => true,
            ],
            'selectors' => [
                '{{WRAPPER}} .cora-why-root' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('min_height', [
            'label' => 'Min Height (Desktop)',
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px', 'vh'],
            'range' => ['px' => ['min' => 200, 'max' => 1000]],
            'default' => ['unit' => 'px', 'size' => 450],
            'selectors' => ['{{WRAPPER}} .cora-why-root' => 'min-height: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_group_control(Group_Control_Border::get_type(), [
            'name' => 'border',
            'selector' => '{{WRAPPER}} .cora-why-root',
            'fields_options' => [
                'border' => ['default' => 'solid'],
                'width' => ['default' => ['top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1, 'unit' => 'px']],
                'color' => ['default' => '#F1F5F9'],
            ],
        ]);

        $this->add_control('border_radius', [
            'label' => 'Border Radius',
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'default' => ['top' => 24, 'right' => 24, 'bottom' => 24, 'left' => 24, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .cora-why-root' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_group_control(Group_Control_Box_Shadow::get_type(), [
            'name' => 'box_shadow',
            'selector' => '{{WRAPPER}} .cora-why-root',
        ]);

        $this->end_controls_section();

        // --- STYLE: CONTENT ---
        $this->start_controls_section('section_style_content', ['label' => 'Content Styling', 'tab' => Controls_Manager::TAB_STYLE]);

        $this->add_control('title_color', [
            'label' => 'Title Color',
            'type' => Controls_Manager::COLOR,
            'default' => '#1E293B',
            'selectors' => ['{{WRAPPER}} .cora-title' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'title_typo',
            'label' => 'Title Typography',
            'selector' => '{{WRAPPER}} .cora-title',
        ]);

        $this->add_control('desc_color', [
            'label' => 'Description Color',
            'type' => Controls_Manager::COLOR,
            'default' => '#64748B',
            'selectors' => ['{{WRAPPER}} .cora-desc' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'desc_typo',
            'label' => 'Desc Typography',
            'selector' => '{{WRAPPER}} .cora-desc',
        ]);

        $this->add_responsive_control('content_gap', [
            'label' => 'Gap (Title to Desc)',
            'type' => Controls_Manager::SLIDER,
            'default' => ['size' => 12, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .cora-content-inner' => 'gap: {{SIZE}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        // --- STYLE: ARROW ICON ---
        $this->start_controls_section('section_style_arrow', ['label' => 'Arrow Button', 'tab' => Controls_Manager::TAB_STYLE]);
        
        $this->add_control('arrow_color', [
            'label' => 'Icon Color',
            'type' => Controls_Manager::COLOR,
            'default' => '#1E293B',
            'selectors' => ['{{WRAPPER}} .cora-arrow-icon' => 'color: {{VALUE}}; border-color: #E2E8F0;'],
        ]);

        $this->add_control('arrow_bg_color', [
            'label' => 'Button BG Color',
            'type' => Controls_Manager::COLOR,
            'default' => 'transparent',
            'selectors' => ['{{WRAPPER}} .cora-arrow-btn' => 'background-color: {{VALUE}};'],
        ]);

        $this->end_controls_section();

        // --- STYLE: IMAGE ---
        $this->start_controls_section('section_style_image', ['label' => 'Image Area', 'tab' => Controls_Manager::TAB_STYLE]);

        $this->add_responsive_control('image_height', [
            'label' => 'Image Height',
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px', 'vh', '%'],
            'range' => [
                'px' => ['min' => 100, 'max' => 600],
            ],
            'default' => ['unit' => 'px', 'size' => 220],
            'selectors' => [
                '{{WRAPPER}} .cora-img' => 'height: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('image_radius', [
            'label' => 'Image Radius',
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'default' => ['top' => 16, 'right' => 16, 'bottom' => 16, 'left' => 16, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .cora-img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('image_top_margin', [
            'label' => 'Spacing Above Image',
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'default' => ['unit' => 'px', 'size' => 32],
            'selectors' => [
                '{{WRAPPER}} .cora-img-wrap' => 'margin-top: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $id = $this->get_id();
        
        $link_url = !empty($settings['link']['url']) ? $settings['link']['url'] : '#';
        $link_attrs = '';
        if(!empty($settings['link']['is_external'])) $link_attrs .= ' target="_blank"';
        if(!empty($settings['link']['nofollow'])) $link_attrs .= ' rel="nofollow"';

        ?>
        <style>
            /* ROOT CONTAINER */
            .cora-root-<?php echo $id; ?> {
                display: flex;
                flex-direction: column;
                box-sizing: border-box;
                overflow: hidden;
                width: 100%;
                position: relative;
                justify-content: space-between;
                transition: transform 0.3s ease, box-shadow 0.3s ease;
            }

            /* HEADER ROW (Title + Arrow) */
            .cora-root-<?php echo $id; ?> .cora-header-row {
                display: flex;
                justify-content: space-between;
                align-items: flex-start; /* Align top in case title wraps */
                gap: 16px;
                width: 100%;
                margin-bottom: 8px; /* Slight gap before desc */
            }

            /* CONTENT WRAPPER */
            .cora-root-<?php echo $id; ?> .cora-content-inner {
                display: flex;
                flex-direction: column;
                flex: 1; 
                /* Gap handled by control */
            }

            /* TITLE */
            .cora-root-<?php echo $id; ?> .cora-title {
                margin: 0;
                font-family: "Fredoka", sans-serif;
                font-size: clamp(22px, 3vw, 28px); /* Responsive Typography */
                font-weight: 700;
                line-height: 1.2;
                letter-spacing: -0.01em;
                width: 100%;
            }

            /* ARROW BUTTON */
            .cora-root-<?php echo $id; ?> .cora-arrow-btn {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 40px;
                height: 40px;
                border-radius: 50%;
                border: 1px solid #E2E8F0;
                flex-shrink: 0; /* Prevent squishing */
                text-decoration: none;
                transition: all 0.2s ease;
            }
            
            .cora-root-<?php echo $id; ?> .cora-arrow-btn svg {
                width: 16px;
                height: 16px;
                stroke-width: 2.5;
            }

            .cora-root-<?php echo $id; ?> .cora-arrow-btn:hover {
                border-color: #94A3B8;
                background-color: #F8FAFC;
            }

            /* DESCRIPTION */
            .cora-root-<?php echo $id; ?> .cora-desc {
                margin: 0;
                font-family: "Inter", sans-serif;
                font-size: 15px;
                line-height: 1.5;
                max-width: 90%;
            }

            /* IMAGE AREA */
            .cora-root-<?php echo $id; ?> .cora-img-wrap {
                width: 100%;
                display: flex;
                /* Margin-top handled by control to push to bottom if needed */
            }

            .cora-root-<?php echo $id; ?> .cora-img {
                width: 100%;
                display: block;
                object-fit: cover;
                object-position: center;
                background-color: #F1F5F9; /* Fallback placeholder color */
            }

            /* RESPONSIVE OVERRIDES */
            @media (max-width: 767px) {
                .cora-root-<?php echo $id; ?> {
                     min-height: auto !important; /* Allow auto height on mobile */
                }
                .cora-root-<?php echo $id; ?> .cora-title {
                    font-size: 22px; /* Fixed readable size for mobile */
                }
                .cora-root-<?php echo $id; ?> .cora-arrow-btn {
                    width: 36px;
                    height: 36px;
                }
            }
        </style>

        <div class="cora-why-root cora-root-<?php echo $id; ?>">

            <?php if ( ! empty( $settings['image']['url'] ) ) : ?>
                <div class="cora-img-wrap">
                    <img src="<?php echo esc_url($settings['image']['url']); ?>" class="cora-img" alt="<?php echo esc_attr($settings['title']); ?>">
                </div>
            <?php endif; ?>
            <div class="cora-header-row">
                <div class="cora-content-inner">
                    <h3 class="cora-title"><?php echo esc_html($settings['title']); ?></h3>
                    <p class="cora-desc"><?php echo esc_html($settings['desc']); ?></p>
                </div>

                <a href="<?php echo esc_url($link_url); ?>" class="cora-arrow-btn" <?php echo $link_attrs; ?>>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </a>
            </div>

        </div>
        <?php
    }
}