<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Guide_Card_V3 extends Base_Widget {

    public function get_name() { return 'cora_guide_card_v3'; }
    public function get_title() { return __( 'Cora Guide Card V3', 'cora-builder' ); }
    public function get_icon() { return 'eicon-info-box'; }

    protected function register_controls() {
        
        // --- TAB: CONTENT - MEDIA ---
        $this->start_controls_section('section_media', [ 'label' => __( 'Header Media', 'cora-builder' ) ]);
        
        $this->add_control('image', [ 
            'label' => 'Cover Image', 
            'type' => Controls_Manager::MEDIA, 
            'dynamic' => [ 'active' => true ],
            'default' => [ 'url' => \Elementor\Utils::get_placeholder_image_src() ] 
        ]);
        
        $this->add_control('category_badge', [ 
            'label' => 'Top-Left Badge', 
            'type' => Controls_Manager::TEXT, 
            'dynamic' => [ 'active' => true ],
            'default' => 'Conversion Optimization' 
        ]);
        
        $this->add_control('level_badge', [ 
            'label' => 'Top-Right Badge', 
            'type' => Controls_Manager::TEXT, 
            'dynamic' => [ 'active' => true ],
            'default' => 'Intermediate' 
        ]);
        
        $this->add_control('rating_badge', [ 
            'label' => 'Bottom-Left Badge', 
            'type' => Controls_Manager::TEXT, 
            'dynamic' => [ 'active' => true ],
            'default' => '4.8' 
        ]);
        
        $this->add_control('rating_icon', [ 
            'label' => 'Rating Icon', 
            'type' => Controls_Manager::ICONS, 
            'default' => [ 'value' => 'fas fa-star', 'library' => 'solid' ] 
        ]);
        
        $this->add_control('views_badge', [ 
            'label' => 'Bottom-Right Badge', 
            'type' => Controls_Manager::TEXT, 
            'dynamic' => [ 'active' => true ],
            'default' => '12.5K views' 
        ]);
        
        $this->add_control('views_icon', [ 
            'label' => 'Views Icon', 
            'type' => Controls_Manager::ICONS, 
            'default' => [ 'value' => 'fas fa-plus', 'library' => 'solid' ] 
        ]);
        
        $this->end_controls_section();

        // --- TAB: CONTENT - BODY ---
        $this->start_controls_section('section_body', [ 'label' => __( 'Content Body', 'cora-builder' ) ]);
        
        $this->add_control('title', [ 
            'label' => 'Title', 
            'type' => Controls_Manager::TEXT, 
            'dynamic' => [ 'active' => true ],
            'default' => 'Optimizing your checkout flow', 
            'label_block' => true 
        ]);
        
        $this->add_control('desc', [ 
            'label' => 'Description', 
            'type' => Controls_Manager::TEXTAREA, 
            'dynamic' => [ 'active' => true ],
            'default' => 'Reduce cart abandonment by streamlining your checkout process with these proven strategies.' 
        ]);
        
        $repeater = new Repeater();
        $repeater->add_control('tag_text', [ 
            'label' => 'Tag Text', 
            'type' => Controls_Manager::TEXT, 
            'dynamic' => [ 'active' => true ], // Enables dynamic tags inside repeater items
            'default' => 'Reduce form fields' 
        ]);
        
        $this->add_control('tags_list', [ 
            'label' => 'Key Takeaways', 
            'type' => Controls_Manager::REPEATER, 
            'fields' => $repeater->get_controls(), 
            'default' => [ 
                ['tag_text' => 'Reduce form fields'], 
                ['tag_text' => 'Add trust signals'], 
                ['tag_text' => 'Optimize mobile flow'] 
            ] 
        ]);
        
        $this->add_control('meta_time', [ 
            'label' => 'Time Meta', 
            'type' => Controls_Manager::TEXT, 
            'dynamic' => [ 'active' => true ],
            'default' => '15 min' 
        ]);
        
        $this->add_control('meta_steps', [ 
            'label' => 'Steps Meta', 
            'type' => Controls_Manager::TEXT, 
            'dynamic' => [ 'active' => true ],
            'default' => '5 Steps' 
        ]);
        
        $this->add_control('cta_text', [ 
            'label' => 'Button Text', 
            'type' => Controls_Manager::TEXT, 
            'dynamic' => [ 'active' => true ],
            'default' => 'Access Free Guide' 
        ]);
        
        $this->add_control('cta_link', [ 
            'label' => 'Button Link', 
            'type' => Controls_Manager::URL, 
            'dynamic' => [ 'active' => true ], 
            'placeholder' => 'https://your-link.com' 
        ]);

        $this->end_controls_section();

        // --- TAB: STYLE - CONTAINER ---
        $this->start_controls_section('style_container', [ 'label' => 'Container', 'tab' => Controls_Manager::TAB_STYLE ]);
        
        $this->add_control('card_bg', [ 'label' => 'Background', 'type' => Controls_Manager::COLOR, 'default' => '#ffffff', 'selectors' => ['{{WRAPPER}} .guide-card-v3' => 'background-color: {{VALUE}};'] ]);
        $this->add_group_control(Group_Control_Border::get_type(), [ 'name' => 'card_border', 'selector' => '{{WRAPPER}} .guide-card-v3' ]);
        $this->add_group_control(Group_Control_Box_Shadow::get_type(), [ 'name' => 'card_shadow', 'selector' => '{{WRAPPER}} .guide-card-v3' ]);
        $this->add_responsive_control('card_radius', [ 'label' => 'Radius', 'type' => Controls_Manager::DIMENSIONS, 'selectors' => ['{{WRAPPER}} .guide-card-v3' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;'] ]);
        
        $this->end_controls_section();

        // --- TAB: STYLE - HEADER ---
        $this->start_controls_section('style_header', [ 'label' => 'Header & Badges', 'tab' => Controls_Manager::TAB_STYLE ]);
        
        $this->add_responsive_control('header_height', [ 'label' => 'Image Height', 'type' => Controls_Manager::SLIDER, 'range' => [ 'px' => [ 'min' => 150, 'max' => 400 ] ], 'default' => [ 'size' => 240 ], 'selectors' => [ '{{WRAPPER}} .v3-header-media' => 'height: {{SIZE}}px;' ] ]);
        
        $this->add_control('badge_bg_color', [ 'label' => 'Badge BG', 'type' => Controls_Manager::COLOR, 'default' => '#ffffff', 'selectors' => [ '{{WRAPPER}} .media-badge' => 'background-color: {{VALUE}};' ] ]);
        $this->add_control('badge_text_color', [ 'label' => 'Badge Text', 'type' => Controls_Manager::COLOR, 'default' => '#0f172a', 'selectors' => [ '{{WRAPPER}} .media-badge' => 'color: {{VALUE}};' ] ]);
        $this->add_control('accent_badge_bg', [ 'label' => 'Accent Badge BG', 'type' => Controls_Manager::COLOR, 'default' => '#DBEAFE', 'selectors' => [ '{{WRAPPER}} .media-badge.accent' => 'background-color: {{VALUE}};' ] ]);
        $this->add_control('accent_badge_text', [ 'label' => 'Accent Badge Text', 'type' => Controls_Manager::COLOR, 'default' => '#1D4ED8', 'selectors' => [ '{{WRAPPER}} .media-badge.accent' => 'color: {{VALUE}};' ] ]);

        $this->end_controls_section();

        // --- TAB: STYLE - TYPOGRAPHY ---
        $this->start_controls_section('style_typo', [ 'label' => 'Typography', 'tab' => Controls_Manager::TAB_STYLE ]);
        
        $this->add_group_control(Group_Control_Typography::get_type(), [ 'name' => 'title_typo', 'selector' => '{{WRAPPER}} .card-title' ]);
        $this->add_control('title_color', [ 'label' => 'Title Color', 'type' => Controls_Manager::COLOR, 'default' => '#0f172a', 'selectors' => [ '{{WRAPPER}} .card-title' => 'color: {{VALUE}};' ] ]);
        
        $this->add_group_control(Group_Control_Typography::get_type(), [ 'name' => 'desc_typo', 'selector' => '{{WRAPPER}} .card-desc' ]);
        $this->add_control('desc_color', [ 'label' => 'Desc Color', 'type' => Controls_Manager::COLOR, 'default' => '#64748b', 'selectors' => [ '{{WRAPPER}} .card-desc' => 'color: {{VALUE}};' ] ]);

        $this->end_controls_section();

        // --- TAB: STYLE - BUTTON ---
        $this->start_controls_section('style_btn', [ 'label' => 'Button', 'tab' => Controls_Manager::TAB_STYLE ]);
        
        $this->add_control('btn_bg', [ 'label' => 'Background', 'type' => Controls_Manager::COLOR, 'default' => '#0f172a', 'selectors' => [ '{{WRAPPER}} .v3-primary-btn' => 'background-color: {{VALUE}};' ] ]);
        $this->add_control('btn_color', [ 'label' => 'Text Color', 'type' => Controls_Manager::COLOR, 'default' => '#ffffff', 'selectors' => [ '{{WRAPPER}} .v3-primary-btn' => 'color: {{VALUE}};' ] ]);
        $this->add_responsive_control('btn_radius', [ 'label' => 'Radius', 'type' => Controls_Manager::DIMENSIONS, 'selectors' => ['{{WRAPPER}} .v3-primary-btn' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;'] ]);
        
        $this->end_controls_section();

        // --- LAYOUT AUTHORITY ---
        $this->start_controls_section('layout_reset', [ 'label' => 'Layout Engine', 'tab' => Controls_Manager::TAB_STYLE ]);
        $this->add_control('css_reset', [
            'type' => Controls_Manager::HIDDEN,
            'default' => 'reset',
            'selectors' => [
                '{{WRAPPER}} .guide-card-v3' => 'display: flex; flex-direction: column; overflow: hidden; height: 100%; transition: transform 0.3s ease;',
                '{{WRAPPER}} .guide-card-v3:hover' => 'transform: translateY(-5px);',
                
                // Media Area
                '{{WRAPPER}} .v3-header-media' => 'position: relative; width: 100%; background: #e2e8f0;',
                '{{WRAPPER}} .header-img' => 'width: 100%; height: 100%; object-fit: cover;',
                '{{WRAPPER}} .badge-container' => 'position: absolute; top: 0; left: 0; width: 100%; height: 100%; padding: 16px; display: flex; flex-direction: column; justify-content: space-between; pointer-events: none;',
                '{{WRAPPER}} .badge-row' => 'display: flex; justify-content: space-between; width: 100%;',
                '{{WRAPPER}} .media-badge' => 'padding: 6px 14px; border-radius: 100px; font-size: 12px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); pointer-events: auto;',
                '{{WRAPPER}} .media-badge i' => 'font-size: 11px;',
                
                // Body Area
                '{{WRAPPER}} .v3-card-body' => 'padding: 24px; display: flex; flex-direction: column; gap: 16px; flex: 1;',
                '{{WRAPPER}} .card-title' => 'margin: 0 !important; font-size: 20px; font-weight: 700; line-height: 1.3;',
                '{{WRAPPER}} .card-desc' => 'margin: 0 !important; font-size: 14px; line-height: 1.6; flex-grow: 1;',
                
                // Tags
                '{{WRAPPER}} .takeaways-section' => 'display: flex; flex-direction: column; gap: 10px;',
                '{{WRAPPER}} .section-label' => 'font-size: 13px; font-weight: 700; color: #0f172a;',
                '{{WRAPPER}} .tag-cloud' => 'display: flex; flex-wrap: wrap; gap: 8px;',
                '{{WRAPPER}} .takeaway-tag' => 'background: #f1f5f9; padding: 6px 12px; border-radius: 8px; font-size: 12px; color: #475569; font-weight: 600;',
                
                // Meta
                '{{WRAPPER}} .meta-row' => 'display: flex; gap: 16px; margin-top: 4px; color: #64748b; font-size: 13px; font-weight: 600;',
                '{{WRAPPER}} .meta-item' => 'display: flex; align-items: center; gap: 6px;',
                
                // Button
                '{{WRAPPER}} .v3-primary-btn' => 'margin-top: 8px; padding: 14px; text-decoration: none; font-weight: 700; font-size: 14px; display: flex; align-items: center; justify-content: center; gap: 8px; transition: opacity 0.3s;',
                '{{WRAPPER}} .v3-primary-btn:hover' => 'opacity: 0.9;',
            ],
        ]);
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        $this->add_link_attributes( 'cta_btn', $settings['cta_link'] );
        ?>
        <div class="cora-unit-container guide-card-v3">
            
            <div class="v3-header-media">
                <?php if ( ! empty( $settings['image']['url'] ) ) : ?>
                    <img src="<?php echo esc_url($settings['image']['url']); ?>" class="header-img" alt="Guide Cover">
                <?php endif; ?>
                
                <div class="badge-container">
                    <div class="badge-row top">
                        <?php if ( ! empty( $settings['category_badge'] ) ) : ?>
                            <span class="media-badge"><?php echo esc_html($settings['category_badge']); ?></span>
                        <?php endif; ?>
                        
                        <?php if ( ! empty( $settings['level_badge'] ) ) : ?>
                            <span class="media-badge accent"><?php echo esc_html($settings['level_badge']); ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="badge-row bottom">
                        <?php if ( ! empty( $settings['rating_badge'] ) ) : ?>
                            <span class="media-badge">
                                <?php \Elementor\Icons_Manager::render_icon( $settings['rating_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                                <?php echo esc_html($settings['rating_badge']); ?>
                            </span>
                        <?php endif; ?>
                        
                        <?php if ( ! empty( $settings['views_badge'] ) ) : ?>
                            <span class="media-badge">
                                <?php \Elementor\Icons_Manager::render_icon( $settings['views_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                                <?php echo esc_html($settings['views_badge']); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="v3-card-body">
                <h3 class="card-title"><?php echo esc_html($settings['title']); ?></h3>
                <p class="card-desc"><?php echo esc_html($settings['desc']); ?></p>
                
                <?php if ( ! empty( $settings['tags_list'] ) ) : ?>
                <div class="takeaways-section">
                    <span class="section-label">Key Takeaways:</span>
                    <div class="tag-cloud">
                        <?php foreach($settings['tags_list'] as $tag) : ?>
                            <span class="takeaway-tag"><?php echo esc_html($tag['tag_text']); ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <div class="meta-row">
                    <?php if ( ! empty( $settings['meta_time'] ) ) : ?>
                        <span class="meta-item"><i class="far fa-clock"></i> <?php echo esc_html($settings['meta_time']); ?></span>
                    <?php endif; ?>
                    
                    <?php if ( ! empty( $settings['meta_steps'] ) ) : ?>
                        <span class="meta-item"><i class="fas fa-list-ol"></i> <?php echo esc_html($settings['meta_steps']); ?></span>
                    <?php endif; ?>
                </div>

                <?php if ( ! empty( $settings['cta_text'] ) ) : ?>
                <a <?php echo $this->get_render_attribute_string('cta_btn'); ?> class="v3-primary-btn">
                    <?php echo esc_html($settings['cta_text']); ?> <i class="fas fa-external-link-alt"></i>
                </a>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
}