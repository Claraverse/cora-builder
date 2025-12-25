<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Mobile_App_Bar extends Base_Widget {

    public function get_name() { return 'cora_mobile_app_bar'; }
    public function get_title() { return __( 'Cora Mobile App Bar', 'cora-builder' ); }

    protected function register_controls() {
        
        // --- TAB: CONTENT - NAV ITEMS ---
        $this->start_controls_section('content', [ 'label' => __( 'Navigation Items', 'cora-builder' ) ]);
        
        $repeater = new Repeater();
        $repeater->add_control('nav_icon', [ 'label' => 'Icon', 'type' => Controls_Manager::ICONS ]);
        $repeater->add_control('nav_label', [ 'label' => 'Label', 'type' => Controls_Manager::TEXT, 'default' => 'Menu' ]);
        $repeater->add_control('nav_link', [ 'label' => 'Link', 'type' => Controls_Manager::URL ]);

        $this->add_control('nav_items', [
            'label' => 'Navigation Links',
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                ['nav_label' => 'Services'],
                ['nav_label' => 'Portfolio'],
            ],
            'title_field' => '{{{ nav_label }}}',
        ]);

        $this->add_control('fab_icon', [ 'label' => 'Central Action Icon', 'type' => Controls_Manager::ICONS, 'default' => [ 'value' => 'fas fa-headset', 'library' => 'solid' ] ]);
        $this->end_controls_section();

        // Style Engines
        $this->register_text_styling_controls('label_style', 'Label Typography', '{{WRAPPER}} .nav-label');
        $this->register_common_spatial_controls(); 
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $items = $settings['nav_items'];
        $half = ceil(count($items) / 2);
        $left_items = array_slice($items, 0, $half);
        $right_items = array_slice($items, $half);
        ?>
        <div class="cora-mobile-app-bar">
            <div class="app-bar-inner">
                <div class="nav-group">
                    <?php foreach ($left_items as $item) : ?>
                        <a href="<?php echo esc_url($item['nav_link']['url']); ?>" class="nav-item">
                            <?php \Elementor\Icons_Manager::render_icon( $item['nav_icon'] ); ?>
                            <span class="nav-label"><?php echo esc_html($item['nav_label']); ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>

                <div class="central-fab">
                    <div class="fab-circle">
                        <?php \Elementor\Icons_Manager::render_icon( $settings['fab_icon'] ); ?>
                    </div>
                </div>

                <div class="nav-group">
                    <?php foreach ($right_items as $item) : ?>
                        <a href="<?php echo esc_url($item['nav_link']['url']); ?>" class="nav-item">
                            <?php \Elementor\Icons_Manager::render_icon( $item['nav_icon'] ); ?>
                            <span class="nav-label"><?php echo esc_html($item['nav_label']); ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php
    }
}