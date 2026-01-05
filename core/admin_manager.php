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
        add_action('admin_menu', [$this, 'add_cora_dashboard']);
        add_action('wp_ajax_toggle_cora_widget', [$this, 'handle_widget_toggle']);
    }

    public function add_cora_dashboard()
    {
        add_menu_page(
            'Cora Builder',
            'Cora Builder',
            'manage_options',
            'cora-builder',
            [$this, 'render_dashboard'],
            'dashicons-admin-generic',
            2
        );
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


        // 4. Post Types Submenu (NEW)
        add_submenu_page(
            'cora-builder',
            'Post Types',
            'Post Types',
            'manage_options',
            'cora-cpt',
            [$this, 'route_cpt_page']
        );



        // Inside register_admin_menu()
        add_submenu_page(
            'cora-builder',
            'Taxonomies',
            'Taxonomies',
            'manage_options',
            'cora-tax',
            [$this, 'route_tax_page']
        );
        // 5. Options Pages Submenu (NEW FEATURE)
        add_submenu_page(
            'cora-builder',
            'Options Pages',
            'Options Pages',
            'manage_options',
            'cora-options-builder',
            [$this, 'route_options_builder_page']
        );

        // Inside register_admin_menu()
        add_submenu_page(
            'cora-builder',
            'Field Groups',
            'Field Groups',
            'manage_options',
            'cora-fieldgroups',
            [$this, 'route_fieldgroups_page']
        );
        // 6. Modern Pages Submenu (NEW)
        add_submenu_page(
            'cora-builder',
            'All Pages',
            'Pages',
            'manage_options',
            'cora-pages',
            [$this, 'route_pages_dashboard']
        );
    }

    /**
     * Routing function for the new Pages Dashboard
     */
    public function route_pages_dashboard() {
        // Fetch pages to display
        $pages = get_posts([
            'post_type' => 'page',
            'posts_per_page' => -1,
            'post_status' => ['publish', 'draft', 'pending']
        ]);
        
        $this->render_pages_dashboard($pages);
    }

    /**
     * Renders the Main Dashboard
     */
    public function render_dashboard()
    {
        // Fetch widgets from our tiered folders
        $widgets = $this->get_all_registered_widgets();
        ?>
                <div class="cora-admin-wrapper">
                    <div class="cora-header">
                        <div class="cora-logo">
                            <h1>Cora Builder</h1> 
                            <span class="cora-version">v1.0.0</span>
                        </div>
                         <div class="cora-search-bar">
                        <i class="dashicons dashicons-search"></i>
                        <input type="text" id="cora-widget-search" placeholder="Search by name or category...">
                    </div>
                        <div class="cora-actions">
                            <a href="#" class="cora-btn cora-btn-secondary">Documentation</a>
                            <a href="#" class="cora-btn cora-btn-primary">Go Pro</a>
                        </div>
                    </div>

            
         

                <nav class="cora-filter-nav">
                    <button class="filter-btn active" data-filter="all">All Units</button>
                    <button class="filter-btn" data-filter="elements">Elements</button>
                    <button class="filter-btn" data-filter="components">Components</button>
                    <button class="filter-btn" data-filter="section">Sections</button>
                    <button class="filter-btn" data-filter="widgets">Widgets</button>
                </nav>

                <div class="cora-widget-grid" id="cora-grid">
                    <?php foreach ($widgets as $id => $data): ?>
                                <div class="cora-widget-card" 
                                     data-name="<?php echo esc_attr(strtolower($data['title'])); ?>" 
                                     data-tier="<?php echo esc_attr($data['tier']); ?>">
                    
                                    <div class="card-status">
                                        <span class="tier-badge"><?php echo esc_html($data['label']); ?></span>
                                        <label class="cora-switch">
                                            <input type="checkbox" class="widget-toggle" 
                                                   data-widget="<?php echo $id; ?>" 
                                                   <?php checked($data['active']); ?>>
                                            <span class="cora-slider"></span>
                                        </label>
                                    </div>

                                    <div class="card-content">
                                        <h3><?php echo esc_html($data['title']); ?></h3>
                                        <p><?php echo esc_html($data['tier']); ?> unit ready for canvas</p>
                                    </div>
                                </div>
                    <?php endforeach; ?>
                </div>
        

          
                </div>
        
                <?php
                $this->render_dashboard_styles();
                $this->render_dashboard_scripts();
    }
/**
 * Saves the widget status (enabled/disabled) to WordPress options via AJAX.
 */
