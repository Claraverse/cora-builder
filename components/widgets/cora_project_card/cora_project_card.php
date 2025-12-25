<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Project_Card extends Base_Widget {

    public function get_name() { return 'cora_project_card'; }
    public function get_title() { return __( 'Cora Project Card', 'cora-builder' ); }

    protected function register_controls() {
        
        // --- TAB: CONTENT - PROJECT DATA ---
        $this->start_controls_section('section_content', [ 'label' => __( 'Project Info', 'cora-builder' ) ]);
        $this->add_control('industry', [ 'label' => 'Industry Label', 'type' => Controls_Manager::TEXT, 'default' => 'Hospitality' ]);
        $this->add_control('title', [ 'label' => 'Project Name', 'type' => Controls_Manager::TEXT, 'default' => 'Siena Dubai', 'dynamic' => ['active' => true] ]);
        $this->add_control('desc', [ 'label' => 'Description', 'type' => Controls_Manager::TEXTAREA, 'default' => 'Siena Dubai is a casually chic Italian restaurant...', 'dynamic' => ['active' => true] ]);
        
        $this->add_control('page_count', [ 'label' => 'Pages count', 'type' => Controls_Manager::TEXT, 'default' => '8+', 'dynamic' => ['active' => true] ]);
        $this->add_control('duration', [ 'label' => 'Project Duration', 'type' => Controls_Manager::TEXT, 'default' => '12 Days', 'dynamic' => ['active' => true] ]);
        $this->end_controls_section();

        // --- TAB: CONTENT - MEDIA & AUTHOR ---
        $this->start_controls_section('section_meta', [ 'label' => __( 'Media & Author', 'cora-builder' ) ]);
        $this->add_control('image', [ 'label' => 'Project Image', 'type' => Controls_Manager::MEDIA ]);
        $this->add_control('author_img', [ 'label' => 'Author Photo', 'type' => Controls_Manager::MEDIA ]);
        $this->add_control('author_name', [ 'label' => 'Author Name', 'type' => Controls_Manager::TEXT, 'default' => 'Luca Romano' ]);
        $this->add_control('author_role', [ 'label' => 'Author Role', 'type' => Controls_Manager::TEXT, 'default' => 'General Manager' ]);
        $this->add_control('cta_url', [ 'label' => 'Project URL', 'type' => Controls_Manager::URL, 'dynamic' => ['active' => true] ]);
        $this->end_controls_section();

        // Style Engines
        $this->register_text_styling_controls('title_style', 'Project Title Styling', '{{WRAPPER}} .project-name');
        $this->register_common_spatial_controls(); 
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="cora-unit-container project-card-split">
            <div class="project-content-left">
                <span class="industry-tag"><?php echo esc_html($settings['industry']); ?></span>
                <h2 class="project-name"><?php echo esc_html($settings['title']); ?></h2>
                <p class="project-desc"><?php echo esc_html($settings['desc']); ?></p>
                
                <div class="project-stats-grid">
                    <div class="stat-box">
                        <span class="stat-label">Pages in Projects</span>
                        <span class="stat-val"><?php echo esc_html($settings['page_count']); ?></span>
                    </div>
                    <div class="stat-box">
                        <span class="stat-label">Project Duration</span>
                        <span class="stat-val"><?php echo esc_html($settings['duration']); ?></span>
                    </div>
                </div>

                <a <?php echo $this->get_render_attribute_string('cta_url'); ?> class="author-cta-pill">
                    <img src="<?php echo esc_url($settings['author_img']['url']); ?>" class="author-thumb">
                    <div class="author-info">
                        <span class="name"><?php echo esc_html($settings['author_name']); ?></span>
                        <span class="role"><?php echo esc_html($settings['author_role']); ?></span>
                    </div>
                    <i class="fas fa-arrow-right arrow-icon"></i>
                </a>
            </div>

            <div class="project-media-right">
                <img src="<?php echo esc_url($settings['image']['url']); ?>" class="main-project-img">
            </div>
        </div>
        <?php
    }
}