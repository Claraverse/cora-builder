<?php
namespace Cora_Builder\Core;

if (!defined('ABSPATH'))
    exit;

class Field_Renderer
{

    /**
     * MAIN ENTRY: Renders all fields in a group
     */
    public function render_fields($fields, $values = [])
    {
        if (!is_array($fields))
            return;

        wp_enqueue_media();

        echo '<div class="cora-editor-grid" style="display:flex; flex-wrap:wrap; gap:20px;">';
        foreach ($fields as $field) {
            if (empty($field['name']))
                continue;

            $val = $values[$field['name']] ?? '';
            $input_name = "cora_meta[" . esc_attr($field['name']) . "]";

            echo '<div class="cora-field-instance" style="flex: 1 1 100%; min-width: 300px; margin-bottom: 25px; background: #fff; padding: 15px; border-radius: 8px; border: 1px solid #eee;">';
            echo '<label style="display:block; font-weight:700; margin-bottom:12px; font-size:13px;">' . esc_html($field['label']) . '</label>';

            $this->render_single_field($field, $val, $input_name);
            echo '</div>';
        }
        echo '</div>';
    }

    /**
     * MODULAR ROUTER: Add new cases here to support new fields
     */
    public function render_single_field($field, $val, $input_name)
    {
        $type = $field['type'] ?? 'text';

        switch ($type) {
            case 'repeater':
                $this->module_repeater($field, $val, $input_name);
                break;
            case 'post_object':
                $this->module_post_object($field, $val, $input_name);
                break;
            case 'relationship':
                $this->module_relationship($field, $val, $input_name);
                break;
            case 'gallery':
                $this->module_gallery($field, $val, $input_name);
                break;
            case 'image':
                $this->module_image($field, $val, $input_name);
                break;
            case 'true_false':
                $this->module_boolean($field, $val, $input_name);
                break;
            case 'textarea':
                echo '<textarea name="' . $input_name . '" rows="4" style="width:100%; border:1px solid #ddd; border-radius:4px;">' . esc_textarea($val) . '</textarea>';
                break;
            default:
                echo '<input type="text" name="' . $input_name . '" value="' . esc_attr($val) . '" style="width:100%; height:40px; border:1px solid #ddd; border-radius:4px;" placeholder="' . esc_attr($field['placeholder'] ?? '') . '">';
                break;
        }
    }

    /* --- MODULES: Each function handles one specific field type --- */

    private function module_repeater($field, $val, $input_name)
    {
        // SAFETY: Always default to an empty array if JSON is bad
        $subfields = json_decode($field['subfields_json'] ?? '[]', true);
        if (!is_array($subfields))
            $subfields = [];

        $rows = is_array($val) ? $val : [[]];

        echo '<div class="cora-repeater-wrap" data-field="' . esc_attr($field['name']) . '">';
        echo '<div class="cora-repeater-rows" style="border: 1px solid #e2e8f0; border-radius: 8px; background: #f8fafc;">';

        foreach ($rows as $index => $row_data) {
            echo '<div class="repeater-row" style="padding:20px; border-bottom:1px solid #e2e8f0; position:relative; background:#fff; margin-bottom:5px;">';
            echo '<span class="dashicons dashicons-no-alt remove-repeater-row" style="position:absolute; top:10px; right:10px; color:#ef4444; cursor:pointer;"></span>';

            foreach ($subfields as $sub) {
                $sub_val = $row_data[$sub['name']] ?? '';
                $sub_input_name = "{$input_name}[{$index}][{$sub['name']}]";
                echo '<div style="margin-bottom:12px;"><label style="font-size:11px; font-weight:700; display:block; margin-bottom:5px;">' . esc_html($sub['label']) . '</label>';
                echo '<input type="text" name="' . esc_attr($sub_input_name) . '" value="' . esc_attr($sub_val) . '" style="width:100%; border:1px solid #cbd5e1; height:34px;"></div>';
            }
            echo '</div>';
        }
        echo '</div><button type="button" class="button add-repeater-row" style="margin-top:15px;">+ Add Row</button></div>';
    }

