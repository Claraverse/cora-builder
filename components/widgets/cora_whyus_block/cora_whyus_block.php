<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;

if (!defined('ABSPATH'))
    exit;

class Cora_WhyUs_Block extends Base_Widget
{

    public function get_name()
    {
        return 'cora_whyus_block';
    }
    public function get_title()
    {
        return __('Cora WhyUs Block', 'cora-builder');
    }
    public function get_icon()
    {
        return 'eicon-info-box';
    }

    protected function register_controls()
    {

        // --- TAB 1: CONTENT ---
        $this->start_controls_section('section_content', ['label' => __('Feature Info', 'cora-builder')]);

        $this->add_control('title', [
            'label' => 'Headline',
            'type' => Controls_Manager::TEXT,
            'default' => 'SEO-Optimized',
            'dynamic' => ['active' => true]
        ]);

        $this->add_control('desc', [
            'label' => 'Description',
            'type' => Controls_Manager::TEXTAREA,
            'default' => 'Our SEO-centric design approach enhances your online visibility and drives organic traffic effectively.',
            'dynamic' => ['active' => true]
        ]);

        $this->add_control('dashboard_img', [
            'label' => 'Analytics Preview',
            'type' => Controls_Manager::MEDIA,
            'default' => ['url' => \Elementor\Utils::get_placeholder_image_src()]
        ]);

        $this->end_controls_section();

        // --- TAB 2: STYLE (Design Reset & Structural Authority) ---
        $this->start_controls_section('style_reset', [
            'label' => 'Design Reset',
            'tab' => Controls_Manager::TAB_STYLE
        ]);

        $this->add_control('reset_info', [
            'type' => Controls_Manager::RAW_HTML,
            'raw' => '<div style="background:#f1f4ff; color:#1e2b5e; padding:12px; border-radius:12px; font-weight:bold; text-align:center; border:1px solid #dbeafe;">
                        <i class="eicon-check-circle"></i> Cora Structural Reset Active
                      </div>',
        ]);

        $this->add_control('structural_reset', [
            'type' => Controls_Manager::HIDDEN,
            'default' => 'reset',
            'selectors' => [
                '{{WRAPPER}} .cora-why-us-container' => 'display: flex; flex-direction: column; transition: all 0.3s ease;',
                '{{WRAPPER}} .why-content' => 'display: flex; flex-direction: column; gap: 16px;',
                '{{WRAPPER}} .why-title, {{WRAPPER}} .why-desc' => 'margin: 0 !important; padding: 0;',
                '{{WRAPPER}} .why-media' => 'width: 100%; display: flex; justify-content: center;',
                '{{WRAPPER}} .dashboard-preview' => 'width: 100%; height: auto; object-fit: contain; transition: all 0.3s ease;',
            ],
        ]);
        $this->end_controls_section();

        // --- ELEMENT CONTROL ENGINES ---

        // 1. Headline & Description Typography
        $this->register_text_styling_controls('title_style', 'Headline Typography', '{{WRAPPER}} .why-title');
        $this->register_text_styling_controls('desc_style', 'Description Typography', '{{WRAPPER}} .why-desc');

        // 2. Media Surface & Sizing (Individual Element Geometry)
        $this->register_layout_geometry('.cora-why-us-container');
        $this->register_surface_styles('.cora-why-us-container');

        // --- TAB 3: GLOBAL (Main Card Container) ---

        // CARD PADDING, MARGIN & GAP
        // This engine provides responsive padding (default 40px) for the entire card.
        $this->register_layout_geometry('.cora-why-us-container', );

        // CARD BACKGROUND, BORDERS & SHADOWS
        $this->register_surface_styles('.cora-why-us-container');

        $this->register_global_design_controls('.cora-why-us-container');
        $this->register_alignment_controls('why_align', '.cora-why-us-container', '.why-content, .why-media');
        $this->register_common_spatial_controls();

        // --- TAB 4: ADVANCED ---
        $this->register_interaction_motion();
        $this->register_transform_engine('.cora-why-us-container');
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="cora-unit-container cora-why-us-container">
            <div class="why-content">
                <h3 class="why-title"><?php echo esc_html($settings['title']); ?></h3>
                <p class="why-desc"><?php echo esc_html($settings['desc']); ?></p>
            </div>

            <div class="why-media">
                <?php if (!empty($settings['dashboard_img']['url'])): ?>
                    <img src="<?php echo esc_url($settings['dashboard_img']['url']); ?>" class="cora-why-us-container">
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
}