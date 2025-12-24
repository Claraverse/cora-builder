<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Guide_List_Card extends Base_Widget {

    public function get_name() { return 'cora_guide_list_card'; }
    public function get_title() { return __( 'Cora Guide List Card', 'cora-builder' ); }
    public function get_icon() { return 'eicon-post-list'; }

    protected function register_controls() {
        
        // --- TAB: CONTENT ---
        $this->start_controls_section('section_content', [ 'label' => __( 'Header & List', 'cora-builder' ) ]);

        $this->add_control('icon', [
            'label' => 'Icon',
            'type' => Controls_Manager::ICONS,
            'default' => [ 'value' => 'fas fa-rocket', 'library' => 'solid' ]
        ]);

        $this->add_control('title', [
            'label' => 'Title',
            'type' => Controls_Manager::TEXT,
            'default' => 'Just Starting Out',
            'dynamic' => [ 'active' => true ]
        ]);

        $this->add_control('subtitle', [
            'label' => 'Subtitle',
            'type' => Controls_Manager::TEXT,
            'default' => "You're launching your first store",
            'dynamic' => [ 'active' => true ]
        ]);

        $repeater = new Repeater();
        $repeater->add_control('item_label', [ 'label' => 'Guide Name', 'type' => Controls_Manager::TEXT, 'dynamic' => ['active' => true] ]);
        $repeater->add_control('item_time', [ 'label' => 'Duration', 'type' => Controls_Manager::TEXT, 'default' => '30 min' ]);
        $repeater->add_control('item_url', [ 'label' => 'Guide URL', 'type' => Controls_Manager::URL, 'dynamic' => ['active' => true] ]);

        $this->add_control('guides', [
            'label' => 'Guide Items',
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                ['item_label' => 'Store setup checklist', 'item_time' => '30 min'],
                ['item_label' => 'First product strategy', 'item_time' => '45 min'],
            ],
            'title_field' => '{{{ item_label }}}',
        ]);

        // $this->add_control('view_all_text', [ 'label' => 'Footer Action Text', 'type' => Controls_Manager::TEXT, 'default' => 'View All Guides' ]);
// --- Inside register_controls() -> section_content ---
$this->add_control('view_all_text', [ 
    'label' => 'Footer Action Text', 
    'type' => Controls_Manager::TEXT, 
    'default' => 'View All Guides',
    'dynamic' => ['active' => true] // Enabled Dynamic Tag
]);

$this->add_control('footer_link', [
    'label' => __( 'Footer Link', 'cora-builder' ),
    'type' => Controls_Manager::URL,
    'dynamic' => [ 'active' => true ], // Connect to Dynamic Sources
    'placeholder' => __( 'https://your-link.com', 'cora-builder' ),
    'default' => [ 'url' => '' ],
]);
        $this->end_controls_section();

        // --- TAB: STYLE - HEADER & ICON ---
        $this->start_controls_section('style_header', [ 'label' => 'Header Styling', 'tab' => Controls_Manager::TAB_STYLE ]);
        $this->add_control('icon_gradient_start', [ 'label' => 'Icon BG Start', 'type' => Controls_Manager::COLOR, 'default' => '#3b82f6' ]);
        $this->add_control('icon_gradient_end', [ 'label' => 'Icon BG End', 'type' => Controls_Manager::COLOR, 'default' => '#2563eb' ]);
        $this->end_controls_section();

        // --- TAB: STYLE - LIST ITEMS ---
        $this->start_controls_section('style_list', [ 'label' => 'List Styling', 'tab' => Controls_Manager::TAB_STYLE ]);
        $this->add_control('item_bg', [ 'label' => 'Item Background', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .guide-item' => 'background-color: {{VALUE}};' ] ]);
        $this->add_responsive_control('item_radius', [ 'label' => 'Item Radius', 'type' => Controls_Manager::DIMENSIONS, 'selectors' => [ '{{WRAPPER}} .guide-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ]);
        $this->end_controls_section();

        // Advanced Text Engines
        $this->register_text_styling_controls('header_title', 'Header Title Styling', '{{WRAPPER}} .card-title');
        $this->register_text_styling_controls('item_text', 'Guide Text Styling', '{{WRAPPER}} .item-label');

        $this->register_common_spatial_controls();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
         $has_footer_link = ! empty( $settings['footer_link']['url'] );
        ?>
        
        <div class="cora-unit-container cora-guide-list-card">
            <div class="card-header">
                <div class="header-icon-box">
                    <?php \Elementor\Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] ); ?>
                </div>
                <div class="header-text">
                    <h3 class="card-title"><?php echo esc_html( $settings['title'] ); ?></h3>
                    <p class="card-subtitle"><?php echo esc_html( $settings['subtitle'] ); ?></p>
                </div>
            </div>

            <div class="guide-list">
                <?php foreach ( $settings['guides'] as $item ) : ?>
                    <a href="<?php echo esc_url( $item['item_url']['url'] ); ?>" class="guide-item">
                        <span class="item-label"><?php echo esc_html( $item['item_label'] ); ?></span>
                        <div class="item-meta">
                            <span class="item-time"><i class="far fa-clock"></i> <?php echo esc_html( $item['item_time'] ); ?></span>
                            <i class="fas fa-arrow-right arrow-icon"></i>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
 <?php if ( $has_footer_link ) : ?>
                <a <?php echo $this->get_render_attribute_string( 'footer_cta' ); ?> class="view-all-link">
            <?php endif; ?>
            <div class="card-footer">
 <button class="view-all-btn">
                <?php echo esc_html( $settings['view_all_text'] ); ?> 
                <i class="fas fa-arrow-right" aria-hidden="true"></i>
            </button>
            <?php if ( $has_footer_link ) : ?></a><?php endif; ?>
            </div>
        </div>
        <?php
    }
}

 