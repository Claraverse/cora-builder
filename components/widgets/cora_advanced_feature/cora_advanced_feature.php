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

        // --- SECTION: FEATURE GRID & CTA ---
        $this->start_controls_section('grid_section', ['label' => 'Features & CTA']);
        
        $repeater = new Repeater();
        $repeater->add_control('feature_title', [ 'label' => 'Title', 'type' => Controls_Manager::TEXT, 'default' => 'Feature Title' ]);
        $repeater->add_control('feature_text', [ 'label' => 'Description', 'type' => Controls_Manager::TEXTAREA, 'default' => 'Feature description goes here.' ]);
        
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

        $this->add_control('cta_text', [
            'label' => 'CTA Button Text',
            'type' => Controls_Manager::TEXT,
            'default' => 'Start Your Project',
            'separator' => 'before',
        ]);
        $this->add_control('cta_link', [
            'label' => 'CTA Link',
            'type' => Controls_Manager::URL,
            'default' => [ 'url' => '#' ],
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <style>
            /* --- Layout Wrapper --- */
            .cora-adv-wrap { 
                width: 100%; 
                font-family: 'Inter', sans-serif; 
                padding: clamp(40px, 6vw, 80px) 0;
                position: relative;
            }
            
            /* --- Top Header --- */
            .cora-adv-header { 
                text-align: center; 
                margin-bottom: clamp(30px, 5vw, 60px);
                padding: 0 20px;
            }
            .cora-adv-header h2 { 
                font-size: clamp(32px, 5vw, 52px); 
                font-weight: 900; 
                color: #0f172a; 
                margin-bottom: 16px; 
                letter-spacing: -0.03em;
                line-height: 1.1;
            }
            .cora-adv-header p { 
                font-size: clamp(16px, 2vw, 18px); 
                color: #64748b; 
                max-width: 680px; 
                margin: 0 auto; 
                line-height: 1.6;
                font-weight: 500;
            }

            /* --- Main Body Split --- */
            .cora-adv-body { 
                display: flex;
                /* CHANGED: Must be flex-start for sticky column to work correctly */
                align-items: flex-start; 
                gap: clamp(30px, 4vw, 50px);
                max-width: 1280px;
                margin: 0 auto;
                padding: 0 12px;
                position: relative; /* Needed context for sticky child */
            }
            
            /* --- Left Column (Sticky Image) --- */
            .cora-adv-left { 
                flex: 1; 
                width: 50%;
                /* STICKY LOGIC START */
                position: -webkit-sticky; /* Safari support */
                position: sticky;
                /* Fluid Top Offset: Ensures it doesn't hit the very top edge */
                top: clamp(80px, 10vw, 120px); 
                align-self: flex-start; /* Prevents stretching height */
                z-index: 2;
                /* STICKY LOGIC END */
            }

            /* --- Right Column (Scrolling Content) --- */
            .cora-adv-right { 
                flex: 1; 
                width: 50%;
                /* Ensure right column is tall enough to scroll past left */
                min-height: 100%; 
            }

            /* Showcase Image */
            .cora-adv-showcase-img img { 
                width: 100%; 
                height: auto; 
                border-radius: 20px; 
                box-shadow: 0 20px 40px -10px rgba(0,0,0,0.1);
                display: block;
            }

            /* Right Side Typography */
            .cora-adv-side-text { margin-bottom: 30px; }
            
            .cora-adv-side-text h3 { 
                font-size: clamp(26px, 3vw, 32px); 
                font-weight: 850; 
                color: #0f172a; 
                line-height: 1.15; 
                margin-bottom: 12px; 
                letter-spacing: -0.02em;
            }
            .cora-adv-side-text p { 
                font-size: 16px; 
                color: #475569; 
                line-height: 1.6; 
                font-weight: 500;
                max-width: 480px;
            }

            /* Feature Grid */
            .cora-adv-feature-grid { 
                display: grid; 
                grid-template-columns: repeat(2, 1fr); 
                gap: 16px;
                margin-bottom: 30px;
            }
            
            /* Feature Card */
            .cora-feat-card { 
                background: #ffffff; 
                border: 1px solid #f1f5f9; 
                border-radius: 16px; 
                padding: 20px;
                transition: all 0.3s ease;
            }
            .cora-feat-card:hover { 
                transform: translateY(-3px); 
                border-color: #3b82f6; 
                box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05); 
            }
            .cora-feat-card h4 { 
                font-size: 17px; 
                font-weight: 800; 
                color: #0f172a; 
                margin: 0 0 8px 0;
                letter-spacing: -0.01em;
            }
            .cora-feat-card p { 
                font-size: 14px; 
                color: #64748b; 
                line-height: 1.5; 
                margin: 0; 
                font-weight: 500;
            }

            /* --- CTA Button --- */
            .cora-adv-cta-wrap { text-align: left; }
            .cora-adv-cta-btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                padding: 14px 28px;
                background-color: #0f172a;
                color: #ffffff;
                font-weight: 700;
                font-size: 15px;
                border-radius: 50px;
                text-decoration: none;
                transition: all 0.3s ease;
            }
            .cora-adv-cta-btn:hover {
                background-color: #3b82f6;
                transform: translateY(-2px);
                box-shadow: 0 10px 20px -5px rgba(59, 130, 246, 0.3);
            }

            /* --- RESPONSIVE LOGIC --- */
            
            /* Tablet Breakpoint */
            @media (max-width: 992px) {
                .cora-adv-body { 
                    flex-direction: column; 
                    gap: 40px;
                    /* Revert alignment for stacked layout */
                    align-items: stretch; 
                }
                
                /* IMPORTANT: Disable sticky on mobile/tablet stack */
                .cora-adv-left { 
                    width: 100%; 
                    position: static; 
                }
                
                .cora-adv-right { width: 100%; }
                
                .cora-adv-showcase-img {
                    max-width: 700px;
                    margin: 0 auto;
                }
                
                /* Center aligned content on Tablet */
                .cora-adv-side-text { text-align: center; }
                .cora-adv-side-text p { margin: 0 auto; }
                .cora-adv-cta-wrap { text-align: center; }
            }

            /* Mobile Breakpoint */
            @media (max-width: 640px) {
                .cora-adv-feature-grid { 
                    grid-template-columns: 1fr;
                    gap: 12px;
                }
                
                .cora-adv-side-text { text-align: left; }
                .cora-adv-cta-wrap { text-align: left; }
                .cora-adv-cta-btn { width: 100%; }
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

                    <?php if ( ! empty( $settings['cta_text'] ) ) : ?>
                    <div class="cora-adv-cta-wrap">
                        <a class="cora-adv-cta-btn" href="<?php echo esc_url($settings['cta_link']['url']); ?>">
                            <?php echo esc_html($settings['cta_text']); ?>
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
    }
}