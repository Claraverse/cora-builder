<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Challenges_Grid extends Base_Widget {

    public function get_name() { return 'cora_challenges_grid'; }
    public function get_title() { return __( 'Cora Challenges Grid', 'cora-builder' ); }

    protected function register_controls() {
        
        $this->start_controls_section('content', [ 'label' => __( 'Project Challenges', 'cora-builder' ) ]);

        $repeater = new Repeater();
        $repeater->add_control('challenge_title', [ 
            'label' => 'Headline', 
            'type' => Controls_Manager::TEXT, 
            'default' => 'Reliance on Third-Party Platforms',
            'dynamic' => ['active' => true] 
        ]);
        $repeater->add_control('challenge_desc', [ 
            'label' => 'Description', 
            'type' => Controls_Manager::TEXTAREA, 
            'default' => 'Despite great food and loyal customers...',
            'dynamic' => ['active' => true]
        ]);

        $this->add_control('challenges', [
            'label' => 'Challenge Items',
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                ['challenge_title' => 'Reliance on Third-Party Platforms'],
            ],
            'title_field' => '{{{ challenge_title }}}',
        ]);

        $this->end_controls_section();
        $this->register_common_spatial_controls(); 
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $index = 1;
        ?>
        <div class="cora-unit-container challenges-grid">
            <?php foreach ($settings['challenges'] as $item) : ?>
                <div class="challenge-card">
                    <div class="challenge-index"><?php echo $index++; ?></div>
                    
                    <h3 class="challenge-h3"><?php echo esc_html($item['challenge_title']); ?></h3>
                    <p class="challenge-p"><?php echo esc_html($item['challenge_desc']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
    }
}