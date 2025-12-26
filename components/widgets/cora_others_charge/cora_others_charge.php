<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Others_Charge extends Base_Widget {

    public function get_name() { return 'cora_others_charge'; }
    public function get_title() { return __( 'Cora Competitive Card', 'cora-builder' ); }

    protected function register_controls() {
        
        // --- TAB: CONTENT ---
        $this->start_controls_section('content', [ 'label' => 'Comparison Data' ]);
        
        $this->add_control('icon', [ 'label' => 'Top Left Icon', 'type' => Controls_Manager::MEDIA, 'dynamic' => ['active' => true] ]);
        $this->add_control('title', [ 'label' => 'Headline', 'type' => Controls_Manager::TEXT, 'default' => 'Others Charge...', 'dynamic' => ['active' => true] ]);
        $this->add_control('subline', [ 'label' => 'Subline', 'type' => Controls_Manager::TEXTAREA, 'default' => "You've probably paid too much for something too generic.", 'dynamic' => ['active' => true] ]);

        $this->add_control('price_tag', [ 'label' => 'Price Highlight', 'type' => Controls_Manager::TEXT, 'default' => '$999+', 'dynamic' => ['active' => true] ]);
        $this->add_control('price_desc', [ 'label' => 'Price Description', 'type' => Controls_Manager::TEXT, 'default' => 'Just for a fancy homepage', 'dynamic' => ['active' => true] ]);

        $this->add_control('main_img', [
            'label' => 'Frustrated Client Illustration',
            'type' => Controls_Manager::MEDIA,
            'dynamic' => ['active' => true],
            'default' => [ 'url' => Utils::get_placeholder_image_src() ], // Fallback
        ]);

        $this->end_controls_section();

        // --- TAB: STYLE - SPATIAL ---
        $this->start_controls_section('style_spatial', [ 'label' => 'Layout & Spacing', 'tab' => Controls_Manager::TAB_STYLE ]);
        $this->add_responsive_control('card_padding', [ 'label' => 'Internal Padding', 'type' => Controls_Manager::DIMENSIONS, 'selectors' => ['{{WRAPPER}} .cora-comp-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'] ]);
        $this->add_responsive_control('canvas_gap', [ 'label' => 'Gap Below Header', 'type' => Controls_Manager::SLIDER, 'selectors' => ['{{WRAPPER}} .comp-header' => 'margin-bottom: {{SIZE}}{{UNIT}} !important;'] ]);
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="cora-comp-container">
            <div class="comp-header">
                <div class="comp-icon-box">
                    <img src="<?php echo esc_url($settings['icon']['url']); ?>" alt="Icon">
                </div>
                <div class="comp-header-text">
                    <h2 class="comp-h2"><?php echo esc_html($settings['title']); ?></h2>
                    <p class="comp-p"><?php echo esc_html($settings['subline']); ?></p>
                </div>
            </div>

            <div class="comp-canvas">
                <div class="comp-illustration">
                    <img src="<?php echo esc_url($settings['main_img']['url']); ?>" alt="Comparison Visual">
                </div>
                <div class="comp-pricing-stack">
                    <h3 class="comp-price"><?php echo esc_html($settings['price_tag']); ?></h3>
                    <p class="comp-price-label"><?php echo esc_html($settings['price_desc']); ?></p>
                </div>
            </div>
        </div>
        <?php
    }
}