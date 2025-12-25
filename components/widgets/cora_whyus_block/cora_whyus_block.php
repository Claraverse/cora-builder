<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_WhyUs_Block extends Base_Widget {

    public function get_name() { return 'cora_whyus_block'; }
    public function get_title() { return __( 'Cora WhyUs Block', 'cora-builder' ); }

    protected function register_controls() {
        
        // --- TAB: CONTENT ---
        $this->start_controls_section('content', [ 'label' => __( 'Feature Info', 'cora-builder' ) ]);
        
        $this->add_control('title', [
            'label' => 'Headline',
            'type' => Controls_Manager::TEXT,
            'default' => 'SEO-Optimized',
            'dynamic' => ['active' => true]
        ]);

        $this->add_control('desc', [
            'label' => 'Description',
            'type' => Controls_Manager::TEXTAREA,
            'default' => 'Our SEO-centric design approach enhances your online visibility...',
            'dynamic' => ['active' => true]
        ]);

        $this->add_control('dashboard_img', [
            'label' => 'Analytics Preview',
            'type' => Controls_Manager::MEDIA
        ]);

        $this->end_controls_section();

        // Style & Layout Core
        $this->register_text_styling_controls('title_style', 'Headline Typography', '{{WRAPPER}} .why-title');
        $this->register_alignment_controls('why_align', '.cora-why-us-container', '.why-content, .why-media');
        $this->register_common_spatial_controls(); 
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="cora-unit-container cora-why-us-container">
            <div class="why-content">
                <h3 class="why-title"><?php echo esc_html($settings['title']); ?></h3>
                <p class="why-desc"><?php echo esc_html($settings['desc']); ?></p>
            </div>
            
            <div class="why-media">
                <img src="<?php echo esc_url($settings['dashboard_img']['url']); ?>" class="dashboard-preview">
            </div>
        </div>
        <?php
    }
}