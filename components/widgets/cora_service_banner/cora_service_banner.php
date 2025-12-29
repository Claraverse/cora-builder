<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Service_Banner extends Base_Widget {

    public function get_name() { return 'cora_service_banner'; }
    public function get_title() { return __( 'Cora Service Banner Hero', 'cora-builder' ); }
    public function get_icon() { return 'eicon-banner'; }

    protected function register_controls() {
        
        // --- TAB 1: CONTENT ---
        $this->start_controls_section('section_content', [ 'label' => 'Banner Identity' ]);
        
        $this->add_control('banner_logo', [
            'label'   => 'Brand Logo',
            'type'    => Controls_Manager::MEDIA,
            'default' => [ 'url' => Utils::get_placeholder_image_src() ],
        ]);

        $this->add_control('title', [
            'label'   => 'Service Title',
            'type'    => Controls_Manager::TEXTAREA,
            'default' => 'High-performance E-Commerce Hosting',
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('subline', [
            'label'   => 'Subline',
            'type'    => Controls_Manager::TEXTAREA,
            'default' => 'Work smarter with tasks that can live in your whiteboards, chat, calendar—anywhere you work.',
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('btn_text', [ 'label' => 'Button Text', 'type' => Controls_Manager::TEXT, 'default' => 'Get Started for Free' ]);
        $this->add_control('btn_link', [ 'label' => 'Button Link', 'type' => Controls_Manager::URL, 'dynamic' => ['active' => true] ]);

        $this->add_control('mockup_img', [
            'label'   => 'Right Side Mockup',
            'type'    => Controls_Manager::MEDIA,
            'default' => [ 'url' => Utils::get_placeholder_image_src() ],
        ]);

        $this->end_controls_section();

        // --- TAB 2: STYLE (Structure & Response) ---
        $this->start_controls_section('style_structure', [ 'label' => 'Layout Structure', 'tab' => Controls_Manager::TAB_STYLE ]);

        $this->add_control('reset_info', [
            'type' => Controls_Manager::RAW_HTML,
            'raw'  => '<div style="background:#f1f4ff; color:#1e2b5e; padding:12px; border-radius:12px; font-weight:bold; text-align:center; border:1px solid #dbeafe; margin-bottom: 15px;">
                        <i class="eicon-device-desktop"></i> Responsive Grid Engine Active
                      </div>',
        ]);

        // 1. BASE RESET (Universal Styles Only)
        $this->add_control('base_reset', [
            'type' => Controls_Manager::HIDDEN,
            'default' => 'reset',
            'selectors' => [
                '{{WRAPPER}} .cora-banner-hero' => 'width: 100%; overflow: hidden; display: flex; align-items: center; border-radius: 40px; background-color: #000; box-sizing: border-box;',
                '{{WRAPPER}} .banner-grid' => 'display: grid; width: 100%; align-items: center;',
                '{{WRAPPER}} .banner-logo' => 'display: block; width: auto; height: 32px; margin-bottom: 32px !important;',
                '{{WRAPPER}} .banner-h1, {{WRAPPER}} .banner-p' => 'margin: 0 !important; padding: 0;', // Zero Margin Authority
                '{{WRAPPER}} .banner-btn' => 'display: inline-flex; align-items: center; justify-content: center; text-decoration: none; transition: all 0.3s ease;',
                '{{WRAPPER}} .banner-mockup img' => 'width: 100%; height: auto; object-fit: contain; filter: drop-shadow(0 40px 80px rgba(0,0,0,0.5));',
            ],
        ]);

        // 2. RESPONSIVE GRID COLUMNS
        // Desktop: 2 Columns (Content | Image) | Mobile: 1 Column (Stacked)
        $this->add_responsive_control('grid_columns', [
            'label' => 'Grid Columns',
            'type' => Controls_Manager::SELECT,
            'default' => '1fr 1.2fr',
            'options' => [
                '1fr 1.2fr' => 'Side by Side (1fr 1.2fr)',
                '1fr 1fr' => 'Equal Split (1fr 1fr)',
                '1fr' => 'Stacked (1fr)',
            ],
            // IMPORTANT: This sets the default device values automatically
            'desktop_default' => '1fr 1.2fr',
            'tablet_default' => '1fr',
            'mobile_default' => '1fr',
            'selectors' => [
                '{{WRAPPER}} .banner-grid' => 'grid-template-columns: {{VALUE}};',
            ],
        ]);

        // 3. RESPONSIVE ALIGNMENT
        // Center text on mobile, Left on desktop
        $this->add_responsive_control('content_align', [
            'label' => 'Content Align',
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'left' => [ 'title' => 'Left', 'icon' => 'eicon-text-align-left' ],
                'center' => [ 'title' => 'Center', 'icon' => 'eicon-text-align-center' ],
                'right' => [ 'title' => 'Right', 'icon' => 'eicon-text-align-right' ],
            ],
            'desktop_default' => 'left',
            'tablet_default' => 'center',
            'mobile_default' => 'center',
            'selectors' => [
                '{{WRAPPER}} .banner-content' => 'text-align: {{VALUE}}; align-items: {{VALUE}} == "center" ? center : ({{VALUE}} == "right" ? flex-end : flex-start); display: flex; flex-direction: column;',
            ],
        ]);

        // 4. RESPONSIVE ORDER (Image on Top for Mobile)
        $this->add_responsive_control('mockup_order', [
            'label' => 'Image Position',
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                '0' => [ 'title' => 'Normal', 'icon' => 'eicon-arrow-down' ], 
                '-1' => [ 'title' => 'Top', 'icon' => 'eicon-arrow-up' ],
            ],
            'desktop_default' => '0',
            'tablet_default' => '-1', // Moves image above text on tablet
            'mobile_default' => '-1', // Moves image above text on mobile
            'selectors' => [
                '{{WRAPPER}} .banner-mockup' => 'order: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();

        // --- ELEMENT ENGINES (Colors, Typos, Geometry) ---
        
        // Logo
        $this->register_layout_geometry('.banner-logo', 'logo_geo', 'Logo Layout');

        // Typography
        $this->register_text_styling_controls('title_typo', 'Headline Typography', '{{WRAPPER}} .banner-h1');
        $this->register_text_styling_controls('desc_typo', 'Subline Typography', '{{WRAPPER}} .banner-p');

        // Button
        $this->register_text_styling_controls('btn_typo', 'Button Text', '{{WRAPPER}} .banner-btn');
        $this->register_layout_geometry('.banner-btn', 'btn_geo', 'Button Geometry');
        
        // Force the Gradient Default for Button via Surface
        $this->start_controls_section('btn_skin_section', ['label' => 'Button Skin', 'tab' => Controls_Manager::TAB_STYLE]);
        $this->add_group_control(Group_Control_Background::get_type(), [
            'name' => 'btn_bg_gradient',
            'types' => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .banner-btn',
            'fields_options' => [
                'background' => [ 'default' => 'gradient' ],
                'color' => [ 'default' => '#3b82f6' ],
                'color_b' => [ 'default' => '#d946ef' ],
                'gradient_angle' => [ 'default' => [ 'unit' => 'deg', 'size' => 90 ] ],
            ],
        ]);
        $this->end_controls_section();

        // Global Container (Padding & Background)
        $this->register_global_design_controls('.cora-banner-hero');
        
        // This responsive padding control handles the desktop (60px) vs mobile (24px) spacing
        $this->register_layout_geometry('.cora-banner-hero', 'hero_geo', 'Container Padding');
        
        // Card Background (Black Default)
        $this->start_controls_section('section_hero_bg', ['label' => 'Card Background', 'tab' => Controls_Manager::TAB_STYLE]);
        $this->add_group_control(Group_Control_Background::get_type(), [
            'name' => 'hero_background',
            'selector' => '{{WRAPPER}} .cora-banner-hero',
            'fields_options' => [
                'background' => [ 'default' => 'classic' ],
                'color' => [ 'default' => '#000000' ],
            ],
        ]);
        $this->end_controls_section();
        
        $this->register_common_spatial_controls();

        // Advanced Motion
        $this->register_interaction_motion();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        $this->add_render_attribute('btn', 'class', 'banner-btn');
        if ( ! empty( $settings['btn_link']['url'] ) ) {
            $this->add_link_attributes( 'btn', $settings['btn_link'] );
        }
        ?>
        <div class="cora-banner-hero cora-unit-container">
            <div class="banner-grid">
                <div class="banner-content">
                    <?php if ( ! empty( $settings['banner_logo']['url'] ) ) : ?>
                        <img src="<?php echo esc_url($settings['banner_logo']['url']); ?>" class="banner-logo">
                    <?php endif; ?>
                    
                    <h1 class="banner-h1"><?php echo esc_html($settings['title']); ?></h1>
                    <p class="banner-p"><?php echo esc_html($settings['subline']); ?></p>
                    
                    <a <?php echo $this->get_render_attribute_string('btn'); ?>>
                        <?php echo esc_html($settings['btn_text']); ?>
                    </a>
                </div>

                <div class="banner-mockup">
                    <?php if ( ! empty( $settings['mockup_img']['url'] ) ) : ?>
                        <img src="<?php echo esc_url($settings['mockup_img']['url']); ?>" alt="Service Preview">
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
    }
}