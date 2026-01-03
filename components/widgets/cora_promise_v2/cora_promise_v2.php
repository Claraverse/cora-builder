<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Promise_V2 extends Base_Widget {

    public function get_name() { return 'cora_promise_v2'; }
    public function get_title() { return __( 'Cora – Promise Banner V2', 'cora-builder' ); }
    public function get_icon() { return 'eicon-call-to-action'; }
    public function get_categories() { return [ 'cora-components' ]; }

    protected function register_controls() {

        $this->start_controls_section('content', ['label' => __( 'Content', 'cora-builder' )]);

        $this->add_control('badge_text', [
            'label' => 'Badge Text',
            'type' => Controls_Manager::TEXT,
            'default' => 'Premium Upgrade',
        ]);

        $this->add_control('heading', [
            'label' => 'Headline',
            'type' => Controls_Manager::TEXTAREA,
            'default' => '30-Day Free Upgrade',
        ]);

        $this->add_control('description', [
            'label' => 'Description',
            'type' => Controls_Manager::TEXTAREA,
            'default' => "We don’t disappear after launch. For 30 days, you get free changes, upgrades, and ongoing support.",
        ]);

        $this->add_control('main_img', [
            'label' => 'Main Asset (MacBook/Large Image)',
            'type' => Controls_Manager::MEDIA,
            'default' => ['url' => Utils::get_placeholder_image_src()],
        ]);

        $this->add_control('float_img', [
            'label' => 'Floating Asset (Pricing Card)',
            'type' => Controls_Manager::MEDIA,
            'default' => ['url' => Utils::get_placeholder_image_src()],
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>

        <div class="cora-promise-v2">
            <div class="promise-container">
                
                <div class="promise-content">
                    <div class="promise-badge">
                        <span class="badge-icon"></span> 
                        <?php echo esc_html( $settings['badge_text'] ); ?>
                    </div>
                    <h2 class="promise-h2"><?php echo esc_html( $settings['heading'] ); ?></h2>
                    <p class="promise-p"><?php echo esc_html( $settings['description'] ); ?></p>
                </div>

                <div class="promise-visuals">
                    <div class="main-asset">
                        <img src="<?php echo esc_url($settings['main_img']['url']); ?>" alt="Main Visual">
                    </div>
                    <?php if ( !empty($settings['float_img']['url']) ) : ?>
                    <div class="floating-card">
                        <img src="<?php echo esc_url($settings['float_img']['url']); ?>" alt="Floating Card">
                    </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
        <style>
            .cora-promise-v2 {
    width: 100%;
    background: #E5E7EB; /* Matching the light grey background from your design */
    border-radius: 40px;
    padding: 80px 60px;
    overflow: hidden;
    box-sizing: border-box;
}

.promise-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 40px;
    align-items: center;
    max-width: 1200px;
    margin: 0 auto;
}

/* Badge Styling */
.promise-badge {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 700;
    color: #4B5563;
    margin-bottom: 24px;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-size: 14px;
}

.badge-icon {
    width: 14px;
    height: 14px;
    background: #3B82F6; /* Cora Blue */
    display: inline-block;
    border-radius: 2px;
}

/* Headline & Text */
.promise-h2 {
    font-size: 56px;
    font-weight: 900;
    line-height: 1.1;
    color: #111827;
    margin-bottom: 30px;
    letter-spacing: -2px;
}

.promise-p {
    font-size: 20px;
    line-height: 1.5;
    color: #374151;
    max-width: 480px;
}

/* Visuals Engine */
.promise-visuals {
    position: relative;
    display: flex;
    justify-content: flex-end;
}

.main-asset img {
    width: 100%;
    max-width: 550px;
    height: auto;
    border-radius: 20px;
    box-shadow: 0 30px 60px rgba(0,0,0,0.1);
}

.floating-card {
    position: absolute;
    top: -40px;
    left: -20px;
    width: 240px;
    z-index: 5;
    filter: drop-shadow(0 20px 40px rgba(0,0,0,0.15));
    animation: float 4s ease-in-out infinite;
}

.floating-card img {
    width: 100%;
    height: auto;
}

/* Floating Animation */
@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-15px); }
}

/* Responsive Fixes */
@media (max-width: 1024px) {
    .cora-promise-v2 { padding: 40px 30px; }
    .promise-container { grid-template-columns: 1fr; }
    .promise-h2 { font-size: 40px; }
    .floating-card { width: 180px; left: 0; }
}
        </style>
        <?php
    }
}