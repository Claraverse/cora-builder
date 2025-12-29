<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Conversion_Pill extends Base_Widget {

    public function get_name() { return 'cora_conversion_pill'; }
    public function get_title() { return __( 'Cora Conversion Pill', 'cora-builder' ); }
    public function get_icon() { return 'eicon-person'; }

    protected function register_controls() {
        
        // --- TAB 1: CONTENT ---
        $this->start_controls_section('section_content', [ 'label' => __( 'Social Proof', 'cora-builder' ) ]);
        
        $repeater = new Repeater();
        $repeater->add_control('client_img', [ 
            'label' => 'Avatar', 
            'type' => Controls_Manager::MEDIA,
            'default' => [ 'url' => Utils::get_placeholder_image_src() ]
        ]);

        $this->add_control('avatars', [
            'label' => 'Client Avatars',
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                [ 'client_img' => [ 'url' => Utils::get_placeholder_image_src() ] ],
                [ 'client_img' => [ 'url' => Utils::get_placeholder_image_src() ] ],
                [ 'client_img' => [ 'url' => Utils::get_placeholder_image_src() ] ],
            ],
        ]);

        $this->add_control('proof_text', [
            'label' => 'Proof Label',
            'type' => Controls_Manager::TEXT,
            'default' => 'Join 460+ Clients',
            'dynamic' => ['active' => true]
        ]);

        $this->add_control('btn_text', [ 'label' => 'Button Text', 'type' => Controls_Manager::TEXT, 'default' => 'Book Now' ]);
        $this->add_control('btn_link', [ 'label' => 'Button Link', 'type' => Controls_Manager::URL, 'dynamic' => ['active' => true] ]);
        
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
                '{{WRAPPER}} .cora-conv-pill' => 'display: inline-flex;',
                '{{WRAPPER}} .pill-inner' => 'display: inline-flex; align-items: center; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 100px; padding: 6px 6px 6px 12px; gap: 16px;',
                '{{WRAPPER}} .avatar-stack' => 'display: flex; align-items: center;',
                '{{WRAPPER}} .avatar-circle' => 'width: 32px; height: 32px; border-radius: 50%; border: 2px solid #ffffff; overflow: hidden; margin-left: -10px;', // Overlap Magic
                '{{WRAPPER}} .avatar-circle:first-child' => 'margin-left: 0;',
                '{{WRAPPER}} .avatar-circle img' => 'width: 100%; height: 100%; object-fit: cover;',
                '{{WRAPPER}} .proof-label' => 'margin: 0 !important; font-size: 15px; font-weight: 600; color: #1e293b;',
                '{{WRAPPER}} .pill-btn' => 'background: #1e2b5e; color: #ffffff; padding: 10px 24px; border-radius: 100px; text-decoration: none; font-weight: 700; font-size: 14px; display: flex; align-items: center; gap: 8px; transition: transform 0.2s ease;',
                '{{WRAPPER}} .pill-btn:hover' => 'transform: scale(1.02);',
            ],
        ]);
        $this->end_controls_section();

        // --- ELEMENT CONTROL ENGINES ---

        // 1. Avatar Configuration
        $this->start_controls_section('avatar_style', ['label' => 'Avatar Stack', 'tab' => Controls_Manager::TAB_STYLE]);
        
        $this->add_responsive_control('avatar_size', [
            'label' => 'Avatar Size',
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => [ 'px' => [ 'min' => 20, 'max' => 60 ] ],
            'selectors' => [ '{{WRAPPER}} .avatar-circle' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};' ],
        ]);

        $this->add_responsive_control('avatar_overlap', [
            'label' => 'Stack Overlap',
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => [ 'px' => [ 'min' => -30, 'max' => 10 ] ],
            'default' => [ 'unit' => 'px', 'size' => -10 ],
            'selectors' => [ '{{WRAPPER}} .avatar-circle:not(:first-child)' => 'margin-left: {{SIZE}}{{UNIT}};' ],
        ]);

        $this->add_control('avatar_border_color', [
            'label' => 'Border Color',
            'type' => Controls_Manager::COLOR,
            'default' => '#ffffff',
            'selectors' => [ '{{WRAPPER}} .avatar-circle' => 'border-color: {{VALUE}};' ],
        ]);
        
        $this->end_controls_section();

        // 2. Proof Label Typography
        $this->register_text_styling_controls('proof_typo', 'Proof Text', '{{WRAPPER}} .proof-label');

        // 3. Button Engine
        // We use specific engines here to allow full button customization (colors, padding, hover)
        $this->register_text_styling_controls('btn_typo', 'Button Typography', '{{WRAPPER}} .pill-btn');
        $this->register_layout_geometry('.pill-btn', 'btn_geo', 'Button Geometry');
        $this->register_surface_styles('.pill-btn', 'btn_surface', 'Button Skin');

        // --- TAB 3: GLOBAL (Main Pill Container) ---
        // This controls the white background, border radius (100px), and main padding
        $this->register_global_design_controls('.pill-inner');
        $this->register_layout_geometry('.pill-inner', 'pill_geo', 'Pill Layout');
        $this->register_surface_styles('.pill-inner', 'pill_surface', 'Pill Surface');
        
        $this->register_alignment_controls('pill_align', '.cora-conv-pill', '.pill-inner');
        $this->register_common_spatial_controls();

        // --- TAB 4: ADVANCED ---
        $this->register_interaction_motion();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        $this->add_render_attribute('btn', 'class', 'pill-btn');
        if ( ! empty( $settings['btn_link']['url'] ) ) {
            $this->add_link_attributes( 'btn', $settings['btn_link'] );
        }
        ?>
        <div class="cora-unit-container cora-conv-pill">
            <div class="pill-inner">
                <div class="avatar-stack">
                    <?php if ( ! empty( $settings['avatars'] ) ) : ?>
                        <?php foreach ($settings['avatars'] as $avatar) : ?>
                            <div class="avatar-circle">
                                <?php if ( ! empty( $avatar['client_img']['url'] ) ) : ?>
                                    <img src="<?php echo esc_url($avatar['client_img']['url']); ?>" alt="Client">
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <span class="proof-label"><?php echo esc_html($settings['proof_text']); ?></span>

                <a <?php echo $this->get_render_attribute_string('btn'); ?>>
                    <?php echo esc_html($settings['btn_text']); ?>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
        <?php
    }
}