<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Promise_Banner extends Base_Widget {

    public function get_name() { return 'cora_promise_banner'; }
    public function get_title() { return __( 'Cora Promise Banner', 'cora-builder' ); }

    protected function register_controls() {
        
        // --- TAB: CONTENT ---
        $this->start_controls_section('content', [ 'label' => 'Banner Content' ]);
        
        $this->add_control('badge_text', [ 'label' => 'Top Badge', 'type' => Controls_Manager::TEXT, 'default' => 'Premium', 'dynamic' => ['active' => true] ]);
        $this->add_control('title', [ 'label' => 'Headline', 'type' => Controls_Manager::TEXT, 'default' => '30 Days of Free Pro Upgrade', 'dynamic' => ['active' => true] ]);
        $this->add_control('subline', [ 'label' => 'Subline', 'type' => Controls_Manager::TEXTAREA, 'default' => 'Get full access to premium features and support for 30 days, on the house.', 'dynamic' => ['active' => true] ]);

        $this->add_control('main_img', [
            'label' => 'Human Representative',
            'type' => Controls_Manager::MEDIA,
            'dynamic' => ['active' => true],
            'default' => [ 'url' => Utils::get_placeholder_image_src() ], // Placeholder
        ]);

        $this->add_control('tooltip_img', [
            'label' => 'Floating Order Tooltip',
            'type' => Controls_Manager::MEDIA,
            'dynamic' => ['active' => true],
        ]);

        $this->end_controls_section();

        // --- TAB: STYLE - SPATIAL & BG ---
        $this->start_controls_section('style_layout', [ 'label' => 'Layout & BG', 'tab' => Controls_Manager::TAB_STYLE ]);
        
        $this->add_group_control(Group_Control_Background::get_type(), [
            'name' => 'banner_bg',
            'selector' => '{{WRAPPER}} .cora-promise-banner',
            'fields_options' => [ 'background' => [ 'default' => 'classic' ], 'color' => [ 'default' => '#d1d5db' ] ], // Light Gray
        ]);

        $this->add_responsive_control('inner_padding', [
            'label' => 'Container Padding',
            'type' => Controls_Manager::DIMENSIONS,
            'selectors' => ['{{WRAPPER}} .cora-promise-banner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);

        $this->add_control('radius', [
            'label' => 'Corner Radius',
            'type' => Controls_Manager::SLIDER,
            'selectors' => ['{{WRAPPER}} .cora-promise-banner' => 'border-radius: {{SIZE}}{{UNIT}} !important;'],
            'default' => ['size' => 40],
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="cora-promise-banner">
            <div class="promise-grid">
                <div class="promise-content">
                    <span class="promise-badge"><i class="fas fa-th-large"></i> <?php echo esc_html($settings['badge_text']); ?></span>
                    <h2 class="promise-h2"><?php echo esc_html($settings['title']); ?></h2>
                    <p class="promise-p"><?php echo esc_html($settings['subline']); ?></p>
                </div>

                <div class="promise-visuals">
                    <div class="promise-main-asset">
                        <img src="<?php echo esc_url($settings['main_img']['url']); ?>" alt="Representative">
                    </div>
                    <?php if ( ! empty( $settings['tooltip_img']['url'] ) ) : ?>
                        <div class="promise-tooltip">
                            <img src="<?php echo esc_url($settings['tooltip_img']['url']); ?>" alt="Order Tooltip">
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
    }
}