<?php
namespace Cora_Builder\Components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Blog_Hero extends Base_Widget {

    public function get_name() {
        return 'cora_blog_hero';
    }

    public function get_title() {
        return 'Cora Blog Hero';
    }

    public function get_icon() {
        return 'eicon-post-list';
    }

    public function get_categories() {
        return [ 'cora_widgets' ];
    }

    // Helper to fetch categories for the dropdown
    protected function get_post_categories() {
        $categories = get_categories();
        $options = [];
        foreach ( $categories as $category ) {
            $options[ $category->term_id ] = $category->name;
        }
        return $options;
    }

    protected function register_controls() {

        // --- TAB: CONTENT ---
        $this->start_controls_section(
            'section_query',
            [
                'label' => 'Data Source',
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'query_source',
            [
                'label' => 'Source',
                'type' => Controls_Manager::SELECT,
                'default' => 'latest',
                'options' => [
                    'latest' => 'Latest Post',
                    'current' => 'Current Post (for Singles)',
                    'manual' => 'Manual Selection (by ID)',
                ],
            ]
        );

        $this->add_control(
            'category_ids',
            [
                'label' => 'Filter by Category',
                'type' => Controls_Manager::SELECT2,
                'options' => $this->get_post_categories(),
                'multiple' => true,
                'label_block' => true,
                'condition' => [ 'query_source' => 'latest' ],
            ]
        );

        $this->add_control(
            'manual_post_id',
            [
                'label' => 'Post ID',
                'type' => Controls_Manager::NUMBER,
                'condition' => [ 'query_source' => 'manual' ],
            ]
        );
        
        $this->add_control(
            'offset',
            [
                'label' => 'Offset',
                'type' => Controls_Manager::NUMBER,
                'default' => 0,
                'condition' => [ 'query_source' => 'latest' ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_elements',
            [
                'label' => 'Content Elements',
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_badge',
            [
                'label' => 'Show Badge',
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'badge_text',
            [
                'label' => 'Badge Text',
                'type' => Controls_Manager::TEXT,
                'default' => 'Featured',
                'dynamic' => [ 'active' => true ],
                'condition' => [ 'show_badge' => 'yes' ],
            ]
        );

        $this->add_control(
            'custom_title',
            [
                'label' => 'Override Title',
                'type' => Controls_Manager::TEXTAREA,
                'placeholder' => 'Leave empty to use Post Title',
                'dynamic' => [ 'active' => true ],
            ]
        );

        $this->add_control(
            'show_meta',
            [
                'label' => 'Show Meta Info',
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_excerpt',
            [
                'label' => 'Show Excerpt',
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'excerpt_length',
            [
                'label' => 'Excerpt length',
                'type' => Controls_Manager::NUMBER,
                'default' => 20,
                'condition' => [ 'show_excerpt' => 'yes' ],
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label' => 'Button Text',
                'type' => Controls_Manager::TEXT,
                'default' => 'Read More',
                'dynamic' => [ 'active' => true ],
            ]
        );

        $this->end_controls_section();


        // --- TAB: STYLE ---

        // 1. Card Container
        $this->start_controls_section(
            'section_style_card',
            [
                'label' => 'Card Container',
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'card_height',
            [
                'label' => 'Height',
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'vh' ],
                'range' => [ 'px' => [ 'min' => 300, 'max' => 1000 ] ],
                'selectors' => [ '{{WRAPPER}} .cora_blog_hero_card' => 'height: {{SIZE}}{{UNIT}};' ],
            ]
        );

        $this->add_responsive_control(
            'card_padding',
            [
                'label' => 'Padding',
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [ '{{WRAPPER}} .cora_hero_inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
            ]
        );
        
        $this->add_responsive_control(
            'content_align',
            [
                'label' => 'Content Alignment',
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [ 'title' => 'Left', 'icon' => 'eicon-text-align-left' ],
                    'center' => [ 'title' => 'Center', 'icon' => 'eicon-text-align-center' ],
                    'right' => [ 'title' => 'Right', 'icon' => 'eicon-text-align-right' ],
                ],
                'selectors' => [ 
                    '{{WRAPPER}} .cora_hero_inner' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .cora_hero_meta' => 'justify-content: {{VALUE}};',
                    '{{WRAPPER}} .cora_hero_action' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'card_border',
                'selector' => '{{WRAPPER}} .cora_blog_hero_card',
            ]
        );

        $this->add_responsive_control(
            'card_radius',
            [
                'label' => 'Border Radius',
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [ '{{WRAPPER}} .cora_blog_hero_card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'card_shadow',
                'selector' => '{{WRAPPER}} .cora_blog_hero_card',
            ]
        );

        $this->end_controls_section();

        // 2. Overlay
        $this->start_controls_section(
            'section_style_overlay',
            [
                'label' => 'Overlay',
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'overlay_background',
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .cora_hero_overlay',
            ]
        );
        
        $this->add_control(
            'overlay_opacity',
            [
                'label' => 'Opacity',
                'type' => Controls_Manager::SLIDER,
                'range' => [ 'px' => [ 'min' => 0, 'max' => 1, 'step' => 0.1 ] ],
                'selectors' => [ '{{WRAPPER}} .cora_hero_overlay' => 'opacity: {{SIZE}};' ],
            ]
        );

        $this->end_controls_section();

        // 3. Badge Style
        $this->start_controls_section(
            'section_style_badge',
            [
                'label' => 'Badge',
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [ 'show_badge' => 'yes' ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'badge_typography',
                'selector' => '{{WRAPPER}} .cora_hero_badge',
            ]
        );

        $this->add_responsive_control(
            'badge_padding',
            [
                'label' => 'Padding',
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [ '{{WRAPPER}} .cora_hero_badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
            ]
        );
        
        $this->add_responsive_control(
            'badge_radius',
            [
                'label' => 'Border Radius',
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [ '{{WRAPPER}} .cora_hero_badge' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;' ],
            ]
        );
        
        $this->add_responsive_control(
            'badge_margin_bottom',
            [
                'label' => 'Bottom Spacing',
                'type' => Controls_Manager::SLIDER,
                'selectors' => [ '{{WRAPPER}} .cora_hero_badge' => 'margin-bottom: {{SIZE}}px;' ],
            ]
        );

        $this->start_controls_tabs( 'tabs_badge_colors' );
        $this->start_controls_tab( 'tab_badge_normal', [ 'label' => 'Normal' ] );
        
        $this->add_control(
            'badge_bg_color',
            [
                'label' => 'Background',
                'type' => Controls_Manager::COLOR,
                'selectors' => [ '{{WRAPPER}} .cora_hero_badge' => 'background-color: {{VALUE}};' ],
            ]
        );

        $this->add_control(
            'badge_text_color',
            [
                'label' => 'Text Color',
                'type' => Controls_Manager::COLOR,
                'selectors' => [ '{{WRAPPER}} .cora_hero_badge' => 'color: {{VALUE}};' ],
            ]
        );
        
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();

        // 4. Title Style
        $this->start_controls_section(
            'section_style_title',
            [
                'label' => 'Title',
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .cora_hero_title a',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => 'Color',
                'type' => Controls_Manager::COLOR,
                'selectors' => [ '{{WRAPPER}} .cora_hero_title a' => 'color: {{VALUE}};' ],
            ]
        );

        $this->add_control(
            'title_hover_color',
            [
                'label' => 'Hover Color',
                'type' => Controls_Manager::COLOR,
                'selectors' => [ '{{WRAPPER}} .cora_hero_title a:hover' => 'color: {{VALUE}};' ],
            ]
        );
        
        $this->add_responsive_control(
            'title_margin_bottom',
            [
                'label' => 'Bottom Spacing',
                'type' => Controls_Manager::SLIDER,
                'selectors' => [ '{{WRAPPER}} .cora_hero_title' => 'margin-bottom: {{SIZE}}px;' ],
            ]
        );

        $this->end_controls_section();
        
        // 5. Meta Style
        $this->start_controls_section(
            'section_style_meta',
            [
                'label' => 'Meta Info',
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [ 'show_meta' => 'yes' ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'meta_typography',
                'selector' => '{{WRAPPER}} .cora_hero_meta',
            ]
        );
        
        $this->add_control(
            'meta_color',
            [
                'label' => 'Color',
                'type' => Controls_Manager::COLOR,
                'selectors' => [ '{{WRAPPER}} .cora_hero_meta' => 'color: {{VALUE}};' ],
            ]
        );
        
        $this->add_responsive_control(
            'meta_gap',
            [
                'label' => 'Gap Between Items',
                'type' => Controls_Manager::SLIDER,
                'selectors' => [ '{{WRAPPER}} .cora_hero_meta' => 'gap: {{SIZE}}px;' ],
            ]
        );
        
        $this->add_responsive_control(
            'meta_margin_bottom',
            [
                'label' => 'Bottom Spacing',
                'type' => Controls_Manager::SLIDER,
                'selectors' => [ '{{WRAPPER}} .cora_hero_meta' => 'margin-bottom: {{SIZE}}px;' ],
            ]
        );
        
        $this->end_controls_section();

        // 6. Button Style
        $this->start_controls_section(
            'section_style_button',
            [
                'label' => 'Button',
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'btn_typography',
                'selector' => '{{WRAPPER}} .cora_hero_btn',
            ]
        );

        $this->start_controls_tabs( 'tabs_button_style' );

        $this->start_controls_tab( 'tab_button_normal', [ 'label' => 'Normal' ] );
        $this->add_control(
            'btn_text_color',
            [
                'label' => 'Text Color',
                'type' => Controls_Manager::COLOR,
                'selectors' => [ '{{WRAPPER}} .cora_hero_btn' => 'color: {{VALUE}};' ],
            ]
        );
        $this->add_control(
            'btn_bg_color',
            [
                'label' => 'Background Color',
                'type' => Controls_Manager::COLOR,
                'selectors' => [ '{{WRAPPER}} .cora_hero_btn' => 'background-color: {{VALUE}};' ],
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab( 'tab_button_hover', [ 'label' => 'Hover' ] );
        $this->add_control(
            'btn_hover_text_color',
            [
                'label' => 'Text Color',
                'type' => Controls_Manager::COLOR,
                'selectors' => [ '{{WRAPPER}} .cora_hero_btn:hover' => 'color: {{VALUE}};' ],
            ]
        );
        $this->add_control(
            'btn_hover_bg_color',
            [
                'label' => 'Background Color',
                'type' => Controls_Manager::COLOR,
                'selectors' => [ '{{WRAPPER}} .cora_hero_btn:hover' => 'background-color: {{VALUE}};' ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_responsive_control(
            'btn_padding',
            [
                'label' => 'Padding',
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [ '{{WRAPPER}} .cora_hero_btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'btn_radius',
            [
                'label' => 'Border Radius',
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [ '{{WRAPPER}} .cora_hero_btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        // Build Query Arguments
        $args = [
            'post_type'      => 'post',
            'posts_per_page' => 1,
            'ignore_sticky_posts' => 1,
        ];

        if ( 'manual' === $settings['query_source'] && ! empty( $settings['manual_post_id'] ) ) {
            $args['p'] = $settings['manual_post_id'];
        } elseif ( 'current' === $settings['query_source'] ) {
            $args['p'] = get_the_ID();
        } else {
            if ( ! empty( $settings['offset'] ) ) {
                $args['offset'] = $settings['offset'];
            }
            if ( ! empty( $settings['category_ids'] ) ) {
                $args['category__in'] = $settings['category_ids'];
            }
        }

        $query = new \WP_Query( $args );

        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();
                
                $id = get_the_ID();
                $thumb_url = get_the_post_thumbnail_url( $id, 'full' );
                $title     = ! empty( $settings['custom_title'] ) ? $settings['custom_title'] : get_the_title();
                $author    = get_the_author();
                $date      = get_the_date( 'M d, Y' );
                
                // Read time logic
                $content    = get_post_field( 'post_content', $id );
                $word_count = str_word_count( strip_tags( $content ) );
                $read_time  = ceil( $word_count / 200 ) . ' min read';
                
                $bg_style = $thumb_url ? "background-image: url('{$thumb_url}');" : "background-color: #333;";
                ?>
                
                <div class="cora_blog_hero_card" style="<?php echo esc_attr( $bg_style ); ?>">
                    <div class="cora_hero_overlay"></div>
                    
                    <div class="cora_hero_inner">
                        
                        <?php if ( 'yes' === $settings['show_badge'] ) : ?>
                            <div class="cora_hero_badge_wrapper">
                                <span class="cora_hero_badge"><?php echo esc_html( $settings['badge_text'] ); ?></span>
                            </div>
                        <?php endif; ?>

                        <h2 class="cora_hero_title">
                            <a href="<?php the_permalink(); ?>"><?php echo esc_html( $title ); ?></a>
                        </h2>

                        <?php if ( 'yes' === $settings['show_excerpt'] ) : ?>
                            <div class="cora_hero_excerpt">
                                <p><?php echo wp_trim_words( get_the_excerpt(), $settings['excerpt_length'] ); ?></p>
                            </div>
                        <?php endif; ?>

                        <?php if ( 'yes' === $settings['show_meta'] ) : ?>
                        <div class="cora_hero_meta" style="display: flex; align-items: center; flex-wrap: wrap;">
                            <span class="cora_meta_item"><?php echo esc_html( $author ); ?></span>
                            <span class="cora_meta_item"><i class="eicon-calendar"></i> <?php echo esc_html( $date ); ?></span>
                            <span class="cora_meta_item"><i class="eicon-clock-o"></i> <?php echo esc_html( $read_time ); ?></span>
                        </div>
                        <?php endif; ?>

                        <div class="cora_hero_action">
                            <a href="<?php the_permalink(); ?>" class="cora_hero_btn">
                                <?php echo esc_html( $settings['button_text'] ); ?> 
                                <i class="eicon-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <?php
            }
            wp_reset_postdata();
        } else {
            if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
                echo '<div class="cora_alert">No posts found. Check your Query settings.</div>';
            }
        }
    }
}