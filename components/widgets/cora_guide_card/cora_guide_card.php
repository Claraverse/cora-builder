<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if (!defined('ABSPATH'))
    exit;

class Cora_Guide_Card extends Base_Widget
{

    public function get_name()
    {
        return 'cora_guide_card';
    }
    public function get_title()
    {
        return __('Cora Guide Card', 'cora-builder');
    }
    public function get_icon()
    {
        return 'eicon-card';
    }

    protected function register_controls()
    {

        // --- TAB: CONTENT ---
        $this->start_controls_section('section_content', ['label' => __('Guide Content', 'cora-builder')]);

        $this->add_control('icon', [
            'label' => 'Icon',
            'type' => Controls_Manager::ICONS,
            'default' => ['value' => 'fas fa-shopping-bag', 'library' => 'solid']
        ]);

        $this->add_control('title', [
            'label' => 'Title',
            'type' => Controls_Manager::TEXT,
            'default' => 'Getting Started',
            'dynamic' => ['active' => true]
        ]);

        $this->add_control('desc', [
            'label' => 'Description',
            'type' => Controls_Manager::TEXTAREA,
            'default' => 'Essential setup and configuration',
            'dynamic' => ['active' => true]
        ]);

        // Inside register_controls() -> content_main section
        $this->add_control('card_link', [
            'label' => __('Link', 'cora-builder'),
            'type' => Controls_Manager::URL,
            'dynamic' => ['active' => true], // Connect to Dynamic Tags
            'placeholder' => __('https://your-link.com', 'cora-builder'),
            'default' => ['url' => ''],
        ]);
        $repeater = new Repeater();
        $repeater->add_control('tag_text', ['label' => 'Tag Label', 'type' => Controls_Manager::TEXT]);

        $this->add_control('tags', [
            'label' => 'Feature Tags',
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [['tag_text' => 'Store setup'], ['tag_text' => 'Theme customization']],
            'title_field' => '{{{ tag_text }}}',
        ]);

        $this->add_control('guide_count', [
            'label' => 'Guide Count',
            'type' => Controls_Manager::TEXT,
            'default' => '12 guides',
            'dynamic' => ['active' => true]
        ]);

        $this->end_controls_section();

        // --- TAB: STYLE - ICON BOX ---
        $this->start_controls_section('style_icon', ['label' => 'Icon Styling', 'tab' => Controls_Manager::TAB_STYLE]);

        $this->add_control('icon_bg', [
            'label' => 'Box Background',
            'type' => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .card-icon-box' => 'background-color: {{VALUE}};']
        ]);

        // Inside your Base_Widget core or the specific widget
$this->add_responsive_control('cora_element_gap', [
    'label'      => __( 'Element Spacing (Gap)', 'cora-builder' ),
    'type'       => Controls_Manager::SLIDER,
    'dynamic'    => [ 'active' => true ], // Enables Pen Icon
    'selectors'  => [ 
        '{{WRAPPER}} .card-body' => 'gap: {{SIZE}}px;',
        '{{WRAPPER}}' => '--cora-element-gap: {{SIZE}}px;' 
    ],
]);
        $this->add_control('icon_color', [
            'label' => 'Icon Color',
            'type' => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .card-icon-box' => 'color: {{VALUE}};']
        ]);

        $this->add_responsive_control('icon_box_size', [
            'label' => 'Box Size',
            'type' => Controls_Manager::SLIDER,
            'selectors' => ['{{WRAPPER}} .card-icon-box' => 'width: {{SIZE}}px; height: {{SIZE}}px;']
        ]);

        $this->end_controls_section();

        // --- TAB: STYLE - TEXT ENGINES ---
        $this->register_text_styling_controls('title_style', 'Title Styling', '{{WRAPPER}} .card-title');
        $this->register_text_styling_controls('desc_style', 'Description Styling', '{{WRAPPER}} .card-desc');
        $this->register_text_styling_controls('tag_style', 'Tag Styling', '{{WRAPPER}} .tag-item');
        $this->register_text_styling_controls('footer_style', 'Footer Styling', '{{WRAPPER}} .count-text');

        // Spacing & Layout Core
        $this->register_common_spatial_controls();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $has_link = !empty($settings['card_link']['url']);

        if ($has_link) {
            $this->add_link_attributes('wrapper_link', $settings['card_link']);
        }
        ?>
        <div class="cora-unit-container cora-guide-card">
            <?php if ($has_link): ?><a <?php echo $this->get_render_attribute_string('wrapper_link'); ?>
                    class="card-link-mask"></a><?php endif; ?>

            <div class="card-icon-box">
                <?php \Elementor\Icons_Manager::render_icon($settings['icon'], ['aria-hidden' => 'true']); ?>
            </div>

            <div class="card-body">
                <h3 class="card-title"><?php echo esc_html($settings['title']); ?></h3>
                <p class="card-desc"><?php echo esc_html($settings['desc']); ?></p>
                <div class="tag-cloud">
                    <?php foreach ($settings['tags'] as $tag): ?>
                        <span class="tag-item"><?php echo esc_html($tag['tag_text']); ?></span>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="card-footer">
                <span class="count-text"><?php echo esc_html($settings['guide_count']); ?></span>
                <span class="footer-arrow"><i class="fas fa-arrow-right" aria-hidden="true"></i></span>
            </div>
        </div>
        <?php
    }
}