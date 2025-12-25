<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Guarantee_Block extends Base_Widget {

    public function get_name() { return 'cora_guarantee_block'; }
    public function get_title() { return __( 'Cora Guarantee Block', 'cora-builder' ); }

    protected function register_controls() {
        
        // --- TAB: CONTENT - ANCHOR ---
        $this->start_controls_section('content', [ 'label' => __( 'Guarantee Anchor', 'cora-builder' ) ]);
        $this->add_control('days_val', [ 'label' => 'Days Count', 'type' => Controls_Manager::TEXT, 'default' => '30' ]);
        $this->add_control('anchor_sub', [ 'label' => 'Anchor Subtext', 'type' => Controls_Manager::TEXT, 'default' => 'Full Money-Back Guarantee' ]);
        $this->end_controls_section();

        // --- TAB: CONTENT - TRUST ITEMS ---
        $this->start_controls_section('section_trust', [ 'label' => __( 'Trust Factors', 'cora-builder' ) ]);
        $repeater = new Repeater();
        $repeater->add_control('item_title', [ 'label' => 'Factor Title', 'type' => Controls_Manager::TEXT, 'default' => 'Full Refund' ]);
        $repeater->add_control('item_desc', [ 'label' => 'Factor Description', 'type' => Controls_Manager::TEXTAREA, 'default' => '100% money back within 30 days...' ]);
        
        $this->add_control('trust_items', [
            'label' => 'Trust Grid',
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                ['item_title' => 'Full Refund'],
                ['item_title' => 'Keep Your Data'],
                ['item_title' => 'Instant Cancellation'],
                ['item_title' => 'Zero Risk'],
            ]
        ]);
        $this->end_controls_section();

        $this->register_common_spatial_controls(); 
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="cora-unit-container guarantee-split-card">
            <div class="guarantee-anchor">
                <div class="day-stack">
                    <span class="d-num"><?php echo esc_html($settings['days_val']); ?></span>
                    <span class="d-label">DAYS</span>
                </div>
                <div class="anchor-divider"></div>
                <p class="anchor-text"><?php echo esc_html($settings['anchor_sub']); ?></p>
            </div>

            <div class="trust-grid-column">
                <?php foreach($settings['trust_items'] as $item) : ?>
                    <div class="trust-unit">
                        <div class="trust-check-icon"><i class="far fa-check-circle"></i></div>
                        <div class="trust-content">
                            <h4 class="trust-title"><?php echo esc_html($item['item_title']); ?></h4>
                            <p class="trust-desc"><?php echo esc_html($item['item_desc']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
}