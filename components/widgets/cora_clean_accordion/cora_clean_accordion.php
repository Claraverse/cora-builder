<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Clean_Accordion extends Base_Widget {

    public function get_name() { return 'cora_clean_accordion'; }
    public function get_title() { return __( 'Cora Clean Accordion', 'cora-builder' ); }

    protected function register_controls() {
        
        // --- TAB: CONTENT ---
        $this->start_controls_section('content', [ 'label' => 'Accordion Data' ]);
        $this->add_control('question', [ 'label' => 'Question', 'type' => Controls_Manager::TEXT, 'dynamic' => ['active' => true], 'default' => 'What is Coraverse?' ]);
        $this->add_control('answer', [ 'label' => 'Answer', 'type' => Controls_Manager::TEXTAREA, 'dynamic' => ['active' => true], 'default' => 'Coraverse is a high-performance Shopify ecosystem...' ]);
        $this->end_controls_section();

        // --- TAB: STYLE - SPATIAL ---
        $this->start_controls_section('style_spatial', [ 'label' => 'Layout & Spacing', 'tab' => Controls_Manager::TAB_STYLE ]);
        $this->add_responsive_control('inner_padding', [
            'label' => 'Internal Padding',
            'type' => Controls_Manager::DIMENSIONS,
            'selectors' => ['{{WRAPPER}} .cora-acc-header, {{WRAPPER}} .cora-acc-content' => 'padding-left: {{LEFT}}{{UNIT}} !important; padding-right: {{RIGHT}}{{UNIT}} !important;'],
        ]);
        $this->add_responsive_control('header_padding_v', [
            'label' => 'Header Vertical Padding',
            'type' => Controls_Manager::SLIDER,
            'selectors' => ['{{WRAPPER}} .cora-acc-header' => 'padding-top: {{SIZE}}{{UNIT}} !important; padding-bottom: {{SIZE}}{{UNIT}} !important;'],
        ]);
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $id = 'cora-acc-' . $this->get_id();
        ?>
        <div class="cora-acc-item" id="<?php echo esc_attr($id); ?>">
            <div class="cora-acc-header">
                <span class="acc-ques"><?php echo $settings['question']; ?></span>
                <span class="acc-icon">+</span>
            </div>
            <div class="cora-acc-content">
                <div class="acc-inner-text">
                    <p class="acc-ans"><?php echo $settings['answer']; ?></p>
                </div>
            </div>
        </div>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const accItem = document.getElementById('<?php echo esc_attr($id); ?>');
            const header = accItem.querySelector('.cora-acc-header');
            
            header.addEventListener('click', function() {
                accItem.classList.toggle('active');
                const content = accItem.querySelector('.cora-acc-content');
                
                if (accItem.classList.contains('active')) {
                    content.style.maxHeight = content.scrollHeight + "px";
                } else {
                    content.style.maxHeight = null;
                }
            });
        });
        </script>
        <?php
    }
}