<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;

if (!defined('ABSPATH')) exit;

class Cora_Home_Feature_Card extends Base_Widget
{
    public function get_name() { return 'cora_home_feature_card'; }
    public function get_title() { return __('Cora Home Feature Card', 'cora-builder'); }
    public function get_icon() { return 'eicon-info-box'; }

    // FORCE LOAD ICON LIBRARIES
    public function get_style_depends() {
        return [ 'elementor-icons-fa-solid', 'elementor-icons-fa-regular', 'elementor-icons-fa-brands' ];
    }

    protected function register_controls()
    {
        // --- TAB 1: CONTENT ---
        $this->start_controls_section('section_content', ['label' => __('Card Content', 'cora-builder')]);

        // ICON SOURCE SWITCHER
        $this->add_control('icon_source', [
            'label' => 'Icon Source',
            'type' => Controls_Manager::SELECT,
            'default' => 'library',
            'options' => [
                'library' => 'Icon Library',
                'custom'  => 'Paste SVG Code',
            ],
        ]);

        $this->add_control('icon', [
            'label' => 'Icon',
            'type' => Controls_Manager::ICONS,
            'default' => ['value' => 'fas fa-palette', 'library' => 'solid'],
            'condition' => ['icon_source' => 'library'],
        ]);

        $this->add_control('custom_svg', [
            'label' => 'SVG Code',
            'type' => Controls_Manager::TEXTAREA,
            'rows' => 10,
            'placeholder' => '<svg viewBox="0 0 24 24">...</svg>',
            'condition' => ['icon_source' => 'custom'],
            'description' => 'Paste raw SVG code here. Ensure it has a viewBox attribute.',
        ]);

        // SKIN SELECTOR
        $this->add_control('skin_type', [
            'label' => 'Card Skin',
            'type' => Controls_Manager::SELECT,
            'default' => 'purple',
            'options' => [
                'purple' => 'Design (Purple)',
                'green'  => 'Develop (Green)',
                'blue'   => 'Dominate (Blue)',
                'custom' => 'Custom',
            ],
            'separator' => 'before',
        ]);

        $this->add_control('title', [ 'label' => 'Title', 'type' => Controls_Manager::TEXT, 'default' => 'Design', 'dynamic' => ['active' => true] ]);
        $this->add_control('desc', [ 'label' => 'Description', 'type' => Controls_Manager::TEXTAREA, 'default' => 'Craft experiences that connect.', 'dynamic' => ['active' => true] ]);
        $this->add_control('link', [ 'label' => 'Link', 'type' => Controls_Manager::URL, 'dynamic' => ['active' => true] ]);

        $this->end_controls_section();

        // --- TAB 2: STYLE (Design Reset) ---
        $this->start_controls_section('style_reset', [ 'label' => 'Design Reset', 'tab' => Controls_Manager::TAB_STYLE ]);

        $this->add_control('reset_info', [
            'type' => Controls_Manager::RAW_HTML,
            'raw'  => '<div style="background:#f1f4ff; color:#1e2b5e; padding:12px; border-radius:12px; font-weight:bold; text-align:center; border:1px solid #dbeafe;">
                        <i class="eicon-check-circle"></i> SVG & Responsive Engine Active
                      </div>',
        ]);

        $this->add_control('structural_reset', [
            'type' => Controls_Manager::HIDDEN,
            'default' => 'reset',
            'selectors' => [
                // Container Structure
                '{{WRAPPER}} .cora-feature-card' => 'display: flex; align-items: flex-start; padding: 24px 32px; border-radius: 16px; gap: 24px; transition: transform 0.3s ease; text-decoration: none; position: relative; overflow: hidden;',
                '{{WRAPPER}} .cora-feature-card:hover' => 'transform: translateY(-3px);',
                
                // Icon Box (Fixed Size)
                '{{WRAPPER}} .feature-icon' => 'flex-shrink: 0; display: flex; align-items: center; justify-content: center; width: 48px; height: 48px; font-size: 32px; line-height: 1;',
                // SVG Scaling Magic
                '{{WRAPPER}} .feature-icon i' => 'font-size: inherit; width: 1em; height: 1em;',
                '{{WRAPPER}} .feature-icon svg' => 'width: 1em; height: 1em; fill: currentColor; display: block;',
                
                // Content Column
                '{{WRAPPER}} .feature-body' => 'display: flex; flex-direction: column; gap: 6px; flex: 1; min-width: 0;', // min-width:0 prevents flex overflow
                '{{WRAPPER}} .feature-title' => 'margin: 0 !important; font-size: 20px; font-weight: 800; line-height: 1.2;',
                '{{WRAPPER}} .feature-desc' => 'margin: 0 !important; font-size: 14px; line-height: 1.5;',

                // PRESET SKINS
                '{{WRAPPER}} .skin-purple' => 'background-color: #F3E8FF;',
                '{{WRAPPER}} .skin-purple .feature-icon' => 'color: #7E22CE;',
                '{{WRAPPER}} .skin-purple .feature-title' => 'color: #581C87;',
                '{{WRAPPER}} .skin-purple .feature-desc' => 'color: #6B7280;',

                '{{WRAPPER}} .skin-green' => 'background-color: #DCFCE7;',
                '{{WRAPPER}} .skin-green .feature-icon' => 'color: #15803D;',
                '{{WRAPPER}} .skin-green .feature-title' => 'color: #14532D;',
                '{{WRAPPER}} .skin-green .feature-desc' => 'color: #4B5563;',

                '{{WRAPPER}} .skin-blue' => 'background-color: #DBEAFE;',
                '{{WRAPPER}} .skin-blue .feature-icon' => 'color: #1D4ED8;',
                '{{WRAPPER}} .skin-blue .feature-title' => 'color: #1E3A8A;',
                '{{WRAPPER}} .skin-blue .feature-desc' => 'color: #475569;',

                // RESPONSIVE FIX (Mobile)
                '@media (max-width: 767px)' => [
                    '{{WRAPPER}} .cora-feature-card' => 'padding: 20px; gap: 16px; align-items: flex-start;',
                    '{{WRAPPER}} .feature-icon' => 'width: 40px; height: 40px; font-size: 24px;', // Smaller icon on mobile
                    '{{WRAPPER}} .feature-title' => 'font-size: 18px;',
                ],
            ],
        ]);
        $this->end_controls_section();

        // --- CUSTOM STYLE OVERRIDES ---
        $this->start_controls_section('style_custom', [ 
            'label' => 'Custom Styling', 
            'tab' => Controls_Manager::TAB_STYLE,
            'condition' => ['skin_type' => 'custom']
        ]);
        $this->add_control('custom_bg', [ 'label' => 'Background', 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .cora-feature-card' => 'background-color: {{VALUE}};'] ]);
        $this->add_control('custom_icon_color', [ 'label' => 'Icon Color', 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .feature-icon' => 'color: {{VALUE}};'] ]);
        $this->add_control('custom_title_color', [ 'label' => 'Title Color', 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .feature-title' => 'color: {{VALUE}};'] ]);
        $this->end_controls_section();

        // Global Typography & Layout
        $this->register_text_styling_controls('title_typo', 'Title Typography', '{{WRAPPER}} .feature-title');
        $this->register_text_styling_controls('desc_typo', 'Description Typography', '{{WRAPPER}} .feature-desc');
        $this->register_layout_geometry('.cora-feature-card');
        $this->register_interaction_motion();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $skin_class = 'skin-' . $settings['skin_type'];
        
        $wrapper_tag = 'div';
        $wrapper_attrs = 'class="cora-unit-container cora-feature-card ' . esc_attr($skin_class) . '"';
        
        if ( ! empty( $settings['link']['url'] ) ) {
            $wrapper_tag = 'a';
            $this->add_link_attributes( 'card_link', $settings['link'] );
            $wrapper_attrs .= ' ' . $this->get_render_attribute_string( 'card_link' );
        }
        ?>
        <<?php echo $wrapper_tag; ?> <?php echo $wrapper_attrs; ?>>
            
            <div class="feature-icon">
                <?php if ( 'custom' === $settings['icon_source'] && ! empty( $settings['custom_svg'] ) ) : ?>
                    <?php echo $settings['custom_svg']; ?>
                <?php else : ?>
                    <?php Icons_Manager::render_icon($settings['icon'], ['aria-hidden' => 'true']); ?>
                <?php endif; ?>
            </div>

            <div class="feature-body">
                <h3 class="feature-title"><?php echo esc_html($settings['title']); ?></h3>
                <p class="feature-desc"><?php echo esc_html($settings['desc']); ?></p>
            </div>

        </<?php echo $wrapper_tag; ?>>
        <?php
    }
}