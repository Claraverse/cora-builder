<?php
namespace Cora_Builder\Core;

if (!defined('ABSPATH')) {
    exit;
}

class CPT_Manager
{
    private $option_name = 'cora_custom_post_types';

    public function __construct()
    {
        add_action('init', [$this, 'register_stored_cpts']);
        add_action('admin_post_cora_save_cpt', [$this, 'handle_save_cpt']);
        add_action('admin_post_cora_delete_cpt', [$this, 'handle_delete_cpt']);
    }

    public function register_stored_cpts()
    {
        $cpts = get_option($this->option_name, []);
        foreach ($cpts as $slug => $data) {
            $singular = $data['singular'] ?? 'Post';
            $plural = $data['plural'] ?? 'Posts';
            $label_add_new = $data['label_add_new'] ?? '';
            $label_not_found = $data['label_not_found'] ?? '';

            register_post_type($slug, [
                'labels' => [
                    'name' => $plural,
                    'singular_name' => $singular,
                    'add_new' => !empty($label_add_new) ? $label_add_new : 'Add New',
                    'add_new_item' => !empty($label_add_new) ? $label_add_new : "Add New $singular",
                    'not_found' => !empty($label_not_found) ? $label_not_found : "No $plural found",
                ],
                'public' => isset($data['public']) ? (bool) $data['public'] : true,
                'has_archive' => isset($data['has_archive']) ? (bool) $data['has_archive'] : true,
                'hierarchical' => isset($data['hierarchical']) ? (bool) $data['hierarchical'] : false,
                'show_in_rest' => isset($data['enable_rest']) ? (bool) $data['enable_rest'] : true,
                'menu_icon' => $data['icon'] ?? 'dashicons-admin-post',
                'menu_position' => isset($data['menu_position']) ? (int) $data['menu_position'] : 5,
                'supports' => $data['supports'] ?? ['title', 'editor', 'thumbnail'],
                'taxonomies' => $data['taxonomies'] ?? [],
                'rewrite' => ['slug' => $slug],
                'exclude_from_search' => isset($data['exclude_search']) ? (bool) $data['exclude_search'] : false,
            ]);
        }
    }

