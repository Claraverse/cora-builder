<?php
namespace Cora_Builder\Components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Cora_Category_Card extends Base_Widget {

	public function get_name() {
		return 'cora_category_card';
	}

	public function get_title() {
		return 'Cora Category Card';
	}

	public function get_icon() {
		return 'eicon-icon-box';
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

		// ICON SOURCE SELECTION
		$this->add_control(
			'icon_type',
			[
				'label' => 'Icon Source',
				'type' => Controls_Manager::SELECT,
				'default' => 'icon',
				'options' => [
					'icon' => 'Icon Library',
					'image' => 'Upload Image',
					'svg' => 'SVG Code',
				],
			]
		);

		$this->add_control(
			'selected_icon',
			[
				'label' => 'Icon',
				'type' => Controls_Manager::ICONS,
				'default' => [ 'value' => 'fas fa-layer-group', 'library' => 'fa-solid' ],
				'condition' => [ 'icon_type' => 'icon' ],
			]
		);

		$this->add_control(
			'icon_image',
			[
				'label' => 'Upload Image',
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [ 'active' => true ],
				'condition' => [ 'icon_type' => 'image' ],
			]
		);

		$this->add_control(
			'icon_svg_raw',
			[
				'label' => 'Paste SVG Code',
				'type' => Controls_Manager::TEXTAREA,
				'rows' => 8,
				'placeholder' => '<svg>...</svg>',
				'description' => 'Paste raw SVG code here. Remove width/height attributes for best scaling.',
				'condition' => [ 'icon_type' => 'svg' ],
			]
		);

		// TEXT
		$this->add_control(
			'title',
			[
				'label' => 'Label',
				'type' => Controls_Manager::TEXT,
				'default' => 'Framer',
				'dynamic' => [ 'active' => true ],
				'label_block' => true,
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

		// --- CARD BOX ---
		$this->start_controls_section( 'section_style_box', [ 'label' => 'Card Box', 'tab' => Controls_Manager::TAB_STYLE ] );
		
		$this->add_responsive_control(
			'card_height',
			[
				'label' => 'Min Height',
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vh' ],
				'range' => [ 'px' => [ 'min' => 50, 'max' => 500 ] ],
				'selectors' => [ '{{WRAPPER}} .cora_cat_card' => 'min-height: {{SIZE}}{{UNIT}};' ],
			]
		);

		$this->add_responsive_control( 'padding', [ 'label' => 'Padding', 'type' => Controls_Manager::DIMENSIONS, 'selectors' => [ '{{WRAPPER}} .cora_cat_card' => 'padding: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;' ], 'default' => [ 'top' => 30, 'right' => 20, 'bottom' => 30, 'left' => 20, 'isLinked' => false ] ] );
		
		$this->add_responsive_control( 'align', [ 'label' => 'Alignment', 'type' => Controls_Manager::CHOOSE, 'options' => [ 'left' => [ 'title' => 'Left', 'icon' => 'eicon-text-align-left' ], 'center' => [ 'title' => 'Center', 'icon' => 'eicon-text-align-center' ], 'right' => [ 'title' => 'Right', 'icon' => 'eicon-text-align-right' ] ], 'selectors' => [ '{{WRAPPER}} .cora_cat_card' => 'text-align: {{VALUE}}; align-items: {{VALUE}};' ], 'default' => 'center' ] );

		// HOVER EFFECT (User Decides)
		$this->add_control(
			'hover_effect',
			[
				'label' => 'Hover Animation',
				'type' => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'none' => 'None',
					'move-up' => 'Move Up',
					'scale' => 'Scale Up',
				],
				'prefix_class' => 'cora-hover-',
			]
		);

		$this->start_controls_tabs( 'tabs_card_style' );

		// Normal
		$this->start_controls_tab( 'tab_card_normal', [ 'label' => 'Normal' ] );
		$this->add_group_control( Group_Control_Background::get_type(), [ 'name' => 'bg', 'selector' => '{{WRAPPER}} .cora_cat_card' ] );
		$this->add_group_control( Group_Control_Border::get_type(), [ 'name' => 'border', 'selector' => '{{WRAPPER}} .cora_cat_card' ] );
		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [ 'name' => 'shadow', 'selector' => '{{WRAPPER}} .cora_cat_card' ] );
		$this->end_controls_tab();

		// Hover
		$this->start_controls_tab( 'tab_card_hover', [ 'label' => 'Hover' ] );
		$this->add_group_control( Group_Control_Background::get_type(), [ 'name' => 'bg_hover', 'selector' => '{{WRAPPER}} .cora_cat_card:hover' ] );
		$this->add_group_control( Group_Control_Border::get_type(), [ 'name' => 'border_hover', 'selector' => '{{WRAPPER}} .cora_cat_card:hover' ] );
		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [ 'name' => 'shadow_hover', 'selector' => '{{WRAPPER}} .cora_cat_card:hover' ] );
		$this->end_controls_tab();

