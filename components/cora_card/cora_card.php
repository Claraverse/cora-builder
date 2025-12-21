<?php
namespace Cora_Builder\Components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Cora_Card extends Base_Widget {

	public function get_name() {
		return 'cora_card';
	}

	public function get_title() {
		return 'Cora Card (Atomic)';
	}

	public function get_icon() {
		return 'eicon-image-box';
	}

	public function get_categories() {
		return [ 'cora_widgets' ];
	}

	protected function register_controls() {

		// ==========================
		// TAB: CONTENT
		// ==========================
		$this->start_controls_section(
			'section_content',
			[ 'label' => 'Card Content', 'tab' => Controls_Manager::TAB_CONTENT ]
		);

		$this->add_responsive_control(
			'layout_mode',
			[
				'label' => 'Layout Mode',
				'type' => Controls_Manager::SELECT,
				'default' => 'side',
				'options' => [
					'side' => 'Side Image (Left)',
					'stacked' => 'Stacked Image (Top)',
					'overlay' => 'Overlay (Text on Image)',
				],
				'prefix_class' => 'cora_layout%s_', // Generates cora_layout_desktop_side, cora_layout_mobile_stacked, etc.
			]
		);

		$this->add_control(
			'image',
			[
				'label' => 'Image',
				'type' => Controls_Manager::MEDIA,
				'default' => [ 'url' => \Elementor\Utils::get_placeholder_image_src() ],
				'dynamic' => [ 'active' => true ],
			]
		);

		$this->add_control(
			'title',
			[
				'label' => 'Title',
				'type' => Controls_Manager::TEXTAREA,
				'default' => 'Designing for the Future',
				'rows' => 2,
				'dynamic' => [ 'active' => true ],
			]
		);

		$this->add_control(
			'meta_text',
			[
				'label' => 'Meta Text',
				'type' => Controls_Manager::TEXT,
				'default' => 'Oct 14, 2025 • 5 min read',
				'dynamic' => [ 'active' => true ],
			]
		);

		$this->add_control(
			'show_badge',
			[
				'label' => 'Show Badge',
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
			]
		);

		$this->add_control(
			'badge_text',
			[
				'label' => 'Badge Text',
				'type' => Controls_Manager::TEXT,
				'default' => 'New',
				'condition' => [ 'show_badge' => 'yes' ],
				'dynamic' => [ 'active' => true ],
			]
		);

		$this->add_control(
			'link',
			[
				'label' => 'Link',
				'type' => Controls_Manager::URL,
				'placeholder' => 'https://your-link.com',
				'dynamic' => [ 'active' => true ],
			]
		);

		$this->end_controls_section();


		// ==========================
		// TAB: STYLE
		// ==========================

		// --- SECTION: LAYOUT ---
		$this->start_controls_section(
			'section_style_layout',
			[ 'label' => 'Layout & Dimensions', 'tab' => Controls_Manager::TAB_STYLE ]
		);

		$this->add_responsive_control(
			'content_align',
			[
				'label' => 'Horizontal Align',
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'start' => [ 'title' => 'Left', 'icon' => 'eicon-text-align-left' ],
					'center' => [ 'title' => 'Center', 'icon' => 'eicon-text-align-center' ],
					'end' => [ 'title' => 'Right', 'icon' => 'eicon-text-align-right' ],
				],
				'selectors' => [ '{{WRAPPER}} .cora_card_content' => 'text-align: {{VALUE}}; align-items: {{VALUE}};' ],
			]
		);

