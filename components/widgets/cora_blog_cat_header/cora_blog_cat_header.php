<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Blog_Cat_Header extends Base_Widget {

    public function get_name() { return 'cora_blog_cat_header'; }
    public function get_title() { return __( 'Cora Blog Cat Header', 'cora-builder' ); }
    public function get_icon() { return 'eicon-header'; }

    protected function register_controls() {
        
        // --- TAB: CONTENT ---
        $this->start_controls_section('left_content', [ 'label' => __( 'Headline Content', 'cora-builder' ) ]);
        $this->add_control('back_text', [ 'label' => 'Back Link Text', 'type' => Controls_Manager::TEXT, 'default' => 'Back to Solutions' ]);
        $this->add_control('back_link', [ 'label' => 'Back URL', 'type' => Controls_Manager::URL, 'dynamic' => ['active' => true] ]);
        $this->add_control('icon', [ 'label' => 'Title Icon', 'type' => Controls_Manager::ICONS, 'default' => [ 'value' => 'fas fa-bolt', 'library' => 'solid' ] ]);
        $this->add_control('title', [ 'label' => 'Headline', 'type' => Controls_Manager::TEXT, 'default' => 'Slow Product Development', 'dynamic' => ['active' => true] ]);
        $this->add_control('subtitle', [ 'label' => 'Description', 'type' => Controls_Manager::TEXTAREA, 'default' => 'Accelerate your development cycle...', 'dynamic' => ['active' => true] ]);
        $this->end_controls_section();

        $this->start_controls_section('right_card', [ 'label' => __( 'Solution Card', 'cora-builder' ) ]);
        $this->add_control('card_badge', [ 'label' => 'Badge', 'type' => Controls_Manager::TEXT, 'default' => 'How We Can Help' ]);
        $this->add_control('card_title', [ 'label' => 'Card Title', 'type' => Controls_Manager::TEXT, 'default' => 'Rapid Development Solutions' ]);
        
        $repeater = new Repeater();
        $repeater->add_control('item', [ 'label' => 'Item', 'type' => Controls_Manager::TEXT, 'default' => 'Agile Sprints' ]);
        $this->add_control('service_list', [ 'label' => 'Services', 'type' => Controls_Manager::REPEATER, 'fields' => $repeater->get_controls() ]);
        $this->add_control('cta_link', [ 'label' => 'CTA Link', 'type' => Controls_Manager::URL, 'dynamic' => ['active' => true] ]);
        $this->end_controls_section();

        // --- TAB: STYLE (This makes the style options appear!) ---
        
        // 1. Headline Style
        $this->start_controls_section('style_headline', [ 'label' => 'Headline', 'tab' => Controls_Manager::TAB_STYLE ]);
        $this->add_control('title_color', [
            'label' => 'Color',
            'type' => Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .main-headline' => 'color: {{VALUE}};' ],
        ]);
        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'title_typo',
            'selector' => '{{WRAPPER}} .main-headline',
        ]);
        $this->end_controls_section();

        // 2. Icon Style
        $this->start_controls_section('style_icon', [ 'label' => 'Icon Box', 'tab' => Controls_Manager::TAB_STYLE ]);
        $this->add_control('icon_color', [
            'label' => 'Icon Color',
            'type' => Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .title-icon-wrapper i, {{WRAPPER}} .title-icon-wrapper svg' => 'color: {{VALUE}}; fill: {{VALUE}};' ],
        ]);
        $this->add_control('icon_bg', [
            'label' => 'Background Color',
            'type' => Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .title-icon-wrapper' => 'background-color: {{VALUE}};' ],
        ]);
        $this->end_controls_section();

        // 3. Card Style
        $this->start_controls_section('style_card', [ 'label' => 'Solution Card', 'tab' => Controls_Manager::TAB_STYLE ]);
        $this->add_control('card_bg', [
            'label' => 'Card Background',
            'type' => Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .solution-card' => 'background-color: {{VALUE}};' ],
        ]);
        $this->add_group_control(Group_Control_Box_Shadow::get_type(), [
            'name' => 'card_shadow',
            'selector' => '{{WRAPPER}} .solution-card',
        ]);
        $this->end_controls_section();

        // --- Layout Authority (Enforcing the Design) ---
        $this->start_controls_section('layout_settings', [ 'label' => 'Structural Layout', 'tab' => Controls_Manager::TAB_STYLE ]);
        $this->add_control('layout_reset', [
            'type' => Controls_Manager::HIDDEN,
            'default' => 'reset',
            'selectors' => [
                '{{WRAPPER}} .blog-cat-header-row' => 'display: flex; justify-content: space-between; align-items: flex-start; gap: 40px; padding: 40px 0;',
                '{{WRAPPER}} .header-left' => 'flex: 1.2; display: flex; flex-direction: column; gap: 20px;',
                '{{WRAPPER}} .header-right' => 'flex: 1; max-width: 440px;',
                '{{WRAPPER}} .title-icon-wrapper' => 'width: 64px; height: 64px; border-radius: 16px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(0,0,0,0.05); background: #fff;',
                '{{WRAPPER}} .title-icon-wrapper i, {{WRAPPER}} .title-icon-wrapper svg' => 'width: 32px; height: 32px; font-size: 32px;',
                '{{WRAPPER}} .solution-card' => 'border-radius: 24px; padding: 40px; display: flex; flex-direction: column; gap: 20px; border: 1px solid #f1f5f9;',
                '{{WRAPPER}} .card-cta-btn' => 'background: #0f172a; color: #fff; text-align: center; padding: 18px; border-radius: 12px; text-decoration: none; font-weight: 700; display: flex; justify-content: center; gap: 10px;',
                '@media (max-width: 1024px)' => [
                    '{{WRAPPER}} .blog-cat-header-row' => 'flex-direction: column; align-items: flex-start;',
                    '{{WRAPPER}} .header-right' => 'max-width: 100%;',
                ],
            ],
        ]);
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $this->add_link_attributes('back_link', $settings['back_link']);
        $this->add_link_attributes('cta_link', $settings['cta_link']);
        ?>
        <div class="cora-unit-container blog-cat-header-row">
            <div class="header-left">
                <a <?php echo $this->get_render_attribute_string('back_link'); ?> class="back-link" style="font-size: 14px; font-weight: 700; color: #0f172a; text-decoration: none;">
                    <i class="fas fa-arrow-left"></i> <?php echo esc_html($settings['back_text']); ?>
                </a>
                
                <div class="title-icon-wrapper">
                    <?php if ( ! empty( $settings['icon']['value'] ) ) : ?>
                        <?php \Elementor\Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] ); ?>
                    <?php endif; ?>
                </div>

                <h1 class="main-headline" style="margin:0;"><?php echo esc_html($settings['title']); ?></h1>
                <p class="main-subtitle" style="margin:0; font-size: 18px; color: #475569; line-height: 1.6;"><?php echo esc_html($settings['subtitle']); ?></p>
            </div>

            <div class="header-right">
                <div class="solution-card">
                    <span class="card-badge" style="font-size: 12px; font-weight: 700; border: 1px solid #e2e8f0; border-radius: 100px; padding: 6px 14px; width: fit-content;">
                        <i class="fas fa-bolt"></i> <?php echo esc_html($settings['card_badge']); ?>
                    </span>
                    <h3 class="card-title" style="margin:0; font-size: 28px; font-weight: 800;"><?php echo esc_html($settings['card_title']); ?></h3>
                    
                    <div class="solution-list" style="display: flex; flex-direction: column; gap: 12px;">
                        <?php foreach($settings['service_list'] as $item) : ?>
                            <div class="list-item" style="display: flex; align-items: center; gap: 12px; color: #475569; font-weight: 600;">
                                <i class="far fa-check-circle" style="color: #22c55e;"></i> 
                                <span><?php echo esc_html($item['item']); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <a <?php echo $this->get_render_attribute_string('cta_link'); ?> class="card-cta-btn">
                        Learn More <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
        <?php
    }
}