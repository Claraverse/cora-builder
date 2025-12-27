<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;

if (!defined('ABSPATH'))
    exit;

class Cora_Project_Card extends Base_Widget
{

    public function get_name()
    {
        return 'cora_project_card';
    }
    public function get_title()
    {
        return __('Cora Project Card (Dynamic Pro)', 'cora-builder');
    }
    public function get_icon()
    {
        return 'eicon-info-box';
    }

    protected function register_controls()
    {

        // --- TAB 1: CONTENT (Fully Dynamic) ---
        $this->start_controls_section('section_content', ['label' => __('Project Info', 'cora-builder')]);

        $this->add_control('industry', [
            'label' => 'Industry Label',
            'type' => Controls_Manager::TEXT,
            'default' => 'Hospitality',
            'dynamic' => ['active' => true]
        ]);

        $this->add_control('title', [
            'label' => 'Project Name',
            'type' => Controls_Manager::TEXT,
            'default' => 'Siena Dubai',
            'dynamic' => ['active' => true]
        ]);

        $this->add_control('desc', [
            'label' => 'Description',
            'type' => Controls_Manager::TEXTAREA,
            'default' => 'Siena Dubai is a casually chic Italian restaurant...',
            'dynamic' => ['active' => true]
        ]);

        $this->add_control('page_count', [
            'label' => 'Pages Count',
            'type' => Controls_Manager::TEXT,
            'default' => '8+',
            'dynamic' => ['active' => true]
        ]);

        $this->add_control('duration', [
            'label' => 'Project Duration',
            'type' => Controls_Manager::TEXT,
            'default' => '12 Days',
            'dynamic' => ['active' => true]
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_meta', ['label' => __('Media & Author', 'cora-builder')]);

        $this->add_control('image', [
            'label' => 'Project Image',
            'type' => Controls_Manager::MEDIA,
            'dynamic' => ['active' => true]
        ]);

        $this->add_control('author_img', [
            'label' => 'Author Photo',
            'type' => Controls_Manager::MEDIA,
            'dynamic' => ['active' => true]
        ]);

        $this->add_control('author_name', [
            'label' => 'Author Name',
            'type' => Controls_Manager::TEXT,
            'default' => 'Luca Romano',
            'dynamic' => ['active' => true]
        ]);

        $this->add_control('author_role', [
            'label' => 'Author Role',
            'type' => Controls_Manager::TEXT,
            'default' => 'General Manager',
            'dynamic' => ['active' => true]
        ]);

        $this->add_control('cta_url', [
            'label' => 'Project URL',
            'type' => Controls_Manager::URL,
            'dynamic' => ['active' => true]
        ]);

        $this->end_controls_section();

        // --- TAB 2: STYLE (Figma Defaults & Responsive Logic) ---
        $this->start_controls_section('style_layout_defaults', ['label' => 'Design Reset', 'tab' => Controls_Manager::TAB_STYLE]);

        // Structural Resets mapping to your style.css
        $this->add_responsive_control('structural_reset', [
            'type' => Controls_Manager::HIDDEN,
            'default' => 'reset',
            'selectors' => [
                '{{WRAPPER}} .project-card-split' => 'display: flex; align-items: center; box-sizing: border-box; transition: all 0.3s ease;',
                '{{WRAPPER}} .project-content-left' => 'flex: 1; display: flex; flex-direction: column; gap: 24px;',
                '{{WRAPPER}} .project-media-right' => 'flex: 1.2; display: flex; width: 100%;',
                '{{WRAPPER}} .main-project-img' => 'width: 100%; height: 100%; object-fit: cover;',
                '{{WRAPPER}} .project-stats-grid' => 'display: flex; gap: 48px; margin: 12px 0;',
                '{{WRAPPER}} .industry-tag, {{WRAPPER}} .project-name, {{WRAPPER}} .project-desc' => 'margin: 0 !important; padding: 0;',
                '{{WRAPPER}} .author-cta-pill' => 'display: inline-flex; align-items: center; text-decoration: none; gap: 16px; width: fit-content;',
            ],
        ]);

        $this->add_control('card_bg_custom', [
            'label' => 'Card Background',
            'type' => Controls_Manager::COLOR,
            'default' => '#f1f4ff', // Design Default
            'selectors' => ['{{WRAPPER}} .project-card-split' => 'background-color: {{VALUE}};'],
        ]);

        $this->end_controls_section();

        // Typography Engines with style.css defaults
        $this->register_text_styling_controls('industry_tag', 'Industry Tag (Serif)', '{{WRAPPER}} .industry-tag');
        $this->register_text_styling_controls('project_title', 'Project Name (Heading)', '{{WRAPPER}} .project-name');
        $this->register_text_styling_controls('project_desc', 'Description Text', '{{WRAPPER}} .project-desc');

        // --- TAB 3: GLOBAL (Modular Sizing & Surfaces) ---
        $this->register_global_design_controls('.project-card-split');

        // This engine now handles responsive direction and 64px padding
        $this->register_layout_geometry('.project-card-split');

        $this->register_surface_styles('.project-card-split');
        $this->register_common_spatial_controls();
        $this->register_alignment_controls('card_main_align', '.project-card-split', '.project-content-left, .project-media-right');

        // --- TAB 4: ADVANCED (Interactions) ---
        $this->register_interaction_motion();
        $this->register_transform_engine('.project-card-split');
        $this->register_cursor_engine('.project-card-split');
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        if (!empty($settings['cta_url']['url'])) {
            $this->add_link_attributes('cta_link', $settings['cta_url']);
        }
        ?>
        <div class="cora-unit-container project-card-split">
            <div class="project-content-left">
                <span class="industry-tag" style="font-family: serif; font-style: italic; font-size: 24px; color: #0f172a;">
                    <?php echo esc_html($settings['industry']); ?>
                </span>

                <h2 class="project-name" style="font-size: 42px; font-weight: 850; color: #0f172a;">
                    <?php echo esc_html($settings['title']); ?>
                </h2>

                <p class="project-desc" style="font-size: 18px; color: #475569; line-height: 1.6; max-width: 500px;">
                    <?php echo esc_html($settings['desc']); ?>
                </p>

                <div class="project-stats-grid">
                    <div class="stat-box">
                        <span class="stat-label"
                            style="display: block; font-weight: 700; color: #0f172a; font-size: 18px; margin-bottom: 8px;">Pages
                            in Projects</span>
                        <span class="stat-val"
                            style="font-size: 24px; color: #64748b;"><?php echo esc_html($settings['page_count']); ?></span>
                    </div>
                    <div class="stat-box">
                        <span class="stat-label"
                            style="display: block; font-weight: 700; color: #0f172a; font-size: 18px; margin-bottom: 8px;">Project
                            Duration</span>
                        <span class="stat-val"
                            style="font-size: 24px; color: #64748b;"><?php echo esc_html($settings['duration']); ?></span>
                    </div>
                </div>

                <a <?php echo $this->get_render_attribute_string('cta_link'); ?> class="author-cta-pill"
                    style="background: #818cf8; padding: 12px 24px 12px 12px; border-radius: 20px; color: #ffffff;">
                    <img src="<?php echo esc_url($settings['author_img']['url']); ?>" class="author-thumb"
                        style="width: 56px; height: 56px; border-radius: 12px; object-fit: cover;">
                    <div class="author-info">
                        <span class="name"
                            style="display: block; font-weight: 700; font-size: 20px;"><?php echo esc_html($settings['author_name']); ?></span>
                        <span class="role"
                            style="font-size: 14px; opacity: 0.8;"><?php echo esc_html($settings['author_role']); ?></span>
                    </div>
                    <i class="fas fa-arrow-right arrow-icon" style="margin-left: auto;"></i>
                </a>
            </div>

            <div class="project-media-right">
                <img src="<?php echo esc_url($settings['image']['url']); ?>" class="main-project-img"
                    style="border-radius: 32px;">
            </div>
        </div>
        <?php
    }
}