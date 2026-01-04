<?php
namespace Cora_Builder\components;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Process_Steps extends Widget_Base {

    public function get_name() { return 'cora_process_steps'; }
    public function get_title() { return __( 'Cora Process Wave', 'cora-builder' ); }
    public function get_icon() { return 'eicon-flow'; }
    public function get_categories() { return [ 'cora-components' ]; }

    public function get_style_depends() {
        wp_register_style( 'google-font-fredoka', 'https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500&display=swap', [], null );
        return [ 'google-font-fredoka' ];
    }

    protected function register_controls() {

        // --- STEPS ---
        $this->start_controls_section('content_section', ['label' => 'Process Steps']);

        $repeater = new Repeater();

        $repeater->add_control('title', [
            'label' => 'Step Title',
            'type' => Controls_Manager::TEXT,
            'default' => 'Step Title',
        ]);

        $repeater->add_control('icon', [
            'label' => 'Icon',
            'type' => Controls_Manager::ICONS,
            'default' => [
                'value' => 'fas fa-search',
                'library' => 'fa-solid',
            ],
        ]);

        $repeater->add_control('color', [
            'label' => 'Theme Color',
            'type' => Controls_Manager::COLOR,
            'default' => '#3b82f6',
            'selectors' => [
                '{{WRAPPER}} {{CURRENT_ITEM}} .cps-circle' => 'border-color: {{VALUE}}; color: {{VALUE}};',
                '{{WRAPPER}} {{CURRENT_ITEM}} .cps-bg-tint' => 'background-color: {{VALUE}};',
                '{{WRAPPER}} {{CURRENT_ITEM}} .cps-title' => 'color: {{VALUE}};',
                '{{WRAPPER}} {{CURRENT_ITEM}} .cps-small-dot' => 'border-color: {{VALUE}};'
            ],
        ]);

        $this->add_control('steps', [
            'label' => 'Steps',
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                [ 'title' => 'Audit & Strategy', 'icon' => ['value'=>'fas fa-search', 'library'=>'solid'], 'color' => '#8b5cf6' ],
                [ 'title' => 'Design & Build', 'icon' => ['value'=>'fas fa-pencil-ruler', 'library'=>'solid'], 'color' => '#0ea5e9' ],
                [ 'title' => 'Optimize & Automate', 'icon' => ['value'=>'fas fa-bolt', 'library'=>'solid'], 'color' => '#10b981' ],
                [ 'title' => 'Launch & Monitor', 'icon' => ['value'=>'fas fa-rocket', 'library'=>'solid'], 'color' => '#84cc16' ],
                [ 'title' => 'Scale & Support', 'icon' => ['value'=>'fas fa-chart-line', 'library'=>'solid'], 'color' => '#eab308' ],
            ],
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $steps = $settings['steps'];
        $count = count($steps);
        ?>

        <style>
            .cps-wrap {
                width: 100%;
                font-family: 'Fredoka', sans-serif;
                position: relative;
                padding: 40px 0;
            }

            /* --- DESKTOP WAVE LAYOUT --- */
            .cps-track {
                display: flex;
                justify-content: space-between;
                align-items: center;
                position: relative;
                max-width: 1200px;
                margin: 0 auto;
                gap: 20px;
            }

            /* The SVG Wave Background */
            .cps-wave-svg {
                position: absolute;
                top: 50%;
                left: 0;
                width: 100%;
                height: 120px;
                transform: translateY(-50%);
                z-index: 0;
                pointer-events: none;
                overflow: visible;
            }
            .cps-wave-path {
                fill: none;
                stroke: #94a3b8;
                stroke-width: 2;
                stroke-dasharray: 6 6;
                opacity: 0.6;
            }

            /* Step Item */
            .cps-step {
                position: relative;
                z-index: 2;
                display: flex;
                flex-direction: column;
                align-items: center;
                text-align: center;
                width: 180px;
                flex-shrink: 0;
            }

            /* Circle Design */
            .cps-circle {
                width: 140px;
                height: 140px;
                border-radius: 50%;
                border: 2px solid #ccc; /* Overridden by repeater */
                display: flex;
                align-items: center;
                justify-content: center;
                position: relative;
                background: #fff;
                box-shadow: 0 10px 20px rgba(0,0,0,0.03);
                transition: transform 0.3s ease;
            }
            .cps-circle:hover { transform: scale(1.05); }

            /* Inner Tint Background */
            .cps-bg-tint {
                position: absolute;
                inset: 4px;
                border-radius: 50%;
                opacity: 0.08; /* Very subtle tint */
                z-index: 0;
            }

            .cps-icon {
                font-size: 32px;
                position: relative;
                z-index: 1;
            }

            /* Title */
            .cps-title {
                margin-top: 15px;
                font-size: 16px;
                font-weight: 500;
                line-height: 1.3;
                max-width: 140px;
            }

            /* Small Connector Dots (Between Steps) */
            .cps-small-dot {
                width: 14px;
                height: 14px;
                border: 2px solid #ccc;
                border-radius: 50%;
                background: #fff;
                position: absolute;
                top: 50%;
                /* Logic to place it between items handled below */
                transform: translateY(-50%);
                z-index: 1;
            }

            /* --- RESPONSIVE MOBILE --- */
            @media (max-width: 1024px) {
                .cps-track {
                    flex-direction: column;
                    gap: 50px;
                }
                .cps-wave-svg { display: none; } /* Hide wave on mobile */
                
                /* Vertical Line for Mobile */
                .cps-track::before {
                    content: "";
                    position: absolute;
                    top: 0; bottom: 0; left: 50%;
                    width: 2px;
                    background: repeating-linear-gradient(to bottom, #cbd5e1 0, #cbd5e1 6px, transparent 6px, transparent 12px);
                    transform: translateX(-50%);
                    z-index: 0;
                }
                
                .cps-step { width: 100%; }
                .cps-circle { width: 100px; height: 100px; background: #fff; } /* Smaller circles */
                .cps-icon { font-size: 24px; }
            }
        </style>

        <div class="cps-wrap">
            <div class="cps-track">
                
                <svg class="cps-wave-svg" preserveAspectRatio="none" viewBox="0 0 1000 100">
                    <path class="cps-wave-path" d="M0,50 Q125,-30 250,50 T500,50 T750,50 T1000,50" />
                    </svg>

                <?php foreach ( $steps as $index => $step ) : ?>
                    <div class="cps-step elementor-repeater-item-<?php echo $step['_id']; ?>">
                        <div class="cps-circle">
                            <div class="cps-bg-tint"></div>
                            <div class="cps-icon">
                                <?php \Elementor\Icons_Manager::render_icon( $step['icon'], [ 'aria-hidden' => 'true' ] ); ?>
                            </div>
                        </div>
                        <div class="cps-title"><?php echo esc_html($step['title']); ?></div>
                        
                        <?php if ($index < $count - 1) : ?>
                            <?php endif; ?>
                    </div>
                <?php endforeach; ?>

            </div>
        </div>

        <script>
            // Optional: Simple script to redraw SVG path based on exact positions if needed
            // For now, the CSS-based flex distribution + wide SVG wave background creates the effect.
        </script>

        <?php
    }
}