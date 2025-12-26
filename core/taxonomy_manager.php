<?php
namespace Cora_Builder\Core;

if (!defined('ABSPATH'))
	exit;

/**
 * Cora Taxonomy Engine: Modular SaaS Edition
 * Refactored for scalability and organized into partial views.
 */
class Taxonomy_Manager
{
	private $option_name = 'cora_custom_taxonomies';

	public function __construct()
	{
		// Core Hooks
		add_action('init', [$this, 'register_stored_taxonomies']);
		add_action('admin_post_cora_save_taxonomy', [$this, 'handle_save_taxonomy']);
		add_action('admin_post_cora_delete_taxonomy', [$this, 'handle_delete_taxonomy']);
	}

	/**
	 * MODULE: WORDPRESS REGISTRATION
	 */
	public function register_stored_taxonomies()
	{
		$taxonomies = get_option($this->option_name, []);

		foreach ($taxonomies as $slug => $data) {
			register_taxonomy($slug, $data['post_types'], [
				'labels' => [
					'name' => $data['plural'],
					'singular_name' => $data['singular'],
					'search_items' => 'Search ' . $data['plural'],
					'all_items' => 'All ' . $data['plural'],
					'edit_item' => 'Edit ' . $data['singular'],
					'update_item' => 'Update ' . $data['singular'],
					'add_new_item' => 'Add New ' . $data['singular'],
					'new_item_name' => 'New ' . $data['singular'] . ' Name',
					'menu_name' => $data['plural'],
				],
				'hierarchical' => (bool) $data['hierarchical'],
				'public' => true,
				'publicly_queryable' => true, // FIXED: Required for frontend visibility
				'show_ui' => true,
				'show_admin_column' => true,
				'show_in_rest' => true,
				'query_var' => true,
				'rewrite' => ['slug' => $slug, 'with_front' => false],
			]);
		}
	}

	/**
	 * MAIN VIEW DISPATCHER
	 */
	public function render_taxonomy_page()
	{
		$taxonomies = get_option($this->option_name, []);
		$post_types = get_post_types(['public' => true], 'objects');
		?>
		<div class="cora-notion-container" id="cora-workspace-root">
			<?php $this->render_sidebar(); ?>
			<main class="cora-main-workspace">
				<?php $this->render_header(); ?>
				<div class="cora-grid-layout">
					<?php $this->render_config_form($post_types); ?>
					<?php $this->render_registry($taxonomies); ?>
				</div>
			</main>
		</div>
		<?php
		$this->render_scripts();
	}

	/**
	 * PARTIAL: SIDEBAR
	 */
	private function render_sidebar()
	{
		?>
		<aside class="cora-sidebar-pro" id="cora-sidebar">
			<button type="button" id="sidebar-toggle" class="sidebar-toggle-btn no-print"><span
					class="dashicons dashicons-menu-alt3"></span></button>
			<div class="sidebar-brand">
				<span class="cora-logo-mark">C</span>
				<strong class="sidebar-text">Cora Studio</strong>
			</div>
			<nav class="sidebar-nav">
				<a href="<?php echo admin_url('admin.php?page=cora-builder'); ?>"><span
						class="dashicons dashicons-dashboard"></span> <span class="sidebar-text">Dashboard</span></a>
				<a href="<?php echo admin_url('admin.php?page=cora-cpt'); ?>"><span
						class="dashicons dashicons-admin-post"></span> <span class="sidebar-text">Post Types</span></a>
				<a href="#" class="active"><span class="dashicons dashicons-tag"></span> <span
						class="sidebar-text">Taxonomies</span></a>
				<a href="<?php echo admin_url('admin.php?page=cora-fieldgroups'); ?>"><span
						class="dashicons dashicons-list-view"></span> <span class="sidebar-text">Field Groups</span></a>
			</nav>
		</aside>
		<?php
	}

	/**
	 * PARTIAL: HEADER
	 */
	private function render_header()
	{
		?>
		<header class="workspace-header">
			<div class="header-info">
				<h1>Taxonomy Engine <span class="mode-pill" id="tax-mode-badge">NEW</span></h1>
				<p>Categorize your custom data structures with ease.</p>
			</div>
			<div class="header-actions">
				<button type="button" id="reset-tax-btn" class="cora-btn-bw secondary">New Engine</button>
				<button type="submit" form="cora-tax-form" id="tax-submit-btn" class="cora-btn-bw primary">Deploy
					Engine</button>
			</div>
		</header>
		<?php
	}

