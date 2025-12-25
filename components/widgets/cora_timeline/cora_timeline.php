<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Timeline extends Base_Widget {

    public function get_name() { return 'cora_timeline'; }
    public function get_title() { return __( 'Cora Project Timeline', 'cora-builder' ); }

    protected function register_controls() {
        
        // --- TAB: CONTENT - TIMELINE STEPS ---
        $this->start_controls_section('content', [ 'label' => __( 'Execution Steps', 'cora-builder' ) ]);

        $repeater = new Repeater();
        $repeater->add_control('step_title', [ 
            'label' => 'Step Title', 
            'type' => Controls_Manager::TEXT, 
            'default' => 'Identified the Revenue Leaks',
            'dynamic' => ['active' => true] 
        ]);
        $repeater->add_control('step_desc', [ 
            'label' => 'Step Description', 
            'type' => Controls_Manager::WYSIWYG, 
            'default' => 'We started by reviewing how their current setup worked...',
            'dynamic' => ['active' => true]
        ]);

        $this->add_control('steps', [
            'label' => 'Timeline Items',
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                ['step_title' => 'Identified the Revenue Leaks'],
                ['step_title' => 'Built a Conversion-Focused Funnel'],
            ],
            'title_field' => '{{{ step_title }}}',
        ]);

        $this->end_controls_section();
        $this->register_common_spatial_controls(); 
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $count = 1;
        ?>
        <div class="cora-unit-container timeline-wrapper">
            <div class="timeline-line"></div>

            <div class="timeline-items">
                <?php foreach ($settings['steps'] as $item) : 
                    $side = ($count % 2 == 0) ? 'left' : 'right'; // Alternating logic
                ?>
                    <div class="timeline-item <?php echo $side; ?>">
                        <div class="timeline-node">
                            <?php echo str_pad($count++, 2, '0', STR_PAD_LEFT); ?>
                        </div>

                        <div class="timeline-content">
                            <h3 class="timeline-h3"><?php echo esc_html($item['step_title']); ?></h3>
                            <div class="timeline-p">
                                <?php echo $this->parse_text_editor($item['step_desc']); ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
}