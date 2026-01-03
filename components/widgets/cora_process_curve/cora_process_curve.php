<?php
namespace Cora_Builder\components;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Process_Curve extends Widget_Base {

    public function get_name() { return 'cora_process_curve'; }
    public function get_title() { return __( 'Cora Process Curve', 'cora-builder' ); }
    public function get_icon() { return 'eicon-shape'; }

    protected function register_controls() {
        $this->start_controls_section('section_steps', ['label' => 'Journey Steps']);

        $repeater = new Repeater();
        $repeater->add_control('step_number', ['label' => 'Number', 'type' => Controls_Manager::TEXT, 'default' => '1']);
        $repeater->add_control('step_title', ['label' => 'Title', 'type' => Controls_Manager::TEXT, 'default' => 'Discovery & Booking']);
        $repeater->add_control('step_desc', ['label' => 'Description', 'type' => Controls_Manager::TEXTAREA, 'default' => 'Quick 15-min discovery call to understand your business.']);
        $repeater->add_control('step_color', ['label' => 'Color', 'type' => Controls_Manager::COLOR, 'default' => '#6366f1']);

        $this->add_control('steps', [
            'label' => 'Steps',
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                ['step_title' => 'Discovery & Booking', 'step_number' => '1', 'step_color' => '#6366f1'],
                ['step_title' => 'Strategy & Planning', 'step_number' => '2', 'step_color' => '#3b82f6'],
                ['step_title' => 'Design, Build & Review', 'step_number' => '3', 'step_color' => '#10b981'],
                ['step_title' => 'Launch & Scale', 'step_number' => '4', 'step_color' => '#84cc16'],
            ],
        ]);
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="cora-journey-smooth-wrap">
            <svg class="journey-svg" viewBox="0 0 1200 400" preserveAspectRatio="none">
                <path d="M0,200 Q150,25 300,200 T600,200 T900,200 T1200,200" 
                      fill="none" stroke="#e2e8f0" stroke-width="2" stroke-dasharray="12,12" />
            </svg>

            <div class="journey-items-container">
                <?php foreach ( $settings['steps'] as $index => $step ) : 
                    $is_even = ($index % 2 != 0);
                    $pos_class = $is_even ? 'valley' : 'peak';
                ?>
                    <div class="journey-step <?php echo $pos_class; ?>" style="--step-accent: <?php echo $step['step_color']; ?>;">
                        
                        <div class="journey-node">
                            <?php echo esc_html($step['step_number']); ?>
                        </div>

                        <div class="journey-card">
                            <h4 class="card-title"><?php echo esc_html($step['step_title']); ?></h4>
                            <p class="card-desc"><?php echo esc_html($step['step_desc']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <style>
            .cora-journey-smooth-wrap {
                position: relative;
                width: 100%;
                padding: 100px 0;
                overflow: visible;
                min-height: 600px;
            }

            .journey-svg {
                position: absolute;
                top: 50%;
                left: 0;
                width: 100%;
                height: 300px;
                transform: translateY(-50%);
                z-index: 1;
                pointer-events: none;
            }

            .journey-items-container {
                display: flex;
                justify-content: space-around;
                align-items: center;
                height: 100%;
                position: relative;
                z-index: 2;
                max-width: 1200px;
                margin: 0 auto;
            }

            .journey-step {
                position: relative;
                width: 250px;
                display: flex;
                flex-direction: column;
                align-items: center;
            }

            /* The Numbered Node - Anchored to the Center */
            .journey-node {
                width: 44px;
                height: 44px;
                background: #fff;
                border: 2px solid var(--step-accent);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: 800;
                color: var(--step-accent);
                font-size: 18px;
                box-shadow: 0 4px 10px rgba(0,0,0,0.05);
                z-index: 10;
                position: absolute;
                top: 50%;
                transform: translateY(-50%);
            }

            /* Card Styling */
            .journey-card {
                background: #fff;
                border: 2px solid var(--step-accent);
                border-radius: 20px;
                padding: 24px;
                text-align: center;
                width: 100%;
                box-shadow: 0 10px 30px rgba(0,0,0,0.03);
                transition: transform 0.3s ease;
            }

            .card-title {
                color: var(--step-accent);
                font-weight: 800;
                margin: 0 0 10px 0;
                font-size: 20px;
                line-height: 1.2;
            }

            .card-desc {
                font-size: 14px;
                color: #64748b;
                margin: 0;
                line-height: 1.6;
            }

            /* Peak (Maxima) Positioning */
            .journey-step.peak .journey-card {
                margin-bottom: 240px; /* Moves card above the curve */
            }

            /* Valley (Minima) Positioning */
            .journey-step.valley .journey-card {
                margin-top: 240px; /* Moves card below the curve */
            }

            /* RESPONSIVE */
            @media (max-width: 1024px) {
                .journey-step { width: 200px; }
                .journey-step.peak .journey-card { margin-bottom: 200px; }
                .journey-step.valley .journey-card { margin-top: 200px; }
            }

            @media (max-width: 767px) {
                .journey-svg { display: none; }
                .journey-items-container { flex-direction: column; gap: 30px; }
                .journey-step { width: 100%; margin: 0 !important; }
                .journey-node { position: static; transform: none; margin-bottom: 10px; }
                .journey-card { margin: 0 !important; }
            }
        </style>
        <?php
    }
}