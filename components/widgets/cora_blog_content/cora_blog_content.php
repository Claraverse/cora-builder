<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Blog_Content extends Base_Widget {

    public function get_name() { return 'cora_blog_content'; }
    public function get_title() { return __( 'Cora Blog Content Body', 'cora-builder' ); }

    protected function register_controls() {
        
        // --- TAB: CONTENT ---
        $this->start_controls_section('content', [ 'label' => __( 'Article Content', 'cora-builder' ) ]);
        
        $this->add_control('show_share', [ 'label' => 'Show Share Sidebar', 'type' => Controls_Manager::SWITCHER, 'default' => 'yes' ]);
        
        $this->add_control('article_body', [
            'label' => 'Main Content',
            'type' => Controls_Manager::WYSIWYG,
            'default' => '<p>Start writing your industry-leading content here...</p>',
            'dynamic' => ['active' => true] // Pull from Post Content
        ]);

        $this->end_controls_section();

        // Style Engines: Typo & Table UI
        $this->register_text_styling_controls('body_typo', 'Body Text Styling', '{{WRAPPER}} .main-article-body');
        $this->register_common_spatial_controls(); 
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="cora-unit-container blog-content-layout">
            
            <?php if ( 'yes' === $settings['show_share'] ) : ?>
                <aside class="share-sidebar">
                    <span class="share-label">Share</span>
                    <div class="share-icons">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-x-twitter"></i></a>
                        <a href="#"><i class="fab fa-whatsapp"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </aside>
            <?php endif; ?>

            <article class="main-article-body">
                <?php echo $this->parse_text_editor( $settings['article_body'] ); ?>
            </article>

        </div>
        <?php
    }
}