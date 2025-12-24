<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Guide_Card_V3 extends Base_Widget {

    public function get_name() { return 'cora_guide_card_v3'; }
    public function get_title() { return __( 'Cora Guide Card V3', 'cora-builder' ); }
    public function get_icon() { return 'eicon-info-box'; }

    protected function register_controls() {
        
        // --- TAB: CONTENT - MEDIA HEADER ---
        $this->start_controls_section('section_media', [ 'label' => __( 'Media Header', 'cora-builder' ) ]);
        $this->add_control('image', [ 'label' => 'Header Image', 'type' => Controls_Manager::MEDIA ]);
        $this->add_control('category', [ 'label' => 'Category Badge', 'type' => Controls_Manager::TEXT, 'default' => 'Conversion Optimization' ]);
        $this->add_control('level', [ 'label' => 'Level Badge', 'type' => Controls_Manager::TEXT, 'default' => 'Intermediate' ]);
        $this->add_control('rating', [ 'label' => 'Rating', 'type' => Controls_Manager::TEXT, 'default' => '4.8' ]);
        $this->add_control('views', [ 'label' => 'Views Count', 'type' => Controls_Manager::TEXT, 'default' => '12.5K views' ]);
        $this->end_controls_section();

        // --- TAB: CONTENT - BODY ---
        $this->start_controls_section('section_body', [ 'label' => __( 'Guide Body', 'cora-builder' ) ]);
        $this->add_control('title', [ 'label' => 'Title', 'type' => Controls_Manager::TEXT, 'default' => 'Optimizing your checkout flow', 'dynamic' => ['active' => true] ]);
        $this->add_control('desc', [ 'label' => 'Description', 'type' => Controls_Manager::TEXTAREA, 'default' => 'Reduce cart abandonment by streamlining your checkout...', 'dynamic' => ['active' => true] ]);
        
        $repeater = new Repeater();
        $repeater->add_control('tag', [ 'label' => 'Takeaway Tag', 'type' => Controls_Manager::TEXT ]);
        $this->add_control('takeaways', [ 'label' => 'Key Takeaways', 'type' => Controls_Manager::REPEATER, 'fields' => $repeater->get_controls() ]);
        
        $this->add_control('duration', [ 'label' => 'Time Label', 'type' => Controls_Manager::TEXT, 'default' => '15 min' ]);
        $this->add_control('steps', [ 'label' => 'Steps Count', 'type' => Controls_Manager::TEXT, 'default' => '5 Steps' ]);
        $this->add_control('cta_url', [ 'label' => 'CTA Link', 'type' => Controls_Manager::URL, 'dynamic' => ['active' => true] ]);
        $this->end_controls_section();

        // Style Engines
        $this->register_text_styling_controls('title_style', 'Title Typography', '{{WRAPPER}} .card-title');
        $this->register_common_spatial_controls();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="cora-unit-container guide-card-v3">
            <div class="v3-header-media">
                <img src="<?php echo esc_url($settings['image']['url']); ?>" class="header-img" alt="">
                <div class="badge-row top-row">
                    <span class="badge category-badge"><?php echo esc_html($settings['category']); ?></span>
                    <span class="badge level-badge"><?php echo esc_html($settings['level']); ?></span>
                </div>
                <div class="badge-row bottom-row">
                    <span class="badge stats-badge"><i class="fas fa-star"></i> <?php echo esc_html($settings['rating']); ?></span>
                    <span class="badge stats-badge"><i class="fas fa-plus"></i> <?php echo esc_html($settings['views']); ?></span>
                </div>
            </div>

            <div class="v3-card-body">
                <h3 class="card-title"><?php echo esc_html($settings['title']); ?></h3>
                <p class="card-desc"><?php echo esc_html($settings['desc']); ?></p>
                
                <div class="takeaways-section">
                    <span class="section-label">Key Takeaways:</span>
                    <div class="tag-cloud">
                        <?php foreach($settings['takeaways'] as $item) : ?>
                            <span class="takeaway-tag"><?php echo esc_html($item['tag']); ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="meta-row">
                    <span><i class="far fa-clock"></i> <?php echo esc_html($settings['duration']); ?></span>
                    <span><i class="fas fa-book-open"></i> <?php echo esc_html($settings['steps']); ?></span>
                </div>

                <a <?php echo $this->get_render_attribute_string('cta_url'); ?> class="v3-primary-btn">
                    Access Free Guide <i class="fas fa-external-link-alt"></i>
                </a>
            </div>
        </div>
        <?php
    }
}