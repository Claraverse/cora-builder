<?php
namespace Cora_Builder\Core;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Cora Base Widget: The Comprehensive Foundation
 * Restores original spatial/alignment logic and adds high-end SaaS design engines.
 */
abstract class Base_Widget extends Widget_Base
{
    // Custom Tab ID for the 4th Tab
    const TAB_GLOBAL = 'cora_global_tab';

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);

        /**
         * REGISTRATION: The 4th "Global" Tab
         * This hook is required to make the tab appear in the Elementor editor.
         */
        // Required to ensure the tab exists during frontend CSS generation
        add_filter('elementor/editor/tabs/register', function ($tabs) {
            $tabs[self::TAB_GLOBAL] = [
                'title' => __('Global', 'cora-builder'),
                'icon' => 'eicon-globe',
            ];
            return $tabs;
        });


        add_action('elementor/editor/after_enqueue_scripts', function () {
            ?>
            <script>
                (function () {
                    window.addEventListener('elementor/panel/opened', function () {
                        const activeModel = elementor.panel.currentView.model;
                        if (activeModel && activeModel.get('widgetType') === '<?php echo $this->get_name(); ?>') {
                            // Force a change trigger to refresh the controls sidebar
                            activeModel.trigger('change:settings');
                            console.log('Cora Sync: Model refreshed for <?php echo $this->get_name(); ?>');
                        }
                    });
                })();
            </script>
            <?php
        });
        // Forces Elementor to re-evaluate the widget's model on load
        add_action('elementor/editor/after_enqueue_scripts', function () {
            ?>
            <script>
                jQuery(window).on('elementor:init', function () {
                    // Ensures that even if the panel is "stuck", the model is refreshed
                    elementor.hooks.addAction('panel/open_editor/widget/<?php echo $this->get_name(); ?>', function (panel, model, view) {
                        if (!model.get('settings').attributes) {
                            console.log('Cora Sync: Refreshing Widget Model...');
                            model.trigger('change');
                        }
                    });
                });
            </script>
            <?php
        });
        $this->register_component_assets();
    }

    /* =========================================================================
       1. CORE ASSET PIPELINE (Preserved from Original)
       ========================================================================= */

    private function register_component_assets()
    {
        $slug = $this->get_component_slug();
        // Preserving your original tiered directory structure logic
        $relative_path = "components/widgets/{$slug}/";

        $css_file = CORA_BUILDER_PATH . $relative_path . "style.css";
        $css_url = CORA_BUILDER_URL . $relative_path . "style.css";
        $js_file = CORA_BUILDER_PATH . $relative_path . "script.js";
        $js_url = CORA_BUILDER_URL . $relative_path . "script.js";

        if (file_exists($css_file)) {
            wp_register_style('cora_' . $slug . '_css', $css_url, [], CORA_BUILDER_VERSION);
        }
        if (file_exists($js_file)) {
            wp_register_script('cora_' . $slug . '_js', $js_url, ['jquery'], CORA_BUILDER_VERSION, true);
        }
    }

    private function get_component_slug()
    {
        $class = get_class($this);
        $parts = explode('\\', $class);
        return strtolower(end($parts));
    }

    public function get_style_depends()
    {
        return ['cora_' . $this->get_component_slug() . '_css'];
    }
    public function get_script_depends()
    {
        return ['cora_' . $this->get_component_slug() . '_js'];
    }

    /* =========================================================================
       2. TAB 4: GLOBAL DESIGN SYSTEM (The New Feature)
       ========================================================================= */

    protected function register_global_design_controls($selector)
    {
        $this->start_controls_section('cora_global_tokens_section', [
            'label' => __('Design System Tokens', 'cora-builder'),
            'tab' => Controls_Manager::TAB_RESPONSIVE,
        ]);

        $this->add_control('cora_sync_brand', [
            'label' => __('Sync Brand Color', 'cora-builder'),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
            'selectors' => ['{{WRAPPER}} ' . $selector => 'color: var(--cora-primary);'],
        ]);

        $this->add_control('cora_sync_radius', [
            'label' => __('Sync Global Radius', 'cora-builder'),
            'type' => Controls_Manager::SWITCHER,
            'default' => '',
            'selectors' => ['{{WRAPPER}} ' . $selector => 'border-radius: var(--cora-radius);'],
        ]);

        $this->end_controls_section();
    }

    /* =========================================================================
       3. STYLE ENGINES (Geometry, Surface, Typography)
       ========================================================================= */

    /**
     * ENGINE: Layout & Geometry (Stack/Grid logic).
     */
    protected function register_layout_geometry($selector)
    {
        $this->start_controls_section('cora_layout_geo', [
            'label' => __('Layout & Geometry', 'cora-builder'),
            'tab' => Controls_Manager::TAB_RESPONSIVE, // Integrated into the 4th custom tab
        ]);

        // --- 1. SIZING ENGINE (Hug / Fill / Fixed / Full Width) ---
        $this->add_responsive_control('cora_width_mode', [
            'label' => __('Width Mode', 'cora-builder'),
            'type' => Controls_Manager::SELECT,
            'default' => '100%',
            'options' => [
                'auto' => 'Hug (Auto)',
                '100%' => 'Fill (100%)',
                '100vw' => 'Full Width (100vw)', // New Full Width Option
                'custom' => 'Fixed (Custom)',
            ],
            'selectors' => [$selector => 'width: {{VALUE}};'],
        ]);

        $this->add_responsive_control('cora_custom_width', [
            'label' => __('Fixed Width', 'cora-builder'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px', 'rem', 'vw', '%'],
            'condition' => ['cora_width_mode' => 'custom'],
            'selectors' => [$selector => 'width: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('cora_min_height', [
            'label' => __('Min Height', 'cora-builder'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px', 'vh', 'em'],
            'selectors' => [$selector => 'min-height: {{SIZE}}{{UNIT}};'],
        ]);
        $this->add_responsive_control('cora_min_width', [
            'label' => __('Min Width', 'cora-builder'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px', 'vh', 'em', '%'],
            'selectors' => [$selector => 'min-width: {{SIZE}}{{UNIT}};'],
        ]);

        // --- 2. FLEXBOX & GRID ENGINE ---
        $this->add_responsive_control('cora_display', [
            'label' => __('Layout Type', 'cora-builder'),
            'type' => Controls_Manager::CHOOSE,
            'default' => 'flex',
            'options' => [
                'flex' => ['title' => 'Stack', 'icon' => 'eicon-column'],
                'grid' => ['title' => 'Grid', 'icon' => 'eicon-grid'],
            ],
            'selectors' => [$selector => 'display: {{VALUE}};'],
        ]);
        // Inside register_layout_geometry or a global CSS injector
        $this->add_control('cora_margin_reset', [
            'type' => Controls_Manager::HIDDEN,
            'default' => '0',
            'selectors' => [
                '{{WRAPPER}} .cora-heading-text' => 'margin: 0 !important; line-height: 1.2;',
            ],
        ]);
        $this->add_responsive_control('cora_direction', [
            'label' => __('Direction', 'cora-builder'),
            'type' => Controls_Manager::CHOOSE,
            'default' => 'column',
            'options' => [
                'row' => ['title' => 'Horizontal', 'icon' => 'eicon-arrow-right'],
                'column' => ['title' => 'Vertical', 'icon' => 'eicon-arrow-down'],
            ],
            'condition' => ['cora_display' => 'flex'],
            'selectors' => [$selector => 'flex-direction: {{VALUE}};'],
        ]);

        // --- 3. ALIGNMENT & SPACING ---
        $this->add_responsive_control('cora_justify', [
            'label' => __('Distribute', 'cora-builder'),
            'type' => Controls_Manager::SELECT,
            'default' => 'center',
            'options' => [
                'flex-start' => 'Start',
                'center' => 'Center',
                'flex-end' => 'End',
                'space-between' => 'Space Between',
                'space-around' => 'Space Around',
            ],
            'selectors' => [$selector => 'justify-content: {{VALUE}};'],
        ]);

        $this->add_responsive_control('cora_align', [
            'label' => __('Align Items', 'cora-builder'),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'flex-start' => ['title' => 'Start', 'icon' => 'eicon-flex-item-start'],
                'center' => ['title' => 'Center', 'icon' => 'eicon-flex-item-center'],
                'flex-end' => ['title' => 'End', 'icon' => 'eicon-flex-item-end'],
                'stretch' => ['title' => 'Stretch', 'icon' => 'eicon-flex-item-stretch'],
            ],
            'selectors' => [$selector => 'align-items: {{VALUE}};'],
        ]);

        $this->add_responsive_control('cora_wrap', [
            'label' => __('Wrap Items', 'cora-builder'),
            'type' => Controls_Manager::CHOOSE,
            'default' => 'nowrap',
            'options' => [
                'nowrap' => ['title' => 'No', 'icon' => 'eicon-ban'],
                'wrap' => ['title' => 'Yes', 'icon' => 'eicon-check'],
            ],
            'selectors' => [$selector => 'flex-wrap: {{VALUE}};'],
        ]);

        // --- 4. SPACING ENGINE (Multi-Gap & Padding) ---
        $this->add_responsive_control('cora_gap_type', [
            'label' => __('Gap Control', 'cora-builder'),
            'type' => Controls_Manager::CHOOSE,
            'default' => 'single',
            'options' => [
                'single' => ['title' => 'Uniform', 'icon' => 'eicon-link'],
                'split' => ['title' => 'Split', 'icon' => 'eicon-link-broken'],
            ],
        ]);

        $this->add_responsive_control('cora_gap', [
            'label' => __('Item Gap', 'cora-builder'),
            'type' => Controls_Manager::GAPS,
            'selectors' => [$selector => 'gap: {{ROW}}{{UNIT}} {{COLUMN}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('cora_padding', [
            'label' => __('Padding', 'cora-builder'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em', 'rem', 'vw'],
            'selectors' => [$selector => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        // --- Background Color ---
        $this->add_control('cora_background_color', [
            'label' => __('Background Color', 'cora-builder'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [$selector => 'background-color: {{VALUE}};'],
        ]);

        // --- 5. BORDER & RADIUS ENGINES (STATE-AWARE) ---
        $this->start_controls_tabs('cora_geo_state_tabs');

        // NORMAL TAB
        $this->start_controls_tab('cora_geo_normal', ['label' => __('Normal', 'cora-builder')]);
        $this->add_group_control(
            Group_Control_Border::get_type(),
            ['name' => 'cora_geo_border', 'selector' => $selector]
        );
        $this->add_responsive_control('cora_geo_radius', [
            'label' => __('Corner Radius', 'cora-builder'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em', 'rem'],
            'selectors' => [$selector => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);
        $this->end_controls_tab();

        // HOVER TAB
        $this->start_controls_tab('cora_geo_hover', ['label' => __('Hover', 'cora-builder')]);
        $this->add_group_control(
            Group_Control_Border::get_type(),
            ['name' => 'cora_geo_border_h', 'selector' => $selector . ':hover']
        );
        $this->add_responsive_control('cora_geo_radius_h', [
            'label' => __('Corner Radius (Hover)', 'cora-builder'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em', 'rem'],
            'selectors' => [$selector . ':hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);
        $this->add_control('cora_geo_transition', [
            'label' => __('Transition Duration (ms)', 'cora-builder'),
            'type' => Controls_Manager::NUMBER,
            'default' => 300,
            'selectors' => [$selector => 'transition: all {{VALUE}}ms ease-in-out;'],
        ]);
        $this->end_controls_tab();

        $this->end_controls_tabs();

        // --- 6. SYSTEM GLOBAL STATE ---
        $this->add_control('cora_disabled_state', [
            'label' => __('Disabled Mode', 'cora-builder'),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => __('Yes', 'cora-builder'),
            'label_off' => __('No', 'cora-builder'),
            'selectors' => [
                '{{WRAPPER}} .cora-disabled ' . $selector => 'opacity: 0.5; pointer-events: none; filter: grayscale(100%);',
            ],
            'separator' => 'before',
        ]);

        $this->end_controls_section();
    }

    /**
     * ENGINE: Styles & Surface (Glassmorphism).
     */
    protected function register_surface_styles($selector)
    {
        $this->start_controls_section('cora_surface_styles', [
            'label' => __('Styles & Surface', 'cora-builder'),
            'tab' => Controls_Manager::TAB_RESPONSIVE,
        ]);

        $this->start_controls_tabs('cora_surface_tabs');

        // --- NORMAL STATE ---
        $this->start_controls_tab('cora_surface_normal', ['label' => __('Normal', 'cora-builder')]);

        $this->add_control('cora_glass_blur', [
            'label' => __('Glass Blur (px)', 'cora-builder'),
            'type' => Controls_Manager::SLIDER,
            'selectors' => [$selector => 'backdrop-filter: blur({{SIZE}}px); -webkit-backdrop-filter: blur({{SIZE}}px);'],
        ]);

        $this->add_group_control(Group_Control_Border::get_type(), ['name' => 'cora_border', 'selector' => $selector]);

        $this->add_group_control(Group_Control_Box_Shadow::get_type(), ['name' => 'cora_depth', 'selector' => $selector]);

        $this->end_controls_tab();

        // --- HOVER STATE ---
        $this->start_controls_tab('cora_surface_hover', ['label' => __('Hover', 'cora-builder')]);

        $this->add_control('cora_glass_blur_h', [
            'label' => __('Glass Blur (px)', 'cora-builder'),
            'type' => Controls_Manager::SLIDER,
            'selectors' => [$selector . ':hover' => 'backdrop-filter: blur({{SIZE}}px); -webkit-backdrop-filter: blur({{SIZE}}px);'],
        ]);

        $this->add_group_control(Group_Control_Border::get_type(), ['name' => 'cora_border_h', 'selector' => $selector . ':hover']);

        $this->add_group_control(Group_Control_Box_Shadow::get_type(), ['name' => 'cora_depth_h', 'selector' => $selector . ':hover']);

        $this->add_control('cora_hover_transition', [
            'label' => __('Transition Duration (ms)', 'cora-builder'),
            'type' => Controls_Manager::NUMBER,
            'default' => 300,
            'selectors' => [$selector => 'transition: all {{VALUE}}ms ease-in-out;'],
        ]);

        $this->end_controls_tab();

        // --- ACTIVE STATE ---
        $this->start_controls_tab('cora_surface_active', ['label' => __('Active', 'cora-builder')]);
        $this->add_group_control(Group_Control_Box_Shadow::get_type(), ['name' => 'cora_depth_a', 'selector' => $selector . ':active']);
        $this->add_control('cora_active_scale', [
            'label' => __('Click Scale', 'cora-builder'),
            'type' => Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 0.5, 'max' => 1.5, 'step' => 0.01]],
            'selectors' => [$selector . ':active' => 'transform: scale({{SIZE}});'],
        ]);
        $this->end_controls_tab();

        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    /**
     * ENGINE: Text Styling Engine (Original Enhanced with SaaS Blend).
     */
    protected function register_text_styling_controls($id, $label, $selector)
    {
        $this->start_controls_section('style_' . $id, [
            'label' => $label,
            'tab' => Controls_Manager::TAB_RESPONSIVE,
        ]);

        // --- 1. RESPONSIVE ALIGNMENT ---
        $this->add_responsive_control($id . '_text_align', [
            'label' => __('Alignment', 'cora-builder'),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'left' => [
                    'title' => __('Left', 'cora-builder'),
                    'icon' => 'eicon-text-align-left',
                ],
                'center' => [
                    'title' => __('Center', 'cora-builder'),
                    'icon' => 'eicon-text-align-center',
                ],
                'right' => [
                    'title' => __('Right', 'cora-builder'),
                    'icon' => 'eicon-text-align-right',
                ],
                'justify' => [
                    'title' => __('Justified', 'cora-builder'),
                    'icon' => 'eicon-text-align-justify',
                ],
            ],
            'selectors' => [
                $selector => 'text-align: {{VALUE}};',
            ],
        ]);

        // Common Typography (Static across states usually)
        $this->add_group_control(Group_Control_Typography::get_type(), ['name' => $id . '_typo', 'selector' => $selector]);

        $this->start_controls_tabs($id . '_text_state_tabs');

        // --- NORMAL ---
        $this->start_controls_tab($id . '_normal', ['label' => __('Normal', 'cora-builder')]);
        $this->add_control($id . '_color', [
            'label' => __('Text Color', 'cora-builder'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [$selector => 'color: {{VALUE}};'],
        ]);
        $this->add_control($id . '_stroke', [
            'label' => __('Text Stroke', 'cora-builder'),
            'type' => 'text-stroke',
            'selectors' => [$selector => '-webkit-text-stroke: {{TEXT_STROKE_SIZE}}px {{TEXT_STROKE_COLOR}};'],
        ]);
        $this->add_group_control(Group_Control_Text_Shadow::get_type(), ['name' => $id . '_shadow', 'selector' => $selector]);
        $this->end_controls_tab();

        // --- HOVER ---
        $this->start_controls_tab($id . '_hover', ['label' => __('Hover', 'cora-builder')]);
        $this->add_control($id . '_color_h', [
            'label' => __('Text Color', 'cora-builder'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [$selector . ':hover' => 'color: {{VALUE}};'],
        ]);
        $this->add_control($id . '_stroke_h', [
            'label' => __('Text Stroke', 'cora-builder'),
            'type' => 'text-stroke',
            'selectors' => [$selector . ':hover' => '-webkit-text-stroke: {{TEXT_STROKE_SIZE}}px {{TEXT_STROKE_COLOR}};'],
        ]);
        $this->add_group_control(Group_Control_Text_Shadow::get_type(), ['name' => $id . '_shadow_h', 'selector' => $selector . ':hover']);
        $this->end_controls_tab();

        $this->end_controls_tabs();

        // Blend Mode (Shared)
        $this->add_control($id . '_blend', [
            'label' => __('Blend Mode', 'cora-builder'),
            'type' => Controls_Manager::SELECT,
            'options' => ['normal' => 'Normal', 'multiply' => 'Multiply', 'overlay' => 'Overlay', 'screen' => 'Screen'],
            'selectors' => [$selector => 'mix-blend-mode: {{VALUE}};'],
            'separator' => 'before',
        ]);

        $this->end_controls_section();
    }

    /* =========================================================================
       4. SPATIAL & ALIGNMENT LOGIC (Preserved Original)
       ========================================================================= */

    protected function register_common_spatial_controls()
    {
        $this->start_controls_section('cora_spatial_matrix', [
            'label' => __('Cora Spacing Matrix', 'cora-builder'),
            'tab' => Controls_Manager::TAB_RESPONSIVE,
        ]);

        $this->start_controls_tabs('cora_spatial_tabs');

        // --- NORMAL ---
        $this->start_controls_tab('cora_spatial_normal', ['label' => __('Normal', 'cora-builder')]);

        $this->add_responsive_control('cora_max_width', [
            'label' => __('Max Width', 'cora-builder'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px', 'em', 'rem', 'vw', '%'],
            'range' => ['px' => ['min' => 0, 'max' => 1600]],
            'selectors' => ['{{WRAPPER}} .cora-unit-container' => 'max-width: {{SIZE}}{{UNIT}}; margin: 0 auto;'],
        ]);

        $this->add_responsive_control('cora_rotation', [
            'label' => __('Rotation (°)', 'cora-builder'),
            'type' => Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 0, 'max' => 360]],
            'selectors' => ['{{WRAPPER}}' => 'transform: rotate({{SIZE}}deg);'],
        ]);

        $this->end_controls_tab();

        // --- HOVER ---
        $this->start_controls_tab('cora_spatial_hover', ['label' => __('Hover', 'cora-builder')]);

        $this->add_responsive_control('cora_max_width_h', [
            'label' => __('Max Width (Hover)', 'cora-builder'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px', 'em', 'rem', 'vw', '%'],
            'selectors' => ['{{WRAPPER}} .cora-unit-container:hover' => 'max-width: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('cora_rotation_h', [
            'label' => __('Rotation (Hover)', 'cora-builder'),
            'type' => Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 0, 'max' => 360]],
            'selectors' => ['{{WRAPPER}}:hover' => 'transform: rotate({{SIZE}}deg);'],
        ]);

        $this->add_control('cora_spatial_transition', [
            'label' => __('Transition (ms)', 'cora-builder'),
            'type' => Controls_Manager::NUMBER,
            'default' => 400,
            'selectors' => ['{{WRAPPER}}, {{WRAPPER}} .cora-unit-container' => 'transition: all {{VALUE}}ms cubic-bezier(0.4, 0, 0.2, 1);'],
        ]);

        $this->end_controls_tab();

        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    protected function register_alignment_controls($id, $container_selector, $inner_selector)
    {
        $this->start_controls_section('alignment_' . $id, [
            'label' => __('Alignment Matrix', 'cora-builder'),
            'tab' => Controls_Manager::TAB_RESPONSIVE,
        ]);

        $this->start_controls_tabs($id . '_align_tabs');

        // --- NORMAL ---
        $this->start_controls_tab($id . '_align_normal', ['label' => __('Normal', 'cora-builder')]);
        $this->add_responsive_control($id . '_block_align', [
            'label' => __('Block Distribution', 'cora-builder'),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'flex-start' => ['title' => 'Left', 'icon' => 'eicon-text-align-left'],
                'center' => ['title' => 'Center', 'icon' => 'eicon-text-align-center'],
                'flex-end' => ['title' => 'Right', 'icon' => 'eicon-text-align-right'],
            ],
            'selectors' => ['{{WRAPPER}}' => 'display: flex; justify-content: {{VALUE}};'],
        ]);
        $this->end_controls_tab();

        // --- HOVER ---
        $this->start_controls_tab($id . '_align_hover', ['label' => __('Hover', 'cora-builder')]);
        $this->add_responsive_control($id . '_block_align_h', [
            'label' => __('Block Distribution (Hover)', 'cora-builder'),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'flex-start' => ['title' => 'Left', 'icon' => 'eicon-text-align-left'],
                'center' => ['title' => 'Center', 'icon' => 'eicon-text-align-center'],
                'flex-end' => ['title' => 'Right', 'icon' => 'eicon-text-align-right'],
            ],
            'selectors' => ['{{WRAPPER}}:hover' => 'justify-content: {{VALUE}};'],
        ]);
        $this->end_controls_tab();

        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    /* =========================================================================
       5. TAB 3: ADVANCED (GSAP & Visibility)
       ========================================================================= */

    protected function register_interaction_motion()
    {
        $this->start_controls_section('cora_gsap_engine', [
            'label' => __('Interactions (GSAP)', 'cora-builder'),
            'tab' => Controls_Manager::TAB_ADVANCED,
        ]);

        $this->add_control('cora_entrance', [
            'label' => __('Entrance Reveal', 'cora-builder'),
            'type' => Controls_Manager::ANIMATION,
            'frontend_available' => true,
        ]);

        // --- DISABLED STATE ENGINE ---
        $this->add_control('cora_disabled_heading', [
            'label' => __('System Global State', 'cora-builder'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('cora_is_disabled', [
            'label' => __('Force Disabled State', 'cora-builder'),
            'type' => Controls_Manager::SWITCHER,
            'description' => __('Applies a visual "Locked" look to the entire unit.', 'cora-builder'),
            'selectors' => [
                '{{WRAPPER}}' => 'opacity: 0.4; filter: grayscale(100%); pointer-events: none; cursor: not-allowed;',
            ],
        ]);

        $this->end_controls_section();
    }

    /* =========================================================================
       6. UTILITIES (Preserved)
       ========================================================================= */

    protected function truncate_text($text, $limit, $mode = 'words')
    {
        if (empty($text) || $limit <= 0)
            return $text;
        if ($mode === 'letters')
            return (strlen($text) > $limit) ? substr($text, 0, $limit) . '...' : $text;
        $words = explode(' ', $text);
        return (count($words) > $limit) ? implode(' ', array_slice($words, 0, $limit)) . '...' : $text;
    }

    /**
     * NEW ENGINE: 3D Transform & Motion (State-Aware)
     * Maps to the "Transforms" section in your design engine.
     */
    protected function register_transform_engine($selector)
    {
        $this->start_controls_section('cora_transform_section', [
            'label' => __('Transforms & Depth', 'cora-builder'),
            'tab' => Controls_Manager::TAB_RESPONSIVE,
        ]);

        $this->start_controls_tabs('cora_transform_tabs');

        // --- NORMAL ---
        $this->start_controls_tab('cora_transform_normal', ['label' => __('Normal', 'cora-builder')]);

        $this->add_responsive_control('cora_translate_x', [
            'label' => __('Offset X', 'cora-builder'),
            'type' => Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => -200, 'max' => 200]],
            'selectors' => [$selector => 'transform: translate({{cora_translate_x.SIZE}}px, {{cora_translate_y.SIZE}}px) rotate({{cora_rotation_manual.SIZE}}deg) scale({{cora_scale.SIZE}});'],
        ]);

        $this->add_responsive_control('cora_translate_y', [
            'label' => __('Offset Y', 'cora-builder'),
            'type' => Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => -200, 'max' => 200]],
        ]);

        $this->add_responsive_control('cora_scale', [
            'label' => __('Scale', 'cora-builder'),
            'type' => Controls_Manager::SLIDER,
            'default' => ['size' => 1],
            'range' => ['px' => ['min' => 0.5, 'max' => 2, 'step' => 0.01]],
        ]);

        $this->add_responsive_control('cora_rotation_manual', [
            'label' => __('Rotate (°)', 'cora-builder'),
            'type' => Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => -360, 'max' => 360]],
        ]);

        $this->end_controls_tab();

        // --- HOVER (Motion Trigger) ---
        $this->start_controls_tab('cora_transform_hover', ['label' => __('Hover', 'cora-builder')]);

        $this->add_responsive_control('cora_translate_x_h', [
            'label' => __('Offset X', 'cora-builder'),
            'type' => Controls_Manager::SLIDER,
            'selectors' => [$selector . ':hover' => 'transform: translate({{cora_translate_x_h.SIZE}}px, {{cora_translate_y_h.SIZE}}px) rotate({{cora_rotation_manual_h.SIZE}}deg) scale({{cora_scale_h.SIZE}});'],
        ]);

        $this->add_responsive_control('cora_translate_y_h', [
            'label' => __('Offset Y', 'cora-builder'),
            'type' => Controls_Manager::SLIDER,
        ]);

        $this->add_responsive_control('cora_scale_h', [
            'label' => __('Scale', 'cora-builder'),
            'type' => Controls_Manager::SLIDER,
            'default' => ['size' => 1.05],
        ]);

        $this->add_responsive_control('cora_rotation_manual_h', [
            'label' => __('Rotate (°)', 'cora-builder'),
            'type' => Controls_Manager::SLIDER,
        ]);

        $this->end_controls_tab();

        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    /**
     * NEW ENGINE: Overlays & Background Patterns
     * Maps to "Overlays" in the Layout screenshots.
     */
    protected function register_overlay_engine($selector)
    {
        $this->start_controls_section('cora_overlays', [
            'label' => __('Overlays & Patterns', 'cora-builder'),
            'tab' => Controls_Manager::TAB_RESPONSIVE,
        ]);

        $this->add_control('cora_overlay_type', [
            'label' => __('Overlay Type', 'cora-builder'),
            'type' => Controls_Manager::SELECT,
            'options' => [
                'none' => 'None',
                'gradient' => 'Gradient Overlay',
                'pattern' => 'Noise/Pattern',
            ],
            'default' => 'none',
        ]);

        $this->add_control('cora_pattern_opacity', [
            'label' => __('Opacity', 'cora-builder'),
            'type' => Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 0, 'max' => 1, 'step' => 0.1]],
            'selectors' => [$selector . '::before' => 'opacity: {{SIZE}};'],
            'condition' => ['cora_overlay_type!' => 'none'],
        ]);

        $this->add_control('cora_pattern_blend', [
            'label' => __('Blend Mode', 'cora-builder'),
            'type' => Controls_Manager::SELECT,
            'options' => ['multiply' => 'Multiply', 'overlay' => 'Overlay', 'soft-light' => 'Soft Light', 'screen' => 'Screen'],
            'selectors' => [$selector . '::before' => 'mix-blend-mode: {{VALUE}};'],
            'condition' => ['cora_overlay_type!' => 'none'],
        ]);

        $this->end_controls_section();
    }

    /**
     * NEW ENGINE: Custom Cursor Engine
     * Maps to the "Cursor" section in Layout.
     */
    protected function register_cursor_engine($selector)
    {
        $this->start_controls_section('cora_cursor_section', [
            'label' => __('Cursor & Interaction', 'cora-builder'),
            'tab' => Controls_Manager::TAB_RESPONSIVE,
        ]);

        $this->add_control('cora_custom_cursor', [
            'label' => __('Pointer Type', 'cora-builder'),
            'type' => Controls_Manager::SELECT,
            'options' => [
                'default' => 'System Default',
                'pointer' => 'Hand (Pointer)',
                'crosshair' => 'Crosshair',
                'not-allowed' => 'Disabled',
                'help' => 'Help Info',
            ],
            'selectors' => [$selector => 'cursor: {{VALUE}};'],
        ]);

        $this->end_controls_section();
    }
}