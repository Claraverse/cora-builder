<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Course_Hero_Feature extends Base_Widget {

    public function get_name() { return 'cora_course_hero_feature'; }
    public function get_title() { return __( 'Cora Course Hero Feature', 'cora-builder' ); }
    public function get_icon() { return 'eicon-info-box'; }

    protected function register_controls() {
        
        // --- TAB: CONTENT ---
        $this->start_controls_section('section_content', [ 'label' => __( 'Features', 'cora-builder' ) ]);

        $repeater = new Repeater();

        $repeater->add_control('item_icon', [
            'label' => 'Icon',
            'type' => Controls_Manager::ICONS,
            'default' => [ 'value' => 'fas fa-book', 'library' => 'solid' ],
        ]);

        $repeater->add_control('item_title', [
            'label' => 'Title',
            'type' => Controls_Manager::TEXT,
            'default' => '100+ Guides',
            'dynamic' => ['active' => true],
        ]);

        $repeater->add_control('item_subtitle', [
            'label' => 'Subtitle',
            'type' => Controls_Manager::TEXT,
            'default' => 'Free access',
            'dynamic' => ['active' => true],
        ]);

        // Specific Item Styles (for different icon colors)
        $repeater->add_control('icon_bg_color', [
            'label' => 'Icon BG Color',
            'type' => Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} {{CURRENT_ITEM}} .icon-box' => 'background-color: {{VALUE}};' ],
        ]);

        $this->add_control('features_list', [
            'label' => 'Feature Items',
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                ['item_title' => '100+ Guides', 'item_subtitle' => 'Free access', 'icon_bg_color' => '#f0fdf4'],
                ['item_title' => '50K+ Users', 'item_subtitle' => 'Active learners', 'icon_bg_color' => '#f1f5f9'],
                ['item_title' => '4.9/5 Rating', 'item_subtitle' => 'Trusted quality', 'icon_bg_color' => '#fffbeb'],
                ['item_title' => 'Weekly Updates', 'item_subtitle' => 'Fresh content', 'icon_bg_color' => '#f1f5f9'],
            ],
            'title_field' => '{{{ item_title }}}',
        ]);

        $this->end_controls_section();

        // --- TAB: STYLE ---
        $this->start_controls_section('style_general', [ 'label' => 'Responsive Settings', 'tab' => Controls_Manager::TAB_STYLE ]);
        
        $this->add_responsive_control('item_gap', [
            'label' => 'Gap (Desktop/Tablet)',
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 0, 'max' => 100]],
            'default' => [ 'size' => 40 ],
            'selectors' => [ '{{WRAPPER}} .cora-features-row' => 'gap: {{SIZE}}{{UNIT}};' ],
        ]);

        $this->end_controls_section();

        // Structural Reset with Mobile Logic
        $this->start_controls_section('layout_reset', [ 'label' => 'Structural Layout', 'tab' => Controls_Manager::TAB_STYLE ]);
        $this->add_control('css_reset', [
            'type' => Controls_Manager::HIDDEN,
            'default' => 'reset',
            'selectors' => [
                '{{WRAPPER}} .cora-features-row' => 'display: flex; flex-wrap: wrap; align-items: center; justify-content: center; width: 100%;',
                '{{WRAPPER}} .feature-item' => 'display: flex; align-items: center; gap: 14px; padding: 5px; flex: 0 1 auto;',
                '{{WRAPPER}} .icon-box' => 'width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;',
                '{{WRAPPER}} .icon-box i' => 'font-size: 18px;',
                '{{WRAPPER}} .icon-box svg' => 'width: 20px; height: 20px;',
                '{{WRAPPER}} .text-box' => 'display: flex; flex-direction: column; min-width: 0;',
                '{{WRAPPER}} .feature-title' => 'margin: 0 !important; font-size: 16px; font-weight: 700; color: #1e2b5e; line-height: 1.2; white-space: nowrap;',
                '{{WRAPPER}} .feature-subtitle' => 'margin: 0 !important; font-size: 14px; color: #64748b; font-weight: 500; white-space: nowrap;',
                
                // Responsive Authority
                '@media (max-width: 1024px)' => [
                    '{{WRAPPER}} .cora-features-row' => 'justify-content: space-around; gap: 30px 20px;',
                    '{{WRAPPER}} .feature-item' => 'flex: 0 1 40%;', // Two items per row on tablet
                ],
                '@media (max-width: 767px)' => [
                    '{{WRAPPER}} .cora-features-row' => 'flex-direction: column; align-items: flex-start; gap: 16px;',
                    '{{WRAPPER}} .feature-item' => 'width: 100%; flex: 1 1 100%;',
                ],
            ],
        ]);
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="cora-features-row">
            <?php foreach ( $settings['features_list'] as $item ) : ?>
                <div class="feature-item elementor-repeater-item-<?php echo esc_attr( $item['_id'] ); ?>">
                    <div class="icon-box">
                        <?php \Elementor\Icons_Manager::render_icon( $item['item_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                    </div>
                    <div class="text-box">
                        <span class="feature-title"><?php echo esc_html($item['item_title']); ?></span>
                        <span class="feature-subtitle"><?php echo esc_html($item['item_subtitle']); ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
    }
}