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
		add_action('admin_init', [$this, 'register_options_page_fields']);
	}

	/**
	 * Logic for Options Page Integration
	 */
	public function register_options_page_fields()
	{
		$groups = get_option($this->option_name, []);
		foreach ($groups as $id => $group) {
			if (isset($group['rule_type']) && $group['rule_type'] === 'options_page') {
				$page_slug = $group['location'];
				add_settings_section($id . '_section', $group['title'], '__return_false', $page_slug);
				foreach ($group['fields'] as $field) {
					add_settings_field($field['name'], $field['label'], [$this, 'render_options_field_callback'], $page_slug, $id . '_section', ['field' => $field, 'page_slug' => $page_slug]);
				}
			}
		}
	}

	public function render_options_field_callback($args)
	{
		$field = $args['field'];
		$page_slug = $args['page_slug'];
		$options = get_option($page_slug . '_data', []);
		$value = $options[$field['name']] ?? '';
		$name = "{$page_slug}_data[{$field['name']}]";
		echo '<div class="cora-field-row">';
		if ($field['type'] === 'textarea') {
			echo '<textarea name="' . esc_attr($name) . '" rows="4" style="width:100%;">' . esc_textarea($value) . '</textarea>';
		} else {
			echo '<input type="text" name="' . esc_attr($name) . '" value="' . esc_attr($value) . '" style="width:100%;">';
		}
		echo '</div>';
	}

	public function register_meta_boxes()
	{
		$screen = get_current_screen();
		$groups = get_option($this->option_name, []);
		foreach ($groups as $id => $group) {
			if (isset($group['rule_type']) && $group['rule_type'] === 'post_type' && $group['location'] === $screen->post_type) {
				add_meta_box($id, $group['title'], [$this, 'render_meta_box_content'], $screen->post_type, 'normal', 'high', ['fields' => $group['fields'] ?? []]);
			}
		}
	}

	/**
	 * PRO RENDERER: Modular Field Display
	 * Updated to fix Fatal Error causing white screens
	 */
	public function render_meta_box_content($post, $args)
{
    // Ensure we are pulling the fields array correctly from the $args passed by add_meta_box
    $fields = $args['args']['fields'] ?? [];
    $values = get_post_meta($post->ID, '_cora_meta_data', true) ?: [];
    
    wp_nonce_field('cora_meta_save', 'cora_meta_nonce');

    if (empty($fields)) {
        echo '<p class="cora-empty-note">No fields defined for this group.</p>';
        return;
    }

    // Now that the class is loaded by Plugin_Loader, this will no longer crash
    $renderer = new Field_Renderer();
    $renderer->render_fields($fields, $values);
}
	// public function render_meta_box_content($post, $args)
	// {
	// 	// Ensure we have a valid post object before proceeding
	// 	if (!$post instanceof \WP_Post) {
	// 		return;
	// 	}

	// 	$fields = $args['args']['fields'] ?? [];
	// 	// Use the stored meta key correctly
	// 	$values = get_post_meta($post->ID, '_cora_meta_data', true) ?: [];

	// 	wp_nonce_field('cora_meta_save', 'cora_meta_nonce');

	// 	if (empty($fields)) {
	// 		echo '<p class="cora-empty-note">Build your fields in the Field Group editor.</p>';
	// 		return;
	// 	}

	// 	// Instantiate the renderer within the same namespace
	// 	$renderer = new Field_Renderer();
	// 	$renderer->render_fields($fields, $values);
	// }

	public function save_post_meta_data($post_id)
	{
		if (!isset($_POST['cora_meta_nonce']) || !wp_verify_nonce($_POST['cora_meta_nonce'], 'cora_meta_save'))
			return;
		if (isset($_POST['cora_meta']))
			update_post_meta($post_id, '_cora_meta_data', array_map('sanitize_text_field', $_POST['cora_meta']));
	}

	/**
	 * STUDIO UI: Sidebar + Main Content Layout
	 */
	public function render_field_group_page()
	{
		$groups = get_option($this->option_name, []);
		$post_types = get_post_types(['public' => true], 'objects');
		$options_pages = get_option('cora_options_pages', []);
		?>
		<div class="cora-admin-wrapper cora-field-studio">

			<aside class="cora-studio-sidebar">
				<div class="cora-sidebar-header">
					<h2>Field Groups</h2>
					<button type="button" id="reset-group-btn" class="cora-btn cora-btn-primary" style="width:100%;">+ Add
						Group</button>
				</div>
				<div class="cora-sidebar-list">
					<?php foreach ($groups as $id => $data): ?>
						<div class="cora-group-item tax-engine-item" data-config='<?php echo json_encode($data); ?>'
							data-id="<?php echo esc_attr($id); ?>">
							<span class="dashicons dashicons-layout"></span>
							<div class="info">
								<strong><?php echo esc_html($data['title']); ?></strong>
								<small><?php echo esc_html($data['location']); ?></small>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</aside>

			<main class="cora-studio-main">
				<form id="cora-field-group-form" method="post" action="<?php echo admin_url('admin-post.php'); ?>">
					<div class="cora-main-header">
						<h2 id="current-group-label">New Field Group</h2>
						<button type="submit" class="cora-btn cora-btn-primary">Deploy Group</button>
					</div>

					<div class="cora-main-content">
						<input type="hidden" name="action" value="cora_save_field_group">
						<input type="hidden" name="group_id" id="group_id" value="">
						<?php wp_nonce_field('cora_group_nonce', 'cora_nonce'); ?>

						<div class="cora-control-group">
							<label>Group Title</label>
							<input type="text" name="group_title" id="group_title" class="cora-input" required
								placeholder="Hero Settings...">
						</div>

						<div class="cora-form-row">
							<div class="cora-control-group">
								<label>Location</label>
								<select name="rule_type" id="rule_type" class="cora-input">
									<option value="post_type">Post Type</option>
									<option value="options_page">Options Page</option>
								</select>
							</div>
							<div class="cora-control-group">
								<label>Target</label>
								<select name="group_location" id="group_location" class="cora-input"></select>
							</div>
						</div>

						<div class="field-factory-pro" style="margin-top:40px;">
							<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
								<h3 style="margin:0;">Fields</h3>
								<button type="button" id="add-pro-field" class="cora-btn cora-btn-secondary">+ Add
									Field</button>
							</div>
							<div id="cora-fields-accordion" class="cora-accordion"></div>
						</div>
						<div id="fields-json-payload" style="display:none;"></div>
					</div>
				</form>
			</main>
		</div>

		<script>
			jQuery(document).ready(function ($) {
				const postTypes = {
					<?php foreach ($post_types as $pt)
						echo "'{$pt->name}': '{$pt->label}',"; ?> };
				const optionsPages = {
					<?php foreach ($options_pages as $slug => $data)
						echo "'{$slug}': '{$data['title']}',"; ?> };
				let fieldIndex = 0;

				function createFieldAccordion(index, data = {}) {
					return `
					<div class="field-item-acc" data-index="${index}">
						<div class="acc-header" style="cursor:pointer; display:flex; align-items:center;">
							<span class="dashicons dashicons-menu" style="margin-right:10px; color:#ccc;"></span>
							<strong class="label-preview" style="flex-grow:1;">${data.label || '(no label)'}</strong>
							<span class="type-badge">${data.type || 'text'}</span>
							<span class="dashicons dashicons-arrow-down-alt2 toggle-icon" style="margin-left:10px;"></span>
							<span class="dashicons dashicons-no-alt delete-field" style="margin-left:10px; color:red;"></span>
						</div>
						<div class="acc-content" style="display:none; padding:20px; border-top:1px solid #eee;">
							<div class="cora-form-row">
								<div class="cora-control-group"><label>Label</label><input type="text" class="cora-input f-label" value="${data.label || ''}"></div>
								<div class="cora-control-group"><label>Slug</label><input type="text" class="cora-input f-name" value="${data.name || ''}" readonly></div>
							</div>
							<div class="cora-form-row">
								<div class="cora-control-group">
									<label>Type</label>
									<select class="cora-input f-type">
										<option value="text" ${data.type === 'text' ? 'selected' : ''}>Text</option>
										<option value="textarea" ${data.type === 'textarea' ? 'selected' : ''}>Textarea</option>
										<option value="image" ${data.type === 'image' ? 'selected' : ''}>Image (URL)</option>
									</select>
								</div>
								<div class="cora-control-group"><label>Placeholder</label><input type="text" class="cora-input f-placeholder" value="${data.placeholder || ''}"></div>
							</div>
						</div>
					</div>`;
				}

				$('#add-pro-field').on('click', function () {
					$('#cora-fields-accordion').append(createFieldAccordion(fieldIndex++));
				});

				$(document).on('click', '.acc-header', function (e) {
					if ($(e.target).hasClass('delete-field')) { $(this).closest('.field-item-acc').remove(); return; }
					$(this).next('.acc-content').slideToggle(200);
				});

				$(document).on('keyup', '.f-label', function () {
					const label = $(this).val();
					$(this).closest('.field-item-acc').find('.label-preview').text(label || '(no label)');
					$(this).closest('.field-item-acc').find('.f-name').val(label.toLowerCase().replace(/ /g, '_'));
				});

				$('#cora-field-group-form').on('submit', function () {
					let html = '';
					$('.field-item-acc').each(function (i) {
						html += `<input type="hidden" name="fields[${i}][label]" value="${$(this).find('.f-label').val()}">`;
						html += `<input type="hidden" name="fields[${i}][name]" value="${$(this).find('.f-name').val()}">`;
						html += `<input type="hidden" name="fields[${i}][type]" value="${$(this).find('.f-type').val()}">`;
					});
					$('#fields-json-payload').html(html);
				});

				function updateTargets(type, selected = null) {
					const $select = $('#group_location').empty();
					const data = (type === 'options_page') ? optionsPages : postTypes;
					$.each(data, (k, v) => {
						let opt = $('<option>').val(k).text(v);
						if (k === selected) opt.prop('selected', true);
						$select.append(opt);
					});
				}

				$('#rule_type').on('change', function () { updateTargets($(this).val()); });
				updateTargets('post_type');

				$('.cora-group-item').on('click', function () {
					const data = $(this).data('config');
					$('.cora-group-item').removeClass('active');
					$(this).addClass('active');
					$('#group_id').val($(this).data('id'));
					$('#group_title').val(data.title);
					$('#current-group-label').text('Editing: ' + data.title);
					$('#rule_type').val(data.rule_type || 'post_type').trigger('change');
					$('#group_location').val(data.location);
					$('#cora-fields-accordion').empty();
					if (data.fields) $.each(data.fields, (idx, f) => { $('#cora-fields-accordion').append(createFieldAccordion(idx, f)); fieldIndex = Math.max(fieldIndex, parseInt(idx) + 1); });
				});

				$('#reset-group-btn').on('click', () => { window.location.reload(); });
			});
		</script>
		<?php
	}

	public function handle_save_group()
	{
		if (!wp_verify_nonce($_POST['cora_nonce'], 'cora_group_nonce'))
			wp_die('Error');
		$groups = get_option($this->option_name, []);
		$id = !empty($_POST['group_id']) ? $_POST['group_id'] : uniqid('group_');
		$groups[$id] = ['title' => $_POST['group_title'], 'rule_type' => $_POST['rule_type'], 'location' => $_POST['group_location'], 'fields' => $_POST['fields'] ?? []];
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