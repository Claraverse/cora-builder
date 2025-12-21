<?php
namespace Cora_Builder\Components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Cora_Solution_Card extends Base_Widget {

	public function get_name() {
		return 'cora_solution_card';
	}

	public function get_title() {
		return 'Cora Solution Card';
	}

	public function get_icon() {
		return 'eicon-bullet-list';
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

		$this->add_control( 'title', [ 'label' => 'Problem/Title', 'type' => Controls_Manager::TEXTAREA, 'default' => 'Struggling with User Retention?', 'rows' => 2, 'dynamic' => [ 'active' => true ] ] );
		
		$this->add_control( 'description', [ 'label' => 'Description', 'type' => Controls_Manager::TEXTAREA, 'default' => 'See how we solve retention issues with data-driven UX.', 'rows' => 2, 'dynamic' => [ 'active' => true ] ] );

		$this->add_control( 'list_heading', [ 'label' => 'List Items', 'type' => Controls_Manager::HEADING, 'separator' => 'before' ] );

		$repeater = new Repeater();
		$repeater->add_control( 'text', [ 'label' => 'Link Text', 'type' => Controls_Manager::TEXT, 'default' => 'Related Article Name', 'label_block' => true ] );
		$repeater->add_control( 'link', [ 'label' => 'Link', 'type' => Controls_Manager::URL, 'placeholder' => 'https://...' ] );
		
		$this->add_control(
			'list_items',
			[
				'label' => 'Related Articles',
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[ 'text' => 'The Psychology of Churn' ],
					[ 'text' => 'Onboarding Best Practices' ],
					[ 'text' => 'Re-engagement Strategies' ],
				],
				'title_field' => '{{{ text }}}',
			]
		);