    public function render_cpt_page()
    {
        $cpts = get_option($this->option_name, []);
        ?>
        <div class="cora-admin-wrapper cora-cpt-container">
            <div class="cora-header">
                <div class="cora-logo">
                    <h1>Cora Post Type Engine <span id="mode-badge" class="cora-version">NEW</span></h1>
                </div>
                <div class="cora-actions">
                    <button type="button" id="reset-form-btn" class="cora-btn cora-btn-secondary" style="display:none;">Add New
                        Engine</button>
                    <button type="submit" form="cora-cpt-form" id="submit-btn" class="cora-btn cora-btn-primary">Deploy
                        Engine</button>
                </div>
            </div>

            <div class="cora-settings-grid">
                <div class="cora-card cora-form-container" id="cpt-form-card">
                    <div class="cora-tabs-nav">
                        <button class="cora-tab-link active" data-tab="general">General</button>
                        <button class="cora-tab-link" data-tab="labels">Labels</button>
                        <button class="cora-tab-link" data-tab="advanced">Advanced</button>
                    </div>

                    <form id="cora-cpt-form" method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                        <input type="hidden" name="action" value="cora_save_cpt">
                        <input type="hidden" name="is_edit" id="is_edit" value="0">
                        <?php wp_nonce_field('cora_cpt_nonce', 'cora_nonce'); ?>

                        <div id="cora-tab-general" class="cora-tab-content active">
                            <div class="cora-section-label">Basic Identification</div>
                            <div class="cora-form-row">
                                <div class="cora-control-group">
                                    <label>Singular Name</label>
                                    <input type="text" name="cpt_singular" id="cpt_singular" class="cora-input" required>
                                </div>
                                <div class="cora-control-group">
                                    <label>Plural Name</label>
                                    <input type="text" name="cpt_plural" id="cpt_plural" class="cora-input" required>
                                </div>
                            </div>

                            <div class="cora-control-group">
                                <label>Slug (Key)</label>
                                <input type="text" name="cpt_slug" id="cpt_slug" class="cora-input" required>
                                <small id="slug-hint">Auto-generated. Lowercase and underscores only.</small>
                            </div>

                            <div class="cora-control-group">
                                <label>Menu Icon</label>
                                <div class="cora-icon-field">
                                    <span id="icon-preview" class="dashicons dashicons-admin-post"></span>
                                    <input type="text" name="cpt_icon" id="cpt_icon" class="cora-input"
                                        value="dashicons-admin-post">
                                </div>
                            </div>

                            <hr class="cora-divider">
                            <div class="cora-section-label">Features & Taxonomies</div>
                            <div class="cora-checkbox-grid">
                                <label><input type="checkbox" name="cpt_supports[]" value="title" checked> Title</label>
                                <label><input type="checkbox" name="cpt_supports[]" value="editor" checked> Editor</label>
                                <label><input type="checkbox" name="cpt_supports[]" value="thumbnail" checked> Image</label>
                                <label><input type="checkbox" name="cpt_supports[]" value="excerpt" checked> Excerpt</label>
                                <label><input type="checkbox" name="cpt_taxonomies[]" value="category"> Categories</label>
                                <label><input type="checkbox" name="cpt_taxonomies[]" value="post_tag"> Tags</label>
                            </div>
                        </div>

                        <div id="cora-tab-labels" class="cora-tab-content">
                            <div class="cora-section-label">Custom Overrides</div>
                            <div class="cora-control-group">
                                <label>Add New Label</label>
                                <input type="text" name="label_add_new" id="label_add_new" class="cora-input"
                                    placeholder="e.g. Add New Project">
                            </div>
                            <div class="cora-control-group">
                                <label>Not Found Label</label>
                                <input type="text" name="label_not_found" id="label_not_found" class="cora-input"
                                    placeholder="e.g. No Projects Found">
                            </div>
                        </div>

                        <div id="cora-tab-advanced" class="cora-tab-content">
                            <div class="cora-section-label">Pro Behavior</div>
                            <div class="cora-checkbox-list">
                                <label><input type="checkbox" name="cpt_public" id="cpt_public" value="1" checked>
                                    Public</label>
                                <label><input type="checkbox" name="cpt_archive" id="cpt_archive" value="1" checked> Has
                                    Archive</label>
                                <label><input type="checkbox" name="cpt_hierarchical" id="cpt_hierarchical" value="1">
                                    Hierarchical</label>
                                <label><input type="checkbox" name="enable_rest" id="enable_rest" value="1" checked> REST
                                    API</label>
                                <label><input type="checkbox" name="exclude_search" id="exclude_search" value="1"> Hide from
                                    Search</label>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="cora-card cpt-list-card">
                    <h3>Deployed Engines</h3>
                    <div class="engine-list-wrapper">
                        <?php if (empty($cpts)): ?>
                            <p class="cora-empty-state">No engines deployed.</p>
                        <?php else: ?>
                            <?php foreach ($cpts as $slug => $data): ?>
                                <div class="engine-item" data-config='<?php echo json_encode($data); ?>'
                                    data-slug="<?php echo esc_attr($slug); ?>">
                                    <div class="engine-info">
                                        <span class="dashicons <?php echo esc_attr($data['icon']); ?>"></span>
                                        <div>
                                            <strong><?php echo esc_html($data['plural']); ?></strong>
                                            <code><?php echo esc_html($slug); ?></code>
                                        </div>
                                    </div>
                                    <form method="post" action="<?php echo admin_url('admin-post.php'); ?>" class="delete-form">
                                        <input type="hidden" name="action" value="cora_delete_cpt">
                                        <input type="hidden" name="cpt_slug" value="<?php echo esc_attr($slug); ?>">
                                        <?php wp_nonce_field('cora_delete_nonce', 'cora_nonce'); ?>
                                        <button type="submit" class="delete-btn"
                                            onclick="event.stopPropagation(); return confirm('Delete engine?')">
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
            jQuery(document).ready(function ($) {
                const $slugInput = $('#cpt_slug');
                const $isEdit = $('#is_edit');

                // Tab Switch
                $('.cora-tab-link').on('click', function (e) {
                    e.preventDefault();
                    $('.cora-tab-link, .cora-tab-content').removeClass('active');
                    $(this).addClass('active');
                    $('#cora-tab-' + $(this).data('tab')).addClass('active');
                });

                // Auto-Slug (Only in Create Mode)
                $('#cpt_singular').on('keyup change', function () {
                    if ($isEdit.val() === "0") {
                        let slug = $(this).val().toLowerCase().trim().replace(/\s+/g, '_').replace(/[^a-z0-9_]/g, '').substring(0, 20);
                        $slugInput.val(slug);
                    }
                });

                // Icon Preview
                $('#cpt_icon').on('keyup change', function () {
                    $('#icon-preview').attr('class', 'dashicons ' + $(this).val());
                });

                // Edit Hydration
                $('.engine-item').on('click', function () {
                    const data = $(this).data('config');
                    const slug = $(this).data('slug');

                    $('.engine-item').removeClass('active');
                    $(this).addClass('active');

                    // Switch to Edit Mode
                    $isEdit.val(1);
                    $slugInput.val(slug).attr('readonly', true).css('background', '#f5f5f5');
                    $('#slug-hint').text('Slug cannot be changed after deployment.');
                    $('#submit-btn').text('Update Engine');
                    $('#reset-form-btn').show();
                    $('#mode-badge').text('EDIT').css('background', '#3582c4');
                    $('#cpt-form-card').css('border-top', '3px solid #3582c4');

                    // Fill Fields
                    $('#cpt_singular').val(data.singular);
                    $('#cpt_plural').val(data.plural);
                    $('#cpt_icon').val(data.icon).trigger('change');
                    $('#label_add_new').val(data.label_add_new);
                    $('#label_not_found').val(data.label_not_found);

                    // Checkboxes
                    $('input[type="checkbox"]').prop('checked', false);
                    if (data.supports) data.supports.forEach(v => $('input[value="' + v + '"]').prop('checked', true));
                    if (data.taxonomies) data.taxonomies.forEach(v => $('input[value="' + v + '"]').prop('checked', true));
                    $('#cpt_public').prop('checked', data.public == 1);
                    $('#cpt_archive').prop('checked', data.has_archive == 1);
                    $('#cpt_hierarchical').prop('checked', data.hierarchical == 1);
                    $('#enable_rest').prop('checked', data.enable_rest == 1);
                    $('#exclude_search').prop('checked', data.exclude_search == 1);
                });

                $('#reset-form-btn').on('click', function () { window.location.reload(); });
            });
        </script>
        <?php
    }

