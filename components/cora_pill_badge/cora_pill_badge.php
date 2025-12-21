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

class Cora_Pill_Badge extends Base_Widget {

	public function get_name() {
		return 'cora_pill_badge';
	}

	public function get_title() {
		return 'Cora Pill Badge';
	}

	public function get_icon() {
		return 'eicon-button';
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
			[ 'label' => 'Badge Content', 'tab' => Controls_Manager::TAB_CONTENT ]
		);

		$this->add_control(
			'text',
			[
				'label' => 'Label',
				'type' => Controls_Manager::TEXT,
				'default' => 'Solutions',
				'dynamic' => [ 'active' => true ],
			]
		);

		$this->add_control(
			'icon_type',
			[
				'label' => 'Icon Type',
				'type' => Controls_Manager::SELECT,
				'default' => 'icon',
				'options' => [
					'none' => 'None',
					'icon' => 'Icon Library',
					'svg' => 'SVG Code',
				],
			]
		);

		$this->add_control(
			'selected_icon',
			[
				'label' => 'Icon',
				'type' => Controls_Manager::ICONS,
				'default' => [ 'value' => 'fas fa-bullseye', 'library' => 'fa-solid' ],
				'condition' => [ 'icon_type' => 'icon' ],
			]
		);

		$this->add_control(
			'icon_svg',
			[
				'label' => 'SVG Code',
				'type' => Controls_Manager::TEXTAREA,
				'rows' => 5,
				'placeholder' => '<svg>...</svg>',
				'condition' => [ 'icon_type' => 'svg' ],
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
				'selectors' => [ '{{WRAPPER}} .cora_pill_wrapper' => 'text-align: {{VALUE}};' ],
				'default' => 'center',
			]
		);

		$this->end_controls_section();


		// ==========================
		// TAB: STYLE
		// ==========================

		// --- BOX STYLE ---
		$this->start_controls_section( 'section_style_box', [ 'label' => 'Badge Shape', 'tab' => Controls_Manager::TAB_STYLE ] );
		
		$this->add_responsive_control( 'padding', [ 'label' => 'Padding', 'type' => Controls_Manager::DIMENSIONS, 'selectors' => [ '{{WRAPPER}} .cora_pill' => 'padding: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;' ], 'default' => [ 'top' => 8, 'right' => 20, 'bottom' => 8, 'left' => 20, 'isLinked' => false ] ] );
		
		$this->add_responsive_control( 'radius', [ 'label' => 'Border Radius', 'type' => Controls_Manager::DIMENSIONS, 'selectors' => [ '{{WRAPPER}} .cora_pill' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;' ], 'default' => [ 'top' => 50, 'right' => 50, 'bottom' => 50, 'left' => 50, 'isLinked' => true ] ] );

		$this->add_group_control( Group_Control_Background::get_type(), [ 'name' => 'bg', 'selector' => '{{WRAPPER}} .cora_pill' ] );
		$this->add_group_control( Group_Control_Border::get_type(), [ 'name' => 'border', 'selector' => '{{WRAPPER}} .cora_pill' ] );
		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [ 'name' => 'shadow', 'selector' => '{{WRAPPER}} .cora_pill' ] );
		
		$this->end_controls_section();

		// --- CONTENT STYLE ---
		$this->start_controls_section( 'section_style_content', [ 'label' => 'Text & Icon', 'tab' => Controls_Manager::TAB_STYLE ] );
		
		// Text
		$this->add_control( 'text_color', [ 'label' => 'Text Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_pill_text' => 'color: {{VALUE}};' ] ] );
		$this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'typography', 'selector' => '{{WRAPPER}} .cora_pill_text' ] );

		// Icon
		$this->add_control( 'heading_icon', [ 'label' => 'Icon', 'type' => Controls_Manager::HEADING, 'separator' => 'before', 'condition' => [ 'icon_type!' => 'none' ] ] );
		$this->add_control( 'icon_color', [ 'label' => 'Icon Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_pill_icon' => 'color: {{VALUE}}; fill: {{VALUE}};' ], 'condition' => [ 'icon_type!' => 'none' ] ] );
		$this->add_responsive_control( 'icon_size', [ 'label' => 'Icon Size', 'type' => Controls_Manager::SLIDER, 'selectors' => [ '{{WRAPPER}} .cora_pill_icon i' => 'font-size: {{SIZE}}px;', '{{WRAPPER}} .cora_pill_icon svg' => 'width: {{SIZE}}px; height: {{SIZE}}px;' ], 'condition' => [ 'icon_type!' => 'none' ] ] );
		$this->add_responsive_control( 'icon_gap', [ 'label' => 'Icon Gap', 'type' => Controls_Manager::SLIDER, 'selectors' => [ '{{WRAPPER}} .cora_pill' => 'gap: {{SIZE}}px;' ], 'default' => [ 'size' => 8 ], 'condition' => [ 'icon_type!' => 'none' ] ] );

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		?>
		<div class="cora_pill_wrapper">
			<div class="cora_pill">
				<?php if ( 'icon' === $settings['icon_type'] ) : ?>
					<div class="cora_pill_icon">
						<?php \Elementor\Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true' ] ); ?>
					</div>
				<?php elseif ( 'svg' === $settings['icon_type'] ) : ?>
					<div class="cora_pill_icon">
						<?php echo $settings['icon_svg']; ?>
					</div>
				<?php endif; ?>

				<span class="cora_pill_text"><?php echo esc_html( $settings['text'] ); ?></span>
			</div>
		</div>
		<?php
	}
}