<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Challenges_Grid extends Base_Widget {

    public function get_name() { return 'cora_challenges_grid'; }
    public function get_title() { return __( 'Cora Challenges Grid', 'cora-builder' ); }
    public function get_icon() { return 'eicon-gallery-grid'; }

    protected function register_controls() {
        
        $this->start_controls_section('content', [ 'label' => __( 'Project Challenges', 'cora-builder' ) ]);

        $repeater = new Repeater();
        
        $repeater->add_control('challenge_title', [ 
            'label' => 'Headline', 
            'type' => Controls_Manager::TEXT, 
            'default' => 'Reliance on Third-Party Platforms',
            'label_block' => true,
        ]);
        
        $repeater->add_control('challenge_desc', [ 
            'label' => 'Description', 
            'type' => Controls_Manager::TEXTAREA, 
            'default' => 'Despite great food and loyal customers, the business struggled with high commission fees...',
        ]);

        $this->add_control('challenges', [
            'label' => 'Challenge Items',
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                ['challenge_title' => 'Reliance on Third-Party Platforms', 'challenge_desc' => 'High fees were eating into margins.'],
                ['challenge_title' => 'Disconnected Customer Data', 'challenge_desc' => 'No way to retarget loyal diners directly.'],
                ['challenge_title' => 'Outdated Mobile Experience', 'challenge_desc' => 'The legacy site was not optimized for mobile bookings.'],
            ],
            'title_field' => '{{{ challenge_title }}}',
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $index = 1;
        ?>
        <style>
            /* --- Grid Layout --- */
            .ccg-wrapper {
                width: 100%;
                font-family: 'Inter', sans-serif;
            }

            .ccg-grid {
                display: grid;
                /* Smart Layout: Fits as many columns as possible, min width 280px */
                grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
                gap: clamp(20px, 3vw, 32px); /* Fluid Gap */
            }

            /* --- Card Styling --- */
            .ccg-card {
                background: #ffffff;
                border: 1px solid #f1f5f9;
                border-radius: 24px;
                padding: clamp(24px, 4vw, 40px); /* Fluid Padding */
                display: flex;
                flex-direction: column;
                align-items: flex-start;
                transition: transform 0.3s ease, box-shadow 0.3s ease;
                height: 100%; /* Equal Height Cards */
            }

            .ccg-card:hover {
                 
                box-shadow: 0 20px 40px -10px rgba(0,0,0,0.06);
                border-color: #e2e8f0;
            }

            /* --- Index Number --- */
            .ccg-index {
                width: 48px;
                height: 48px;
                background: #f8fafc;
                border: 1px solid #e2e8f0;
                border-radius: 14px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 18px;
                font-weight: 800;
                color: #0f172a;
                margin-bottom: 24px;
            }

            /* --- Typography --- */
            .ccg-h3 { 
                margin: 0 0 16px 0 !important; 
                font-size: clamp(22px, 2.5vw, 32px); /* Fluid Type */
                font-weight: 800; 
                color: #1e293b; 
                line-height: 1.2;
                letter-spacing: -0.02em;
            }

            .ccg-p { 
                margin: 0 !important; 
                font-size: clamp(15px, 1.5vw, 16px); 
                color: #64748b; 
                line-height: 1.6; 
            }

            /* --- Mobile Optimizations --- */
            @media (max-width: 600px) {
                .ccg-grid {
                    grid-template-columns: 1fr; /* Force single column on small phones */
                }
                .ccg-card {
                    padding: 24px;
                }
                .ccg-index {
                    margin-bottom: 20px;
                    width: 40px; height: 40px; font-size: 16px; /* Smaller badge */
                }
            }
        </style>

        <div class="cora-unit-container ccg-wrapper">
            <div class="ccg-grid">
                <?php foreach ($settings['challenges'] as $item) : ?>
                    <div class="ccg-card">
                        <div class="ccg-index"><?php echo sprintf("%02d", $index++); ?></div>
                        
                        <h3 class="ccg-h3"><?php echo esc_html($item['challenge_title']); ?></h3>
                        <p class="ccg-p"><?php echo esc_html($item['challenge_desc']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
}