    public function handle_save_cpt()
    {
        if (!isset($_POST['cora_nonce']) || !wp_verify_nonce($_POST['cora_nonce'], 'cora_cpt_nonce'))
            wp_die('Security check failed');
        if (!current_user_can('manage_options'))
            return;

        $slug = sanitize_title($_POST['cpt_slug']);
        $cpts = get_option($this->option_name, []);

        $cpts[$slug] = [
            'singular' => sanitize_text_field($_POST['cpt_singular']),
            'plural' => sanitize_text_field($_POST['cpt_plural']),
            'icon' => sanitize_text_field($_POST['cpt_icon']),
            'supports' => isset($_POST['cpt_supports']) ? array_map('sanitize_text_field', $_POST['cpt_supports']) : [],
            'taxonomies' => isset($_POST['cpt_taxonomies']) ? array_map('sanitize_text_field', $_POST['cpt_taxonomies']) : [],
            'public' => isset($_POST['cpt_public']) ? 1 : 0,
            'has_archive' => isset($_POST['cpt_archive']) ? 1 : 0,
            'hierarchical' => isset($_POST['cpt_hierarchical']) ? 1 : 0,
            'enable_rest' => isset($_POST['enable_rest']) ? 1 : 0,
            'exclude_search' => isset($_POST['exclude_search']) ? 1 : 0,
            'menu_position' => intval($_POST['menu_position'] ?? 5),
            'label_add_new' => sanitize_text_field($_POST['label_add_new']),
            'label_not_found' => sanitize_text_field($_POST['label_not_found']),
        ];

        update_option($this->option_name, $cpts);
        flush_rewrite_rules();
        wp_redirect(admin_url('admin.php?page=cora-cpt'));
        exit;
    }

    public function handle_delete_cpt()
    {
        if (!isset($_POST['cora_nonce']) || !wp_verify_nonce($_POST['cora_nonce'], 'cora_delete_nonce'))
            wp_die('Security check failed');
        $slug = sanitize_text_field($_POST['cpt_slug']);
        $cpts = get_option($this->option_name, []);
        if (isset($cpts[$slug])) {
            unset($cpts[$slug]);
            update_option($this->option_name, $cpts);
            flush_rewrite_rules();
        }
        wp_redirect(admin_url('admin.php?page=cora-cpt'));
        exit;
    }
}