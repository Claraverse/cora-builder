<?php
namespace Cora_Builder\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Field_Group_Manager {

	private $option_name = 'cora_field_groups';

	public function __construct() {
		// Handle saving and deleting groups
		add_action( 'admin_post_cora_save_field_group', [ $this, 'handle_save_group' ] );
		add_action( 'admin_post_cora_delete_field_group', [ $this, 'handle_delete_group' ] );
	}

	/**
	 * UI: Renders the Field Groups Dashboard
	 */
	public function render_field_group_page() {
		$groups = get_option( $this->option_name, [] );
		$post_types = get_post_types( [ 'public' => true ], 'objects' );
		?>
		<div class="cora-admin-wrapper">
			<div class="cora-header">
				<div class="cora-logo"><h1>Field Groups</h1></div>
				<div class="cora-actions">
					<a href="#" class="cora-btn cora-btn-primary" id="cora-add-group-btn">
						<span class="dashicons dashicons-plus"></span> Add New Group
					</a>
				</div>
			</div>

			<div class="cora-card">
				<h3>Active Field Groups</h3>
				<?php if ( empty( $groups ) ) : ?>
					<div class="cora-empty-state">
						<p>No field groups found. Create one to start adding custom fields to your content.</p>
					</div>
				<?php else : ?>
					<table class="wp-list-table widefat fixed striped">
						<thead>
							<tr>
								<th>Group Title</th>
								<th>Location Rule</th>
								<th>Fields Count</th>
								<th style="width:100px; text-align:right;">Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ( $groups as $id => $data ) : ?>
								<tr>
									<td><strong><?php echo esc_html( $data['title'] ); ?></strong></td>
									<td><code>Post Type == <?php echo esc_html( $data['location'] ); ?></code></td>
									<td><span class="cora-count">0 Fields</span></td>
									<td style="text-align:right;">
										<form method="post" action="<?php echo admin_url( 'admin-post.php' ); ?>" style="display:inline;">
											<input type="hidden" name="action" value="cora_delete_field_group">
											<input type="hidden" name="group_id" value="<?php echo esc_attr( $id ); ?>">
											<?php wp_nonce_field( 'cora_group_del_nonce', 'cora_nonce' ); ?>
											<button type="submit" class="button-link-delete" onclick="return confirm('Delete this group?')">
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

			<div id="cora-group-modal" class="cora-modal" style="display:none;">
				<div class="cora-modal-content">
					<div class="cora-card">
						<h3>Create Field Group</h3>
						<form method="post" action="<?php echo admin_url( 'admin-post.php' ); ?>">
							<input type="hidden" name="action" value="cora_save_field_group">
							<?php wp_nonce_field( 'cora_group_nonce', 'cora_nonce' ); ?>

							<div class="cora-control-group">
								<label>Group Title</label>
								<input type="text" name="group_title" class="cora-input" placeholder="e.g. Project Details" required>
							</div>

							<div class="cora-section-label">Location Rules</div>
							<p style="font-size:12px; color:#666; margin-bottom:10px;">Show this field group if:</p>
							
							<div class="cora-form-row">
								<div class="cora-control-group">
									<label>Rule Type</label>
									<select class="cora-input" disabled><option>Post Type</option></select>
								</div>
								<div class="cora-control-group">
									<label>Operator</label>
									<select class="cora-input" disabled><option>is equal to</option></select>
								</div>
								<div class="cora-control-group">
									<label>Value</label>
									<select name="group_location" class="cora-input">
										<?php foreach ( $post_types as $pt ) : ?>
											<option value="<?php echo esc_attr($pt->name); ?>"><?php echo esc_html($pt->label); ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>

							<div style="display:flex; gap:10px; margin-top:20px;">
								<button type="submit" class="cora-btn cora-btn-primary">Create Group</button>
								<button type="button" class="cora-btn cora-btn-secondary" id="cora-close-modal">Cancel</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>

		<script>
		jQuery(document).ready(function($) {
			$('#cora-add-group-btn').on('click', function(e) {
				e.preventDefault();
				$('#cora-group-modal').fadeIn(200);
			});
			$('#cora-close-modal').on('click', function() {
				$('#cora-group-modal').fadeOut(200);
			});
		});
		</script>
		<?php
	}

	public function handle_save_group() {
		if ( ! isset( $_POST['cora_nonce'] ) || ! wp_verify_nonce( $_POST['cora_nonce'], 'cora_group_nonce' ) ) wp_die('Security check failed');
		
		$groups = get_option( $this->option_name, [] );
		$id = uniqid('group_');

		$groups[$id] = [
			'title'    => sanitize_text_field( $_POST['group_title'] ),
			'location' => sanitize_text_field( $_POST['group_location'] ),
			'fields'   => [] // Empty for now
		];

		update_option( $this->option_name, $groups );
		wp_redirect( admin_url( 'admin.php?page=cora-field-groups' ) );
		exit;
	}

	public function handle_delete_group() {
		if ( ! isset( $_POST['cora_nonce'] ) || ! wp_verify_nonce( $_POST['cora_nonce'], 'cora_group_del_nonce' ) ) wp_die('Security check failed');
		
		$groups = get_option( $this->option_name, [] );
		$id = sanitize_text_field( $_POST['group_id'] );

		if ( isset( $groups[$id] ) ) {
			unset( $groups[$id] );
			update_option( $this->option_name, $groups );
		}
		wp_redirect( admin_url( 'admin.php?page=cora-field-groups' ) );
		exit;
	}
}