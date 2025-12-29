<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Testimonial_Block extends Base_Widget {

    public function get_name() { return 'cora_testimonial_block'; }
    public function get_title() { return __( 'Cora Testimonial Block', 'cora-builder' ); }
    public function get_icon() { return 'eicon-testimonial'; }

    protected function register_controls() {
        
        // --- TAB 1: CONTENT ---
        $this->start_controls_section('section_content', [ 'label' => __( 'Client Feedback', 'cora-builder' ) ]);
        
        $this->add_control('bg_image', [ 
            'label' => 'Client Photo', 
            'type' => Controls_Manager::MEDIA,
            'default' => [ 'url' => \Elementor\Utils::get_placeholder_image_src() ]
        ]);
        
        $this->add_control('category', [ 'label' => 'Industry/Category', 'type' => Controls_Manager::TEXT, 'default' => 'Personal Care' ]);
        
        $this->add_control('testimonial', [
            'label' => 'Testimonial Text',
            'type' => Controls_Manager::TEXTAREA,
            'default' => 'Beautiful new site—simplified bookings and guests love the serene online vibe!',
            'dynamic' => ['active' => true]
        ]);

        $this->add_control('client_name', [ 'label' => 'Client Name', 'type' => Controls_Manager::TEXT, 'default' => 'Dr. Omar Khalid' ]);
        $this->add_control('client_role', [ 'label' => 'Client Role', 'type' => Controls_Manager::TEXT, 'default' => 'Marketing Manager' ]);

        $this->end_controls_section();

        // --- TAB 2: STYLE (Design Reset & Structural Bones) ---
        $this->start_controls_section('style_reset', [ 
            'label' => 'Design Reset', 
            'tab'   => Controls_Manager::TAB_STYLE 
        ]);

        // Visual feedback prevents empty sidebar states
        $this->add_control('reset_info', [
            'type' => Controls_Manager::RAW_HTML,
            'raw'  => '<div style="background:#f1f4ff; color:#1e2b5e; padding:12px; border-radius:12px; font-weight:bold; text-align:center; border:1px solid #dbeafe;">
                        <i class="eicon-check-circle"></i> Cora Structural Reset Active
                      </div>',
        ]);

        $this->add_control('structural_reset', [
            'type' => Controls_Manager::HIDDEN,
            'default' => 'reset',
            'selectors' => [
                '{{WRAPPER}} .cora-testimonial-wrapper' => 'position: relative; height: 550px; background-size: cover; background-position: center; display: flex; align-items: flex-end;',
                '{{WRAPPER}} .testimonial-glass-card' => 'width: 100%; display: flex; flex-direction: column; transition: all 0.3s ease;',
                '{{WRAPPER}} .testi-category, {{WRAPPER}} .testi-content' => 'margin: 0 !important; padding: 0;', // Zero Margin Authority
                '{{WRAPPER}} .testi-meta' => 'display: flex; flex-direction: column; gap: 4px;',
            ],
        ]);
        $this->end_controls_section();

        // --- ELEMENT CONTROL ENGINES ---

        // 1. Glass Card Layer (Padding, Blur, Background)
        $this->register_layout_geometry('.testimonial-glass-card', 'glass_layout', 'Glass Card Layout');
        $this->register_surface_styles('.testimonial-glass-card', 'glass_surface', 'Glass Card Surface');

        // 2. Typography Engines
        $this->register_text_styling_controls('cat_style', 'Category Typography', '{{WRAPPER}} .testi-category');
        $this->register_text_styling_controls('content_style', 'Testimonial Typography', '{{WRAPPER}} .testi-content');
        $this->register_text_styling_controls('meta_style', 'Meta (Name/Role)', '{{WRAPPER}} .testi-meta');

        // --- TAB 3: GLOBAL (Outer Container) ---
        $this->register_global_design_controls('.cora-testimonial-wrapper');
        $this->register_layout_geometry('.cora-testimonial-wrapper'); // Outer padding (24px)
        $this->register_surface_styles('.cora-testimonial-wrapper');  // Outer Border Radius (32px)
        
        $this->register_common_spatial_controls();
        $this->register_alignment_controls('testi_align', '.cora-testimonial-wrapper', '.testimonial-glass-card');

        // --- TAB 4: ADVANCED (Interactions) ---
        $this->register_interaction_motion();
        $this->register_transform_engine('.testimonial-glass-card');
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        // Dynamic background style
        $this->add_render_attribute('wrapper', 'class', 'cora-unit-container cora-testimonial-wrapper');
        if ( ! empty( $settings['bg_image']['url'] ) ) {
            $this->add_render_attribute('wrapper', 'style', 'background-image: url(' . esc_url($settings['bg_image']['url']) . ');');
        }
        ?>
        <div <?php echo $this->get_render_attribute_string('wrapper'); ?>>
            <div class="testimonial-glass-card">
                <h4 class="testi-category"><?php echo esc_html($settings['category']); ?></h4>
                <p class="testi-content"><?php echo esc_html($settings['testimonial']); ?></p>
                <div class="testi-meta">
                    <span class="name"><?php echo esc_html($settings['client_name']); ?></span>
                    <span class="role"><?php echo esc_html($settings['client_role']); ?></span>
                </div>
            </div>
        </div>
        <?php
    }
}