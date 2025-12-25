<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Ecosystem_Card extends Base_Widget {

    public function get_name() { return 'cora_ecosystem_card'; }
    public function get_title() { return __( 'Cora Ecosystem Card', 'cora-builder' ); }

    protected function register_controls() {
        
        // --- TAB: CONTENT ---
        $this->start_controls_section('content', [ 'label' => __( 'Ecosystem Content', 'cora-builder' ) ]);
        
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

        // --- TAB: STYLE - ICON BOX ---
        $this->start_controls_section('style_icon', [ 'label' => 'Icon Container', 'tab' => Controls_Manager::TAB_STYLE ]);
        
        $this->add_control('icon_bg', [
            'label' => 'Background Color',
            'type' => Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .eco-icon-box' => 'background-color: {{VALUE}};' ],
        ]);

        $this->add_responsive_control('icon_radius', [
            'label' => 'Border Radius',
            'type' => Controls_Manager::SLIDER,
            'selectors' => [ '{{WRAPPER}} .eco-icon-box' => 'border-radius: {{SIZE}}px;' ]
        ]);

        $this->end_controls_section();

        // Typography & Layout Core
        $this->register_text_styling_controls('title_style', 'Title Typography', '{{WRAPPER}} .eco-title');
        $this->register_alignment_controls('eco_align', '.cora-ecosystem-card', '.eco-icon-box, .eco-content');
        $this->register_common_spatial_controls(); 
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="cora-unit-container cora-ecosystem-card">
            <div class="eco-icon-box">
                <?php \Elementor\Icons_Manager::render_icon( $settings['icon'] ); ?>
            </div>
            <div class="eco-content">
                <h3 class="eco-title"><?php echo esc_html($settings['title']); ?></h3>
                <p class="eco-desc"><?php echo esc_html($settings['desc']); ?></p>
            </div>
        </div>
        <?php
    }
}