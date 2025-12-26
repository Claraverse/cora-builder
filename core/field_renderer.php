<?php
namespace Cora_Builder\Core;

if (!defined('ABSPATH'))
    exit;

/**
 * Modular Field Renderer
 * Handles the generation of HTML inputs for all Cora Custom Fields.
 */
class Field_Renderer
{

    /**
     * Main entry point to render a collection of fields (e.g., in a meta box).
     */
    public function render_fields($fields, $values = [])
    {
        if (!is_array($fields))
            return;

        wp_enqueue_media();
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');

        // jQuery UI is used as a fallback for standard Date Pickers
        wp_enqueue_script('jquery-ui-datepicker');
        wp_enqueue_style('jquery-ui-style', '//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css');

        echo '<div class="cora-editor-grid" style="display:flex; flex-wrap:wrap; gap:20px;">';
        foreach ($fields as $field) {
            if (empty($field['name']))
                continue;

            $val = $values[$field['name']] ?? '';
            $input_name = "cora_meta[" . esc_attr($field['name']) . "]";

            echo '<div class="cora-field-instance" style="flex: 1 1 100%; min-width: 300px; margin-bottom: 25px; background: #fff; padding: 20px; border-radius: 8px; border: 1px solid #eee;">';
            echo '<label style="display:block; font-weight:700; margin-bottom:12px; font-size:13px; color:#333;">' . esc_html($field['label']) . '</label>';

            $this->render_single_field($field, $val, $input_name);
            echo '</div>';
        }
        echo '</div>';
        $this->render_editor_scripts();
    }

    /**
     * MODULAR ROUTER
     */
    public function render_single_field($field, $val, $input_name)
    {
        $type = $field['type'] ?? 'text';
        $string_val = is_array($val) ? '' : $val;

        switch ($type) {
            // LAYOUT MODULES
            case 'message':
                $this->module_message($field);
                break;
            case 'group':
            case 'accordion':
            case 'tab':
                $this->module_nested_group($field, $val, $input_name);
                break;
            case 'repeater':
            case 'flexible_content':
                $this->module_repeater($field, $val, $input_name);
                break;
            case 'clone':
                $this->module_clone($field, $val, $input_name);
                break;
            case 'google_map':
                $this->module_google_map($field, $string_val, $input_name);
                break;
            case 'date_picker':
            case 'date_time_picker':
            case 'time_picker':
                $this->module_date_time($field, $string_val, $input_name);
                break;
            case 'color_picker':
                $this->module_color($field, $string_val, $input_name);
                break;
            case 'icon_picker':
                $this->module_icon($field, $string_val, $input_name);
                break;
            case 'page_link':
                $this->module_page_link($field, $string_val, $input_name);
                break;
            case 'taxonomy':
                $this->module_taxonomy($field, $string_val, $input_name);
                break;
            case 'user':
                $this->module_user($field, $string_val, $input_name);
                break;
            case 'select':
            case 'checkbox':
            case 'radio':
            case 'button_group':
                $this->module_choices($field, $val, $input_name);
                break;

            case 'gallery':
                $this->module_gallery($field, $string_val, $input_name);
                break;
            case 'image':
                $this->module_image($field, $string_val, $input_name);
                break;
            case 'wysiwyg':
                $this->module_wysiwyg($field, $string_val, $input_name);
                break;
            case 'true_false':
                $this->module_boolean($field, $string_val, $input_name);
                break;
            case 'oembed':
                $this->module_oembed($field, $string_val, $input_name);
                break;
            case 'number':
                echo '<input type="number" name="' . $input_name . '" value="' . esc_attr($string_val) . '" style="width:100%; height:40px; border:1px solid #ddd; border-radius:4px;">';
                break;
            case 'textarea':
                echo '<textarea name="' . $input_name . '" rows="4" style="width:100%; border:1px solid #ddd; border-radius:4px;">' . esc_textarea($string_val) . '</textarea>';
                break;
            default:
                echo '<input type="text" name="' . $input_name . '" value="' . esc_attr($string_val) . '" style="width:100%; height:40px; border:1px solid #ddd; border-radius:4px;" placeholder="' . esc_attr($field['placeholder'] ?? '') . '">';
                break;
        }
    }

    /* --- PRIVATE SCRIPTS --- */

