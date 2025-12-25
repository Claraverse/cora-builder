<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Hosting_Hero  extends Base_Widget {

    public function get_name() { return 'cora_hosting_hero '; }
    public function get_title() { return __( 'Cora Hosting Hero  ', 'cora-builder' ); }

    protected function register_controls() {
        
        // --- TAB: CONTENT - HEADLINE ---
        $this->start_controls_section('content', [ 'label' => __( 'Hero Headline', 'cora-builder' ) ]);
        $this->add_control('top_badge', [ 'label' => 'Top Badge', 'type' => Controls_Manager::TEXT, 'default' => 'Managed WooCommerce Platform' ]);
        $this->add_control('title_line_1_prefix', [ 'label' => 'Line 1 Prefix', 'type' => Controls_Manager::TEXT, 'default' => 'The' ]);
        $this->add_control('title_line_1_green', [ 'label' => 'Line 1 Highlight', 'type' => Controls_Manager::TEXT, 'default' => 'Shopify-Style' ]);
        $this->add_control('title_line_1_suffix', [ 'label' => 'Line 1 Suffix', 'type' => Controls_Manager::TEXT, 'default' => 'Simplicity.' ]);
        $this->add_control('title_line_2_prefix', [ 'label' => 'Line 2 Prefix', 'type' => Controls_Manager::TEXT, 'default' => 'WooCommerce-Level' ]);
        $this->add_control('title_line_2_brush', [ 'label' => 'Brush Word', 'type' => Controls_Manager::TEXT, 'default' => 'Freedom' ]);
        $this->end_controls_section();

        // --- TAB: CONTENT - PRICING ---
        $this->start_controls_section('pricing', [ 'label' => __( 'Pricing Details', 'cora-builder' ) ]);
        $this->add_control('price', [ 'label' => 'Monthly Price', 'type' => Controls_Manager::TEXT, 'default' => '99.00', 'dynamic' => ['active' => true] ]);
        $this->add_control('guarantee', [ 'label' => 'Guarantee Text', 'type' => Controls_Manager::TEXT, 'default' => '30-day money-back guarantee' ]);
        $this->end_controls_section();

        // --- TAB: STYLE - DYNAMIC HIGHLIGHT ---
        $this->start_controls_section('style_brush', [ 'label' => 'Brush Styling', 'tab' => Controls_Manager::TAB_STYLE ]);
        $this->add_control('brush_color', [ 'label' => 'Brush Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .brush-freedom' => 'background-color: {{VALUE}};' ]]);
        $this->add_responsive_control('brush_tilt', [ 'label' => 'Rotation', 'type' => Controls_Manager::SLIDER, 'selectors' => [ '{{WRAPPER}} .brush-freedom' => 'transform: rotate({{SIZE}}deg);' ]]);
        $this->add_responsive_control('brush_padding', [ 'label' => 'Inner Padding', 'type' => Controls_Manager::DIMENSIONS, 'selectors' => [ '{{WRAPPER}} .brush-freedom' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ]]);
        $this->end_controls_section();

        $this->register_common_spatial_controls(); 
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="cora-unit-container hosting-hero-v2-stack">
            <div class="hero-top-badge"><?php echo esc_html($settings['top_badge']); ?></div>

            <h1 class="hero-headline">
                <?php echo esc_html($settings['title_line_1_prefix']); ?> 
                <span class="green-text"><?php echo esc_html($settings['title_line_1_green']); ?></span> 
                <?php echo esc_html($settings['title_line_1_suffix']); ?><br>
                <?php echo esc_html($settings['title_line_2_prefix']); ?> 
                <span class="brush-freedom"><?php echo esc_html($settings['title_line_2_brush']); ?></span>
            </h1>

            <div class="price-display-v2">
                <div class="main-price">
                    <span class="currency">₹</span><?php echo esc_html($settings['price']); ?><span class="asterisk">*</span><span class="per-mo">/mo</span>
                </div>
                <div class="guarantee-row">
                    <i class="fas fa-check-circle"></i> <?php echo esc_html($settings['guarantee']); ?>
                </div>
            </div>

            <div class="cta-cluster">
                <a href="#" class="btn-primary">Start Free Trial <i class="fas fa-arrow-right"></i></a>
                <a href="#" class="btn-secondary">Watch Demo <i class="fas fa-play"></i></a>
            </div>
        </div>
        <?php
    }
}