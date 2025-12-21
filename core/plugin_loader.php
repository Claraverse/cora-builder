<?php
namespace Cora_Builder\Core;

if (!defined('ABSPATH')) {
    exit;
}

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
        // 1. Register Widgets
        add_action('elementor/widgets/register', [$this, 'register_components']);

        // 2. Register Custom Category (NEW)
        add_action('elementor/elements/categories_registered', [$this, 'register_categories']);

        // 3. Enqueue Global Design System
        add_action('elementor/frontend/after_enqueue_styles', [$this, 'enqueue_global_styles']);
    }

    /**
     * Register the dedicated Cora Builder section
     */
    /**
     * Register AND Reorder the Cora Builder section
     */
    public function register_categories($elements_manager)
    {
        // 1. Add the category normally (it lands at the bottom)
        $elements_manager->add_category(
            'cora_widgets',
            [
                'title' => 'Cora Builder',
                'icon' => 'fa fa-plug',
            ]
        );

        // 2. The "Hack" to move it up the list
        try {
            // Access the private $_categories property in Elementor
            $reflection = new \ReflectionClass($elements_manager);
            $property = $reflection->getProperty('categories');
            $property->setAccessible(true);

            $categories = $property->getValue($elements_manager);

            // Extract our category
            $cora_cat = $categories['cora_widgets'];
            unset($categories['cora_widgets']);

            // Rebuild the array with ours in the 3rd spot (After 'basic')
            $new_categories = [];
            $inserted = false;

            foreach ($categories as $key => $value) {
                $new_categories[$key] = $value;

                // We insert immediately after the 'basic' category
                if ('basic' === $key) {
                    $new_categories['cora_widgets'] = $cora_cat;
                    $inserted = true;
                }
            }

            // Safety net: If 'basic' wasn't found, put it at the end
            if (!$inserted) {
                $new_categories['cora_widgets'] = $cora_cat;
            }

            // Save the new order back to Elementor
            $property->setValue($elements_manager, $new_categories);

        } catch (\Exception $e) {
            // If the hack fails, it just stays at the bottom. No crash.
            error_log('Cora Builder: Failed to reorder categories.');
        }
    }

    public function enqueue_global_styles()
    {
        wp_enqueue_style(
            'cora-global-vars',
            CORA_BUILDER_URL . 'assets/css/globals.css',
            [],
            CORA_BUILDER_VERSION
        );
    }

    public function register_components($widgets_manager)
    {
        require_once CORA_BUILDER_PATH . 'core/base_widget.php';

        $components = [
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
            'cora_cta_section','cora_newsletter'
        ];

        foreach ($components as $component) {
            $file_path = CORA_BUILDER_PATH . "components/{$component}/{$component}.php";

            if (file_exists($file_path)) {
                require_once $file_path;

                $class_name = str_replace('_', ' ', $component);
                $class_name = ucwords($class_name);
                $class_name = str_replace(' ', '_', $class_name);
                $class_full = "\\Cora_Builder\\Components\\{$class_name}";

                if (class_exists($class_full)) {
                    $widgets_manager->register(new $class_full());
                }
            }
        }
    }
}