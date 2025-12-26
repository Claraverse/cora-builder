<?php
namespace Cora_Builder\Core;

if (!defined('ABSPATH'))
    exit;

/**
 * Cora Options Manager: Modular SaaS Edition
 * Structured for easy maintenance with a uniform global studio UI.
 */
class Options_Manager
{
    private $option_name = 'cora_options_pages';

    public function __construct()
    {
        // Core Registration Hooks
        add_action('admin_menu', [$this, 'register_custom_options_pages'], 99);
        add_action('admin_init', [$this, 'init_settings_groups']);

        // Form & Logic Controllers
        add_action('admin_post_cora_save_options_page', [$this, 'handle_save_page']);
        add_action('admin_post_cora_delete_options_page', [$this, 'handle_delete_page']);
    }

    /**
     * MODULE: SETTINGS INITIALIZATION
     * Registers the database keys for every custom options page.
     */
    public function init_settings_groups()
    {
        $pages = get_option($this->option_name, []);
        foreach ($pages as $slug => $data) {
            register_setting($slug . '_group', $slug . '_data');
        }
    }

    /**
     * MODULE: SIDEBAR INJECTION
     * Injects the user-created pages into the WordPress sidebar.
     */
    public function register_custom_options_pages()
    {
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
     * MAIN VIEW: BUILDER UI DISPATCHER
     * The primary entry point for managing Options Pages.
     */
    public function render_builder_ui()
    {
        $pages = get_option($this->option_name, []);
        ?>
        <div class="cora-notion-container" id="cora-workspace-root">
            <?php $this->render_sidebar(); ?>
            <main class="cora-main-workspace">
                <?php $this->render_header(); ?>
                <div class="cora-grid-layout">
                    <?php $this->render_config_form(); ?>
                    <?php $this->render_registry($pages); ?>
                </div>
            </main>
        </div>
        <?php
        $this->render_scripts();
    }

    /**
     * PARTIAL: NAVIGATION SIDEBAR
     */
    private function render_sidebar()
    {
        ?>
                <?php include CORA_BUILDER_PATH . 'views/components/sidebar.php'; ?>

        <?php
    }

    /**
     * PARTIAL: WORKSPACE HEADER
     */
    private function render_header()
    {
        ?>
        <header class="workspace-header">
            <div class="header-info">
                <h1>Options Builder <span class="mode-pill" id="opt-mode-badge">PRO</span></h1>
                <p>Deploy custom sitewide settings panels to your admin sidebar.</p>
            </div>
            <div class="header-actions">
                <button type="button" id="reset-options-btn" class="cora-btn-bw secondary">New Page</button>
                <button type="submit" form="cora-options-builder-form" id="opt-submit-btn" class="cora-btn-bw primary">Deploy
                    Page</button>
            </div>
        </header>
        <?php
    }

    /**
     * PARTIAL: CONFIGURATION FORM
     */
    private function render_config_form()
    {
        ?>
        <section class="cora-card-pro config-area">
            <form id="cora-options-builder-form" method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                <input type="hidden" name="action" value="cora_save_options_page">
                <input type="hidden" name="is_edit" id="opt_is_edit" value="0">
                <?php wp_nonce_field('cora_options_nonce', 'cora_nonce'); ?>

                <div class="input-pro">
                    <label>Menu Title</label>
                    <input type="text" name="page_title" id="page_title" class="ghost-input" placeholder="e.g. Theme Settings"
                        required>
                </div>

                <div class="input-pro">
                    <label>Menu Slug</label>
                    <input type="text" name="page_slug" id="page_slug" class="ghost-input" placeholder="e.g. theme-settings"
                        required>
                </div>

                <div class="input-pro">
                    <label>Sidebar Icon (Dashicon)</label>
                    <input type="text" name="page_icon" id="page_icon" class="ghost-input" value="dashicons-admin-generic">
                </div>
            </form>
        </section>
        <?php
    }

    /**
     * PARTIAL: ACTIVE PAGE REGISTRY
     */
    private function render_registry($pages)
    {
        ?>
        <section class="cora-registry">
            <h3 class="panel-label">Active Options Pages</h3>
            <div class="registry-scroll">
                <?php if (empty($pages)): ?>
                    <p class="empty-state-pro">No options pages created yet.</p>
                <?php else: ?>
                    <?php foreach ($pages as $slug => $data): ?>
                        <div class="engine-item" data-config='<?php echo esc_attr(json_encode($data)); ?>'
                            data-slug="<?php echo $slug; ?>">
                            <div class="engine-icon-pro"><span class="dashicons <?php echo esc_attr($data['icon']); ?>"></span></div>
                            <div class="item-meta">
                                <strong><?php echo esc_html($data['title']); ?></strong>
                                <code>/<?php echo esc_html($slug); ?></code>
                            </div>
                            <form method="post" action="<?php echo admin_url('admin-post.php'); ?>" class="no-print">
                                <input type="hidden" name="action" value="cora_delete_options_page">
                                <input type="hidden" name="page_slug" value="<?php echo esc_attr($slug); ?>">
                                <?php wp_nonce_field('cora_options_del_nonce', 'cora_nonce'); ?>
                                <button type="submit" class="delete-btn"
                                    onclick="event.stopPropagation(); return confirm('Delete this page?')">
                                    <span class="dashicons dashicons-trash"></span>
                                </button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
        <?php
    }

    /**
     * VIEW: DYNAMIC PAGE CONTENT
     * Renders the actual sitewide settings panel.
     */
    public function render_options_content()
    {
        $slug = isset($_GET['page']) ? sanitize_text_field($_GET['page']) : '';
        $pages = get_option($this->option_name, []);
        $current_page = $pages[$slug] ?? null;

        if (!$current_page)
            wp_die("Options page not found.");

        ?>
        <div class="cora-notion-container cora-options-page-view">
            <?php $this->render_sidebar(); ?>

            <main class="cora-main-workspace">
                <header class="workspace-header">
                    <div class="header-info">
                        <h1><?php echo esc_html($current_page['title']); ?></h1>
                        <p>Manage sitewide global configurations for your workspace.</p>
                    </div>
                    <div class="header-actions">
                        <button type="submit" form="cora-options-data-form" class="cora-btn-bw primary">Save Changes</button>
                    </div>
                </header>

                <section class="cora-card-pro settings-canvas">
                    <form id="cora-options-data-form" method="post" action="options.php">
                        <?php
                        settings_fields($slug . '_group');
                        do_settings_sections($slug);
                        ?>
                    </form>
                </section>
            </main>
        </div>
        <?php
        $this->render_scripts();
    }

    /**
     * CONTROLLER: SAVE DATA
     */
    public function handle_save_page()
    {
        if (!wp_verify_nonce($_POST['cora_nonce'], 'cora_options_nonce'))
            wp_die('Failed');
        $slug = sanitize_title($_POST['page_slug']);
        $pages = get_option($this->option_name, []);

        $pages[$slug] = [
            'title' => sanitize_text_field($_POST['page_title']),
            'icon' => sanitize_text_field($_POST['page_icon']),
            'position' => 60
        ];

        update_option($this->option_name, $pages);
        flush_rewrite_rules();
        wp_redirect(admin_url('admin.php?page=cora-options-builder'));
        exit;
    }

    /**
     * CONTROLLER: DELETE DATA
     */
    public function handle_delete_page()
    {
        if (!wp_verify_nonce($_POST['cora_nonce'], 'cora_options_del_nonce'))
            wp_die('Failed');
        $slug = sanitize_text_field($_POST['page_slug']);
        $pages = get_option($this->option_name, []);
        if (isset($pages[$slug]))
            unset($pages[$slug]);
        update_option($this->option_name, $pages);
        wp_redirect(admin_url('admin.php?page=cora-options-builder'));
        exit;
    }

    /**
     * SCRIPTS: INTERACTIVE LOGIC
     */
    private function render_scripts()
    {
        ?>
        <script>
            jQuery(document).ready(function ($) {
                // Collapsible Sidebar
                $('#sidebar-toggle').on('click', function () {
                    $('.cora-notion-container').toggleClass('sidebar-collapsed');
                });

                // Auto-Slug (Only for new pages)
                $('#page_title').on('keyup', function () {
                    if ($('#opt_is_edit').val() === "0") {
                        $('#page_slug').val($(this).val().toLowerCase().trim().replace(/\s+/g, '-').replace(/[^a-z0-9-]/g, ''));
                    }
                });

                // Data Hydration logic
                $('.engine-item').on('click', function () {
                    const data = $(this).data('config');
                    const slug = $(this).data('slug');
                    $('.engine-item').removeClass('active'); $(this).addClass('active');

                    $('#opt_is_edit').val(1);
                    $('#page_slug').val(slug).attr('readonly', true).css('background', '#f5f5f5');
                    $('#opt-submit-btn').text('Update Page');
                    $('#reset-options-btn').show();
                    $('#opt-mode-badge').text('EDIT').css('background', '#000');

                    $('#page_title').val(data.title);
                    $('#page_icon').val(data.icon);
                });

                $('#reset-options-btn').on('click', () => window.location.reload());
            });
        </script>
        <?php
    }
}