    private function render_editor_scripts()
    {
        ?>
        <script>
            jQuery(document).ready(function ($) {
                // Initialize Color Picker
                if ($.fn.wpColorPicker) {
                    $('.cora-color-picker').wpColorPicker();
                }

                // Fallback: If browser doesn't support HTML5 date, use jQuery UI for basic dates
                if ($.fn.datepicker && $('.cora-date_picker').prop('type') === 'text') {
                    $('.cora-date_picker').datepicker({ dateFormat: 'yy-mm-dd' });
                }

                // Real-time Icon Preview
                $(document).on('keyup change', '.cora-icon-input', function () {
                    const val = $(this).val();
                    $(this).closest('.cora-icon-picker-wrap').find('.icon-preview span').attr('class', 'dashicons ' + val);
                });
            });
        </script>
        <?php
    }

    /* --- MODULES --- */

    private function module_date_time($field, $val, $input_name)
    {
        // Use native HTML5 types for maximum compatibility and functional pickers
        $html_type = 'text';
        if ($field['type'] === 'date_picker')
            $html_type = 'date';
        if ($field['type'] === 'time_picker')
            $html_type = 'time';
        if ($field['type'] === 'date_time_picker')
            $html_type = 'datetime-local';

        echo '<div class="cora-datetime-wrap">';
        echo '<input type="' . $html_type . '" name="' . $input_name . '" value="' . esc_attr($val) . '" class="cora-input cora-' . $field['type'] . '" style="width:100%; height:40px; border:1px solid #ddd; border-radius:4px;">';
        echo '</div>';
    }

    private function module_color($field, $val, $input_name)
    {
        echo '<input type="text" name="' . $input_name . '" value="' . esc_attr($val) . '" class="cora-color-picker" data-default-color="#ffffff">';
    }

    private function module_icon($field, $val, $input_name)
    {
        echo '<div class="cora-icon-picker-wrap" style="display:flex; gap:10px;">';
        echo '<div class="icon-preview" style="width:40px; height:40px; border:1px solid #ddd; display:flex; align-items:center; justify-content:center; background:#f9f9f9;"><span class="dashicons ' . ($val ?: 'dashicons-smiley') . '"></span></div>';
        echo '<input type="text" name="' . $input_name . '" value="' . esc_attr($val) . '" class="cora-input cora-icon-input" placeholder="e.g. dashicons-admin-users" style="flex-grow:1;">';
        echo '</div>';
    }

    private function module_image($field, $val, $input_name)
    {
        echo '<div class="cora-media-upload-wrap" style="display:flex; flex-direction:column; gap:12px;">';
        echo '<div class="cora-media-preview" style="width:140px; height:140px; border:1px dashed #cbd5e1; display:flex; align-items:center; justify-content:center; background:#f8fafc; border-radius:12px; overflow:hidden;">';
        echo !empty($val) ? '<img src="' . esc_url($val) . '" style="max-width:100%; max-height:100%;">' : '<span class="dashicons dashicons-format-image" style="font-size:40px; color:#cbd5e1;"></span>';
        echo '</div><div style="display:flex; gap:10px;"><input type="text" name="' . $input_name . '" value="' . esc_attr($val) . '" class="cora-media-input" style="flex-grow:1; height:36px;"><button type="button" class="button cora-media-upload-btn">Select Image</button></div></div>';
    }

    private function module_choices($field, $val, $input_name)
    {
        $choices = $this->parse_choices($field['choices'] ?? '');
        if ($field['type'] === 'select') {
            echo '<select name="' . $input_name . '" style="width:100%; height:40px; border:1px solid #ddd; border-radius:4px;">';
            foreach ($choices as $k => $v) {
                echo '<option value="' . esc_attr($k) . '" ' . selected($val, $k, false) . '>' . esc_html($v) . '</option>';
            }
            echo '</select>';
        } elseif ($field['type'] === 'checkbox') {
            $selected = is_array($val) ? $val : [];
            echo '<div class="cora-checkbox-group" style="background:#fcfcfc; padding:15px; border:1px solid #eee; border-radius:4px;">';
            foreach ($choices as $k => $v) {
                $checked = in_array($k, $selected) ? 'checked' : '';
                echo '<label style="display:block; margin-bottom:8px; cursor:pointer;"><input type="checkbox" name="' . $input_name . '[]" value="' . esc_attr($k) . '" ' . $checked . '> ' . esc_html($v) . '</label>';
            }
            echo '</div>';
        } else {
            echo '<div class="cora-radio-group" style="display:flex; gap:20px; flex-wrap:wrap;">';
            foreach ($choices as $k => $v) {
                $checked = ($val == $k) ? 'checked' : '';
                echo '<label style="cursor:pointer; font-size:13px; display:flex; align-items:center; gap:6px;"><input type="radio" name="' . $input_name . '" value="' . esc_attr($k) . '" ' . $checked . '> ' . esc_html($v) . '</label>';
            }
            echo '</div>';
        }
    }

