<?php
namespace Cora_Builder\components;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Portfolio_Card extends Widget_Base {

    public function get_name() { return 'cora_portfolio_card'; }
    public function get_title() { return __( 'Cora Portfolio Card', 'cora-builder' ); }
    public function get_icon() { return 'eicon-product-related'; }
    public function get_categories() { return [ 'cora-components' ]; }

    protected function register_controls() {

        // --- CONTENT: INFO ---
        $this->start_controls_section('info_section', ['label' => 'Project Info']);
        
        $this->add_control('project_title', [
            'label' => 'Project Name',
            'type' => Controls_Manager::TEXT,
            'default' => 'Moonbeam',
            'label_block' => true,
        ]);

        $this->add_control('project_desc', [
            'label' => 'Description',
            'type' => Controls_Manager::TEXTAREA,
            'default' => 'Never Write From Scratch Again. Kickstart your next great document with Moonbeam - Your long form AI Writing Assistant',
        ]);

        $this->end_controls_section();

        // --- CONTENT: BUTTONS ---
        $this->start_controls_section('btn_section', ['label' => 'Action Buttons']);

        $this->add_control('btn_1_text', [ 'label' => 'Button 1 Text', 'type' => Controls_Manager::TEXT, 'default' => 'Live Preview' ]);
        $this->add_control('btn_1_link', [ 'label' => 'Button 1 Link', 'type' => Controls_Manager::URL, 'default' => [ 'url' => '#' ] ]);
        
        $this->add_control('btn_2_text', [ 'label' => 'Button 2 Text', 'type' => Controls_Manager::TEXT, 'default' => 'View Case Study' ]);
        $this->add_control('btn_2_link', [ 'label' => 'Button 2 Link', 'type' => Controls_Manager::URL, 'default' => [ 'url' => '#' ] ]);

        $this->end_controls_section();

        // --- CONTENT: SCREENSHOTS ---
        $this->start_controls_section('gallery_section', ['label' => 'Project Screenshots']);

        $repeater = new Repeater();
        $repeater->add_control('image', [
            'label' => 'Screenshot',
            'type' => Controls_Manager::MEDIA,
            'default' => [ 'url' => Utils::get_placeholder_image_src() ],
        ]);

        $this->add_control('screenshots', [
            'label' => 'Images',
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                [ 'image' => [ 'url' => Utils::get_placeholder_image_src() ] ],
                [ 'image' => [ 'url' => Utils::get_placeholder_image_src() ] ],
            ],
        ]);

        $this->end_controls_section();

        // --- STYLE ---
        $this->start_controls_section('style_section', ['label' => 'Styling']);
        
