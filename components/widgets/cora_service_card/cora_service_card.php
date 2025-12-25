<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Service_Card extends Base_Widget {

    public function get_name() { return 'cora_service_card'; }
    public function get_title() { return __( 'Cora Service Card', 'cora-builder' ); }

    protected function register_controls() {
        
        // --- TAB: CONTENT - BADGE & TEXT ---
        $this->start_controls_section('content', [ 'label' => __( 'Service Content', 'cora-builder' ) ]);
        
        $this->add_control('badge_icon', [ 'label' => 'Badge Icon', 'type' => Controls_Manager::ICONS, 'default' => [ 'value' => 'fas fa-fire', 'library' => 'solid' ] ]);
        $this->add_control('badge_label', [ 'label' => 'Badge Label', 'type' => Controls_Manager::TEXT, 'default' => 'Design' ]);
        
        $this->add_control('title', [ 
            'label' => 'Title', 
            'type' => Controls_Manager::TEXT, 
            'default' => 'Clara – UI/UX & Prototype',
            'dynamic' => ['active' => true] 
        ]);
        
        $this->add_control('subtitle', [ 
            'label' => 'Subtitle', 
            'type' => Controls_Manager::TEXTAREA, 
            'default' => 'Get conversion-focused branding, UI, and systems tailored to your business goals — not likes.',
            'dynamic' => ['active' => true] 
        ]);

        $this->add_control('preview_image', [ 'label' => 'Preview Media', 'type' => Controls_Manager::MEDIA ]);
        $this->end_controls_section();

        // Style Engines
        $this->register_text_styling_controls('title_style', 'Title Typography', '{{WRAPPER}} .service-title');
        $this->register_common_spatial_controls(); 
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="cora-unit-container service-card-v1">
            <div class="card-header-group">
                <div class="service-badge">
                    <?php \Elementor\Icons_Manager::render_icon( $settings['badge_icon'] ); ?>
                    <span><?php echo esc_html($settings['badge_label']); ?></span>
                </div>
                <h3 class="service-title"><?php echo esc_html($settings['title']); ?></h3>
                <p class="service-subtitle"><?php echo esc_html($settings['subtitle']); ?></p>
            </div>
            
            <div class="service-media-frame">
                <img src="<?php echo esc_url($settings['preview_image']['url']); ?>" class="preview-img">
            </div>
        </div>
        <?php
    }
}