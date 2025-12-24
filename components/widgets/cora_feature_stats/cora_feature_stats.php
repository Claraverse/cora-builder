<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Feature_Stats extends Base_Widget {

    public function get_name() { return 'cora_feature_stats'; }
    public function get_title() { return __( 'Cora Feature Stats', 'cora-builder' ); }
    public function get_icon() { return 'eicon-number-field'; }

    protected function register_controls() {
        
        // --- TAB: CONTENT ---
        $this->start_controls_section('section_content', [ 'label' => __( 'Stats Data', 'cora-builder' ) ]);

        $repeater = new Repeater();
        $repeater->add_control('stat_value', [ 'label' => 'Stat Value', 'type' => Controls_Manager::TEXT, 'default' => '69+' ]);
        $repeater->add_control('stat_label', [ 'label' => 'Label', 'type' => Controls_Manager::TEXT, 'default' => 'Total Guides' ]);
        $repeater->add_control('stat_note', [ 'label' => 'Sub-note', 'type' => Controls_Manager::TEXT, 'default' => 'Comprehensive resources' ]);

        $this->add_control('stats', [
            'label' => 'Stat Items',
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                ['stat_value' => '69+', 'stat_label' => 'Total Guides', 'stat_note' => 'Comprehensive resources'],
                ['stat_value' => '87%', 'stat_label' => 'Avg. Completion', 'stat_note' => 'High engagement rate'],
                ['stat_value' => '~6 hrs', 'stat_label' => 'Time to Master', 'stat_note' => 'Per category average'],
                ['stat_value' => 'Weekly', 'stat_label' => 'Updated', 'stat_note' => 'Fresh content always'],
            ],
            'title_field' => '{{{ stat_label }}}',
        ]);

        $this->end_controls_section();

        // --- TAB: STYLE - GRID ENGINE ---
        $this->start_controls_section('style_grid', [ 'label' => 'Grid Engine', 'tab' => Controls_Manager::TAB_STYLE ]);
        
        $this->add_responsive_control('columns', [
            'label' => 'Columns',
            'type' => Controls_Manager::SLIDER,
            'range' => [ 'px' => [ 'min' => 1, 'max' => 6 ] ],
            'default' => [ 'size' => 4 ],
            'selectors' => [ '{{WRAPPER}} .stats-grid' => 'grid-template-columns: repeat({{SIZE}}, 1fr);' ],
        ]);

        $this->add_responsive_control('column_gap', [
            'label' => 'Gap',
            'type' => Controls_Manager::SLIDER,
            'selectors' => [ '{{WRAPPER}} .stats-grid' => 'gap: {{SIZE}}px;' ],
        ]);

        $this->end_controls_section();

        // --- TAB: STYLE - TEXT ENGINES ---
        $this->register_text_styling_controls('val_style', 'Value Styling (69+)', '{{WRAPPER}} .stat-val');
        $this->register_text_styling_controls('label_style', 'Label Styling', '{{WRAPPER}} .stat-label');
        $this->register_text_styling_controls('note_style', 'Note Styling', '{{WRAPPER}} .stat-note');

        $this->register_common_spatial_controls();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="cora-unit-container stats-grid">
            <?php foreach ( $settings['stats'] as $item ) : ?>
                <div class="stat-item">
                    <div class="stat-val"><?php echo esc_html( $item['stat_value'] ); ?></div>
                    <div class="stat-label"><?php echo esc_html( $item['stat_label'] ); ?></div>
                    <div class="stat-note"><?php echo esc_html( $item['stat_note'] ); ?></div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
    }
}