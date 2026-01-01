<?php
namespace Cora_Builder\Components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;

if (!defined('ABSPATH'))
    exit;

class Cora_Mission_Block extends Base_Widget
{
    public function get_name() { return 'cora_mission_block'; }
    public function get_title() { return 'Cora Mission Block'; }
    public function get_icon() { return 'eicon-info-box'; }

    // Load Fredoka Font Automatically
    public function get_style_depends() {
        wp_register_style('cora-google-fredoka', 'https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap', [], null);
        return ['cora-google-fredoka'];
    }

    protected function register_controls()
    {
        // --- CONTENT ---
        $this->start_controls_section('section_content', ['label' => 'Content']);

        $this->add_control('badge_text', [
            'label' => 'Badge Text',
            'type' => Controls_Manager::TEXT,
            'default' => 'Our mission',
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('title', [
            'label' => 'Headline',
            'type' => Controls_Manager::TEXTAREA,
            'default' => 'We Design. We Develop. You Dominate with Claraverse.',
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('desc', [
            'label' => 'Description',
            'type' => Controls_Manager::TEXTAREA,
            'default' => 'Remember that idea you had? the product you always wanted to create? We help you launch your ideas and achieve your goals much faster.',
            'dynamic' => ['active' => true],
        ]);

        $this->end_controls_section();

        // --- STATS REPEATER ---
        $this->start_controls_section('section_stats', ['label' => 'Statistics']);

        $repeater = new Repeater();

        $repeater->add_control('stat_num', [
            'label' => 'Number',
            'type' => Controls_Manager::TEXT,
            'default' => '3+',
        ]);

        $repeater->add_control('stat_label', [
            'label' => 'Label',
            'type' => Controls_Manager::TEXT,
            'default' => 'Years of experience.',
        ]);

        $this->add_control('stats_list', [
            'label' => 'Stats Items',
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                ['stat_num' => '3+', 'stat_label' => 'Years of experience.'],
                ['stat_num' => '$4M*', 'stat_label' => 'Revenue for our clients.'],
                ['stat_num' => '3', 'stat_label' => 'Active Products'],
                ['stat_num' => '100+', 'stat_label' => 'Projects completed'],
            ],
            'title_field' => '{{{ stat_label }}}',
        ]);

        $this->end_controls_section();

        // --- STYLE ---
        $this->start_controls_section('section_style', ['label' => 'Design', 'tab' => Controls_Manager::TAB_STYLE]);

        // Badge Style
        $this->add_control('badge_heading', ['label' => 'Badge', 'type' => Controls_Manager::HEADING]);
        
        $this->add_control('badge_bg', [
            'label' => 'Badge Background',
            'type' => Controls_Manager::COLOR,
            'default' => '#FFEDD5', // Light Orange
            'selectors' => ['{{WRAPPER}} .cora-badge' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_control('badge_color', [
            'label' => 'Badge Text Color',
            'type' => Controls_Manager::COLOR,
            'default' => '#C2410C', // Dark Orange
            'selectors' => ['{{WRAPPER}} .cora-badge' => 'color: {{VALUE}};'],
        ]);

        // Typography
        $this->add_control('typo_heading', ['label' => 'Typography', 'type' => Controls_Manager::HEADING, 'separator' => 'before']);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'title_typo',
            'label' => 'Headline',
            'selector' => '{{WRAPPER}} .cora-title',
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'stat_num_typo',
            'label' => 'Stat Number',
            'selector' => '{{WRAPPER}} .cora-stat-num',
        ]);

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $id = $this->get_id();
        ?>

        <style>
            /* Main Container */
            .cora-root-<?php echo $id; ?> {
                display: flex;
                flex-direction: column;
                align-items: flex-start;
                gap: 32px;
                width: 100%;
                max-width: 800px; /* Limits width for readability */
            }

            /* Badge (Pill) */
            .cora-root-<?php echo $id; ?> .cora-badge {
                display: inline-block;
                padding: 6px 16px;
                border-radius: 100px;
                font-family: "Fredoka", sans-serif;
                font-size: 14px;
                font-weight: 600;
                line-height: 1;
                background-color: #FFEDD5;
                color: #C2410C;
            }

            /* Headline */
            .cora-root-<?php echo $id; ?> .cora-title {
                margin: 0;
                font-family: "Fredoka", sans-serif;
                font-size: 48px;
                font-weight: 500;
                line-height: 1.1;
                color: #0F172A;
                letter-spacing: -0.02em;
            }

            /* Description */
            .cora-root-<?php echo $id; ?> .cora-desc {
                margin: 0;
                font-family: "Inter", sans-serif;
                font-size: 18px;
                line-height: 1.6;
                color: #334155;
            }

            /* Stats Grid */
            .cora-root-<?php echo $id; ?> .cora-stats-grid {
                display: grid;
                grid-template-columns: repeat(2, 1fr); /* 2 Columns */
                column-gap: 60px;
                row-gap: 40px;
                width: 100%;
                margin-top: 16px;
            }

            .cora-root-<?php echo $id; ?> .cora-stat-item {
                display: flex;
                flex-direction: column;
                gap: 8px;
            }

            .cora-root-<?php echo $id; ?> .cora-stat-num {
                font-family: "Fredoka", sans-serif;
                font-size: 42px;
                font-weight: 500;
                color: #0F172A;
                line-height: 1;
            }

            .cora-root-<?php echo $id; ?> .cora-stat-label {
                font-family: "Inter", sans-serif;
                font-size: 16px;
                color: #475569;
                line-height: 1.4;
            }

            /* Responsive */
            @media (max-width: 768px) {
                .cora-root-<?php echo $id; ?> .cora-title {
                    font-size: 36px;
                }
                .cora-root-<?php echo $id; ?> .cora-stats-grid {
                    column-gap: 24px; /* Tighter gap on mobile */
                }
            }
        </style>

        <div class="cora-unit-container cora-mission-root cora-root-<?php echo $id; ?>">
            
            <?php if (!empty($settings['badge_text'])) : ?>
                <span class="cora-badge"><?php echo esc_html($settings['badge_text']); ?></span>
            <?php endif; ?>

            <h2 class="cora-title"><?php echo esc_html($settings['title']); ?></h2>
            
            <p class="cora-desc"><?php echo esc_html($settings['description']); ?></p>

            <div class="cora-stats-grid">
                <?php foreach ($settings['stats_list'] as $item) : ?>
                    <div class="cora-stat-item">
                        <span class="cora-stat-num"><?php echo esc_html($item['stat_num']); ?></span>
                        <span class="cora-stat-label"><?php echo esc_html($item['stat_label']); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>

        </div>
        <?php
    }
}