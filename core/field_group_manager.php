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
		add_action('add_meta_boxes', [$this, 'register_meta_boxes']);
		add_action('save_post', [$this, 'save_post_meta_data']);
	}

	/**
	 * Handshake: Attach meta boxes to Post Types
	 */
	public function register_meta_boxes()
	{
		$screen = get_current_screen();
		$groups = get_option($this->option_name, []);
		foreach ($groups as $id => $group) {
			if (isset($group['rule_type']) && $group['rule_type'] === 'post_type' && $group['location'] === $screen->post_type) {
				add_meta_box($id, $group['title'], [$this, 'render_meta_box_html'], $screen->post_type, 'normal', 'high', ['fields' => $group['fields'] ?? []]);
			}
		}
	}

	public function render_meta_box_html($post, $args)
	{
		$fields = $args['args']['fields'] ?? [];
		$values = get_post_meta($post->ID, '_cora_meta_data', true) ?: [];
		wp_nonce_field('cora_meta_save', 'cora_meta_nonce');

		if (empty($fields)) {
			echo '<p style="color:#717680; padding:15px; border:1px dashed #E9EAEC;">No fields defined for this group.</p>';
			return;
		}

		foreach ($fields as $field) {
			$val = $values[$field['name']] ?? ($field['default'] ?? '');
			echo '<div style="margin-bottom:15px;">';
			echo '<label style="display:block; font-weight:600; margin-bottom:5px;">' . esc_html($field['label']) . '</label>';
			echo '<input type="text" name="cora_data[' . esc_attr($field['name']) . ']" value="' . esc_attr($val) . '" style="width:100%;">';
			echo '</div>';
		}
	}

	public function save_post_meta_data($post_id)
	{
		if (!isset($_POST['cora_meta_nonce']) || !wp_verify_nonce($_POST['cora_meta_nonce'], 'cora_meta_save'))
			return;
		if (isset($_POST['cora_data']))
			update_post_meta($post_id, '_cora_meta_data', array_map('sanitize_text_field', $_POST['cora_data']));
	}

	public function render_field_group_page()
	{
		$groups = get_option($this->option_name, []);
		$post_types = get_post_types(['public' => true], 'objects');
		$options_pages = get_option('cora_options_pages', []);
		?>
		<div class="cora-admin-wrapper cora-tax-container">
			<div class="cora-header">
				<h1>Field Groups <span id="group-badge" class="cora-version">PRO</span></h1>
				<div class="cora-actions">
					<button type="button" id="reset-group-btn" class="cora-btn cora-btn-secondary" style="display:none;">Add
						New</button>
					<button type="submit" form="cora-field-group-form" class="cora-btn cora-btn-primary">Save Changes</button>
				</div>
			</div>

			<div class="cora-settings-grid">
				<div class="cora-card">
					<form id="cora-field-group-form" method="post" action="<?php echo admin_url('admin-post.php'); ?>">
						<input type="hidden" name="action" value="cora_save_field_group">
						<input type="hidden" name="group_id" id="group_id" value="">
						<?php wp_nonce_field('cora_group_nonce', 'cora_nonce'); ?>

						<div class="cora-control-group">
							<label>Group Title *</label>
							<input type="text" name="group_title" id="group_title" class="cora-input" required
								placeholder="e.g. Hero Section">
						</div>

						<div class="cora-section-label" style="margin-top:20px;">Location Rules</div>
						<div class="cora-form-row">
							<div class="cora-control-group">
								<label>Category</label>
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

						<div id="fields-payload" style="display:none;"></div>
					</form>
				</div>

				<div class="cora-card">
					<h3>Active Groups</h3>
					<div class="tax-list-wrapper">
						<?php foreach ($groups as $id => $data): ?>
							<div class="tax-engine-item" data-config='<?php echo json_encode($data); ?>'
								data-id="<?php echo $id; ?>">
								<div class="tax-engine-info">
									<strong><?php echo esc_html($data['title']); ?></strong>
									<code><?php echo esc_html($data['location']); ?></code>
								</div>
								<form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
									<input type="hidden" name="action" value="cora_delete_field_group">
									<input type="hidden" name="group_id" value="<?php echo $id; ?>">
									<?php wp_nonce_field('cora_group_del_nonce', 'cora_nonce'); ?>
									<button type="submit" class="delete-btn" onclick="event.stopPropagation();">×</button>
								</form>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>

			<div class="cora-card" style="margin-top:30px; padding:0;">
				<div class="field-factory-header"
					style="padding:20px; border-bottom:1px solid #eee; display:flex; justify-content:space-between; align-items:center;">
					<h3 style="margin:0;">Field Definitions</h3>
					<button type="button" id="add-field-btn" class="cora-btn cora-btn-secondary">+ Add Field</button>
				</div>

				<div id="cora-fields-accordion" class="cora-pro-accordion">
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

				function createAccordionField(index, data = {}) {
					return `
				<div class="accordion-item" data-index="${index}">
					<div class="accordion-header">
						<span class="dashicons dashicons-menu handle"></span>
						<span class="field-label-text">${data.label || '(no label)'}</span>
						<span class="field-type-badge">${data.type || 'text'}</span>
						<div class="header-actions">
							<span class="dashicons dashicons-arrow-down-alt2 toggle-acc"></span>
							<span class="dashicons dashicons-trash delete-acc"></span>
						</div>
					</div>
					<div class="accordion-body" style="display:none;">
						<div class="cora-form-row">
							<div class="cora-control-group">
								<label>Field Label</label>
								<input type="text" class="cora-input f-label" value="${data.label || ''}" placeholder="e.g. Phone Number">
							</div>
							<div class="cora-control-group">
								<label>Field Name (Slug)</label>
								<input type="text" class="cora-input f-name" value="${data.name || ''}" readonly>
							</div>
						</div>
						<div class="cora-form-row">
							<div class="cora-control-group">
								<label>Field Type</label>
								<select class="cora-input f-type">
									<option value="text" ${data.type === 'text' ? 'selected' : ''}>Text</option>
									<option value="textarea" ${data.type === 'textarea' ? 'selected' : ''}>Textarea</option>
									<option value="image" ${data.type === 'image' ? 'selected' : ''}>Image</option>
								</select>
							</div>
							<div class="cora-control-group">
								<label>Default Value</label>
								<input type="text" class="cora-input f-default" value="${data.default || ''}">
							</div>
						</div>
					</div>
				</div>`;
				}

				$('#add-field-btn').on('click', function () {
					$('#cora-fields-accordion').append(createAccordionField(fieldIndex));
					fieldIndex++;
				});

				$(document).on('click', '.toggle-acc', function () {
					$(this).closest('.accordion-item').find('.accordion-body').slideToggle(200);
				});

				$(document).on('keyup', '.f-label', function () {
					const label = $(this).val();
					const slug = label.toLowerCase().replace(/ /g, '_').replace(/[^\w-]+/g, '');
					$(this).closest('.accordion-item').find('.field-label-text').text(label || '(no label)');
					$(this).closest('.accordion-item').find('.f-name').val(slug);
				});

				// Map UI to hidden form before submit
				$('#cora-field-group-form').on('submit', function () {
					let html = '';
					$('.accordion-item').each(function (i) {
						const label = $(this).find('.f-label').val();
						const name = $(this).find('.f-name').val();
						const type = $(this).find('.f-type').val();
						const def = $(this).find('.f-default').val();
						html += `<input type="hidden" name="fields[${i}][label]" value="${label}">`;
						html += `<input type="hidden" name="fields[${i}][name]" value="${name}">`;
						html += `<input type="hidden" name="fields[${i}][type]" value="${type}">`;
						html += `<input type="hidden" name="fields[${i}][default]" value="${def}">`;
					});
					$('#fields-payload').html(html);
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
							$('#cora-fields-accordion').append(createAccordionField(idx, f));
							fieldIndex = Math.max(fieldIndex, parseInt(idx) + 1);
						});
					}
					$('#reset-group-btn').show();
				});
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