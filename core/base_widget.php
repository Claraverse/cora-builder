<?php
namespace Cora_Builder\Core;

use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class Base_Widget extends Widget_Base {

    public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );
        
        $this->register_component_assets();
	}

    /**
     * Automatically registers style.css and script.js if they exist 
     * in the component folder.
     */
    private function register_component_assets() {
        $slug = $this->get_component_slug();
        
        // CSS Path: components/{slug}/style.css
        $css_file = CORA_BUILDER_PATH . "components/{$slug}/style.css";
        $css_url  = CORA_BUILDER_URL . "components/{$slug}/style.css";

        if ( file_exists( $css_file ) ) {
            wp_register_style( 
                'cora_' . $slug . '_css', 
                $css_url, 
                [], 
                CORA_BUILDER_VERSION 
            );
        }

        // JS Path: components/{slug}/script.js
        $js_file = CORA_BUILDER_PATH . "components/{$slug}/script.js";
        $js_url  = CORA_BUILDER_URL . "components/{$slug}/script.js";

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

    /**
     * Helper to get the folder name from the class.
     * e.g., returns 'dual_heading'
     */
    private function get_component_slug() {
        $class = get_class( $this );
        $parts = explode( '\\', $class );
        $class_name = end( $parts ); // "Dual_Heading"
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
}