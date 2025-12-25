<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Blog_Cat_Header extends Base_Widget {

    public function get_name() { return 'cora_blog_cat_header'; }
    public function get_title() { return __( 'Cora Blog Cat Header', 'cora-builder' ); }

    protected function register_controls() {
        
        // --- TAB: CONTENT - LEFT SIDE ---
        $this->start_controls_section('left_content', [ 'label' => __( 'Headline Content', 'cora-builder' ) ]);
        $this->add_control('back_text', [ 'label' => 'Back Link Text', 'type' => Controls_Manager::TEXT, 'default' => 'Back to Solutions' ]);
        $this->add_control('icon', [ 'label' => 'Title Icon', 'type' => Controls_Manager::ICONS ]);
        $this->add_control('title', [ 'label' => 'Headline', 'type' => Controls_Manager::TEXT, 'default' => 'Slow Product Development', 'dynamic' => ['active' => true] ]);
        $this->add_control('subtitle', [ 'label' => 'Description', 'type' => Controls_Manager::TEXTAREA, 'default' => 'Accelerate your development cycle...', 'dynamic' => ['active' => true] ]);
        
        $this->add_control('article_count', [ 'label' => 'Article count', 'type' => Controls_Manager::TEXT, 'default' => '3 Articles' ]);
        $this->add_control('author_type', [ 'label' => 'Author label', 'type' => Controls_Manager::TEXT, 'default' => 'Expert Authors' ]);
        $this->end_controls_section();

        // --- TAB: CONTENT - SOLUTION CARD ---
        $this->start_controls_section('right_card', [ 'label' => __( 'Solution Card', 'cora-builder' ) ]);
        $this->add_control('card_badge', [ 'label' => 'Badge Label', 'type' => Controls_Manager::TEXT, 'default' => 'How We Can Help' ]);
        $this->add_control('card_title', [ 'label' => 'Card Title', 'type' => Controls_Manager::TEXT, 'default' => 'Rapid Development Solutions' ]);
        
        $repeater = new Repeater();
        $repeater->add_control('item', [ 'label' => 'List Item', 'type' => Controls_Manager::TEXT, 'default' => 'Agile Sprints' ]);
        $this->add_control('service_list', [ 'label' => 'Services', 'type' => Controls_Manager::REPEATER, 'fields' => $repeater->get_controls() ]);
        $this->end_controls_section();

        // Style Engines
        $this->register_text_styling_controls('title_style', 'Headline Typography', '{{WRAPPER}} .main-headline');
        $this->register_common_spatial_controls(); 
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="cora-unit-container blog-cat-header-row">
            <div class="header-left">
                <a href="#" class="back-link"><i class="fas fa-arrow-left"></i> <?php echo esc_html($settings['back_text']); ?></a>
                <div class="title-icon-wrapper">
                    <?php \Elementor\Icons_Manager::render_icon( $settings['icon'] ); ?>
                </div>
                <h1 class="main-headline"><?php echo esc_html($settings['title']); ?></h1>
                <p class="main-subtitle"><?php echo esc_html($settings['subtitle']); ?></p>
                <div class="header-meta-row">
                    <span><i class="far fa-book"></i> <?php echo esc_html($settings['article_count']); ?></span>
                    <span><i class="far fa-user"></i> <?php echo esc_html($settings['author_type']); ?></span>
                    <span class="curated-badge">Curated Content</span>
                </div>
            </div>

            <div class="header-right">
                <div class="solution-card">
                    <span class="card-badge"><i class="fas fa-bolt"></i> <?php echo esc_html($settings['card_badge']); ?></span>
                    <h3 class="card-title"><?php echo esc_html($settings['card_title']); ?></h3>
                    <div class="solution-list">
                        <?php foreach($settings['service_list'] as $item) : ?>
                            <div class="list-item"><i class="far fa-check-circle"></i> <?php echo esc_html($item['item']); ?></div>
                        <?php endforeach; ?>
                    </div>
                    <a href="#" class="card-cta-btn">Learn More About This Service <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
        <?php
    }
}