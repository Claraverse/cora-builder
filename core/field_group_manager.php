<?php
namespace Cora_Builder\Core;

if (!defined('ABSPATH'))
	exit;

class Field_Group_Manager
{
	private $option_name = 'cora_field_groups';

	public function __construct()
	{
		add_action('admin_post_cora_save_field_group', [$this, 'handle_save_group']);
		add_action('admin_post_cora_delete_field_group', [$this, 'handle_delete_group']);

		// Handshake Engine: Attach fields to appropriate locations
		add_action('add_meta_boxes', [$this, 'register_meta_boxes']);
		add_action('save_post', [$this, 'save_post_meta_data']);
	}

	/**
	 * PRO ENGINE: Location Discovery
	 */
	public function register_meta_boxes()
	{
		$screen = get_current_screen();
		$groups = get_option($this->option_name, []);

		foreach ($groups as $id => $group) {
			if (isset($group['rule_type']) && $group['rule_type'] === 'post_type' && $group['location'] === $screen->post_type) {
				add_meta_box(
					$id,
					$group['title'],
					[$this, 'render_meta_box_content'],
					$screen->post_type,
					'normal',
					'high',
					['fields' => $group['fields'] ?? []]
				);
			}
		}
	}

	/**
	 * PRO RENDERER: Modular Field Display
	 */
	public function render_meta_box_content($post, $args)
	{
		$fields = $args['args']['fields'] ?? [];
		$values = get_post_meta($post->ID, '_cora_meta_data', true) ?: [];
		wp_nonce_field('cora_meta_save', 'cora_meta_nonce');

		if (empty($fields)) {
			echo '<p class="cora-empty-note">Build your fields in the Field Group editor to see them here.</p>';
			return;
		}

		foreach ($fields as $field) {
			$val = $values[$field['name']] ?? ($field['default'] ?? '');
			echo '<div class="cora-field-instance" style="margin-bottom:20px;">';
			echo '<label style="display:block; font-weight:600; margin-bottom:6px;">' . esc_html($field['label']) . '</label>';

			// Dynamic Control Switching
			if ($field['type'] === 'textarea') {
				echo '<textarea name="cora_data[' . esc_attr($field['name']) . ']" rows="4" style="width:100%;" placeholder="' . esc_attr($field['placeholder'] ?? '') . '">' . esc_textarea($val) . '</textarea>';
			} else {
				echo '<input type="text" name="cora_data[' . esc_attr($field['name']) . ']" value="' . esc_attr($val) . '" style="width:100%;" placeholder="' . esc_attr($field['placeholder'] ?? '') . '">';
			}

			if (!empty($field['instructions'])) {
				echo '<p style="font-size:11px; color:#667085; margin-top:4px;">' . esc_html($field['instructions']) . '</p>';
			}
			echo '</div>';
		}
	}

	public function save_post_meta_data($post_id)
	{
		if (!isset($_POST['cora_meta_nonce']) || !wp_verify_nonce($_POST['cora_meta_nonce'], 'cora_meta_save'))
			return;
		if (isset($_POST['cora_data'])) {
			update_post_meta($post_id, '_cora_meta_data', array_map('sanitize_text_field', $_POST['cora_data']));
		}
	}

