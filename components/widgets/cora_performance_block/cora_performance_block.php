<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Performance_Block extends Base_Widget {

    public function get_name() { return 'cora_performance_block'; }
    public function get_title() { return __( 'Cora Performance Block', 'cora-builder' ); }

    protected function register_controls() {
        
        // --- TAB: CONTENT ---
        $this->start_controls_section('content', [ 'label' => 'Feature Identity' ]);
        
        $this->add_control('title', [ 
            'label' => 'Headline', 
            'type' => Controls_Manager::TEXT, 
            'default' => 'Mobile-Optimized & Blazing Fast', 
            'dynamic' => ['active' => true] 
        ]);
        
        $this->add_control('subline', [ 
            'label' => 'Subline', 
            'type' => Controls_Manager::TEXTAREA, 
            'default' => 'Seamlessly plan within SAP for enhanced collaboration and productivity.', 
            'dynamic' => ['active' => true] 
        ]);

        $this->add_control('main_img', [
            'label' => 'Main Background Image',
            'type' => Controls_Manager::MEDIA,
            'dynamic' => ['active' => true],
            'default' => [ 'url' => Utils::get_placeholder_image_src() ], // Fallback
        ]);

        $this->add_control('floating_tooltip', [
            'label' => 'Floating UI Tooltip',
            'type' => Controls_Manager::MEDIA,
            'dynamic' => ['active' => true],
        ]);

        $this->end_controls_section();

        // --- TAB: STYLE - SPATIAL & VISUAL ---
        $this->start_controls_section('style_layout', [ 'label' => 'Layout & Spacing', 'tab' => Controls_Manager::TAB_STYLE ]);
        
        $this->add_responsive_control('inner_padding', [
            'label' => 'Internal Padding',
            'type' => Controls_Manager::DIMENSIONS,
            'selectors' => ['{{WRAPPER}} .cora-perf-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);

        $this->add_control('radius', [
            'label' => 'Corner Radius',
            'type' => Controls_Manager::SLIDER,
            'selectors' => ['{{WRAPPER}} .cora-perf-container' => 'border-radius: {{SIZE}}{{UNIT}} !important;'],
            'default' => ['size' => 40],
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="cora-perf-container">
            <div class="perf-header">
                <div class="header-content">
                    <h2 class="perf-h2"><?php echo esc_html($settings['title']); ?></h2>
                    <p class="perf-p"><?php echo esc_html($settings['subline']); ?></p>
                </div>
                <div class="header-action">
                    <div class="action-circle"><i class="fas fa-arrow-right"></i></div>
                </div>
            </div>

            <div class="perf-canvas">
                <div class="canvas-main-img">
                    <img src="<?php echo esc_url($settings['main_img']['url']); ?>" alt="Feature Visual">
                </div>
                
                <?php if ( ! empty( $settings['floating_tooltip']['url'] ) ) : ?>
                    <div class="canvas-tooltip">
                        <img src="<?php echo esc_url($settings['floating_tooltip']['url']); ?>" alt="UI Tooltip">
                    </div>
                <?php endif; ?>

                <div class="canvas-uptime-badge">
                    <span>99%</span>
                    <strong>Uptime</strong>
                </div>
            </div>
        </div>
        <?php
    }
}