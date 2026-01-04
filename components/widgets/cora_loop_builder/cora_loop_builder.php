<?php
namespace Cora_Builder\components;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Loop_Builder extends Widget_Base {

    public function get_name() { return 'cora_loop_builder'; }
    public function get_title() { return __( 'Cora Loop Grid (Direct)', 'cora-builder' ); }
    public function get_icon() { return 'eicon-apps'; }
    public function get_categories() { return [ 'cora-components' ]; }

    protected function register_controls() {

        // --- LAYOUT SECTION ---
        $this->start_controls_section('layout_section', ['label' => 'Layout']);

        // 1. SKIN SELECTOR (Direct Widget Selection)
        $this->add_control('skin_type', [
            'label' => 'Card Design',
            'type' => Controls_Manager::SELECT,
            'default' => 'project',
            'options' => [
                'project'     => 'Project Card (Image + Text)',
                'solution'    => 'Solution Card (Mobile Mockup)',
                'deliverable' => 'Deliverable Card (Icon + Text)',
            ],
            'description' => 'Automatically maps Post Title, Excerpt, and Featured Image to the selected design.',
        ]);

        $this->add_responsive_control('columns', [
            'label' => 'Columns',
            'type' => Controls_Manager::SELECT,
            'default' => '3',
            'tablet_default' => '2',
            'mobile_default' => '1',
            'options' => [ '1'=>'1', '2'=>'2', '3'=>'3', '4'=>'4', '5'=>'5', '6'=>'6' ],
            'selectors' => [ '{{WRAPPER}} .clg-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);' ],
        ]);

        $this->add_responsive_control('gap', [
            'label' => 'Gap (px)',
            'type' => Controls_Manager::SLIDER,
            'range' => [ 'px' => [ 'min' => 0, 'max' => 100 ] ],
            'default' => [ 'size' => 30, 'unit' => 'px' ],
            'selectors' => [ '{{WRAPPER}} .clg-grid' => 'gap: {{SIZE}}{{UNIT}};' ],
        ]);

        $this->add_control('posts_per_page', [
            'label' => 'Items Per Page',
            'type' => Controls_Manager::NUMBER,
            'default' => 6,
        ]);

        $this->end_controls_section();

        // --- QUERY SECTION ---
        $this->start_controls_section('query_section', ['label' => 'Query']);

        $this->add_control('post_type', [
            'label' => 'Source',
            'type' => Controls_Manager::SELECT,
            'options' => $this->get_post_types(),
            'default' => 'post',
        ]);

        $this->add_control('orderby', [
            'label' => 'Order By',
            'type' => Controls_Manager::SELECT,
            'default' => 'date',
            'options' => [ 'date' => 'Date', 'title' => 'Title', 'rand' => 'Random' ],
        ]);

        $this->add_control('order', [
            'label' => 'Order',
            'type' => Controls_Manager::SELECT,
            'default' => 'DESC',
            'options' => [ 'ASC' => 'ASC', 'DESC' => 'DESC' ],
        ]);

        $this->end_controls_section();

        // --- STYLE SECTION (Simple Overrides) ---
        $this->start_controls_section('style_section', ['label' => 'Card Styling', 'tab' => Controls_Manager::TAB_STYLE]);
        
        $this->add_control('card_bg', [
            'label' => 'Card Background',
            'type' => Controls_Manager::COLOR,
            'default' => '#ffffff',
            'selectors' => [ '{{WRAPPER}} .clg-skin-card' => 'background-color: {{VALUE}};' ],
        ]);

        $this->add_control('accent_color', [
            'label' => 'Accent Color',
            'type' => Controls_Manager::COLOR,
            'default' => '#3b82f6',
            'selectors' => [
                '{{WRAPPER}} .clg-btn' => 'background-color: {{VALUE}};',
                '{{WRAPPER}} .clg-icon-box' => 'color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();
    }

    protected function get_post_types() {
        $post_types = get_post_types(['public' => true], 'objects');
        $options = [];
        foreach ($post_types as $post_type) {
            $options[$post_type->name] = $post_type->label;
        }
        return $options;
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        if (is_front_page()) $paged = (get_query_var('page')) ? get_query_var('page') : 1;

        $args = [
            'post_type'      => $settings['post_type'],
            'posts_per_page' => $settings['posts_per_page'],
            'orderby'        => $settings['orderby'],
            'order'          => $settings['order'],
            'paged'          => $paged,
            'post_status'    => 'publish',
        ];

        $query = new \WP_Query($args);
        $skin = $settings['skin_type'];

        if ($query->have_posts()) :
            ?>
            <style>
                .clg-grid { display: grid; width: 100%; }
                
                /* --- SHARED CARD BASE --- */
                .clg-skin-card {
                    display: flex; flex-direction: column;
                    background: #fff; border-radius: 24px; overflow: hidden;
                    border: 1px solid #f1f5f9;
                    transition: transform 0.3s ease, box-shadow 0.3s ease;
                    height: 100%; position: relative;
                }
                .clg-skin-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.08); }
                .clg-link-overlay { position: absolute; inset: 0; z-index: 10; cursor: pointer; }

                /* --- SKIN: PROJECT --- */
                .clg-skin-project .clg-img-wrap {
                    aspect-ratio: 16/10; overflow: hidden; background: #f8fafc;
                }
                .clg-skin-project img { width: 100%; height: 100%; object-fit: cover; transition: 0.5s; }
                .clg-skin-project:hover img { transform: scale(1.05); }
                .clg-skin-project .clg-content { padding: 24px; flex: 1; display: flex; flex-direction: column; }
                .clg-skin-project h3 { font-size: 20px; font-weight: 700; margin: 0 0 10px 0; color: #0f172a; }
                .clg-skin-project p { font-size: 15px; color: #64748b; line-height: 1.5; margin: 0 0 20px 0; flex: 1; }
                .clg-btn {
                    display: inline-flex; align-items: center; gap: 8px;
                    font-size: 14px; font-weight: 600; color: #fff;
                    padding: 10px 20px; border-radius: 50px;
                    align-self: flex-start; text-decoration: none;
                }

                /* --- SKIN: SOLUTION (Mobile Mockup) --- */
                .clg-skin-solution { padding: 20px 20px 0 20px; align-items: center; text-align: center; }
                .clg-skin-solution .clg-content { margin-bottom: 20px; }
                .clg-skin-solution h3 { font-size: 22px; font-weight: 800; margin-bottom: 8px; }
                .clg-skin-solution p { font-size: 14px; color: #64748b; margin-bottom: 0; }
                .clg-mockup-frame {
                    width: 100%; max-width: 220px;
                    border: 4px solid #1e293b; border-bottom: none;
                    border-radius: 20px 20px 0 0; overflow: hidden;
                    margin-top: auto; /* Push to bottom */
                    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
                }
                .clg-mockup-frame img { display: block; width: 100%; height: auto; }

                /* --- SKIN: DELIVERABLE (Icon) --- */
                .clg-skin-deliverable { padding: 32px; align-items: center; text-align: center; justify-content: center; }
                .clg-icon-box {
                    width: 60px; height: 60px; background: #f1f5f9;
                    border-radius: 16px; display: flex; align-items: center; justify-content: center;
                    font-size: 24px; margin-bottom: 20px;
                }
                .clg-icon-box img { width: 30px; height: 30px; object-fit: contain; } /* If featured image is icon */
                .clg-skin-deliverable h3 { font-size: 18px; font-weight: 700; margin-bottom: 8px; }
            </style>

            <div class="clg-grid">
                <?php while ($query->have_posts()) : $query->the_post(); 
                    $title = get_the_title();
                    $link = get_permalink();
                    $thumb_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
                    if(!$thumb_url) $thumb_url = \Elementor\Utils::get_placeholder_image_src();
                    $excerpt = wp_trim_words(get_the_excerpt(), 12, '...');
                ?>
                    
                    <div class="clg-item">
                        <a href="<?php echo $link; ?>" class="clg-link-overlay"></a>
                        
                        <?php if($skin === 'project') : ?>
                            <div class="clg-skin-card clg-skin-project">
                                <div class="clg-img-wrap">
                                    <img src="<?php echo esc_url($thumb_url); ?>" alt="<?php echo esc_attr($title); ?>">
                                </div>
                                <div class="clg-content">
                                    <h3><?php echo esc_html($title); ?></h3>
                                    <p><?php echo esc_html($excerpt); ?></p>
                                    <span class="clg-btn">View Details <i class="fas fa-arrow-right"></i></span>
                                </div>
                            </div>

                        <?php elseif($skin === 'solution') : ?>
                            <div class="clg-skin-card clg-skin-solution">
                                <div class="clg-content">
                                    <h3><?php echo esc_html($title); ?></h3>
                                    <p><?php echo esc_html($excerpt); ?></p>
                                </div>
                                <div class="clg-mockup-frame">
                                    <img src="<?php echo esc_url($thumb_url); ?>" alt="<?php echo esc_attr($title); ?>">
                                </div>
                            </div>

                        <?php elseif($skin === 'deliverable') : ?>
                            <div class="clg-skin-card clg-skin-deliverable">
                                <div class="clg-icon-box">
                                    <img src="<?php echo esc_url($thumb_url); ?>" alt="Icon">
                                </div>
                                <h3><?php echo esc_html($title); ?></h3>
                                <p><?php echo esc_html($excerpt); ?></p>
                            </div>
                        <?php endif; ?>

                    </div>

                <?php endwhile; ?>
            </div>

            <div style="margin-top:40px; text-align:center;">
                <?php echo paginate_links(['total' => $query->max_num_pages, 'current' => $paged]); ?>
            </div>

        <?php 
        wp_reset_postdata();
        endif;
    }
}