	/**
	 * PARTIAL: CONFIG FORM
	 */
	private function render_config_form($post_types)
	{
		?>
		<section class="cora-card-pro config-area" id="tax-form-card">
			<form id="cora-tax-form" method="post" action="<?php echo admin_url('admin-post.php'); ?>">
				<input type="hidden" name="action" value="cora_save_taxonomy">
				<input type="hidden" name="is_edit" id="tax_is_edit" value="0">
				<?php wp_nonce_field('cora_tax_nonce', 'cora_nonce'); ?>

				<div class="form-row">
					<div class="input-pro"><label>Singular Name</label><input type="text" name="tax_singular" id="tax_singular"
							placeholder="e.g. Genre" required></div>
					<div class="input-pro"><label>Plural Name</label><input type="text" name="tax_plural" id="tax_plural"
							placeholder="e.g. Genres" required></div>
				</div>

				<div class="input-pro">
					<label>Slug (System Key)</label>
					<input type="text" name="tax_slug" id="tax_slug" required>
					<small id="tax-slug-hint">Auto-generated identifier.</small>
				</div>

				<div class="cora-label-sm" style="margin-top:30px;">Attach to Post Types</div>
				<div class="checkbox-grid-pro">
					<?php foreach ($post_types as $pt): ?>
						<label><input type="checkbox" name="tax_post_types[]" value="<?php echo esc_attr($pt->name); ?>">
							<?php echo esc_html($pt->label); ?></label>
					<?php endforeach; ?>
				</div>

				<div class="cora-checkbox-stack" style="margin-top:30px;">
					<label class="toggle-control">
						<input type="checkbox" name="tax_hierarchical" id="tax_hierarchical" value="1" checked>
						<span><strong>Hierarchical Logic</strong> (Categories vs. Tags)</span>
					</label>
				</div>
			</form>
		</section>
		<?php
	}

	/**
	 * PARTIAL: REGISTRY
	 */
	private function render_registry($taxonomies)
	{
		?>
		<section class="cora-registry">
			<h3 class="panel-label">Deployed Engines</h3>
			<div class="registry-scroll">
				<?php if (empty($taxonomies)): ?>
					<p class="empty-state-pro">No taxonomies deployed.</p>
				<?php else: ?>
					<?php foreach ($taxonomies as $slug => $data): ?>
						<div class="engine-item" data-config='<?php echo esc_attr(json_encode($data)); ?>'
							data-slug="<?php echo esc_attr($slug); ?>">
							<div class="engine-icon-pro"><span class="dashicons dashicons-tag"></span></div>
							<div class="item-meta">
								<strong><?php echo esc_html($data['plural']); ?></strong>
								<code>/<?php echo esc_html($slug); ?></code>
							</div>
							<form method="post" action="<?php echo admin_url('admin-post.php'); ?>" class="no-print">
								<input type="hidden" name="action" value="cora_delete_taxonomy">
								<input type="hidden" name="tax_slug" value="<?php echo esc_attr($slug); ?>">
								<?php wp_nonce_field('cora_tax_del_nonce', 'cora_nonce'); ?>
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
	 * CONTROLLERS: DATA HANDLING
	 */
	public function handle_save_taxonomy()
	{
		if (!wp_verify_nonce($_POST['cora_nonce'], 'cora_tax_nonce'))
			wp_die('Security Error');
		$slug = sanitize_title($_POST['tax_slug']);
		$taxonomies = get_option($this->option_name, []);

		$taxonomies[$slug] = [
			'singular' => sanitize_text_field($_POST['tax_singular']),
			'plural' => sanitize_text_field($_POST['tax_plural']),
			'post_types' => isset($_POST['tax_post_types']) ? array_map('sanitize_text_field', $_POST['tax_post_types']) : ['post'],
			'hierarchical' => isset($_POST['tax_hierarchical']) ? 1 : 0,
		];

		update_option($this->option_name, $taxonomies);
		flush_rewrite_rules();
		wp_redirect(admin_url('admin.php?page=cora-tax'));
		exit;
	}

	public function handle_delete_taxonomy()
	{
		if (!wp_verify_nonce($_POST['cora_nonce'], 'cora_tax_del_nonce'))
			wp_die('Security Error');
		$slug = sanitize_text_field($_POST['tax_slug']);
		$taxonomies = get_option($this->option_name, []);
		if (isset($taxonomies[$slug]))
			unset($taxonomies[$slug]);
		update_option($this->option_name, $taxonomies);
		flush_rewrite_rules();
		wp_redirect(admin_url('admin.php?page=cora-tax'));
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
				// Sidebar Toggle
				$('#sidebar-toggle').on('click', function () {
					$('.cora-notion-container').toggleClass('sidebar-collapsed');
				});

				// Auto-Slug Logic
				$('#tax_singular').on('keyup change', function () {
					if ($('#tax_is_edit').val() === "0") {
						$('#tax_slug').val($(this).val().toLowerCase().trim().replace(/\s+/g, '_').replace(/[^a-z0-9_]/g, ''));
					}
				});

				// Registry Hydration
				$('.engine-item').on('click', function () {
					const data = $(this).data('config');
					const slug = $(this).data('slug');
					$('.engine-item').removeClass('active'); $(this).addClass('active');

					$('#tax_is_edit').val(1);
					$('#tax_slug').val(slug).attr('readonly', true).css('background', '#f5f5f5');
					$('#tax-submit-btn').text('Update Engine');
					$('#reset-tax-btn').show();
					$('#tax-mode-badge').text('EDIT').css('background', '#000');

					$('#tax_singular').val(data.singular);
					$('#tax_plural').val(data.plural);
					$('#tax_hierarchical').prop('checked', data.hierarchical == 1);

					$('input[name="tax_post_types[]"]').prop('checked', false);
					if (data.post_types) {
						data.post_types.forEach(pt => {
							$('input[value="' + pt + '"]').prop('checked', true);
						});
					}
				});

				$('#reset-tax-btn').on('click', () => window.location.reload());
			});
		</script>
		<?php
	}
}