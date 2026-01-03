<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Promise_Banner extends Base_Widget {

    public function get_name() {
        return 'cora_promise_banner';
    }

    public function get_title() {
        return __( 'Cora – Promise Banner', 'cora-builder' );
    }

    public function get_icon() {
        return 'eicon-banner';
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
            'badge_text',
            [
                'label' => __( 'Badge Text', 'cora-builder' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'Premium',
            ]
        );

        $this->add_control(
            'heading',
            [
                'label' => __( 'Heading', 'cora-builder' ),
                'type' => Controls_Manager::TEXTAREA,
                'default' => '30 Days of Free Pro Upgrade',
            ]
        );

        $this->add_control(
            'description',
            [
                'label' => __( 'Description', 'cora-builder' ),
                'type' => Controls_Manager::TEXTAREA,
                'default' => 'Get full access to premium features and priority support for 30 days — completely on the house.',
            ]
        );

        $this->add_control(
            'main_img',
            [
                'label' => __( 'Background Image', 'cora-builder' ),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>

<style>
/* =====================================
   CORA PROMISE BANNER — RESPONSIVE FIX
   ===================================== */

.cora-promise {
    position: relative;
    width: 100%;
    border-radius: 36px;
    overflow: hidden;
    background: #f8fafc;

    /* adaptive height */
    min-height: 240px;
    max-height: 320px;

    display: grid;
    grid-template-columns: 1fr;
    align-items: center;
}

/* IMAGE LAYER */
.cora-promise-media {
    position: absolute;
    inset: 0;
    z-index: 1;
}

.cora-promise-media img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* SOFT OVERLAY */
.cora-promise-media::after {
    content: "";
    position: absolute;
    inset: 0;
    background: linear-gradient(
        90deg,
        rgba(248,250,252,1) 0%,
        rgba(248,250,252,0.96) 30%,
        rgba(248,250,252,0.75) 45%,
        rgba(248,250,252,0) 60%
    );
}

/* CONTENT */
.cora-promise-content {
    position: relative;
    z-index: 2;

    max-width: 520px;
    padding: 32px 40px;

    display: flex;
    flex-direction: column;
    justify-content: center;
}

/* BADGE */
.cora-promise-badge {
    font-size: 13px;
    font-weight: 600;
    color: #2563eb;
    margin-bottom: 10px;
}

/* TITLE */
.cora-promise-title {
    margin: 0 0 10px;
    font-size: 30px;
    font-weight: 800;
    line-height: 1.2;
    color: #0f172a;
}

/* DESCRIPTION */
.cora-promise-text {
    font-size: 15px;
    line-height: 1.5;
    color: #334155;
    max-width: 460px;
}

/* =========================
   TABLET (<= 1024px)
   ========================= */
@media (max-width: 1024px) {
    .cora-promise {
        max-height: 300px;
    }

    .cora-promise-content {
        max-width: 480px;
        padding: 28px 32px;
    }

    .cora-promise-title {
        font-size: 26px;
    }
}

/* =========================
   MOBILE (<= 768px)
   ========================= */
@media (max-width: 768px) {
    .cora-promise {
        max-height: none;
        min-height: unset;
        border-radius: 24px;
    }

    .cora-promise-media {
        position: relative;
        height: 180px;
    }

    .cora-promise-media::after {
        background: linear-gradient(
            180deg,
            rgba(248,250,252,0) 0%,
            rgba(248,250,252,1) 88%
        );
    }

    .cora-promise-content {
        max-width: 100%;
        padding: 20px 20px 24px;
    }

    .cora-promise-title {
        font-size: 22px;
    }

    .cora-promise-text {
        font-size: 14px;
    }
}

</style>

<div class="cora-promise">

    <div class="cora-promise-media">
        <img src="<?php echo esc_url( $settings['main_img']['url'] ); ?>" alt="">
    </div>

    <div class="cora-promise-content">
        <div class="cora-promise-badge">
            ■ <?php echo esc_html( $settings['badge_text'] ); ?>
        </div>

        <h2 class="cora-promise-title">
            <?php echo esc_html( $settings['heading'] ); ?>
        </h2>

        <div class="cora-promise-text">
            <?php echo esc_html( $settings['description'] ); ?>
        </div>
    </div>

</div>

<?php
    }
}
