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

	public function register_options_page_fields()
	{
		$groups = get_option($this->option_name, []);
		foreach ($groups as $id => $group) {
			if (isset($group['rule_type']) && $group['rule_type'] === 'options_page') {
				$page_slug = $group['location'];
				register_setting($page_slug . '_group', $page_slug . '_data');
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
		(new Field_Renderer())->render_single_field($field, $value, $name);
	}

	public function register_meta_boxes()
	{
		$screen = get_current_screen();
		if (!$screen)
			return;
		$groups = get_option($this->option_name, []);
		foreach ($groups as $id => $group) {
			if (isset($group['rule_type']) && $group['rule_type'] === 'post_type' && $group['location'] === $screen->post_type) {
				add_meta_box($id, $group['title'], [$this, 'render_meta_box_content'], $screen->post_type, 'normal', 'high', ['fields' => $group['fields'] ?? []]);
			}
		}
	}

	public function render_meta_box_content($post, $args)
	{
		$fields = $args['args']['fields'] ?? [];
		$values = get_post_meta($post->ID, '_cora_meta_data', true) ?: [];
		wp_nonce_field('cora_meta_save', 'cora_meta_nonce');
		(new Field_Renderer())->render_fields($fields, $values);
	}

	public function save_post_meta_data($post_id)
	{
		if (!isset($_POST['cora_meta_nonce']) || !wp_verify_nonce($_POST['cora_meta_nonce'], 'cora_meta_save'))
			return;

		if (isset($_POST['cora_meta'])) {
			// FIX: Use map_deep to safely sanitize strings within nested arrays (Repeaters/Checkboxes)
			$sanitized_data = map_deep($_POST['cora_meta'], 'sanitize_text_field');
			update_post_meta($post_id, '_cora_meta_data', $sanitized_data);
		}
	}

	public function render_field_group_page()
	{
		$groups = get_option($this->option_name, []);
		$post_types = get_post_types(['public' => true], 'objects');
		$options_pages = get_option('cora_options_pages', []);
		?>
		<div class="cora-admin-wrapper cora-field-studio">
			<aside class="cora-studio-sidebar">
				<div class="cora-sidebar-header">
					<h2>Field Groups</h2><button type="button" id="reset-group-btn" class="cora-btn cora-btn-primary"
						style="width:100%;">+ Add Group</button>
				</div>
				<div class="cora-sidebar-list">
					<?php foreach ($groups as $id => $data): ?>
						<div class="cora-group-item tax-engine-item" data-config='<?php echo esc_attr(json_encode($data)); ?>'
							data-id="<?php echo esc_attr($id); ?>">
							<span class="dashicons dashicons-layout"></span>
							<div class="info">
								<strong><?php echo esc_html($data['title']); ?></strong><small><?php echo esc_html($data['location']); ?></small>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</aside>

			<main class="cora-studio-main">
				<form id="cora-field-group-form" method="post" action="<?php echo admin_url('admin-post.php'); ?>">
					<div class="cora-main-header">
						<h2 id="current-group-label">New Field Group</h2><button type="submit"
							class="cora-btn cora-btn-primary">Deploy Group</button>
					</div>
					<div class="cora-main-content">
						<input type="hidden" name="action" value="cora_save_field_group">
						<input type="hidden" name="group_id" id="group_id" value="">
						<?php wp_nonce_field('cora_group_nonce', 'cora_nonce'); ?>
						<div class="cora-control-group"><label>Group Title</label><input type="text" name="group_title"
								id="group_title" class="cora-input" required placeholder="Project Details..."></div>
						<div class="cora-form-row">
							<div class="cora-control-group"><label>Location</label><select name="rule_type" id="rule_type"
									class="cora-input">
									<option value="post_type">Post Type</option>
									<option value="options_page">Options Page</option>
								</select></div>
							<div class="cora-control-group"><label>Target</label><select name="group_location"
									id="group_location" class="cora-input"></select></div>
						</div>
						<div class="field-factory-pro" style="margin-top:40px;">
							<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
								<h3>Fields</h3><button type="button" id="add-pro-field" class="cora-btn cora-btn-secondary">+
									Add Field</button>
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
					const type = data.type || 'text';
					const isChoice = ['select', 'checkbox', 'radio', 'button_group'].includes(type);
					const isRepeater = type === 'repeater';
					// Container types that require sub-field definitions
					const isContainer = ['repeater', 'flexible_content', 'group', 'accordion', 'tab'].includes(type);
					const isMessage = type === 'message';
					return `
					<div class="field-item-acc" data-index="${index}">
						<div class="acc-header" style="cursor:pointer; display:flex; align-items:center;">
							<span class="dashicons dashicons-menu" style="margin-right:10px;"></span>
							<strong class="label-preview" style="flex-grow:1;">${data.label || '(no label)'}</strong>
							<span class="type-badge">${type}</span>
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
									<select class="f-type cora-input">
										<optgroup label="Basic">
											<option value="text" ${type === 'text' ? 'selected' : ''}>Text</option>
											<option value="textarea" ${type === 'textarea' ? 'selected' : ''}>Textarea</option>
											<option value="number" ${type === 'number' ? 'selected' : ''}>Number</option>
											<option value="true_false" ${type === 'true_false' ? 'selected' : ''}>True / False</option>
										</optgroup>
										<optgroup label="Advanced">
											<option value="google_map" ${type === 'google_map' ? 'selected' : ''}>Google Map</option>
											<option value="date_picker" ${type === 'date_picker' ? 'selected' : ''}>Date Picker</option>
											<option value="date_time_picker" ${type === 'date_time_picker' ? 'selected' : ''}>Date Time Picker</option>
											<option value="time_picker" ${type === 'time_picker' ? 'selected' : ''}>Time Picker</option>
											<option value="color_picker" ${type === 'color_picker' ? 'selected' : ''}>Color Picker</option>
											<option value="icon_picker" ${type === 'icon_picker' ? 'selected' : ''}>Icon Picker</option>
										</optgroup>
										<optgroup label="Choice">
											<option value="select" ${type === 'select' ? 'selected' : ''}>Select</option>
											<option value="checkbox" ${type === 'checkbox' ? 'selected' : ''}>Checkbox</option>
											<option value="radio" ${type === 'radio' ? 'selected' : ''}>Radio Button</option>
											<option value="button_group" ${type === 'button_group' ? 'selected' : ''}>Button Group</option>
										</optgroup>
										<optgroup label="Media">
											<option value="image" ${type === 'image' ? 'selected' : ''}>Image</option>
											<option value="file" ${type === 'file' ? 'selected' : ''}>File</option>
											<option value="gallery" ${type === 'gallery' ? 'selected' : ''}>Gallery</option>
											<option value="wysiwyg" ${type === 'wysiwyg' ? 'selected' : ''}>WYSIWYG Editor</option>
											<option value="oembed" ${type === 'oembed' ? 'selected' : ''}>oEmbed</option>
										</optgroup>
										<optgroup label="Relational">
											<option value="post_object" ${type === 'post_object' ? 'selected' : ''}>Post Object</option>
											<option value="page_link" ${type === 'page_link' ? 'selected' : ''}>Page Link</option>
											<option value="relationship" ${type === 'relationship' ? 'selected' : ''}>Relationship</option>
											<option value="taxonomy" ${type === 'taxonomy' ? 'selected' : ''}>Taxonomy</option>
											<option value="user" ${type === 'user' ? 'selected' : ''}>User</option>
										</optgroup>
										<optgroup label="Layout">
											<option value="message" ${type === 'message' ? 'selected' : ''}>Message</option>
											<option value="accordion" ${type === 'accordion' ? 'selected' : ''}>Accordion</option>
											<option value="tab" ${type === 'tab' ? 'selected' : ''}>Tab</option>
											<option value="group" ${type === 'group' ? 'selected' : ''}>Group</option>
											<option value="clone" ${type === 'clone' ? 'selected' : ''}>Clone</option>
											<option value="flexible_content" ${type === 'flexible_content' ? 'selected' : ''}>Flexible Content</option>
											<option value="repeater" ${type === 'repeater' ? 'selected' : ''}>Repeater</option>
										</optgroup>
									</select>
								</div>
								<div class="cora-control-group"><label>Placeholder</label><input type="text" class="cora-input f-placeholder" value="${data.placeholder || ''}"></div>
							</div>
							
							<div class="choice-config" style="margin-top:15px; ${isChoice ? '' : 'display:none;'}">
								<label><strong>Choices (One per line)</strong></label>
								<textarea class="f-choices cora-input" style="height:80px;" placeholder="value : Label">${data.choices || ''}</textarea>
							</div>

							<div class="repeater-config" style="margin-top:15px; ${isRepeater ? '' : 'display:none;'}">
								<label><strong>Sub-fields JSON Definition</strong></label>
								<textarea class="f-subfields cora-input" style="font-family:monospace; height:80px;" placeholder='[{"label":"Title", "name":"title", "type":"text"}]'>${data.subfields_json || ''}</textarea>
							</div>
						</div>
					</div>`;
				}

				$('#add-pro-field').on('click', () => $('#cora-fields-accordion').append(createFieldAccordion(fieldIndex++)));

				$(document).on('change', '.f-type', function () {
					const val = $(this).val();
					const $acc = $(this).closest('.acc-content');
					$acc.find('.choice-config').toggle(['select', 'checkbox', 'radio', 'button_group'].includes(val));
					$acc.find('.repeater-config').toggle(val === 'repeater' || val === 'flexible_content');
				});

				$(document).on('click', '.acc-header', function (e) { if (!$(e.target).hasClass('delete-field')) $(this).next('.acc-content').slideToggle(200); });
				$(document).on('click', '.delete-field', function () { $(this).closest('.field-item-acc').remove(); });

				$(document).on('keyup', '.f-label', function () {
					const label = $(this).val(); $(this).closest('.field-item-acc').find('.label-preview').text(label || '(no label)');
					$(this).closest('.field-item-acc').find('.f-name').val(label.toLowerCase().trim().replace(/\s+/g, '_').replace(/[^a-z0-9_]/g, ''));
				});

				$('#cora-field-group-form').on('submit', function () {
					let html = '';
					$('.field-item-acc').each(function (i) {
						html += `<input type="hidden" name="fields[${i}][label]" value="${$(this).find('.f-label').val()}">`;
						html += `<input type="hidden" name="fields[${i}][name]" value="${$(this).find('.f-name').val()}">`;
						html += `<input type="hidden" name="fields[${i}][type]" value="${$(this).find('.f-type').val()}">`;
						html += `<input type="hidden" name="fields[${i}][placeholder]" value="${$(this).find('.f-placeholder').val()}">`;
						html += `<input type="hidden" name="fields[${i}][choices]" value="${$(this).find('.f-choices').val() || ''}">`;
						html += `<input type="hidden" name="fields[${i}][subfields_json]" value='${$(this).find('.f-subfields').val() || ''}'>`;
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

				$(document).on('click', '.cora-group-item', function () {
					const data = $(this).data('config'); $('.cora-group-item').removeClass('active'); $(this).addClass('active');
					$('#group_id').val($(this).data('id')); $('#group_title').val(data.title); $('#current-group-label').text('Editing: ' + data.title);
					$('#rule_type').val(data.rule_type || 'post_type').trigger('change'); $('#group_location').val(data.location);
					$('#cora-fields-accordion').empty();
					if (data.fields) $.each(data.fields, (idx, f) => { $('#cora-fields-accordion').append(createFieldAccordion(idx, f)); fieldIndex = Math.max(fieldIndex, idx + 1); });
				});
				$('#reset-group-btn').on('click', () => window.location.reload());
			});
		</script>
		<?php
	}

	public function handle_save_group()
	{
		if (!wp_verify_nonce($_POST['cora_nonce'], 'cora_group_nonce'))
			wp_die('Error');
		$groups = get_option($this->option_name, []);
		$id = !empty($_POST['group_id']) ? sanitize_text_field($_POST['group_id']) : uniqid('group_');
		$groups[$id] = ['title' => sanitize_text_field($_POST['group_title']), 'rule_type' => sanitize_text_field($_POST['rule_type']), 'location' => sanitize_text_field($_POST['group_location']), 'fields' => $_POST['fields'] ?? []];
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