		$this->add_control( 'icon_heading', [ 'label' => 'Icon Settings', 'type' => Controls_Manager::HEADING, 'separator' => 'before' ] );
		$this->add_control( 'list_icon', [ 'label' => 'List Icon (SVG)', 'type' => Controls_Manager::TEXTAREA, 'default' => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>', 'rows' => 3, 'description' => 'Paste raw SVG code.' ] );

		$this->end_controls_section();


		// ==========================
		// TAB: STYLE
		// ==========================

		// --- CONTAINER ---
		$this->start_controls_section( 'section_style_box', [ 'label' => 'Card Box', 'tab' => Controls_Manager::TAB_STYLE ] );
		$this->add_responsive_control( 'padding', [ 'label' => 'Padding', 'type' => Controls_Manager::DIMENSIONS, 'selectors' => [ '{{WRAPPER}} .cora_sol_card' => 'padding: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;' ], 'default' => [ 'top' => 32, 'right' => 32, 'bottom' => 32, 'left' => 32, 'isLinked' => true ] ] );
		$this->add_group_control( Group_Control_Background::get_type(), [ 'name' => 'bg', 'selector' => '{{WRAPPER}} .cora_sol_card' ] );
		$this->add_group_control( Group_Control_Border::get_type(), [ 'name' => 'border', 'selector' => '{{WRAPPER}} .cora_sol_card' ] );
		$this->add_responsive_control( 'radius', [ 'label' => 'Radius', 'type' => Controls_Manager::DIMENSIONS, 'selectors' => [ '{{WRAPPER}} .cora_sol_card' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;' ] ] );
		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [ 'name' => 'shadow', 'selector' => '{{WRAPPER}} .cora_sol_card' ] );
		$this->end_controls_section();

		// --- TYPOGRAPHY: TITLE ---
		$this->start_controls_section( 'section_style_title', [ 'label' => 'Title', 'tab' => Controls_Manager::TAB_STYLE ] );
		$this->add_control( 'title_color', [ 'label' => 'Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_sol_title' => 'color: {{VALUE}};' ] ] );
		$this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'title_typo', 'selector' => '{{WRAPPER}} .cora_sol_title' ] );
		$this->add_responsive_control( 'title_mb', [ 'label' => 'Bottom Spacing', 'type' => Controls_Manager::SLIDER, 'selectors' => [ '{{WRAPPER}} .cora_sol_title' => 'margin-bottom: {{SIZE}}px;' ] ] );
		
		// TRUNCATE TITLE
		$this->add_control( 'trunc_title', [ 'label' => 'Truncate Title', 'type' => Controls_Manager::SWITCHER, 'separator' => 'before' ] );
		$this->add_control( 'trunc_title_lines', [ 'label' => 'Max Lines', 'type' => Controls_Manager::NUMBER, 'default' => 2, 'selectors' => [ '{{WRAPPER}} .cora_sol_title' => 'display: -webkit-box; -webkit-line-clamp: {{VALUE}}; -webkit-box-orient: vertical; overflow: hidden;' ], 'condition' => [ 'trunc_title' => 'yes' ] ] );
		$this->end_controls_section();

		// --- TYPOGRAPHY: DESCRIPTION ---
		$this->start_controls_section( 'section_style_desc', [ 'label' => 'Description', 'tab' => Controls_Manager::TAB_STYLE ] );
		$this->add_control( 'desc_color', [ 'label' => 'Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_sol_desc' => 'color: {{VALUE}};' ] ] );
		$this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'desc_typo', 'selector' => '{{WRAPPER}} .cora_sol_desc' ] );
		$this->add_responsive_control( 'desc_mb', [ 'label' => 'Bottom Spacing', 'type' => Controls_Manager::SLIDER, 'selectors' => [ '{{WRAPPER}} .cora_sol_desc' => 'margin-bottom: {{SIZE}}px;' ] ] );
		
		// TRUNCATE DESCRIPTION
		$this->add_control( 'trunc_desc', [ 'label' => 'Truncate Desc', 'type' => Controls_Manager::SWITCHER, 'separator' => 'before' ] );
		$this->add_control( 'trunc_desc_lines', [ 'label' => 'Max Lines', 'type' => Controls_Manager::NUMBER, 'default' => 2, 'selectors' => [ '{{WRAPPER}} .cora_sol_desc' => 'display: -webkit-box; -webkit-line-clamp: {{VALUE}}; -webkit-box-orient: vertical; overflow: hidden;' ], 'condition' => [ 'trunc_desc' => 'yes' ] ] );
		$this->end_controls_section();

		// --- LIST STYLE ---
		$this->start_controls_section( 'section_style_list', [ 'label' => 'List Items', 'tab' => Controls_Manager::TAB_STYLE ] );
		
		$this->add_responsive_control( 'list_gap', [ 'label' => 'Item Spacing', 'type' => Controls_Manager::SLIDER, 'selectors' => [ '{{WRAPPER}} .cora_sol_list' => 'gap: {{SIZE}}px;' ] ] );
		$this->add_control( 'item_color', [ 'label' => 'Text Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_sol_link' => 'color: {{VALUE}};' ] ] );
		$this->add_control( 'item_hover_color', [ 'label' => 'Hover Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_sol_link:hover' => 'color: {{VALUE}};' ] ] );
		$this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'item_typo', 'selector' => '{{WRAPPER}} .cora_sol_link' ] );

		// TRUNCATE LIST
		$this->add_control( 'trunc_list', [ 'label' => 'Truncate Items', 'type' => Controls_Manager::SWITCHER, 'separator' => 'before' ] );
		$this->add_control( 'trunc_list_lines', [ 'label' => 'Max Lines', 'type' => Controls_Manager::NUMBER, 'default' => 1, 'selectors' => [ '{{WRAPPER}} .cora_sol_link span' => 'display: -webkit-box; -webkit-line-clamp: {{VALUE}}; -webkit-box-orient: vertical; overflow: hidden;' ], 'condition' => [ 'trunc_list' => 'yes' ] ] );

		$this->add_control( 'icon_color', [ 'label' => 'Icon Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_sol_icon' => 'color: {{VALUE}};' ], 'separator' => 'before' ] );
		$this->add_responsive_control( 'icon_size', [ 'label' => 'Icon Size', 'type' => Controls_Manager::SLIDER, 'selectors' => [ '{{WRAPPER}} .cora_sol_icon' => 'width: {{SIZE}}px; height: {{SIZE}}px;' ] ] );

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		?>
		<div class="cora_sol_card">
			<?php if ( ! empty( $settings['title'] ) ) : ?>
				<h3 class="cora_sol_title"><?php echo esc_html( $settings['title'] ); ?></h3>
			<?php endif; ?>

			<?php if ( ! empty( $settings['description'] ) ) : ?>
				<div class="cora_sol_desc"><?php echo esc_html( $settings['description'] ); ?></div>
			<?php endif; ?>

			<?php if ( $settings['list_items'] ) : ?>
				<div class="cora_sol_list">
					<?php foreach ( $settings['list_items'] as $item ) : 
						$link_url = $item['link']['url'];
						$tag = ! empty( $link_url ) ? 'a' : 'div';
						$href = ! empty( $link_url ) ? 'href="' . esc_url( $link_url ) . '"' : '';
					?>
					<<?php echo $tag . ' ' . $href; ?> class="cora_sol_link">
						<span><?php echo esc_html( $item['text'] ); ?></span>
						<div class="cora_sol_icon"><?php echo $settings['list_icon']; ?></div>
					</<?php echo $tag; ?>>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}
}