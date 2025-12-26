<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Category_Header extends Base_Widget {

    public function get_name() { return 'cora_category_header'; }
    public function get_title() { return __( 'Cora Category Header', 'cora-builder' ); }

    protected function register_controls() {
        
        // --- TAB: CONTENT ---
        $this->start_controls_section('intro', [ 'label' => 'Header Introduction' ]);
        $this->add_control('icon', [ 'label' => 'Category Icon', 'type' => Controls_Manager::MEDIA ]);
        $this->add_control('title', [ 'label' => 'Category Title', 'type' => Controls_Manager::TEXT, 'default' => 'Customer Service' ]);
        $this->add_control('description', [ 'label' => 'Description', 'type' => Controls_Manager::TEXTAREA, 'default' => 'Deliver exceptional customer experiences that build loyalty.' ]);
        $this->end_controls_section();

        $this->start_controls_section('stats', [ 'label' => 'Category Statistics' ]);
        $repeater = new Repeater();
        $repeater->add_control('stat_icon', [ 'label' => 'Stat Icon', 'type' => Controls_Manager::ICONS ]);
        $repeater->add_control('stat_label', [ 'label' => 'Stat Label', 'type' => Controls_Manager::TEXT, 'default' => 'Total Guides' ]);
        $repeater->add_control('stat_value', [ 'label' => 'Stat Value', 'type' => Controls_Manager::TEXT, 'default' => '2' ]);
        
        $this->add_control('stat_list', [
            'label' => 'Statistic Cards',
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [ ['stat_label' => 'Total Guides'], ['stat_label' => 'Avg Rating'], ['stat_label' => 'Total Views'] ],
        ]);
        $this->end_controls_section();

        // --- TAB: STYLE ---
        $this->start_controls_section('style_layout', [ 'label' => 'Spatial Controls', 'tab' => Controls_Manager::TAB_STYLE ]);
        $this->add_responsive_control('inner_padding', [
            'label' => 'Section Padding',
            'type' => Controls_Manager::DIMENSIONS,
            'selectors' => ['{{WRAPPER}} .category-header-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
        ]);
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="category-header-wrapper">
            <nav class="cat-breadcrumbs">
                <i class="fas fa-home"></i> Home &nbsp;>&nbsp; Categories &nbsp;>&nbsp; <?php echo esc_html($settings['title']); ?>
            </nav>

            <div class="cat-identity-grid">
                <div class="cat-icon-box">
                    <img src="<?php echo esc_url($settings['icon']['url']); ?>" alt="Icon">
                </div>
                <div class="cat-text-stack">
                    <h1 class="cat-h1"><?php echo esc_html($settings['title']); ?></h1>
                    <p class="cat-p"><?php echo esc_html($settings['description']); ?></p>
                </div>
            </div>

            <div class="cat-stats-matrix">
                <?php foreach ( $settings['stat_list'] as $item ) : ?>
                    <div class="cat-stat-card">
                        <div class="stat-top">
                            <span class="stat-ico"><?php \Elementor\Icons_Manager::render_icon( $item['stat_icon'] ); ?></span>
                            <span class="stat-lbl"><?php echo esc_html($item['stat_label']); ?></span>
                        </div>
                        <div class="stat-val"><?php echo esc_html($item['stat_value']); ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
}