		$this->add_responsive_control(
			'content_vertical_align',
			[
				'label' => 'Vertical Align',
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [ 'title' => 'Top', 'icon' => 'eicon-v-align-top' ],
					'center' => [ 'title' => 'Middle', 'icon' => 'eicon-v-align-middle' ],
					'flex-end' => [ 'title' => 'Bottom', 'icon' => 'eicon-v-align-bottom' ],
				],
				'selectors' => [ '{{WRAPPER}} .cora_card_content' => 'justify-content: {{VALUE}};' ],
				'condition' => [ 'layout_mode!' => 'stacked' ], // Only useful for side/overlay
			]
		);

		$this->add_responsive_control(
			'image_width',
			[
				'label' => 'Image Width',
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [ 'px' => [ 'min' => 50, 'max' => 600 ], '%' => [ 'min' => 10, 'max' => 100 ] ],
				'default' => [ 'unit' => 'px', 'size' => 140 ],
				'selectors' => [ 
					'{{WRAPPER}} .cora_card_img_wrap' => 'width: {{SIZE}}{{UNIT}}; flex-shrink: 0;',
				],
				'condition' => [ 'layout_mode' => 'side' ],
			]
		);

		$this->add_responsive_control(
			'image_height',
			[
				'label' => 'Image Height',
				'type' => Controls_Manager::SLIDER,
				'range' => [ 'px' => [ 'min' => 50, 'max' => 600 ] ],
				'default' => [ 'unit' => 'px', 'size' => 140 ],
				'selectors' => [ 
					'{{WRAPPER}} .cora_card_img' => 'height: {{SIZE}}px;',
					'{{WRAPPER}} .cora_layout_overlay .cora_card_img_wrap' => 'height: {{SIZE}}px;',
				],
			]
		);
		
		$this->add_responsive_control(
			'content_gap',
			[
				'label' => 'Content Gap',
				'type' => Controls_Manager::SLIDER,
				'default' => [ 'size' => 20 ],
				'selectors' => [ '{{WRAPPER}} .cora_single_card' => 'gap: {{SIZE}}px;' ],
				'condition' => [ 'layout_mode!' => 'overlay' ],
			]
		);

		$this->add_control(
			'object_fit',
			[
				'label' => 'Image Fit',
				'type' => Controls_Manager::SELECT,
				'default' => 'cover',
				'options' => [ 'cover' => 'Cover', 'contain' => 'Contain', 'fill' => 'Fill' ],
				'selectors' => [ '{{WRAPPER}} .cora_card_img' => 'background-size: {{VALUE}};' ],
			]
		);

		$this->end_controls_section();

		// --- SECTION: CARD BOX ---
		$this->start_controls_section(
			'section_style_box',
			[ 'label' => 'Card Box', 'tab' => Controls_Manager::TAB_STYLE ]
		);
		
		$this->add_responsive_control( 'padding', [ 'label' => 'Padding', 'type' => Controls_Manager::DIMENSIONS, 'selectors' => [ '{{WRAPPER}} .cora_single_card' => 'padding: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;' ] ] );
		$this->add_group_control( Group_Control_Background::get_type(), [ 'name' => 'card_bg', 'selector' => '{{WRAPPER}} .cora_single_card' ] );
		$this->add_group_control( Group_Control_Border::get_type(), [ 'name' => 'card_border', 'selector' => '{{WRAPPER}} .cora_single_card' ] );
		
		$this->add_responsive_control( 
			'radius', 
			[ 
				'label' => 'Border Radius', 
				'type' => Controls_Manager::DIMENSIONS, 
				'selectors' => [ 
					'{{WRAPPER}} .cora_single_card' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;', 
					'{{WRAPPER}} .cora_card_img_wrap' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;' 
				] 
			] 
		);
		
		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [ 'name' => 'card_shadow', 'selector' => '{{WRAPPER}} .cora_single_card' ] );
		$this->add_control( 'hover_anim', [ 'label' => 'Hover Animation', 'type' => Controls_Manager::SWITCHER, 'default' => 'yes', 'return_value' => 'yes' ] );

		$this->end_controls_section();

		// --- SECTION: TYPOGRAPHY ---
		$this->start_controls_section(
			'section_style_typo',
			[ 'label' => 'Typography', 'tab' => Controls_Manager::TAB_STYLE ]
		);

		// Title
		$this->add_control( 'heading_title', [ 'label' => 'Title', 'type' => Controls_Manager::HEADING ] );
		$this->add_control( 'title_color', [ 'label' => 'Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_card_title' => 'color: {{VALUE}};' ] ] );
		$this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'title_typo', 'selector' => '{{WRAPPER}} .cora_card_title' ] );
		$this->add_responsive_control( 'title_spacing', [ 'label' => 'Spacing', 'type' => Controls_Manager::SLIDER, 'selectors' => [ '{{WRAPPER}} .cora_card_title' => 'margin-bottom: {{SIZE}}px;' ] ] );

		// Meta
		$this->add_control( 'heading_meta', [ 'label' => 'Meta', 'type' => Controls_Manager::HEADING, 'separator' => 'before' ] );
		$this->add_control( 'meta_color', [ 'label' => 'Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_card_meta' => 'color: {{VALUE}};' ] ] );
		$this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'meta_typo', 'selector' => '{{WRAPPER}} .cora_card_meta' ] );

		// Badge
		$this->add_control( 'heading_badge', [ 'label' => 'Badge', 'type' => Controls_Manager::HEADING, 'separator' => 'before', 'condition' => [ 'show_badge' => 'yes' ] ] );
		$this->add_control( 'badge_bg', [ 'label' => 'Background', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_card_badge' => 'background-color: {{VALUE}};' ], 'condition' => [ 'show_badge' => 'yes' ] ] );
		$this->add_control( 'badge_color', [ 'label' => 'Text Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_card_badge' => 'color: {{VALUE}};' ], 'condition' => [ 'show_badge' => 'yes' ] ] );

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$link_url = $settings['link']['url'];
		$tag = ! empty( $link_url ) ? 'a' : 'div';
		$href = ! empty( $link_url ) ? 'href="' . esc_url( $link_url ) . '"' : '';
		
		// Note: The classes .cora_layout_desktop_side etc are auto-added by Elementor prefix_class control
		$classes = 'cora_single_card'; 
		if ( 'yes' === $settings['hover_anim'] ) { $classes .= ' cora-hover-lift'; }
		
		?>

		<<?php echo $tag . ' ' . $href; ?> class="<?php echo esc_attr( $classes ); ?>">
			
			<div class="cora_card_img_wrap">
				<div class="cora_card_img" style="background-image: url('<?php echo esc_url( $settings['image']['url'] ); ?>');"></div>
				
				<?php if ( strpos( $settings['layout_mode'], 'overlay' ) !== false ) : ?>
					<div class="cora_card_overlay"></div>
				<?php endif; ?>

				<?php if ( 'yes' === $settings['show_badge'] ) : ?>
					<span class="cora_card_badge"><?php echo esc_html( $settings['badge_text'] ); ?></span>
				<?php endif; ?>
			</div>
			
			<div class="cora_card_content">
				<div class="cora_card_meta"><?php echo esc_html( $settings['meta_text'] ); ?></div>
				<h3 class="cora_card_title"><?php echo esc_html( $settings['title'] ); ?></h3>
			</div>

		</<?php echo $tag; ?>>

		<?php
	}
}