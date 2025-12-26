<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Others_Charge_V2 extends Base_Widget {

    public function get_name() { return 'cora_others_charge_v2'; }
    public function get_title() { return __( 'Cora Competitive V2 (Hover Reveal)', 'cora-builder' ); }

    protected function register_controls() {
        
        // --- TAB: CONTENT ---
        $this->start_controls_section('content', [ 'label' => 'Comparison Data' ]);
        
        $this->add_control('icon', [ 'label' => 'Icon Asset', 'type' => Controls_Manager::MEDIA, 'dynamic' => ['active' => true], 'default' => [ 'url' => Utils::get_placeholder_image_src() ] ]);
        $this->add_control('title', [ 'label' => 'Headline', 'type' => Controls_Manager::TEXT, 'default' => 'Others Charge...', 'dynamic' => ['active' => true] ]);
        $this->add_control('subline', [ 'label' => 'Subline', 'type' => Controls_Manager::TEXTAREA, 'default' => "You've probably paid too much for something too generic.", 'dynamic' => ['active' => true] ]);

        $this->add_control('reveal_text', [ 
            'label' => 'Initial "Reveal" Text', 
            'type' => Controls_Manager::TEXT, 
            'default' => 'Hover to reveal market price', 
            'dynamic' => ['active' => true],
            'separator' => 'before'
        ]);

        $this->add_control('price_tag', [ 'label' => 'Hidden Price', 'type' => Controls_Manager::TEXT, 'default' => '$999+', 'dynamic' => ['active' => true] ]);
        $this->add_control('price_desc', [ 'label' => 'Hidden Description', 'type' => Controls_Manager::TEXT, 'default' => 'Just for a fancy homepage', 'dynamic' => ['active' => true] ]);

        $this->add_control('main_img', [
            'label' => 'Frustrated Client Illustration',
            'type' => Controls_Manager::MEDIA,
            'dynamic' => ['active' => true],
            'default' => [ 'url' => Utils::get_placeholder_image_src() ], // Placeholder
        ]);

        $this->end_controls_section();

        // --- TAB: STYLE - SPATIAL ---
        $this->start_controls_section('style_spatial', [ 'label' => 'Layout & Spacing', 'tab' => Controls_Manager::TAB_STYLE ]);
        $this->add_responsive_control('card_padding', [ 'label' => 'Internal Padding', 'type' => Controls_Manager::DIMENSIONS, 'selectors' => ['{{WRAPPER}} .cora-comp-container-v2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'] ]);
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="cora-comp-container-v2">
            <div class="comp-header">
                <div class="comp-icon-box">
                    <img src="<?php echo esc_url($settings['icon']['url']); ?>" alt="Icon">
                </div>
                <div class="comp-header-text">
                    <h2 class="comp-h2"><?php echo esc_html($settings['title']); ?></h2>
                    <p class="comp-p"><?php echo esc_html($settings['subline']); ?></p>
                </div>
            </div>

            <div class="comp-canvas-v2">
                <div class="canvas-overlay"></div>
                <div class="comp-illustration">
                    <img src="<?php echo esc_url($settings['main_img']['url']); ?>" alt="Visual">
                </div>
                
                <div class="comp-reveal-trigger">
                    <span><i class="fas fa-eye"></i> <?php echo esc_html($settings['reveal_text']); ?></span>
                </div>

                <div class="comp-pricing-stack-hidden">
                    <h3 class="comp-price"><?php echo esc_html($settings['price_tag']); ?></h3>
                    <p class="comp-price-label"><?php echo esc_html($settings['price_desc']); ?></p>
                </div>
            </div>
        </div>
        <?php
    }
}