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
        
        // --- TAB: CONTENT ---
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

        // --- TAB: STYLE - PILL CONTAINER ---
        $this->start_controls_section('style_pill', [ 'label' => 'Container Styling', 'tab' => Controls_Manager::TAB_STYLE ]);
        
        $this->add_control('pill_bg', [
            'label'     => 'Pill Background',
            'type'      => Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .cora-info-pill-container' => 'background-color: {{VALUE}};' ],
        ]);

        $this->add_control('pill_border_color', [
            'label'     => 'Border Color',
            'type'      => Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .cora-info-pill-container' => 'border-color: {{VALUE}};' ],
        ]);

        $this->end_controls_section();

        // --- STYLE ENGINES: Typo & Spacing ---
        $this->register_text_styling_controls('badge_style', 'Badge Typography', '{{WRAPPER}} .pill-badge');
        $this->register_text_styling_controls('msg_style', 'Message Typography', '{{WRAPPER}} .pill-msg');
        // Inside register_controls()
$this->register_alignment_controls('pill_layout', '.cora-info-pill-container', '.pill-badge, .pill-msg');

        $this->register_common_spatial_controls();
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