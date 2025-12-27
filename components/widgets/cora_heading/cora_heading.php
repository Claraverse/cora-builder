<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Heading extends Base_Widget {

    public function get_name() { return 'cora_heading'; }
    public function get_title() { return __( 'Cora Heading', 'cora-builder' ); }
    public function get_icon() { return 'eicon-t-letter'; }

    protected function register_controls() {
        
        // --- TAB 1: CONTENT ---
        $this->start_controls_section('section_content', [ 'label' => __( 'Title Content', 'cora-builder' ) ]);

        $this->add_control('title', [
            'label'   => 'Title Text',
            'type'    => Controls_Manager::TEXT,
            'default' => 'Add Your Heading Here',
            'dynamic' => [ 'active' => true ],
        ]);

        $this->add_control('header_tag', [
            'label'   => 'HTML Tag',
            'type'    => Controls_Manager::SELECT,
            'options' => [
                'h1'   => 'H1',
                'h2'   => 'H2',
                'h3'   => 'H3',
                'h4'   => 'H4',
                'h5'   => 'H5',
                'h6'   => 'H6',
                'p'    => 'p',
                'span' => 'span',
                'div'  => 'div',
            ],
            'default' => 'h2',
        ]);

        $this->add_control('link', [
            'label'   => 'Link',
            'type'    => Controls_Manager::URL,
            'dynamic' => [ 'active' => true ],
        ]);

        $this->end_controls_section();

        // --- TAB 2: STYLE (Global Design System) ---
        // Integrated engines from Base_Widget mapping to your Figma-style UI
        
        // 1. Typography Engine (Normal/Hover/Alignment)
        $this->register_text_styling_controls('heading_style', 'Text Styling', '{{WRAPPER}} .cora-heading-text');

        // 2. Surface & Layout (Glassmorphism & Sizing)
        $this->register_global_design_controls('.cora-heading-container');
        $this->register_layout_geometry('.cora-heading-container');
        $this->register_surface_styles('.cora-heading-container');

        // 3. Spatial & Alignment Matrix
        $this->register_common_spatial_controls();
        $this->register_alignment_controls('heading_align', '.cora-heading-container', '.cora-heading-text');

        // --- TAB 4: ADVANCED (Motion & Interactivity) ---
        $this->register_interaction_motion();
        $this->register_transform_engine('.cora-heading-text'); // Kinetic typography
        $this->register_cursor_engine('.cora-heading-container');
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $tag = $settings['header_tag'];
        $has_link = ! empty( $settings['link']['url'] );
        
        if ( $has_link ) {
            $this->add_link_attributes( 'heading_link', $settings['link'] );
        }
        ?>
        <div class="cora-unit-container cora-heading-container">
            <<?php echo $tag; ?> class="cora-heading-text">
                <?php if ( $has_link ) : ?>
                    <a <?php echo $this->get_render_attribute_string( 'heading_link' ); ?>>
                        <?php echo esc_html( $settings['title'] ); ?>
                    </a>
                <?php else : ?>
                    <?php echo esc_html( $settings['title'] ); ?>
                <?php endif; ?>
            </<?php echo $tag; ?>>
        </div>
        <?php
    }
}