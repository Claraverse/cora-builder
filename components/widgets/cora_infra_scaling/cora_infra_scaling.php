<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Infra_Scaling extends Base_Widget {

    public function get_name() { return 'cora_infra_scaling'; }
    public function get_title() { return __( 'Cora Infra Scaling', 'cora-builder' ); }

    protected function register_controls() {
        
        // --- TAB: CONTENT ---
        $this->start_controls_section('content', [ 'label' => __( 'Infrastructure Details', 'cora-builder' ) ]);
        
        $this->add_control('title', [
            'label'   => 'Title',
            'type'    => Controls_Manager::TEXT,
            'default' => 'Elastic Infrastructure Scaling',
            'dynamic' => ['active' => true]
        ]);

        $this->add_control('desc', [
            'label'   => 'Description',
            'type'    => Controls_Manager::TEXTAREA,
            'default' => 'Serverless architecture automatically provisions resources based on real-time demand.',
            'dynamic' => ['active' => true]
        ]);

        $repeater = new Repeater();
        $repeater->add_control('tag_label', [ 'label' => 'Tag Label', 'type' => Controls_Manager::TEXT, 'default' => 'Auto-Scaling' ]);
        $this->add_control('infra_tags', [
            'label'  => 'Architecture Tags',
            'type'   => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [ ['tag_label' => 'Auto-Scaling'], ['tag_label' => 'Load Balancing'], ['tag_label' => 'Zero Config'] ]
        ]);

        $this->add_control('metric_label', [ 'label' => 'Primary Metric', 'type' => Controls_Manager::TEXT, 'default' => 'Infinite', 'dynamic' => ['active' => true] ]);

        $this->end_controls_section();

        // Style & Layout Core
        $this->register_common_spatial_controls(); 
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="cora-unit-container cora-infra-scaling-card">
            <div class="infra-header">
                <div class="infra-icon-box">
                    <i class="fas fa-server"></i>
                </div>
                <div class="status-indicator">
                    <span class="dot"></span> Active
                </div>
            </div>

            <div class="infra-body">
                <h3 class="infra-title"><?php echo esc_html($settings['title']); ?></h3>
                <p class="infra-desc"><?php echo esc_html($settings['desc']); ?></p>
                
                <div class="infra-tags-row">
                    <?php foreach($settings['infra_tags'] as $tag) : ?>
                        <span class="infra-tag">• <?php echo esc_html($tag['tag_label']); ?></span>
                    <?php endforeach; ?>
                </div>

                <div class="scalability-badge">
                    <span class="val"><?php echo esc_html($settings['metric_label']); ?></span> scalability
                </div>
            </div>
        </div>
        <?php
    }
}