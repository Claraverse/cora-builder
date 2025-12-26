<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;

if (!defined('ABSPATH'))
    exit;

class Cora_Strategy_Hub extends Base_Widget
{

    public function get_name()
    {
        return 'cora_strategy_hub';
    }
    public function get_title()
    {
        return __('Cora Strategy Hub', 'cora-builder');
    }

    protected function register_controls()
    {

        // --- TAB: CONTENT ---
        $this->start_controls_section('content', ['label' => 'Content']);
        $this->add_control('title', [
            'label' => 'Headline',
            'type' => Controls_Manager::TEXT,
            'dynamic' => ['active' => true],
            'default' => 'Why Top Shopify Stores Choose Coraverse'
        ]);
        $this->add_control('subline', [
            'label' => 'Subline',
            'type' => Controls_Manager::TEXTAREA,
            'dynamic' => ['active' => true],
            'default' => 'Everything you need to launch a high-performing Shopify store...'
        ]);

        $repeater = new Repeater();
        $repeater->add_control('p_title', ['label' => 'Phase Title', 'type' => Controls_Manager::TEXT, 'dynamic' => ['active' => true]]);
        $repeater->add_control('p_desc', ['label' => 'Phase Description', 'type' => Controls_Manager::TEXTAREA, 'dynamic' => ['active' => true]]);
        $repeater->add_control('p_media', ['label' => 'Linked Media', 'type' => Controls_Manager::MEDIA, 'dynamic' => ['active' => true]]);

        $this->add_control('phases', [
            'label' => 'Strategy Phases',
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'title_field' => '{{{ p_title }}}',
        ]);
        $this->end_controls_section();

        // --- TAB: STYLE - SPATIAL CONTROLS ---
        $this->start_controls_section('style_layout', ['label' => 'Layout & Spacing', 'tab' => Controls_Manager::TAB_STYLE]);

        $this->add_responsive_control('intro_margin', [
            'label' => 'Intro Bottom Spacing',
            'type' => Controls_Manager::SLIDER,
            'selectors' => ['{{WRAPPER}} .strategy-intro' => 'margin-bottom: {{SIZE}}{{UNIT}} !important;'],
        ]);

        $this->add_responsive_control('grid_gap', [
            'label' => 'Column Gap',
            'type' => Controls_Manager::SLIDER,
            'selectors' => ['{{WRAPPER}} .strategy-interactive-grid' => 'gap: {{SIZE}}{{UNIT}} !important;'],
        ]);

        $this->add_responsive_control('node_padding', [
            'label' => 'Node Internal Padding',
            'type' => Controls_Manager::DIMENSIONS,
            'selectors' => ['{{WRAPPER}} .strategy-toggle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="cora-unit-container strategy-hub-wrapper" id="cora-strategy-engine">
            <div class="strategy-intro">
                <h2 class="strategy-h2"><?php echo $settings['title']; ?></h2>
                <p class="strategy-p"><?php echo $settings['subline']; ?></p>
            </div>

            <div class="strategy-interactive-grid">
                <div class="strategy-media-canvas">
                    <?php foreach ($settings['phases'] as $index => $item): ?>
                        <div class="media-layer <?php echo ($index === 0) ? 'active' : ''; ?>"
                            id="cora-media-<?php echo $index; ?>">
                            <img src="<?php echo esc_url($item['p_media']['url']); ?>">
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="strategy-accordion">
                    <?php foreach ($settings['phases'] as $index => $item): ?>
                        <div class="strategy-toggle <?php echo ($index === 0) ? 'active' : ''; ?>"
                            data-index="<?php echo $index; ?>">
                            <h3 class="toggle-h3"><?php echo $item['p_title']; ?></h3>
                            <div class="toggle-content">
                                <p><?php echo $item['p_desc']; ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const engine = document.getElementById('cora-strategy-engine');
                if (!engine) return;
                const toggles = engine.querySelectorAll('.strategy-toggle');
                const layers = engine.querySelectorAll('.media-layer');
                toggles.forEach(toggle => {
                    toggle.addEventListener('click', function () {
                        toggles.forEach(t => t.classList.remove('active'));
                        layers.forEach(l => l.classList.remove('active'));
                        this.classList.add('active');
                        engine.querySelector('#cora-media-' + this.getAttribute('data-index')).classList.add('active');
                    });
                });
            });
        </script>
        <?php
    }
}