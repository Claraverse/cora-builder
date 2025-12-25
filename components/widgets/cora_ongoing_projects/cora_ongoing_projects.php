<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Ongoing_Projects extends Base_Widget {

    public function get_name() { return 'cora_ongoing_projects'; }
    public function get_title() { return __( 'Cora Ongoing Projects', 'cora-builder' ); }

    protected function register_controls() {
        
        // --- TAB: CONTENT - PROJECT DATA ---
        $this->start_controls_section('content', [ 'label' => __( 'Project Data', 'cora-builder' ) ]);
        
        $this->add_control('project_img', [ 'label' => 'Project Image', 'type' => Controls_Manager::MEDIA ]);
        $this->add_control('title', [ 
            'label' => 'Headline', 
            'type' => Controls_Manager::TEXT, 
            'default' => 'B2B Content Marketing: Strategies and Examples',
            'dynamic' => ['active' => true] 
        ]);

        $repeater = new Repeater();
        $repeater->add_control('tag_label', [ 'label' => 'Tag Label', 'type' => Controls_Manager::TEXT, 'default' => 'UI/UX' ]);
        
        $this->add_control('project_tags', [
            'label' => 'Technology Pills',
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [ ['tag_label' => 'UI/UX'], ['tag_label' => 'NextJs'] ]
        ]);

        $this->end_controls_section();

        $this->register_common_spatial_controls(); 
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="cora-unit-container project-card-v3">
            <div class="canvas-frame">
                <div class="canvas-media-inner">
                    <img src="<?php echo esc_url($settings['project_img']['url']); ?>" alt="Project Visual">
                </div>
                
                <div class="pill-hub">
                    <?php foreach ($settings['project_tags'] as $tag) : ?>
                        <div class="cora-glass-pill-v3">
                            <?php echo esc_html($tag['tag_label']); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <h3 class="p-card-title-v3"><?php echo esc_html($settings['title']); ?></h3>
        </div>
        <?php
    }
}