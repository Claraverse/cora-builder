<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Blog_Single_Header extends Base_Widget {

    public function get_name() { return 'cora_blog_single_header'; }
    public function get_title() { return __( 'Cora Individual Blog Header', 'cora-builder' ); }

    protected function register_controls() {
        
        // --- TAB: CONTENT - ARTICLE DATA ---
        $this->start_controls_section('content', [ 'label' => __( 'Article Info', 'cora-builder' ) ]);
        
        $this->add_control('breadcrumb_text', [ 'label' => 'Breadcrumb Label', 'type' => Controls_Manager::TEXT, 'default' => 'Post' ]);
        
        $this->add_control('title', [ 
            'label' => 'Headline', 
            'type' => Controls_Manager::TEXTAREA, 
            'default' => 'ClickUp vs. Trello, Asana & Notion: Why ClickUp Wins',
            'dynamic' => ['active' => true] 
        ]);
        
        $this->add_control('excerpt', [ 
            'label' => 'Excerpt', 
            'type' => Controls_Manager::TEXTAREA, 
            'default' => 'Why Businesses Need the Right Project Management Tool...',
            'dynamic' => ['active' => true] 
        ]);

        $this->add_control('date', [ 'label' => 'Publish Date', 'type' => Controls_Manager::TEXT, 'default' => '13 August 2025', 'dynamic' => ['active' => true] ]);
        $this->add_control('read_time', [ 'label' => 'Reading Time', 'type' => Controls_Manager::TEXT, 'default' => '2 min read', 'dynamic' => ['active' => true] ]);
        
        $this->add_control('featured_image', [ 'label' => 'Featured Thumbnail', 'type' => Controls_Manager::MEDIA ]);
        
        $this->end_controls_section();

        // Style Engines
        $this->register_text_styling_controls('title_style', 'Headline Typography', '{{WRAPPER}} .article-title');
        $this->register_common_spatial_controls(); 
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="cora-unit-container blog-single-header-row">
            <div class="header-content-left">
                <nav class="breadcrumb-nav">
                    <i class="fas fa-home"></i> / <span><?php echo esc_html($settings['breadcrumb_text']); ?></span>
                </nav>
                <h1 class="article-title"><?php echo esc_html($settings['title']); ?></h1>
                <p class="article-excerpt"><?php echo esc_html($settings['excerpt']); ?></p>
                <div class="article-meta-row">
                    <span class="meta-item"><?php echo esc_html($settings['date']); ?></span>
                    <span class="meta-item"><?php echo esc_html($settings['read_time']); ?></span>
                </div>
            </div>

            <div class="header-media-right">
                <div class="thumb-frame">
                    <img src="<?php echo esc_url($settings['featured_image']['url']); ?>" class="featured-img">
                </div>
            </div>
        </div>
        <?php
    }
}