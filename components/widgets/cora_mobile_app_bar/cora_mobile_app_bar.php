<?php
namespace Cora_Builder\Components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Icons_Manager;

if (!defined('ABSPATH'))
    exit;

class Cora_Mobile_App_Bar extends Base_Widget
{

    public function get_name()
    {
        return 'cora_mobile_app_bar';
    }
    public function get_title()
    {
        return __('Cora Mobile App Bar', 'cora-builder');
    }
    public function get_icon()
    {
        return 'eicon-nav-menu';
    }

    protected function register_controls()
    {

        $this->start_controls_section('content', ['label' => __('Navigation Items', 'cora-builder')]);

        $repeater = new Repeater();
        $repeater->add_control('nav_icon', ['label' => 'Icon', 'type' => Controls_Manager::ICONS]);
        $repeater->add_control('nav_label', ['label' => 'Label', 'type' => Controls_Manager::TEXT, 'default' => 'Menu']);

        $repeater->add_control('action_type', [
            'label' => 'Action',
            'type' => Controls_Manager::SELECT,
            'default' => 'link',
            'options' => [
                'link' => 'External Link',
                'template' => 'Open Drawer (Template)',
            ],
        ]);

        $repeater->add_control('nav_link', [
            'label' => 'Link',
            'type' => Controls_Manager::URL,
            'condition' => ['action_type' => 'link']
        ]);

        $repeater->add_control('template_id', [
            'label' => 'Select Template',
            'type' => Controls_Manager::SELECT,
            'options' => $this->get_elementor_templates(),
            'condition' => ['action_type' => 'template']
        ]);

        $this->add_control('nav_items', [
            'label' => 'Navigation Links',
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                ['nav_label' => 'Home', 'action_type' => 'link'],
                ['nav_label' => 'Menu', 'action_type' => 'template'],
            ],
            'title_field' => '{{{ nav_label }}}',
        ]);

        // --- CENTRAL FAB ---
        $this->add_control('fab_heading', ['label' => 'Central Action (FAB)', 'type' => Controls_Manager::HEADING, 'separator' => 'before']);
        $this->add_control('fab_icon', ['label' => 'FAB Icon', 'type' => Controls_Manager::ICONS, 'default' => ['value' => 'fas fa-plus', 'library' => 'solid']]);

        $this->add_control('fab_action_type', [
            'label' => 'FAB Action',
            'type' => Controls_Manager::SELECT,
            'default' => 'link',
            'options' => ['link' => 'Link', 'template' => 'Drawer'],
        ]);

        $this->add_control('fab_link', ['label' => 'FAB Link', 'type' => Controls_Manager::URL, 'condition' => ['fab_action_type' => 'link']]);
        $this->add_control('fab_template_id', ['label' => 'FAB Template', 'type' => Controls_Manager::SELECT, 'options' => $this->get_elementor_templates(), 'condition' => ['fab_action_type' => 'template']]);

        $this->end_controls_section();

