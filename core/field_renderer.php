<?php
namespace Cora_Builder\Core;

if (!defined('ABSPATH'))
    exit;

class Field_Renderer
{

    public function render_fields($fields, $values = [])
    {
        if (empty($fields) || !is_array($fields))
            return;

        foreach ($fields as $field) {
            // Safety check: skip fields without names to prevent crashes
            if (empty($field['name']))
                continue;

            $value = isset($values[$field['name']]) ? $values[$field['name']] : ($field['default'] ?? '');
            $type = $field['type'] ?? 'text';

            echo '<div class="cora-field-input-row" style="margin-bottom:20px;">';
            echo '<label style="display:block; font-weight:bold; margin-bottom:5px;">' . esc_html($field['label']) . '</label>';

            // Name attribute must match the "save" logic in Field_Group_Manager
            $input_name = "cora_meta[" . esc_attr($field['name']) . "]";

            switch ($type) {
                case 'textarea':
                    echo '<textarea name="' . $input_name . '" class="large-text" rows="4" style="width:100%">' . esc_textarea($value) . '</textarea>';
                    break;
                case 'image':
                    echo '<div class="cora-media-upload-group">';
                    echo '<input type="text" name="' . $input_name . '" value="' . esc_attr($value) . '" class="regular-text">';
                    echo '<button type="button" class="button cora-media-upload">Upload Image</button>';
                    echo '</div>';
                    break;
                default:
                    echo '<input type="text" name="' . $input_name . '" value="' . esc_attr($value) . '" class="regular-text" style="width:100%">';
                    break;
            }
            echo '</div>';
        }
    }
}