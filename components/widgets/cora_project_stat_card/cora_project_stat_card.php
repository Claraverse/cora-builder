<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Project_Stat_Card extends Base_Widget {

    public function get_name() { return 'cora_project_stat_card'; }
    public function get_title() { return __( 'Cora Project Stat Card', 'cora-builder' ); }

    protected function register_controls() {
        
        // --- TAB: CONTENT - PROJECT DATA ---
        $this->start_controls_section('content', [ 'label' => __( 'Project Stats', 'cora-builder' ) ]);
        $this->add_control('p_name', [ 'label' => 'Project Name', 'type' => Controls_Manager::TEXT, 'default' => 'Zenith App Design' ]);
        $this->add_control('p_progress', [ 'label' => 'Completion %', 'type' => Controls_Manager::NUMBER, 'default' => 78, 'dynamic' => ['active' => true] ]);
        $this->add_control('p_status', [ 'label' => 'Status Pill', 'type' => Controls_Manager::TEXT, 'default' => 'On Going' ]);
        $this->end_controls_section();

        // --- TAB: CONTENT - TIMELINE ---
        $this->start_controls_section('timeline', [ 'label' => __( 'Project Timeline', 'cora-builder' ) ]);
        $repeater = new Repeater();
        $repeater->add_control('day', [ 'label' => 'Day Name', 'type' => Controls_Manager::TEXT, 'default' => 'Mon' ]);
        $repeater->add_control('date', [ 'label' => 'Date', 'type' => Controls_Manager::TEXT, 'default' => '17' ]);
        $repeater->add_control('is_active', [ 'label' => 'Highlight Today', 'type' => Controls_Manager::SWITCHER ]);

        $this->add_control('days', [
            'label' => 'Timeline Tracker',
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [ ['day' => 'Mon', 'date' => '17'], ['day' => 'Tue', 'date' => '18', 'is_active' => 'yes'] ]
        ]);
        $this->end_controls_section();

        $this->register_common_spatial_controls(); 
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="cora-unit-container p-stat-card-wrapper">
            <div class="p-stat-header">
                <div class="p-id-stack">
                    <h4 class="p-name"><?php echo esc_html($settings['p_name']); ?></h4>
                    <span class="p-time"><i class="far fa-clock"></i> 10:00 am - 12:00 pm</span>
                </div>
                <div class="p-status-pill"><?php echo esc_html($settings['p_status']); ?></div>
            </div>

            <div class="p-activity-hub">
                <span class="hub-label">Overall activity</span>
                <div class="radial-gauge-container">
                    <svg viewBox="0 0 100 50">
                        <path class="gauge-bg" d="M10,45 A35,35 0 0,1 90,45" />
                        <path class="gauge-val" d="M10,45 A35,35 0 0,1 90,45" style="stroke-dasharray: <?php echo ($settings['p_progress'] * 1.25); ?>, 250;" />
                    </svg>
                    <div class="gauge-data">
                        <strong><?php echo esc_html($settings['p_progress']); ?>%</strong>
                        <span>Done</span>
                    </div>
                </div>
            </div>

            <div class="p-timeline-bar">
                <?php foreach ($settings['days'] as $day) : 
                    $active_class = ('yes' === $day['is_active']) ? 'active' : ''; ?>
                    <div class="day-unit <?php echo $active_class; ?>">
                        <span class="d-name"><?php echo esc_html($day['day']); ?></span>
                        <span class="d-num"><?php echo esc_html($day['date']); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
}