<?php
namespace Cora_Builder\Core;

if (!defined('ABSPATH')) exit;

class Field_Renderer {

    public function render_fields($fields, $values = []) {
        if (empty($fields) || !is_array($fields)) return;

        wp_enqueue_media(); // Required for Image/File uploads

        echo '<div class="cora-fields-container" style="display:flex; flex-wrap:wrap; gap:20px;">';
        foreach ($fields as $field) {
            $value = isset($values[$field['name']]) ? $values[$field['name']] : '';
            $input_name = "cora_meta[" . esc_attr($field['name']) . "]";
            
            echo '<div class="cora-field-row" style="flex: 1 1 100%; min-width:300px; margin-bottom:15px; background:#fcfcfc; padding:15px; border-radius:8px; border:1px solid #eee;">';
            echo '<label style="display:block; font-weight:600; margin-bottom:8px;">' . esc_html($field['label']) . '</label>';
            
            $this->render_single_field($field, $value, $input_name);
            echo '</div>';
        }
        echo '</div>';
    }

    public function render_single_field($field, $value, $input_name) {
        switch ($field['type']) {
            case 'textarea':
                echo '<textarea name="' . $input_name . '" rows="4" style="width:100%;">' . esc_textarea($value) . '</textarea>';
                break;

            case 'email':
                echo '<input type="email" name="' . $input_name . '" value="' . esc_attr($value) . '" style="width:100%;" placeholder="user@example.com">';
                break;

            case 'url':
                echo '<input type="url" name="' . $input_name . '" value="' . esc_attr($value) . '" style="width:100%;" placeholder="https://...">';
                break;

            case 'image':
            case 'file':
                echo '<div class="cora-media-upload-wrap" style="display:flex; align-items:center; gap:10px;">';
                echo '<input type="text" name="' . $input_name . '" value="' . esc_attr($value) . '" class="cora-media-input" style="flex-grow:1;">';
                echo '<button type="button" class="button cora-media-upload-btn">Upload ' . ucfirst($field['type']) . '</button>';
                echo '</div>';
                break;

            case 'true_false':
                $checked = checked($value, '1', false);
                echo '<label class="cora-switch">';
                echo '<input type="hidden" name="' . $input_name . '" value="0">'; // Default value
                echo '<input type="checkbox" name="' . $input_name . '" value="1" ' . $checked . '>';
                echo ' <span class="switch-label">' . ($value == '1' ? 'Enabled' : 'Disabled') . '</span>';
                echo '</label>';
                break;

            default: // standard text
                echo '<input type="text" name="' . $input_name . '" value="' . esc_attr($value) . '" style="width:100%;">';
                break;
        }
    }
}