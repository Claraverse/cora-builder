<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Comparison_Table extends Base_Widget {

    public function get_name() { return 'cora_comparison_table'; }
    public function get_title() { return __( 'Cora Comparison Table', 'cora-builder' ); }

    protected function register_controls() {
        
        // --- TAB: CONTENT - TABLE STRUCTURE ---
        $this->start_controls_section('content', [ 'label' => __( 'Feature Matrix', 'cora-builder' ) ]);
        
        $repeater = new Repeater();
        $repeater->add_control('type', [
            'label' => 'Row Type',
            'type' => Controls_Manager::SELECT,
            'default' => 'feature',
            'options' => [ 'category' => 'Category Header', 'feature' => 'Feature Row' ]
        ]);
        
        $repeater->add_control('title', [ 'label' => 'Label', 'type' => Controls_Manager::TEXT, 'default' => 'CDN Integration' ]);
        $repeater->add_control('subtitle', [ 'label' => 'Small Subtext', 'type' => Controls_Manager::TEXT, 'default' => 'Global delivery' ]);
        
        // Cora Status
        $repeater->add_control('cora_status', [
            'label' => 'Cora Status',
            'type' => Controls_Manager::SELECT,
            'default' => 'check',
            'options' => [ 'check' => 'Checkmark', 'pill' => 'Status Pill', 'none' => 'None' ]
        ]);
        $repeater->add_control('cora_pill_text', [ 'label' => 'Cora Pill Text', 'type' => Controls_Manager::TEXT, 'default' => 'Automated' ]);

        // Traditional Status
        $repeater->add_control('trad_status', [
            'label' => 'Traditional Status',
            'type' => Controls_Manager::SELECT,
            'default' => 'pill',
            'options' => [ 'check' => 'Checkmark', 'pill' => 'Status Pill', 'none' => 'None' ]
        ]);
        $repeater->add_control('trad_pill_text', [ 'label' => 'Trad Pill Text', 'type' => Controls_Manager::TEXT, 'default' => 'Manual' ]);

        $this->add_control('rows', [
            'label' => 'Table Rows',
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'title_field' => '{{{ title }}}',
        ]);
        $this->end_controls_section();

        $this->register_common_spatial_controls(); 
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="cora-unit-container comp-table-wrapper">
            <div class="comp-table-header">
                <div class="header-col empty"></div>
                <div class="header-col cora-main">
                    <span class="brand">Cora</span>
                    <span class="sub">MANAGED PLATFORM</span>
                </div>
                <div class="header-col trad-side">
                    <span class="brand">Traditional</span>
                    <span class="sub">STANDARD HOSTING</span>
                </div>
            </div>

            <div class="comp-table-body">
                <?php foreach($settings['rows'] as $row) : ?>
                    <div class="comp-row type-<?php echo esc_attr($row['type']); ?>">
                        
                        <div class="comp-col feature-info">
                            <span class="f-title"><?php echo esc_html($row['title']); ?></span>
                            <?php if($row['subtitle']): ?>
                                <span class="f-sub"><?php echo esc_html($row['subtitle']); ?></span>
                            <?php endif; ?>
                        </div>

                        <div class="comp-col cora-val">
                            <?php if($row['cora_status'] == 'check'): ?>
                                <div class="check-box black"><i class="fas fa-check"></i></div>
                            <?php elseif($row['cora_status'] == 'pill'): ?>
                                <span class="status-pill automated"><?php echo esc_html($row['cora_pill_text']); ?></span>
                            <?php endif; ?>
                        </div>

                        <div class="comp-col trad-val">
                            <?php if($row['trad_status'] == 'check'): ?>
                                <div class="check-box gray"><i class="fas fa-check"></i></div>
                            <?php elseif($row['trad_status'] == 'pill'): ?>
                                <span class="status-pill manual"><?php echo esc_html($row['trad_pill_text']); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
}