	/**
	 * PRO BUILDER: Accordion-Style Factory
	 */
	public function render_field_group_page()
	{
		$groups = get_option($this->option_name, []);
		$post_types = get_post_types(['public' => true], 'objects');
		$options_pages = get_option('cora_options_pages', []);
		?>
		<div class="cora-admin-wrapper cora-tax-container">
			<div class="cora-header">
				<div class="cora-logo">
					<h1>Field Groups <span class="cora-version">PRO</span></h1>
				</div>
				<div class="cora-actions">
					<button type="button" id="reset-group-btn" class="cora-btn cora-btn-secondary" style="display:none;">Add
						New</button>
					<button type="submit" form="cora-field-group-form" class="cora-btn cora-btn-primary">Save Changes</button>
				</div>
			</div>

			<div class="cora-settings-grid">
				<div class="cora-card">
					<div class="cora-section-label">Group Configuration</div>
					<form id="cora-field-group-form" method="post" action="<?php echo admin_url('admin-post.php'); ?>">
						<input type="hidden" name="action" value="cora_save_field_group">
						<input type="hidden" name="group_id" id="group_id" value="">
						<?php wp_nonce_field('cora_group_nonce', 'cora_nonce'); ?>

						<div class="cora-control-group">
							<label>Group Title *</label>
							<input type="text" name="group_title" id="group_title" class="cora-input" required
								placeholder="e.g. Hero Section Settings">
						</div>

						<div class="cora-section-label" style="margin-top:20px;">Visibility Logic</div>
						<div class="cora-form-row">
							<div class="cora-control-group">
								<label>Location Category</label>
								<select name="rule_type" id="rule_type" class="cora-input">
									<option value="post_type">Post Type</option>
									<option value="options_page">Options Page</option>
								</select>
							</div>
							<div class="cora-control-group">
								<label>is equal to</label>
								<select name="group_location" id="group_location" class="cora-input"></select>
							</div>
						</div>

						<div id="fields-json-payload" style="display:none;"></div>
					</form>
				</div>

				<div class="cora-card">
					<h3>Deployed Groups</h3>
					<div class="tax-list-wrapper">
						<?php foreach ($groups as $id => $data): ?>
							<div class="tax-engine-item" data-config='<?php echo json_encode($data); ?>'
								data-id="<?php echo esc_attr($id); ?>">
								<div class="tax-engine-info">
									<div class="tax-icon-box"><span class="dashicons dashicons-layout"></span></div>
									<div>
										<strong><?php echo esc_html($data['title']); ?></strong>
										<code><?php echo esc_html($data['location']); ?></code>
									</div>
								</div>
								<button type="button" class="delete-btn">×</button>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>

			<div class="cora-card field-factory-pro" style="margin-top:30px; padding:0;">
				<div class="factory-header"
					style="padding:20px; border-bottom:1px solid #E9EAEC; display:flex; justify-content:space-between; align-items:center;">
					<h3 style="margin:0;">Field Definitions</h3>
					<button type="button" id="add-pro-field" class="cora-btn cora-btn-secondary">+ Add Field</button>
				</div>

				<div id="cora-fields-accordion" class="cora-accordion">
				</div>
			</div>
		</div>

		<script>
			jQuery(document).ready(function ($) {
				const postTypes = { <?php foreach ($post_types as $pt)
					echo "'{$pt->name}': '{$pt->label}',"; ?> };
				const optionsPages = { <?php foreach ($options_pages as $slug => $data)
					echo "'{$slug}': '{$data['title']}',"; ?> };
				let fieldIndex = 0;

				function createFieldAccordion(index, data = {}) {
					return `
				<div class="field-item-acc" data-index="${index}">
					<div class="acc-header" style="padding:15px 20px; background:#F9FAFB; border-bottom:1px solid #E9EAEC; display:flex; align-items:center; cursor:pointer;">
						<span class="dashicons dashicons-menu" style="color:#D5D7DA; margin-right:15px;"></span>
						<strong class="label-preview" style="flex-grow:1;">${data.label || '(no label)'}</strong>
						<span class="type-badge" style="background:#fff; border:1px solid #E9EAEC; padding:2px 8px; border-radius:4px; font-size:11px; text-transform:uppercase;">${data.type || 'text'}</span>
						<span class="dashicons dashicons-arrow-down-alt2 toggle-icon" style="margin-left:15px; color:#98A2B3;"></span>
						<span class="dashicons dashicons-no-alt delete-field" style="margin-left:15px; color:#F04438;"></span>
					</div>
					<div class="acc-content" style="display:none; padding:25px; background:#fff; border-bottom:1px solid #E9EAEC;">
						<div class="cora-form-row">
							<div class="cora-control-group"><label>Field Label</label><input type="text" class="cora-input f-label" value="${data.label || ''}"></div>
							<div class="cora-control-group"><label>Field Name (Slug)</label><input type="text" class="cora-input f-name" value="${data.name || ''}" readonly></div>
						</div>
						<div class="cora-form-row">
							<div class="cora-control-group">
								<label>Field Type</label>
								<select class="cora-input f-type">
									<option value="text" ${data.type === 'text' ? 'selected' : ''}>Text</option>
									<option value="textarea" ${data.type === 'textarea' ? 'selected' : ''}>Textarea</option>
									<option value="image" ${data.type === 'image' ? 'selected' : ''}>Image (URL)</option>
								</select>
							</div>
							<div class="cora-control-group"><label>Placeholder</label><input type="text" class="cora-input f-placeholder" value="${data.placeholder || ''}"></div>
						</div>
						<div class="cora-control-group"><label>Instructions</label><input type="text" class="cora-input f-instr" value="${data.instructions || ''}"></div>
					</div>
				</div>`;
				}

				$('#add-pro-field').on('click', function () {
					$('#cora-fields-accordion').append(createFieldAccordion(fieldIndex));
					fieldIndex++;
				});

				$(document).on('click', '.acc-header', function (e) {
					if ($(e.target).hasClass('delete-field')) {
						$(this).closest('.field-item-acc').remove();
						return;
					}
					$(this).next('.acc-content').slideToggle(200);
					$(this).find('.toggle-icon').toggleClass('dashicons-arrow-up-alt2 dashicons-arrow-down-alt2');
				});

				$(document).on('keyup', '.f-label', function () {
					const label = $(this).val();
					const slug = label.toLowerCase().replace(/ /g, '_').replace(/[^\w-]+/g, '');
					$(this).closest('.field-item-acc').find('.label-preview').text(label || '(no label)');
					$(this).closest('.field-item-acc').find('.f-name').val(slug);
				});

				$('#cora-field-group-form').on('submit', function () {
					let html = '';
					$('.field-item-acc').each(function (i) {
						html += `<input type="hidden" name="fields[${i}][label]" value="${$(this).find('.f-label').val()}">`;
						html += `<input type="hidden" name="fields[${i}][name]" value="${$(this).find('.f-name').val()}">`;
						html += `<input type="hidden" name="fields[${i}][type]" value="${$(this).find('.f-type').val()}">`;
						html += `<input type="hidden" name="fields[${i}][placeholder]" value="${$(this).find('.f-placeholder').val()}">`;
						html += `<input type="hidden" name="fields[${i}][instructions]" value="${$(this).find('.f-instr').val()}">`;
					});
					$('#fields-json-payload').html(html);
				});

				function updateLocationChoices(type, selectedValue = null) {
					const $select = $('#group_location'); $select.empty();
					const data = (type === 'options_page') ? optionsPages : postTypes;
					$.each(data, function (k, v) {
						let opt = $('<option></option>').attr('value', k).text(v);
						if (selectedValue && k === selectedValue) opt.attr('selected', 'selected');
						$select.append(opt);
					});
				}

				$('#rule_type').on('change', function () { updateLocationChoices($(this).val()); });
				updateLocationChoices('post_type');

				$(document).on('click', '.tax-engine-item', function () {
					const data = $(this).data('config');
					$('#group_id').val($(this).data('id'));
					$('#group_title').val(data.title);
					$('#rule_type').val(data.rule_type || 'post_type').trigger('change');
					$('#group_location').val(data.location);
					$('#cora-fields-accordion').empty();
					if (data.fields) {
						$.each(data.fields, function (idx, f) {
							$('#cora-fields-accordion').append(createFieldAccordion(idx, f));
							fieldIndex = Math.max(fieldIndex, parseInt(idx) + 1);
						});
					}
					$('#reset-group-btn').show();
				});

				$('#reset-group-btn').on('click', function () { window.location.reload(); });
			});
		</script>
		<?php
	}

	public function handle_save_group()
	{
		if (!isset($_POST['cora_nonce']) || !wp_verify_nonce($_POST['cora_nonce'], 'cora_group_nonce'))
			wp_die('Security error');
		$groups = get_option($this->option_name, []);
		$id = !empty($_POST['group_id']) ? sanitize_text_field($_POST['group_id']) : uniqid('group_');
		$groups[$id] = [
			'title' => sanitize_text_field($_POST['group_title']),
			'rule_type' => sanitize_text_field($_POST['rule_type']),
			'location' => sanitize_text_field($_POST['group_location']),
			'fields' => $_POST['fields'] ?? []
		];
		update_option($this->option_name, $groups);
		wp_redirect(admin_url('admin.php?page=cora-fieldgroups'));
		exit;
	}

	public function handle_delete_group()
	{
		$groups = get_option($this->option_name, []);
		unset($groups[$_POST['group_id']]);
		update_option($this->option_name, $groups);
		wp_redirect(admin_url('admin.php?page=cora-fieldgroups'));
		exit;
	}
}