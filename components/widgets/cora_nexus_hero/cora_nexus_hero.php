<?php
namespace Cora_Builder\Components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;

if (!defined('ABSPATH'))
    exit;

class Cora_Nexus_Hero extends Base_Widget
{

    public function get_name()
    {
        return 'cora_nexus_hero';
    }
    public function get_title()
    {
        return __('Cora Nexus Hero (Optimized)', 'cora-builder');
    }
    public function get_icon()
    {
        return 'eicon-inner-section';
    }

    protected function register_controls()
    {

        // --- TAB: CONTENT ---
        $this->start_controls_section('content_main', ['label' => __('Main Messaging', 'cora-builder')]);

        $this->add_control('pill_text', [
            'label' => __('Announcement Pill', 'cora-builder'),
            'type' => Controls_Manager::TEXT,
            'default' => 'New Resources Added daily!!',
        ]);

        $this->add_control('headline_primary', [
            'label' => __('Primary Headline', 'cora-builder'),
            'type' => Controls_Manager::TEXTAREA,
            'default' => 'Master ecommerce with',
        ]);

        $this->add_control('headline_highlight', [
            'label' => __('High-Impact Word', 'cora-builder'),
            'type' => Controls_Manager::TEXT,
            'default' => 'guides',
        ]);

        $this->add_control('description', [
            'label' => __('Description', 'cora-builder'),
            'type' => Controls_Manager::TEXTAREA,
            'default' => 'Comprehensive, step-by-step guides for scaling your Shopify business.',
        ]);

        $this->end_controls_section();

        // --- TAB: STYLE (HEADLINE) ---
        $this->start_controls_section('style_headline', [
            'label' => __('Headline Style', 'cora-builder'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'headline_typography',
                'selector' => '{{WRAPPER}} .cora-nexus-headline',
            ]
        );

        $this->add_control('highlight_color', [
            'label' => __('Highlight Brush Color', 'cora-builder'),
            'type' => Controls_Manager::COLOR,
            'default' => '#000000',
            'selectors' => ['{{WRAPPER}} .brush-stroke' => 'fill: {{VALUE}};'],
        ]);

        $this->end_controls_section();

        // --- TAB: STYLE (TRUST CARDS) ---
        $this->start_controls_section('style_cards', [
            'label' => __('Trust Card Design', 'cora-builder'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('card_padding', [
            'label' => __('Card Internal Padding', 'cora-builder'),
            'type' => Controls_Manager::DIMENSIONS,
            'selectors' => ['{{WRAPPER}} .trust-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'card_shadow',
                'selector' => '{{WRAPPER}} .trust-item',
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="cora-nexus-wrapper">
            <div class="cora-nexus-container">

                <div class="cora-pill-anim">
                    <span class="pulse-dot"></span> <?php echo esc_html($settings['pill_text']); ?>
                </div>

                <h1 class="cora-nexus-headline">
                    <?php echo esc_html($settings['headline_primary']); ?>
                    <span class="cora-text-highlight">
                        <?php echo esc_html($settings['headline_highlight']); ?>
                        <svg class="brush-stroke" viewBox="0 0 500 150" preserveAspectRatio="none">
                            <path
                                d="M7.7,145.6C109,125,299.9,111.2,501,33.2c27.7-10.8,54.4,21.9,28.4,33.3C339.3,143.8,118.8,150.3,7.7,145.6Z">
                            </path>
                        </svg>
                    </span>
                </h1>

                <p class="cora-nexus-sub"><?php echo esc_html($settings['description']); ?></p>

                <div class="cora-nexus-btns">
                    <a href="#" class="cora-btn-main">Browse All Guides <i class="fas fa-arrow-right"></i></a>
                    <a href="#" class="cora-btn-ghost">View Categories</a>
                </div>

                <div class="cora-nexus-grid">
                    <div class="trust-item">
                        <div class="trust-icon">📚</div>
                        <strong>100+ Guides</strong>
                        <span>Comprehensive resources</span>
                    </div>
                    <div class="trust-item">
                        <div class="trust-icon">👥</div>
                        <strong>50K+ Users</strong>
                        <span>Active community</span>
                    </div>
                    <div class="trust-item">
                        <div class="trust-icon">⭐</div>
                        <strong>4.9/5 Rating</strong>
                        <span>Trusted quality</span>
                    </div>
                    <div class="trust-item">
                        <div class="trust-icon">🔄</div>
                        <strong>Weekly Updates</strong>
                        <span>Fresh content</span>
                    </div>
                </div>

            </div>
        </div>
        <?php
    }
}