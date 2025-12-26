<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

class Clara_Expert_Card extends Base_Widget {

    public function get_name() { return 'clara_expert_card'; }
    public function get_title() { return __( 'Clara Expert Trust Card', 'clara-builder' ); }

    protected function register_controls() {
        
        // --- TAB: CONTENT - IDENTITY ---
        $this->start_controls_section('identity', [ 'label' => 'Expert Identity' ]);
        $this->add_control('avatar', [ 'label' => 'Profile Picture', 'type' => Controls_Manager::MEDIA ]);
        $this->add_control('name', [ 'label' => 'Full Name', 'type' => Controls_Manager::TEXT, 'default' => 'Dravya Bansal' ]);
        $this->add_control('title', [ 'label' => 'Job Title', 'type' => Controls_Manager::TEXT, 'default' => 'Senior Shopify Developer' ]);
        $this->end_controls_section();

        // --- TAB: CONTENT - STATS ---
        $this->start_controls_section('stats', [ 'label' => 'Performance Stats' ]);
        $this->add_control('stat_line', [ 
            'label' => 'Stat Description', 
            'type' => Controls_Manager::TEXT, 
            'default' => '50+ Shopify Projects | $20M+ Revenue Optimized',
            'dynamic' => ['active' => true] 
        ]);
        $this->end_controls_section();

        $this->register_common_spatial_controls(); 
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="cora-unit-container expert-trust-card">
            <div class="expert-avatar-wrap">
                <img src="<?php echo esc_url($settings['avatar']['url']); ?>" alt="Dravya Bansal">
            </div>

            <div class="expert-bio-stack">
                <h2 class="expert-name-h2"><?php echo esc_html($settings['name']); ?></h2>
                <p class="expert-title-p"><?php echo esc_html($settings['title']); ?></p>
                
                <div class="expert-metric-row">
                    <span class="emoji">📈</span>
                    <span class="metric-text"><?php echo esc_html($settings['stat_line']); ?></span>
                </div>
            </div>

            <div class="expert-stack-badges">
                <div class="stack-unit figma-badge">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/3/33/Figma-logo.svg" alt="Figma">
                </div>
                <div class="stack-unit shopify-badge">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/0/0e/Shopify_logo_2018.svg" alt="Shopify">
                </div>
            </div>
        </div>
        <?php
    }
}