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
            'default' => [ ['chap_title' => 'Introduction & Overview', 'chap_duration'=>'5'], ['chap_title' => 'Getting Started', 'chap_duration'=>'10'] ],
        ]);
        $this->end_controls_section();

        // --- TAB: CONTENT - INSTRUCTOR DATA ---
        $this->start_controls_section('instructor', [ 'label' => 'Instructor Profile' ]);
        $this->add_control('ins_img', [ 'label' => 'Photo', 'type' => Controls_Manager::MEDIA, 'default' => ['url' => Utils::get_placeholder_image_src()] ]);
        $this->add_control('ins_name', [ 'label' => 'Name', 'type' => Controls_Manager::TEXT, 'default' => 'Sarah Mitchell' ]);
        $this->add_control('ins_role', [ 'label' => 'Role', 'type' => Controls_Manager::TEXT, 'default' => 'Former Head of Growth' ]);
        $this->end_controls_section();

        // --- TAB: STYLE - SPATIAL AUTHORITY ---
        $this->start_controls_section('style_layout', [ 'label' => 'Spatial Controls', 'tab' => Controls_Manager::TAB_STYLE ]);
        
        $this->add_responsive_control('layout_gap', [
            'label' => 'Column Gap',
            'type' => Controls_Manager::SLIDER,
            'default' => [ 'size' => 60 ],
            'selectors' => [ '{{WRAPPER}} .guide-content-layout' => 'gap: {{SIZE}}px;' ],
        ]);

        $this->add_responsive_control('sidebar_width', [
            'label' => 'Sidebar Width',
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ '%', 'px' ],
            'range' => ['%' => ['min' => 20, 'max' => 50]],
            'default' => ['size' => 30, 'unit' => '%'],
            'tablet_default' => ['size' => 100, 'unit' => '%'], // Auto stack on tablet
            'mobile_default' => ['size' => 100, 'unit' => '%'],
            'selectors' => [ '{{WRAPPER}} .guide-sidebar' => 'width: {{SIZE}}{{UNIT}};' ],
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <style>
            /* --- Global Layout Architecture --- */
            .guide-content-layout {
                display: flex;
                align-items: flex-start;
                width: 100%;
                box-sizing: border-box;
                /* Gap handled by control */
            }

            /* --- Main Lesson Column --- */
            .guide-main { 
                flex: 1; 
                min-width: 0; /* Prevents flex overflow on small screens */
            }

            .lesson-chapter { 
                padding-bottom: 60px; 
                border-bottom: 1px solid #e2e8f0; 
                margin-bottom: 60px; 
            }
            .lesson-chapter:last-child { border-bottom: none; margin-bottom: 0; }

            .chapter-header { 
                display: flex; 
                align-items: flex-start; 
                gap: 20px; 
                margin-bottom: 32px; 
            }

            .chap-num { 
                background: #f1f5f9; 
                width: clamp(40px, 5vw, 50px); 
                height: clamp(40px, 5vw, 50px); 
                border-radius: 50%; 
                display: flex; align-items: center; justify-content: center; 
                font-weight: 800; color: #64748b; 
                font-size: 14px;
                flex-shrink: 0;
            }

            .chap-head-text { flex: 1; }

            .chap-h2 { 
                margin: 0 0 8px 0; 
                /* Fluid Type: 24px mobile -> 36px desktop */
                font-size: clamp(24px, 3vw, 36px); 
                color: #1e293b; 
                font-weight: 750; 
                line-height: 1.2;
            }

            .chap-time { 
                font-size: 14px; color: #94a3b8; font-weight: 600; 
                display: inline-flex; align-items: center; gap: 6px;
            }

            .chap-wysiwyg {
                font-size: 17px;
                line-height: 1.7;
                color: #334155;
            }
            .chap-wysiwyg p { margin-bottom: 24px; }
            .chap-wysiwyg img { max-width: 100%; height: auto; border-radius: 12px; }

            /* --- Sidebar Hub --- */
            .guide-sidebar { 
                /* Width handled by control */
                position: sticky; 
                top: 40px; 
                flex-shrink: 0;
            }

            .sidebar-card { 
                background: #ffffff; 
                border: 1px solid #f1f5f9; 
                border-radius: 24px; 
                padding: 30px; 
                margin-bottom: 24px; 
                box-shadow: 0 10px 30px rgba(0,0,0,0.02);
            }

            .card-h4 { 
                margin: 0 0 20px 0; 
                font-size: 16px; 
                color: #1e293b; 
                font-weight: 700; 
                text-transform: uppercase; 
                letter-spacing: 0.5px;
            }

            /* TOC Styles */
            .toc-list { list-style: none; padding: 0; margin: 0; }
            .toc-item { 
                display: flex; gap: 15px; padding: 12px 0; 
                border-bottom: 1px solid #f8fafc; 
                cursor: pointer;
                transition: color 0.2s;
            }
            .toc-item:last-child { border-bottom: none; }
            .toc-item:hover .toc-title { color: #3b82f6; }

            .toc-num { 
                font-size: 12px; font-weight: 800; color: #cbd5e1; margin-top: 3px; 
                min-width: 15px;
            }
            .toc-title { 
                display: block; font-size: 14px; font-weight: 600; color: #475569; margin-bottom: 4px; line-height: 1.4; 
            }
            .toc-duration { font-size: 12px; color: #94a3b8; }

            /* Instructor Styles */
            .ins-grid { display: flex; align-items: center; gap: 16px; }
            .ins-photo { width: 56px; height: 56px; border-radius: 50%; object-fit: cover; }
            .ins-meta strong { display: block; font-size: 16px; color: #1e293b; margin-bottom: 2px; }
            .ins-meta span { font-size: 13px; color: #64748b; }

            /* --- RESPONSIVE LOGIC --- */
            
            /* Tablet Breakpoint (Sidebar Stacks) */
            @media (max-width: 1024px) {
                .guide-content-layout { 
                    flex-direction: column; 
                }
                
                .guide-sidebar { 
                    width: 100% !important; /* Force full width if control isn't used */
                    position: static; /* Remove sticky */
                    margin-top: 40px;
                    order: 2; /* Ensure sidebar comes after content on mobile if desired, or keep as is */
                }
                
                .sidebar-card { padding: 24px; }
            }

            /* Mobile Breakpoint */
            @media (max-width: 767px) {
                .chap-h2 { font-size: 24px; }
                .lesson-chapter { margin-bottom: 40px; padding-bottom: 40px; }
                .chap-num { width: 40px; height: 40px; font-size: 12px; }
            }
        </style>

        <div class="guide-content-layout">
            <div class="guide-main">
                <?php foreach ( $settings['chapter_list'] as $index => $chap ) : ?>
                    <section class="lesson-chapter" id="chap-<?php echo $index; ?>">
                        <div class="chapter-header">
                            <span class="chap-num">0<?php echo $index + 1; ?></span>
                            <div class="chap-head-text">
                                <h2 class="chap-h2"><?php echo esc_html($chap['chap_title']); ?></h2>
                                <?php if(!empty($chap['chap_duration'])) : ?>
                                    <span class="chap-time"><i class="far fa-clock"></i> <?php echo esc_html($chap['chap_duration']); ?> min</span>
                                <?php endif; ?>
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
                            <li class="toc-item" onclick="document.getElementById('chap-<?php echo $index; ?>').scrollIntoView({behavior: 'smooth'})">
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
                        <?php if(!empty($settings['ins_img']['url'])) : ?>
                            <img src="<?php echo esc_url($settings['ins_img']['url']); ?>" class="ins-photo" alt="Instructor">
                        <?php endif; ?>
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