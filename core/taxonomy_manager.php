<?php
namespace Cora_Builder\Core;

if (!defined('ABSPATH')) {
	exit;
}

class Taxonomy_Manager
{

	private $option_name = 'cora_custom_taxonomies';

	public function __construct()
	{
		add_action('init', [$this, 'register_stored_taxonomies']);
		add_action('admin_post_cora_save_taxonomy', [$this, 'handle_save_taxonomy']);
		add_action('admin_post_cora_delete_taxonomy', [$this, 'handle_delete_taxonomy']);
	}

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
				'show_ui' => true,
				'show_admin_column' => true,
				'show_in_rest' => true,
				'query_var' => true,
				'rewrite' => ['slug' => $slug],
			]);
		}
	}

	public function render_taxonomy_page()
	{
		$taxonomies = get_option($this->option_name, []);
		$available_post_types = get_post_types(['public' => true], 'objects');
		?>
		<div class="cora-admin-wrapper cora-tax-container">
			<div class="cora-header">
				<div class="cora-logo">
					<h1>Taxonomy Builder <span id="tax-mode-badge" class="cora-version">NEW</span></h1>
				</div>
				<div class="cora-actions">
					<button type="button" id="reset-tax-btn" class="cora-btn cora-btn-secondary" style="display:none;">Add
						New</button>
					<button type="submit" form="cora-tax-form" id="tax-submit-btn" class="cora-btn cora-btn-primary">Create
						Taxonomy</button>
				</div>
			</div>

			<div class="cora-settings-grid">

				<div class="cora-card cora-form-container" id="tax-form-card">
					<h3>Configure Taxonomy</h3>
					<form id="cora-tax-form" method="post" action="<?php echo admin_url('admin-post.php'); ?>">
						<input type="hidden" name="action" value="cora_save_taxonomy">
						<input type="hidden" name="is_edit" id="tax_is_edit" value="0">
						<?php wp_nonce_field('cora_tax_nonce', 'cora_nonce'); ?>

						<div class="cora-form-row">
							<div class="cora-control-group">
								<label>Singular Name</label>
								<input type="text" name="tax_singular" id="tax_singular" class="cora-input"
									placeholder="e.g. Genre" required>
							</div>
							<div class="cora-control-group">
								<label>Plural Name</label>
								<input type="text" name="tax_plural" id="tax_plural" class="cora-input"
									placeholder="e.g. Genres" required>
							</div>
						</div>

						<div class="cora-control-group">
							<label>Slug (Key)</label>
							<input type="text" name="tax_slug" id="tax_slug" class="cora-input" required>
							<small id="tax-slug-hint">Auto-generated. Lowercase and underscores only.</small>
						</div>

						<hr class="cora-divider">

						<div class="cora-section-label">Attach to Post Types</div>
						<div class="cora-checkbox-grid">
							<?php foreach ($available_post_types as $pt): ?>
								<label><input type="checkbox" name="tax_post_types[]" value="<?php echo esc_attr($pt->name); ?>">
									<?php echo esc_html($pt->label); ?></label>
							<?php endforeach; ?>
						</div>

						<hr class="cora-divider">

						<div class="cora-checkbox-list">
							<label class="cora-toggle-item">
								<input type="checkbox" name="tax_hierarchical" id="tax_hierarchical" value="1" checked>
								<div>
									<strong>Hierarchical</strong>
									<span>Like Categories (Parent/Child), otherwise like Tags.</span>
								</div>
							</label>
						</div>
					</form>
				</div>

				<div class="cora-card cpt-list-card">
					<div class="card-header-flex">
						<div class="header-title-group">
							<h3>Active Taxonomies</h3>
							<span class="cora-badge-count"><?php echo count($taxonomies); ?> Total</span>
						</div>
					</div>

					<div class="tax-list-wrapper">
						<?php if (empty($taxonomies)): ?>
							<div class="cora-empty-state">
								<span class="dashicons dashicons-tag"></span>
								<p>No custom taxonomies deployed yet.</p>
							</div>
						<?php else: ?>
							<?php foreach ($taxonomies as $slug => $data): ?>
								<div class="tax-engine-item" data-config='<?php echo json_encode($data); ?>'
									data-slug="<?php echo esc_attr($slug); ?>">
									<div class="tax-engine-info">
										<div class="tax-icon-box">
											<span class="dashicons dashicons-tag"></span>
										</div>
										<div class="tax-main-meta">
											<div class="tax-title-row">
												<strong><?php echo esc_html($data['plural']); ?></strong>
												<span
													class="tax-type-badge <?php echo $data['hierarchical'] ? 'is-hier' : 'is-flat'; ?>">
													<?php echo $data['hierarchical'] ? 'Category' : 'Tag'; ?>
												</span>
											</div>
											<code><?php echo esc_html($slug); ?></code>

											<div class="tax-attached-to">
												<span class="label">Mapped to:</span>
												<?php foreach ($data['post_types'] as $pt)
													echo '<span class="pt-tag">' . esc_html($pt) . '</span>'; ?>
											</div>
										</div>
									</div>

									<div class="tax-item-actions">
										<form method="post" action="<?php echo admin_url('admin-post.php'); ?>" class="delete-form">
											<input type="hidden" name="action" value="cora_delete_taxonomy">
											<input type="hidden" name="tax_slug" value="<?php echo esc_attr($slug); ?>">
											<?php wp_nonce_field('cora_tax_del_nonce', 'cora_nonce'); ?>
											<button type="submit" class="delete-btn"
												onclick="event.stopPropagation(); return confirm('Delete this taxonomy?')">
												<span class="dashicons dashicons-trash"></span>
											</button>
										</form>
									</div>
								</div>
							<?php endforeach; ?>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>

		<script>
			jQuery(document).ready(function ($) {
				const $slugInput = $('#tax_slug');
				const $isEdit = $('#tax_is_edit');

				// Auto-Slug (Create Mode Only)
				$('#tax_singular').on('keyup change', function () {
					if ($isEdit.val() === "0") {
						let slug = $(this).val().toLowerCase().trim().replace(/\s+/g, '_').replace(/[^a-z0-9_]/g, '');
						$slugInput.val(slug);
					}
				});

				// Edit Hydration
				$('.tax-engine-item').on('click', function () {
					const data = $(this).data('config');
					const slug = $(this).data('slug');

					$('.tax-engine-item').removeClass('active');
					$(this).addClass('active');

					// Switch UI to Edit Mode
					$isEdit.val(1);
					$slugInput.val(slug).attr('readonly', true).css('background', '#f5f5f5');
					$('#tax-slug-hint').text('Slug cannot be changed after creation.');
					$('#tax-submit-btn').text('Update Taxonomy');
					$('#reset-tax-btn').show();
					$('#tax-mode-badge').text('EDIT').css('background', '#3582c4');
					$('#tax-form-card').css('border-top', '3px solid #3582c4');

					// Fill Inputs
					$('#tax_singular').val(data.singular);
					$('#tax_plural').val(data.plural);
					$('#tax_hierarchical').prop('checked', data.hierarchical == 1);

					// Check Post Types
					$('input[name="tax_post_types[]"]').prop('checked', false);
					if (data.post_types) {
						data.post_types.forEach(pt => {
							$('input[value="' + pt + '"]').prop('checked', true);
						});
					}
				});

				$('#reset-tax-btn').on('click', function () { window.location.reload(); });
			});
		</script>
		<?php
	}

	public function handle_save_taxonomy()
	{
		if (!isset($_POST['cora_nonce']) || !wp_verify_nonce($_POST['cora_nonce'], 'cora_tax_nonce'))
			wp_die('Failed');
		if (!current_user_can('manage_options'))
			return;

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
		if (!isset($_POST['cora_nonce']) || !wp_verify_nonce($_POST['cora_nonce'], 'cora_tax_del_nonce'))
			wp_die('Failed');
		$slug = sanitize_text_field($_POST['tax_slug']);
		$taxonomies = get_option($this->option_name, []);
		if (isset($taxonomies[$slug])) {
			unset($taxonomies[$slug]);
			update_option($this->option_name, $taxonomies);
			flush_rewrite_rules();
		}
		wp_redirect(admin_url('admin.php?page=cora-tax'));
		exit;
	}
}