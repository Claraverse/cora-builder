<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit;

class Clara_Video_Booster extends Base_Widget {

    public function get_name() { return 'clara_video_booster'; }
    public function get_title() { return __( 'Clara Video Booster', 'clara-builder' ); }
    public function get_icon() { return 'eicon-youtube'; }

    protected function register_controls() {
        
        // --- VIDEO SOURCE ---
        $this->start_controls_section('video_setup', [ 'label' => 'Video Settings' ]);
        
        $this->add_control('video_url', [ 
            'label' => 'Video Link (YouTube/Vimeo)', 
            'type' => Controls_Manager::TEXT, 
            'default' => 'https://www.youtube.com/watch?v=XHOmBV4js_E',
            'placeholder' => 'Enter Video URL',
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('cover_mode', [
            'label' => 'Cover Type',
            'type' => Controls_Manager::SELECT,
            'default' => 'layout',
            'options' => [
                'layout' => 'Dynamic Layout (Text + Grid)',
                'image'  => 'Custom Image',
            ],
        ]);

        $this->add_control('custom_cover_image', [
            'label' => 'Upload Cover Image',
            'type' => Controls_Manager::MEDIA,
            'condition' => ['cover_mode' => 'image'],
            'default' => [ 'url' => Utils::get_placeholder_image_src() ],
        ]);

        $this->add_control('show_play_icon', [
            'label' => 'Show Play Button',
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('show_player_bar', [
            'label' => 'Show Fake Player Bar',
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
            'condition' => ['cover_mode' => 'layout'], 
        ]);

        $this->end_controls_section();

        // --- DYNAMIC CONTENT (Layout Mode) ---
        $this->start_controls_section('content_setup', [ 
            'label' => 'Cover Content',
            'condition' => ['cover_mode' => 'layout'],
        ]);
        
        $this->add_control('brand_name', [ 'label' => 'Brand Label', 'type' => Controls_Manager::TEXT, 'default' => '' ]);
        $this->add_control('headline_prefix', [ 'label' => 'Headline (Normal)', 'type' => Controls_Manager::TEXT, 'default' => 'Shopify' ]);
        $this->add_control('headline_suffix', [ 'label' => 'Headline (Serif)', 'type' => Controls_Manager::TEXT, 'default' => 'Sales Booster' ]);
        $this->add_control('description', [ 'label' => 'Description', 'type' => Controls_Manager::TEXTAREA, 'default' => 'See how we increased conversion rates by 200% in just 30 days.' ]);

        // Floating Decorations
        $this->add_control('dec_image_1', [ 'label' => 'Floating Card 1', 'type' => Controls_Manager::MEDIA ]);
        $this->add_control('dec_image_2', [ 'label' => 'Floating Card 2', 'type' => Controls_Manager::MEDIA ]);
        $this->add_control('dec_image_3', [ 'label' => 'Floating Card 3', 'type' => Controls_Manager::MEDIA ]);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        // Helper: Extract Video ID
        $video_url = $settings['video_url'];
        $video_id = '';
        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $video_url, $match)) {
            $video_id = $match[1];
        }
        
        $has_image_cover = ($settings['cover_mode'] === 'image' && !empty($settings['custom_cover_image']['url']));
        ?>

        <style>
            /* --- Component Container --- */
            .cv-player-wrap {
                position: relative;
                width: 100%;
                /* Fluid Max Width */
                max-width: 1100px;
                margin: 0 auto;
                border-radius: 24px;
                overflow: hidden;
                box-shadow: 0 30px 60px rgba(0,0,0,0.12);
                border: 1px solid #f1f5f9;
                background: #000;
                /* Default Desktop Aspect Ratio */
                aspect-ratio: 16 / 9;
                isolation: isolate;
            }

            /* --- COVER LAYER --- */
            .cv-cover-layer {
                position: absolute;
                inset: 0;
                background-color: #ffffff;
                background-size: cover;
                background-position: center;
                z-index: 10;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                text-align: center;
                cursor: pointer;
                transition: opacity 0.5s ease;
                padding: 20px; 
            }

            /* Grid Pattern */
            .cv-mode-layout {
                background-image: 
                    linear-gradient(#f1f5f9 1px, transparent 1px), 
                    linear-gradient(90deg, #f1f5f9 1px, transparent 1px);
                background-size: 40px 40px;
            }

            .cv-cover-hidden {
                opacity: 0;
                pointer-events: none;
                z-index: -1;
            }

            /* --- TYPOGRAPHY (Fluid) --- */
            .cv-brand-pill {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                font-size: 13px;
                font-weight: 700;
                color: #475569;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                margin-bottom: 15px;
            }
            .cv-brand-pill::before { content: ""; width: 6px; height: 6px; background: #3b82f6; border-radius: 50%; }

            .cv-title {
                font-family: 'Inter', sans-serif;
                /* Fluid: 32px mobile -> 56px desktop */
                font-size: clamp(32px, 5vw, 56px);
                font-weight: 850;
                color: #0f172a;
                line-height: 1.1;
                letter-spacing: -0.03em;
                margin: 0 0 20px 0;
                position: relative; z-index: 5;
            }
            .cv-serif {
                font-family: 'Playfair Display', serif;
                font-style: italic;
                color: #15803d; /* Forest Green */
                font-weight: 600;
            }

            .cv-desc {
                font-family: 'Inter', sans-serif;
                font-size: clamp(16px, 2vw, 18px);
                color: #64748b;
                line-height: 1.6;
                max-width: 550px;
                margin: 0 auto 30px auto;
                position: relative; z-index: 5;
            }

            /* --- FLOATING DECORATIONS (Desktop Only) --- */
            .cv-float {
                position: absolute;
                z-index: 2;
                width: clamp(100px, 12vw, 160px); 
                filter: drop-shadow(0 15px 30px rgba(0,0,0,0.08));
                transition: transform 0.4s ease;
                pointer-events: none;
            }
            .cv-float img { width: 100%; height: auto; border-radius: 10px; display: block; }
            
            .f-1 { top: 12%; left: 8%; transform: rotate(-8deg); }
            .f-2 { bottom: 18%; right: 6%; transform: rotate(6deg); width: clamp(120px, 15vw, 180px); }
            .f-3 { top: 10%; right: 10%; transform: rotate(4deg); width: clamp(80px, 10vw, 120px); }

            /* --- PLAY BUTTON --- */
            .cv-play-btn {
                position: relative;
                z-index: 20;
                width: clamp(60px, 8vw, 80px); 
                height: clamp(40px, 5vw, 56px);
                background: #ef4444; /* YouTube Red */
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0 10px 20px rgba(220, 38, 38, 0.4);
                transition: transform 0.2s ease;
                flex-shrink: 0;
            }
            .cv-play-btn svg { width: clamp(20px, 3vw, 28px); height: auto; fill: #fff; }
            .cv-player-wrap:hover .cv-play-btn { transform: scale(1.1); }

            /* --- FAKE PLAYER BAR --- */
            .cv-fake-bar {
                position: absolute; bottom: 20px; left: 20px; right: 20px;
                height: 40px;
                background: rgba(255,255,255,0.85);
                backdrop-filter: blur(12px);
                border-radius: 10px;
                border: 1px solid rgba(0,0,0,0.05);
                display: flex;
                align-items: center;
                padding: 0 15px;
                gap: 15px;
                color: #64748b;
                font-size: 12px;
                font-family: sans-serif;
                font-weight: 600;
                z-index: 5;
            }
            .cv-bar-play { width: 0; height: 0; border-style: solid; border-width: 6px 0 6px 10px; border-color: transparent transparent transparent #94a3b8; }
            .cv-bar-line { flex: 1; height: 4px; background: #cbd5e1; border-radius: 2px; }
            .cv-bar-line::after { content:''; display:block; width: 30%; height:100%; background: #ef4444; border-radius: 2px; }

            /* --- VIDEO LAYER --- */
            .cv-video-layer {
                position: absolute;
                inset: 0;
                height: 100%;
                width: 100%;
                z-index: 1;
            }
            
            /* --- RESPONSIVE FIX (MOBILE) --- */
            @media (max-width: 768px) {
                .cv-player-wrap {
                    /* Release strict 16:9 ratio on mobile to allow content to fit */
                    aspect-ratio: unset;
                    min-height: 240px;
                }
                
                /* Ensure decorations don't cause overflow */
                .cv-float { display: none !important; }
                
                /* Tighter Bar */
                .cv-fake-bar { bottom: 15px; left: 15px; right: 15px; height: 36px; }
                
                /* Ensure title wraps cleanly */
                .cv-title { font-size: 32px; margin-bottom: 16px; }
                
                /* Ensure description is visible */
                .cv-desc { margin-bottom: 24px; font-size: 16px; }
            }
        </style>

        <div class="cora-unit-container cv-player-wrap" id="cv-player-<?php echo $this->get_id(); ?>">
            
            <div class="cv-cover-layer <?php echo ($settings['cover_mode'] === 'layout') ? 'cv-mode-layout' : ''; ?>"
                 style="<?php echo $has_image_cover ? 'background-image: url(' . esc_url($settings['custom_cover_image']['url']) . ');' : ''; ?>"
                 onclick="playCoraVideo('<?php echo $this->get_id(); ?>', '<?php echo $video_id; ?>')">

                <?php if ( $settings['cover_mode'] === 'layout' ) : ?>
                    
                    <?php if(!empty($settings['dec_image_1']['url'])) : ?><div class="cv-float f-1"><img src="<?php echo esc_url($settings['dec_image_1']['url']); ?>"></div><?php endif; ?>
                    <?php if(!empty($settings['dec_image_2']['url'])) : ?><div class="cv-float f-2"><img src="<?php echo esc_url($settings['dec_image_2']['url']); ?>"></div><?php endif; ?>
                    <?php if(!empty($settings['dec_image_3']['url'])) : ?><div class="cv-float f-3"><img src="<?php echo esc_url($settings['dec_image_3']['url']); ?>"></div><?php endif; ?>

                    <div class="cv-content">
                        <?php if(!empty($settings['brand_name'])) : ?>
                            <div class="cv-brand-pill"><?php echo esc_html($settings['brand_name']); ?></div>
                        <?php endif; ?>
                        
                        <?php if(!empty($settings['headline_prefix']) || !empty($settings['headline_suffix'])) : ?>
                            <h2 class="cv-title">
                                <?php echo esc_html($settings['headline_prefix']); ?> 
                                <span class="cv-serif"><?php echo esc_html($settings['headline_suffix']); ?></span>
                            </h2>
                        <?php endif; ?>

                        <?php if(!empty($settings['description'])) : ?>
                            <p class="cv-desc"><?php echo esc_html($settings['description']); ?></p>
                        <?php endif; ?>
                    </div>

                    <?php if ( 'yes' === $settings['show_player_bar'] ) : ?>
                        <div class="cv-fake-bar">
                            <div class="cv-bar-play"></div>
                            <div>0:00 / 2:45</div>
                            <div class="cv-bar-line"></div>
                            <div>HD</div>
                        </div>
                    <?php endif; ?>

                <?php endif; ?>
                
                <?php if ( 'yes' === $settings['show_play_icon'] ) : ?>
                    <div class="cv-play-btn">
                        <svg viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                    </div>
                <?php endif; ?>

            </div>

            <div class="cv-video-layer" id="cv-iframe-<?php echo $this->get_id(); ?>"></div>
        </div>

        <script>
            function playCoraVideo(widgetId, videoId) {
                if(!videoId) return;
                var wrapper = document.getElementById('cv-player-' + widgetId);
                var cover = wrapper.querySelector('.cv-cover-layer');
                var videoContainer = document.getElementById('cv-iframe-' + widgetId);
                
                // Fade out cover
                cover.classList.add('cv-cover-hidden');
                
                // Inject Iframe (Will fill the 450px min-height on mobile)
                videoContainer.innerHTML = '<iframe style="width:100%; height:100%;" src="https://www.youtube.com/embed/' + videoId + '?autoplay=1&rel=0" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
            }
        </script>
        <?php
    }
}