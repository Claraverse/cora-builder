<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Service_Banner extends Base_Widget {

    public function get_name() { return 'cora_service_banner'; }
    public function get_title() { return __( 'Cora Service Banner Hero', 'cora-builder' ); }

    protected function register_controls() {
        
        // --- TAB: CONTENT ---
        $this->start_controls_section('content', [ 'label' => 'Banner Identity' ]);
        
        $this->add_control('banner_logo', [
            'label' => 'Brand Logo',
            'type' => Controls_Manager::MEDIA,
            'dynamic' => ['active' => true],
            'default' => [ 'url' => Utils::get_placeholder_image_src() ], // Placeholder
        ]);

        $this->add_control('title', [
            'label' => 'Service Title',
            'type' => Controls_Manager::TEXT,
            'dynamic' => ['active' => true],
            'default' => 'Shopify Store Setup',
        ]);

        $this->add_control('subline', [
            'label' => 'Subline',
            'type' => Controls_Manager::TEXTAREA,
            'dynamic' => ['active' => true],
            'default' => 'Launch fast with a fully optimized Shopify store built to sell from day one.',
        ]);

        $this->add_control('mockup_img', [
            'label' => 'Right Side Mockup',
            'type' => Controls_Manager::MEDIA,
            'dynamic' => ['active' => true],
            'default' => [ 'url' => Utils::get_placeholder_image_src() ], // Placeholder
        ]);

        $this->add_control('btn_text', [ 'label' => 'Button Text', 'type' => Controls_Manager::TEXT, 'default' => 'Book Appointment' ]);
        
        $this->end_controls_section();

        // --- TAB: STYLE - SPATIAL & BG ---
        $this->start_controls_section('style_layout', [ 'label' => 'Layout & BG', 'tab' => Controls_Manager::TAB_STYLE ]);
        
        $this->add_group_control(Group_Control_Background::get_type(), [
            'name' => 'banner_bg',
            'selector' => '{{WRAPPER}} .cora-banner-hero',
        ]);

        $this->add_responsive_control('inner_padding', [
            'label' => 'Container Padding',
            'type' => Controls_Manager::DIMENSIONS,
            'selectors' => ['{{WRAPPER}} .cora-banner-hero' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);

        $this->end_controls_section();

        // --- TAB: STYLE - CTA GRADIENT ---
        $this->start_controls_section('style_cta', [ 'label' => 'CTA Button', 'tab' => Controls_Manager::TAB_STYLE ]);
        $this->add_group_control(Group_Control_Background::get_type(), [
            'name' => 'btn_bg',
            'selector' => '{{WRAPPER}} .banner-btn',
        ]);
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="cora-banner-hero">
            <div class="cora-unit-container banner-grid">
                <div class="banner-content">
                    <img src="<?php echo esc_url($settings['banner_logo']['url']); ?>" class="banner-logo">
                    <h1 class="banner-h1"><?php echo esc_html($settings['title']); ?></h1>
                    <p class="banner-p"><?php echo esc_html($settings['subline']); ?></p>
                    <a href="#" class="banner-btn"><?php echo esc_html($settings['btn_text']); ?></a>
                </div>

                <div class="banner-mockup">
                    <img src="<?php echo esc_url($settings['mockup_img']['url']); ?>" alt="Service Preview">
                </div>
            </div>
        </div>
        <?php
    }
}