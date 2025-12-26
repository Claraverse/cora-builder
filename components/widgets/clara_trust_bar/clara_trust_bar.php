<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

class Clara_Trust_Bar extends Base_Widget {

    public function get_name() { return 'clara_trust_bar'; }
    public function get_title() { return __( 'Clara Authority Trust Bar', 'clara-builder' ); }

    protected function register_controls() {
        
        $this->start_controls_section('content', [ 'label' => 'Trust Metrics' ]);
        
        // Metric 01: Success Rate
        $this->add_control('m1_val', [ 'label' => 'Metric 1 Value', 'type' => Controls_Manager::TEXT, 'default' => '96%', 'dynamic' => ['active' => true] ]);
        $this->add_control('m1_label', [ 'label' => 'Metric 1 Label', 'type' => Controls_Manager::TEXT, 'default' => 'Success Rate' ]);
        $this->add_control('m1_desc', [ 'label' => 'Metric 1 Description', 'type' => Controls_Manager::TEXT, 'default' => 'Proven ability to grow and scale stores...' ]);

        // Metric 02: Project Count
        $this->add_control('m2_val', [ 'label' => 'Metric 2 Value', 'type' => Controls_Manager::TEXT, 'default' => '50+', 'dynamic' => ['active' => true] ]);
        $this->add_control('m2_label', [ 'label' => 'Metric 2 Label', 'type' => Controls_Manager::TEXT, 'default' => 'Shopify Projects' ]);
        $this->add_control('m2_desc', [ 'label' => 'Metric 2 Description', 'type' => Controls_Manager::TEXT, 'default' => 'Successfully delivered for top brands...' ]);

        // Badge: Shopify Partner
        $this->add_control('partner_label', [ 'label' => 'Badge Headline', 'type' => Controls_Manager::TEXT, 'default' => 'Official Shopify Partner' ]);
        $this->add_control('partner_desc', [ 'label' => 'Badge Subline', 'type' => Controls_Manager::TEXT, 'default' => 'Recognized globally for excellence...' ]);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="cora-unit-container clara-trust-bar">
            <div class="trust-matrix">
                <div class="t-unit">
                    <div class="t-primary-val"><?php echo esc_html($settings['m1_val']); ?></div>
                    <div class="t-copy-stack">
                        <strong><?php echo esc_html($settings['m1_label']); ?></strong>
                        <p><?php echo esc_html($settings['m1_desc']); ?></p>
                    </div>
                </div>

                <div class="t-unit">
                    <div class="t-primary-val"><?php echo esc_html($settings['m2_val']); ?></div>
                    <div class="t-copy-stack">
                        <strong><?php echo esc_html($settings['m2_label']); ?></strong>
                        <p><?php echo esc_html($settings['m2_desc']); ?></p>
                    </div>
                </div>

                <div class="t-unit partner-badge">
                    <div class="t-copy-stack">
                        <strong><?php echo esc_html($settings['partner_label']); ?></strong>
                        <p><?php echo esc_html($settings['partner_desc']); ?></p>
                    </div>
                    <div class="partner-visual">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/0/0e/Shopify_logo_2018.svg" alt="Shopify logo">
                        <span>partner</span>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}