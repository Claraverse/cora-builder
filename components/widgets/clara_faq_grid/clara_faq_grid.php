<?php
namespace Cora_Builder\components;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) exit;

class Clara_FAQ_Grid extends Widget_Base {

    public function get_name() { return 'clara_faq_grid'; }
    public function get_title() { return __( 'Cora FAQ Grid', 'clara-builder' ); }
    public function get_icon() { return 'eicon-help-o'; }
    public function get_categories() { return [ 'cora-components' ]; }

    protected function register_controls() {
        
        // --- CONTENT SECTION ---
        $this->start_controls_section('content', [ 'label' => 'FAQ Items' ]);

        $repeater = new Repeater();
        $repeater->add_control('question', [ 
            'label' => 'Question', 
            'type' => Controls_Manager::TEXT, 
            'default' => 'How long will it take to see results?',
            'label_block' => true,
        ]);
        $repeater->add_control('answer', [ 
            'label' => 'Answer', 
            'type' => Controls_Manager::TEXTAREA, 
            'default' => 'A typical project goes live in 14 days. Most clients see measurable improvements within the first 30-45 days.',
        ]);

        $this->add_control('faq_items', [
            'label' => 'Questions',
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                ['question' => 'How long will it take to see results?', 'answer' => 'A typical project goes live in 14 days. Most clients see measurable improvements within the first 30-45 days.'],
                ['question' => 'I already have a Shopify store. Can you improve it?', 'answer' => 'Absolutely. 60% of our clients come to us with existing stores. We optimize your design and flows without rebuilding from scratch.'],
                ['question' => 'Do you guarantee results?', 'answer' => 'Every store we build follows our proven Booster Framework — 96% of clients see measurable ROI within 60 days.'],
                ['question' => 'What’s included in the free Shopify Audit?', 'answer' => 'We’ll analyze your store’s speed, UX, checkout flow, and automation setup — showing you exactly where you’re losing sales.'],
            ],
            'title_field' => '{{{ question }}}',
        ]);

        $this->end_controls_section();

        // --- STYLE SECTION ---
        $this->start_controls_section('style_section', [ 'label' => 'Grid Styling', 'tab' => Controls_Manager::TAB_STYLE ]);

        $this->add_control('q_color', [
            'label' => 'Question Color',
            'type' => Controls_Manager::COLOR,
            'default' => '#1e293b', // Slate 800
            'selectors' => [ '{{WRAPPER}} .faq-q' => 'color: {{VALUE}}' ],
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'q_typography',
                'label' => 'Question Typography',
                'selector' => '{{WRAPPER}} .faq-q',
                'fields_options' => [
                    'font_weight' => [ 'default' => '700' ], // Match design weight
                    'font_size' => [ 'default' => [ 'unit' => 'px', 'size' => 18 ] ],
                ],
            ]
        );

        $this->add_control('a_color', [
            'label' => 'Answer Color',
            'type' => Controls_Manager::COLOR,
            'default' => '#475569', // Slate 600
            'selectors' => [ '{{WRAPPER}} .faq-a' => 'color: {{VALUE}}' ],
            'separator' => 'before',
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'a_typography',
                'label' => 'Answer Typography',
                'selector' => '{{WRAPPER}} .faq-a',
                'fields_options' => [
                    'font_size' => [ 'default' => [ 'unit' => 'px', 'size' => 16 ] ],
                    'line_height' => [ 'default' => [ 'unit' => 'em', 'size' => 1.6 ] ],
                ],
            ]
        );

        $this->add_responsive_control('col_gap', [
            'label' => 'Column Gap',
            'type' => Controls_Manager::SLIDER,
            'default' => [ 'size' => 80 ],
            'range' => [ 'px' => [ 'min' => 20, 'max' => 150 ] ],
            'selectors' => [ '{{WRAPPER}} .faq-matrix' => 'column-gap: {{SIZE}}px;' ],
            'separator' => 'before',
        ]);

        $this->add_responsive_control('row_gap', [
            'label' => 'Row Gap',
            'type' => Controls_Manager::SLIDER,
            'default' => [ 'size' => 50 ],
            'range' => [ 'px' => [ 'min' => 20, 'max' => 100 ] ],
            'selectors' => [ '{{WRAPPER}} .faq-matrix' => 'row-gap: {{SIZE}}px;' ],
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <style>
            .faq-matrix {
                display: grid;
                grid-template-columns: 1fr 1fr; /* 2 Columns by default */
                width: 100%;
                font-family: 'Inter', sans-serif;
            }

            .faq-item {
                display: flex;
                flex-direction: column;
                justify-content: flex-start;
                padding-bottom: 30px;
                border-bottom: 1px solid #e2e8f0; /* Subtle divider */
            }

            .faq-q {
                margin: 0 0 12px 0;
                line-height: 1.3;
            }

            .faq-a {
                margin: 0;
            }

            /* Responsive: Stack on mobile */
            @media (max-width: 768px) {
                .faq-matrix {
                    grid-template-columns: 1fr !important;
                    column-gap: 0 !important;
                    row-gap: 40px !important;
                }
            }
        </style>

        <div class="faq-matrix">
            <?php foreach ($settings['faq_items'] as $item) : ?>
                <div class="faq-item">
                    <h4 class="faq-q"><?php echo esc_html($item['question']); ?></h4>
                    <p class="faq-a"><?php echo esc_html($item['answer']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
    }
}