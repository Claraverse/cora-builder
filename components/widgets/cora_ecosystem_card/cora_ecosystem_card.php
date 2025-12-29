<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Ecosystem_Card extends Base_Widget {

    public function get_name() { return 'cora_ecosystem_card'; }
    public function get_title() { return __( 'Cora Ecosystem Card', 'cora-builder' ); }
    public function get_icon() { return 'eicon-card'; }

    protected function register_controls() {
        
        // --- TAB 1: CONTENT ---
        $this->start_controls_section('section_content', [ 'label' => __( 'Ecosystem Content', 'cora-builder' ) ]);
        
        $this->add_control('icon', [
            'label' => 'Icon',
            'type' => Controls_Manager::ICONS,
            'default' => [ 'value' => 'fas fa-shopping-cart', 'library' => 'solid' ]
        ]);

        $this->add_control('title', [
            'label' => 'Title',
            'type' => Controls_Manager::TEXT,
            'default' => 'e-Commerce',
            'dynamic' => ['active' => true]
        ]);

        $this->add_control('desc', [
            'label' => 'Description',
            'type' => Controls_Manager::TEXTAREA,
            'default' => 'We help you build your e-commerce stores and digital platforms.',
            'dynamic' => ['active' => true]
        ]);

        $this->end_controls_section();

        // --- TAB 2: STYLE (Design Reset & Structural Bones) ---
        $this->start_controls_section('style_reset', [ 
            'label' => 'Design Reset', 
            'tab'   => Controls_Manager::TAB_STYLE 
        ]);

        // Visual feedback prevents "Empty Section" errors
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
                '{{WRAPPER}} .cora-ecosystem-card' => 'display: flex; flex-direction: column; transition: all 0.3s ease;',
                '{{WRAPPER}} .eco-icon-box' => 'display: flex; align-items: center; justify-content: center; width: 64px; height: 64px; transition: all 0.3s ease;',
                '{{WRAPPER}} .eco-content' => 'display: flex; flex-direction: column; gap: 12px;',
                '{{WRAPPER}} .eco-title, {{WRAPPER}} .eco-desc' => 'margin: 0 !important; padding: 0;', // Zero Margin Authority
                '{{WRAPPER}} .cora-ecosystem-card:hover .eco-icon-box' => 'transform: translateY(-5px);',
            ],
        ]);
        $this->end_controls_section();

        // --- ELEMENT CONTROL ENGINES ---

        // 1. Icon Box Styling (Geometry & Surface)
        $this->register_layout_geometry('.eco-icon-box', 'icon_box_geo', 'Icon Box Layout');
        $this->register_surface_styles('.eco-icon-box', 'icon_box_surface', 'Icon Box Skin');

        // 2. Typography Engines
        $this->register_text_styling_controls('title_style', 'Title Typography', '{{WRAPPER}} .eco-title');
        $this->register_text_styling_controls('desc_style', 'Description Typography', '{{WRAPPER}} .eco-desc');

        // --- TAB 3: GLOBAL (Card Container) ---
        $this->register_global_design_controls('.cora-ecosystem-card');
        $this->register_layout_geometry('.cora-ecosystem-card'); // Main Padding & Gap
        $this->register_surface_styles('.cora-ecosystem-card');  // Card BG & Effects
        $this->register_alignment_controls('eco_align', '.cora-ecosystem-card', '.eco-icon-box, .eco-content');
        $this->register_common_spatial_controls();

        // --- TAB 4: ADVANCED ---
        $this->register_interaction_motion();
        $this->register_transform_engine('.cora-ecosystem-card');
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="cora-unit-container cora-ecosystem-card">
            <div class="eco-icon-box">
                <?php Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] ); ?>
            </div>
            <div class="eco-content">
                <h3 class="eco-title"><?php echo esc_html($settings['title']); ?></h3>
                <p class="eco-desc"><?php echo esc_html($settings['desc']); ?></p>
            </div>
        </div>
        <?php
    }
}