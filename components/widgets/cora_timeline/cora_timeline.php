<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Timeline extends Base_Widget {

    public function get_name() { return 'cora_timeline'; }
    public function get_title() { return __( 'Cora Project Timeline', 'cora-builder' ); }
    public function get_icon() { return 'eicon-time-line'; }

    protected function register_controls() {
        
        // --- TAB: CONTENT - TIMELINE STEPS ---
        $this->start_controls_section('content', [ 'label' => __( 'Execution Steps', 'cora-builder' ) ]);

        $repeater = new Repeater();
        
        $repeater->add_control('step_title', [ 
            'label' => 'Step Title', 
            'type' => Controls_Manager::TEXT, 
            'default' => 'Identified the Revenue Leaks',
            'label_block' => true,
        ]);
        
        $repeater->add_control('step_desc', [ 
            'label' => 'Step Description', 
            'type' => Controls_Manager::WYSIWYG, 
            'default' => 'We started by reviewing how their current setup worked, identifying friction points in the checkout process.',
        ]);

        $this->add_control('steps', [
            'label' => 'Timeline Items',
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                ['step_title' => 'Identified the Revenue Leaks'],
                ['step_title' => 'Built a Conversion-Focused Funnel'],
                ['step_title' => 'Launched & Scaled Traffic'],
            ],
            'title_field' => '{{{ step_title }}}',
        ]);

        $this->end_controls_section();
        
        // --- TAB: STYLE ---
        $this->start_controls_section('style_section', [ 'label' => 'Timeline Styling', 'tab' => Controls_Manager::TAB_STYLE ]);
        
        $this->add_control('accent_color', [
            'label' => 'Accent Color',
            'type' => Controls_Manager::COLOR,
            'default' => '#1e293b',
            'selectors' => [
                '{{WRAPPER}} .timeline-node' => 'background-color: {{VALUE}};',
                '{{WRAPPER}} .timeline-line' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $count = 1;
        ?>
        <style>
            /* --- Architecture --- */
            .timeline-wrapper {
                position: relative;
                padding: clamp(40px, 5vw, 80px) 0;
                max-width: 1000px;
                margin: 0 auto;
                font-family: 'Inter', sans-serif;
            }

            .timeline-items {
                display: flex;
                flex-direction: column;
                position: relative;
                /* Minimum height to prevent collapse */
                min-height: 200px;
            }

            /* --- The Central Line --- */
            .timeline-line {
                position: absolute;
                left: 50%;
                top: 20px;
                bottom: 20px;
                width: 2px;
                background: #1e293b;
                transform: translateX(-50%);
                z-index: 0;
            }

            /* --- Item Container --- */
            .timeline-item {
                position: relative;
                width: 50%;
                display: flex;
                margin-bottom: 60px;
                z-index: 1;
            }
            .timeline-item:last-child { margin-bottom: 0; }

            /* Desktop Zig-Zag Logic */
            .timeline-item.right {
                align-self: flex-end;
                padding-left: 60px;
                justify-content: flex-start;
                text-align: left;
            }

            .timeline-item.left {
                align-self: flex-start;
                padding-right: 60px;
                justify-content: flex-end;
                text-align: right;
            }

            /* --- The Node (Number Circle) --- */
            .timeline-node {
                position: absolute;
                top: 0;
                width: 44px;
                height: 44px;
                background: #1e293b;
                color: #fff;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 14px;
                font-weight: 800;
                box-shadow: 0 0 0 8px #fff; /* White halo to separate from content */
                z-index: 5;
            }

            /* Node Positioning */
            .timeline-item.right .timeline-node { left: -22px; } /* Half of 44px */
            .timeline-item.left .timeline-node { right: -22px; }

            /* --- Content Typography --- */
            .timeline-h3 {
                margin: 0 0 12px 0;
                font-size: clamp(20px, 3vw, 26px); /* Fluid Type */
                font-weight: 800;
                color: #0f172a;
                line-height: 1.2;
                letter-spacing: -0.02em;
            }

            .timeline-p {
                margin: 0;
                font-size: 16px;
                color: #475569;
                line-height: 1.6;
            }
            .timeline-p p { margin-bottom: 12px; }
            .timeline-p ul { margin: 8px 0; padding-left: 18px; text-align: inherit; }
            .timeline-item.left .timeline-p ul { direction: rtl; } /* Trick to align bullets right on left items? Better to just keep bullets standard */

            /* --- Mobile Optimizations (< 768px) --- */
            @media (max-width: 768px) {
                
                /* Move Line to Left */
                .timeline-line {
                    left: 22px; /* Center of the 44px node */
                    transform: none;
                }

                /* Reset Item to Full Width Stack */
                .timeline-item {
                    width: 100%;
                    align-self: flex-start !important; /* Override flex-end */
                    padding: 0 0 0 70px !important; /* Space for node on left */
                    text-align: left !important;
                    margin-bottom: 48px;
                }

                /* Reset Node Position to Left */
                .timeline-node {
                    left: 0 !important;
                    right: auto !important;
                }
                
                /* Adjust Text for Mobile */
                .timeline-h3 { font-size: 22px; }
            }
        </style>

        <div class="cora-unit-container timeline-wrapper">
            <div class="timeline-items">
                <div class="timeline-line"></div>

                <?php foreach ($settings['steps'] as $item) : 
                    // Logic: First item Right, Second Left? Or Alternating?
                    // Usually timelines start Top-Right or Top-Left. 
                    // Let's stick to the code: Count 1 = right, Count 2 = left.
                    $side = ($count % 2 != 0) ? 'right' : 'left'; 
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