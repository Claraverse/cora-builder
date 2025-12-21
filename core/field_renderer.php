<?php
namespace Cora_Builder\Core;

class Field_Renderer {
    
    /**
     * Renders an array of fields into HTML inputs
     */
    public function render_fields($fields, $values = []) {
        if (empty($fields)) return;

        foreach ($fields as $field) {
            $value = isset($values[$field['name']]) ? $values[$field['name']] : '';
            echo '<div class="cora-field-input-row" style="margin-bottom:20px;">';
            echo '<label style="display:block; font-weight:bold; margin-bottom:5px;">' . esc_html($field['label']) . '</label>';
            
            switch ($field['type']) {
                case 'textarea':
                    echo '<textarea name="cora_meta[' . esc_attr($field['name']) . ']" class="large-text">' . esc_textarea($value) . '</textarea>';
                    break;
                case 'image':
                    echo '<input type="text" name="cora_meta[' . esc_attr($field['name']) . ']" value="' . esc_attr($value) . '" class="regular-text">';
                    echo '<button type="button" class="button cora-media-upload">Upload Image</button>';
                    break;
                default: // text
                    echo '<input type="text" name="cora_meta[' . esc_attr($field['name']) . ']" value="' . esc_attr($value) . '" class="regular-text">';
                    break;
            }
            echo '</div>';
        }
    }
}