<?php
namespace Cora_Builder\components;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Stats_Trust_Row extends Widget_Base {

    public function get_name() { return 'cora_stats_trust_row'; }
    public function get_title() { return __( 'Cora Stats & Trust Row', 'cora-builder' ); }
    public function get_icon() { return 'eicon-counter'; }
    public function get_categories() { return [ 'cora-components' ]; }

    protected function register_controls() {

        // --- STAT 1 ---
        $this->start_controls_section('stat_1_section', ['label' => 'Statistic #1']);
        $this->add_control('stat_1_val', [ 'label' => 'Value', 'type' => Controls_Manager::TEXT, 'default' => '96%' ]);
        $this->add_control('stat_1_label', [ 'label' => 'Label', 'type' => Controls_Manager::TEXT, 'default' => 'Success Rate' ]);
        $this->add_control('stat_1_desc', [ 'label' => 'Description', 'type' => Controls_Manager::TEXTAREA, 'default' => 'Proven Shopify growth strategies that deliver measurable ROI.' ]);
        $this->end_controls_section();

        // --- STAT 2 ---
        $this->start_controls_section('stat_2_section', ['label' => 'Statistic #2']);
        $this->add_control('stat_2_val', [ 'label' => 'Value', 'type' => Controls_Manager::TEXT, 'default' => '50+' ]);
        $this->add_control('stat_2_label', [ 'label' => 'Label', 'type' => Controls_Manager::TEXT, 'default' => 'Shopify Projects' ]);
        $this->add_control('stat_2_desc', [ 'label' => 'Description', 'type' => Controls_Manager::TEXTAREA, 'default' => 'From startups to 7-figure DTC brands — deep industry insights.' ]);
        $this->end_controls_section();

        // --- PARTNER SECTION ---
        $this->start_controls_section('partner_section', ['label' => 'Partner Badge']);
        $this->add_control('partner_heading', [ 'label' => 'Heading', 'type' => Controls_Manager::TEXT, 'default' => 'Official Shopify Partner' ]);
        $this->add_control('partner_logo', [ 'label' => 'Partner Logo', 'type' => Controls_Manager::MEDIA, 'default' => [ 'url' => Utils::get_placeholder_image_src() ] ]);
        $this->add_control('partner_desc', [ 'label' => 'Description', 'type' => Controls_Manager::TEXTAREA, 'default' => 'Recognized by Shopify as a trusted partner. Our dedicated team uses cutting-edge strategies.' ]);
        $this->end_controls_section();

        // --- STYLE ---
        $this->start_controls_section('style_section', ['label' => 'Styling']);
        $this->add_control('accent_color', [ 'label' => 'Green Accent', 'type' => Controls_Manager::COLOR, 'default' => '#064e3b' ]);
        $this->add_control('text_color', [ 'label' => 'Body Text Color', 'type' => Controls_Manager::COLOR, 'default' => '#64748b' ]);
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $accent = $settings['accent_color'];
        ?>

        <style>
            .ctr-wrap {
                width: 100%;
                font-family: 'Fredoka', sans-serif;
                padding: clamp(40px, 6vw, 80px) 0;
                display: grid;
                /* Desktop: 3 uneven columns for perfect spacing */
                grid-template-columns: 1fr 1fr 1.5fr; 
                gap: clamp(40px, 5vw, 80px);
                border-top: 1px solid #f1f5f9;
            }

            /* --- Generic Column Layout --- */
            .ctr-col {
                display: flex;
                flex-direction: column;
                justify-content: flex-start;
            }

            /* --- Stat Typography --- */
            .ctr-stat-val {
                font-size: clamp(42px, 4vw, 56px); /* Fluid Scaling */
                font-weight: 500;
                color: <?php echo esc_attr($accent); ?>;
                line-height: 1;
                margin-bottom: 12px;
                letter-spacing: -0.02em;
            }

            .ctr-stat-label {
                font-size: clamp(18px, 2vw, 22px);
                font-weight: 500;
                color: #1e293b;
                margin-bottom: 12px;
                letter-spacing: -0.01em;
            }

            /* --- Partner Section Specifics --- */
            .ctr-partner-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                flex-wrap: wrap;
                gap: 15px;
                margin-bottom: 16px;
            }
            .ctr-partner-title {
                font-size: clamp(20px, 2vw, 24px);
                font-weight: 700;
                color: <?php echo esc_attr($accent); ?>;
                margin: 0;
                letter-spacing: -0.01em;
            }
            .ctr-partner-logo {
                height: 28px;
                width: auto;
                object-fit: contain;
                opacity: 0.9;
            }

            /* --- Shared Description --- */
            .ctr-desc {
                font-size: 16px;
                line-height: 1.6;
                color: <?php echo esc_attr($settings['text_color']); ?>;
                max-width: 95%;
            }

            /* --- RESPONSIVE BREAKPOINTS --- */
            
            /* Tablet (Portrait & Landscape small) -> 2 Columns */
            @media (max-width: 1024px) {
                .ctr-wrap {
                    grid-template-columns: 1fr 1fr; /* 2 Cols */
                    gap: 40px;
                }
                
                /* Make the Partner Badge span full width at bottom */
                .ctr-col-partner {
                    grid-column: 1 / -1; 
                    border-top: 1px solid #f1f5f9;
                    padding-top: 40px;
                }
            }

            /* Mobile -> 1 Column */
            @media (max-width: 600px) {
                .ctr-wrap {
                    grid-template-columns: 1fr;
                    gap: 40px;
                }
                .ctr-col-partner {
                    grid-column: auto; /* Reset span */
                    border-top: none;
                    padding-top: 0;
                }
                .ctr-col {
                    border-bottom: 1px solid #f1f5f9;
                    padding-bottom: 30px;
                }
                .ctr-col:last-child {
                    border-bottom: none;
                    padding-bottom: 0;
                }
            }
        </style>

        <div class="ctr-wrap">
            
            <div class="ctr-col">
                <div class="ctr-stat-val"><?php echo esc_html($settings['stat_1_val']); ?></div>
                <div class="ctr-stat-label"><?php echo esc_html($settings['stat_1_label']); ?></div>
                <div class="ctr-desc"><?php echo esc_html($settings['stat_1_desc']); ?></div>
            </div>

            <div class="ctr-col">
                <div class="ctr-stat-val"><?php echo esc_html($settings['stat_2_val']); ?></div>
                <div class="ctr-stat-label"><?php echo esc_html($settings['stat_2_label']); ?></div>
                <div class="ctr-desc"><?php echo esc_html($settings['stat_2_desc']); ?></div>
            </div>

            <div class="ctr-col ctr-col-partner">
                <div class="ctr-partner-header">
                    <h3 class="ctr-partner-title"><?php echo esc_html($settings['partner_heading']); ?></h3>
                    <?php if(!empty($settings['partner_logo']['url'])) : ?>
                        <img class="ctr-partner-logo" src="<?php echo esc_url($settings['partner_logo']['url']); ?>" alt="Partner">
                    <?php endif; ?>
                </div>
                <div class="ctr-desc"><?php echo esc_html($settings['partner_desc']); ?></div>
            </div>

        </div>
        <?php
    }
}