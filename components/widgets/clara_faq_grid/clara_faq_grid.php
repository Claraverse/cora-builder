<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) exit;

class Clara_FAQ_Grid extends Base_Widget {

    public function get_name() { return 'clara_faq_grid'; }
    public function get_title() { return __( 'Clara Technical FAQ', 'clara-builder' ); }

    protected function register_controls() {
        
        // --- TAB: CONTENT - QUESTIONS ---
        $this->start_controls_section('content', [ 'label' => 'Technical Queries' ]);

        $repeater = new Repeater();
        $repeater->add_control('question', [ 
            'label' => 'Question Text', 
            'type' => Controls_Manager::TEXT, 
            'default' => 'How long will it take to see results?',
            'dynamic' => ['active' => true] 
        ]);
        $repeater->add_control('answer', [ 
            'label' => 'Answer Text', 
            'type' => Controls_Manager::TEXTAREA, 
            'default' => 'A typical Shopify Booster project goes live in 14 days...',
            'dynamic' => ['active' => true]
        ]);

        $this->add_control('faq_items', [
            'label' => 'FAQ Pairings',
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                ['question' => 'How long will it take to see results?'],
                ['question' => 'Do you guarantee results?'],
            ],
            'title_field' => '{{{ question }}}',
        ]);

        $this->end_controls_section();
        $this->register_common_spatial_controls(); 
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="clara-unit-container faq-grid-wrapper">
            <div class="faq-technical-matrix">
                <?php foreach ($settings['faq_items'] as $item) : ?>
                    <div class="faq-pair-unit">
                        <h4 class="faq-q-h4"><?php echo esc_html($item['question']); ?></h4>
                        <p class="faq-a-p"><?php echo esc_html($item['answer']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
}