<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

class Clara_Video_Booster extends Base_Widget {

    public function get_name() { return 'clara_video_booster'; }
    public function get_title() { return __( 'Clara Video Booster', 'clara-builder' ); }

    protected function register_controls() {
        
        // --- TAB: CONTENT - VIDEO SOURCE ---
        $this->start_controls_section('video_section', [ 'label' => 'Video Content' ]);
        $this->add_control('video_url', [ 
            'label' => 'YouTube / Vimeo Link', 
            'type' => Controls_Manager::TEXT, 
            'default' => 'https://www.youtube.com/watch?v=example',
            'dynamic' => ['active' => true] 
        ]);
        $this->add_control('headline', [ 
            'label' => 'Video Title', 
            'type' => Controls_Manager::TEXT, 
            'default' => 'Shopify Sales Booster',
            'dynamic' => ['active' => true] 
        ]);
        $this->add_control('description', [ 
            'label' => 'Video Description', 
            'type' => Controls_Manager::TEXTAREA, 
            'default' => 'In this comprehensive overview, we will delve into the intricate details...',
            'dynamic' => ['active' => true]
        ]);
        $this->end_controls_section();

        $this->register_common_spatial_controls(); 
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $video_html = wp_oembed_get( $settings['video_url'] );
        ?>
        <div class="cora-unit-container video-booster-wrapper">
            <div class="video-grid-canvas">
                
                <div class="decorator dec-1"></div>
                <div class="decorator dec-2"></div>
                
                <div class="video-inner-stack">
                    <div class="video-text-header">
                        <div class="video-brand">Claraverse | <span>Booster</span></div>
                        <h2 class="video-h2"><?php echo $settings['headline']; ?></h2>
                        <p class="video-p"><?php echo esc_html($settings['description']); ?></p>
                    </div>

                    <div class="video-player-frame">
                        <?php echo $video_html; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}