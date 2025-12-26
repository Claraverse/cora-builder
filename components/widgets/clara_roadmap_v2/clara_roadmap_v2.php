<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) exit;

class Clara_Roadmap_V2 extends Base_Widget {

    public function get_name() { return 'clara_roadmap_v2'; }
    public function get_title() { return __( 'Clara Growth Roadmap V2', 'clara-builder' ); }

    protected function register_controls() {
        
        $this->start_controls_section('content', [ 'label' => 'Strategy Flow' ]);

        $repeater = new Repeater();
        $repeater->add_control('label', [ 'label' => 'Stage Name', 'type' => Controls_Manager::TEXT, 'default' => 'Audit & Strategy' ]);
        $repeater->add_control('icon', [ 'label' => 'Icon', 'type' => Controls_Manager::ICONS ]);
        $repeater->add_control('is_featured', [ 'label' => 'Dark Frame', 'type' => Controls_Manager::SWITCHER ]);
        $repeater->add_control('color', [ 'label' => 'Stage Color', 'type' => Controls_Manager::COLOR, 'default' => '#3b82f6' ]);

        $this->add_control('steps', [
            'label' => 'Roadmap Steps',
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                ['label' => 'Audit & Strategy', 'is_featured' => 'yes', 'color' => '#6366f1'],
                ['label' => 'Design & Build', 'color' => '#3498db'],
                ['label' => 'Optimize & Automate', 'color' => '#27ae60'],
            ],
        ]);

        $this->end_controls_section();
        $this->register_common_spatial_controls(); 
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="cora-unit-container roadmap-v2-wrapper">
            <div class="roadmap-bg-glow"></div>

            <div class="roadmap-v2-track">
                <?php foreach ($settings['steps'] as $index => $step) : 
                    $is_featured = ('yes' === $step['is_featured']) ? 'featured-node' : '';
                ?>
                    <div class="roadmap-v2-node <?php echo $is_featured; ?>" style="--step-color: <?php echo esc_attr($step['color']); ?>;">
                        <div class="node-visual-hub">
                            <?php \Elementor\Icons_Manager::render_icon( $step['icon'], [ 'aria-hidden' => 'true' ] ); ?>
                        </div>
                        <h4 class="node-v2-label"><?php echo esc_html($step['label']); ?></h4>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
}