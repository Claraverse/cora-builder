<?php
namespace Cora_Builder\Core;

if ( ! defined( 'ABSPATH' ) ) exit;

class Options_Manager {
    private $option_name = 'cora_options_pages';

    public function __construct() {
        // 1. Register the Sidebar Menus
        add_action('admin_menu', [$this, 'register_custom_options_pages'], 99);
        
        // 2. IMPORTANT: Register the settings groups for each page
        add_action('admin_init', [$this, 'init_settings_groups']);
        
        // 3. Handle Builder Logic
        add_action('admin_post_cora_save_options_page', [$this, 'handle_save_page']);
        add_action('admin_post_cora_delete_options_page', [$this, 'handle_delete_page']);
    }

    /**
     * Security Initialization: This prevents the blank screen error.
     */
    public function init_settings_groups() {
        $pages = get_option($this->option_name, []);
        foreach ($pages as $slug => $data) {
            // Register a unique setting group for every created page
            register_setting($slug . '_group', $slug . '_data');
        }
    }

    /**
     * Sidebar Menu Registration
     */
    public function register_custom_options_pages() {
        $pages = get_option($this->option_name, []);
        foreach ($pages as $slug => $data) {
            add_menu_page(
                $data['title'],
                $data['title'],
                'manage_options',
                $slug,
                [$this, 'render_options_content'],
                $data['icon'] ?: 'dashicons-admin-generic',
                60
            );
        }
    }

    /**
     * Page Content: Updated with Settings API requirements
     */
    public function render_options_content() {
        $slug = isset($_GET['page']) ? sanitize_text_field($_GET['page']) : '';
        $pages = get_option($this->option_name, []);
        $current_page = $pages[$slug] ?? null;

        if (!$current_page) {
            echo "Options page configuration not found.";
            return;
        }
        ?>
        <div class="cora-admin-wrapper">
            <div class="cora-header">
                <div class="cora-logo">
                    <h1><?php echo esc_html($current_page['title']); ?></h1>
                </div>
                <div class="cora-actions">
                    <button type="submit" form="cora-options-form" class="cora-btn cora-btn-primary">Save Settings</button>
                </div>
            </div>
            
            <div class="cora-card">
                <form id="cora-options-form" method="post" action="options.php">
                    <?php
                    // Outputs hidden security fields and tells WP which group to save
                    settings_fields($slug . '_group');
                    
                    // Displays the actual fields attached to this slug
                    do_settings_sections($slug);
                    
                    // Displayed if no field groups are yet mapped via the Field Group Manager
                    echo "<div class='cora-empty-state' style='padding: 60px 20px; text-align: center;'>";
                    echo "<span class='dashicons dashicons-layout' style='font-size:48px; width:48px; height:48px; color:#D5D7DA; margin-bottom:15px;'></span>";
                    echo "<h3>Empty Options Page</h3>";
                    echo "<p>Go to <strong>Field Groups</strong> to create fields and attach them to this page.</p>";
                    echo "</div>";
                    ?>
                </form>
            </div>
        </div>
        <?php
    }

    /**
     * The Builder UI (Keep your existing code here, just add Delete support)
     */
    public function render_builder_ui() {
        $pages = get_option($this->option_name, []);
        ?>
        <div class="cora-admin-wrapper">
            <div class="cora-header">
                <div class="cora-logo"><h1>Options Page Builder <span class="cora-version">PRO</span></h1></div>
            </div>

            <div class="cora-settings-grid" style="grid-template-columns: 1.2fr 1.8fr;">
                <div class="cora-card">
                    <h3>Create Options Page</h3>
                    <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                        <input type="hidden" name="action" value="cora_save_options_page">
                        <?php wp_nonce_field('cora_options_nonce', 'cora_nonce'); ?>
                        
                        <div class="cora-control-group">
                            <label>Menu Title</label>
                            <input type="text" name="page_title" id="page_title" class="cora-input" required>
                        </div>
                        
                        <div class="cora-control-group">
                            <label>Menu Slug</label>
                            <input type="text" name="page_slug" id="page_slug" class="cora-input" required>
                        </div>

                        <div class="cora-control-group">
                            <label>Menu Icon</label>
                            <input type="text" name="page_icon" class="cora-input" value="dashicons-admin-generic">
                        </div>

                        <button type="submit" class="cora-btn cora-btn-primary" style="width:100%; margin-top:20px;">Deploy Options Page</button>
                    </form>
                </div>

                <div class="cora-card">
                    <h3>Active Options Pages</h3>
                    <div class="tax-list-wrapper">
                        <?php if(empty($pages)): ?>
                             <p class="cora-empty-state">No options pages created yet.</p>
                        <?php else: ?>
                            <?php foreach ($pages as $slug => $data) : ?>
                                <div class="tax-engine-item" style="display:flex; justify-content:space-between; align-items:center;">
                                    <div class="tax-engine-info">
                                        <div class="tax-icon-box"><span class="dashicons <?php echo $data['icon']; ?>"></span></div>
                                        <div>
                                            <strong><?php echo esc_html($data['title']); ?></strong>
                                            <code><?php echo esc_html($slug); ?></code>
                                        </div>
                                    </div>
                                    <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                                        <input type="hidden" name="action" value="cora_delete_options_page">
                                        <input type="hidden" name="page_slug" value="<?php echo esc_attr($slug); ?>">
                                        <?php wp_nonce_field('cora_options_del_nonce', 'cora_nonce'); ?>
                                        <button type="submit" class="delete-btn" onclick="return confirm('Delete this page?')">
                                            <span class="dashicons dashicons-trash"></span>
                                        </button>
                                    </form>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <script>
        jQuery(document).ready(function($) {
            $('#page_title').on('keyup', function() {
                $('#page_slug').val($(this).val().toLowerCase().trim().replace(/\s+/g, '-').replace(/[^a-z0-9-]/g, ''));
            });
        });
        </script>
        <?php
    }

    public function handle_save_page() {
        if (!isset($_POST['cora_nonce']) || !wp_verify_nonce($_POST['cora_nonce'], 'cora_options_nonce')) wp_die('Failed');
        $slug = sanitize_title($_POST['page_slug']);
        $pages = get_option($this->option_name, []);
        
        $pages[$slug] = [
            'title' => sanitize_text_field($_POST['page_title']),
            'icon'  => sanitize_text_field($_POST['page_icon']),
            'position' => 60
        ];
        
        update_option($this->option_name, $pages);
        flush_rewrite_rules();
        wp_redirect(admin_url('admin.php?page=cora-options-builder'));
        exit;
    }

    public function handle_delete_page() {
        if (!isset($_POST['cora_nonce']) || !wp_verify_nonce($_POST['cora_nonce'], 'cora_options_del_nonce')) wp_die('Failed');
        $slug = sanitize_text_field($_POST['page_slug']);
        $pages = get_option($this->option_name, []);
        if(isset($pages[$slug])) {
            unset($pages[$slug]);
            update_option($this->option_name, $pages);
        }
        wp_redirect(admin_url('admin.php?page=cora-options-builder'));
        exit;
    }
}