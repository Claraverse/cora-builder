<?php
namespace Cora_Builder\Core;

if (!defined('ABSPATH'))
    exit;

/**
 * Cora Post Type Engine: Modular SaaS Edition
 * Structured for easy maintenance and future feature scaling.
 */
class CPT_Manager
{
    private $option_name = 'cora_custom_post_types';

    public function __construct()
    {
        // Core Hooks
        add_action('init', [$this, 'register_stored_cpts']);
        add_action('admin_post_cora_save_cpt', [$this, 'handle_save_cpt']);
        add_action('admin_post_cora_delete_cpt', [$this, 'handle_delete_cpt']);
    }

    /**
     * MODULE: WORDPRESS REGISTRATION
     * Handles the background injection of post types into the system.
     */

    public function register_stored_cpts()
    {
        $cpts = get_option($this->option_name, []);
        foreach ($cpts as $slug => $data) {
            // Safe access with defaults
            $icon_type = $data['icon_type'] ?? 'dashicon';
            $icon_file_url = $data['icon_file_url'] ?? '';
            $icon_svg_code = $data['icon_svg_code'] ?? '';
            $icon_value = $data['icon_value'] ?? 'dashicons-admin-post';

            $icon = $icon_type === 'file' ? ($icon_file_url ?: 'dashicons-admin-post') :
                ($icon_type === 'svg' ? 'data:image/svg+xml;base64,' . base64_encode($icon_svg_code) :
                    ($icon_value ?: 'dashicons-admin-post'));

            register_post_type($slug, [
                'labels' => [
                    'name' => ($data['plural'] ?? '') ?: 'Posts',
                    'singular_name' => ($data['singular'] ?? '') ?: 'Post',
                    'add_new' => ($data['label_add_new'] ?? '') ?: 'Add New',
                    'not_found' => ($data['label_not_found'] ?? '') ?: 'No items found',
                ],
                'public' => true,
                'publicly_queryable' => true,
                'show_ui' => true,
                'show_in_menu' => (bool) ($data['show_in_menu'] ?? true),
                'has_archive' => (bool) ($data['has_archive'] ?? true),
                'hierarchical' => (bool) ($data['hierarchical'] ?? false),
                'show_in_rest' => (bool) ($data['enable_rest'] ?? true),
                'menu_icon' => $icon,
                'menu_position' => (int) ($data['menu_position'] ?? 5),
                'supports' => $data['supports'] ?? ['title', 'editor', 'thumbnail'],
                'taxonomies' => $data['taxonomies'] ?? [],
                'rewrite' => ['slug' => ($data['rewrite_slug'] ?? '') ?: $slug, 'with_front' => false],
                'query_var' => true,
            ]);
        }
    }

    /**
     * MAIN VIEW DISPATCHER
     * This acts as the "Dashboard Index". It calls individual components.
     */
    public function render_cpt_page()
    {
        $cpts = get_option($this->option_name, []);
        wp_enqueue_media(); // Load WP Media Library for File Icons
        ?>
        <div class="cora-notion-container" id="cora-workspace-root">
            <?php $this->render_sidebar(); ?>
            <main class="cora-main-workspace">
                <?php $this->render_header(); ?>
                <div class="cora-grid-layout">
                    <?php $this->render_config_form(); ?>
                    <?php $this->render_registry($cpts); ?>
                </div>
            </main>
        </div>
        <?php
        $this->render_scripts();
    }

    /**
     * PARTIAL: COLLAPSIBLE SIDEBAR
     */
    private function render_sidebar()
    {
        ?>
        <?php include CORA_BUILDER_PATH . 'views/components/sidebar.php'; ?>

        <?php
    }

