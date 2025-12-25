<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Status_Pill extends Base_Widget {

    public function get_name() { return 'cora_status_pill'; }
    public function get_title() { return __( 'Cora Status Pill', 'cora-builder' ); }

    protected function register_controls() {
        
        // --- TAB: CONTENT ---
        $this->start_controls_section('content', [ 'label' => __( 'Status Details', 'cora-builder' ) ]);
        
        $this->add_control('status_text', [
            'label'   => 'Status Label',
            'type'    => Controls_Manager::TEXT,
            'default' => 'All Systems Operational',
            'dynamic' => ['active' => true]
        ]);

        $this->add_control('store_count', [
            'label'   => 'Store Count',
            'type'    => Controls_Manager::TEXT,
            'default' => '2,847 stores',
            'dynamic' => ['active' => true]
        ]);

        $this->add_control('pill_link', [
            'label'   => 'Status Page Link',
            'type'    => Controls_Manager::URL,
            'dynamic' => ['active' => true]
        ]);

        $this->end_controls_section();

        // Style Engines
        $this->register_text_styling_controls('status_style', 'Text Typography', '{{WRAPPER}} .status-label, {{WRAPPER}} .count-label');
        $this->register_alignment_controls('status_align', '.cora-status-pill', '.status-pill-inner');
        $this->register_common_spatial_controls(); 
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $has_link = ! empty( $settings['pill_link']['url'] );
        ?>
        <div class="cora-unit-container cora-status-pill">
            <<?php echo $has_link ? 'a' : 'div'; ?> <?php echo $has_link ? $this->get_render_attribute_string( 'pill_link' ) : ''; ?> class="status-pill-inner">
                
                <div class="status-dot"></div>
                
                <span class="status-label"><?php echo esc_html($settings['status_text']); ?></span>
                
                <div class="status-divider"></div>
                
                <span class="count-label"><?php echo esc_html($settings['store_count']); ?></span>
                
            </<?php echo $has_link ? 'a' : 'div'; ?>>
        </div>
        <?php
    }
}