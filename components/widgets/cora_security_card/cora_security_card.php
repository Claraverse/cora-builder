<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Security_Card extends Base_Widget {

    public function get_name() { return 'cora_security_card'; }
    public function get_title() { return __( 'Cora Security Card', 'cora-builder' ); }

    protected function register_controls() {
        
        // --- TAB: CONTENT ---
        $this->start_controls_section('content', [ 'label' => __( 'Security Details', 'cora-builder' ) ]);
        
        $this->add_control('status_label', [ 'label' => 'Status Badge', 'type' => Controls_Manager::TEXT, 'default' => 'Coming Soon' ]);
        $this->add_control('title', [ 'label' => 'Headline', 'type' => Controls_Manager::TEXT, 'default' => 'Auto Security & Backup', 'dynamic' => ['active' => true] ]);
        
        $this->add_control('desc', [
            'label' => 'Description',
            'type' => Controls_Manager::TEXTAREA,
            'default' => 'Real-time malware scanning, automatic daily backups with 30-day retention...',
            'dynamic' => ['active' => true]
        ]);

        $this->add_control('freq_val', [ 'label' => 'Frequency Value', 'type' => Controls_Manager::TEXT, 'default' => 'Daily' ]);
        $this->add_control('scan_val', [ 'label' => 'Scan Value', 'type' => Controls_Manager::TEXT, 'default' => '24/7' ]);
        $this->end_controls_section();

        // Style Engines
        $this->register_text_styling_controls('title_style', 'Headline Typography', '{{WRAPPER}} .sec-title');
        $this->register_common_spatial_controls(); 
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="cora-unit-container security-card-wrapper">
            <div class="sec-card-header">
                <div class="sec-icon-box">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div class="sec-status-badge">
                    <?php echo esc_html($settings['status_label']); ?>
                </div>
            </div>

            <div class="sec-card-body">
                <h3 class="sec-title"><?php echo esc_html($settings['title']); ?></h3>
                <p class="sec-desc"><?php echo esc_html($settings['desc']); ?></p>
            </div>

            <div class="sec-metric-grid">
                <div class="metric-unit">
                    <span class="m-val"><?php echo esc_html($settings['freq_val']); ?></span>
                    <span class="m-label">Backup Frequency</span>
                </div>
                <div class="metric-unit">
                    <span class="m-val"><?php echo esc_html($settings['scan_val']); ?></span>
                    <span class="m-label">Threat Scans</span>
                </div>
            </div>
        </div>
        <?php
    }
}