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
		<div id="cora-field-picker-modal" class="cora-modal-overlay">
			<div class="cora-modal-container">
				<div class="modal-left">
					<header class="modal-header">
						<h2>Select Field Type</h2>
						<div class="modal-search">
							<span class="dashicons dashicons-search"></span>
							<input type="text" id="modal-search-input" placeholder="Search fields...">
						</div>
					</header>
					<nav class="modal-nav">
						<button type="button" class="modal-nav-btn active" data-cat="Popular">Popular</button>
						<button type="button" class="modal-nav-btn" data-cat="Basic">Basic</button>
						<button type="button" class="modal-nav-btn" data-cat="Advanced">Advanced</button>
						<button type="button" class="modal-nav-btn" data-cat="Choice">Choice</button>
						<button type="button" class="modal-nav-btn" data-cat="Media">Media</button>
						<button type="button" class="modal-nav-btn" data-cat="Relational">Relational</button>
						<button type="button" class="modal-nav-btn" data-cat="Layout">Layout</button>
					</nav>
					<div id="modal-field-grid" class="modal-field-grid"></div>
				</div>
				<div class="modal-right-preview">
					<div id="field-preview-content">
						<h3 id="preview-title">Select a Field</h3>
						<p id="preview-desc">Choose a field type from the left to see its description and details.</p>
						<div class="preview-visual">
							<div class="ghost-line"></div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="cora-btn-bw secondary"
							onclick="jQuery('#cora-field-picker-modal').removeClass('is-visible')">Cancel</button>
						<button type="button" class="cora-btn-bw primary" id="confirm-field-selection"
							style="display:none;">Select Field</button>
					</div>
				</div>
			</div>
		</div>
		<script>
			jQuery(document).ready(function ($) {
				/** * 1. DATA HYDRATION: PHP to JS Objects
				 */
				const studioPostTypes = { <?php foreach ($post_types as $pt)
					echo "'{$pt->name}': '" . addslashes($pt->label) . "',"; ?> };
				const studioOptions = { <?php foreach ($options_pages as $slug => $data)
					echo "'{$slug}': '" . addslashes($data['title']) . "',"; ?> };

				/**
				 * 2. COMPREHENSIVE FIELD LIBRARY
				 * Includes all 29+ types from your original select dropdown.
				 */
				const fieldLibrary = {
					'Popular': [
						{ id: 'text', label: 'Text', icon: 'dashicons-editor-textcolor', desc: 'Standard single-line text input.' },
						{ id: 'textarea', label: 'Text Area', icon: 'dashicons-editor-paragraph', desc: 'Multi-line area for long content.' },
						{ id: 'image', label: 'Image', icon: 'dashicons-format-image', desc: 'Single image selection from Media Library.' },
						{ id: 'select', label: 'Select', icon: 'dashicons-editor-ul', desc: 'Standard dropdown selection menu.' },
						{ id: 'repeater', label: 'Repeater', icon: 'dashicons-menu-alt', desc: 'Create rows of repeating sub-field data.' }
					],
					'Basic': [
						{ id: 'text', label: 'Text', icon: 'dashicons-editor-textcolor', desc: 'Standard single-line text input.' },
						{ id: 'textarea', label: 'Text Area', icon: 'dashicons-editor-paragraph', desc: 'Large area for multi-line text.' },
						{ id: 'number', label: 'Number', icon: 'dashicons-editor-ol', desc: 'Input restricted to numerical values.' },
						{ id: 'true_false', label: 'True / False', icon: 'dashicons-yes', desc: 'A simple boolean checkbox/switch.' }
					],
					'Advanced': [
						{ id: 'google_map', label: 'Google Map', icon: 'dashicons-location', desc: 'Interactive map for coordinate selection.' },
						{ id: 'date_picker', label: 'Date Picker', icon: 'dashicons-calendar-alt', desc: 'Calendar interface for date selection.' },
						{ id: 'date_time_picker', label: 'Date Time', icon: 'dashicons-clock', desc: 'Select specific date and time.' },
						{ id: 'time_picker', label: 'Time Picker', icon: 'dashicons-performance', desc: 'Interface for specific clock times.' },
						{ id: 'color_picker', label: 'Color Picker', icon: 'dashicons-admin-appearance', desc: 'Hex-based color selection tool.' },
						{ id: 'icon_picker', label: 'Icon Picker', icon: 'dashicons-smiley', desc: 'Select from WordPress Dashicon library.' }
					],
					'Choice': [
						{ id: 'select', label: 'Select', icon: 'dashicons-editor-ul', desc: 'Standard dropdown selection menu.' },
						{ id: 'checkbox', label: 'Checkbox', icon: 'dashicons-forms', desc: 'Select one or more via checkboxes.' },
						{ id: 'radio', label: 'Radio Button', icon: 'dashicons-marker', desc: 'Select a single option from a list.' },
						{ id: 'button_group', label: 'Button Group', icon: 'dashicons-button', desc: 'Horizontal list of selectable buttons.' }
					],
					'Media': [
						{ id: 'image', label: 'Image', icon: 'dashicons-format-image', desc: 'Single image from Media Library.' },
						{ id: 'file', label: 'File', icon: 'dashicons-media-default', desc: 'Upload or select any file type.' },
						{ id: 'gallery', label: 'Gallery', icon: 'dashicons-images-alt2', desc: 'Manage a collection of multiple images.' },
						{ id: 'wysiwyg', label: 'Visual Editor', icon: 'dashicons-editor-kitchensink', desc: 'Full WordPress Rich Text editor.' },
						{ id: 'oembed', label: 'oEmbed', icon: 'dashicons-video-alt3', desc: 'Embed content (YouTube, Vimeo, etc).' }
					],
					'Relational': [
						{ id: 'post_object', label: 'Post Object', icon: 'dashicons-admin-post', desc: 'Link to another post or CPT.' },
						{ id: 'page_link', label: 'Page Link', icon: 'dashicons-admin-links', desc: 'Select and link to a site page.' },
						{ id: 'relationship', label: 'Relationship', icon: 'dashicons-networking', desc: 'Many-to-many relationship builder.' },
						{ id: 'taxonomy', label: 'Taxonomy', icon: 'dashicons-tag', desc: 'Select terms from a taxonomy.' },
						{ id: 'user', label: 'User', icon: 'dashicons-admin-users', desc: 'Select one or more site users.' }
					],
					'Layout': [
						{ id: 'message', label: 'Message', icon: 'dashicons-editor-help', desc: 'Display a read-only instructional message.' },
						{ id: 'accordion', label: 'Accordion', icon: 'dashicons-arrow-down-alt2', desc: 'Group fields into a collapsible box.' },
						{ id: 'tab', label: 'Tab', icon: 'dashicons-index-card', desc: 'Group fields into visual tabs.' },
						{ id: 'group', label: 'Group', icon: 'dashicons-category', desc: 'Container for related sub-fields.' },
						{ id: 'clone', label: 'Clone', icon: 'dashicons-admin-page', desc: 'Reuse existing field groups.' },
						{ id: 'flexible_content', label: 'Flexible', icon: 'dashicons-layout', desc: 'Dynamic block-based builder layouts.' },
						{ id: 'repeater', label: 'Repeater', icon: 'dashicons-menu-alt', desc: 'Rows of repeating sub-field data.' }
					]
				};

				let fieldIndex = 0;
				let activeFieldRow = null;
				let pendingSelection = null;

				/**
				 * 3. TARGETING ENGINE
				 */
				function updateTargets(type, selected = null) {
					const $select = $('#group_location').empty();
					const data = (type === 'options_page') ? studioOptions : studioPostTypes;
					$.each(data, (k, v) => {
						let opt = $('<option>').val(k).text(v);
						if (k === selected) opt.prop('selected', true);
						$select.append(opt);
					});
				}

				$('#rule_type').on('change', function () { updateTargets($(this).val()); });
				updateTargets('post_type'); // Default Init

				/**
				 * 4. MODAL & PICKER LOGIC
				 */
				function renderCard(field) {
					return `<div class="field-type-card" data-id="${field.id}" data-label="${field.label}" data-desc="${field.desc}">
					<span class="dashicons ${field.icon}"></span>
					<strong>${field.label}</strong>
				</div>`;
				}

				function renderFieldGrid(category) {
					let html = '';
					if (fieldLibrary[category]) {
						fieldLibrary[category].forEach(field => { html += renderCard(field); });
					}
					$('#modal-field-grid').html(html);
				}

				$(document).on('click', '.f-type-trigger', function () {
					activeFieldRow = $(this).closest('.field-item-acc');
					$('#cora-field-picker-modal').addClass('is-visible');
					$('.modal-nav-btn[data-cat="Popular"]').click();
				});

				$(document).on('click', '.modal-nav-btn', function () {
					$('.modal-nav-btn').removeClass('active');
					$(this).addClass('active');
					$('#modal-search-input').val(''); // Clear search
					renderFieldGrid($(this).data('cat'));
				});

				// Modal Search
				$('#modal-search-input').on('input', function () {
					const term = $(this).val().toLowerCase();
					if (term === '') { $('.modal-nav-btn.active').click(); return; }
					let html = '';
					Object.keys(fieldLibrary).forEach(cat => {
						fieldLibrary[cat].forEach(field => {
							if (field.label.toLowerCase().includes(term)) html += renderCard(field);
						});
					});
					$('#modal-field-grid').html(html || '<p style="padding:20px; color:#999;">No fields found...</p>');
				});

				$(document).on('click', '.field-type-card', function () {
					$('.field-type-card').removeClass('selected');
					$(this).addClass('selected');
					pendingSelection = { id: $(this).data('id'), label: $(this).data('label') };
					$('#preview-title').text($(this).data('label'));
					$('#preview-desc').text($(this).data('desc'));
					$('#confirm-field-selection').show();
				});

				$('#confirm-field-selection').on('click', function () {
					if (pendingSelection && activeFieldRow) {
						activeFieldRow.find('.f-type').val(pendingSelection.id);
						activeFieldRow.find('.f-type-trigger span').text(pendingSelection.label);
						activeFieldRow.find('.type-badge').text(pendingSelection.id.toUpperCase());

						// Conditional Config Views
						const isChoice = ['select', 'checkbox', 'radio', 'button_group'].includes(pendingSelection.id);
						const isRepeater = ['repeater', 'flexible_content'].includes(pendingSelection.id);
						activeFieldRow.find('.choice-config').toggle(isChoice);
						activeFieldRow.find('.repeater-config').toggle(isRepeater);
					}
					$('#cora-field-picker-modal').removeClass('is-visible');
				});

				/**
				 * 5. ARCHITECTURE FACTORY: Dynamic Row Generation
				 */
				function createFieldAccordion(index, data = {}) {
					const type = data.type || 'text';
					const typeLabel = type.charAt(0).toUpperCase() + type.slice(1);
					return `
		<div class="field-item-acc" data-index="${index}">
			<div class="acc-header">
				<span class="dashicons dashicons-menu"></span>
				<strong class="label-preview" style="flex-grow:1;">${data.label || '(no label)'}</strong>
				<span class="type-badge">${type.toUpperCase()}</span>
				<span class="dashicons dashicons-no-alt delete-field" style="margin-left:10px; color:#ddd;"></span>
			</div>
			<div class="acc-content" style="display:none;">
				<div class="cora-form-row">
					<div class="input-pro"><label>Unit Label</label><input type="text" class="ghost-input f-label" value="${data.label || ''}"></div>
					<div class="input-pro"><label>Unit Slug</label><input type="text" class="ghost-input f-name" value="${data.name || ''}" readonly></div>
				</div>
				<div class="cora-form-row">
					<div class="input-pro">
						<label>Field Type</label>
						<input type="hidden" class="f-type" value="${type}">
						<div class="f-type-trigger"><span>${typeLabel}</span> <i class="dashicons dashicons-arrow-down-alt2"></i></div>
					</div>
					<div class="input-pro"><label>Placeholder</label><input type="text" class="ghost-input f-placeholder" value="${data.placeholder || ''}"></div>
				</div>
				<div class="choice-config" style="margin-top:20px; ${['select', 'checkbox', 'radio', 'button_group'].includes(type) ? '' : 'display:none;'}">
					<label>Choices (key : Value)</label>
					<textarea class="f-choices ghost-area" style="height:80px;" placeholder="red : Red Color">${data.choices || ''}</textarea>
				</div>
				<div class="repeater-config" style="margin-top:20px; ${['repeater', 'flexible_content'].includes(type) ? '' : 'display:none;'}">
					<label>Sub-fields JSON Architecture</label>
					<textarea class="f-subfields ghost-area" style="height:80px; font-family:monospace;" placeholder='[{"label":"Title", "name":"t", "type":"text"}]'>${data.subfields_json || ''}</textarea>
				</div>
			</div>
		</div>`;
				}

				// Interactive Controls
				$('#add-pro-field').on('click', () => $('#cora-fields-accordion').append(createFieldAccordion(fieldIndex++)));
				$(document).on('click', '.acc-header', function (e) { if (!$(e.target).hasClass('delete-field')) $(this).next().slideToggle(200); });
				$(document).on('click', '.delete-field', function () { $(this).closest('.field-item-acc').remove(); });
				$(document).on('keyup', '.f-label', function () {
					const label = $(this).val();
					$(this).closest('.field-item-acc').find('.label-preview').text(label || '(no label)');
					$(this).closest('.field-item-acc').find('.f-name').val(label.toLowerCase().trim().replace(/\s+/g, '_').replace(/[^a-z0-9_]/g, ''));
				});

				/**
				 * 6. PAYLOAD SERIALIZATION
				 */
				$('#cora-field-group-form').on('submit', function () {
					let payload = '';
					$('.field-item-acc').each(function (i) {
						const $row = $(this);
						const props = ['label', 'name', 'type', 'placeholder', 'choices', 'subfields_json'];
						props.forEach(p => {
							payload += `<input type="hidden" name="fields[${i}][${p}]" value='${$row.find('.f-' + p).val() || ''}'>`;
						});
					});
					$('#fields-json-payload').html(payload);
				});

				/**
				 * 7. REGISTRY HYDRATION
				 */
				$(document).on('click', '.cora-group-item', function () {
					const data = $(this).data('config');
					$('.cora-group-item').removeClass('active'); $(this).addClass('active');
					$('#group_id').val($(this).data('id'));
					$('#group_title').val(data.title);
					$('#current-group-label').text('Editing Architecture: ' + data.title);
					$('#rule_type').val(data.rule_type || 'post_type').trigger('change');
					$('#group_location').val(data.location);
					$('#cora-fields-accordion').empty();
					if (data.fields) {
						$.each(data.fields, (idx, f) => {
							$('#cora-fields-accordion').append(createFieldAccordion(idx, f));
							fieldIndex = Math.max(fieldIndex, idx + 1);
						});
					}
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