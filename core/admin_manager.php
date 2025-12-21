<?php
namespace Cora_Builder\Core;

if (!defined('ABSPATH')) {
    exit;
}

class Admin_Manager
{

    public function __construct()
    {
        add_action('admin_menu', [$this, 'register_admin_menu']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
    }

    public function register_admin_menu()
    {
        // 1. Main Menu Item
        add_menu_page(
            'Cora Builder',
            'Cora Builder',
            'manage_options',
            'cora-builder',
            [$this, 'render_dashboard'],
            'dashicons-layout',
            2
        );

        // 2. Dashboard Submenu (Default)
        add_submenu_page(
            'cora-builder',
            'Dashboard',
            'Dashboard',
            'manage_options',
            'cora-builder',
            [$this, 'render_dashboard']
        );

        // 3. Settings Submenu (Corrected)
        add_submenu_page(
            'cora-builder',
            'Settings',
            'Settings',
            'manage_options',
            'cora-settings',
            [$this, 'route_settings_page'] // Calls the new Settings Manager
        );
    }

    /**
     * Renders the Main Dashboard
     */
    public function render_dashboard()
    {
        ?>
        <div class="cora-admin-wrapper">
            <div class="cora-header">
                <div class="cora-logo">
                    <h1>Cora Builder</h1>
                    <span class="cora-version">v1.0.0</span>
                </div>
                <div class="cora-actions">
                    <a href="#" class="cora-btn cora-btn-secondary">Documentation</a>
                    <a href="#" class="cora-btn cora-btn-primary">Go Pro</a>
                </div>
            </div>

            <div class="cora-hero">
                <h2>Welcome to your new Design Experience</h2>
                <p>Cora Builder empowers you to create stunning, Shopify-like experiences inside WordPress. Get started by
                    exploring your widgets.</p>
            </div>

            <div class="cora-grid">
                <div class="cora-card">
                    <div class="icon-wrap"><span class="dashicons dashicons-layout"></span></div>
                    <h3>Widget Library</h3>
                    <p>Browse the 15+ custom components enabled on your site.</p>
                    <a href="<?php echo admin_url('elementor'); ?>" class="cora-link">
                        Open Editor <span class="dashicons dashicons-arrow-right-alt2"></span>
                    </a>
                </div>

                <div class="cora-card">
                    <div class="icon-wrap"><span class="dashicons dashicons-admin-settings"></span></div>
                    <h3>Global Settings</h3>
                    <p>Configure colors, typography, and default behaviors.</p>
                    <a href="<?php echo admin_url('admin.php?page=cora-settings'); ?>" class="cora-link">
                        Manage Settings <span class="dashicons dashicons-arrow-right-alt2"></span>
                    </a>
                </div>

                <div class="cora-card">
                    <div class="icon-wrap"><span class="dashicons dashicons-book"></span></div>
                    <h3>Knowledge Base</h3>
                    <p>Learn how to use the dual headings, atomic cards, and more.</p>
                    <a href="#" class="cora-link">
                        View Tutorials <span class="dashicons dashicons-arrow-right-alt2"></span>
                    </a>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Route to the Settings Manager
     */
    public function route_settings_page()
    {
        // Ensure the class exists before calling it to prevent fatal errors
        if (class_exists('\Cora_Builder\Core\Settings_Manager')) {
            $settings = new \Cora_Builder\Core\Settings_Manager();
            $settings->render_settings_page();
        } else {
            echo '<div class="notice notice-error"><p>Error: Settings Manager class not found.</p></div>';
        }
    }

    /**
     * Enqueue Styles
     */
    public function enqueue_admin_assets()
    {
        // Only load on "cora-" pages
        if (!isset($_GET['page']) || strpos($_GET['page'], 'cora') === false) {
            return;
        }

        wp_enqueue_style(
            'cora-admin-css',
            CORA_BUILDER_URL . 'assets/css/admin.css',
            [],
            time() // Forces cache refresh during dev
        );
    }
}