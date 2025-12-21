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

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'CORA_BUILDER_VERSION', '1.0.0' );
define( 'CORA_BUILDER_PATH', plugin_dir_path( __FILE__ ) );
define( 'CORA_BUILDER_URL', plugin_dir_url( __FILE__ ) );

// New Code (Waits for Elementor)
require_once CORA_BUILDER_PATH . 'core/plugin_loader.php';

add_action( 'plugins_loaded', function() {
    // Only load if Elementor is actually active
    if ( did_action( 'elementor/loaded' ) ) {
        \Cora_Builder\Core\Plugin_Loader::instance();
    }
});