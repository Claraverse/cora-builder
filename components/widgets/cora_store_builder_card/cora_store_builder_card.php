<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Store_Builder_Card extends Base_Widget {

    public function get_name() { return 'cora_store_builder_card'; }
    public function get_title() { return __( 'Cora Store Builder Card', 'cora-builder' ); }

    protected function register_controls() {
        
        // --- TAB: CONTENT ---
        $this->start_controls_section('content', [ 'label' => __( 'Card Content', 'cora-builder' ) ]);
        
        $this->add_control('badge_label', [ 'label' => 'Status Badge', 'type' => Controls_Manager::TEXT, 'default' => 'Coming Soon' ]);
        
        $this->add_control('title', [ 
            'label' => 'Headline', 
            'type' => Controls_Manager::TEXT, 
            'default' => 'Cora Store Builder',
            'dynamic' => ['active' => true] 
        ]);
        
        $this->add_control('desc', [
            'label' => 'Description',
            'type' => Controls_Manager::TEXTAREA,
            'default' => 'Drag-and-drop, pre-built store templates.',
            'dynamic' => ['active' => true]
        ]);

        $this->end_controls_section();

        // Style Engines
        $this->register_text_styling_controls('title_style', 'Headline Typography', '{{WRAPPER}} .builder-title');
        $this->register_common_spatial_controls(); 
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="cora-unit-container builder-card-dark">
            <div class="builder-card-header">
                <div class="builder-icon-box">
                    <i class="fas fa-sparkles"></i>
                </div>
                <div class="builder-status-badge">
                    <?php echo esc_html($settings['badge_label']); ?>
                </div>
            </div>

            <div class="builder-card-body">
                <h3 class="builder-title"><?php echo esc_html($settings['title']); ?></h3>
                <p class="builder-desc"><?php echo esc_html($settings['desc']); ?></p>
            </div>
        </div>
        <?php
    }
}