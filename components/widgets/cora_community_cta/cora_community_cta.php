<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Community_CTA extends Base_Widget {

    public function get_name() { return 'cora_community_cta'; }
    public function get_title() { return __( 'Cora Community CTA', 'cora-builder' ); }

    protected function register_controls() {
        
        // --- TAB: CONTENT - COPY ---
        $this->start_controls_section('content', [ 'label' => __( 'Community Copy', 'cora-builder' ) ]);
        $this->add_control('headline', [ 
            'label' => 'Headline', 
            'type' => Controls_Manager::TEXT, 
            'default' => 'Join the Community of Successful Ecommerce Businesses',
            'dynamic' => ['active' => true]
        ]);
        $this->add_control('subline', [ 
            'label' => 'Subline', 
            'type' => Controls_Manager::TEXTAREA, 
            'default' => 'Get instant access to 2,000+ expert guides, proven strategies, and a supportive community.',
            'dynamic' => ['active' => true]
        ]);
        $this->end_controls_section();

        // --- TAB: CONTENT - TRUST BADGES ---
        $this->start_controls_section('trust', [ 'label' => __( 'Trust Badges', 'cora-builder' ) ]);
        $repeater = new Repeater();
        $repeater->add_control('badge_text', [ 'label' => 'Badge Label', 'type' => Controls_Manager::TEXT, 'default' => 'No credit card required' ]);

        $this->add_control('badges', [
            'label' => 'Benefits Row',
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                ['badge_text' => 'No credit card required'],
                ['badge_text' => '14-day free trial'],
                ['badge_text' => 'Cancel anytime'],
            ],
        ]);
        $this->end_controls_section();

        $this->register_common_spatial_controls(); 
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="cora-unit-container community-cta-wrapper">
            <div class="glow-top-left"></div>
            <div class="glow-bottom-right"></div>

            <div class="cta-inner-content">
                <h2 class="community-h2"><?php echo esc_html($settings['headline']); ?></h2>
                <p class="community-p"><?php echo esc_html($settings['subline']); ?></p>

                <div class="community-btn-row">
                    <a href="#" class="btn-primary-light">Start Free Trial <i class="fas fa-arrow-right"></i></a>
                    <a href="#" class="btn-secondary-dark">View Pricing</a>
                </div>

                <div class="community-trust-row">
                    <?php foreach ($settings['badges'] as $badge) : ?>
                        <div class="trust-unit">
                            <i class="far fa-check-circle"></i>
                            <span><?php echo esc_html($badge['badge_text']); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php
    }
}