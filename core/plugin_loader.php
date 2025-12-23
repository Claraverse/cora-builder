<?php
namespace Cora_Builder\Core;

if (!defined('ABSPATH'))
    exit;

class Plugin_Loader
{
    private static $_instance = null;

    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct()
    {
        $this->load_core_managers();
        $this->init_elementor_hooks();
    }

    /**
     * Optimized Manager Loading
     * Grouping these ensures all admin_init and admin_menu hooks 
     * are caught globally.
     */
    private function load_core_managers()
    {
        $managers = [
            'admin_manager' => 'Admin_Manager',
            'settings_manager' => 'Settings_Manager',
            'cpt_manager' => 'CPT_Manager',
            'taxonomy_manager' => 'Taxonomy_Manager',
            'options_manager' => 'Options_Manager',
            'editor_customizer' => 'Editor_Customizer',
            'field_renderer' => 'Field_Renderer',
            'field_group_manager' => 'Field_Group_Manager'
        ];

        foreach ($managers as $file => $class) {
            $path = CORA_BUILDER_PATH . "core/{$file}.php";
            if (file_exists($path)) {
                require_once $path;
                $full_class = "\\Cora_Builder\\Core\\{$class}";
                if (class_exists($full_class)) {
                    new $full_class();
                }
            }
        }
    }

    private function init_elementor_hooks()
    {
        add_action('elementor/widgets/register', [$this, 'register_components']);
        add_action('elementor/elements/categories_registered', [$this, 'register_categories']);
        add_action('elementor/frontend/after_enqueue_styles', [$this, 'enqueue_global_styles']);
        add_action('elementor/dynamic_tags/register', [$this, 'register_cora_dynamic_tags']);
    }

    /**
     * Pro Category Reordering
     * Moves 'Cora Builder' to the top of the list for a better UX.
     */
    public function register_categories($elements_manager)
    {
        $elements_manager->add_category('cora_widgets', [
            'title' => 'Cora Builder',
            'icon' => 'fa fa-plug',
        ]);

        try {
            $reflection = new \ReflectionClass($elements_manager);
            $property = $reflection->getProperty('categories');
            $property->setAccessible(true);
            $categories = $property->getValue($elements_manager);

            if (isset($categories['cora_widgets'])) {
                $cora_cat = ['cora_widgets' => $categories['cora_widgets']];
                unset($categories['cora_widgets']);

                // Optimized: Insert directly after 'basic' section
                $pos = array_search('basic', array_keys($categories)) + 1;
                $categories = array_merge(
                    array_slice($categories, 0, $pos),
                    $cora_cat,
                    array_slice($categories, $pos)
                );

                $property->setValue($elements_manager, $categories);
            }
        } catch (\Exception $e) {
            error_log('Cora Builder: Category reorder failed.');
        }
    }

    public function register_cora_dynamic_tags($dynamic_tags)
    {
        // 1. Register the Cora Group
        \Elementor\Plugin::$instance->dynamic_tags->register_group('cora-builder-group', [
            'title' => __('Cora Studio', 'cora-builder')
        ]);

        require_once CORA_BUILDER_PATH . 'core/dynamic_tags.php';

        // 2. Register every specialized tag class
        $dynamic_tags->register(new \Cora_Builder\Core\Cora_Dynamic_Text_Tag());
        $dynamic_tags->register(new \Cora_Builder\Core\Cora_Dynamic_Image_Tag());
        $dynamic_tags->register(new \Cora_Builder\Core\Cora_Dynamic_URL_Tag());
        $dynamic_tags->register(new \Cora_Builder\Core\Cora_Dynamic_Number_Tag());
        $dynamic_tags->register( new \Cora_Builder\Core\Cora_Dynamic_Gallery_Tag() );
    }
    public function register_components($widgets_manager)
    {
        require_once CORA_BUILDER_PATH . 'core/base_widget.php';

        $widgets = [
            'dual_heading',
            'blog_hero',
            'post_grid',
            'cora_card',
            'cora_advance_heading',
            'cora_blog_card',
            'cora_solution_card',
            'cora_category_card',
            'cora_pill_badge',
            'cora_industry_card',
            'cora_solution_cluster',
            'cora_team_card',
            'cora_pricing_card',
            'cora_cta_section',
            'cora_newsletter'
        ];

        foreach ($widgets as $component) {
            $file_path = CORA_BUILDER_PATH . "components/widgets/{$component}/{$component}.php";
            if (file_exists($file_path)) {
                require_once $file_path;
                $class_name = str_replace(' ', '_', ucwords(str_replace('_', ' ', $component)));
                $class_full = "\\Cora_Builder\\Components\\{$class_name}";

                if (class_exists($class_full)) {
                    $widgets_manager->register(new $class_full());
                }
            }
        }
    }

    public function enqueue_global_styles()
    {
        wp_enqueue_style('cora-global-vars', CORA_BUILDER_URL . 'assets/css/globals.css', [], CORA_BUILDER_VERSION);
    }
}