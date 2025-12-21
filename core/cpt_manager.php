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

    /**
     * REGISTER POST TYPES
     */
    public function register_stored_cpts()
    {
        $cpts = get_option($this->option_name, []);

        foreach ($cpts as $slug => $data) {

            // Defaults
            $supports = isset($data['supports']) ? $data['supports'] : ['title', 'editor', 'thumbnail'];
            $taxonomies = isset($data['taxonomies']) ? $data['taxonomies'] : [];
            $public = isset($data['public']) ? (bool) $data['public'] : true;
            $archive = isset($data['has_archive']) ? (bool) $data['has_archive'] : true;
            $hierarchical = isset($data['hierarchical']) ? (bool) $data['hierarchical'] : false;

            register_post_type($slug, [
                'labels' => [
                    'name' => $data['plural'],
                    'singular_name' => $data['singular'],
                    'add_new' => 'Add New',
                    'add_new_item' => 'Add New ' . $data['singular'],
                    'edit_item' => 'Edit ' . $data['singular'],
                    'new_item' => 'New ' . $data['singular'],
                    'view_item' => 'View ' . $data['singular'],
                    'search_items' => 'Search ' . $data['plural'],
                    'not_found' => 'No ' . strtolower($data['plural']) . ' found',
                ],
                'public' => $public,
                'has_archive' => $archive,
                'hierarchical' => $hierarchical,
                'show_in_rest' => true,
                'menu_icon' => $data['icon'] ?? 'dashicons-admin-post',
                'supports' => $supports,
                'taxonomies' => $taxonomies,
                'rewrite' => ['slug' => $slug],
            ]);
        }
    }

    /**
     * UI: Renders the Dashboard Page
     */
    public function render_cpt_page()
    {
        $cpts = get_option($this->option_name, []);
        ?>
        <div class="cora-admin-wrapper">

            <div class="cora-header">
                <div class="cora-logo">
                    <h1>Advanced Post Type Builder</h1>
                </div>
                <div class="cora-actions">
                    <a href="https://developer.wordpress.org/resource/dashicons/" target="_blank"
                        class="cora-btn cora-btn-secondary">
                        <span class="dashicons dashicons-art"></span> Icon Reference
                    </a>
                </div>
            </div>

            <div class="cora-settings-grid" style="grid-template-columns: 1.2fr 1.8fr; align-items: start;">

                <div class="cora-card">
                    <h3>Create New Post Type</h3>
                    <p style="margin-bottom:20px;">Configure the labels and behavior of your new content type.</p>

                    <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                        <input type="hidden" name="action" value="cora_save_cpt">
                        <?php wp_nonce_field('cora_cpt_nonce', 'cora_nonce'); ?>

                        <div class="cora-section-label">Basic Identification</div>

                        <div class="cora-form-row">
                            <div class="cora-control-group">
                                <label>Singular Name</label>
                                <input type="text" name="cpt_singular" id="cpt_singular_input" class="cora-input"
                                    placeholder="e.g. Portfolio" required>
                            </div>
                            <div class="cora-control-group">
                                <label>Plural Name</label>
                                <input type="text" name="cpt_plural" class="cora-input" placeholder="e.g. Portfolios" required>
                            </div>
                        </div>

                        <div class="cora-control-group">
                            <label>Slug (Key)</label>
                            <input type="text" name="cpt_slug" id="cpt_slug_input" class="cora-input"
                                placeholder="e.g. portfolio" required>
                            <small>Auto-generated. Lowercase and underscores only.</small>
                        </div>

                        <div class="cora-control-group">
                            <label>Menu Icon</label>
                            <input type="text" name="cpt_icon" class="cora-input" value="dashicons-admin-post"
                                placeholder="dashicons-...">
                        </div>

                        <hr class="cora-divider">

                        <div class="cora-section-label">Supported Features</div>
                        <div class="cora-checkbox-grid">
                            <label><input type="checkbox" name="cpt_supports[]" value="title" checked> Title</label>
                            <label><input type="checkbox" name="cpt_supports[]" value="editor" checked> Editor</label>
                            <label><input type="checkbox" name="cpt_supports[]" value="thumbnail" checked> Featured
                                Image</label>
                            <label><input type="checkbox" name="cpt_supports[]" value="excerpt" checked> Excerpt</label>
                            <label><input type="checkbox" name="cpt_supports[]" value="author"> Author</label>
                            <label><input type="checkbox" name="cpt_supports[]" value="comments"> Comments</label>
                            <label><input type="checkbox" name="cpt_supports[]" value="page-attributes"> Page Attributes</label>
                            <label><input type="checkbox" name="cpt_supports[]" value="custom-fields"> Custom Fields</label>
                        </div>

                        <hr class="cora-divider">

                        <div class="cora-section-label">Taxonomies</div>
                        <div class="cora-checkbox-grid">
                            <label><input type="checkbox" name="cpt_taxonomies[]" value="category"> Categories</label>
                            <label><input type="checkbox" name="cpt_taxonomies[]" value="post_tag"> Tags</label>
                        </div>

                        <hr class="cora-divider">

                        <div class="cora-section-label">Options</div>
                        <div class="cora-checkbox-list">
                            <label>
                                <input type="checkbox" name="cpt_public" value="1" checked>
                                <strong>Public</strong> (Visible on frontend)
                            </label>
                            <label>
                                <input type="checkbox" name="cpt_archive" value="1" checked>
                                <strong>Has Archive</strong> (e.g. /portfolio/)
                            </label>
                            <label>
                                <input type="checkbox" name="cpt_hierarchical" value="1">
                                <strong>Hierarchical</strong> (Parent/Child like Pages)
                            </label>
                        </div>

                        <button type="submit" class="cora-btn cora-btn-primary"
                            style="width:100%; justify-content:center; margin-top:20px;">
                            <span class="dashicons dashicons-plus"></span> Create Post Type
                        </button>
                    </form>
                </div>

                <div class="cora-card" style="align-self: start;">
                    <h3>Active Post Types</h3>
                    <?php if (empty($cpts)): ?>
                        <p class="cora-empty-state">No custom post types found. Create your first one!</p>
                    <?php else: ?>
                        <table class="wp-list-table widefat fixed striped table-view-list">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Key</th>
                                    <th>Features</th>
                                    <th style="width: 60px; text-align:right;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cpts as $slug => $data): ?>
                                    <tr>
                                        <td>
                                            <strong style="color:var(--gray-900); display:flex; align-items:center; gap:8px;">
                                                <span class="dashicons <?php echo esc_attr($data['icon']); ?>"></span>
                                                <?php echo esc_html($data['plural']); ?>
                                            </strong>
                                        </td>
                                        <td><code><?php echo esc_html($slug); ?></code></td>
                                        <td style="color:var(--gray-500); font-size:11px;">
                                            <?php
                                            $features = isset($data['supports']) ? count($data['supports']) : 0;
                                            echo $features . ' features';
                                            if (!empty($data['taxonomies']))
                                                echo ', ' . count($data['taxonomies']) . ' tax';
                                            ?>
                                        </td>
                                        <td style="text-align:right;">
                                            <form method="post" action="<?php echo admin_url('admin-post.php'); ?>"
                                                style="display:inline;">
                                                <input type="hidden" name="action" value="cora_delete_cpt">
                                                <input type="hidden" name="cpt_slug" value="<?php echo esc_attr($slug); ?>">
                                                <?php wp_nonce_field('cora_delete_nonce', 'cora_nonce'); ?>
                                                <button type="submit" class="button-link-delete" style="color:#ef4444;"
                                                    onclick="return confirm('Delete this post type?')">
                                                    <span class="dashicons dashicons-trash"></span>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>

            </div>
        </div>

        <script>
            jQuery(document).ready(function ($) {
                $('#cpt_singular_input').on('keyup change', function () {
                    var val = $(this).val();
                    // Convert to lowercase, replace spaces with underscores, remove non-alphanumeric
                    var slug = val.toLowerCase().trim()
                        .replace(/\s+/g, '_')      // Spaces to underscores
                        .replace(/[^a-z0-9_]/g, '') // Remove special chars
                        .substring(0, 20);         // Limit to 20 chars (WP recommendation)

                    $('#cpt_slug_input').val(slug);
                });
            });
        </script>
        <?php
    }

    /**
     * SAVE HANDLER
     */
    public function handle_save_cpt()
    {
        if (!isset($_POST['cora_nonce']) || !wp_verify_nonce($_POST['cora_nonce'], 'cora_cpt_nonce')) {
            wp_die('Security check failed');
        }
        if (!current_user_can('manage_options'))
            return;

        $slug = sanitize_title($_POST['cpt_slug']);

        // Prepare Data
        $new_cpt = [
            'singular' => sanitize_text_field($_POST['cpt_singular']),
            'plural' => sanitize_text_field($_POST['cpt_plural']),
            'icon' => sanitize_text_field($_POST['cpt_icon']),
            'supports' => isset($_POST['cpt_supports']) ? array_map('sanitize_text_field', $_POST['cpt_supports']) : [],
            'taxonomies' => isset($_POST['cpt_taxonomies']) ? array_map('sanitize_text_field', $_POST['cpt_taxonomies']) : [],
            'public' => isset($_POST['cpt_public']) ? 1 : 0,
            'has_archive' => isset($_POST['cpt_archive']) ? 1 : 0,
            'hierarchical' => isset($_POST['cpt_hierarchical']) ? 1 : 0,
        ];

        $cpts = get_option($this->option_name, []);
        $cpts[$slug] = $new_cpt;

        update_option($this->option_name, $cpts);
        flush_rewrite_rules();

        wp_redirect(admin_url('admin.php?page=cora-cpt'));
        exit;
    }

    /**
     * DELETE HANDLER
     */
    public function handle_delete_cpt()
    {
        if (!isset($_POST['cora_nonce']) || !wp_verify_nonce($_POST['cora_nonce'], 'cora_delete_nonce')) {
            wp_die('Security check failed');
        }
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