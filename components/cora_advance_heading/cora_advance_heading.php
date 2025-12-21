<?php
namespace Cora_Builder\Components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Cora_Advance_Heading extends Base_Widget {

	public function get_name() {
		return 'cora_advance_heading';
	}

	public function get_title() {
		return 'Cora Advance Heading';
	}

	public function get_icon() {
		return 'eicon-heading';
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
			[ 'label' => 'Heading Content', 'tab' => Controls_Manager::TAB_CONTENT ]
		);

		$this->add_control(
			'text_before',
			[
				'label' => 'Text Before',
				'type' => Controls_Manager::TEXT,
				'default' => 'Browse by',
				'dynamic' => [ 'active' => true ],
			]
		);

		$this->add_control(
			'text_highlight',
			[
				'label' => 'Highlighted Text',
				'type' => Controls_Manager::TEXT,
				'default' => 'Category',
				'dynamic' => [ 'active' => true ],
			]
		);

		$this->add_control(
			'highlight_type',
			[
				'label' => 'Highlight Source',
				'type' => Controls_Manager::SELECT,
				'default' => 'svg',
				'options' => [
					'image' => 'Image Upload',
					'svg' => 'Raw SVG Code (Recommended)',
				],
			]
		);

		$this->add_control(
			'highlight_image',
			[
				'label' => 'Brush Image',
				'type' => Controls_Manager::MEDIA,
				'condition' => [ 'highlight_type' => 'image' ],
			]
		);

		$this->add_control(
			'highlight_svg',
			[
				'label' => 'Paste SVG Code',
				'type' => Controls_Manager::TEXTAREA,
				'rows' => 10,
				'placeholder' => '<svg>...</svg>',
				'description' => 'Paste your SVG code here. Remove width/height attributes for best results.',
				'condition' => [ 'highlight_type' => 'svg' ],
			]
		);

