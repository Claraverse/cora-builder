<?php
namespace Cora_Builder\Core;

if (!defined('ABSPATH'))
    exit;

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
                if (!empty($group['fields'])) {
                    foreach ($group['fields'] as $field) {
                        $stored_type = strtolower($field['type'] ?? '');
                        if (empty($filter_types) || in_array($stored_type, $filter_types)) {
                            $group_options[$field['name']] = $field['label'];
                        }
                    }
                }
                if (!empty($group_options)) {
                    $options[$group_id] = ['label' => strtoupper($group['title']), 'options' => $group_options];
                }
            }
        }
        return $options;
    }
}

class Cora_Dynamic_Text_Tag extends Cora_Dynamic_Tag_Base
{
    public function get_name()
    {
        return 'cora-text-tag';
    }
    public function get_title()
    {
        return __('Cora Field (Text)', 'cora-builder');
    }
    public function get_categories()
    {
        return [\Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY];
    }
    protected function register_controls()
    {
        $this->add_control('key', ['label' => __('Select Field', 'cora-builder'), 'type' => \Elementor\Controls_Manager::SELECT2, 'groups' => $this->get_cora_fields_by_type(['text', 'textarea', 'email'])]);
    }
    public function render()
    {
        $values = get_post_meta(get_the_ID(), '_cora_meta_data', true) ?: [];
        echo wp_kses_post($values[$this->get_settings('key')] ?? '');
    }
}

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
        $this->add_control('key', ['label' => __('Select Field', 'cora-builder'), 'type' => \Elementor\Controls_Manager::SELECT2, 'groups' => $this->get_cora_fields_by_type(['image'])]);
    }
    public function get_value(array $options = [])
    {
        $values = get_post_meta(get_the_ID(), '_cora_meta_data', true) ?: [];
        return ['id' => 0, 'url' => $values[$this->get_settings('key')] ?? ''];
    }
}

class Cora_Dynamic_URL_Tag extends Cora_Dynamic_Tag_Base
{
    public function get_name()
    {
        return 'cora-url-tag';
    }
    public function get_title()
    {
        return __('Cora Field (URL/File)', 'cora-builder');
    }
    public function get_categories()
    {
        return [\Elementor\Modules\DynamicTags\Module::URL_CATEGORY];
    }
    protected function register_controls()
    {
        $this->add_control('key', ['label' => __('Select Field', 'cora-builder'), 'type' => \Elementor\Controls_Manager::SELECT2, 'groups' => $this->get_cora_fields_by_type(['url', 'file', 'link', 'image'])]);
    }
    public function render()
    {
        $values = get_post_meta(get_the_ID(), '_cora_meta_data', true) ?: [];
        echo esc_url($values[$this->get_settings('key')] ?? '');
    }
}


class Cora_Dynamic_Gallery_Tag extends Cora_Dynamic_Tag_Base
{
    public function get_name()
    {
        return 'cora-gallery-tag';
    }
    public function get_title()
    {
        return __('Cora Gallery (PRO)', 'cora-builder');
    }
    public function get_categories()
    {
        return [\Elementor\Modules\DynamicTags\Module::GALLERY_CATEGORY];
    }
    protected function register_controls()
    {
        $this->add_control('key', ['label' => __('Select Gallery', 'cora-builder'), 'type' => \Elementor\Controls_Manager::SELECT2, 'groups' => $this->get_cora_fields_by_type(['gallery'])]);
    }
    public function get_value(array $options = [])
    {
        $values = get_post_meta(get_the_ID(), '_cora_meta_data', true) ?: [];
        $csv = $values[$this->get_settings('key')] ?? '';
        $urls = !empty($csv) ? explode(',', $csv) : [];
        $gallery = [];
        foreach ($urls as $url) {
            if (!empty($url))
                $gallery[] = ['id' => 0, 'url' => $url];
        }
        return $gallery;
    }
}