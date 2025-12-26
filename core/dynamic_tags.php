<?php
namespace Cora_Builder\Core;

if (!defined('ABSPATH'))
    exit;

/**
 * Base Logic: Handles hybrid selection (Dropdown + Manual Slug).
 */
abstract class Cora_Dynamic_Tag_Base extends \Elementor\Core\DynamicTags\Tag
{
    public function get_group()
    {
        return 'cora-builder-group';
    }

    /**
     * Shared Control Logic
     * Tags will call this with their specific field filters.
     */
    protected function register_cora_controls($filter_types = [])
    {
        $this->add_control('manual_slug', [
            'label' => __(' Manual Slug', 'cora-builder'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'placeholder' => __('e.g., project_title', 'cora-builder'),
            'description' => __('Enter a slug manually to override the dropdown selection.', 'cora-builder'),
            'label_block' => true,
        ]);
        $this->add_control('key', [
            'label' => __('OR Select Field', 'cora-builder'),
            'type' => \Elementor\Controls_Manager::SELECT2,
            'groups' => $this->get_cora_fields_by_type($filter_types),
            'label_block' => true,
        ]);


    }

    /**
     * FIX: Returns a numerically indexed array for Elementor OptGroups.
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
                        // Force lowercase for comparison
                        $stored_type = strtolower($field['type'] ?? '');
                        if (empty($filter_types) || in_array($stored_type, $filter_types)) {
                            $group_options[$field['name']] = $field['label'] . ' (' . $field['name'] . ')';
                        }
                    }
                }

                if (!empty($group_options)) {
                    // Use a standard indexed array for Elementor groups
                    $formatted_options[] = [
                        'label' => strtoupper($group['title']),
                        'options' => $group_options,
                    ];
                }
            }
        }
        return $formatted_options;
    }

    /**
     * Retrieves the value, prioritizing the manual slug.
     */
    protected function get_field_value()
    {
        // Prioritize manual slug input
        $slug = $this->get_settings('manual_slug');

        // If manual slug is empty, use the dropdown selection
        if (empty($slug)) {
            $slug = $this->get_settings('key');
        }

        if (empty($slug) || $slug === 'none')
            return '';

        // 1. Check current Post/Page Meta (for Custom Post Types)
        $meta = get_post_meta(get_the_ID(), '_cora_meta_data', true) ?: [];
        if (isset($meta[$slug])) {
            return $meta[$slug];
        }

        // 2. Fallback: Search through all global Options Page data
        $groups = get_option('cora_field_groups', []);
        foreach ($groups as $group) {
            if (isset($group['rule_type']) && $group['rule_type'] === 'options_page') {
                $options_data = get_option($group['location'] . '_data', []);
                if (isset($options_data[$slug])) {
                    return $options_data[$slug];
                }
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
        $this->register_cora_controls(['text', 'textarea', 'wysiwyg', 'number', 'select', 'radio', 'button_group', 'user', 'taxonomy', 'page_link']);
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
        $this->register_cora_controls(['image']);
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
        $this->register_cora_controls(['url', 'file', 'oembed', 'page_link']);
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
        $this->register_cora_controls(['number', 'range']);
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
        $this->register_cora_controls(['gallery']);
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