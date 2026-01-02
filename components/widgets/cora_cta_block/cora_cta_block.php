<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_CTA_Block extends Base_Widget {

    public function get_name() { return 'cora_cta_block'; }
    public function get_title() { return __( 'Cora CTA Block', 'cora-builder' ); }
    public function get_icon() { return 'eicon-call-to-action'; }

    protected function register_controls() {
        
        // --- TAB: CONTENT ---
        $this->start_controls_section('section_content', [ 'label' => __( 'Content', 'cora-builder' ) ]);
        
        $this->add_control('title', [
            'label' => 'Headline',
            'type' => Controls_Manager::TEXTAREA,
            'default' => 'Need Personalized Guidance?',
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('description', [
            'label' => 'Description',
            'type' => Controls_Manager::TEXTAREA,
            'default' => 'Our experts can help you implement these strategies for your specific use case',
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('btn_text', [
            'label' => 'Button Text',
            'type' => Controls_Manager::TEXT,
            'default' => 'Schedule a Free Consultation',
        ]);

        $this->add_control('btn_link', [
            'label' => 'Button Link',
            'type' => Controls_Manager::URL,
            'dynamic' => ['active' => true],
            'placeholder' => 'https://your-link.com',
            'default' => [ 'url' => '#' ],
        ]);

        $this->end_controls_section();

        // --- TAB: STYLE ---
        
        // Title Style
        $this->start_controls_section('style_title', [ 'label' => 'Title', 'tab' => Controls_Manager::TAB_STYLE ]);
        $this->add_control('title_color', [
            'label' => 'Color',
            'type' => Controls_Manager::COLOR,
            'default' => '#0f172a',
            'selectors' => [ '{{WRAPPER}} .cta-title' => 'color: {{VALUE}};' ],
        ]);
        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'title_typography',
            'selector' => '{{WRAPPER}} .cta-title',
        ]);
        $this->end_controls_section();

        // Description Style
        $this->start_controls_section('style_desc', [ 'label' => 'Description', 'tab' => Controls_Manager::TAB_STYLE ]);
        $this->add_control('desc_color', [
            'label' => 'Color',
            'type' => Controls_Manager::COLOR,
            'default' => '#64748b',
            'selectors' => [ '{{WRAPPER}} .cta-desc' => 'color: {{VALUE}};' ],
        ]);
        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'desc_typography',
            'selector' => '{{WRAPPER}} .cta-desc',
        ]);
        $this->end_controls_section();

        // Button Style
        $this->start_controls_section('style_button', [ 'label' => 'Button', 'tab' => Controls_Manager::TAB_STYLE ]);
        $this->add_control('btn_bg', [
            'label' => 'Background Color',
            'type' => Controls_Manager::COLOR,
            'default' => '#0f172a',
            'selectors' => [ '{{WRAPPER}} .cta-button' => 'background-color: {{VALUE}};' ],
        ]);
        $this->add_control('btn_text_color', [
            'label' => 'Text Color',
            'type' => Controls_Manager::COLOR,
            'default' => '#ffffff',
            'selectors' => [ '{{WRAPPER}} .cta-button' => 'color: {{VALUE}};' ],
        ]);
        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'btn_typography',
            'selector' => '{{WRAPPER}} .cta-button',
        ]);
        $this->end_controls_section();

        // Layout Reset (Hidden Control for CSS Authority)
        $this->start_controls_section('layout_reset_sec', [ 'label' => 'Layout Settings', 'tab' => Controls_Manager::TAB_STYLE ]);
        $this->add_control('css_reset', [
            'type' => Controls_Manager::HIDDEN,
            'default' => 'reset',
            'selectors' => [
                '{{WRAPPER}} .cora-cta-container' => 'display: flex; flex-direction: column; align-items: center; text-align: center; width: 100%; padding: 60px 20px;',
                '{{WRAPPER}} .cta-title' => 'margin: 0 0 16px 0 !important; font-size: 42px; font-weight: 800; line-height: 1.2; letter-spacing: -1px;',
                '{{WRAPPER}} .cta-desc' => 'margin: 0 0 32px 0 !important; font-size: 18px; line-height: 1.6; max-width: 650px;',
                '{{WRAPPER}} .cta-button' => 'display: inline-flex; align-items: center; justify-content: center; padding: 18px 40px; border-radius: 100px; text-decoration: none; font-weight: 700; transition: all 0.3s ease;',
                '{{WRAPPER}} .cta-button:hover' => 'transform: translateY(-3px); box-shadow: 0 10px 25px rgba(0,0,0,0.1);',
            ],
        ]);
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        if ( ! empty( $settings['btn_link']['url'] ) ) {
            $this->add_link_attributes( 'button_attr', $settings['btn_link'] );
        }
        ?>
        <div class="cora-cta-container">
            <h2 class="cta-title"><?php echo esc_html($settings['title']); ?></h2>
            <p class="cta-desc"><?php echo esc_html($settings['description']); ?></p>
            
            <?php if ( ! empty( $settings['btn_text'] ) ) : ?>
                <a class="cta-button" <?php echo $this->get_render_attribute_string( 'button_attr' ); ?>>
                    <?php echo esc_html($settings['btn_text']); ?>
                </a>
            <?php endif; ?>
        </div>
        <?php
    }
}