    /**
     * PARTIAL: DASHBOARD HEADER
     */
    private function render_header()
    {
        ?>
        <header class="workspace-header">
            <div class="header-info">
                <h1>Post Type Engine <span class="mode-pill" id="mode-badge">NEW</span></h1>
                <p>Architect data structures with surgical precision.</p>
            </div>
            <div class="header-actions">
                <button type="button" id="reset-form-btn" class="cora-btn-bw secondary">Clear Workspace</button>
                <button type="submit" form="cora-cpt-form" id="submit-btn" class="cora-btn-bw primary">Deploy Engine</button>
            </div>
        </header>
        <?php
    }

    /**
     * PARTIAL: CONFIGURATION WORKSPACE (The Form)
     */
    private function render_config_form()
    {
        ?>
        <section class="cora-card-pro config-area" id="cpt-form-card">
            <div class="cora-tabs">
                <button class="tab-link active" data-tab="general">General</button>
                <button class="tab-link" data-tab="labels">Labels</button>
                <button class="tab-link" data-tab="advanced">Advanced</button>
            </div>

            <form id="cora-cpt-form" method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                <input type="hidden" name="action" value="cora_save_cpt">
                <input type="hidden" name="is_edit" id="is_edit" value="0">
                <?php wp_nonce_field('cora_cpt_nonce', 'cora_nonce'); ?>

                <div id="tab-general" class="tab-pane active">
                    <div class="form-row">
                        <div class="input-pro"><label>Singular Name</label><input type="text" name="cpt_singular"
                                id="cpt_singular" required></div>
                        <div class="input-pro"><label>Plural Name</label><input type="text" name="cpt_plural" id="cpt_plural"
                                required></div>
                    </div>
                    <div class="input-pro"><label>Slug (System Key)</label><input type="text" name="cpt_slug" id="cpt_slug"
                            required></div>

                    <div class="input-pro">
                        <label>Icon Configuration</label>
                        <div class="icon-selector-grid">
                            <select name="icon_type" id="icon_type" class="icon-select">
                                <option value="dashicon">Dashicon Name</option>
                                <option value="svg">SVG Path/Code</option>
                                <option value="file">Media File Upload</option>
                            </select>
                            <div id="icon-input-wrap">
                                <input type="text" name="icon_value" id="icon_value" placeholder="dashicons-admin-post">
                                <textarea name="icon_svg_code" id="icon_svg" style="display:none;"
                                    placeholder="<svg>...</svg>"></textarea>
                                <div id="icon-file-wrap" style="display:none; display:flex; gap:10px; align-items:center;">
                                    <input type="text" name="icon_file_url" id="icon_file_url" readonly style="flex-grow:1;">
                                    <button type="button" class="cora-btn-bw secondary" id="upload-icon-btn"
                                        style="white-space:nowrap; padding:5px 10px;">Select File</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="cora-label-sm">Components & Features</div>
                    <div class="checkbox-grid-pro">
                        <label><input type="checkbox" name="cpt_supports[]" value="title" checked> Title</label>
                        <label><input type="checkbox" name="cpt_supports[]" value="editor" checked> Editor</label>
                        <label><input type="checkbox" name="cpt_supports[]" value="thumbnail" checked> Featured Image</label>
                        <label><input type="checkbox" name="cpt_supports[]" value="excerpt"> Excerpt</label>
                    </div>
                </div>

                <div id="tab-labels" class="tab-pane">
                    <div class="input-pro"><label>Add New Label</label><input type="text" name="label_add_new"
                            id="label_add_new" placeholder="e.g. Add New Member"></div>
                    <div class="input-pro"><label>Not Found Label</label><input type="text" name="label_not_found"
                            id="label_not_found" placeholder="e.g. No Members Found"></div>
                </div>

                <div id="tab-advanced" class="tab-pane">
                    <div class="form-row">
                        <div class="input-pro"><label>URL Rewrite</label><input type="text" name="rewrite_slug"
                                id="rewrite_slug" placeholder="e.g. our-work"></div>
                        <div class="input-pro"><label>Sidebar Position</label><input type="number" name="menu_position"
                                id="menu_position" value="5"></div>
                    </div>
                    <div class="checkbox-stack-pro">
                        <label><input type="checkbox" name="cpt_public" id="cpt_public" value="1" checked> Public
                            Presence</label>
                        <label><input type="checkbox" name="show_in_menu" id="show_in_menu" value="1" checked> Show in
                            Sidebar</label>
                        <label><input type="checkbox" name="cpt_archive" id="cpt_archive" value="1" checked> Deploy Archive
                            URL</label>
                        <label><input type="checkbox" name="enable_rest" id="enable_rest" value="1" checked> REST API
                            Support</label>
                    </div>
                </div>
            </form>
        </section>
        <?php
    }

