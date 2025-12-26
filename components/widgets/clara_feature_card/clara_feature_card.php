<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

class Clara_Feature_Card extends Base_Widget {

    public function get_name() { return 'clara_feature_card'; }
    public function get_title() { return __( 'Clara Performance Feature Card', 'clara-builder' ); }

    protected function register_controls() {
        
        // --- TAB: CONTENT - FEATURE DATA ---
        $this->start_controls_section('content', [ 'label' => 'Feature Info' ]);
        $this->add_control('headline', [ 
            'label' => 'Headline', 
            'type' => Controls_Manager::TEXT, 
            'default' => 'Faster Load Time',
            'dynamic' => ['active' => true] 
        ]);
        $this->add_control('subline', [ 
            'label' => 'Subline', 
            'type' => Controls_Manager::TEXTAREA, 
            'default' => 'Load under 2 seconds — reduce bounce rate and boost conversions instantly',
            'dynamic' => ['active' => true]
        ]);
        $this->end_controls_section();

        // --- TAB: CONTENT - MEDIA ---
        $this->start_controls_section('media', [ 'label' => 'Hardware Mockup' ]);
        $this->add_control('mockup_img', [ 'label' => 'Laptop Screenshot', 'type' => Controls_Manager::MEDIA ]);
        $this->end_controls_section();

        $this->register_common_spatial_controls(); 
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="cora-unit-container performance-card-wrapper">
            <div class="f-header-stack">
                <h3 class="f-h3"><?php echo esc_html($settings['headline']); ?></h3>
                <p class="f-p"><?php echo esc_html($settings['subline']); ?></p>
            </div>

            <div class="f-mockup-canvas">
                <div class="laptop-frame">
                    <img src="<?php echo esc_url($settings['mockup_img']['url']); ?>" alt="Performance Metric">
                </div>
            </div>
        </div>
        <?php
    }
}