    private function module_repeater($field, $val, $input_name)
    {
        $subfields = json_decode($field['subfields_json'] ?? '[]', true) ?: [];
        $rows = is_array($val) ? $val : [[]];
        echo '<div class="cora-repeater-wrap" data-field="' . esc_attr($field['name']) . '">';
        echo '<div class="cora-repeater-rows" style="border: 1px solid #e2e8f0; border-radius: 8px; background: #f8fafc;">';
        foreach ($rows as $index => $row_data) {
            echo '<div class="repeater-row" style="padding:20px; border-bottom:1px solid #e2e8f0; position:relative; background:#fff; margin-bottom:5px;">';
            echo '<span class="dashicons dashicons-no-alt remove-repeater-row" style="position:absolute; top:10px; right:10px; color:#ef4444; cursor:pointer;"></span>';
            foreach ($subfields as $sub) {
                $sub_val = $row_data[$sub['name']] ?? '';
                $sub_input_name = "{$input_name}[{$index}][{$sub['name']}]";
                echo '<div style="margin-bottom:12px;"><label style="font-size:11px; font-weight:700; display:block; margin-bottom:5px; color:#64748b;">' . esc_html($sub['label']) . '</label>';
                echo '<input type="text" name="' . esc_attr($sub_input_name) . '" value="' . esc_attr(is_array($sub_val) ? '' : $sub_val) . '" style="width:100%; border:1px solid #cbd5e1; border-radius:4px; height:34px;"></div>';
            }
            echo '</div>';
        }
        echo '</div><button type="button" class="button add-repeater-row" style="margin-top:15px; background:#6366f1; color:#fff; border:none; padding:6px 15px; border-radius:4px; cursor:pointer;">+ Add Row</button></div>';
    }

    private function module_wysiwyg($field, $val, $input_name)
    {
        $editor_id = 'cora_editor_' . str_replace(['[', ']'], '_', $field['name']);
        wp_editor($val, $editor_id, ['textarea_name' => $input_name, 'media_buttons' => true, 'textarea_rows' => 8]);
    }

    private function module_oembed($field, $val, $input_name)
    {
        echo '<div class="cora-oembed-wrap">';
        echo '<input type="url" name="' . $input_name . '" value="' . esc_attr($val) . '" placeholder="Paste Youtube link..." style="width:100%; height:40px; border:1px solid #ddd; border-radius:4px; margin-bottom:10px;">';
        if (!empty($val)) {
            $embed = wp_oembed_get($val);
            if ($embed)
                echo '<div class="cora-oembed-preview" style="max-width:450px; border-radius:8px; overflow:hidden; border:1px solid #eee;">' . $embed . '</div>';
        }
        echo '</div>';
    }

    private function module_gallery($field, $val, $input_name)
    {
        $urls = !empty($val) ? explode(',', $val) : [];
        echo '<div class="cora-media-upload-wrap" data-type="gallery">';
        echo '<div class="cora-gallery-preview" style="display:grid; grid-template-columns:repeat(auto-fill, 85px); gap:12px; margin-bottom:12px; min-height:85px; border:2px dashed #e2e8f0; padding:12px;">';
        foreach ($urls as $url) {
            if (!empty($url))
                echo '<div class="gallery-item" style="width:85px; height:85px; position:relative; border-radius:8px; overflow:hidden; border:1px solid #cbd5e1;"><img src="' . esc_url($url) . '" style="width:100%; height:100%; object-fit:cover;"><span class="dashicons dashicons-no-alt remove-gal-img" style="position:absolute; top:2px; right:2px; background:#fff; cursor:pointer; border-radius:50%;"></span></div>';
        }
        echo '</div><input type="hidden" name="' . $input_name . '" value="' . esc_attr($val) . '" class="cora-media-input"><button type="button" class="button cora-media-upload-btn">Manage Gallery</button></div>';
    }

    private function module_google_map($field, $val, $input_name)
    {
        echo '<div class="cora-map-wrap"><input type="text" name="' . $input_name . '" value="' . esc_attr($val) . '" class="cora-input cora-map-search" placeholder="Search location..."></div>';
    }