    /**
     * PARTIAL: DEPLOYED ENGINES REGISTRY
     */
    private function render_registry($cpts)
    {
        ?>
        <section class="cora-registry">
            <h3 class="panel-label">Deployed Engines</h3>
            <div class="registry-scroll">
                <?php if (empty($cpts)): ?>
                    <p class="empty-state-pro">No engines deployed.</p>
                <?php else: ?>
                    <?php foreach ($cpts as $slug => $data):
                        // Logic for displaying the representative icon
                        $type = $data['icon_type'] ?? 'dashicon';
                        $val = $data['icon_value'] ?? 'dashicons-admin-post';
                        $icon_class = ($type === 'dashicon') ? ($val ?: 'dashicons-admin-post') : 'dashicons-format-image';
                        // $icon_class = ($data['icon_type'] === 'dashicon') ? ($data['icon_value'] ?: 'dashicons-admin-post') : 'dashicons-format-image';
                        ?>
                        <div class="engine-item" data-config='<?php echo esc_attr(json_encode($data)); ?>'
                            data-slug="<?php echo esc_attr($slug); ?>">
                            <div class="engine-icon-pro"><span class="dashicons <?php echo esc_attr($icon_class); ?>"></span></div>
                            <div class="item-meta">
                                <strong><?php echo esc_html($data['plural']); ?></strong>
                                <code>/<?php echo esc_html($slug); ?></code>
                            </div>
                            <form method="post" action="<?php echo admin_url('admin-post.php'); ?>" class="no-print">
                                <input type="hidden" name="action" value="cora_delete_cpt">
                                <input type="hidden" name="cpt_slug" value="<?php echo esc_attr($slug); ?>">
                                <?php wp_nonce_field('cora_delete_nonce', 'cora_nonce'); ?>
                                <button type="submit" class="delete-btn"
                                    onclick="event.stopPropagation(); return confirm('Delete engine?')"><span
                                        class="dashicons dashicons-trash"></span></button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
        <?php
    }

    /**
     * CONTROLLER: SAVE DATA
     */
    public function handle_save_cpt()
    {
        if (!wp_verify_nonce($_POST['cora_nonce'], 'cora_cpt_nonce'))
            wp_die('Security Error');
        $slug = sanitize_title($_POST['cpt_slug']);
        $cpts = get_option($this->option_name, []);

        $cpts[$slug] = [
            'singular' => sanitize_text_field($_POST['cpt_singular']),
            'plural' => sanitize_text_field($_POST['cpt_plural']),
            'icon_type' => sanitize_text_field($_POST['icon_type']),
            'icon_value' => sanitize_text_field($_POST['icon_value']),
            'icon_svg_code' => wp_kses_post($_POST['icon_svg_code']),
            'icon_file_url' => esc_url_raw($_POST['icon_file_url']),
            'label_add_new' => sanitize_text_field($_POST['label_add_new']),
            'label_not_found' => sanitize_text_field($_POST['label_not_found']),
            'rewrite_slug' => sanitize_title($_POST['rewrite_slug']),
            'menu_position' => intval($_POST['menu_position']),
            'supports' => isset($_POST['cpt_supports']) ? array_map('sanitize_text_field', $_POST['cpt_supports']) : [],
            'public' => isset($_POST['cpt_public']) ? 1 : 0,
            'show_in_menu' => isset($_POST['show_in_menu']) ? 1 : 0,
            'has_archive' => isset($_POST['cpt_archive']) ? 1 : 0,
            'enable_rest' => isset($_POST['enable_rest']) ? 1 : 0,
        ];

        update_option($this->option_name, $cpts);
        flush_rewrite_rules();
        wp_redirect(admin_url('admin.php?page=cora-cpt'));
        exit;
    }

