<?php
namespace Cora_Builder\Core;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Dynamic_Tag extends \Elementor\Core\DynamicTags\Tag {

    public function get_name() {
        return 'cora-dynamic-tag';
    }

    public function get_title() {
        return __( 'Cora Field', 'cora-builder' );
    }

    public function get_group() {
        return 'cora-builder-group';
    }

    public function get_categories() {
        return [ 
            \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY,
            \Elementor\Modules\DynamicTags\Module::IMAGE_CATEGORY,
            \Elementor\Modules\DynamicTags\Module::URL_CATEGORY,
        ];
    }

    protected function register_controls() {
        // 1. Fetch all registered field groups
        $groups = get_option('cora_field_groups', []);
        $options = [ '' => __( 'Choose Field', 'cora-builder' ) ];
        
        // 2. Build the options list
        foreach ( $groups as $group ) {
            if ( ! empty( $group['fields'] ) ) {
                foreach ( $group['fields'] as $field ) {
                    // Logic: You can filter here based on $this->get_categories() 
                    // to only show 'image' types for image controls
                    $options[ $field['name'] ] = $group['title'] . ': ' . $field['label'];
                }
            }
        }

        // 3. Add Control: Toggle between Selection and Manual
        $this->add_control(
            'input_type',
            [
                'label' => __( 'Input Method', 'cora-builder' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'select',
                'options' => [
                    'select' => __( 'Select from List', 'cora-builder' ),
                    'manual' => __( 'Manual Entry', 'cora-builder' ),
                ],
            ]
        );

        // 4. Add Control: The Selection Dropdown
        $this->add_control(
            'key_select',
            [
                'label' => __( 'Select Field', 'cora-builder' ),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'label_block' => true,
                'options' => $options,
                'condition' => [
                    'input_type' => 'select',
                ],
            ]
        );

        // 5. Add Control: Manual Key Input
        $this->add_control(
            'key_manual',
            [
                'label' => __( 'Manual Field Slug', 'cora-builder' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => __( 'e.g. my_custom_field', 'cora-builder' ),
                'condition' => [
                    'input_type' => 'manual',
                ],
            ]
        );
    }

    public function render() {
        $settings = $this->get_settings();
        $key = ( 'manual' === $settings['input_type'] ) ? $settings['key_manual'] : $settings['key_select'];

        if ( empty( $key ) ) return;

        $post_id = get_the_ID();
        $values = get_post_meta( $post_id, '_cora_meta_data', true ) ?: [];
        
        $value = isset( $values[ $key ] ) ? $values[ $key ] : '';

        // If it's an image category and we have a value, return it properly
        if ( in_array( \Elementor\Modules\DynamicTags\Module::IMAGE_CATEGORY, $this->get_categories() ) ) {
            echo esc_url( $value );
        } else {
            echo wp_kses_post( $value );
        }
    }
}