    private function module_page_link($field, $val, $input_name)
    {
        $pages = get_pages();
        echo '<select name="' . $input_name . '" style="width:100%; height:40px; border:1px solid #ddd; border-radius:4px;">';
        echo '<option value="">Select a Page...</option>';
        foreach ($pages as $p) {
            echo '<option value="' . get_permalink($p->ID) . '" ' . selected($val, get_permalink($p->ID), false) . '>' . esc_html($p->post_title) . '</option>';
        }
        echo '</select>';
    }

    private function module_taxonomy($field, $val, $input_name)
    {
        $taxs = get_taxonomies(['public' => true], 'objects');
        echo '<select name="' . $input_name . '" style="width:100%; height:40px; border:1px solid #ddd; border-radius:4px;">';
        foreach ($taxs as $tax) {
            $terms = get_terms(['taxonomy' => $tax->name, 'hide_empty' => false]);
            if (is_wp_error($terms))
                continue;
            echo '<optgroup label="' . esc_attr($tax->label) . '">';
            foreach ($terms as $t) {
                echo '<option value="' . $t->term_id . '" ' . selected($val, $t->term_id, false) . '>' . esc_html($t->name) . '</option>';
            }
            echo '</optgroup>';
        }
        echo '</select>';
    }

    private function module_user($field, $val, $input_name)
    {
        $users = get_users();
        echo '<select name="' . $input_name . '" style="width:100%; height:40px; border:1px solid #ddd; border-radius:4px;">';
        foreach ($users as $u) {
            echo '<option value="' . $u->ID . '" ' . selected($val, $u->ID, false) . '>' . esc_html($u->display_name) . '</option>';
        }
        echo '</select>';
    }

    private function module_boolean($field, $val, $input_name)
    {
        $checked = checked($val, '1', false);
        echo '<label style="display:flex; align-items:center; cursor:pointer; gap:10px;"><input type="hidden" name="' . $input_name . '" value="0"><input type="checkbox" name="' . $input_name . '" value="1" ' . $checked . ' style="width:18px; height:18px;"> <span style="font-weight:600;">' . ($val == '1' ? 'Enabled' : 'Disabled') . '</span></label>';
    }
     private function module_message($field)
    {
        echo '<div class="cora-ui-message" style="background:#f0f6fb; border-left:4px solid #2271b1; padding:12px; margin:5px 0;">';
        echo '<p style="margin:0; font-style:italic; color:#1d2327;">' . esc_html($field['placeholder'] ?? '') . '</p>';
        echo '</div>';
    }

    private function module_nested_group($field, $val, $input_name)
    {
        $subfields = json_decode($field['subfields_json'] ?? '[]', true) ?: [];
        $is_accordion = $field['type'] === 'accordion';

        echo '<div class="cora-layout-container cora-' . esc_attr($field['type']) . '" style="border:1px solid #ddd; border-radius:6px; background:#fff; overflow:hidden;">';
        if ($is_accordion) {
            echo '<div style="background:#f6f6f6; padding:10px; font-weight:bold; border-bottom:1px solid #ddd; cursor:default;">' . esc_html($field['label']) . '</div>';
        }
        echo '<div style="padding:20px;">';
        foreach ($subfields as $sub) {
            $sub_val = $val[$sub['name']] ?? '';
            $sub_input_name = "{$input_name}[{$sub['name']}]";
            echo '<div style="margin-bottom:15px;"><label style="display:block; font-size:12px; font-weight:bold; margin-bottom:5px;">' . esc_html($sub['label']) . '</label>';
            $this->render_single_field($sub, $sub_val, $sub_input_name);
            echo '</div>';
        }
        echo '</div></div>';
    }

    private function module_clone($field, $val, $input_name)
    {
        echo '<div class="cora-clone-placeholder" style="padding:15px; border:1px dashed #ccc; background:#f9f9f9; text-align:center; color:#888;">';
        echo '<span class="dashicons dashicons-admin-page" style="vertical-align:middle; margin-right:5px;"></span> Reference to: ' . esc_html($field['placeholder'] ?: 'Select Group');
        echo '</div>';
    }

    private function parse_choices($text)
    {
        $lines = explode("\n", str_replace("\r", "", $text));
        $items = [];
        foreach ($lines as $line) {
            if (strpos($line, ':') !== false) {
                list($k, $v) = explode(':', $line);
                $items[trim($k)] = trim($v);
            } else if (!empty(trim($line))) {
                $items[trim($line)] = trim($line);
            }
        }
        return $items;
    }
}