<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Article_Card extends Base_Widget {

    public function get_name() { return 'cora_article_card'; }
    public function get_title() { return __( 'Cora Article Card', 'cora-builder' ); }
    public function get_icon() { return 'eicon-post'; }

    protected function register_controls() {
        
        // --- TAB 1: CONTENT ---
        $this->start_controls_section('section_content', [ 'label' => __( 'Article Details', 'cora-builder' ) ]);
        
        $this->add_control('thumbnail', [
            'label' => 'Thumbnail Image',
            'type' => Controls_Manager::MEDIA,
            'default' => [ 'url' => Utils::get_placeholder_image_src() ],'dynamic' => ['active' => true]
        ]);

        $this->add_control('meta_date', [
            'label' => 'Date Text',
            'type' => Controls_Manager::TEXT,
            'default' => 'Oct 14, 2025',
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('meta_read_time', [
            'label' => 'Read Time',
            'type' => Controls_Manager::TEXT,
            'default' => '5 min read',
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('title', [
            'label' => 'Title',
            'type' => Controls_Manager::TEXTAREA,
            'rows' => 2,
            'default' => 'Principles of Modern UI/UX Design',
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('description', [
            'label' => 'Excerpt',
            'type' => Controls_Manager::TEXTAREA,
            'rows' => 3,
            'default' => 'Learn the fundamental principles that make great user experiences and how to apply them.',
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('link', [ 'label' => 'Link', 'type' => Controls_Manager::URL, 'dynamic' => ['active' => true] ]);

        $this->end_controls_section();

        // --- TAB 2: STYLE (Design Reset) ---
        $this->start_controls_section('style_reset', [ 'label' => 'Design Reset', 'tab' => Controls_Manager::TAB_STYLE ]);

        // Visual Status Bar
        $this->add_control('reset_info', [
            'type' => Controls_Manager::RAW_HTML,
            'raw'  => '<div style="background:#f1f4ff; color:#1e2b5e; padding:12px; border-radius:12px; font-weight:bold; text-align:center; border:1px solid #dbeafe;">
                        <i class="eicon-check-circle"></i> Horizontal Layout Active
                      </div>',
        ]);

        $this->add_control('structural_reset', [
            'type' => Controls_Manager::HIDDEN,
            'default' => 'reset',
            'selectors' => [
                // Main Container (Enforce Horizontal Row)
                '{{WRAPPER}} .cora-article-card' => 'display: flex; flex-direction: row; align-items: flex-start; gap: 24px; padding: 24px; background: #fff;  text-decoration: none;',
                
                // Image Styling (Fixed Width is Key for Horizontal Layout)
                '{{WRAPPER}} .article-thumb' => 'width: 180px; height: 120px; border-radius: 12px; object-fit: cover; flex-shrink: 0;',
                
                // Content Column (Flex 1 ensures it fills remaining space)
                '{{WRAPPER}} .article-content' => 'display: flex; flex-direction: column; gap: 8px; flex: 1; min-width: 0;',
                
                // Meta Row (Date & Time)
                '{{WRAPPER}} .meta-row' => 'display: flex; align-items: center; gap: 16px; color: #64748b; font-size: 13px; font-weight: 500; line-height: 1;',
                '{{WRAPPER}} .meta-item' => 'display: flex; align-items: center; gap: 6px;',
                '{{WRAPPER}} .meta-item i' => 'font-size: 13px; color: #94a3b8;',
                
                // Typography Authority
                '{{WRAPPER}} .article-title' => 'margin: 4px 0 8px 0 !important; font-size: 18px; font-weight: 700; color: #0f172a; line-height: 1.3;',
                '{{WRAPPER}} .article-desc' => 'margin: 0 !important; font-size: 15px; color: #475569; line-height: 1.5; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;',

                // Responsive: Stack on Mobile
                '@media (max-width: 767px)' => [
                    '{{WRAPPER}} .cora-article-card' => 'flex-direction: column;',
                    '{{WRAPPER}} .article-thumb' => 'width: 100%; height: 200px;',
                ],
            ],
        ]);
        $this->end_controls_section();

        // --- ELEMENT ENGINES ---

        // 1. Thumbnail Geometry (Allows you to resize the image)
        $this->register_layout_geometry('.article-thumb' );
        
        // 2. Typography Engines
        $this->register_text_styling_controls('title_typo', 'Title Typography', '{{WRAPPER}} .article-title');
        $this->register_text_styling_controls('desc_typo', 'Excerpt Typography', '{{WRAPPER}} .article-desc');
        
        // 3. Meta Data Styling
        $this->start_controls_section('meta_style_sec', ['label' => 'Meta Data', 'tab' => Controls_Manager::TAB_STYLE]);
        $this->add_control('meta_color', ['label' => 'Text Color', 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .meta-row' => 'color: {{VALUE}};']]);
        $this->add_control('meta_icon_color', ['label' => 'Icon Color', 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .meta-item i' => 'color: {{VALUE}};']]);
        $this->add_group_control(Group_Control_Typography::get_type(), ['name' => 'meta_typo', 'selector' => '{{WRAPPER}} .meta-row']);
        $this->end_controls_section();

        // 4. Global Container
        $this->register_global_design_controls('.cora-article-card');
        $this->register_layout_geometry('.cora-article-card' );
        $this->register_surface_styles('.cora-article-card'  );
        
        $this->register_interaction_motion();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        // Link Wrapper Logic
        $wrapper_tag = 'div';
        $wrapper_attrs = 'class="cora-unit-container cora-article-card"';
        if ( ! empty( $settings['link']['url'] ) ) {
            $wrapper_tag = 'a';
            $this->add_link_attributes( 'card_link', $settings['link'] );
            $wrapper_attrs = 'class="cora-unit-container cora-article-card" ' . $this->get_render_attribute_string( 'card_link' );
        }
        ?>
        <<?php echo $wrapper_tag; ?> <?php echo $wrapper_attrs; ?>>
            
            <?php if ( ! empty( $settings['thumbnail']['url'] ) ) : ?>
                <img src="<?php echo esc_url($settings['thumbnail']['url']); ?>" class="article-thumb" alt="Article Thumbnail">
            <?php endif; ?>

            <div class="article-content">
                <div class="meta-row">
                    <?php if ( ! empty( $settings['meta_date'] ) ) : ?>
                        <div class="meta-item">
                            <i class="far fa-calendar"></i>
                            <span><?php echo esc_html($settings['meta_date']); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if ( ! empty( $settings['meta_read_time'] ) ) : ?>
                        <div class="meta-item">
                            <i class="far fa-clock"></i>
                            <span><?php echo esc_html($settings['meta_read_time']); ?></span>
                        </div>
                    <?php endif; ?>
                </div>

                <h3 class="article-title"><?php echo esc_html($settings['title']); ?></h3>
                <p class="article-desc"><?php echo esc_html($settings['description']); ?></p>
            </div>

        </<?php echo $wrapper_tag; ?>>
        <?php
    }
}