<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Advanced_Bento extends Base_Widget {

    public function get_name() { return 'cora_advanced_bento'; }
    public function get_title() { return __( 'Cora Advanced Bento Grid', 'cora-builder' ); }

    protected function register_controls() {
        
        $this->start_controls_section('content', [ 'label' => 'Bento Items' ]);

        $repeater = new Repeater();
        
        // Dynamic Content
        $repeater->add_control('item_img', [ 'label' => 'Asset', 'type' => Controls_Manager::MEDIA, 'dynamic' => ['active' => true], 'default' => ['url' => Utils::get_placeholder_image_src()] ]);
        $repeater->add_control('item_title', [ 'label' => 'Title', 'type' => Controls_Manager::TEXT, 'dynamic' => ['active' => true] ]);
        
        // Advanced Grid Spanning Controls
        $repeater->add_responsive_control('col_span', [
            'label' => 'Column Span',
            'type' => Controls_Manager::SELECT,
            'default' => '1',
            'options' => [ '1' => '1 Col', '2' => '2 Cols', '3' => '3 Cols', '4' => '4 Cols' ],
            'selectors' => [ '{{WRAPPER}} {{CURRENT_ITEM}}' => 'grid-column: span {{VALUE}};' ],
        ]);

        $repeater->add_responsive_control('row_span', [
            'label' => 'Row Span',
            'type' => Controls_Manager::SELECT,
            'default' => '1',
            'options' => [ '1' => '1 Row', '2' => '2 Rows', '3' => '3 Rows' ],
            'selectors' => [ '{{WRAPPER}} {{CURRENT_ITEM}}' => 'grid-row: span {{VALUE}};' ],
        ]);

        $this->add_control('bento_items', [
            'label' => 'Bento Cells',
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'title_field' => '{{{ item_title }}}',
        ]);

        $this->end_controls_section();

        // --- TAB: STYLE - GRID CONFIG ---
        $this->start_controls_section('style_grid', [ 'label' => 'Grid Architecture', 'tab' => Controls_Manager::TAB_STYLE ]);
        
        $this->add_responsive_control('grid_columns', [
            'label' => 'Base Columns',
            'type' => Controls_Manager::NUMBER,
            'min' => 1, 'max' => 12, 'default' => 4,
            'selectors' => [ '{{WRAPPER}} .cora-bento-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);' ],
        ]);

        $this->add_responsive_control('grid_gap', [
            'label' => 'Cell Gap',
            'type' => Controls_Manager::SLIDER,
            'selectors' => [ '{{WRAPPER}} .cora-bento-grid' => 'gap: {{SIZE}}{{UNIT}} !important;' ],
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="cora-bento-grid">
            <?php foreach ( $settings['bento_items'] as $item ) : 
                $item_id = 'elementor-repeater-item-' . $item['_id'];
            ?>
                <div class="cora-bento-item <?php echo esc_attr($item_id); ?>">
                    <div class="bento-asset-wrapper">
                        <img src="<?php echo esc_url($item['item_img']['url']); ?>">
                    </div>
                    <div class="bento-content">
                        <h3><?php echo esc_html($item['item_title']); ?></h3>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
    }
}