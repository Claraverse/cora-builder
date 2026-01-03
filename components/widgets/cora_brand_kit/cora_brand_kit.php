<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Brand_Kit extends Base_Widget {

    public function get_name() { return 'cora_brand_kit'; }
    public function get_title() { return __( 'Cora Brand Kit Widget', 'cora-builder' ); }
    public function get_icon() { return 'eicon-image-box'; }

    protected function register_controls() {
        
        // --- CONTENT SECTION ---
        $this->start_controls_section('content', [ 'label' => 'Content' ]);
        
        $this->add_control('main_img', [ 
            'label' => 'Gallery Showcase Image', 
            'type' => Controls_Manager::MEDIA,
            'default' => [ 'url' => Utils::get_placeholder_image_src() ],
        ]);

        $this->add_control('clients_count', [ 'label' => 'Clients Count', 'type' => Controls_Manager::TEXT, 'default' => '298 Clients' ]);
        $this->add_control('rating', [ 'label' => 'Rating', 'type' => Controls_Manager::TEXT, 'default' => '4.8' ]);
        $this->add_control('success_rate', [ 'label' => 'Success Rate', 'type' => Controls_Manager::TEXT, 'default' => '96% Success Rate' ]);

        $this->add_control('title', [ 
            'label' => 'Headline', 
            'type' => Controls_Manager::TEXT, 
            'default' => 'Brand Kit',
        ]);

        $this->add_control('description', [ 
            'label' => 'Description', 
            'type' => Controls_Manager::TEXTAREA, 
            'default' => 'Logo, color systems, typography, and brand assets — built to last and instantly recognizable.',
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>

        <style>
            /* Container Reset */
            .brand-kit-card {
                width: 100%;
                max-width: 450px; /* Aligned with your typical micro-card width */
                background: #ffffff;
                font-family: 'Inter', sans-serif;
                display: flex;
                flex-direction: column;
                gap: 20px;
            }

            /* Gallery Image Layer */
            .bk-image-wrap {
                width: 100%;
                border-radius: 24px;
                overflow: hidden;
                box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            }

            .bk-image-wrap img {
                width: 100%;
                height: auto;
                display: block;
                object-fit: cover;
            }

            /* Stats Row */
            .bk-stats-row {
                display: flex;
                align-items: center;
                gap: 15px;
                padding: 0 5px;
            }

            .bk-stat-item {
                display: flex;
                align-items: center;
                gap: 6px;
                font-size: 13px;
                font-weight: 600;
                color: #64748b; /* slate-500 */
            }

            .bk-icon {
                width: 16px;
                height: 16px;
                opacity: 0.7;
            }

            /* Content Layer */
            .bk-content {
                display: flex;
                flex-direction: column;
                gap: 8px;
                padding: 0 5px;
            }

            .bk-title {
                margin: 0 !important;
                font-size: clamp(22px, 4vw, 26px);
                font-weight: 850;
                color: #0f172a; /* slate-900 */
                line-height: 1.2;
            }

            .bk-desc {
                margin: 0 !important;
                font-size: 15px;
                color: #64748b;
                line-height: 1.5;
                font-weight: 400;
            }

            /* RESPONSIVE OPTIMIZATION */
            @media (max-width: 768px) {
                .bk-stats-row {
                    flex-wrap: wrap;
                    gap: 10px;
                }
                .bk-stat-item {
                    font-size: 12px;
                }
            }
        </style>

        <div class="brand-kit-card">
            <div class="bk-image-wrap">
                <img src="<?php echo esc_url($settings['main_img']['url']); ?>" alt="Brand Kit Gallery">
            </div>

            <div class="bk-stats-row">
                <div class="bk-stat-item">
                    <svg class="bk-icon" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                    <span><?php echo esc_html($settings['clients_count']); ?></span>
                </div>
                <div class="bk-stat-item">
                    <svg class="bk-icon" fill="currentColor" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                    <span><?php echo esc_html($settings['rating']); ?></span>
                </div>
                <div class="bk-stat-item">
                    <svg class="bk-icon" fill="currentColor" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                    <span><?php echo esc_html($settings['success_rate']); ?></span>
                </div>
            </div>

            <div class="bk-content">
                <h3 class="bk-title"><?php echo esc_html($settings['title']); ?></h3>
                <p class="bk-desc"><?php echo esc_html($settings['description']); ?></p>
            </div>
        </div>

        <?php
    }
}