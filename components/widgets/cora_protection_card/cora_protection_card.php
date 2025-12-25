<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Protection_Card extends Base_Widget {

    public function get_name() { return 'cora_protection_card'; }
    public function get_title() { return __( 'Cora Protection Card', 'cora-builder' ); }

    protected function register_controls() {
        
        // --- TAB: CONTENT ---
        $this->start_controls_section('content', [ 'label' => __( 'Security Protection', 'cora-builder' ) ]);
        
        $this->add_control('title', [ 'label' => 'Headline', 'type' => Controls_Manager::TEXT, 'default' => 'Fully Protected' ]);
        $this->add_control('desc', [
            'label' => 'Description',
            'type' => Controls_Manager::TEXTAREA,
            'default' => 'Automated patches, firewall, and daily backups included.',
        ]);

        $repeater = new Repeater();
        $repeater->add_control('label', [ 'label' => 'Service Name', 'type' => Controls_Manager::TEXT, 'default' => 'Malware Scanning' ]);
        $repeater->add_control('time', [ 'label' => 'Uptime/Frequency', 'type' => Controls_Manager::TEXT, 'default' => '24/7' ]);
        
        $this->add_control('services', [
            'label' => 'Security Services',
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                ['label' => 'Malware Scanning'],
                ['label' => 'Daily Backups'],
                ['label' => 'Firewall Rules'],
            ],
        ]);

        $this->add_control('metric_val', [ 'label' => 'Metric Value', 'type' => Controls_Manager::TEXT, 'default' => '99.9%' ]);
        $this->end_controls_section();

        $this->register_common_spatial_controls(); 
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="cora-unit-container protection-card-wrapper">
            <div class="prot-card-header">
                <div class="prot-icon-box"><i class="fas fa-shield-alt"></i></div>
                <div class="prot-live-pill"><span class="dot"></span> LIVE</div>
            </div>

            <h3 class="prot-title"><?php echo esc_html($settings['title']); ?></h3>
            <p class="prot-desc"><?php echo esc_html($settings['desc']); ?></p>

            <div class="prot-service-stack">
                <?php foreach($settings['services'] as $service) : ?>
                    <div class="prot-service-row">
                        <span><?php echo esc_html($service['label']); ?></span>
                        <span class="prot-time-pill"><?php echo esc_html($service['time']); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="prot-footer-metric">
                <span class="m-val"><?php echo esc_html($settings['metric_val']); ?></span>
                <span class="m-label">Threat Prevention</span>
            </div>
        </div>
        <?php
    }
}