		$this->add_control(
			'description',
			[
				'label' => 'Description',
				'type' => Controls_Manager::TEXTAREA,
				'default' => 'Find guides organized by your current business challenge',
				'rows' => 2,
				'dynamic' => [ 'active' => true ],
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label' => 'Alignment',
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [ 'title' => 'Left', 'icon' => 'eicon-text-align-left' ],
					'center' => [ 'title' => 'Center', 'icon' => 'eicon-text-align-center' ],
					'right' => [ 'title' => 'Right', 'icon' => 'eicon-text-align-right' ],
				],
				'selectors' => [ '{{WRAPPER}} .cora_adv_heading_wrapper' => 'text-align: {{VALUE}};' ],
				'default' => 'center',
			]
		);

		$this->end_controls_section();


		// ==========================
		// TAB: STYLE
		// ==========================

		// --- LAYOUT DEFAULTS ---
		$this->start_controls_section(
			'section_style_layout',
			[ 'label' => 'Layout Panel', 'tab' => Controls_Manager::TAB_STYLE ]
		);

		$this->add_responsive_control(
			'container_width',
			[
				'label' => 'Max Width',
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [ 'px' => [ 'min' => 0, 'max' => 1200 ], '%' => [ 'min' => 0, 'max' => 100 ] ],
				'selectors' => [ '{{WRAPPER}} .cora_adv_heading_wrapper' => 'max-width: {{SIZE}}{{UNIT}}; margin-left: auto; margin-right: auto;' ],
			]
		);

		$this->add_responsive_control(
			'container_padding',
			[
				'label' => 'Padding',
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [ '{{WRAPPER}} .cora_adv_heading_wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
			]
		);

		$this->add_responsive_control(
			'container_margin',
			[
				'label' => 'Margin',
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [ '{{WRAPPER}} .cora_adv_heading_wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
			]
		);

		$this->end_controls_section();


		// --- MAIN HEADING ---
		$this->start_controls_section( 'section_style_heading', [ 'label' => 'Heading Typography', 'tab' => Controls_Manager::TAB_STYLE ] );
		
		$this->add_control( 'color_text_main', [ 'label' => 'Main Text Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_head_main' => 'color: {{VALUE}};' ] ] );
		$this->add_control( 'color_text_highlight', [ 'label' => 'Highlight Text Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_head_highlight' => 'color: {{VALUE}};' ] ] );
		
		$this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'typography_main', 'selector' => '{{WRAPPER}} .cora_adv_heading_title' ] );
		
		$this->add_responsive_control( 'heading_spacing', [ 'label' => 'Bottom Spacing', 'type' => Controls_Manager::SLIDER, 'selectors' => [ '{{WRAPPER}} .cora_adv_heading_title' => 'margin-bottom: {{SIZE}}px;' ] ] );

		// TITLE TRUNCATION (NEW)
		$this->add_control( 'trunc_title', [ 'label' => 'Truncate Title', 'type' => Controls_Manager::SWITCHER, 'separator' => 'before' ] );
		$this->add_responsive_control(
			'trunc_title_lines',
			[
				'label' => 'Max Lines',
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'default' => 2,
				'selectors' => [
					'{{WRAPPER}} .cora_adv_heading_title' => 'display: -webkit-box; -webkit-line-clamp: {{VALUE}}; -webkit-box-orient: vertical; overflow: hidden;',
				],
				'condition' => [ 'trunc_title' => 'yes' ],
			]
		);

		$this->end_controls_section();

		// --- BRUSH STROKE ---
		$this->start_controls_section( 'section_style_brush', [ 'label' => 'Brush Highlight', 'tab' => Controls_Manager::TAB_STYLE ] );
		
		$this->add_responsive_control(
			'brush_width',
			[
				'label' => 'Brush Width',
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'range' => [ '%' => [ 'min' => 50, 'max' => 200 ] ],
				'default' => [ 'unit' => '%', 'size' => 110 ],
				'selectors' => [ '{{WRAPPER}} .cora_brush_layer' => 'width: {{SIZE}}{{UNIT}};' ],
			]
		);

		$this->add_responsive_control(
			'brush_rotation',
			[
				'label' => 'Rotation',
				'type' => Controls_Manager::SLIDER,
				'range' => [ 'px' => [ 'min' => -45, 'max' => 45 ] ],
				'selectors' => [ '{{WRAPPER}} .cora_brush_layer' => 'transform: translate(-50%, -50%) rotate({{SIZE}}deg);' ],
			]
		);

		$this->add_control(
			'svg_color',
			[
				'label' => 'SVG Fill Color',
				'type' => Controls_Manager::COLOR,
				'selectors' => [ '{{WRAPPER}} .cora_brush_layer svg path' => 'fill: {{VALUE}};', '{{WRAPPER}} .cora_brush_layer svg' => 'fill: {{VALUE}};' ],
				'condition' => [ 'highlight_type' => 'svg' ],
			]
		);
		
		$this->end_controls_section();

		// --- DESCRIPTION ---
		$this->start_controls_section( 'section_style_desc', [ 'label' => 'Description & Truncation', 'tab' => Controls_Manager::TAB_STYLE ] );
		
		$this->add_control( 'color_desc', [ 'label' => 'Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_adv_desc' => 'color: {{VALUE}};' ] ] );
		$this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'typography_desc', 'selector' => '{{WRAPPER}} .cora_adv_desc' ] );
		$this->add_responsive_control( 'desc_width', [ 'label' => 'Max Width', 'type' => Controls_Manager::SLIDER, 'size_units' => ['px', '%'], 'selectors' => [ '{{WRAPPER}} .cora_adv_desc' => 'max-width: {{SIZE}}{{UNIT}}; margin-left: auto; margin-right: auto;' ] ] );
		
		// DESC TRUNCATION (UPDATED)
		$this->add_control( 'enable_truncate', [ 'label' => 'Enable Truncation', 'type' => Controls_Manager::SWITCHER, 'separator' => 'before' ] );
		
		$this->add_responsive_control(
			'truncate_lines',
			[
				'label' => 'Max Lines',
				'type' => Controls_Manager::NUMBER,
				'default' => 2,
				'min' => 1,
				'selectors' => [
					'{{WRAPPER}} .cora_adv_desc' => 'display: -webkit-box; -webkit-line-clamp: {{VALUE}}; -webkit-box-orient: vertical; overflow: hidden;',
				],
				'condition' => [ 'enable_truncate' => 'yes' ],
			]
		);
		
		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		?>
		<div class="cora_adv_heading_wrapper">
			<h2 class="cora_adv_heading_title">
				<span class="cora_head_main"><?php echo esc_html( $settings['text_before'] ); ?></span>
				
				<span class="cora_head_highlight_wrap">
					<span class="cora_head_highlight"><?php echo esc_html( $settings['text_highlight'] ); ?></span>
					
					<div class="cora_brush_layer">
						<?php if ( 'image' === $settings['highlight_type'] && ! empty( $settings['highlight_image']['url'] ) ) : ?>
							<img src="<?php echo esc_url( $settings['highlight_image']['url'] ); ?>" alt="brush">
						<?php elseif ( 'svg' === $settings['highlight_type'] && ! empty( $settings['highlight_svg'] ) ) : ?>
							<?php echo $settings['highlight_svg']; // Safe SVG Output ?>
						<?php endif; ?>
					</div>
				</span>
			</h2>
			
			<?php if ( ! empty( $settings['description'] ) ) : ?>
				<div class="cora_adv_desc"><?php echo esc_html( $settings['description'] ); ?></div>
			<?php endif; ?>
		</div>
		<?php
	}
}