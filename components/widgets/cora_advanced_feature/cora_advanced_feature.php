<?php
namespace Cora_Builder\components;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Advanced_Feature extends Widget_Base {

    public function get_name() { return 'cora_advanced_feature'; }
    public function get_title() { return __( 'Cora Advanced Showcase', 'cora-builder' ); }
    public function get_icon() { return 'eicon-inner-section'; }
    public function get_categories() { return [ 'cora-components' ]; }

    protected function register_controls() {

        // --- SECTION: HEADER ---
        $this->start_controls_section('header_section', ['label' => 'Main Header']);
        $this->add_control('main_title', [
            'label' => 'Main Headline',
            'type' => Controls_Manager::TEXT,
            'default' => 'Web Design & Prototype',
            'dynamic' => ['active' => true],
        ]);
        $this->add_control('main_desc', [
            'label' => 'Main Subline',
            'type' => Controls_Manager::TEXTAREA,
            'default' => 'Custom stores built for conversion, speed, and growth.',
            'dynamic' => ['active' => true],
        ]);
        $this->end_controls_section();

        // --- SECTION: SHOWCASE ---
        $this->start_controls_section('showcase_section', ['label' => 'Visual Showcase']);
        $this->add_control('showcase_img', [
            'label' => 'Showcase Image (Laptop)',
            'type' => Controls_Manager::MEDIA,
            'default' => ['url' => Utils::get_placeholder_image_src()],
            'dynamic' => ['active' => true],
        ]);
        $this->add_control('side_title', [
            'label' => 'Side Headline',
            'type' => Controls_Manager::TEXT,
            'default' => 'Designs that look great — and work even better',
            'dynamic' => ['active' => true],
        ]);
        $this->add_control('side_desc', [
            'label' => 'Side Description',
            'type' => Controls_Manager::TEXTAREA,
            'default' => 'We craft web experiences with brand clarity, UX strategy, and development-ready prototypes.',
            'dynamic' => ['active' => true],
        ]);
        $this->end_controls_section();

        // --- SECTION: FEATURE GRID ---
        $this->start_controls_section('grid_section', ['label' => 'Feature Cards']);
        $repeater = new Repeater();
        $repeater->add_control('feature_title', [
            'label' => 'Title', 
            'type' => Controls_Manager::TEXT, 
            'default' => 'Strategy-Led Wireframes',
            'dynamic' => ['active' => true]
        ]);
        $repeater->add_control('feature_text', [
            'label' => 'Description', 
            'type' => Controls_Manager::TEXTAREA, 
            'default' => 'Every layout starts with a goal — whether it’s conversions or user flow.',
            'dynamic' => ['active' => true]
        ]);
        
        $this->add_control('features', [
            'label' => 'Features',
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                ['feature_title' => 'Strategy-Led Wireframes', 'feature_text' => 'Every layout starts with a goal — whether it’s conversions or user flow.'],
                ['feature_title' => 'Development-Ready', 'feature_text' => 'Figma files are organized, responsive, and ready for pixel-perfect handoff.'],
                ['feature_title' => 'Mobile-First UI', 'feature_text' => 'We prioritize the mobile experience to ensure high conversion on all devices.'],
                ['feature_title' => 'Pixel Perfect Design', 'feature_text' => 'Clean, consistent designs that maintain brand integrity across all touchpoints.'],
            ]
        ]);
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <style>
            .cora-adv-wrap { 
                width: 100%; 
                font-family: 'Inter', sans-serif; 
                padding: clamp(40px, 8vw, 20px) 0;
                 
            }
            
            /* Top Header */
            .cora-adv-header { 
                text-align: center; 
                margin-bottom: clamp(60px, 10vw, 40px); 
                padding: 0 20px;
            }
            .cora-adv-header h2 { 
                font-size: clamp(32px, 5vw, 56px); 
                font-weight: 900; 
                color: #0f172a; 
                margin-bottom: 20px; 
                letter-spacing: -0.04em;
                line-height: 1.1;
            }
            .cora-adv-header p { 
                font-size: clamp(16px, 2vw, 20px); 
                color: #64748b; 
                max-width: 720px; 
                margin: 0 auto; 
                line-height: 1.6;
                font-weight: 500;
            }

