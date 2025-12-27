<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;

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

        // --- TAB 2: STYLE (Widget Specific) ---
        $this->start_controls_section('style_pill', [ 'label' => 'Pill Skin Settings', 'tab' => Controls_Manager::TAB_STYLE ]);
        
        $this->add_control('badge_bg_local', [
            'label'     => 'Badge Background',
            'type'      => Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .pill-badge' => 'background-color: {{VALUE}};' ],
        ]);

        $this->end_controls_section();

        // Register Core Typography Engines
        $this->register_text_styling_controls('badge_style', 'Badge Typography', '{{WRAPPER}} .pill-badge');
        $this->register_text_styling_controls('msg_style', 'Message Typography', '{{WRAPPER}} .pill-msg');

        // --- TAB 3: GLOBAL (Design System & Structural Layout) ---
        // Integrated engines from Base_Widget mapping to your screenshots
        
        // 1. System Tokens
        $this->register_global_design_controls('.cora-info-pill-container');

        // 2. Layout & Geometry (Hug/Fill & Flex Distribution)
        $this->register_layout_geometry('.cora-info-pill-container');

        // 3. Styles & Surface (Glassmorphism & Shadows)
        $this->register_surface_styles('.cora-info-pill-container');

        // 4. Spatial Matrix (Rotation & Scale)
        $this->register_common_spatial_controls();
        
        // 5. Alignment Matrix
        $this->register_alignment_controls('pill_layout', '.cora-info-pill-container', '.pill-badge, .pill-msg');

        // --- TAB 4: ADVANCED (Interactive & Code) ---
        $this->register_overlay_engine('.cora-info-pill-container');
        $this->register_cursor_engine('.cora-info-pill-container');
        $this->register_transform_engine('.cora-info-pill-container');
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