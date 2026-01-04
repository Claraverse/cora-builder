<?php
namespace Cora_Builder\Core;

if (!defined('ABSPATH'))
    exit;

class Plugin_Loader
{
    private static $_instance = null;

    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct()
    {
        $this->load_core_managers();
        $this->init_elementor_hooks();
    }

    /**
     * Optimized Manager Loading
     * Grouping these ensures all admin_init and admin_menu hooks 
     * are caught globally.
     */
    private function load_core_managers()
    {
        $managers = [
            'admin_manager' => 'Admin_Manager',
            'settings_manager' => 'Settings_Manager',
            'cpt_manager' => 'CPT_Manager',
            'taxonomy_manager' => 'Taxonomy_Manager',
            'options_manager' => 'Options_Manager',
            'editor_customizer' => 'Editor_Customizer',
            'field_renderer' => 'Field_Renderer',
            'field_group_manager' => 'Field_Group_Manager'
        ];

        foreach ($managers as $file => $class) {
            $path = CORA_BUILDER_PATH . "core/{$file}.php";
            if (file_exists($path)) {
                require_once $path;
                $full_class = "\\Cora_Builder\\Core\\{$class}";
                if (class_exists($full_class)) {
                    new $full_class();
                }
            }
        }
    }

    private function init_elementor_hooks()
    {
        add_action('elementor/widgets/register', [$this, 'register_components']);
        add_action('elementor/elements/categories_registered', [$this, 'register_categories']);
        add_action('elementor/frontend/after_enqueue_styles', [$this, 'enqueue_global_styles']);
        add_action('elementor/dynamic_tags/register', [$this, 'register_cora_dynamic_tags']);
    }

    /**
     * Pro Category Reordering
     * Moves 'Cora Builder' to the top of the list for a better UX.
     */
    public function register_categories($elements_manager)
    {
        $elements_manager->add_category('cora_widgets', [
            'title' => 'Cora Builder',
            'icon' => 'fa fa-plug',
        ]);

        try {
            $reflection = new \ReflectionClass($elements_manager);
            $property = $reflection->getProperty('categories');
            $property->setAccessible(true);
            $categories = $property->getValue($elements_manager);

            if (isset($categories['cora_widgets'])) {
                $cora_cat = ['cora_widgets' => $categories['cora_widgets']];
                unset($categories['cora_widgets']);

                // Optimized: Insert directly after 'basic' section
                $pos = array_search('basic', array_keys($categories)) + 1;
                $categories = array_merge(
                    array_slice($categories, 0, $pos),
                    $cora_cat,
                    array_slice($categories, $pos)
                );

                $property->setValue($elements_manager, $categories);
            }
        } catch (\Exception $e) {
            error_log('Cora Builder: Category reorder failed.');
        }
    }

