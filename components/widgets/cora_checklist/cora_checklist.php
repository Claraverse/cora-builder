<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if (!defined('ABSPATH'))
    exit;

class Cora_Checklist extends Base_Widget
{

    public function get_name()
    {
        return 'cora_checklist';
    }
    public function get_title()
    {
        return __('Cora Checklist', 'cora-builder');
    }
    public function get_icon()
    {
        return 'eicon-editor-list-ul';
    }

    protected function register_controls()
    {

        // --- TAB: CONTENT ---
        $this->start_controls_section('content_section', ['label' => __('List Items', 'cora-builder')]);
        $repeater = new Repeater();
        $repeater->add_control('text', [
            'label' => 'Item Text',
            'type' => Controls_Manager::TEXTAREA,
            'dynamic' => ['active' => true]
        ]);
        $this->add_control('items', [
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                ['text' => 'Learn ecommerce principles from research...'],
                ['text' => 'Build real-world projects using tools like Shopify...']
            ],
            'title_field' => '{{{ text }}}',
        ]);
        $this->end_controls_section();

        // --- TAB: STYLE - ICON STYLING ---
        $this->start_controls_section('style_icon', ['label' => 'Icon Styling', 'tab' => Controls_Manager::TAB_STYLE]);
        $this->add_control('icon_type', [
            'label' => 'Checkmark Icon',
            'type' => Controls_Manager::ICONS,
            'default' => ['value' => 'fas fa-check-circle', 'library' => 'solid']
        ]);
        $this->add_control('icon_color', [
            'label' => 'Color',
            'type' => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .checklist-icon' => 'color: {{VALUE}};']
        ]);
        $this->add_responsive_control('icon_size', [
            'label' => 'Size',
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px', 'em', 'rem'],
            'selectors' => ['{{WRAPPER}} .checklist-icon i, {{WRAPPER}} .checklist-icon svg' => 'font-size: {{SIZE}}{{UNIT}};']
        ]);
        $this->add_responsive_control('icon_gap', [
            'label' => 'Space After Icon',
            'type' => Controls_Manager::SLIDER,
            'dynamic' => ['active' => true],
            'selectors' => ['{{WRAPPER}} .checklist-item' => 'gap: {{SIZE}}px;']
        ]);
        $this->end_controls_section();

        // --- TAB: STYLE - TEXT ENGINE ---
        $this->register_text_styling_controls('list_text', 'Item Text Styling', '{{WRAPPER}} .checklist-text');

        // --- TAB: STYLE - LAYOUT ---
        $this->register_common_spatial_controls(); // Max-Width, Vertical Gap, Padding
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="cora-unit-container cora-checklist-wrapper">
            <?php foreach ($settings['items'] as $item): ?>
                <div class="checklist-item">
                    <span class="checklist-icon"><?php \Elementor\Icons_Manager::render_icon($settings['icon_type']); ?></span>
                    <span class="checklist-text"><?php echo esc_html($item['text']); ?></span>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
    }
}