<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Hero_Title extends Base_Widget {

    public function get_name() { return 'cora_hero_title'; }
    public function get_title() { return __( 'Cora Hero Title', 'cora-builder' ); }
    public function get_icon() { return 'eicon-t-letter'; }

    protected function register_controls() {
        
        // --- TAB: CONTENT ---
        $this->start_controls_section('section_content', [ 'label' => __( 'Headline Content', 'cora-builder' ) ]);

        $this->add_control('title_pre', [ 'label' => 'Prefix (From)', 'type' => Controls_Manager::TEXT, 'default' => 'From' ]);
        $this->add_control('title_sketch', [ 'label' => 'Sketch Word (Idea)', 'type' => Controls_Manager::TEXT, 'default' => 'Idea' ]);
        $this->add_control('title_mid', [ 'label' => 'Middle (to)', 'type' => Controls_Manager::TEXT, 'default' => 'to' ]);
        $this->add_control('title_highlight_1', [ 'label' => 'Highlight 1 (Website)', 'type' => Controls_Manager::TEXT, 'default' => 'Website' ]);
        $this->add_control('title_line_2_start', [ 'label' => 'Line 2 Start (That)', 'type' => Controls_Manager::TEXT, 'default' => 'That' ]);
        $this->add_control('title_highlight_2', [ 'label' => 'Highlight 2 (Sells)', 'type' => Controls_Manager::TEXT, 'default' => 'Sells' ]);
$this->add_control('title_sketch', [
    'label' => 'Sketch Word (Idea)',
    'type' => \Elementor\Controls_Manager::TEXT,
    'default' => 'Idea',
    'dynamic' => ['active' => true], // Fully Dynamic
]);
        $this->add_control('subtitle', [
            'label' => 'Subtitle',
            'type' => Controls_Manager::TEXTAREA,
            'default' => 'Launch, sell, and scale — without juggling teams.',
            'dynamic' => ['active' => true]
        ]);

        $this->end_controls_section();

        // Styling Engine for the Sketch Effect
$this->start_controls_section('style_sketch', [ 'label' => 'Sketch Style', 'tab' => \Elementor\Controls_Manager::TAB_STYLE ]);

$this->add_control('sketch_font_color', [
    'label' => 'Outline Color',
    'type' => \Elementor\Controls_Manager::COLOR,
    'selectors' => [ '{{WRAPPER}} .sketch-word' => '-webkit-text-stroke-color: {{VALUE}};' ],
]);

$this->add_responsive_control('sketch_stroke_width', [
    'label' => 'Stroke Width',
    'type' => \Elementor\Controls_Manager::SLIDER,
    'selectors' => [ '{{WRAPPER}} .sketch-word' => '-webkit-text-stroke-width: {{SIZE}}px;' ],
]);

$this->end_controls_section();

        // --- TAB: STYLE - TEXT ENGINES ---
        $this->register_text_styling_controls('main_typo', 'Primary Title Styling', '{{WRAPPER}} .hero-title');
        $this->register_text_styling_controls('sketch_typo', 'Sketch Word Styling', '{{WRAPPER}} .sketch-word');
        $this->register_text_styling_controls('highlight_typo', 'Highlight Styling', '{{WRAPPER}} .highlight-word');
        $this->register_text_styling_controls('sub_typo', 'Subtitle Styling', '{{WRAPPER}} .hero-subtitle');

        
        // Alignment Matrix
        $this->register_alignment_controls('hero_align', '.hero-title-container', '.hero-title, .hero-subtitle');
        $this->register_common_spatial_controls();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="cora-unit-container hero-title-container">
            <h1 class="hero-title">
                <span class="regular-word"><?php echo esc_html($settings['title_pre']); ?></span>
                <span class="sketch-word"><?php echo esc_html($settings['title_sketch']); ?></span>
                <span class="regular-word"><?php echo esc_html($settings['title_mid']); ?></span>
                <span class="highlight-word"><?php echo esc_html($settings['title_highlight_1']); ?></span>
                <br>
                <span class="regular-word"><?php echo esc_html($settings['title_line_2_start']); ?></span>
                <span class="highlight-word"><?php echo esc_html($settings['title_highlight_2']); ?></span>
            </h1>
            <p class="hero-subtitle"><?php echo esc_html($settings['subtitle']); ?></p>
        </div>
        <?php
    }
}