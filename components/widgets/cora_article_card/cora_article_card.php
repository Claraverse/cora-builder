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
            'default' => [ 'url' => Utils::get_placeholder_image_src() ],
            'dynamic' => ['active' => true]
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

        $this->add_control('structural_reset', [
            'type' => Controls_Manager::HIDDEN,
            'default' => 'reset',
            'selectors' => [
                // Container: Use a fluid gap and responsive padding
                '{{WRAPPER}} .cora-article-card' => 'display: flex; flex-direction: row; align-items: stretch; gap: 4%; padding: 20px; background: #fff; text-decoration: none; width: 100%; box-sizing: border-box;',
                
                // Image Styling: Use flex-basis for fluid sizing on desktop
                '{{WRAPPER}} .article-thumb-wrapper' => 'flex: 0 0 30%; max-width: 220px; min-width: 140px;',
                '{{WRAPPER}} .article-thumb' => 'width: 100%; height: 100%; aspect-ratio: 3/2; border-radius: 12px; object-fit: cover;',
                
                // Content Column
                '{{WRAPPER}} .article-content' => 'display: flex; flex-direction: column; justify-content: center; gap: 8px; flex: 1; min-width: 0;',
                
                // Meta Row
                '{{WRAPPER}} .meta-row' => 'display: flex; flex-wrap: wrap; align-items: center; gap: 12px; color: #64748b; font-size: clamp(11px, 1vw, 13px); font-weight: 500;',
                '{{WRAPPER}} .meta-item' => 'display: flex; align-items: center; gap: 6px; white-space: nowrap;',
                
                // Typography Authority with Fluid Sizing
                '{{WRAPPER}} .article-title' => 'margin: 0 !important; font-size: clamp(16px, 2vw, 20px); font-weight: 700; color: #0f172a; line-height: 1.25;',
                '{{WRAPPER}} .article-desc' => 'margin: 0 !important; font-size: clamp(13px, 1.5vw, 15px); color: #475569; line-height: 1.5; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;',

                // Tablet Breakpoint
                '@media (max-width: 1024px)' => [
                    '{{WRAPPER}} .cora-article-card' => 'gap: 20px;',
                    '{{WRAPPER}} .article-thumb-wrapper' => 'flex: 0 0 35%;',
                ],

                // Mobile Breakpoint: Stack vertically
                '@media (max-width: 767px)' => [
                    '{{WRAPPER}} .cora-article-card' => 'flex-direction: column; align-items: flex-start; padding: 16px; gap: 16px;',
                    '{{WRAPPER}} .article-thumb-wrapper' => 'width: 100%; max-width: 100%; flex: 0 0 auto;',
                    '{{WRAPPER}} .article-thumb' => 'aspect-ratio: 16/9;',
                    '{{WRAPPER}} .article-title' => 'font-size: 18px;',
                    '{{WRAPPER}} .article-desc' => '-webkit-line-clamp: 3;', // Show more text on mobile
                ],
            ],
        ]);
        $this->end_controls_section();

        // --- CUSTOM STYLE CONTROLS ---
        $this->register_text_styling_controls('title_typo', 'Title Typography', '{{WRAPPER}} .article-title');
        $this->register_text_styling_controls('desc_typo', 'Excerpt Typography', '{{WRAPPER}} .article-desc');
        
        $this->start_controls_section('meta_style_sec', ['label' => 'Meta Data', 'tab' => Controls_Manager::TAB_STYLE]);
        $this->add_control('meta_color', ['label' => 'Text Color', 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .meta-row' => 'color: {{VALUE}};']]);
        $this->add_control('meta_icon_color', ['label' => 'Icon Color', 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .meta-item i' => 'color: {{VALUE}};']]);
        $this->end_controls_section();

        $this->register_global_design_controls('.cora-article-card');
        $this->register_layout_geometry('.cora-article-card' );
        $this->register_surface_styles('.cora-article-card'  );
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
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
                <div class="article-thumb-wrapper">
                    <img src="<?php echo esc_url($settings['thumbnail']['url']); ?>" class="article-thumb" alt="Article Thumbnail">
                </div>
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