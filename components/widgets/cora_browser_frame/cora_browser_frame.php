<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Browser_Frame extends Base_Widget {

    public function get_name() { return 'cora_browser_frame'; }
    public function get_title() { return __( 'Cora Browser Frame', 'cora-builder' ); }

    protected function register_controls() {
        
        // --- TAB: CONTENT ---
        $this->start_controls_section('content', [ 'label' => __( 'Browser Settings', 'cora-builder' ) ]);
        
        $this->add_control('display_url', [
            'label'   => 'Display URL',
            'type'    => Controls_Manager::TEXT,
            'default' => 'yourdomain.com',
            'dynamic' => ['active' => true]
        ]);

        $this->add_control('inner_screenshot', [
            'label' => 'Dashboard Image',
            'type'  => Controls_Manager::MEDIA,
        ]);

        $this->end_controls_section();

        // Style & Layout Core
        $this->register_common_spatial_controls(); 
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="cora-unit-container browser-frame-wrapper">
            <div class="browser-header-bar">
                <div class="traffic-lights">
                    <span class="dot red"></span>
                    <span class="dot yellow"></span>
                    <span class="dot green"></span>
                </div>
                
                <div class="browser-address-bar">
                    <i class="fas fa-lock"></i>
                    <span class="url-text"><?php echo esc_html($settings['display_url']); ?></span>
                    <i class="fas fa-redo-alt refresh-icon"></i>
                </div>
                
                <div class="browser-actions">
                    <i class="far fa-share-square"></i>
                    <i class="fas fa-plus"></i>
                    <i class="far fa-copy"></i>
                </div>
            </div>

            <div class="browser-content-canvas">
                <img src="<?php echo esc_url($settings['inner_screenshot']['url']); ?>" class="canvas-img">
            </div>
        </div>
        <?php
    }
}