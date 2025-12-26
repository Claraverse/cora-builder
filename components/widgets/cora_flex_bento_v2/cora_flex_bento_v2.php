<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Flex_Bento_V2 extends Base_Widget {

    public function get_name() { return 'cora_flex_bento_v2'; }
    public function get_title() { return __( 'Cora Flex-Bento Engine V2', 'cora-builder' ); }

    protected function register_controls() {
        
        $this->start_controls_section('content', [ 'label' => 'Bento Cells' ]);

        $repeater = new Repeater();
        
        // Dynamic Source Toggle: Placeholder vs Template
        $repeater->add_control('source_type', [
            'label' => 'Cell Source',
            'type' => Controls_Manager::SELECT,
            'default' => 'placeholder',
            'options' => [
                'placeholder' => 'Media & Title (Quick)',
                'template' => 'Elementor Template (Advanced)',
            ],
        ]);

        $repeater->add_control('cell_img', [ 
            'label' => 'Backdrop Asset', 
            'type' => Controls_Manager::MEDIA, 
            'dynamic' => ['active' => true],
            'condition' => ['source_type' => 'placeholder'] 
        ]);

        $repeater->add_control('template_id', [
            'label' => 'Choose Nested Template',
            'type' => Controls_Manager::SELECT2,
            'label_block' => true,
            'condition' => ['source_type' => 'template']
        ]);

        // Grid Span Logic
        $repeater->add_responsive_control('col_span', [
            'label' => 'Column Span',
            'type' => Controls_Manager::SELECT,
            'default' => '1',
            'options' => [ '1' => '1 Col', '2' => '2 Cols', '3' => '3 Cols' ],
            'selectors' => [ '{{WRAPPER}} {{CURRENT_ITEM}}' => 'grid-column: span {{VALUE}};' ],
        ]);

        $this->add_control('cells', [
            'label' => 'Bento Cells',
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
        ]);

        $this->end_controls_section();

        // --- TAB: STYLE - DYNAMIC SPACING ---
        $this->start_controls_section('style_grid', [ 'label' => 'Grid Controls', 'tab' => Controls_Manager::TAB_STYLE ]);
        $this->add_responsive_control('grid_gap', [
            'label' => 'Cell Gap',
            'type' => Controls_Manager::SLIDER,
            'selectors' => [ '{{WRAPPER}} .cora-bento-v2' => 'gap: {{SIZE}}{{UNIT}} !important;' ],
        ]);
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="cora-bento-v2">
            <?php foreach ( $settings['cells'] as $item ) : 
                $item_id = 'elementor-repeater-item-' . $item['_id'];
            ?>
                <div class="bento-v2-item <?php echo esc_attr($item_id); ?>">
                    <?php if ( 'placeholder' === $item['source_type'] ) : ?>
                        <div class="bento-bg-asset" style="background-image: url('<?php echo esc_url($item['cell_img']['url']); ?>');"></div>
                        <div class="bento-overlay"></div>
                    <?php else : ?>
                        <div class="bento-nested-host">
                            <?php echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $item['template_id'] ); ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
    }
}