            /* Main Split Layout */
            .cora-adv-body { 
                display: flex; 
                align-items: flex-start; 
                gap: clamp(40px, 6vw, 20px); 
                max-width: 1400px;
                margin: 0 auto;
                padding: 0 30px;
            }
            .cora-adv-left { flex: 1.3; }
            .cora-adv-right { flex: 1; }

            .cora-adv-showcase-img img { 
                width: 100%; 
                height: auto; 
                border-radius: 24px; 
                box-shadow: 0 20px 50px rgba(0,0,0,0.08);
            }

            /* Right Side Typography */
            .cora-adv-side-text { margin-bottom: 0; }
            .cora-adv-side-text h3 { 
                font-size: clamp(26px, 4vw, 36px); 
                font-weight: 850; 
                color: #0f172a; 
                line-height: 1.15; 
                margin-bottom: 0; 
                letter-spacing: -0.03em;
            }
            .cora-adv-side-text p { 
                font-size: 17px; 
                color: #475569; 
                line-height: 1.7; 
                font-weight: 500;
            }

            /* Internal Feature Grid */
            .cora-adv-feature-grid { 
                display: grid; 
                grid-template-columns: repeat(2, 1fr); 
                gap: 12px; 
            }
            .cora-feat-card { 
                background: #ffffff; 
                border: 1px solid #f1f5f9; 
                border-radius: 20px; 
                padding: 12px; 
                transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1); 
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
            }
            .cora-feat-card:hover { 
                transform: translateY(-8px); 
                border-color: #3b82f6; 
                box-shadow: 0 20px 40px rgba(0,0,0,0.06); 
            }
            .cora-feat-card h4 { 
                font-size: 18px; 
                font-weight: 800; 
                color: #0f172a; 
                margin-bottom: 12px; 
                line-height: 1.3; 
                letter-spacing: -0.02em;
            }
            .cora-feat-card p { 
                font-size: 15px; 
                color: #64748b; 
                line-height: 1.6; 
                margin: 0; 
                font-weight: 500;
            }

            /* RESPONSIVE */
            @media (max-width: 1024px) {
                .cora-adv-body { flex-direction: column; gap: 60px; }
                .cora-adv-left, .cora-adv-right { width: 100%; }
                .cora-adv-header { margin-bottom: 50px; }
            }
            @media (max-width: 640px) {
                .cora-adv-feature-grid { grid-template-columns: 1fr; }
                .cora-feat-card { padding: 25px; }
                .cora-adv-body { padding: 0 20px; }
            }
        </style>

        <div class="cora-adv-wrap">
            <div class="cora-adv-header">
                <h2><?php echo esc_html($settings['main_title']); ?></h2>
                <p><?php echo esc_html($settings['main_desc']); ?></p>
            </div>

            <div class="cora-adv-body">
                <div class="cora-adv-left">
                    <div class="cora-adv-showcase-img">
                        <?php if ( ! empty( $settings['showcase_img']['url'] ) ) : ?>
                            <img src="<?php echo esc_url($settings['showcase_img']['url']); ?>" alt="Showcase">
                        <?php endif; ?>
                    </div>
                </div>

                <div class="cora-adv-right">
                    <div class="cora-adv-side-text">
                        <h3><?php echo esc_html($settings['side_title']); ?></h3>
                        <p><?php echo esc_html($settings['side_desc']); ?></p>
                    </div>

                    <div class="cora-adv-feature-grid">
                        <?php foreach ( $settings['features'] as $item ) : ?>
                            <div class="cora-feat-card">
                                <h4><?php echo esc_html($item['feature_title']); ?></h4>
                                <p><?php echo esc_html($item['feature_text']); ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}