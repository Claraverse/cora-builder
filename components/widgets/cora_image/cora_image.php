<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Image extends Base_Widget {

    public function get_name() { return 'cora_image'; }
    public function get_title() { return __( 'Cora Image', 'cora-builder' ); }
    public function get_icon() { return 'eicon-image-bold'; }

    protected function register_controls() {
        
        // --- TAB: CONTENT ---
        $this->start_controls_section('section_image', [ 'label' => __( 'Image', 'cora-builder' ) ]);

        $this->add_control('image', [
            'label'   => __( 'Choose Image', 'cora-builder' ),
            'type'    => Controls_Manager::MEDIA,
            'dynamic' => [ 'active' => true ], // Connect to Shopify Metafields / WP Custom Fields
            'default' => [ 'url' => \Elementor\Utils::get_placeholder_image_src() ],
        ]);

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [ 'name' => 'image_size', 'default' => 'large' ]
        );

        $this->add_control('link_to', [
            'label'   => __( 'Link', 'cora-builder' ),
            'type'    => Controls_Manager::URL,
            'dynamic' => [ 'active' => true ],
            'placeholder' => __( 'https://your-link.com', 'cora-builder' ),
        ]);

        $this->add_control('caption_text', [
            'label'       => __( 'Caption', 'cora-builder' ),
            'type'        => Controls_Manager::TEXTAREA,
            'dynamic'     => [ 'active' => true ],
            'placeholder' => __( 'Enter image caption...', 'cora-builder' ),
        ]);

        $this->end_controls_section();

        // --- TAB: STYLE - IMAGE & FILTERS ---
        $this->start_controls_section('style_image', [ 'label' => __( 'Image Styling', 'cora-builder' ), 'tab' => Controls_Manager::TAB_STYLE ]);
        
        // Exhaustive Dimension Controls
        $this->add_responsive_control('width', [
            'label' => 'Width', 'type' => Controls_Manager::SLIDER, 'size_units' => ['px', '%', 'vw'],
            'selectors' => [ '{{WRAPPER}} img' => 'width: {{SIZE}}{{UNIT}};' ],
        ]);

        $this->add_responsive_control('object_fit', [
            'label' => 'Fit Type', 'type' => Controls_Manager::SELECT,
            'options' => [ 'cover' => 'Cover', 'contain' => 'Contain', 'fill' => 'Fill' ],
            'selectors' => [ '{{WRAPPER}} img' => 'object-fit: {{VALUE}};' ],
        ]);

        $this->end_controls_section();

        // --- TAB: STYLE - CAPTION ENGINE ---
        // Utilizing your fixed register_text_styling_controls from Base_Widget
        $this->register_text_styling_controls('img_caption', 'Caption Styling', '{{WRAPPER}} .cora-image-caption');

        $this->register_common_spatial_controls();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $has_link = ! empty( $settings['link_to']['url'] );
        
        $this->add_render_attribute( 'wrapper', 'class', 'cora-image-container' );
        ?>
        <div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
            <?php if ( $has_link ) : ?>
                <a href="<?php echo esc_url( $settings['link_to']['url'] ); ?>" <?php echo $settings['link_to']['is_external'] ? 'target="_blank"' : ''; ?>>
            <?php endif; ?>

            <div class="cora-image-mask">
                <?php echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'image_size', 'image' ); ?>
            </div>

            <?php if ( $has_link ) : ?></a><?php endif; ?>

            <?php if ( ! empty( $settings['caption_text'] ) ) : ?>
                <div class="cora-image-caption"><?php echo esc_html( $settings['caption_text'] ); ?></div>
            <?php endif; ?>
        </div>
        <?php
    }
}