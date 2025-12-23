<?php
namespace Cora_Builder\Core;

if (!defined('ABSPATH'))
    exit;

/**
 * Base Logic: Fetches and filters Cora Fields from the database.
 * This class provides the shared utility used by all specific tag classes.
 */
abstract class Cora_Dynamic_Tag_Base extends \Elementor\Core\DynamicTags\Tag
{
    public function get_group()
    {
        return 'cora-builder-group';
    }

    protected function get_cora_fields_by_type($filter_types = [])
    {
        $groups = get_option('cora_field_groups', []);
        $options = [];

        if (!empty($groups) && is_array($groups)) {
            foreach ($groups as $group_id => $group) {
                $group_options = [];
                if (!empty($group['fields']) && is_array($group['fields'])) {
                    foreach ($group['fields'] as $field) {
                        // FIX: Ensure case-insensitive matching for stored types
                        $stored_type = strtolower($field['type'] ?? '');
                        if (empty($filter_types) || in_array($stored_type, $filter_types)) {
                            $group_options[$field['name']] = $field['label'];
                        }
                    }
                }
                if (!empty($group_options)) {
                    $options[$group_id] = [
                        'label' => strtoupper($group['title']),
                        'options' => $group_options,
                    ];
                }
            }
        }
        return $options;
    }

    protected function get_field_value()
    {
        $key = $this->get_settings('key');
        if (empty($key))
            return '';
        $values = get_post_meta(get_the_ID(), '_cora_meta_data', true) ?: [];
        return $values[$key] ?? '';
    }
}

/**
 * TAG: TEXT & HTML
 * Covers: Text, Textarea, WYSIWYG, Number, Email, Password, Select, Radio, User, and Taxonomy.
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
        $this->add_control('key', [
            'label' => __('Select Field', 'cora-builder'),
            'type' => \Elementor\Controls_Manager::SELECT2,
            // Added 'user' and 'taxonomy' to the text filter list
            'groups' => $this->get_cora_fields_by_type(['text', 'textarea', 'wysiwyg', 'number', 'email', 'password', 'select', 'radio', 'button_group', 'user', 'taxonomy']),
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
 * Covers: Image.
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
 * Covers: URL, File, Link, oEmbed, and Page Link.
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
        return [\Elementor\Modules\DynamicTags\Module::URL_CATEGORY, \Elementor\Modules\DynamicTags\Module::POST_META_CATEGORY];
    }

    protected function register_controls()
    {
        $this->add_control('key', [
            'label' => __('Select Source', 'cora-builder'),
            'type' => \Elementor\Controls_Manager::SELECT2,
            // Added 'page_link' to the URL filter list
            'groups' => $this->get_cora_fields_by_type(['url', 'file', 'link', 'oembed', 'page_link']),
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
 * Covers: Number, Range, and Post Object (ID).
 */
class Cora_Dynamic_Number_Tag extends Cora_Dynamic_Tag_Base
{
    public function get_name()
    {
        return 'cora-number-tag';
    }
    public function get_title()
    {
        return __('Cora Field (Number/Range)', 'cora-builder');
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
            'groups' => $this->get_cora_fields_by_type(['number', 'range', 'post_object']),
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
 * Covers: Gallery.
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
                $gallery[] = ['id' => 0, 'url' => $url];
            }
        }
        return $gallery;
    }
}