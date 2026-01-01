<?php
namespace Cora_Builder\Components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

if (!defined('ABSPATH'))
    exit;

class Cora_Advance_Heading extends Base_Widget
{
    public function get_name() { return 'cora_advance_heading'; }
    public function get_title() { return 'Cora Advance Heading'; }
    public function get_icon() { return 'eicon-heading'; }

    // Load Fredoka Font Automatically
    public function get_style_depends() {
        wp_register_style('cora-google-fredoka', 'https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&display=swap', [], null);
        return ['cora-google-fredoka'];
    }

    protected function register_controls()
    {
        // --- CONTENT ---
        $this->start_controls_section('section_content', ['label' => 'Heading Content']);

        $this->add_control('title', [
            'label' => 'Title',
            'type' => Controls_Manager::TEXTAREA,
            'default' => 'The best talent, For all the needs.',
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('subtitle', [
            'label' => 'Subtitle',
            'type' => Controls_Manager::TEXTAREA,
            'default' => 'Find the best service and for your business.',
            'dynamic' => ['active' => true],
        ]);

        // Alignment Control (Optional override)
        $this->add_responsive_control('align', [
            'label' => 'Alignment',
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'left' => [ 'title' => 'Left', 'icon' => 'eicon-text-align-left' ],
                'center' => [ 'title' => 'Center', 'icon' => 'eicon-text-align-center' ],
                'right' => [ 'title' => 'Right', 'icon' => 'eicon-text-align-right' ],
            ],
            'default' => 'center',
            'selectors' => ['{{WRAPPER}} .cora-root-container' => 'text-align: {{VALUE}}; align-items: {{VALUE}};'],
        ]);

        $this->end_controls_section();

        // --- STYLE ---
        $this->start_controls_section('style_section', ['label' => 'Typography', 'tab' => Controls_Manager::TAB_STYLE]);

        // Title Styling
        $this->add_control('title_color', [
            'label' => 'Title Color',
            'type' => Controls_Manager::COLOR,
            'default' => '#0F172A',
            'selectors' => ['{{WRAPPER}} .cora-main-title' => 'color: {{VALUE}};'],
        ]);
        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'title_typo',
            'label' => 'Title Typography',
            'selector' => '{{WRAPPER}} .cora-main-title',
        ]);

        // Subtitle Styling
        $this->add_control('subtitle_color', [
            'label' => 'Subtitle Color',
            'type' => Controls_Manager::COLOR,
            'default' => '#64748B',
            'selectors' => ['{{WRAPPER}} .cora-sub-title' => 'color: {{VALUE}};'],
        ]);
        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'subtitle_typo',
            'label' => 'Subtitle Typography',
            'selector' => '{{WRAPPER}} .cora-sub-title',
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
                width: 100%;
                display: flex;
                flex-direction: column;
                gap: 16px; /* Spacing between title and subtitle */
            }

            /* Corrected CSS as per your request */
            .cora-root-<?php echo $id; ?> .cora-main-title {
                margin: 0;
                font-family: "Fredoka", sans-serif; /* Forced Font */
                font-size: 48px;
                font-weight: 500; /* Medium/SemiBold */
                line-height: 1.1;
                text-align: center;
                letter-spacing: -0.02em;
                color: #0F172A;
            }

            .cora-root-<?php echo $id; ?> .cora-sub-title {
                margin: 0;
                font-family: "Fredoka", sans-serif; /* Forced Font */
                font-size: 18px;
                font-weight: 400; /* Regular */
                text-align: center;
                color: #64748B;
                line-height: 1.6;
            }

            /* Responsive Scaling */
            @media (max-width: 768px) {
                .cora-root-<?php echo $id; ?> .cora-main-title {
                    font-size: 36px;
                }
                .cora-root-<?php echo $id; ?> .cora-sub-title {
                    font-size: 16px;
                }
            }
        </style>

        <div class="cora-unit-container cora-root-container cora-root-<?php echo $id; ?>">
            <h2 class="cora-main-title"><?php echo esc_html($settings['title']); ?></h2>
            <p class="cora-sub-title"><?php echo esc_html($settings['subtitle']); ?></p>
        </div>

        <?php
    }
}