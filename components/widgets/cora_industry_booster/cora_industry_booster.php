<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Industry_Booster extends Base_Widget {

    public function get_name() {
        return 'cora_industry_booster';
    }

    public function get_title() {
        return __( 'Cora – Industry Booster (End-to-End)', 'cora-builder' );
    }

    public function get_icon() {
        return 'eicon-gallery-grid';
    }

    public function get_categories() {
        return [ 'cora-components' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'content_section',
            [
                'label' => __( 'Content', 'cora-builder' ),
            ]
        );

        $this->add_control(
            'industry_title',
            [
                'label' => __( 'Industry Title', 'cora-builder' ),
                'type' => Controls_Manager::TEXTAREA,
                'default' => 'Coaches &<br>Consultants',
                'rows' => 2,
            ]
        );

        $this->add_control(
            'cta_text',
            [
                'label' => __( 'CTA Text', 'cora-builder' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'Know more',
            ]
        );

        $this->add_control(
            'link',
            [
                'label' => __( 'Link', 'cora-builder' ),
                'type' => Controls_Manager::URL,
                'placeholder' => __( 'https://your-link.com', 'cora-builder' ),
                'default' => [
                    'url' => '#',
                ],
            ]
        );

        $this->add_control(
            'image_1',
            [
                'label' => __( 'Main Image (Front)', 'cora-builder' ),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_control(
            'image_2',
            [
                'label' => __( 'Secondary Image (Back)', 'cora-builder' ),
                'type' => Controls_Manager::MEDIA,
                'description' => 'This image appears faded in the background stack.',
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->end_controls_section();
        
        $this->start_controls_section(
            'style_section',
            [
                'label' => __( 'Style', 'cora-builder' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => __( 'Title Typography', 'cora-builder' ),
                'selector' => '{{WRAPPER}} .cora-booster-title',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $this->add_link_attributes( 'card_link', $settings['link'] );
        ?>

<style>
/* =====================================
   CORA – INDUSTRY BOOSTER (End-to-End)
   ===================================== */

.cora-booster-card {
    background: #F5F5F7; /* Apple-style light gray */
    border-radius: 32px;
    padding: 0;
    position: relative;
    overflow: hidden;
    cursor: pointer;
    /* Clean transition, no bouncy hover */
    transition: transform 0.3s cubic-bezier(0.25, 0.8, 0.25, 1), box-shadow 0.3s ease;
    display: flex;
    flex-direction: column;
    text-decoration: none;
    color: inherit;
    width: 100%;
}

.cora-booster-card:hover {
    box-shadow: 0 20px 40px rgba(0,0,0,0.06);
    /* Optional: extremely subtle lift, or remove if you want 100% static */
    transform: translateY(-2px); 
}

/* CONTENT AREA */
.cora-booster-content {
    padding: 40px 40px 20px 40px;
    position: relative;
    z-index: 20;
}

.cora-booster-title {
    font-size: 32px;
    line-height: 1.1;
    font-weight: 700;
    color: #1d1d1f;
    margin: 0 0 16px;
    letter-spacing: -0.02em;
}

.cora-booster-cta {
    font-size: 17px;
    font-weight: 500;
    color: #424245;
    display: flex;
    align-items: center;
    gap: 8px;
}

/* STACKED VISUAL AREA - END TO END */
.cora-booster-visual {
    position: relative;
    height: 320px;
    margin-top: 24px;
    padding: 0; /* NO PADDING - END TO END */
    width: 100%;
    /* overflow hidden handles the clipping at card border radius */
}

/* Layer 1: Back (Secondary) */
.cora-layer-back {
    position: absolute;
    top: 0;
    left: 50%;
    transform: translateX(-50%); 
    width: 94%; /* Slightly narrower than full width to show depth */
    height: 100%;
    background: #d2d2d7;
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
    opacity: 0.5;
    z-index: 1;
}

/* Layer 2: Front (Primary) */
.cora-layer-front {
    position: absolute;
    z-index: 10;
    width: 100%; /* FULL WIDTH */
    height: 100%;
    left: 0;
    top: 14px; /* Push down slightly to reveal back layer */
    background: #fff;
    /* Only round top corners */
    border-top-left-radius: 16px;
    border-top-right-radius: 16px;
    box-shadow: 0 -4px 24px rgba(0,0,0,0.06);
    overflow: hidden;
}

/* BROWSER BAR DOTS */
.cora-browser-bar {
    height: 36px;
    background: #fbfbfd;
    border-bottom: 1px solid #f0f0f0;
    display: flex;
    align-items: center;
    padding-left: 16px;
    gap: 8px;
}
.cora-dot { width: 10px; height: 10px; border-radius: 50%; }
.cora-dot.red { background: #ff5f57; }
.cora-dot.yellow { background: #febc2e; }
.cora-dot.green { background: #28c840; }

/* IMAGES */
.cora-layer-front img,
.cora-layer-back img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: top center;
    display: block;
}

.cora-layer-back img {
    filter: grayscale(100%);
    opacity: 0.7;
}

/* =========================
   RESPONSIVE
   ========================= */
@media (max-width: 768px) {
    .cora-booster-content { padding: 32px 24px 10px; }
    .cora-booster-title { font-size: 26px; }
    .cora-booster-visual { height: 260px; }
    /* Ensure visual remains end-to-end on mobile */
}
</style>

<a <?php echo $this->get_render_attribute_string( 'card_link' ); ?> class="cora-booster-card">

    <div class="cora-booster-content">
        <h3 class="cora-booster-title">
            <?php echo $settings['industry_title']; ?>
        </h3>

        <div class="cora-booster-cta">
            <?php echo esc_html( $settings['cta_text'] ); ?>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="5" y1="12" x2="19" y2="12"></line>
                <polyline points="12 5 19 12 12 19"></polyline>
            </svg>
        </div>
    </div>

    <div class="cora-booster-visual">
        <!-- <div class="cora-layer-back">
             <?php if( !empty($settings['image_2']['url']) ) : ?>
                <img src="<?php echo esc_url( $settings['image_2']['url'] ); ?>" alt="">
            <?php endif; ?>
        </div> -->

        <div class="cora-layer-front">
            <div class="cora-browser-bar">
                <span class="cora-dot red"></span>
                <span class="cora-dot yellow"></span>
                <span class="cora-dot green"></span>
            </div>
            
            <?php if( !empty($settings['image_1']['url']) ) : ?>
                <img src="<?php echo esc_url( $settings['image_1']['url'] ); ?>" alt="">
            <?php endif; ?>
        </div>
    </div>

</a>

<?php
    }
}