    private function module_post_object($field, $val, $input_name)
    {
        $posts = get_posts(['post_type' => 'any', 'posts_per_page' => 50]) ?: [];
        echo '<select name="' . $input_name . '" style="width:100%; height:40px; border:1px solid #ddd;">';
        echo '<option value="">Select a Post...</option>';
        foreach ($posts as $p) {
            echo '<option value="' . esc_attr($p->ID) . '" ' . selected($val, $p->ID, false) . '>' . esc_html($p->post_title) . ' (' . esc_html($p->post_type) . ')</option>';
        }
        echo '</select>';
    }

    private function module_relationship($field, $val, $input_name)
    {
        $posts = get_posts(['post_type' => 'any', 'posts_per_page' => 50]) ?: [];
        $selected_ids = is_array($val) ? $val : (!empty($val) ? explode(',', $val) : []);

        echo '<div class="cora-relationship-wrap" style="border:1px solid #ddd; border-radius:8px; padding:15px; background:#fff; max-height:200px; overflow-y:auto;">';
        foreach ($posts as $p) {
            $checked = in_array($p->ID, $selected_ids) ? 'checked' : '';
            echo '<label style="display:block; margin-bottom:8px; cursor:pointer;"><input type="checkbox" name="' . $input_name . '[]" value="' . $p->ID . '" ' . $checked . '> ' . esc_html($p->post_title) . '</label>';
        }
        echo '</div>';
    }

    private function module_gallery($field, $val, $input_name)
    {
        $urls = !empty($val) ? explode(',', $val) : [];
        echo '<div class="cora-media-upload-wrap" data-type="gallery">';
        echo '<div class="cora-gallery-preview" style="display:grid; grid-template-columns:repeat(auto-fill, 80px); gap:10px; margin-bottom:12px; min-height:80px; border:1px dashed #ddd; padding:10px;">';
        foreach ($urls as $url) {
            if (!empty($url))
                echo '<div class="gallery-item" style="width:80px; height:80px; position:relative;"><img src="' . esc_url($url) . '" style="width:100%; height:100%; object-fit:cover;"><span class="dashicons dashicons-no-alt remove-gal-img" style="position:absolute; top:2px; right:2px; background:#fff; cursor:pointer; border-radius:50%;"></span></div>';
        }
        echo '</div><input type="hidden" name="' . $input_name . '" value="' . esc_attr($val) . '" class="cora-media-input"><button type="button" class="button cora-media-upload-btn">Manage Gallery</button></div>';
    }

    private function module_image($field, $val, $input_name)
    {
        echo '<div class="cora-media-upload-wrap" style="display:flex; flex-direction:column; gap:10px;">';
        echo '<div class="cora-media-preview" style="width:120px; height:120px; border:1px dashed #ccc; display:flex; align-items:center; justify-content:center; background:#f9f9f9;">';
        echo !empty($val) ? '<img src="' . esc_url($val) . '" style="max-width:100%; max-height:100%;">' : '<span class="dashicons dashicons-format-image" style="font-size:30px; color:#ccc;"></span>';
        echo '</div><div style="display:flex; gap:10px;"><input type="text" name="' . $input_name . '" value="' . esc_attr($val) . '" class="cora-media-input" style="flex-grow:1;"><button type="button" class="button cora-media-upload-btn">Select Image</button></div></div>';
    }

    private function module_boolean($field, $val, $input_name)
    {
        $checked = checked($val, '1', false);
        echo '<label style="display:flex; align-items:center; cursor:pointer; gap:10px;">';
        echo '<input type="hidden" name="' . $input_name . '" value="0">';
        echo '<input type="checkbox" name="' . $input_name . '" value="1" ' . $checked . '>';
        echo '<span>' . ($val == '1' ? 'Enabled' : 'Disabled') . '</span></label>';
    }
}