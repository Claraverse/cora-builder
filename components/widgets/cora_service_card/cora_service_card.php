<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Service_Card extends Base_Widget {

    public function get_name() { return 'cora_service_card'; }
    public function get_title() { return __( 'Cora Service Card', 'cora-builder' ); }
    public function get_icon() { return 'eicon-info-box'; }

    protected function register_controls() {
        
        // --- TAB 1: CONTENT ---
        $this->start_controls_section('section_content', [ 'label' => __( 'Service Content', 'cora-builder' ) ]);
        $this->add_control('badge_icon', [ 'label' => 'Badge Icon', 'type' => Controls_Manager::ICONS, 'default' => [ 'value' => 'fas fa-fire', 'library' => 'solid' ] ]);
        $this->add_control('badge_label', [ 'label' => 'Badge Label', 'type' => Controls_Manager::TEXT, 'default' => 'Design' , 'dynamic' => ['active' => true]]);
        $this->add_control('title', [ 'label' => 'Title', 'type' => Controls_Manager::TEXT, 'default' => 'Clara – UI/UX & Prototype', 'dynamic' => ['active' => true] ]);
        $this->add_control('subtitle', [ 'label' => 'Subtitle', 'type' => Controls_Manager::TEXTAREA, 'default' => 'Conversion-focused branding and systems.', 'dynamic' => ['active' => true] ]);
        $this->add_control('preview_image', [ 'label' => 'Preview Media', 'type' => Controls_Manager::MEDIA, 'dynamic' => ['active' => true] ]);
        $this->end_controls_section();

        // --- TAB 2: STYLE (Architectural Resets) ---
        $this->start_controls_section('style_reset', [ 
            'label' => 'Design Reset', 
            'tab'   => Controls_Manager::TAB_STYLE // Changed to Style Tab for better visibility
        ]);

        // VISUAL FEEDBACK: Tells the user the reset is working
        $this->add_control('reset_status', [
            'type' => Controls_Manager::RAW_HTML,
            'raw'  => '<div style="background: #f1f4ff; color: #1e2b5e; padding: 10px; border-radius: 8px; font-size: 12px; font-weight: bold; text-align: center;">
                        <i class="eicon-check-circle"></i> Cora Structural Reset Active
                      </div>',
        ]);

        $this->add_control('structural_reset', [
            'type' => Controls_Manager::HIDDEN,
            'default' => 'reset',
            'selectors' => [
                '{{WRAPPER}} .service-card-v1' => 'display: flex; flex-direction: column; transition: all 0.3s ease;',
                '{{WRAPPER}} .card-header-group' => 'display: flex; flex-direction: column; gap: 16px;',
                '{{WRAPPER}} .service-badge' => 'display: inline-flex; width: fit-content; align-items: center; gap: 8px; border: 1px solid #0f172a; padding: 6px 16px; border-radius: 100px;',
                '{{WRAPPER}} .service-title, {{WRAPPER}} .service-subtitle' => 'margin: 0 !important; padding: 0;', // Margin Fix
                '{{WRAPPER}} .service-media-frame' => 'width: 100%; overflow: hidden; border-radius: 20px;',
                '{{WRAPPER}} .preview-img' => 'width: 100%; height: auto; display: block; object-fit: cover; transition: transform 0.5s ease;',
                '{{WRAPPER}} .service-card-v1:hover .preview-img' => 'transform: scale(1.03);',
            ],
        ]);

        $this->end_controls_section();

        // --- OPTIMIZED ENGINES (Grouped for Performance) ---
        
        // 1. Text Engines (Focused only on Typography)
        $this->register_text_styling_controls('title_style', 'Title Styling', '{{WRAPPER}} .service-title');
        $this->register_text_styling_controls('desc_style', 'Subtitle Styling', '{{WRAPPER}} .service-subtitle');

        // 2. The Main Layout Geometry (Used ONCE for the Card)
        $this->register_layout_geometry('.service-card-v1'); 

        // 3. The Main Surface (Used ONCE for Colors/Shadows/Radius)
        $this->register_surface_styles('.service-card-v1'); 

        // 4. Spatial & Alignment
        $this->register_common_spatial_controls();
        $this->register_alignment_controls('card_align', '.service-card-v1', '.card-header-group, .service-media-frame');

        // --- TAB 4: ADVANCED ---
        $this->register_interaction_motion();
        $this->register_transform_engine('.service-card-v1');
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="cora-unit-container service-card-v1">
            <div class="card-header-group">
                <?php if ( ! empty( $settings['badge_label'] ) ) : ?>
                <div class="service-badge">
                    <?php Icons_Manager::render_icon( $settings['badge_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                    <span style="font-weight:700; font-size:14px;"><?php echo esc_html($settings['badge_label']); ?></span>
                </div>
                <?php endif; ?>
                
                <h3 class="service-title"><?php echo esc_html($settings['title']); ?></h3>
                <p class="service-subtitle"><?php echo esc_html($settings['subtitle']); ?></p>
            </div>
            
            <div class="service-media-frame" style="margin-top:24px;">
                <?php if ( ! empty( $settings['preview_image']['url'] ) ) : ?>
                    <img src="<?php echo esc_url($settings['preview_image']['url']); ?>" class="preview-img">
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
}