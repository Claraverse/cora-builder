<?php
namespace Cora_Builder\Core;

use Elementor\Widget_Base;

if (!defined('ABSPATH')) {
    exit;
}

// abstract class Base_Widget extends Widget_Base
// {

//     public function __construct($data = [], $args = null)
//     {
//         parent::__construct($data, $args);
//         $this->register_component_assets();
//     }

//     private function register_component_assets()
//     {
//         $slug = $this->get_component_slug();
//         $tier = $this->get_component_tier(); // This replaces the hardcoded 'widgets'

//         // Construction: components/{tier}/{slug}/style.css
//         $relative_path = "components/{$tier}/{$slug}/";

//         $css_file = CORA_BUILDER_PATH . $relative_path . "style.css";
//         $css_url = CORA_BUILDER_URL . $relative_path . "style.css";
//         $js_file = CORA_BUILDER_PATH . $relative_path . "script.js";
//         $js_url = CORA_BUILDER_URL . $relative_path . "script.js";

//         if (file_exists($css_file)) {
//             wp_register_style('cora_' . $slug . '_css', $css_url, [], CORA_BUILDER_VERSION);
//         }

//         if (file_exists($js_file)) {
//             wp_register_script('cora_' . $slug . '_js', $js_url, ['jquery'], CORA_BUILDER_VERSION, true);
//         }
//     }

//     /**
//      * Extracts the folder tier (elements, components, etc.) from the Namespace
//      */
//     private function get_component_tier()
//     {
//         $reflection = new \ReflectionClass($this);
//         $namespace = $reflection->getNamespaceName();

//         // Check namespace for architectural keywords
//         if (strpos($namespace, 'elements') !== false)
//             return 'elements';
//         if (strpos($namespace, 'components') !== false)
//             return 'components';
//         if (strpos($namespace, 'section') !== false)
//             return 'section';

//         return 'widgets'; // Default fallback
//     }

//     private function get_component_slug()
//     {
//         return strtolower((new \ReflectionClass($this))->getShortName());
//     }

//     public function get_style_depends()
//     {
//         return ['cora_' . $this->get_component_slug() . '_css'];
//     }

//     public function get_script_depends()
//     {
//         return ['cora_' . $this->get_component_slug() . '_js'];
//     }
// }


abstract class Base_Widget extends Widget_Base {

