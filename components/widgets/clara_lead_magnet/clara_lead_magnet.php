<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

class Clara_Lead_Magnet extends Base_Widget {

    public function get_name() { return 'clara_lead_magnet'; }
    public function get_title() { return __( 'Clara Lead Magnet Bridge', 'clara-builder' ); }

    protected function register_controls() {
        
        // --- TAB: CONTENT - NARRATIVE ---
        $this->start_controls_section('content', [ 'label' => 'Lead Magnet Info' ]);
        $this->add_control('headline', [ 
            'label' => 'Headline', 
            'type' => Controls_Manager::TEXT, 
            'default' => 'The Shopify Growth Checklist Used by 7-Figure Stores',
            'dynamic' => ['active' => true] 
        ]);
        $this->add_control('subline', [ 
            'label' => 'Subline', 
            'type' => Controls_Manager::TEXTAREA, 
            'default' => 'Everything you need to turn your store into a selling machine. Grab our proven roadmap today.' 
        ]);
        $this->add_control('btn_text', [ 'label' => 'Button Text', 'type' => Controls_Manager::TEXT, 'default' => 'Get the Free Checklist' ]);
        $this->end_controls_section();

        // --- TAB: CONTENT - MEDIA ---
        $this->start_controls_section('media', [ 'label' => 'Asset Media' ]);
        $this->add_control('book_img', [ 'label' => 'Book Mockup', 'type' => Controls_Manager::MEDIA ]);
        $this->end_controls_section();

        $this->register_common_spatial_controls(); 
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="cora-unit-container lead-bridge-wrapper">
            <div class="bridge-inner-canvas">
                <div class="bridge-copy-side">
                    <span class="bridge-badge">Free Guide</span>
                    <h2 class="bridge-h2"><?php echo esc_html($settings['headline']); ?></h2>
                    <p class="bridge-p"><?php echo esc_html($settings['subline']); ?></p>
                    <a href="#" class="btn-primary-green"><?php echo esc_html($settings['btn_text']); ?></a>
                </div>

                <div class="bridge-asset-side">
                    <div class="book-container">
                        <img src="<?php echo esc_url($settings['book_img']['url']); ?>" alt="Shopify Checklist Book">
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}