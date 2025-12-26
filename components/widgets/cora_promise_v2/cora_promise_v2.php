<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Promise_V2 extends Base_Widget {

    public function get_name() { return 'cora_promise_v2'; }
    public function get_title() { return __( 'Cora Promise Card V2', 'cora-builder' ); }

    protected function register_controls() {
        
        // --- TAB: CONTENT ---
        $this->start_controls_section('intro', [ 'label' => 'Primary Copy' ]);
        $this->add_control('badge', [ 'label' => 'Badge', 'type' => Controls_Manager::TEXT, 'default' => 'Premium', 'dynamic' => ['active' => true] ]);
        $this->add_control('title', [ 'label' => 'Main Headline', 'type' => Controls_Manager::TEXT, 'default' => '30-Day Free Upgrade', 'dynamic' => ['active' => true] ]);
        $this->add_control('subline', [ 'label' => 'Main Subline', 'type' => Controls_Manager::TEXTAREA, 'default' => 'Ongoing support so you\'re never left hanging.', 'dynamic' => ['active' => true] ]);
        $this->end_controls_section();

        $this->start_controls_section('hero_canvas', [ 'label' => 'Inner Hero Canvas' ]);
        $this->add_control('hero_bg', [ 'label' => 'Hero Background', 'type' => Controls_Manager::MEDIA, 'default' => [ 'url' => Utils::get_placeholder_image_src() ] ]);
        $this->add_control('hero_title', [ 'label' => 'Hero Headline', 'type' => Controls_Manager::TEXT, 'default' => 'A Haven of Peace & Beauty' ]);
        $this->add_control('hero_p', [ 'label' => 'Hero Description', 'type' => Controls_Manager::TEXTAREA, 'default' => 'Reconnect with your inner calm.' ]);
        $this->end_controls_section();

        // --- TAB: STYLE ---
        $this->start_controls_section('style_spatial', [ 'label' => 'Spatial Controls', 'tab' => Controls_Manager::TAB_STYLE ]);
        $this->add_responsive_control('card_padding', [ 'label' => 'Outer Card Padding', 'type' => Controls_Manager::DIMENSIONS, 'selectors' => ['{{WRAPPER}} .cora-promise-v4' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'] ]);
        $this->add_responsive_control('canvas_height', [ 'label' => 'Hero Height', 'type' => Controls_Manager::SLIDER, 'range' => ['px' => ['min' => 300, 'max' => 800]], 'selectors' => ['{{WRAPPER}} .v4-hero-canvas' => 'height: {{SIZE}}{{UNIT}} !important;'] ]);
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="cora-promise-v4">
            <div class="v4-text-stack">
                <span class="v4-badge"><i class="fas fa-th-large"></i> <?php echo esc_html($settings['badge']); ?></span>
                <h2 class="v4-h2"><?php echo esc_html($settings['title']); ?></h2>
                <p class="v4-p"><?php echo esc_html($settings['subline']); ?></p>
            </div>

            <div class="v4-hero-canvas" style="background-image: url('<?php echo esc_url($settings['hero_bg']['url']); ?>');">
                <div class="v4-hero-overlay"></div>
                <div class="v4-hero-content">
                    <div class="v4-hero-leaf"><i class="fas fa-leaf"></i></div>
                    <h3 class="v4-hero-h3"><?php echo esc_html($settings['hero_title']); ?></h3>
                    <p class="v4-hero-p"><?php echo esc_html($settings['hero_p']); ?></p>
                    <div class="v4-hero-btns">
                        <a href="#" class="btn-solid">Book An Appointment</a>
                        <a href="#" class="btn-outline">Shop Beauty Products</a>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}