<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Deliverable_Card extends Base_Widget {

    public function get_name() { return 'cora_deliverable_card'; }
    public function get_title() { return __( 'Cora Deliverable Card', 'cora-builder' ); }

    protected function register_controls() {
        
        $this->start_controls_section('content', [ 'label' => __( 'Deliverable Info', 'cora-builder' ) ]);
        
        $this->add_control('feature_icon', [ 'label' => 'Icon', 'type' => Controls_Manager::ICONS ]);
        $this->add_control('title', [ 
            'label' => 'Headline', 
            'type' => Controls_Manager::TEXT, 
            'default' => 'Booking Funnel (Wix)',
            'dynamic' => ['active' => true] 
        ]);
        $this->add_control('desc', [ 
            'label' => 'Subline', 
            'type' => Controls_Manager::TEXTAREA, 
            'default' => 'Mobile-optimized, direct booking site with sticky CTAs',
            'dynamic' => ['active' => true]
        ]);

        $this->end_controls_section();
        $this->register_common_spatial_controls(); 
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="cora-unit-container deliverable-card">
            <div class="deliverable-icon-box">
                <?php \Elementor\Icons_Manager::render_icon( $settings['feature_icon'], [ 'aria-hidden' => 'true' ] ); ?>
            </div>

            <div class="deliverable-copy">
                <h3 class="deliverable-h3"><?php echo esc_html($settings['title']); ?></h3>
                <p class="deliverable-p"><?php echo esc_html($settings['desc']); ?></p>
            </div>
        </div>
        <?php
    }
}