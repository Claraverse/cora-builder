<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Service_Feature_Card extends Base_Widget {

    public function get_name() { return 'cora_service_feature_card'; }
    public function get_title() { return __( 'Cora Service Feature Vertical Card', 'cora-builder' ); }

    protected function register_controls() {
        
        // --- TAB: CONTENT ---
        $this->start_controls_section('content', [ 'label' => 'Feature Data' ]);
        
        $this->add_control('feature_img', [
            'label' => 'Hardware Mockup',
            'type' => Controls_Manager::MEDIA,
            'dynamic' => ['active' => true],
            // Placeholder fallback to prevent layout collapse
            'default' => [ 'url' => \Elementor\Utils::get_placeholder_image_src() ],
        ]);

        $this->add_control('feature_title', [
            'label' => 'Headline',
            'type' => Controls_Manager::TEXT,
            'dynamic' => ['active' => true],
            'default' => 'Mobile-First Storefront',
        ]);

        $this->add_control('feature_desc', [
            'label' => 'Description',
            'type' => Controls_Manager::TEXTAREA,
            'dynamic' => ['active' => true],
            'default' => 'Deliver lightning-fast, mobile-optimized shopping experiences that convert anywhere.',
        ]);

        $this->end_controls_section();

        // --- TAB: STYLE - CONTAINER ---
        $this->start_controls_section('style_container', [ 'label' => 'Card Appearance', 'tab' => Controls_Manager::TAB_STYLE ]);
        
        $this->add_responsive_control('card_padding', [
            'label' => 'Internal Padding',
            'type' => Controls_Manager::DIMENSIONS,
            'default' => [ 'top' => '48', 'right' => '48', 'bottom' => '48', 'left' => '48', 'unit' => 'px' ],
            'selectors' => ['{{WRAPPER}} .f-v-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);

        $this->add_control('card_radius', [
            'label' => 'Corner Radius',
            'type' => Controls_Manager::SLIDER,
            'default' => [ 'size' => 40 ],
            'selectors' => ['{{WRAPPER}} .cora-feature-v-card' => 'border-radius: {{SIZE}}{{UNIT}} !important;'],
        ]);

        $this->add_group_control(Group_Control_Box_Shadow::get_type(), [
            'name' => 'card_shadow',
            'selector' => '{{WRAPPER}} .cora-feature-v-card',
        ]);

        $this->end_controls_section();

        // --- TAB: STYLE - IMAGE CANVAS ---
        $this->start_controls_section('style_image', [ 'label' => 'Mockup Canvas', 'tab' => Controls_Manager::TAB_STYLE ]);
        
        $this->add_responsive_control('canvas_height', [
            'label' => 'Canvas Height',
            'type' => Controls_Manager::SLIDER,
            'default' => [ 'size' => 400 ],
            'selectors' => ['{{WRAPPER}} .f-v-canvas' => 'height: {{SIZE}}{{UNIT}} !important;'],
        ]);

        $this->add_control('canvas_bg', [
            'label' => 'Canvas Background',
            'type' => Controls_Manager::COLOR,
            'default' => '#f8fafc',
            'selectors' => ['{{WRAPPER}} .f-v-canvas' => 'background-color: {{VALUE}} !important;'],
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="cora-feature-v-card">
            <div class="f-v-canvas">
                <img src="<?php echo esc_url($settings['feature_img']['url']); ?>" alt="Mockup Asset">
            </div>
            <div class="f-v-content">
                <h3 class="f-v-h3"><?php echo esc_html($settings['feature_title']); ?></h3>
                <p class="f-v-p"><?php echo esc_html($settings['feature_desc']); ?></p>
            </div>
        </div>
        <?php
    }
}