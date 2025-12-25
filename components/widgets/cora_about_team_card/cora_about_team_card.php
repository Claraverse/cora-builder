<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_About_Team_Card extends Base_Widget {

    public function get_name() { return 'cora_about_team_card'; }
    public function get_title() { return __( 'Cora About Team Card', 'cora-builder' ); }

    protected function register_controls() {
        
        // --- TAB: CONTENT - PROFILE ---
        $this->start_controls_section('content', [ 'label' => __( 'Team Member Info', 'cora-builder' ) ]);
        $this->add_control('photo', [ 'label' => 'Profile Photo', 'type' => Controls_Manager::MEDIA ]);
        $this->add_control('vision_title', [ 'label' => 'Vision Title', 'type' => Controls_Manager::TEXT, 'default' => 'Voice Behind the Vision' ]);
        
        $this->add_control('bio', [
            'label' => 'Vision Statement',
            'type' => Controls_Manager::TEXTAREA,
            'default' => 'I shape content that connects — blending strategy, story, and style...',
            'dynamic' => ['active' => true]
        ]);

        $this->add_control('name', [ 'label' => 'Full Name', 'type' => Controls_Manager::TEXT, 'default' => 'Shruti B.', 'dynamic' => ['active' => true] ]);
        $this->add_control('role', [ 'label' => 'Professional Role', 'type' => Controls_Manager::TEXT, 'default' => 'Content Head', 'dynamic' => ['active' => true] ]);
        $this->end_controls_section();

        // Style Engines: Typo & Layout
        $this->register_text_styling_controls('vision_style', 'Vision Typography', '{{WRAPPER}} .vision-title');
        $this->register_alignment_controls('team_align', '.team-card-inner', '.photo-frame, .content-frame');
        $this->register_common_spatial_controls(); 
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="cora-unit-container team-card-split">
            <div class="team-card-inner">
                <div class="photo-frame">
                    <img src="<?php echo esc_url($settings['photo']['url']); ?>" class="headshot">
                </div>

                <div class="content-frame">
                    <div class="quote-icon"><i class="fas fa-quote-right"></i></div>
                    <h3 class="vision-title"><?php echo esc_html($settings['vision_title']); ?></h3>
                    <p class="vision-text"><?php echo esc_html($settings['bio']); ?></p>
                    
                    <div class="member-meta">
                        <span class="name"><?php echo esc_html($settings['name']); ?></span>
                        <span class="role"><?php echo esc_html($settings['role']); ?></span>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}