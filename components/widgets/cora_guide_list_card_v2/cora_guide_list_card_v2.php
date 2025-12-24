<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Guide_List_Card_V2 extends Base_Widget {

    public function get_name() { return 'cora_guide_list_card_v2'; }
    public function get_title() { return __( 'Cora Guide List V2', 'cora-builder' ); }
    public function get_icon() { return 'eicon-post-list'; }

    protected function register_controls() {
        
        // --- TAB: CONTENT ---
        $this->start_controls_section('section_header', [ 'label' => __( 'Card Header', 'cora-builder' ) ]);
        $this->add_control('icon', [ 'label' => 'Header Icon', 'type' => Controls_Manager::ICONS ]);
        $this->add_control('badge_text', [ 'label' => 'Badge Text', 'type' => Controls_Manager::TEXT, 'default' => 'Beginner', 'dynamic' => ['active' => true] ]);
        $this->add_control('avg_time', [ 'label' => 'Avg Time Text', 'type' => Controls_Manager::TEXT, 'default' => '8 min avg' ]);
        $this->add_control('title', [ 'label' => 'Title', 'type' => Controls_Manager::TEXT, 'default' => 'Getting Started', 'dynamic' => ['active' => true] ]);
        $this->add_control('desc', [ 'label' => 'Description', 'type' => Controls_Manager::TEXTAREA, 'default' => 'Everything you need to launch...', 'dynamic' => ['active' => true] ]);
        
        $tag_repeater = new Repeater();
        $tag_repeater->add_control('tag_label', [ 'label' => 'Tag', 'type' => Controls_Manager::TEXT ]);
        $this->add_control('header_tags', [ 'label' => 'Category Tags', 'type' => Controls_Manager::REPEATER, 'fields' => $tag_repeater->get_controls() ]);
        $this->end_controls_section();

        $this->start_controls_section('section_guides', [ 'label' => __( 'Popular Guides', 'cora-builder' ) ]);
        $this->add_control('list_title', [ 'label' => 'List Title', 'type' => Controls_Manager::TEXT, 'default' => 'Popular Guides' ]);
        $this->add_control('list_total', [ 'label' => 'Total Count Text', 'type' => Controls_Manager::TEXT, 'default' => '12 total' ]);
        
        $guide_repeater = new Repeater();
        $guide_repeater->add_control('guide_name', [ 'label' => 'Name', 'type' => Controls_Manager::TEXT, 'dynamic' => ['active' => true] ]);
        $guide_repeater->add_control('guide_time', [ 'label' => 'Time', 'type' => Controls_Manager::TEXT, 'default' => '12 min' ]);
        $this->add_control('guides', [ 'label' => 'Guide Items', 'type' => Controls_Manager::REPEATER, 'fields' => $guide_repeater->get_controls() ]);
        $this->end_controls_section();

        // Style Engines
        $this->register_text_styling_controls('title_style', 'Main Title Styling', '{{WRAPPER}} .card-title');
        $this->register_common_spatial_controls();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="cora-unit-container guide-card-v2">
            <div class="v2-header">
                <div class="header-top-row">
                    <div class="v2-icon-box"><?php \Elementor\Icons_Manager::render_icon($settings['icon']); ?></div>
                    <div class="v2-badges">
                        <span class="badge-pill beginner"><?php echo esc_html($settings['badge_text']); ?></span>
                        <span class="badge-pill time"><?php echo esc_html($settings['avg_time']); ?></span>
                    </div>
                </div>
                <h2 class="card-title"><?php echo esc_html($settings['title']); ?></h2>
                <p class="card-desc"><?php echo esc_html($settings['desc']); ?></p>
                <div class="v2-tag-row">
                    <?php foreach($settings['header_tags'] as $tag) : ?>
                        <span class="header-tag"><?php echo esc_html($tag['tag_label']); ?></span>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="v2-list-section">
                <div class="list-header">
                    <span class="list-label"><?php echo esc_html($settings['list_title']); ?></span>
                    <span class="list-total"><?php echo esc_html($settings['list_total']); ?></span>
                </div>
                <div class="v2-guide-list">
                    <?php foreach($settings['guides'] as $guide) : ?>
                        <div class="v2-guide-item">
                            <div class="check-icon"><i class="fas fa-check-circle"></i></div>
                            <div class="guide-info">
                                <span class="guide-name"><?php echo esc_html($guide['guide_name']); ?></span>
                                <span class="guide-time"><?php echo esc_html($guide['guide_time']); ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="v2-footer">
                <a href="#" class="v2-primary-btn">Explore <?php echo esc_html($settings['title']); ?> <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
        <?php
    }
}