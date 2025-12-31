<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Logo_Carousel extends Base_Widget {

    public function get_name() { return 'cora_logo_carousel'; }
    public function get_title() { return __( 'Cora Logo Carousel', 'cora-builder' ); }
    public function get_icon() { return 'eicon-slider-push'; }

    protected function register_controls() {
        
        // --- TAB 1: CONTENT ---
        $this->start_controls_section('section_content', [ 'label' => __( 'Logo Management', 'cora-builder' ) ]);
        
        $repeater = new Repeater();
        $repeater->add_control('client_logo', [ 
            'label' => 'Logo Image', 
            'type' => Controls_Manager::MEDIA,
            'default' => [ 'url' => Utils::get_placeholder_image_src() ]
        ]);
        $repeater->add_control('client_name', [ 'label' => 'Client Name', 'type' => Controls_Manager::TEXT, 'default' => 'Client' ]);

        $this->add_control('logos', [
            'label' => 'Logos',
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                ['client_name' => 'Spectrum'],
                ['client_name' => 'Enigma'],
                ['client_name' => 'Synergy'],
                ['client_name' => 'Orbit'],
                ['client_name' => 'Vertex'],
            ],
            'title_field' => '{{{ client_name }}}',
        ]);

        $this->end_controls_section();

        // --- TAB 2: SETTINGS ---
        $this->start_controls_section('settings_section', [ 'label' => 'Carousel Settings', 'tab' => Controls_Manager::TAB_CONTENT ]);

        $this->add_control('speed', [
            'label' => 'Scroll Duration (Seconds)',
            'type' => Controls_Manager::NUMBER,
            'default' => 30,
            'min' => 5,
            'max' => 120,
            'step' => 1,
            'description' => 'Lower number = Faster speed.',
        ]);

        $this->add_control('pause_on_hover', [
            'label' => 'Pause on Hover',
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('show_fade', [
            'label' => 'Show Gradient Edges',
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_responsive_control('logo_gap', [
            'label' => 'Gap Between Logos',
            'type' => Controls_Manager::SLIDER,
            'default' => [ 'size' => 80 ],
            'range' => [ 'px' => [ 'min' => 20, 'max' => 200 ] ],
            'selectors' => [ '{{WRAPPER}} .logo-track' => 'gap: {{SIZE}}px;' ],
        ]);

        $this->end_controls_section();

        // --- TAB 3: STYLE (Design Reset) ---
        $this->start_controls_section('style_reset', [ 'label' => 'Design Reset', 'tab' => Controls_Manager::TAB_STYLE ]);

        $this->add_control('reset_info', [
            'type' => Controls_Manager::RAW_HTML,
            'raw'  => '<div style="background:#f1f4ff; color:#1e2b5e; padding:12px; border-radius:12px; font-weight:bold; text-align:center; border:1px solid #dbeafe;">
                        <i class="eicon-check-circle"></i> Infinite Scroll Engine Active
                      </div>',
        ]);

        $this->add_control('structural_reset', [
            'type' => Controls_Manager::HIDDEN,
            'default' => 'reset',
            'selectors' => [
                // Container
                '{{WRAPPER}} .logo-carousel-wrapper' => 'width: 100%; overflow: hidden; padding: 40px 0; position: relative; mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent); -webkit-mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);',
                
                // Track
                '{{WRAPPER}} .logo-track' => 'display: flex; align-items: center; width: max-content; animation: coraLogoScroll linear infinite;',
                
                // Items
                '{{WRAPPER}} .logo-item' => 'display: flex; align-items: center; gap: 12px; opacity: 0.6; transition: all 0.3s ease; filter: grayscale(100%); cursor: default;',
                '{{WRAPPER}} .logo-item:hover' => 'opacity: 1; filter: grayscale(0%); transform: scale(1.05);',
                
                // Image Sizing
                '{{WRAPPER}} .logo-item img' => 'height: 28px; width: auto; max-width: 150px; object-fit: contain;',
                
                // Text
                '{{WRAPPER}} .logo-text' => 'font-size: 18px; font-weight: 700; color: #334155; line-height: 1;',
            ],
        ]);

        // MOVED INSIDE SECTION TO FIX ERROR
        $this->add_control('animation_css', [
            'type' => Controls_Manager::RAW_HTML,
            'raw' => '<style>
                @keyframes coraLogoScroll { 
                    0% { transform: translateX(0); } 
                    100% { transform: translateX(-50%); } 
                }
                .logo-carousel-wrapper:hover .logo-track.paused { animation-play-state: paused; }
            </style>',
        ]);

        $this->end_controls_section();

        // --- ELEMENT ENGINES ---
        // These typically open their own sections, so they are fine here
        $this->register_text_styling_controls('text_style', 'Logo Text Style', '{{WRAPPER}} .logo-text');
        $this->register_common_spatial_controls();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $logos = $settings['logos'];
        $pause_class = ('yes' === $settings['pause_on_hover']) ? 'paused' : '';
        
        // Double the array for infinite loop logic
        $render_list = array_merge($logos, $logos); 

        ?>
        <div class="cora-unit-container logo-carousel-wrapper">
            <div class="logo-track <?php echo esc_attr($pause_class); ?>" style="animation-duration: <?php echo esc_attr($settings['speed']); ?>s;">
                <?php foreach ($render_list as $index => $logo) : ?>
                    <div class="logo-item elementor-repeater-item-<?php echo esc_attr($logo['_id']); ?>">
                        <?php if ( ! empty( $logo['client_logo']['url'] ) ) : ?>
                            <img src="<?php echo esc_url($logo['client_logo']['url']); ?>" alt="<?php echo esc_attr($logo['client_name']); ?>">
                        <?php endif; ?>
                        
                        <?php if ( ! empty( $logo['client_name'] ) ) : ?>
                            <span class="logo-text"><?php echo esc_html($logo['client_name']); ?></span>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
}