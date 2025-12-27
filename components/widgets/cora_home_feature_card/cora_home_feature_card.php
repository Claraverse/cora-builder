<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;

if (!defined('ABSPATH'))
    exit;

class Cora_Home_Feature_Card extends Base_Widget
{

    public function get_name()
    {
        return 'cora_home_feature_card';
    }
    public function get_title()
    {
        return __('Cora Home Feature Card', 'cora-builder');
    }
    public function get_icon()
    {
        return 'eicon-info-box';
    }

    protected function register_controls()
    {

        // --- TAB 1: CONTENT (Icon Mode Toggle) ---
        $this->start_controls_section('section_content', ['label' => __('Feature Content', 'cora-builder')]);

        $this->add_control('icon_source', [
            'label' => __('Icon Source', 'cora-builder'),
            'type' => Controls_Manager::SELECT,
            'default' => 'library',
            'options' => [
                'library' => __('Icon Library', 'cora-builder'),
                'custom' => __('Custom SVG Code', 'cora-builder'),
            ],
        ]);

        $this->add_control('icon', [
            'label' => 'Select Icon',
            'type' => Controls_Manager::ICONS,
            'default' => ['value' => 'fas fa-paint-brush', 'library' => 'solid'],
            'condition' => ['icon_source' => 'library'],
        ]);

        $this->add_control('custom_svg', [
            'label' => 'Paste SVG Code',
            'type' => Controls_Manager::TEXTAREA,
            'condition' => ['icon_source' => 'custom'],
        ]);

        $this->add_control('title', [
            'label' => 'Title',
            'type' => Controls_Manager::TEXT,
            'default' => 'Design',
        ]);

        $this->add_control('desc', [
            'label' => 'Description',
            'type' => Controls_Manager::TEXTAREA,
            'default' => 'Craft experiences that connect, convert, and inspire.',
        ]);

        $this->end_controls_section();

        // --- TAB 2: STYLE (Individual Elements & Resets) ---
        $this->start_controls_section('style_icon', ['label' => 'Icon Skin', 'tab' => Controls_Manager::TAB_STYLE]);

        // MANDATORY: Force Layout Structure (Replaces style.css)
        $this->add_control('layout_structure_reset', [
            'type' => Controls_Manager::HIDDEN,
            'default' => 'reset',
            'selectors' => [
                '{{WRAPPER}} .cora-feature-card-container' => 'display: flex; box-sizing: border-box; transition: all 0.3s ease;',
                '{{WRAPPER}} .feature-icon-box' => 'display: flex; align-items: center; justify-content: center; flex-shrink: 0;',
                '{{WRAPPER}} .feature-icon-box svg' => 'width: 1em; height: 1em; fill: currentColor;',
                '{{WRAPPER}} .feature-content-body' => 'display: flex; flex-direction: column; gap: 8px;',
                '{{WRAPPER}} .feature-title, {{WRAPPER}} .feature-desc' => 'margin: 0 !important; padding: 0;',
            ],
        ]);

        $this->add_responsive_control('icon_size', [
            'label' => 'Icon Size (px)',
            'type' => Controls_Manager::SLIDER,
            'default' => ['size' => 48],
            'selectors' => ['{{WRAPPER}} .feature-icon-box' => 'font-size: {{SIZE}}px;'],
        ]);

        $this->add_control('icon_color', [
            'label' => 'Icon Color',
            'type' => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .feature-icon-box' => 'color: {{VALUE}};'],
        ]);

        $this->end_controls_section();

        // Register Global Engines
        $this->register_text_styling_controls('title_style', 'Title Typography', '{{WRAPPER}} .feature-title');
        $this->register_text_styling_controls('desc_style', 'Description Typography', '{{WRAPPER}} .feature-desc');

        // --- TAB 3: GLOBAL (Surfaces & Colors) ---
        $this->start_controls_section('cora_card_surface', [
            'label' => __('Background & Skin', 'cora-builder'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->start_controls_tabs('cora_card_tabs');
        $this->start_controls_tab('cora_card_normal', ['label' => __('Normal', 'cora-builder')]);
        $this->add_control('card_bg', [
            'label' => 'Background Color',
            'type' => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .cora-feature-card-container' => 'background-color: {{VALUE}};'],
        ]);
        $this->end_controls_tab();

        $this->start_controls_tab('cora_card_hover', ['label' => __('Hover', 'cora-builder')]);
        $this->add_control('card_bg_h', [
            'label' => 'Hover Background',
            'type' => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .cora-feature-card-container:hover' => 'background-color: {{VALUE}};'],
        ]);
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        // Architectural Engines
        $this->register_layout_geometry('.cora-feature-card-container');
        $this->register_surface_styles('.cora-feature-card-container');
        $this->register_common_spatial_controls();
        $this->register_alignment_controls('card_align', '.cora-feature-card-container', '.feature-icon-box, .feature-content-body');

        // --- TAB 4: ADVANCED ---
        $this->register_interaction_motion();
        $this->register_transform_engine('.cora-feature-card-container');
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="cora-unit-container cora-feature-card-container">
            <?php if (!empty($settings['icon']['value']) || !empty($settings['custom_svg'])): ?>
                <div class="feature-icon-box">
                    <?php
                    if ('library' === $settings['icon_source']) {
                        Icons_Manager::render_icon($settings['icon'], ['aria-hidden' => 'true']);
                    } else {
                        echo wp_kses($settings['custom_svg'], ['svg' => ['xmlns' => [], 'viewbox' => [], 'fill' => [], 'class' => []], 'path' => ['d' => [], 'fill' => []]]);
                    }
                    ?>
                </div>
            <?php endif; ?>
            <div class="feature-content-body">
                <h3 class="feature-title"><?php echo esc_html($settings['title']); ?></h3>
                <p class="feature-desc"><?php echo esc_html($settings['desc']); ?></p>
            </div>
        </div>
        <?php
    }
}