public function handle_widget_toggle() {
    check_ajax_referer('cora_admin_nonce', 'nonce');

    $widget_id = sanitize_text_field($_POST['widget_id']);
    $is_active = $_POST['is_active'] === 'true';

    $disabled_widgets = get_option('cora_disabled_widgets', []);

    if ($is_active) {
        $disabled_widgets = array_diff($disabled_widgets, [$widget_id]);
    } else {
        if (!in_array($widget_id, $disabled_widgets)) {
            $disabled_widgets[] = $widget_id;
        }
    }

    update_option('cora_disabled_widgets', array_values($disabled_widgets));
    wp_send_json_success(['message' => 'Widget status updated']);
}
    private function render_dashboard_styles() {
    ?>
    <style>
        .cora-admin-dashboard { margin-top: 30px; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif; }
        .cora-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .cora-title-section h1 { font-size: 28px; font-weight: 800; color: #1e293b; margin: 0; }
        
        /* Search Bar */
        .cora-search-bar { position: relative; background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 5px 15px; width: 350px; display: flex; align-items: center; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
        .cora-search-bar input { border: none; box-shadow: none; width: 100%; font-size: 14px; padding: 8px; }
        .cora-search-bar input:focus { outline: none; border: none; box-shadow: none; }
        
        /* Filters */
        .cora-filter-nav { margin-bottom: 30px; display: flex; gap: 10px; }
        .filter-btn { background: #fff; border: 1px solid #e2e8f0; padding: 8px 20px; border-radius: 30px; cursor: pointer; font-weight: 600; color: #64748b; transition: 0.3s; }
        .filter-btn.active { background: #0f172a; color: #fff; border-color: #0f172a; }

        /* Grid & Cards */
        .cora-widget-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; }
        .cora-widget-card { background: #fff; border-radius: 16px; border: 1px solid #e2e8f0; padding: 24px; transition: 0.3s; position: relative; }
        .cora-widget-card:hover { border: 1px solid #101828; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); }
        
        .card-status { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .tier-badge { font-size: 11px; text-transform: uppercase; font-weight: 700; background: #f1f5f9; padding: 4px 10px; border-radius: 6px; color: #475569; }

        /* Custom Switch */
        .cora-switch { position: relative; display: inline-block; width: 44px; height: 24px; }
        .cora-switch input { opacity: 0; width: 0; height: 0; }
        .cora-slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #cbd5e1; transition: .4s; border-radius: 34px; }
        .cora-slider:before { position: absolute; content: ""; height: 18px; width: 18px; left: 3px; bottom: 3px; background-color: white; transition: .4s; border-radius: 50%; }
        input:checked + .cora-slider { background-color: #101828; }
        input:checked + .cora-slider:before { transform: translateX(20px); }
    </style>
    <?php
}
    private function render_dashboard_scripts()
    {
        ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('cora-widget-search');
            const filterBtns = document.querySelectorAll('.filter-btn');
            const cards = document.querySelectorAll('.cora-widget-card');

            function filterWidgets() {
                const searchTerm = searchInput.value.toLowerCase();
                const activeFilter = document.querySelector('.filter-btn.active').dataset.filter;

                cards.forEach(card => {
                    const name = card.dataset.name;
                    const tier = card.dataset.tier;

                    const matchesSearch = name.includes(searchTerm);
                    const matchesFilter = activeFilter === 'all' || tier === activeFilter;

                    if (matchesSearch && matchesFilter) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            }

        // Live Search
        searchInput.addEventListener('input', filterWidgets);

        // Filter Buttons
        filterBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                filterBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                filterWidgets();
            });
        });
    });
    </script>
    <?php
}
/**
 * Scans tiered directories to build a registry of all available Cora units.
 * This enables the many-to-many relationship management in the dashboard.
 * * @return array Metadata of all discovered widgets.
 */

/**
     * SaaS UI Renderer for Pages
     */
    private function render_pages_dashboard($pages) {
        ?>
        <div class="cora-notion-container cora-pages-studio">
            <?php include CORA_BUILDER_PATH . 'views/components/sidebar.php'; ?>
            
            <main class="cora-main-workspace">
                <header class="workspace-header">
                    <div class="header-info">
                        <h1>Pages Studio <span class="mode-pill">LIVE</span></h1>
                        <p>Manage your site architecture with surgical precision.</p>
                    </div>
                    <div class="header-actions">
                        <a href="<?php echo admin_url('post-new.php?post_type=page'); ?>" class="cora-btn-bw primary">Add New Page</a>
                    </div>
                </header>

                <div class="cora-grid-layout pages-grid">
                    <?php if (empty($pages)) : ?>
                        <p class="empty-state-pro">No pages found in your workspace.</p>
                    <?php else : ?>
                        <?php foreach ($pages as $page) : 
                            $edit_link = get_edit_post_link($page->ID);
                            $preview_link = get_permalink($page->ID);
                            $status = get_post_status($page->ID);
                        ?>
                            <div class="page-item-card">
                                <div class="card-status status-<?php echo $status; ?>"><?php echo ucfirst($status); ?></div>
                                <div class="card-main">
                                    <div class="page-icon-box">
                                        <span class="dashicons dashicons-admin-page"></span>
                                    </div>
                                    <div class="page-info">
                                        <h3><?php echo esc_html($page->post_title); ?></h3>
                                        <code>/<?php echo esc_html($page->post_name); ?></code>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <a href="<?php echo $edit_link; ?>" class="action-btn">Edit Design</a>
                                    <a href="<?php echo $preview_link; ?>" target="_blank" class="preview-btn">
                                        <span class="dashicons dashicons-external"></span>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </main>
        </div>
        <?php
    }
private function get_all_registered_widgets() {
    $widgets = [];
    
    // Define the tiers and their human-readable labels for the UI
    $tiers = [
        'elements'   => 'Element',
        'components' => 'Component',
        'widgets'    => 'Widget',
        'section'    => 'Section'
    ];

    // Fetch the list of disabled widgets from the database to determine "active" status
    $disabled_widgets = get_option('cora_disabled_widgets', []);

    foreach ($tiers as $folder => $label) {
        $path = CORA_BUILDER_PATH . "components/{$folder}/";

        if (!is_dir($path)) {
            continue;
        }

        // Scan the directory for subfolders (each subfolder is a widget unit)
        $items = array_diff(scandir($path), ['..', '.']);

        foreach ($items as $item) {
            $file_path = $path . "{$item}/{$item}.php";

            if (file_exists($file_path)) {
                // Generate a unique ID for the widget based on its tier and folder name
                $widget_id = "{$folder}_{$item}";
                
                // Format the title (e.g., cora_nexus_hero -> Cora Nexus Hero)
                $display_name = str_replace(['_', '-'], ' ', $item);
                $display_name = ucwords($display_name);

                $widgets[$widget_id] = [
                    'id'     => $widget_id,
                    'slug'   => $item,
                    'title'  => $display_name,
                    'tier'   => $folder,
                    'label'  => $label,
                    'active' => !in_array($widget_id, $disabled_widgets), // Toggle state for the UI
                    'path'   => $file_path
                ];
            }
        }
    }

    return $widgets;
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
    // Add this function
    public function route_tax_page()
    {
        if (!class_exists('\Cora_Builder\Core\Taxonomy_Manager')) {
            require_once CORA_BUILDER_PATH . 'core/taxonomy_manager.php';
        }
        $manager = new \Cora_Builder\Core\Taxonomy_Manager();
        $manager->render_taxonomy_page();
    }
    // Add this function
    public function route_fieldgroups_page()
    {
        if (!class_exists('\Cora_Builder\Core\Field_Group_Manager')) {
            require_once CORA_BUILDER_PATH . 'core/field_group_manager.php';
        }
        $manager = new \Cora_Builder\Core\Field_Group_Manager();
        $manager->render_field_group_page();
    }
    // Add this function
    public function route_options_builder_page()
    {
        if (!class_exists('\Cora_Builder\Core\Options_Manager')) {
            require_once CORA_BUILDER_PATH . 'core/options_manager.php';
        }
        $manager = new \Cora_Builder\Core\Options_Manager();
        $manager->render_builder_ui();
    }
    // Add this routing function
    public function route_cpt_page()
    {
        if (!class_exists('\Cora_Builder\Core\CPT_Manager')) {
            require_once CORA_BUILDER_PATH . 'core/cpt_manager.php';
        }
        $cpt_manager = new \Cora_Builder\Core\CPT_Manager();
        $cpt_manager->render_cpt_page();
    }
    /**
     * Enqueue Styles
     */
    public function enqueue_admin_assets()
    {
        // Only load for Cora Builder pages
        if (!isset($_GET['page']) || strpos($_GET['page'], 'cora') === false)
            return;

        // Load base admin styles
        wp_enqueue_style('cora-admin-css', CORA_BUILDER_URL . 'assets/css/admin.css', [], time());

        // Load Post Type specific CSS (NEW)
        if ($_GET['page'] === 'cora-cpt') {
            wp_enqueue_style('cora-cpt-pro', CORA_BUILDER_URL . 'assets/css/cora-cpt.css', ['cora-admin-css'], time());
        }
        // Load Post Type specific CSS (NEW)
        if ($_GET['page'] === 'cora-tax') {
            wp_enqueue_style('cora-tax-pro', CORA_BUILDER_URL . 'assets/css/cora-tax.css', ['cora-admin-css'], time());
        }
        // Load Post Type specific CSS (NEW)
        if ($_GET['page'] === 'cora-options-builder') {
            wp_enqueue_style('cora-options-builder-pro', CORA_BUILDER_URL . 'assets/css/cora-option.css', ['cora-admin-css'], time());
        }

if ($_GET['page'] === 'cora-pages') {
            wp_enqueue_style('cora-pages-studio-css', CORA_BUILDER_URL . 'assets/css/pages-studio.css', ['cora-admin-css'], time());
        }

        // Load dedicated Field Groups Studio CSS
        if ($_GET['page'] === 'cora-fieldgroups') {
            wp_enqueue_style('cora-field-studio-css', CORA_BUILDER_URL . 'assets/css/field-groups.css', ['cora-admin-css'], time());
        }
    }
}