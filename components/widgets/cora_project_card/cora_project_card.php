<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Utils;

if (!defined('ABSPATH')) exit;

class Cora_Project_Card extends Base_Widget
{
    public function get_name() { return 'cora_project_card'; }
    public function get_title() { return __('Cora Project Card', 'cora-builder'); }
    public function get_icon() { return 'eicon-info-box'; }

    public function get_style_depends() { return [ 'style' ]; }

    protected function register_controls()
    {
        $this->start_controls_section('content', ['label' => 'Project Info']);
        $this->add_control('industry', [ 'label' => 'Industry Label', 'type' => Controls_Manager::TEXT, 'default' => 'Hospitality', 'dynamic' => ['active' => true] ]);
        $this->add_control('title', [ 'label' => 'Project Name', 'type' => Controls_Manager::TEXT, 'default' => 'Siena Dubai', 'dynamic' => ['active' => true] ]);
        $this->add_control('desc', [ 'label' => 'Description', 'type' => Controls_Manager::TEXTAREA, 'default' => 'Siena Dubai is a casually chic Italian restaurant located in the Grosvenor House Hotel, Dubai.', 'dynamic' => ['active' => true] ]);
        
        $this->add_control('stat_1_label', [ 'label' => 'Stat 1 Label', 'type' => Controls_Manager::TEXT, 'default' => 'Pages in Projects' ]);
        $this->add_control('stat_1_val', [ 'label' => 'Stat 1 Value', 'type' => Controls_Manager::TEXT, 'default' => '40+' ]);
        $this->add_control('stat_2_label', [ 'label' => 'Stat 2 Label', 'type' => Controls_Manager::TEXT, 'default' => 'Project Duration' ]);
        $this->add_control('stat_2_val', [ 'label' => 'Stat 2 Value', 'type' => Controls_Manager::TEXT, 'default' => '8 Weeks' ]);
        $this->end_controls_section();

        $this->start_controls_section('media', ['label' => 'Images & Author']);
        $this->add_control('main_image', [ 'label' => 'Main Project Image', 'type' => Controls_Manager::MEDIA, 'default' => [ 'url' => Utils::get_placeholder_image_src() ], 'dynamic' => ['active' => true] ]);
        $this->add_control('author_image', [ 'label' => 'Author Photo', 'type' => Controls_Manager::MEDIA, 'default' => [ 'url' => Utils::get_placeholder_image_src() ], 'dynamic' => ['active' => true] ]);
        $this->add_control('author_name', [ 'label' => 'Author Name', 'type' => Controls_Manager::TEXT, 'default' => 'Luca Romano', 'dynamic' => ['active' => true] ]);
        $this->add_control('author_role', [ 'label' => 'Author Role', 'type' => Controls_Manager::TEXT, 'default' => 'General Manager', 'dynamic' => ['active' => true] ]);
        $this->add_control('card_link', [ 'label' => 'Link URL', 'type' => Controls_Manager::URL, 'dynamic' => ['active' => true] ]);
        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        if ( ! empty( $settings['card_link']['url'] ) ) {
            $this->add_link_attributes( 'card_link', $settings['card_link'] );
        }
        ?>
        <div class="cora-project-card">
            <div class="cora-card-content">
                <div class="cora-content-top">
                    <h4 class="cora-industry-tag"><?php echo esc_html($settings['industry']); ?></h4>
                    <h2 class="cora-card-title"><?php echo esc_html($settings['title']); ?></h2>
                    <p class="cora-card-desc"><?php echo esc_html($settings['desc']); ?></p>

                    <div class="cora-stats-row">
                        <div class="cora-stat-item">
                            <span class="cora-stat-label"><?php echo esc_html($settings['stat_1_label']); ?></span>
                            <span class="cora-stat-value"><?php echo esc_html($settings['stat_1_val']); ?></span>
                        </div>
                        <div class="cora-stat-item">
                            <span class="cora-stat-label"><?php echo esc_html($settings['stat_2_label']); ?></span>
                            <span class="cora-stat-value"><?php echo esc_html($settings['stat_2_val']); ?></span>
                        </div>
                    </div>
                </div>

                <a <?php echo $this->get_render_attribute_string('card_link'); ?> class="cora-author-banner">
                    <?php if ( ! empty( $settings['author_image']['url'] ) ) : ?>
                        <img src="<?php echo esc_url($settings['author_image']['url']); ?>" class="cora-author-thumb" alt="Author">
                    <?php endif; ?>
                    <div class="cora-author-info">
                        <span class="cora-author-name"><?php echo esc_html($settings['author_name']); ?></span>
                        <span class="cora-author-role"><?php echo esc_html($settings['author_role']); ?></span>
                    </div>
                    <i class="fas fa-arrow-right cora-arrow-icon"></i>
                </a>
            </div>

            <div class="cora-card-media">
                <?php if ( ! empty( $settings['main_image']['url'] ) ) : ?>
                    <img src="<?php echo esc_url($settings['main_image']['url']); ?>" class="cora-main-image" alt="Project Preview">
                <?php endif; ?>
            </div>
        </div>
        <style>
            /* =========================================
           CORA PROJECT CARD (Siena Compact Premium)
           ========================================= */
        
            .cora-project-card {
                display: flex;
                flex-direction: row;
                background-color: #F8FAFF;
                /* Premium Light Blue Surface */
                border: 1px solid #EBEFFF;
                border-radius: 28px;
                /* Slightly tighter radius */
                padding: 10px;
                /* Small outer frame gap */
                width: 100%;
                min-height: 420px;
                /* REDUCED: Fixes the "too big" height issue */
                box-sizing: border-box;
                position: relative;
                transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.02);
                gap: 12px;
            }
        
