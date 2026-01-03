<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Solution_Card_Two extends Base_Widget {

    public function get_name() { return 'cora_solution_card_two'; }
    public function get_title() { return __( 'Cora Solution Card Two', 'cora-builder' ); }

    protected function register_controls() {
        
        $this->start_controls_section('content', [ 'label' => __( 'Solution Detail', 'cora-builder' ) ]);
        
        $this->add_control('mockup_img', [ 'label' => 'Mobile Screen Image', 'type' => Controls_Manager::MEDIA ]);
        $this->add_control('headline', [ 
            'label' => 'Headline', 
            'type' => Controls_Manager::TEXT, 
            'default' => 'Direct Booking Funnel with Instant Table Reservations',
            'label_block' => true,
        ]);
        $this->add_control('desc', [ 
            'label' => 'Description', 
            'type' => Controls_Manager::TEXTAREA, 
            'default' => 'We built a high-converting web funnel with a sticky WhatsApp/reserve button...',
        ]);

        $this->end_controls_section();

        // --- Responsive Height Controls ---
        $this->start_controls_section('style_responsiveness', [ 'label' => 'Layout Adjustments', 'tab' => Controls_Manager::TAB_STYLE ]);

        $this->add_responsive_control('canvas_height', [
            'label' => 'Mockup Canvas Height',
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'vh' ],
            'range' => [ 'px' => [ 'min' => 200, 'max' => 600 ] ],
            'selectors' => [ '{{WRAPPER}} .solution-canvas' => 'height: {{SIZE}}{{UNIT}};' ],
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="solution-card">
            <div class="solution-canvas">
                <div class="iphone-frame">
                    <?php if ( ! empty( $settings['mockup_img']['url'] ) ) : ?>
                        <img src="<?php echo esc_url($settings['mockup_img']['url']); ?>" alt="Mockup">
                    <?php endif; ?>
                </div>
            </div>

            <div class="solution-copy">
                <h3 class="solution-h3"><?php echo esc_html($settings['headline']); ?></h3>
                <p class="solution-p"><?php echo esc_html($settings['desc']); ?></p>
            </div>
        </div>
        <?php
    }
}