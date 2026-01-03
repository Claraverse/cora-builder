<?php
namespace Cora_Builder\components;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Micro_Card extends Widget_Base {

    public function get_name() { return 'cora_micro_card'; }
    public function get_title() { return __( 'Cora Micro Card', 'cora-builder' ); }

    protected function register_controls() {
        $this->start_controls_section('content', ['label' => 'Content']);

        $this->add_control('image', [
            'label' => 'Image',
            'type' => Controls_Manager::MEDIA,
            'default' => ['url' => Utils::get_placeholder_image_src()],
        ]);

        $this->add_control('title', [
            'label' => 'Title',
            'type' => Controls_Manager::TEXTAREA,
            'default' => 'Website + Mobile First',
            'rows' => 2,
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>

        <style>
            /* Static Container with soft brand shadow */
            .cora-static-card {
                background: #ffffff;
                border: 1px solid #f1f5f9;
                border-radius: 32px; /* Soft radius from design */
            
                text-align: center;
                max-width: 360px;
                margin: 0 auto;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
            }

            /* Inner Grey Frame for Image */
            .cora-image-frame {
                background: #F8FAFC; 
                border-top-left-radius: 24px;
                border-top-right-radius: 24px;
                
                display: flex;
                align-items: center;
                justify-content: center;
           
                overflow: hidden;
            }

            .cora-image-frame img {
                width: 100%;
                height: auto;
                object-fit: contain;
            }

            /* Bold, Tight Typography */
            .cora-static-title {
                font-family: 'Inter', sans-serif;
                font-size: 26px;
                font-weight: 850; /* Heavy weight from screenshot */
                color: #0f172a;
                line-height: 1.1;
                margin: 10px 0 20px 0;
                letter-spacing: -0.02em;
            }
        </style>

        <div class="cora-static-card">
            <div class="cora-image-frame">
                <img src="<?php echo esc_url($settings['image']['url']); ?>" alt="Card Graphic">
            </div>
            <h3 class="cora-static-title">
                <?php echo wp_kses_post($settings['title']); ?>
            </h3>
        </div>
        <?php
    }
}