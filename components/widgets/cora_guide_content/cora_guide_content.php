<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Guide_Content extends Base_Widget {

    public function get_name() { return 'cora_guide_content'; }
    public function get_title() { return __( 'Cora Guide Content Engine', 'cora-builder' ); }

    protected function register_controls() {
        
        // --- TAB: CONTENT - LESSON CHAPTERS ---
        $this->start_controls_section('chapters', [ 'label' => 'Lesson Chapters' ]);
        $repeater = new Repeater();
        $repeater->add_control('chap_title', [ 'label' => 'Chapter Title', 'type' => Controls_Manager::TEXT, 'dynamic' => ['active' => true] ]);
        $repeater->add_control('chap_duration', [ 'label' => 'Duration (min)', 'type' => Controls_Manager::TEXT ]);
        $repeater->add_control('chap_body', [ 'label' => 'Content', 'type' => Controls_Manager::WYSIWYG ]);
        
        $this->add_control('chapter_list', [
            'label' => 'Chapters',
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [ ['chap_title' => 'Introduction & Overview'], ['chap_title' => 'Getting Started'] ],
        ]);
        $this->end_controls_section();

        // --- TAB: CONTENT - INSTRUCTOR DATA ---
        $this->start_controls_section('instructor', [ 'label' => 'Instructor Profile' ]);
        $this->add_control('ins_img', [ 'label' => 'Photo', 'type' => Controls_Manager::MEDIA ]);
        $this->add_control('ins_name', [ 'label' => 'Name', 'type' => Controls_Manager::TEXT, 'default' => 'Sarah Mitchell' ]);
        $this->add_control('ins_role', [ 'label' => 'Role', 'type' => Controls_Manager::TEXT, 'default' => 'Former Head of Growth' ]);
        $this->end_controls_section();

        // --- TAB: STYLE - SPATIAL AUTHORITY ---
        $this->start_controls_section('style_layout', [ 'label' => 'Spatial Controls', 'tab' => Controls_Manager::TAB_STYLE ]);
        $this->add_responsive_control('sidebar_width', [
            'label' => 'Sidebar Width (%)',
            'type' => Controls_Manager::SLIDER,
            'range' => ['%' => ['min' => 20, 'max' => 40]],
            'default' => ['size' => 30],
            'selectors' => ['{{WRAPPER}} .guide-sidebar' => 'width: {{SIZE}}% !important;', '{{WRAPPER}} .guide-main' => 'width: calc(100% - {{SIZE}}% - 60px) !important;'],
        ]);
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="guide-content-layout">
            <div class="guide-main">
                <?php foreach ( $settings['chapter_list'] as $index => $chap ) : ?>
                    <section class="lesson-chapter" id="chap-<?php echo $index; ?>">
                        <div class="chapter-header">
                            <span class="chap-num">0<?php echo $index + 1; ?></span>
                            <div class="chap-head-text">
                                <h2 class="chap-h2"><?php echo esc_html($chap['chap_title']); ?></h2>
                                <span class="chap-time"><i class="far fa-clock"></i> <?php echo esc_html($chap['chap_duration']); ?> min</span>
                            </div>
                        </div>
                        <div class="chap-wysiwyg"><?php echo $chap['chap_body']; ?></div>
                    </section>
                <?php endforeach; ?>
            </div>

            <aside class="guide-sidebar">
                <div class="sidebar-card toc-card">
                    <h4 class="card-h4"><i class="fas fa-list-ul"></i> Table of Contents</h4>
                    <ul class="toc-list">
                        <?php foreach ( $settings['chapter_list'] as $index => $chap ) : ?>
                            <li class="toc-item">
                                <span class="toc-num">0<?php echo $index + 1; ?></span>
                                <div class="toc-meta">
                                    <span class="toc-title"><?php echo esc_html($chap['chap_title']); ?></span>
                                    <span class="toc-duration"><?php echo esc_html($chap['chap_duration']); ?> min</span>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="sidebar-card instructor-card">
                    <h4 class="card-h4">Your Instructor</h4>
                    <div class="ins-grid">
                        <img src="<?php echo esc_url($settings['ins_img']['url']); ?>" class="ins-photo">
                        <div class="ins-meta">
                            <strong><?php echo esc_html($settings['ins_name']); ?></strong>
                            <span><?php echo esc_html($settings['ins_role']); ?></span>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
        <?php
    }
}