<?php
namespace Cora_Builder\Components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Utils;

if (!defined('ABSPATH'))
    exit;

class Cora_WhyUs_Block extends Base_Widget
{
    public function get_name() { return 'cora_whyus_block'; }
    public function get_title() { return 'Cora Why Us Block'; }
    public function get_icon() { return 'eicon-info-box'; }

    // Load Fredoka Font Automatically
    public function get_style_depends() {
        wp_register_style('cora-google-fredoka', 'https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&display=swap', [], null);
        return ['cora-google-fredoka'];
    }

    protected function register_controls()
    {
        // --- CONTENT ---
        $this->start_controls_section('section_content', ['label' => 'Content']);

        $this->add_control('title', [
            'label' => 'Title',
            'type' => Controls_Manager::TEXTAREA,
            'default' => 'SEO-Optimized',
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('desc', [
            'label' => 'Description',
            'type' => Controls_Manager::TEXTAREA,
            'default' => 'Our SEO-centric design approach enhances your online visibility and drives organic traffic effectively.',
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('image', [
            'label' => 'Image',
            'type' => Controls_Manager::MEDIA,
            'default' => [ 'url' => Utils::get_placeholder_image_src() ],
            'dynamic' => ['active' => true],
        ]);

        $this->end_controls_section();

        // --- STYLE ---
        $this->start_controls_section('section_style', ['label' => 'Design', 'tab' => Controls_Manager::TAB_STYLE]);

        $this->add_control('bg_color', [
            'label' => 'Background Color',
            'type' => Controls_Manager::COLOR,
            'default' => '#FFFFFF',
            'selectors' => ['{{WRAPPER}} .cora-why-root' => 'background-color: {{VALUE}};'],
        ]);

        // Title Styling
        $this->add_control('title_color', [
            'label' => 'Title Color',
            'type' => Controls_Manager::COLOR,
            'default' => '#2E2B88',
            'selectors' => ['{{WRAPPER}} .cora-title' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'title_typo',
            'label' => 'Title Typography',
            'selector' => '{{WRAPPER}} .cora-title',
        ]);

        // Desc Styling
        $this->add_control('desc_color', [
            'label' => 'Description Color',
            'type' => Controls_Manager::COLOR,
            'default' => '#475569',
            'selectors' => ['{{WRAPPER}} .cora-desc' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'desc_typo',
            'label' => 'Desc Typography',
            'selector' => '{{WRAPPER}} .cora-desc',
        ]);

        // --- NEW: Image Height Control ---
        $this->add_control('img_height_heading', [
            'label' => 'Image Sizing',
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_responsive_control('image_height', [
            'label' => 'Image Height',
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px', '%', 'vh'],
            'range' => [
                'px' => ['min' => 100, 'max' => 800],
                'vh' => ['min' => 10, 'max' => 100],
            ],
            'default' => ['unit' => 'px', 'size' => 300],
            'selectors' => [
                '{{WRAPPER}} .cora-img' => 'height: {{SIZE}}{{UNIT}}; object-fit: cover;',
            ],
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
                flex-direction: column;
                background-color: #FFFFFF;
                border-radius: 32px;
                padding: 20px 20px 20px 20px; /* Flush bottom */
                box-sizing: border-box;
                border: 1px solid #E0E7FF;
                overflow: hidden;
                width: 100%;
                gap: 24px;
                min-height: 400px;
                box-shadow: 0 4px 20px rgba(0,0,0,0.02);
            }

            .cora-root-<?php echo $id; ?> .cora-content {
                display: flex;
                flex-direction: column;
                gap: 16px;
                padding: 12px; /* Inner padding for text */
            }

            .cora-root-<?php echo $id; ?> .cora-title {
                margin: 0;
                font-family: "Fredoka", sans-serif;
                font-size: 38px;
                font-weight: 700;
                color: #2E2B88;
                line-height: 1.1;
                letter-spacing: -0.02em;
            }

            .cora-root-<?php echo $id; ?> .cora-desc {
                margin: 0;
                font-family: "Inter", sans-serif;
                font-size: 16px;
                line-height: 1.6;
                color: #475569;
                max-width: 95%;
            }

            /* Image Wrapper: Anchored to Bottom */
            .cora-root-<?php echo $id; ?> .cora-img-wrap {
                margin-top: auto;
                width: 100%;
                display: flex;
            }

            .cora-root-<?php echo $id; ?> .cora-img {
                width: 100%;
                /* Height controlled via Elementor settings */
                display: block;
                object-fit: cover;
                border-radius: 12px;
   
            }
        </style>

        <div class="cora-unit-container cora-why-root cora-root-<?php echo $id; ?>">
            <div class="cora-content">
                <h3 class="cora-title"><?php echo esc_html($settings['title']); ?></h3>
                <p class="cora-desc"><?php echo esc_html($settings['desc']); ?></p>
            </div>

            <?php if ( ! empty( $settings['image']['url'] ) ) : ?>
                <div class="cora-img-wrap">
                    <img src="<?php echo esc_url($settings['image']['url']); ?>" class="cora-img" alt="<?php echo esc_attr($settings['title']); ?>">
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
}