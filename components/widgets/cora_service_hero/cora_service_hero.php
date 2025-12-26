<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Service_Hero extends Base_Widget {

    public function get_name() { return 'cora_service_hero'; }
    public function get_title() { return __( 'Cora Service Hero', 'cora-builder' ); }

    protected function register_controls() {
        
        // --- TAB: CONTENT ---
        $this->start_controls_section('content', [ 'label' => 'Hero Copy' ]);
        $this->add_control('badge', [ 'label' => 'Top Badge', 'type' => Controls_Manager::TEXT, 'default' => "Cora's Shopify", 'dynamic' => ['active' => true] ]);
        $this->add_control('headline', [ 
            'label' => 'Headline', 
            'type' => Controls_Manager::TEXTAREA, 
            'default' => 'Shopify Stores <span class="bold">Designed to Sell</span> <span class="italic">Scale and Grow.</span>',
            'dynamic' => ['active' => true] 
        ]);
        $this->add_control('subline', [ 
            'label' => 'Subline', 
            'type' => Controls_Manager::TEXTAREA, 
            'default' => 'Trusted by brands across industries to grow sales with CRO-first design & automation',
            'dynamic' => ['active' => true]
        ]);
        $this->end_controls_section();

        $this->start_controls_section('media', [ 'label' => 'Hardware & Logos' ]);
        $this->add_control('left_device', [ 'label' => 'Left Tablet', 'type' => Controls_Manager::MEDIA, 'dynamic' => ['active' => true] ]);
        $this->add_control('right_device', [ 'label' => 'Right Laptop', 'type' => Controls_Manager::MEDIA, 'dynamic' => ['active' => true] ]);
        
        $repeater = new Repeater();
        $repeater->add_control('tech_icon', [ 'label' => 'Icon', 'type' => Controls_Manager::MEDIA, 'dynamic' => ['active' => true] ]);
        $this->add_control('tech_stack', [ 'label' => 'Tech Stack', 'type' => Controls_Manager::REPEATER, 'fields' => $repeater->get_controls() ]);
        $this->end_controls_section();

        // --- TAB: STYLE ---
        $this->start_controls_section('style_spatial', [ 'label' => 'Layout & Spacing', 'tab' => Controls_Manager::TAB_STYLE ]);
        $this->add_responsive_control('hero_padding', [ 'label' => 'Hero Padding', 'type' => Controls_Manager::DIMENSIONS, 'selectors' => ['{{WRAPPER}} .cora-service-hero' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'] ]);
        $this->add_responsive_control('title_gap', [ 'label' => 'Title Bottom Margin', 'type' => Controls_Manager::SLIDER, 'selectors' => ['{{WRAPPER}} .hero-h1' => 'margin-bottom: {{SIZE}}{{UNIT}} !important;'] ]);
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="cora-service-hero">
            <div class="device-asset dev-left"><img src="<?php echo esc_url($settings['left_device']['url']); ?>"></div>
            <div class="device-asset dev-right"><img src="<?php echo esc_url($settings['right_device']['url']); ?>"></div>

            <div class="cora-unit-container hero-center-stack">
                <div class="hero-badge-wrap">
                    <span class="hero-badge"><i class="fas fa-leaf"></i> <?php echo esc_html($settings['badge']); ?></span>
                </div>
                
                <h1 class="hero-h1"><?php echo $settings['headline']; ?></h1>
                <p class="hero-p"><?php echo esc_html($settings['subline']); ?></p>

                <div class="hero-actions">
                    <button class="btn-dark">Book a Call <i class="fas fa-arrow-right"></i></button>
                    <a href="#" class="btn-link">Case Studies <i class="fas fa-external-link-alt"></i></a>
                </div>

                <div class="hero-tech-pills">
                    <?php foreach ($settings['tech_stack'] as $item) : ?>
                        <div class="tech-pill"><img src="<?php echo esc_url($item['tech_icon']['url']); ?>"></div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php
    }
}