        $this->register_common_spatial_controls();
    }

    protected function get_elementor_templates()
    {
        $templates = get_posts(['post_type' => 'elementor_library', 'posts_per_page' => -1]);
        $options = ['' => 'Select a template'];
        foreach ($templates as $template) {
            $options[$template->ID] = $template->post_title;
        }
        return $options;
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $items = $settings['nav_items'];
        $count = count($items);
        $half = ceil($count / 2);

        $left_items = array_slice($items, 0, $half);
        $right_items = array_slice($items, $half);
        $id = $this->get_id();
        ?>

        <style>
            /* UI Containers */
            .cora-drawer-root {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: 10000;
                visibility: hidden;
                pointer-events: none;
            }

            .cora-drawer-overlay {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                opacity: 0;
                transition: opacity 0.3s;
                pointer-events: auto;
            }

            .cora-drawer-content {
                position: absolute;
                bottom: -100%;
                left: 0;
                width: 100%;
                background: #fff;
                border-radius: 24px 24px 0 0;
                padding: 30px 20px 100px;
                transition: bottom 0.4s cubic-bezier(0.25, 1, 0.5, 1);
                box-shadow: 0 -10px 30px rgba(0, 0, 0, 0.1);
                pointer-events: auto;
                max-height: 80vh;
                overflow-y: auto;
            }

            /* Active States */
            .cora-drawer-active {
                visibility: visible;
            }

            .cora-drawer-active .cora-drawer-overlay {
                opacity: 1;
            }

            .cora-drawer-active .cora-drawer-content {
                bottom: 0;
            }

            .drawer-handle {
                width: 40px;
                height: 4px;
                background: #E2E8F0;
                border-radius: 10px;
                margin: 0 auto 20px;
                cursor: pointer;
            }

            /* App Bar Layout */
            .cora-mobile-app-bar-container {
                position: fixed;
                bottom: 20px;
                left: 50%;
                transform: translateX(-50%);
                width: 92%;
                max-width: 450px;
                z-index: 10002;
            }

            .app-bar-inner {
                background: #ffffff;
                border-radius: 24px;
                height: 80px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 0 10px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
                border: 1px solid #f1f5f9;
            }

            .nav-group {
                display: flex;
                flex: 1;
                justify-content: space-around;
            }

            .nav-item {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 4px;
                text-decoration: none;
                color: #64748b;
                cursor: pointer;
            }

            .nav-item i,
            .nav-item svg {
                font-size: 20px;
                width: 24px;
                height: 24px;
            }

            .nav-label {
                font-size: 11px;
                font-weight: 700;
            }

            .fab-circle {
                width: 64px;
                height: 64px;
                background: #000;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 24px;
                color: #fff;
                border: 4px solid #ffffff;
                box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
                margin-top: 0;
                cursor: pointer;
            }
        </style>

        <div class="cora-mobile-app-bar-container" id="app-bar-<?php echo $id; ?>">
            <div class="app-bar-inner">
                <div class="nav-group">
                    <?php foreach ($left_items as $index => $item):
                        $this->render_nav_item($item, $index, $id);
                    endforeach; ?>
                </div>

                <div class="central-fab">
                    <?php $this->render_fab($settings, $id); ?>
                </div>

                <div class="nav-group">
                    <?php foreach ($right_items as $index => $item):
                        $global_index = $index + $half; // Continue indexing from where left group ended
                        $this->render_nav_item($item, $global_index, $id);
                    endforeach; ?>
                </div>
            </div>
        </div>

        <div id="drawers-container-<?php echo $id; ?>">
            <?php
            // Loop all items to create drawers
            foreach ($items as $index => $item) {
                if ($item['action_type'] === 'template' && !empty($item['template_id'])) {
                    $this->render_drawer_markup('drawer-item-' . $index . '-' . $id, $item['template_id']);
                }
            }
            // Central FAB Drawer
            if ($settings['fab_action_type'] === 'template' && !empty($settings['fab_template_id'])) {
                $this->render_drawer_markup('drawer-fab-main-' . $id, $settings['fab_template_id']);
            }
            ?>
        </div>

        <script>
            jQuery(document).ready(function ($) {
                // Open Logic
                $('.trigger-drawer').on('click', function (e) {
                    e.preventDefault();
                    const targetID = $(this).attr('data-drawer-target');
                    $('.cora-drawer-root').removeClass('cora-drawer-active'); // Reset existing
                    $('#' + targetID).addClass('cora-drawer-active');
                });

                // Close Logic
                $(document).on('click', '.cora-drawer-overlay, .drawer-handle', function () {
                    $(this).closest('.cora-drawer-root').removeClass('cora-drawer-active');
                });
            });
        </script>
        <?php
    }

    protected function render_nav_item($item, $index, $widget_id)
    {
        $is_drawer = ($item['action_type'] === 'template');
        $tag = $is_drawer ? 'div' : 'a';
        $drawer_target = 'drawer-item-' . $index . '-' . $widget_id;

        $attr = $is_drawer
            ? 'class="nav-item trigger-drawer" data-drawer-target="' . $drawer_target . '"'
            : 'href="' . esc_url($item['nav_link']['url']) . '" class="nav-item"';
        ?>
        <<?php echo $tag; ?>         <?php echo $attr; ?>>
            <?php Icons_Manager::render_icon($item['nav_icon']); ?>
            <span class="nav-label"><?php echo esc_html($item['nav_label']); ?></span>
        </<?php echo $tag; ?>>
        <?php
    }

    protected function render_fab($settings, $widget_id)
    {
        $is_drawer = ($settings['fab_action_type'] === 'template');
        $tag = $is_drawer ? 'div' : 'a';
        $drawer_target = 'drawer-fab-main-' . $widget_id;

        $attr = $is_drawer
            ? 'class="fab-circle trigger-drawer" data-drawer-target="' . $drawer_target . '"'
            : 'href="' . esc_url($settings['fab_link']['url']) . '" class="fab-circle"';
        ?>
        <<?php echo $tag; ?>         <?php echo $attr; ?>>
            <?php Icons_Manager::render_icon($settings['fab_icon']); ?>
        </<?php echo $tag; ?>>
        <?php
    }

    protected function render_drawer_markup($full_id, $template_id)
    {
        ?>
        <div class="cora-drawer-root" id="<?php echo $full_id; ?>">
            <div class="cora-drawer-overlay"></div>
            <div class="cora-drawer-content">
                <div class="drawer-handle"></div>
                <?php echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display($template_id); ?>
            </div>
        </div>
        <?php
    }
}