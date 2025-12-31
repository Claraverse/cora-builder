<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Info_Pill extends Base_Widget {

    public function get_name() { return 'cora_info_pill'; }
    public function get_title() { return __( 'Cora Info Pill', 'cora-builder' ); }
    public function get_icon() { return 'eicon-bullet-list'; }

    protected function register_controls() {
        
        // --- TAB 1: CONTENT ---
        $this->start_controls_section('section_content', [ 'label' => __( 'Pill Content', 'cora-builder' ) ]);

        $this->add_control('badge_text', [
            'label'   => 'Badge Text',
            'type'    => Controls_Manager::TEXT,
            'default' => 'New!',
            'dynamic' => [ 'active' => true ],
        ]);

        $this->add_control('pill_message', [
            'label'   => 'Main Message',
            'type'    => Controls_Manager::TEXT,
            'default' => 'One team. One platform. Zero guesswork.',
            'dynamic' => [ 'active' => true ],
        ]);

        $this->add_control('pill_link', [
            'label'   => 'Link',
            'type'    => Controls_Manager::URL,
            'dynamic' => [ 'active' => true ],
        ]);

        $this->end_controls_section();

        // --- TAB 2: STYLE (Design Reset) ---
        $this->start_controls_section('style_reset', [ 'label' => 'Design Reset', 'tab' => Controls_Manager::TAB_STYLE ]);

        // Visual Status Bar
        $this->add_control('reset_info', [
            'type' => Controls_Manager::RAW_HTML,
            'raw'  => '<div style="background:#f1f4ff; color:#1e2b5e; padding:12px; border-radius:12px; font-weight:bold; text-align:center; border:1px solid #dbeafe;">
                        <i class="eicon-check-circle"></i> Design Match Active
                      </div>',
        ]);

        $this->add_control('structural_reset', [
            'type' => Controls_Manager::HIDDEN,
            'default' => 'reset',
            'selectors' => [
                // Main Container (The Outer Pill)
                '{{WRAPPER}} .cora-info-pill-container' => 'display: inline-flex; align-items: center; background: #F1F5F9; border-radius: 100px; padding: 4px 20px 4px 4px; gap: 12px; text-decoration: none; transition: transform 0.2s ease;',
                '{{WRAPPER}} .cora-info-pill-container:hover' => 'transform: translateY(-1px);',

                // The "New!" Badge (Lavender & Purple)
                '{{WRAPPER}} .pill-badge' => 'background: #E9D5FF; color: #581C87; padding: 6px 16px; border-radius: 100px; font-weight: 700; font-size: 14px; line-height: 1; white-space: nowrap;',

                // The Message Text (Grey)
                '{{WRAPPER}} .pill-msg' => 'color: #475569; font-size: 15px; font-weight: 500; line-height: 1; white-space: nowrap;',
            ],
        ]);
        $this->end_controls_section();

        // --- ELEMENT ENGINES ---

        // 1. Badge Styling
        $this->start_controls_section('badge_style', ['label' => 'Badge Style', 'tab' => Controls_Manager::TAB_STYLE]);
        $this->add_control('badge_bg', ['label' => 'Background', 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .pill-badge' => 'background-color: {{VALUE}};']]);
        $this->add_control('badge_color', ['label' => 'Text Color', 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .pill-badge' => 'color: {{VALUE}};']]);
        $this->add_group_control(Group_Control_Typography::get_type(), ['name' => 'badge_typo', 'selector' => '{{WRAPPER}} .pill-badge']);
        $this->end_controls_section();

        // 2. Message Styling
        $this->register_text_styling_controls('msg_style', 'Message Text', '{{WRAPPER}} .pill-msg');

        // 3. Container Styling (Global)
        $this->register_global_design_controls('.cora-info-pill-container');
        $this->register_layout_geometry('.cora-info-pill-container', 'pill_geo', 'Container Layout'); // Padding & Gap
        $this->register_surface_styles('.cora-info-pill-container', 'pill_surface', 'Container Surface'); // BG Color & Border

        // 4. Alignment & Position
        $this->register_alignment_controls('pill_align', '.cora-unit-container', '.cora-info-pill-container');
        $this->register_common_spatial_controls();
        
        $this->register_interaction_motion();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $has_link = ! empty( $settings['pill_link']['url'] );
        
        if ( $has_link ) {
            $this->add_link_attributes( 'wrapper_link', $settings['pill_link'] );
        }
        ?>
        <div class="cora-unit-container">
            <<?php echo $has_link ? 'a' : 'div'; ?> <?php echo $has_link ? $this->get_render_attribute_string( 'wrapper_link' ) : ''; ?> class="cora-info-pill-container">
                <span class="pill-badge"><?php echo esc_html( $settings['badge_text'] ); ?></span>
                <span class="pill-msg"><?php echo esc_html( $settings['pill_message'] ); ?></span>
            </<?php echo $has_link ? 'a' : 'div'; ?>>
        </div>
        <?php
    }
}