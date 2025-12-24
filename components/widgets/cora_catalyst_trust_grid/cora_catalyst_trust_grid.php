<?php
namespace Cora_Builder\Components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if (!defined('ABSPATH'))
    exit;

class Cora_Catalyst_Trust_Grid extends Base_Widget
{

    public function get_name()
    {
        return 'cora_catalyst_trust_grid';
    }
    public function get_title()
    {
        return __('Cora Catalyst Trust Grid', 'cora-builder');
    }
    public function get_icon()
    {
        return 'eicon-inner-section';
    }

    protected function register_controls()
    {
        $this->start_controls_section('section_grid', ['label' => __('Metric Cards', 'cora-builder')]);

        $repeater = new Repeater();
        $repeater->add_control('title', ['label' => 'Title', 'type' => Controls_Manager::TEXT, 'default' => '100+ Guides']);
        $repeater->add_control('subtext', ['label' => 'Subtext', 'type' => Controls_Manager::TEXT, 'default' => 'Free access']);
        $repeater->add_control('icon', ['label' => 'Icon', 'type' => Controls_Manager::ICONS]);

        $this->add_control('cards', [
            'label' => __('Cards', 'cora-builder'),
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                ['title' => '100+ Guides', 'subtext' => 'Free access'],
                ['title' => '50K+ Users', 'subtext' => 'Active learners'],
            ],
        ]);

        $this->add_responsive_control('columns', [
            'label' => __('Columns', 'cora-builder'),
            'type' => Controls_Manager::SELECT,
            'default' => '4',
            'options' => ['1' => '1', '2' => '2', '4' => '4'],
            'selectors' => ['{{WRAPPER}} .cora-catalyst-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);'],
        ]);

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="cora-catalyst-grid">
            <?php foreach ($settings['cards'] as $card): ?>
                <div class="cora-catalyst-card">
                    <div class="card-icon"><?php \Elementor\Icons_Manager::render_icon($card['icon']); ?></div>
                    <div class="card-text">
                        <strong><?php echo esc_html($card['title']); ?></strong>
                        <span><?php echo esc_html($card['subtext']); ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
    }
}