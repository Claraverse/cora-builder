<?php
/**
 * Cora Studio Component: Global Sidebar
 * Unified navigation hub for all engine modules.
 */
if (!defined('ABSPATH'))
    exit;

// Determine active page for highlighting links
$current_page = isset($_GET['page']) ? sanitize_text_field($_GET['page']) : '';
?>

<aside class="cora-sidebar-pro" id="cora-sidebar">
    <button type="button" id="sidebar-toggle" class="sidebar-toggle-btn no-print" title="Toggle Sidebar">
        <span class="dashicons dashicons-menu-alt3"></span>
    </button>

    <div class="sidebar-brand">
        <span class="cora-logo-mark">C</span>
        <strong class="sidebar-text">Cora Studio</strong>
    </div>

    <nav class="sidebar-nav">
        <a href="<?php echo admin_url('admin.php?page=cora-builder'); ?>"
            class="<?php echo $current_page === 'cora-builder' ? 'active' : ''; ?>">
            <span class="dashicons dashicons-dashboard"></span>
            <span class="sidebar-text">Dashboard</span>
        </a>

        <a href="<?php echo admin_url('admin.php?page=cora-cpt'); ?>"
            class="<?php echo $current_page === 'cora-cpt' ? 'active' : ''; ?>">
            <span class="dashicons dashicons-admin-post"></span>
            <span class="sidebar-text">Post Types</span>
        </a>

        <a href="<?php echo admin_url('admin.php?page=cora-tax'); ?>"
            class="<?php echo $current_page === 'cora-tax' ? 'active' : ''; ?>">
            <span class="dashicons dashicons-tag"></span>
            <span class="sidebar-text">Taxonomies</span>
        </a>

        <a href="<?php echo admin_url('admin.php?page=cora-options-builder'); ?>"
            class="<?php echo $current_page === 'cora-options-builder' ? 'active' : ''; ?>">
            <span class="dashicons dashicons-admin-generic"></span>
            <span class="sidebar-text">Options Pages</span>
        </a>

        <a href="<?php echo admin_url('admin.php?page=cora-fieldgroups'); ?>"
            class="<?php echo $current_page === 'cora-fieldgroups' ? 'active' : ''; ?>">
            <span class="dashicons dashicons-list-view"></span>
            <span class="sidebar-text">Field Groups</span>
        </a>
    </nav>

    <div class="sidebar-footer sidebar-text"
        style="margin-top: auto; padding-top: 20px; border-top: 1px solid #eeeeee;">
        <span style="font-size: 10px; color: #999; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">
            Cora Engine v1.0
        </span>
    </div>
</aside>