            .cora-project-card:hover {
                transform: translateY(-4px);
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.06);
            }
        
            /* --- Left Column (Text Content) --- */
            .cora-card-content {
                flex: 1;
                /* Takes ~45% space */
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                /* Pushes author banner to bottom */
                padding: 32px 24px 32px 32px;
                /* Tighter padding */
                box-sizing: border-box;
            }
        
            /* Typography */
            .cora-industry-tag {
                font-family: "Playfair Display", serif;
                font-style: italic;
                font-size: 24px;
                /* Slightly smaller for balance */
                color: #1e293b;
                margin: 0 0 10px 0 !important;
                line-height: 1;
            }
        
            .cora-card-title {
                font-family: "Inter", sans-serif;
                font-size: 36px;
                /* Compact but bold */
                font-weight: 800;
                color: #0f172a;
                margin: 0 0 16px 0 !important;
                line-height: 1.1;
                letter-spacing: -0.03em;
            }
        
            .cora-card-desc {
                font-family: "Inter", sans-serif;
                font-size: 15px;
                color: #64748b;
                line-height: 1.5;
                margin: 0 !important;
                max-width: 95%;
            }
        
            /* Stats Grid */
            .cora-stats-row {
                display: flex;
                gap: 48px;
                margin-top: 24px;
                /* Reduced gap */
            }
        
            .cora-stat-item {
                display: flex;
                flex-direction: column;
                gap: 4px;
            }
        
            .cora-stat-label {
                font-size: 12px;
                font-weight: 700;
                color: #0f172a;
                text-transform: capitalize;
                margin: 0 !important;
                opacity: 0.9;
            }
        
            .cora-stat-value {
                font-size: 16px;
                color: #475569;
                font-weight: 600;
                margin: 0 !important;
            }
        
            /* --- Author / CTA Banner (Purple Gradient) --- */
            .cora-author-banner {
                display: flex !important;
                align-items: center;
                background: linear-gradient(135deg, #9F8BFF 0%, #7E7AFF 100%);
                /* Adjusted Purple */
                padding: 10px 16px;
                /* Sleeker banner */
                border-radius: 16px;
                gap: 14px;
                text-decoration: none !important;
                transition: all 0.3s ease;
                margin-top: 32px;
                /* Pulls it closer to stats */
                width: 100%;
                box-sizing: border-box;
                cursor: pointer;
                box-shadow: 0 4px 12px rgba(126, 122, 255, 0.25);
            }
        
            .cora-author-banner:hover {
                filter: brightness(1.05);
                transform: scale(1.01);
            }
        
            .cora-author-thumb {
                width: 44px !important;
                /* Slightly smaller avatar */
                height: 44px !important;
                border-radius: 100px;
                border: 2px solid #ffffff;
                object-fit: cover;
                flex-shrink: 0;
            }
        
            .cora-author-info {
                display: flex;
                flex-direction: column;
                justify-content: center;
                flex: 1;
            }
        
            .cora-author-name {
                color: #ffffff;
                font-weight: 700;
                font-size: 16px;
                line-height: 1.2;
            }
        
            .cora-author-role {
                color: rgba(255, 255, 255, 0.85);
                font-size: 12px;
                line-height: 1.3;
            }
        
            .cora-arrow-icon {
                color: #ffffff;
                font-size: 20px;
                margin-right: 4px;
                transition: transform 0.3s ease;
            }
        
            .cora-author-banner:hover .cora-arrow-icon {
                transform: translateX(4px);
            }
        
            /* --- Right Column (Image) --- */
            .cora-card-media {
                flex: 1.25;
                /* Image is 55-60% width */
                position: relative;
                    align-items: center;
    justify-content: center;
    display: flex;
                border-radius: 20px;
                /* Inner radius matching outer flow */
                overflow: hidden;
                max-height: 400px;
            }
        
            .cora-main-image {
                width: 100%;
                height: 100%;
                object-fit: cover;
                display: block;
            }
        
            /* --- Responsive (Mobile / Tablet) --- */
            @media (max-width: 1024px) {
                .cora-project-card {
                    flex-direction: column;
                    height: auto;
                    min-height: auto;
                    padding: 8px;
                }
        
                .cora-card-content {
                    padding: 24px;
                    width: 100%;
                    order: 2;
                }
        .cora-card-media {
                flex: 1.25;
                /* Image is 55-60% width */
                position: relative;
                border-radius: 20px;
                /* Inner radius matching outer flow */
                overflow: hidden;
                max-height: 600px;
            }
                .cora-card-title {
                    font-size: 28px;
                }
        
                .cora-card-media {
                    width: 100%;
                    height: 280px;
                    /* Fixed mobile height */
                    flex: none;
                    order: 1;
                    border-radius: 20px;
                }
            }
        </style>
        <?php
    }
}