    public function register_cora_dynamic_tags($dynamic_tags)
    {
        // 1. Register the Cora Group
        \Elementor\Plugin::$instance->dynamic_tags->register_group('cora-builder-group', [
            'title' => __('Cora Studio', 'cora-builder')
        ]);

        require_once CORA_BUILDER_PATH . 'core/dynamic_tags.php';

        // 2. Register every specialized tag class
        $dynamic_tags->register(new \Cora_Builder\Core\Cora_Dynamic_Text_Tag());
        $dynamic_tags->register(new \Cora_Builder\Core\Cora_Dynamic_Image_Tag());
        $dynamic_tags->register(new \Cora_Builder\Core\Cora_Dynamic_URL_Tag());
        $dynamic_tags->register(new \Cora_Builder\Core\Cora_Dynamic_Number_Tag());
        $dynamic_tags->register(new \Cora_Builder\Core\Cora_Dynamic_Gallery_Tag());
    }
    public function register_components($widgets_manager)
    {
        require_once CORA_BUILDER_PATH . 'core/base_widget.php';

        $widgets = [
            'dual_heading',
            'blog_hero',
            'post_grid',
            'cora_heading',
            'cora_card',
            'cora_advance_heading',
            'cora_blog_card',
            'cora_solution_card',
            'cora_category_card',
            'cora_pill_badge',
            'cora_industry_card',
            'cora_solution_cluster',
            'cora_team_card',
            'cora_cta_section',
            'cora_newsletter',
            'cora_nexus_hero',
            'cora_catalyst_trust_grid',
            'cora_button_group',
            'cora_pill',
            'brush_headline',
            'metric_grid',
            'feature_card_grid',
            'cora_checklist',
            'brush_headline_two',
            'cora_image',
            'cora_feature_card',
            'cora_loop_builder',
            'cora_feature_stats',
            'cora_guide_card',
            'cora_guide_list_card',
            'cora_guide_list_card_v2',
            'cora_mobile_action_bar',
            'cora_guide_card_v3',

            // home page
            'cora_advance_heading_two',
            'cora_hero_title',
            'cora_info_pill',
            'cora_logo_carousel',
            'cora_home_feature_card',
            'cora_blog_header',
            'cora_project_card',
            'cora_service_card',
            'cora_ecosystem_card',
            'cora_whyus_block',
            'cora_testimonial_block',


            // Course Page
            'cora_community_cta',
            'cora_category_stats_header',
            'cora_course_hero_feature',
            'cora_guide_header',
            'cora_guide_content',

            // About Page
            'cora_about_team_card',
            'cora_mission_block',

            // Contact Page
            'cora_contact_pill',
            


            // Invoice
            'cora_invoice_maker',
            'cora_invoice_builder',

            // Blog Page
            'cora_article_card',
            'cora_blog_cat_header',
            'cora_cta_block',
            'cora_blog_single_header',
            'cora_blog_content',

            // Mobile App Bar
            'cora_mobile_app_bar',

            // Hosting Page
            'cora_hosting_hero',
            'cora_browser_frame',
            'cora_status_pill',
            'cora_infra_scaling',
            'cora_hosting_price',
            'cora_security_card',
            'cora_comparison_table',
            'cora_protection_card',
            'cora_store_builder_card',
            'cora_regional_status',
            'cora_guarantee_block',
            'cora_pricing_card_v3',

            // POrtfolio Page
            'cora_conversion_pill',
            'cora_app_showcase',
            'cora_consultation_hero',
            'cora_project_hero_v2',
            'cora_portfolio_card',
            'cora_project_challenge',
            'cora_ongoing_projects',
            'cora_project_stat_card',
            'cora_challenges_grid',
            'cora_solution_card_two',
            'cora_timeline',
            'cora_deliverable_card',


            // Global
            'cora_loop_builder',

            // Booster Services
            'clara_trust_bar',
            'clara_lead_magnet',
            'clara_expert_card',
            'clara_video_booster',
            'clara_impact_testimonial',
            'clara_feature_card',
            'clara_roadmap_v2',
            'clara_success_hub',
            'clara_faq_grid',
            'cora_strategy_hub',

            // Shopify Services
            'cora_service_hero',
            'cora_problem_card',
            'cora_micro_card',
            'cora_process_curve',
            'cora_integrations_orbit',
            'cora_whyus_block_two',
            'cora_service_feature_card',
            'cora_performance_showcase',
            'cora_service_banner',
            'cora_advanced_bento',
            'cora_flex_bento_v2',
            'cora_clean_accordion',
            'cora_success_card',

            // Design Service
            'cora_brand_kit',
            'cora_advanced_feature',
            'cora_portfolio_showcase',

            // Services Page
            'cora_infinite_carousel',
            'cora_process_steps',
            'cora_logo_marquee',
            'cora_stats_hub',
            'cora_stats_trust_row',
            'cora_industry_booster',
            'cora_promise_banner',
            'cora_profile_card',
            'cora_performance_block',
            'cora_others_charge',
            'cora_others_charge_v2',
            'cora_promise_v2',
        ];

        foreach ($widgets as $component) {
            $file_path = CORA_BUILDER_PATH . "components/widgets/{$component}/{$component}.php";
            if (file_exists($file_path)) {
                require_once $file_path;
                $class_name = str_replace(' ', '_', ucwords(str_replace('_', ' ', $component)));
                $class_full = "\\Cora_Builder\\Components\\{$class_name}";

                if (class_exists($class_full)) {
                    $widgets_manager->register(new $class_full());
                }
            }
        }

    }

    public function enqueue_global_styles()
    {
        wp_enqueue_style('cora-global-vars', CORA_BUILDER_URL . 'assets/css/globals.css', [], CORA_BUILDER_VERSION);
    }
}