<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Blog_Header extends Base_Widget {

    public function get_name() { return 'cora_blog_header'; }
    public function get_title() { return __( 'Cora Blog Header', 'cora-builder' ); }
    public function get_icon() { return 'eicon-heading'; }

    protected function register_controls() {
        
        // --- TAB: CONTENT ---
        $this->start_controls_section('section_content', [ 'label' => __( 'Headline Content', 'cora-builder' ) ]);

        $this->add_control('title_main', [
            'label'   => 'Main Title',
            'type'    => Controls_Manager::TEXT,
            'default' => 'Proven Success in',
            'dynamic' => [ 'active' => true ],
        ]);

        $this->add_control('title_highlight', [
            'label'   => 'Highlight Word',
            'type'    => Controls_Manager::TEXT,
            'default' => 'Your Industry',
            'dynamic' => [ 'active' => true ],
        ]);

        $this->add_control('subtitle', [
            'label'   => 'Subtitle',
            'type'    => Controls_Manager::TEXTAREA,
            'default' => 'Explore our curated selection of top blogs, offering expert insights and valuable tips for project management success.',
            'dynamic' => [ 'active' => true ],
        ]);

        $this->end_controls_section();

        // --- TAB: STYLE - TYPOGRAPHY ENGINES ---
        $this->register_text_styling_controls('main_style', 'Main Title Styling', '{{WRAPPER}} .main-text');
        $this->register_text_styling_controls('highlight_style', 'Highlight Styling', '{{WRAPPER}} .highlight-text');
        $this->register_text_styling_controls('sub_style', 'Subtitle Styling', '{{WRAPPER}} .subtitle-text');

        // Alignment Matrix & Spatial Engine
        $this->register_common_spatial_controls();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="cora-unit-container blog-header-container">
            <h2 class="blog-headline">
                <span class="main-text"><?php echo esc_html( $settings['title_main'] ); ?></span>
                <span class="highlight-text"><?php echo esc_html( $settings['title_highlight'] ); ?></span>
            </h2>
            <p class="subtitle-text"><?php echo esc_html( $settings['subtitle'] ); ?></p>
        </div>
        <?php
    }
}