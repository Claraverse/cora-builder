<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Home_Feature_Card extends Base_Widget {

    public function get_name() { return 'cora_home_feature_card'; }
    public function get_title() { return __( 'Cora Home Feature Card', 'cora-builder' ); }
    public function get_icon() { return 'eicon-info-box'; }

    protected function register_controls() {
        
        // --- TAB: CONTENT ---
        $this->start_controls_section('section_content', [ 'label' => __( 'Feature Content', 'cora-builder' ) ]);

        $this->add_control('icon', [
            'label' => 'Icon',
            'type' => Controls_Manager::ICONS,
            'default' => [ 'value' => 'fas fa-paint-brush', 'library' => 'solid' ]
        ]);

        $this->add_control('title', [
            'label' => 'Title',
            'type' => Controls_Manager::TEXT,
            'default' => 'Design',
            'dynamic' => [ 'active' => true ], // Fully Dynamic
        ]);

        $this->add_control('desc', [
            'label' => 'Description',
            'type' => Controls_Manager::TEXTAREA,
            'default' => 'Craft experiences that connect, convert, and inspire.',
            'dynamic' => [ 'active' => true ],
        ]);

        $this->end_controls_section();

        // --- TAB: STYLE - ICON & BG ---
        $this->start_controls_section('style_card', [ 'label' => 'Card Styling', 'tab' => Controls_Manager::TAB_STYLE ]);
        
        $this->add_control('card_bg', [
            'label' => 'Background Color',
            'type' => Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .cora-feature-card-container' => 'background-color: {{VALUE}};' ],
        ]);

        $this->add_control('icon_color', [
            'label' => 'Icon Color',
            'type' => Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .feature-icon-box' => 'color: {{VALUE}};' ],
        ]);

        $this->end_controls_section();

        // Style Engines: Typo & Spacing
        $this->register_text_styling_controls('title_style', 'Title Typography', '{{WRAPPER}} .feature-title');
        $this->register_text_styling_controls('desc_style', 'Description Typography', '{{WRAPPER}} .feature-desc');
        
        $this->register_common_spatial_controls(); // Controls Horizontal Gap
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="cora-unit-container cora-feature-card-container">
            <div class="feature-icon-box">
                <?php \Elementor\Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] ); ?>
            </div>
            <div class="feature-content-body">
                <h3 class="feature-title"><?php echo esc_html( $settings['title'] ); ?></h3>
                <p class="feature-desc"><?php echo esc_html( $settings['desc'] ); ?></p>
            </div>
        </div>
        <?php
    }
}