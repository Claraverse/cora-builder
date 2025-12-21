<?php
namespace Cora_Builder\Core;

if (!defined('ABSPATH')) {
    exit;
}

class Settings_Manager
{

    public function __construct()
    {
        // Register Settings
        add_action('admin_init', [$this, 'register_settings']);

        // Inject CSS Variables
        add_action('wp_enqueue_scripts', [$this, 'render_global_css']);
        add_action('elementor/editor/after_enqueue_styles', [$this, 'render_global_css']);
    }

    public function register_settings()
    {
        register_setting('cora_settings_group', 'cora_theme_options');
    }

    public function render_settings_page()
    {
        $options = get_option('cora_theme_options', []);
        $primary = isset($options['primary_color']) ? $options['primary_color'] : '#4F46E5';
        $radius = isset($options['border_radius']) ? $options['border_radius'] : '8';
        $font = isset($options['body_font']) ? $options['body_font'] : 'Inter';
        ?>
        <div class="cora-admin-wrapper">
            <div class="cora-header">
                <div class="cora-logo">
                    <h1>Global Design System</h1>
                </div>
                <div class="cora-actions">
                    <button type="submit" form="cora-settings-form" class="cora-btn cora-btn-primary">
                        <span class="dashicons dashicons-saved"></span> Save Changes
                    </button>
                </div>
            </div>

            <form id="cora-settings-form" method="post" action="options.php" class="cora-settings-grid">
                <?php settings_fields('cora_settings_group'); ?>

                <div class="cora-card">
                    <h3>Brand Colors</h3>
                    <p>Define the core colors used across all buttons, badges, and accents.</p>
                    <div class="cora-control-group">
                        <label>Primary Brand Color</label>
                        <div class="cora-color-input-wrapper">
                            <input type="color" name="cora_theme_options[primary_color]"
                                value="<?php echo esc_attr($primary); ?>">
                            <input type="text" value="<?php echo esc_attr($primary); ?>" class="cora-color-text" readonly>
                        </div>
                    </div>
                </div>

                <div class="cora-card">
                    <h3>UI & Shape</h3>
                    <p>Control the roundness of cards and standard typography.</p>
                    <div class="cora-control-group">
                        <label>Global Border Radius (px)</label>
                        <input type="number" name="cora_theme_options[border_radius]" value="<?php echo esc_attr($radius); ?>"
                            class="cora-input">
                    </div>
                    <div class="cora-control-group">
                        <label>Body Font Family</label>
                        <select name="cora_theme_options[body_font]" class="cora-input">
                            <option value="Inter" <?php selected($font, 'Inter'); ?>>Inter</option>
                            <option value="Roboto" <?php selected($font, 'Roboto'); ?>>Roboto</option>
                            <option value="Helvetica Neue" <?php selected($font, 'Helvetica Neue'); ?>>Helvetica Neue</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>
        <?php
    }

    public function render_global_css()
    {
        $options = get_option('cora_theme_options', []);
        $primary = !empty($options['primary_color']) ? $options['primary_color'] : '#4F46E5';
        $radius = !empty($options['border_radius']) ? $options['border_radius'] : '8';
        $font = !empty($options['body_font']) ? $options['body_font'] : 'Inter';

        $css = ":root { --cora-primary: {$primary}; --cora-radius: {$radius}px; --cora-font-body: '{$font}', sans-serif; }";

        wp_add_inline_style('elementor-frontend', $css);
        if (is_admin())
            echo '<style>' . $css . '</style>';
    }
}