<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Pricing_Card_V3 extends Base_Widget {

    public function get_name() { return 'cora_pricing_card_v3'; }
    public function get_title() { return __( 'Cora Pricing Card V3', 'cora-builder' ); }

    protected function register_controls() {
        $this->start_controls_section('content', [ 'label' => 'Content' ]);
        $this->add_control('is_recommended', [ 'label' => 'Recommended Styling', 'type' => Controls_Manager::SWITCHER ]);
        $this->add_control('plan_name', [ 'label' => 'Plan Name', 'type' => Controls_Manager::TEXT, 'default' => 'Startup' ]);
        $this->add_control('price', [ 'label' => 'Price', 'type' => Controls_Manager::TEXT, 'default' => '249.00' ]);
        $this->add_control('storage', [ 'label' => 'Storage', 'type' => Controls_Manager::TEXT, 'default' => '100 GB NVMe' ]);
        $this->add_control('bandwidth', [ 'label' => 'Bandwidth', 'type' => Controls_Manager::TEXT, 'default' => '1 TB/mo' ]);
        $this->add_control('visits', [ 'label' => 'Visits', 'type' => Controls_Manager::TEXT, 'default' => '1M visits/mo' ]);
        $this->end_controls_section();

        $this->register_common_spatial_controls(); 
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $card_class = ( 'yes' === $settings['is_recommended'] ) ? 'p-card-v3 recommended' : 'p-card-v3';
        ?>
        <div class="cora-unit-container <?php echo $card_class; ?>">
            <?php if('yes' === $settings['is_recommended']): ?>
                <div class="p-banner-v3">RECOMMENDED</div>
            <?php endif; ?>

            <div class="p-content-stack">
                <div class="p-info-top">
                    <h3 class="p-title"><?php echo esc_html($settings['plan_name']); ?></h3>
                    <p class="p-tagline">For E-commerce Store</p>
                    <div class="p-price-row">
                        <span class="p-curr">₹</span>
                        <span class="p-val"><?php echo esc_html($settings['price']); ?></span>
                        <span class="p-mo">/mo</span>
                    </div>
                </div>

                <a href="#" class="p-btn-v3">Choose plan</a>
                <p class="p-note">Cancel anytime. 100% Refund Policy*</p>

                <div class="p-specs-v3">
                    <div class="s-unit"><span>STORAGE</span><strong><?php echo esc_html($settings['storage']); ?></strong></div>
                    <div class="s-unit"><span>BANDWIDTH</span><strong><?php echo esc_html($settings['bandwidth']); ?></strong></div>
                    <div class="s-unit"><span>VISITS</span><strong><?php echo esc_html($settings['visits']); ?></strong></div>
                </div>
            </div>
        </div>
        <?php
    }
}