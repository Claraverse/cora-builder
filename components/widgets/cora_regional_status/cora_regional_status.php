<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Regional_Status extends Base_Widget {

    public function get_name() { return 'cora_regional_status'; }
    public function get_title() { return __( 'Cora Regional Status', 'cora-builder' ); }

    protected function register_controls() {
        
        // --- TAB: CONTENT - REGION & LOCATIONS ---
        $this->start_controls_section('content', [ 'label' => __( 'Region Details', 'cora-builder' ) ]);
        $this->add_control('region_name', [ 'label' => 'Region Name', 'type' => Controls_Manager::TEXT, 'default' => 'India' ]);
        $this->add_control('dc_count', [ 'label' => 'DC Count Label', 'type' => Controls_Manager::TEXT, 'default' => '3 DCs' ]);
        
        $repeater = new Repeater();
        $repeater->add_control('city', [ 'label' => 'City Name', 'type' => Controls_Manager::TEXT, 'default' => 'Mumbai' ]);
        $this->add_control('locations', [
            'label' => 'Active Locations',
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [ ['city' => 'Mumbai'], ['city' => 'Bangalore'], ['city' => 'Delhi'] ]
        ]);
        $this->end_controls_section();

        // --- TAB: CONTENT - METRICS ---
        $this->start_controls_section('metrics', [ 'label' => __( 'Network Metrics', 'cora-builder' ) ]);
        $this->add_control('latency', [ 'label' => 'Latency Value', 'type' => Controls_Manager::TEXT, 'default' => '< 50ms', 'dynamic' => ['active' => true] ]);
        $this->add_control('uptime', [ 'label' => 'Uptime Value', 'type' => Controls_Manager::TEXT, 'default' => '99.99%', 'dynamic' => ['active' => true] ]);
        $this->add_control('requests', [ 'label' => 'Req/Day Value', 'type' => Controls_Manager::TEXT, 'default' => '12.4M', 'dynamic' => ['active' => true] ]);
        $this->end_controls_section();

        $this->register_common_spatial_controls(); 
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="cora-unit-container regional-status-card">
            <div class="reg-header">
                <div class="status-indicator">
                    <span class="dot"></span> OPERATIONAL
                </div>
                <div class="dc-badge"><?php echo esc_html($settings['dc_count']); ?></div>
            </div>

            <div class="reg-body">
                <h3 class="reg-title"><?php echo esc_html($settings['region_name']); ?></h3>
                <span class="loc-label">LOCATIONS</span>
                <div class="loc-tag-row">
                    <?php foreach($settings['locations'] as $loc) : ?>
                        <span class="loc-tag"><?php echo esc_html($loc['city']); ?></span>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="reg-metrics-row">
                <div class="m-unit">
                    <span class="m-val"><?php echo esc_html($settings['latency']); ?></span>
                    <span class="m-sub">LATENCY</span>
                </div>
                <div class="m-unit">
                    <span class="m-val"><?php echo esc_html($settings['uptime']); ?></span>
                    <span class="m-sub">UPTIME</span>
                </div>
                <div class="m-unit">
                    <span class="m-val"><?php echo esc_html($settings['requests']); ?></span>
                    <span class="m-sub">REQ/DAY</span>
                </div>
            </div>
        </div>
        <?php
    }
}