<?php
namespace Cora_Builder\Core;

if (!defined('ABSPATH'))
    exit;

/**
 * Base Logic: Fetches and filters Cora Fields from the database.
 * Resolved: Uses numerically indexed arrays for Elementor OptGroups to prevent Select2 failures.
 */
abstract class Cora_Dynamic_Tag_Base extends \Elementor\Core\DynamicTags\Tag
{

    public function get_group()
    {
        return 'cora-builder-group';
    }

    /**
     * Fetches registered fields and formats them for Elementor's Select2 groups.
     * FIX: Returns an indexed array instead of an associative array.
     */
    protected function get_cora_fields_by_type($filter_types = [])
    {
        $groups = get_option('cora_field_groups', []);
        $formatted_options = [];

        if (!empty($groups) && is_array($groups)) {
            foreach ($groups as $group) {
                $group_options = [];
                if (!empty($group['fields']) && is_array($group['fields'])) {
                    foreach ($group['fields'] as $field) {
                        $stored_type = strtolower($field['type'] ?? '');
                        if (empty($filter_types) || in_array($stored_type, $filter_types)) {
                            $group_options[$field['name']] = $field['label'] . ' (' . $field['name'] . ')';
                        }
                    }
                }

                if (!empty($group_options)) {
                    // Using [] instead of [$group_id] ensures a numerically indexed array.
                    $formatted_options[] = [
                        'label' => strtoupper($group['title']),
                        'options' => $group_options,
                    ];
                }
            }
        }

        // If no fields are found, provide a debug hint in the dropdown
        if (empty($formatted_options)) {
            return [
                [
                    'label' => __('System Debug', 'cora-builder'),
                    'options' => ['none' => __('No matching fields found in Studio', 'cora-builder')]
                ]
            ];
        }

        return $formatted_options;
    }

    /**
     * Retrieves the actual data value for the tag.
     * FIX: Support for both Post Meta (CPTs) and Options Pages.
     */
    protected function get_field_value()
    {
        $slug = $this->get_settings('field_slug');
        if (empty($slug))
            return '';

        // 1. Check current Post Meta (Custom Post Types)
        $meta = get_post_meta(get_the_ID(), '_cora_meta_data', true) ?: [];
        if (isset($meta[$slug]))
            return $meta[$slug];

        // 2. Fallback: Search global Options Pages
        $groups = get_option('cora_field_groups', []);
        foreach ($groups as $group) {
            if (isset($group['rule_type']) && $group['rule_type'] === 'options_page') {
                $options_data = get_option($group['location'] . '_data', []);
                if (isset($options_data[$slug]))
                    return $options_data[$slug];
            }
        }
        return '';
    }
}

/**
 * TAG: TEXT & HTML
 */
class Cora_Dynamic_Text_Tag extends Cora_Dynamic_Tag_Base
{
    public function get_name()
    {
        return 'cora-text-tag';
    }
    public function get_title()
    {
        return __('Cora Field (Text/HTML)', 'cora-builder');
    }
    public function get_categories()
    {
        return [\Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY];
    }

    protected function register_controls()
    {
        $this->add_control('field_slug', [
            'label' => __('Field Slug', 'cora-builder'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'placeholder' => __('e.g., project_title', 'cora-builder'),
            'description' => __('Enter the unique Unit Slug defined in the Field Studio.', 'cora-builder'),
            'label_block' => true,
        ]);
    }

    public function render()
    {
        echo wp_kses_post($this->get_field_value());
    }
}

/**
 * TAG: IMAGES
 */
class Cora_Dynamic_Image_Tag extends Cora_Dynamic_Tag_Base
{
    public function get_name()
    {
        return 'cora-image-tag';
    }
    public function get_title()
    {
        return __('Cora Field (Image)', 'cora-builder');
    }
    public function get_categories()
    {
        return [\Elementor\Modules\DynamicTags\Module::IMAGE_CATEGORY];
    }

    protected function register_controls()
    {
        $this->add_control('key', [
            'label' => __('Select Image Field', 'cora-builder'),
            'type' => \Elementor\Controls_Manager::SELECT2,
            'groups' => $this->get_cora_fields_by_type(['image']),
            'label_block' => true,
        ]);
    }

    public function get_value(array $options = [])
    {
        return ['id' => 0, 'url' => $this->get_field_value()];
    }
}

/**
 * TAG: URL & VIDEO
 */
class Cora_Dynamic_URL_Tag extends Cora_Dynamic_Tag_Base
{
    public function get_name()
    {
        return 'cora-url-tag';
    }
    public function get_title()
    {
        return __('Cora Field (URL/Video)', 'cora-builder');
    }
    public function get_categories()
    {
        return [\Elementor\Modules\DynamicTags\Module::URL_CATEGORY];
    }

    protected function register_controls()
    {
        $this->add_control('key', [
            'label' => __('Select Source', 'cora-builder'),
            'type' => \Elementor\Controls_Manager::SELECT2,
            'groups' => $this->get_cora_fields_by_type(['url', 'file', 'oembed', 'page_link']),
            'label_block' => true,
        ]);
    }

    public function render()
    {
        echo esc_url($this->get_field_value());
    }
}

/**
 * TAG: NUMERIC
 */
class Cora_Dynamic_Number_Tag extends Cora_Dynamic_Tag_Base
{
    public function get_name()
    {
        return 'cora-number-tag';
    }
    public function get_title()
    {
        return __('Cora Field (Number)', 'cora-builder');
    }
    public function get_categories()
    {
        return [\Elementor\Modules\DynamicTags\Module::NUMBER_CATEGORY];
    }

    protected function register_controls()
    {
        $this->add_control('key', [
            'label' => __('Select Numeric Field', 'cora-builder'),
            'type' => \Elementor\Controls_Manager::SELECT2,
            'groups' => $this->get_cora_fields_by_type(['number', 'range']),
            'label_block' => true,
        ]);
    }

    public function render()
    {
        echo floatval($this->get_field_value());
    }
}

/**
 * TAG: GALLERY
 */
class Cora_Dynamic_Gallery_Tag extends Cora_Dynamic_Tag_Base
{
    public function get_name()
    {
        return 'cora-gallery-tag';
    }
    public function get_title()
    {
        return __('Cora Gallery', 'cora-builder');
    }
    public function get_categories()
    {
        return [\Elementor\Modules\DynamicTags\Module::GALLERY_CATEGORY];
    }

    protected function register_controls()
    {
        $this->add_control('key', [
            'label' => __('Select Gallery', 'cora-builder'),
            'type' => \Elementor\Controls_Manager::SELECT2,
            'groups' => $this->get_cora_fields_by_type(['gallery']),
            'label_block' => true,
        ]);
    }

    public function get_value(array $options = [])
    {
        $csv = $this->get_field_value();
        $urls = !empty($csv) ? explode(',', $csv) : [];
        $gallery = [];
        foreach ($urls as $url) {
            if (!empty($url)) {
                $gallery[] = ['id' => 0, 'url' => trim($url)];
            }
        }
        return $gallery;
    }
}