    /**
     * CONTROLLER: DELETE DATA
     */
    public function handle_delete_cpt()
    {
        if (!wp_verify_nonce($_POST['cora_nonce'], 'cora_delete_nonce'))
            wp_die('Failed');
        $slug = sanitize_text_field($_POST['cpt_slug']);
        $cpts = get_option($this->option_name, []);
        if (isset($cpts[$slug]))
            unset($cpts[$slug]);
        update_option($this->option_name, $cpts);
        flush_rewrite_rules();
        wp_redirect(admin_url('admin.php?page=cora-cpt'));
        exit;
    }

    /**
     * PARTIAL: CORE INTERACTIVE SCRIPTS
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

                // Tab Engine
                $('.tab-link').on('click', function (e) {
                    e.preventDefault();
                    $('.tab-link, .tab-pane').removeClass('active');
                    $(this).addClass('active');
                    $('#tab-' + $(this).data('tab')).addClass('active');
                });

                // Slug Auto-Generation
                $('#cpt_singular').on('keyup', function () {
                    if ($('#is_edit').val() === "0") {
                        $('#cpt_slug').val($(this).val().toLowerCase().trim().replace(/\s+/g, '_').replace(/[^a-z0-9_]/g, ''));
                    }
                });

                // Icon mode Switcher logic
                $('#icon_type').on('change', function () {
                    const type = $(this).val();
                    $('#icon_value').toggle(type === 'dashicon');
                    $('#icon_svg').toggle(type === 'svg');
                    $('#icon_file_wrap').toggle(type === 'file');
                }).trigger('change');

                // WordPress Media Library Hook
                $('#upload-icon-btn').on('click', function (e) {
                    e.preventDefault();
                    var frame = wp.media({ title: 'Select Menu Icon', button: { text: 'Use Icon' }, multiple: false });
                    frame.on('select', function () {
                        $('#icon_file_url').val(frame.state().get('selection').first().toJSON().url);
                    }).open();
                });

                // Data Hydration for Editing
                $('.engine-item').on('click', function () {
                    const data = $(this).data('config');
                    const slug = $(this).data('slug');
                    $('.engine-item').removeClass('active'); $(this).addClass('active');

                    $('#is_edit').val(1);
                    $('#cpt_slug').val(slug).attr('readonly', true).css('background', '#f5f5f5');
                    $('#submit-btn').text('Update Engine');
                    $('#reset-form-btn').show();
                    $('#mode-badge').text('EDIT').css('background', '#000');

                    $('#cpt_singular').val(data.singular);
                    $('#cpt_plural').val(data.plural);
                    $('#icon_type').val(data.icon_type || 'dashicon').trigger('change');
                    $('#icon_value').val(data.icon_value);
                    $('#icon_svg').val(data.icon_svg_code);
                    $('#icon_file_url').val(data.icon_file_url);
                    $('#label_add_new').val(data.label_add_new);
                    $('#label_not_found').val(data.label_not_found);
                    $('#rewrite_slug').val(data.rewrite_slug);
                    $('#menu_position').val(data.menu_position);

                    $('input[type="checkbox"]').prop('checked', false);
                    if (data.supports) data.supports.forEach(v => $(`input[value="${v}"]`).prop('checked', true));
                    $('#cpt_public').prop('checked', data.public == 1);
                    $('#show_in_menu').prop('checked', data.show_in_menu == 1);
                    $('#cpt_archive').prop('checked', data.has_archive == 1);
                    $('#enable_rest').prop('checked', data.enable_rest == 1);
                });

                $('#reset-form-btn').on('click', () => window.location.reload());
            });
        </script>
        <?php
    }
}