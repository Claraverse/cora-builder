<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Mission_Block extends Base_Widget {

    public function get_name() { return 'cora_mission_block'; }
    public function get_title() { return __( 'Cora Mission Block', 'cora-builder' ); }

    protected function register_controls() {
        
        // --- TAB: CONTENT - MISSION TEXT ---
        $this->start_controls_section('content', [ 'label' => __( 'Mission Statement', 'cora-builder' ) ]);
        $this->add_control('badge', [ 'label' => 'Badge Label', 'type' => Controls_Manager::TEXT, 'default' => 'Our mission' ]);
        $this->add_control('title', [ 
            'label' => 'Headline', 
            'type' => Controls_Manager::TEXTAREA, 
            'default' => 'We Design. We Develop. You Dominate with Claraverse.',
            'dynamic' => ['active' => true] 
        ]);
        $this->add_control('description', [
            'label' => 'Description',
            'type' => Controls_Manager::TEXTAREA,
            'default' => 'Remember that idea you had? the product you always wanted to create...',
            'dynamic' => ['active' => true]
        ]);
        $this->end_controls_section();

        // --- TAB: CONTENT - STATISTICS ---
        $this->start_controls_section('section_stats', [ 'label' => __( 'Impact Metrics', 'cora-builder' ) ]);
        $repeater = new Repeater();
        $repeater->add_control('stat_number', [ 'label' => 'Number', 'type' => Controls_Manager::TEXT, 'default' => '100+', 'dynamic' => ['active' => true] ]);
        $repeater->add_control('stat_label', [ 'label' => 'Label', 'type' => Controls_Manager::TEXT, 'default' => 'Projects completed' ]);
        
        $this->add_control('stats', [
            'label' => 'Statistics Grid',
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                ['stat_number' => '3+', 'stat_label' => 'Years of experience.'],
                ['stat_number' => '$4M*', 'stat_label' => 'Revenue for our clients.'],
                ['stat_number' => '3', 'stat_label' => 'Active Products'],
                ['stat_number' => '100+', 'stat_label' => 'Projects completed'],
            ]
        ]);
        $this->end_controls_section();

        // Style Engines
        $this->register_text_styling_controls('title_style', 'Headline Typography', '{{WRAPPER}} .mission-title');
        $this->register_common_spatial_controls(); 
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="cora-unit-container mission-split-row">
            <div class="vision-column">
                <span class="mission-badge"><?php echo esc_html($settings['badge']); ?></span>
                <h2 class="mission-title"><?php echo esc_html($settings['title']); ?></h2>
                <p class="mission-desc"><?php echo esc_html($settings['description']); ?></p>
            </div>

            <div class="stats-column-grid">
                <?php foreach($settings['stats'] as $stat) : ?>
                    <div class="mission-stat-item">
                        <span class="stat-num"><?php echo esc_html($stat['stat_number']); ?></span>
                        <span class="stat-text"><?php echo esc_html($stat['stat_label']); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
}