        $this->add_control('accent_color', [
            'label' => 'Accent Color (Border)',
            'type' => Controls_Manager::COLOR,
            'default' => '#0ea5e9', // Sky Blue
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $widget_id = $this->get_id(); // Unique ID for JS targeting
        ?>

        <style>
            .cpc-card {
                background: #ffffff;
                border-radius: 32px;
                padding: 50px;
                display: flex;
                gap: 60px;
                font-family: 'Inter', sans-serif;
                box-shadow: 0 20px 40px rgba(0,0,0,0.04);
                overflow: hidden;
            }

            /* --- Left Column: Info --- */
            .cpc-info {
                flex: 0 0 35%;
                display: flex;
                flex-direction: column;
                justify-content: center;
            }

            .cpc-title {
                font-size: 32px;
                font-weight: 800;
                color: #0f172a;
                margin-bottom: 20px;
                letter-spacing: -0.02em;
            }

            .cpc-desc {
                font-size: 16px;
                line-height: 1.6;
                color: #64748b;
                margin-bottom: 40px;
            }

            .cpc-actions {
                display: flex;
                gap: 16px;
                flex-wrap: wrap;
            }

            .cpc-btn {
                padding: 12px 24px;
                border-radius: 12px;
                font-weight: 600;
                text-decoration: none;
                font-size: 15px;
                transition: transform 0.2s ease;
                text-align: center;
            }
            .cpc-btn:hover { transform: translateY(-2px); }

            .cpc-btn-primary {
                background: #1e293b;
                color: #ffffff;
                border: 1px solid #1e293b;
            }

            .cpc-btn-secondary {
                background: #ffffff;
                color: #1e293b;
                border: 1px solid #e2e8f0;
                box-shadow: 0 2px 5px rgba(0,0,0,0.03);
            }

            /* --- Right Column: Gallery (Draggable) --- */
            .cpc-gallery-wrap {
                flex: 1;
                overflow-x: auto;
                padding-bottom: 20px;
                
                /* Hide Scrollbars */
                scrollbar-width: none; 
                -ms-overflow-style: none;
                
                /* Cursor Logic for Pan */
                cursor: grab;
                user-select: none; /* Prevent text highlighting while dragging */
            }
            .cpc-gallery-wrap::-webkit-scrollbar { display: none; }
            
            /* Class added by JS while dragging */
            .cpc-gallery-wrap.is-dragging {
                cursor: grabbing;
            }

            .cpc-gallery-track {
                display: flex;
                gap: 30px;
                width: max-content;
                pointer-events: none; /* Let clicks pass through to parent for drag logic */
            }
            /* Re-enable pointer events on children if you had links, but here it helps drag smoothness */
            .cpc-gallery-track > * { pointer-events: auto; }

            .cpc-shot-frame {
                background: <?php echo esc_attr($settings['accent_color']); ?>;
                border-radius: 20px;
                padding: 0;
                width: 450px;
                flex-shrink: 0;
                transition: transform 0.3s ease;
            }
            .elementor img {
    border: none;
    border-radius: 20px;
    box-shadow: none;
    height: auto;
    max-width: 100%;
}
            .cpc-shot-frame:hover {
                transform: translateY(-0);
            }

            .cpc-shot-img {
                width: 100%;
                height: auto;
                border-radius: 12px 12px 0 0;
                display: block;
                box-shadow: 0 -10px 30px rgba(0,0,0,0.1);
                pointer-events: none; /* Prevent image drag ghosting */
            }

            /* --- Responsive --- */
            @media (max-width: 1024px) {
                .cpc-card {
                    flex-direction: column;
                    gap: 40px;
                    padding: 40px;
                }
                .cpc-info { flex: auto; text-align: center; }
                .cpc-actions { justify-content: center; }
                .cpc-gallery-wrap { width: 100%; }
            }

            @media (max-width: 767px) {
                .cpc-card { padding: 30px; border-radius: 24px; }
                .cpc-shot-frame { width: 280px; }
                .cpc-title { font-size: 26px; }
                .cpc-actions { flex-direction: column; }
                .cpc-btn { width: 100%; }
            }
        </style>

        <div class="cpc-card">
            
            <div class="cpc-info">
                <h3 class="cpc-title"><?php echo esc_html($settings['project_title']); ?></h3>
                <p class="cpc-desc"><?php echo esc_html($settings['project_desc']); ?></p>
                
                <div class="cpc-actions">
                    <?php if(!empty($settings['btn_1_text'])) : ?>
                        <a href="<?php echo esc_url($settings['btn_1_link']['url']); ?>" class="cpc-btn cpc-btn-primary">
                            <?php echo esc_html($settings['btn_1_text']); ?>
                        </a>
                    <?php endif; ?>

                    <?php if(!empty($settings['btn_2_text'])) : ?>
                        <a href="<?php echo esc_url($settings['btn_2_link']['url']); ?>" class="cpc-btn cpc-btn-secondary">
                            <?php echo esc_html($settings['btn_2_text']); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="cpc-gallery-wrap" id="cpc-gallery-<?php echo esc_attr($widget_id); ?>">
                <div class="cpc-gallery-track">
                    <?php foreach ($settings['screenshots'] as $shot) : ?>
                        <div class="cpc-shot-frame">
                            <?php if(!empty($shot['image']['url'])) : ?>
                                <img class="cpc-shot-img" src="<?php echo esc_url($shot['image']['url']); ?>" alt="Project Screenshot">
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>

        <script>
            (function() {
                const slider = document.getElementById('cpc-gallery-<?php echo esc_js($widget_id); ?>');
                let isDown = false;
                let startX;
                let scrollLeft;

                if (!slider) return;

                slider.addEventListener('mousedown', (e) => {
                    isDown = true;
                    slider.classList.add('is-dragging');
                    startX = e.pageX - slider.offsetLeft;
                    scrollLeft = slider.scrollLeft;
                });

                slider.addEventListener('mouseleave', () => {
                    isDown = false;
                    slider.classList.remove('is-dragging');
                });

                slider.addEventListener('mouseup', () => {
                    isDown = false;
                    slider.classList.remove('is-dragging');
                });

                slider.addEventListener('mousemove', (e) => {
                    if (!isDown) return;
                    e.preventDefault();
                    const x = e.pageX - slider.offsetLeft;
                    const walk = (x - startX) * 2; // Speed multiplier (2x faster)
                    slider.scrollLeft = scrollLeft - walk;
                });
            })();
        </script>
        <?php
    }
}