<?php
/**
 * Plugin Name: Cora Builder
 * Description: In-house Elementor component library.
 * Version: 1.0.0
 * Author: Cora Team
 * Author URI: https://elementor.com/?utm_source=wp-plugins&utm_campaign=author-uri&utm_medium=wp-dash
 * Requires PHP: 7.4
 * Requires at least: 6.6
 * Text Domain: elementor
 * Text Domain: cora_builder
 */

if (!defined('ABSPATH')) {
    exit;
}

define('CORA_BUILDER_VERSION', '1.0.0');
define('CORA_BUILDER_PATH', plugin_dir_path(__FILE__));
define('CORA_BUILDER_URL', plugin_dir_url(__FILE__));

// New Code (Waits for Elementor)
require_once CORA_BUILDER_PATH . 'core/plugin_loader.php';

add_action('plugins_loaded', function () {
    // Only load if Elementor is actually active
    if (did_action('elementor/loaded')) {
        \Cora_Builder\Core\Plugin_Loader::instance();
    }
});
// Register Custom Widget Category
add_action('elementor/elements/categories_registered', function ($elements_manager) {
    $elements_manager->add_category(
        'cora_elements',
        [
            'title' => esc_html__('Cora Studio Elements', 'cora-builder'),
            'icon' => 'eicon-font',
        ]
    );
});

class Cora_Editor_Customizer
{

    public function __construct()
    {
        // Enqueue styles and scripts for the Elementor Editor
        add_action('elementor/editor/after_enqueue_styles', [$this, 'enqueue_editor_styles']);
        add_action('elementor/editor/after_enqueue_scripts', [$this, 'enqueue_editor_scripts']);
    }

    public function enqueue_editor_styles()
    {
        wp_enqueue_style(
            'cora-editor-style',
            plugins_url('/assets/css/editor-style.css', __FILE__),
            [],
            '1.0.0'
        );
    }

    public function enqueue_editor_scripts()
    {
        wp_enqueue_script(
            'cora-editor-script',
            plugins_url('/assets/js/editor-script.js', __FILE__),
            ['jquery'],
            '1.0.0',
            true
        );
    }
}

new Cora_Editor_Customizer();