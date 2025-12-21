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

class Cora_Industry_Card extends Base_Widget {

	public function get_name() {
		return 'cora_industry_card';
	}

	public function get_title() {
		return 'Cora Industry Card';
	}

	public function get_icon() {
		return 'eicon-info-box';
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

		// ICON
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
				'default' => [ 'value' => 'fas fa-microchip', 'library' => 'fa-solid' ],
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
				'condition' => [ 'icon_type' => 'svg' ],
			]
		);

		// TEXT CONTENT
		$this->add_control( 'title', [ 'label' => 'Title', 'type' => Controls_Manager::TEXT, 'default' => 'SaaS', 'dynamic' => [ 'active' => true ], 'label_block' => true ] );
		
		$this->add_control( 'description', [ 'label' => 'Description', 'type' => Controls_Manager::TEXTAREA, 'default' => 'Product-led growth strategies', 'dynamic' => [ 'active' => true ], 'rows' => 2 ] );

		// LINK / BUTTON
		$this->add_control( 'btn_text', [ 'label' => 'Button Text', 'type' => Controls_Manager::TEXT, 'default' => 'Explore Articles', 'dynamic' => [ 'active' => true ] ] );
		
		$this->add_control( 'link', [ 'label' => 'Link', 'type' => Controls_Manager::URL, 'placeholder' => 'https://your-link.com', 'dynamic' => [ 'active' => true ] ] );

		$this->end_controls_section();


		// ==========================
		// TAB: STYLE
		// ==========================

		// --- CARD BOX ---
		$this->start_controls_section( 'section_style_box', [ 'label' => 'Card Box', 'tab' => Controls_Manager::TAB_STYLE ] );
		
		$this->add_responsive_control( 'card_height', [ 'label' => 'Min Height', 'type' => Controls_Manager::SLIDER, 'size_units' => [ 'px', 'vh' ], 'selectors' => [ '{{WRAPPER}} .cora_ind_card' => 'min-height: {{SIZE}}{{UNIT}};' ] ] );
		
		$this->add_responsive_control( 'padding', [ 'label' => 'Padding', 'type' => Controls_Manager::DIMENSIONS, 'selectors' => [ '{{WRAPPER}} .cora_ind_card' => 'padding: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;' ], 'default' => [ 'top' => 32, 'right' => 32, 'bottom' => 32, 'left' => 32, 'isLinked' => true ] ] );
		
		$this->add_responsive_control( 'align', [ 'label' => 'Alignment', 'type' => Controls_Manager::CHOOSE, 'options' => [ 'left' => [ 'title' => 'Left', 'icon' => 'eicon-text-align-left' ], 'center' => [ 'title' => 'Center', 'icon' => 'eicon-text-align-center' ], 'right' => [ 'title' => 'Right', 'icon' => 'eicon-text-align-right' ] ], 'selectors' => [ '{{WRAPPER}} .cora_ind_card' => 'text-align: {{VALUE}};' ], 'default' => 'left' ] );

		$this->start_controls_tabs( 'tabs_card_style' );
		$this->start_controls_tab( 'tab_card_normal', [ 'label' => 'Normal' ] );
		$this->add_group_control( Group_Control_Background::get_type(), [ 'name' => 'bg', 'selector' => '{{WRAPPER}} .cora_ind_card' ] );
		$this->add_group_control( Group_Control_Border::get_type(), [ 'name' => 'border', 'selector' => '{{WRAPPER}} .cora_ind_card' ] );
		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [ 'name' => 'shadow', 'selector' => '{{WRAPPER}} .cora_ind_card' ] );
		$this->end_controls_tab();

		$this->start_controls_tab( 'tab_card_hover', [ 'label' => 'Hover' ] );
		$this->add_group_control( Group_Control_Background::get_type(), [ 'name' => 'bg_hover', 'selector' => '{{WRAPPER}} .cora_ind_card:hover' ] );
		$this->add_group_control( Group_Control_Border::get_type(), [ 'name' => 'border_hover', 'selector' => '{{WRAPPER}} .cora_ind_card:hover' ] );
		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [ 'name' => 'shadow_hover', 'selector' => '{{WRAPPER}} .cora_ind_card:hover' ] );
		$this->add_control( 'hover_anim', [ 'label' => 'Hover Animation', 'type' => Controls_Manager::SELECT, 'default' => 'move-up', 'options' => [ 'none' => 'None', 'move-up' => 'Move Up', 'scale' => 'Scale Up' ] ] );
		$this->end_controls_tab();
		$this->end_controls_tabs();
		
		$this->add_responsive_control( 'radius', [ 'label' => 'Border Radius', 'type' => Controls_Manager::DIMENSIONS, 'selectors' => [ '{{WRAPPER}} .cora_ind_card' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;' ], 'separator' => 'before' ] );
		
		$this->end_controls_section();


		// --- ICON STYLE ---
		$this->start_controls_section( 'section_style_icon', [ 'label' => 'Icon', 'tab' => Controls_Manager::TAB_STYLE ] );
		
		$this->add_responsive_control( 'icon_size', [ 'label' => 'Size', 'type' => Controls_Manager::SLIDER, 'range' => [ 'px' => [ 'min' => 10, 'max' => 100 ] ], 'default' => [ 'size' => 40, 'unit' => 'px' ], 'selectors' => [ '{{WRAPPER}} .cora_ind_icon i' => 'font-size: {{SIZE}}px;', '{{WRAPPER}} .cora_ind_icon svg' => 'width: {{SIZE}}px; height: {{SIZE}}px;', '{{WRAPPER}} .cora_ind_icon img' => 'width: {{SIZE}}px;' ] ] );
		
		$this->add_responsive_control( 'icon_spacing', [ 'label' => 'Bottom Spacing', 'type' => Controls_Manager::SLIDER, 'default' => [ 'size' => 20, 'unit' => 'px' ], 'selectors' => [ '{{WRAPPER}} .cora_ind_icon' => 'margin-bottom: {{SIZE}}px;' ] ] );

		$this->add_control( 'icon_color', [ 'label' => 'Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_ind_icon' => 'color: {{VALUE}}; fill: {{VALUE}};' ] ] );
		
		$this->end_controls_section();


		// --- TYPOGRAPHY ---
		$this->start_controls_section( 'section_style_typo', [ 'label' => 'Typography', 'tab' => Controls_Manager::TAB_STYLE ] );
		
		// Title
		$this->add_control( 'title_color', [ 'label' => 'Title Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_ind_title' => 'color: {{VALUE}};' ] ] );
		$this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'title_typo', 'selector' => '{{WRAPPER}} .cora_ind_title' ] );
		$this->add_responsive_control( 'title_spacing', [ 'label' => 'Bottom Spacing', 'type' => Controls_Manager::SLIDER, 'selectors' => [ '{{WRAPPER}} .cora_ind_title' => 'margin-bottom: {{SIZE}}px;' ] ] );

		// Description
		$this->add_control( 'desc_color', [ 'label' => 'Desc Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_ind_desc' => 'color: {{VALUE}};' ] ] );
		$this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'desc_typo', 'selector' => '{{WRAPPER}} .cora_ind_desc' ] );
		$this->add_responsive_control( 'desc_spacing', [ 'label' => 'Bottom Spacing', 'type' => Controls_Manager::SLIDER, 'selectors' => [ '{{WRAPPER}} .cora_ind_desc' => 'margin-bottom: {{SIZE}}px;' ] ] );

		$this->end_controls_section();


		// --- BUTTON ---
		$this->start_controls_section( 'section_style_btn', [ 'label' => 'Button / Link', 'tab' => Controls_Manager::TAB_STYLE ] );
		
		$this->add_control( 'btn_color', [ 'label' => 'Text Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_ind_btn' => 'color: {{VALUE}};' ] ] );
		$this->add_control( 'btn_hover_color', [ 'label' => 'Hover Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_ind_card:hover .cora_ind_btn' => 'color: {{VALUE}};' ] ] );
		$this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'btn_typo', 'selector' => '{{WRAPPER}} .cora_ind_btn' ] );
		
		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$link_url = $settings['link']['url'];
		$tag = ! empty( $link_url ) ? 'a' : 'div';
		$href = ! empty( $link_url ) ? 'href="' . esc_url( $link_url ) . '"' : '';
		
		// Animation
		$anim_class = '';
		if ( 'move-up' === $settings['hover_anim'] ) $anim_class = 'cora-anim-up';
		if ( 'scale' === $settings['hover_anim'] ) $anim_class = 'cora-anim-scale';

		?>
		<<?php echo $tag . ' ' . $href; ?> class="cora_ind_card <?php echo esc_attr( $anim_class ); ?>">
			
			<div class="cora_ind_icon">
				<?php if ( 'image' === $settings['icon_type'] && ! empty( $settings['icon_image']['url'] ) ) : ?>
					<img src="<?php echo esc_url( $settings['icon_image']['url'] ); ?>" alt="icon">
				<?php elseif ( 'svg' === $settings['icon_type'] && ! empty( $settings['icon_svg_raw'] ) ) : ?>
					<?php echo $settings['icon_svg_raw']; ?>
				<?php elseif ( 'icon' === $settings['icon_type'] ) : ?>
					<?php \Elementor\Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true' ] ); ?>
				<?php endif; ?>
			</div>

			<h3 class="cora_ind_title"><?php echo esc_html( $settings['title'] ); ?></h3>
			<div class="cora_ind_desc"><?php echo esc_html( $settings['description'] ); ?></div>

			<?php if ( ! empty( $settings['btn_text'] ) ) : ?>
				<div class="cora_ind_btn">
					<?php echo esc_html( $settings['btn_text'] ); ?>
					<i class="eicon-arrow-right cora-arrow"></i>
				</div>
			<?php endif; ?>

		</<?php echo $tag; ?>>
		<?php
	}
}