    public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );

        $this->register_component_assets();
	}

    private function register_component_assets() {
        $slug = $this->get_component_slug();

        // CSS Path: components/{slug}/style.css
        $css_file = CORA_BUILDER_PATH . "components/widgets/{$slug}/style.css";
        $css_url  = CORA_BUILDER_URL . "components/widgets/{$slug}/style.css";

        if ( file_exists( $css_file ) ) {
            wp_register_style( 
                'cora_' . $slug . '_css', 
                $css_url, 
                [], 
                CORA_BUILDER_VERSION 
            );
        }

        // JS Path: components/{slug}/script.js
        $js_file = CORA_BUILDER_PATH . "components/widgets/{$slug}/script.js";
        $js_url  = CORA_BUILDER_URL . "components/widgets/{$slug}/script.js";

        if ( file_exists( $js_file ) ) {
            wp_register_script( 
                'cora_' . $slug . '_js', 
                $js_url, 
                [ 'jquery' ], 
                CORA_BUILDER_VERSION, 
                true 
            );
        }
    }

    private function get_component_slug() {
        $class = get_class( $this );
        $parts = explode( '\\', $class );
        $class_name = end( $parts );  
        return strtolower( $class_name );
    }

    public function get_style_depends() {
        $slug = $this->get_component_slug();
		return [ 'cora_' . $slug . '_css' ];
	}

    public function get_script_depends() {
        $slug = $this->get_component_slug();
		return [ 'cora_' . $slug . '_js' ];
	}

    /**
     * Common Layout Controls for all Cora Units
     */
    protected function register_common_layout_controls() {
        $this->start_controls_section('cora_common_layout', [
            'label' => __( 'Cora Layout', 'cora-builder' ),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_responsive_control('cora_max_width', [
            'label'      => __( 'Max Width', 'cora-builder' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px', '%', 'vw' ],
            'range'      => [
                'px' => [ 'min' => 200, 'max' => 1600 ],
                '%'  => [ 'min' => 10, 'max' => 100 ],
            ],
            'selectors'  => [ '{{WRAPPER}} .cora-unit-container' => 'max-width: {{SIZE}}{{UNIT}}; margin-left: auto; margin-right: auto;' ],
            'devices'    => [ 'desktop', 'tablet', 'mobile' ], // Requirement: Viewport level control
        ]);

        $this->add_responsive_control('cora_container_align', [
            'label'     => __( 'Container Alignment', 'cora-builder' ),
            'type'      => \Elementor\Controls_Manager::CHOOSE,
            'options'   => [
                'left'   => [ 'title' => 'Left', 'icon' => 'eicon-text-align-left' ],
                'center' => [ 'title' => 'Center', 'icon' => 'eicon-text-align-center' ],
                'right'  => [ 'title' => 'Right', 'icon' => 'eicon-text-align-right' ],
            ],
            'selectors' => [
                '{{WRAPPER}} .cora-unit-container' => 'margin-left: {{VALUE === "left" ? "0" : (VALUE === "right" ? "auto" : "auto")}}; margin-right: {{VALUE === "right" ? "0" : (VALUE === "left" ? "auto" : "auto")}};',
            ],
        ]);

        $this->end_controls_section();
    }

    /**
     * Utility: Truncate text by Word or Letter
     * @param string $text The content to trim
     * @param int $limit The threshold
     * @param string $mode 'words' or 'letters'
     */
    protected function truncate_text($text, $limit, $mode = 'words') {
        if (empty($text) || $limit <= 0) return $text;

        if ($mode === 'letters') {
            return (strlen($text) > $limit) ? substr($text, 0, $limit) . '...' : $text;
        }

        $words = explode(' ', $text);
        if (count($words) > $limit) {
            return implode(' ', array_slice($words, 0, $limit)) . '...';
        }

        return $text;
    }
    /**
     * Common Spatial Controls for all Cora Units
     * Handles Gap, Padding, and Margin at Viewport levels
     */
    protected function register_common_spatial_controls() {
        $this->start_controls_section('cora_spatial_settings', [
            'label' => __( 'Cora Layout & Spacing', 'cora-builder' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

       // Max Width with Dynamic Pen Variable
        $this->add_responsive_control('cora_max_width', [
            'label'      => __( 'Max Width', 'cora-builder' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'em', 'rem', 'vw', '%' ],
            'range'      => [
                'px' => [ 'min' => 0, 'max' => 1600 ],
                'vw' => [ 'min' => 0, 'max' => 100 ],
            ],
            'dynamic'    => [ 'active' => true ], // Enables the "Pen" icon for variables
            'selectors'  => [ '{{WRAPPER}} .cora-unit-container' => 'max-width: {{SIZE}}{{UNIT}};' ],
        ]);

        // Internal Gap with Dynamic Binding
        $this->add_responsive_control('cora_internal_gap', [
            'label'      => __( 'Element Spacing (Gap)', 'cora-builder' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'em', 'rem', 'vw' ],
            'dynamic'    => [ 'active' => true ], // Enables variable binding
            'selectors'  => [ '{{WRAPPER}} .cora-unit-container' => 'gap: {{SIZE}}{{UNIT}};' ],
        ]);

        // Container Padding with Multi-Unit Support
        $this->add_responsive_control('cora_padding', [
            'label'      => __( 'Padding', 'cora-builder' ),
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%', 'em', 'rem', 'vh', 'vw' ],
            'dynamic'    => [ 'active' => true ],
            'selectors'  => [ '{{WRAPPER}} .cora-unit-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
        ]);

        $this->end_controls_section();
    }

    /**
     * Advanced Positioning & Unit Controls
     * Enables Fixed, Relative, and Viewport-based layouts
     */
    protected function register_advanced_positioning_controls() {
        $this->start_controls_section('cora_positioning_section', [
            'label' => __( 'Cora Advanced Positioning', 'cora-builder' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('cora_pos_mode', [
            'label'   => __( 'Position Mode', 'cora-builder' ),
            'type'    => \Elementor\Controls_Manager::SELECT,
            'options' => [
                'static'   => 'Default',
                'relative' => 'Relative',
                'fixed'    => 'Fixed',
                'viewport' => 'Viewport (vh/vw)',
            ],
            'default'   => 'static',
            'selectors' => [ '{{WRAPPER}} .cora-unit-container' => 'position: {{VALUE}};' ],
        ]);

        // Dynamic Unit Support for Width
        $this->add_responsive_control('cora_dynamic_width', [
            'label'      => __( 'Dynamic Width', 'cora-builder' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px', '%', 'vw', 'rem' ],
            'range'      => [
                'px' => [ 'min' => 0, 'max' => 2000 ],
                '%'  => [ 'min' => 0, 'max' => 100 ],
                'vw' => [ 'min' => 0, 'max' => 100 ],
            ],
            'selectors' => [ '{{WRAPPER}} .cora-unit-container' => 'width: {{SIZE}}{{UNIT}};' ],
        ]);

        $this->end_controls_section();
    }
}