		$this->end_controls_tabs();
		
		$this->add_responsive_control( 'radius', [ 'label' => 'Border Radius', 'type' => Controls_Manager::DIMENSIONS, 'selectors' => [ '{{WRAPPER}} .cora_cat_card' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;' ], 'separator' => 'before' ] );
		
		$this->end_controls_section();


		// --- ICON STYLE ---
		$this->start_controls_section( 'section_style_icon', [ 'label' => 'Icon', 'tab' => Controls_Manager::TAB_STYLE ] );
		
		$this->add_responsive_control( 'icon_size', [ 'label' => 'Size', 'type' => Controls_Manager::SLIDER, 'range' => [ 'px' => [ 'min' => 10, 'max' => 100 ] ], 'default' => [ 'size' => 32, 'unit' => 'px' ], 'selectors' => [ '{{WRAPPER}} .cora_cat_icon i' => 'font-size: {{SIZE}}px;', '{{WRAPPER}} .cora_cat_icon svg' => 'width: {{SIZE}}px; height: {{SIZE}}px;', '{{WRAPPER}} .cora_cat_icon img' => 'width: {{SIZE}}px;' ] ] );
		
		$this->add_responsive_control( 'icon_spacing', [ 'label' => 'Bottom Spacing', 'type' => Controls_Manager::SLIDER, 'default' => [ 'size' => 15, 'unit' => 'px' ], 'selectors' => [ '{{WRAPPER}} .cora_cat_icon' => 'margin-bottom: {{SIZE}}px;' ] ] );

		$this->start_controls_tabs( 'tabs_icon_colors' );
		
		$this->start_controls_tab( 'tab_icon_normal', [ 'label' => 'Normal' ] );
		$this->add_control( 'icon_color', [ 'label' => 'Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_cat_icon' => 'color: {{VALUE}}; fill: {{VALUE}};' ] ] );
		$this->end_controls_tab();

		$this->start_controls_tab( 'tab_icon_hover', [ 'label' => 'Hover' ] );
		$this->add_control( 'icon_color_hover', [ 'label' => 'Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_cat_card:hover .cora_cat_icon' => 'color: {{VALUE}}; fill: {{VALUE}};' ] ] );
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		$this->end_controls_section();


		// --- LABEL STYLE ---
		$this->start_controls_section( 'section_style_label', [ 'label' => 'Label', 'tab' => Controls_Manager::TAB_STYLE ] );
		
		$this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'label_typo', 'selector' => '{{WRAPPER}} .cora_cat_title' ] );
		
		$this->start_controls_tabs( 'tabs_label_colors' );
		
		$this->start_controls_tab( 'tab_label_normal', [ 'label' => 'Normal' ] );
		$this->add_control( 'label_color', [ 'label' => 'Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_cat_title' => 'color: {{VALUE}};' ] ] );
		$this->end_controls_tab();

		$this->start_controls_tab( 'tab_label_hover', [ 'label' => 'Hover' ] );
		$this->add_control( 'label_color_hover', [ 'label' => 'Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_cat_card:hover .cora_cat_title' => 'color: {{VALUE}};' ] ] );
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$link_url = $settings['link']['url'];
		$tag = ! empty( $link_url ) ? 'a' : 'div';
		$href = ! empty( $link_url ) ? 'href="' . esc_url( $link_url ) . '"' : '';
		
		?>
		<<?php echo $tag . ' ' . $href; ?> class="cora_cat_card">
			
			<div class="cora_cat_icon">
				<?php if ( 'image' === $settings['icon_type'] && ! empty( $settings['icon_image']['url'] ) ) : ?>
					<img src="<?php echo esc_url( $settings['icon_image']['url'] ); ?>" alt="icon">
				<?php elseif ( 'svg' === $settings['icon_type'] && ! empty( $settings['icon_svg_raw'] ) ) : ?>
					<?php echo $settings['icon_svg_raw']; // Raw SVG ?>
				<?php elseif ( 'icon' === $settings['icon_type'] ) : ?>
					<?php \Elementor\Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true' ] ); ?>
				<?php endif; ?>
			</div>

			<span class="cora_cat_title"><?php echo esc_html( $settings['title'] ); ?></span>

		</<?php echo $tag; ?>>
		<?php
	}
}