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

class Cora_Solution_Cluster extends Base_Widget {

	public function get_name() {
		return 'cora_solution_cluster';
	}

	public function get_title() {
		return 'Cora Solution Cluster';
	}

	public function get_icon() {
		return 'eicon-post-list';
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
			[ 'label' => 'Header Content', 'tab' => Controls_Manager::TAB_CONTENT ]
		);

		// ICON
		$this->add_control(
			'icon_type',
			[
				'label' => 'Icon Source',
				'type' => Controls_Manager::SELECT,
				'default' => 'icon',
				'options' => [ 'icon' => 'Icon Library', 'svg' => 'SVG Code' ],
			]
		);

		$this->add_control(
			'selected_icon',
			[
				'label' => 'Icon',
				'type' => Controls_Manager::ICONS,
				'default' => [ 'value' => 'fas fa-chart-line', 'library' => 'fa-solid' ],
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

		$this->add_control( 'title', [ 'label' => 'Title', 'type' => Controls_Manager::TEXT, 'default' => 'Struggling with User Retention', 'dynamic' => [ 'active' => true ], 'label_block' => true ] );
		$this->add_control( 'description', [ 'label' => 'Description', 'type' => Controls_Manager::TEXTAREA, 'default' => 'Learn proven strategies to keep users engaged and reduce churn rates', 'dynamic' => [ 'active' => true ], 'rows' => 3 ] );

		$this->end_controls_section();


		// --- LIST SECTION ---
		$this->start_controls_section(
			'section_list',
			[ 'label' => 'Related Articles', 'tab' => Controls_Manager::TAB_CONTENT ]
		);

		$this->add_control( 'list_heading', [ 'label' => 'Section Label', 'type' => Controls_Manager::TEXT, 'default' => 'Related Articles' ] );
		$this->add_control( 'show_count', [ 'label' => 'Show Count Badge', 'type' => Controls_Manager::SWITCHER, 'default' => 'yes' ] );

		$repeater = new Repeater();
		
		$repeater->add_control( 'image', [ 'label' => 'Thumbnail', 'type' => Controls_Manager::MEDIA, 'default' => [ 'url' => \Elementor\Utils::get_placeholder_image_src() ] ] );
		$repeater->add_control( 'item_title', [ 'label' => 'Title', 'type' => Controls_Manager::TEXTAREA, 'rows' => 2, 'default' => '7 Data-Driven Strategies to Boost Retention' ] );
		$repeater->add_control( 'item_meta', [ 'label' => 'Meta (Time)', 'type' => Controls_Manager::TEXT, 'default' => '9 min read' ] );
		$repeater->add_control( 'item_author', [ 'label' => 'Author', 'type' => Controls_Manager::TEXT, 'default' => 'Sarah Chen' ] );
		$repeater->add_control( 'item_link', [ 'label' => 'Link', 'type' => Controls_Manager::URL, 'placeholder' => 'https://...' ] );

		$this->add_control(
			'items',
			[
				'label' => 'Articles',
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[ 'item_title' => '7 Data-Driven Strategies to Boost Retention', 'item_meta' => '9 min read' ],
					[ 'item_title' => 'The Engagement Metrics That Actually Matter', 'item_meta' => '6 min read' ],
					[ 'item_title' => 'Building Habits: The Hook Model', 'item_meta' => '12 min read' ],
				],
				'title_field' => '{{{ item_title }}}',
			]
		);

		$this->end_controls_section();


		// --- FOOTER SECTION ---
		$this->start_controls_section(
			'section_footer',
			[ 'label' => 'Footer Button', 'tab' => Controls_Manager::TAB_CONTENT ]
		);
		
		$this->add_control( 'btn_text', [ 'label' => 'Button Text', 'type' => Controls_Manager::TEXT, 'default' => 'Explore All Solutions', 'dynamic' => [ 'active' => true ] ] );
		$this->add_control( 'btn_link', [ 'label' => 'Button Link', 'type' => Controls_Manager::URL, 'placeholder' => 'https://...', 'dynamic' => [ 'active' => true ] ] );
		
		$this->end_controls_section();


		// ==========================
		// TAB: STYLE
		// ==========================

		// --- CARD BOX ---
		$this->start_controls_section( 'section_style_box', [ 'label' => 'Card Container', 'tab' => Controls_Manager::TAB_STYLE ] );
		
		$this->add_control(
			'tint_color',
			[
				'label' => 'Header Tint Color',
				'type' => Controls_Manager::COLOR,
				'default' => '#E0F2FE', // Light Blue Default
				'selectors' => [ '{{WRAPPER}} .cora_cluster_card' => 'background: linear-gradient(180deg, {{VALUE}} 0%, #FFFFFF 35%);' ],
			]
		);

		$this->add_responsive_control( 'padding', [ 'label' => 'Padding', 'type' => Controls_Manager::DIMENSIONS, 'selectors' => [ '{{WRAPPER}} .cora_cluster_card' => 'padding: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;' ], 'default' => [ 'top' => 32, 'right' => 24, 'bottom' => 32, 'left' => 24, 'isLinked' => false ] ] );
		$this->add_group_control( Group_Control_Border::get_type(), [ 'name' => 'border', 'selector' => '{{WRAPPER}} .cora_cluster_card' ] );
		$this->add_responsive_control( 'radius', [ 'label' => 'Border Radius', 'type' => Controls_Manager::DIMENSIONS, 'selectors' => [ '{{WRAPPER}} .cora_cluster_card' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;' ], 'default' => [ 'top' => 16, 'right' => 16, 'bottom' => 16, 'left' => 16, 'isLinked' => true ] ] );
		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [ 'name' => 'shadow', 'selector' => '{{WRAPPER}} .cora_cluster_card' ] );
		
		$this->end_controls_section();


		// --- HEADER STYLE ---
		$this->start_controls_section( 'section_style_header', [ 'label' => 'Header & Icon', 'tab' => Controls_Manager::TAB_STYLE ] );
		
		// Icon Box
		$this->add_control( 'icon_bg_color', [ 'label' => 'Icon Bg Color', 'type' => Controls_Manager::COLOR, 'default' => '#FFFFFF', 'selectors' => [ '{{WRAPPER}} .cora_cluster_icon' => 'background-color: {{VALUE}};' ] ] );
		$this->add_control( 'icon_color', [ 'label' => 'Icon Color', 'type' => Controls_Manager::COLOR, 'default' => '#007bff', 'selectors' => [ '{{WRAPPER}} .cora_cluster_icon' => 'color: {{VALUE}}; fill: {{VALUE}};' ] ] );
		$this->add_responsive_control( 'icon_size', [ 'label' => 'Icon Size', 'type' => Controls_Manager::SLIDER, 'selectors' => [ '{{WRAPPER}} .cora_cluster_icon i' => 'font-size: {{SIZE}}px;', '{{WRAPPER}} .cora_cluster_icon svg' => 'width: {{SIZE}}px; height: {{SIZE}}px;' ] ] );

		// Typography
		$this->add_control( 'heading_title', [ 'label' => 'Title', 'type' => Controls_Manager::HEADING, 'separator' => 'before' ] );
		$this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'title_typo', 'selector' => '{{WRAPPER}} .cora_cluster_title' ] );
		$this->add_responsive_control( 'title_mb', [ 'label' => 'Bottom Spacing', 'type' => Controls_Manager::SLIDER, 'selectors' => [ '{{WRAPPER}} .cora_cluster_title' => 'margin-bottom: {{SIZE}}px;' ] ] );
		$this->add_responsive_control( 'trunc_title_lines', [ 'label' => 'Truncate Title', 'type' => Controls_Manager::NUMBER, 'min' => 1, 'selectors' => [ '{{WRAPPER}} .cora_cluster_title' => 'display: -webkit-box; -webkit-line-clamp: {{VALUE}}; -webkit-box-orient: vertical; overflow: hidden;' ] ] );

		$this->add_control( 'heading_desc', [ 'label' => 'Description', 'type' => Controls_Manager::HEADING, 'separator' => 'before' ] );
		$this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'desc_typo', 'selector' => '{{WRAPPER}} .cora_cluster_desc' ] );
		$this->add_responsive_control( 'trunc_desc_lines', [ 'label' => 'Truncate Desc', 'type' => Controls_Manager::NUMBER, 'min' => 1, 'selectors' => [ '{{WRAPPER}} .cora_cluster_desc' => 'display: -webkit-box; -webkit-line-clamp: {{VALUE}}; -webkit-box-orient: vertical; overflow: hidden;' ] ] );

		$this->end_controls_section();


		// --- LIST STYLE ---
		$this->start_controls_section( 'section_style_list', [ 'label' => 'Article List', 'tab' => Controls_Manager::TAB_STYLE ] );
		
		$this->add_control( 'subhead_color', [ 'label' => 'Subheader Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_cluster_list_head' => 'color: {{VALUE}};' ] ] );
		$this->add_responsive_control( 'list_gap', [ 'label' => 'Item Spacing', 'type' => Controls_Manager::SLIDER, 'selectors' => [ '{{WRAPPER}} .cora_cluster_items' => 'gap: {{SIZE}}px;' ] ] );

		// Item Styling
		$this->add_control( 'heading_items', [ 'label' => 'Item Design', 'type' => Controls_Manager::HEADING, 'separator' => 'before' ] );
		$this->add_control( 'item_bg', [ 'label' => 'Item Background', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_cluster_item' => 'background-color: {{VALUE}};' ] ] );
		$this->add_responsive_control( 'item_padding', [ 'label' => 'Item Padding', 'type' => Controls_Manager::DIMENSIONS, 'selectors' => [ '{{WRAPPER}} .cora_cluster_item' => 'padding: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;' ], 'default' => [ 'top' => 12, 'right' => 12, 'bottom' => 12, 'left' => 12, 'isLinked' => true ] ] );
		
		// THUMBNAIL RESPONSIVE
		$this->add_responsive_control( 
			'thumb_width', 
			[ 
				'label' => 'Thumbnail Width', 
				'type' => Controls_Manager::SLIDER, 
				'default' => [ 'size' => 64 ],
				'selectors' => [ '{{WRAPPER}} .cora_cluster_thumb' => 'width: {{SIZE}}px; height: {{SIZE}}px;' ] 
			] 
		);

		$this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'item_title_typo', 'selector' => '{{WRAPPER}} .cora_cluster_item_title' ] );
		$this->add_responsive_control( 'trunc_item_lines', [ 'label' => 'Truncate Item Title', 'type' => Controls_Manager::NUMBER, 'min' => 1, 'selectors' => [ '{{WRAPPER}} .cora_cluster_item_title' => 'display: -webkit-box; -webkit-line-clamp: {{VALUE}}; -webkit-box-orient: vertical; overflow: hidden;' ] ] );

		$this->end_controls_section();


		// --- FOOTER BUTTON ---
		$this->start_controls_section( 'section_style_btn', [ 'label' => 'Button', 'tab' => Controls_Manager::TAB_STYLE ] );
		
		$this->add_control( 'btn_bg', [ 'label' => 'Background', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_cluster_btn' => 'background-color: {{VALUE}};' ], 'default' => '#101828' ] );
		$this->add_control( 'btn_text_color', [ 'label' => 'Text Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_cluster_btn' => 'color: {{VALUE}};' ], 'default' => '#FFFFFF' ] );
		$this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'btn_typo', 'selector' => '{{WRAPPER}} .cora_cluster_btn' ] );
		$this->add_responsive_control( 'btn_radius', [ 'label' => 'Radius', 'type' => Controls_Manager::SLIDER, 'selectors' => [ '{{WRAPPER}} .cora_cluster_btn' => 'border-radius: {{SIZE}}px;' ] ] );
		
		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		
		$btn_tag = 'div'; $btn_href = '';
		if ( ! empty( $settings['btn_link']['url'] ) ) {
			$btn_tag = 'a';
			$btn_href = 'href="' . esc_url( $settings['btn_link']['url'] ) . '"';
		}

		?>
		<div class="cora_cluster_card">
			
			<div class="cora_cluster_header">
				<div class="cora_cluster_icon">
					<?php if ( 'svg' === $settings['icon_type'] ) : ?>
						<?php echo $settings['icon_svg']; ?>
					<?php else : ?>
						<?php \Elementor\Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true' ] ); ?>
					<?php endif; ?>
				</div>
				<h3 class="cora_cluster_title"><?php echo esc_html( $settings['title'] ); ?></h3>
				<div class="cora_cluster_desc"><?php echo esc_html( $settings['description'] ); ?></div>
			</div>

			<div class="cora_cluster_divider"></div>

			<div class="cora_cluster_list_head">
				<span class="cora_list_label"><?php echo esc_html( $settings['list_heading'] ); ?></span>
				<?php if ( 'yes' === $settings['show_count'] ) : ?>
					<span class="cora_list_badge"><?php echo count( $settings['items'] ); ?> total</span>
				<?php endif; ?>
			</div>

			<div class="cora_cluster_items">
				<?php foreach ( $settings['items'] as $item ) : 
					$item_tag = ! empty( $item['item_link']['url'] ) ? 'a' : 'div';
					$item_href = ! empty( $item['item_link']['url'] ) ? 'href="' . esc_url( $item['item_link']['url'] ) . '"' : '';
				?>
				<<?php echo $item_tag . ' ' . $item_href; ?> class="cora_cluster_item">
					<div class="cora_cluster_thumb" style="background-image: url('<?php echo esc_url( $item['image']['url'] ); ?>');"></div>
					<div class="cora_cluster_content">
						<h4 class="cora_cluster_item_title"><?php echo esc_html( $item['item_title'] ); ?></h4>
						<div class="cora_cluster_meta">
							<div class="cora_meta_item"><i class="eicon-clock-o"></i> <span><?php echo esc_html( $item['item_meta'] ); ?></span></div>
							<div class="cora_dot">•</div>
							<div class="cora_meta_item"><i class="eicon-person"></i> <span><?php echo esc_html( $item['item_author'] ); ?></span></div>
						</div>
					</div>
				</<?php echo $item_tag; ?>>
				<?php endforeach; ?>
			</div>

			<?php if ( ! empty( $settings['btn_text'] ) ) : ?>
			<div class="cora_cluster_footer">
				<<?php echo $btn_tag . ' ' . $btn_href; ?> class="cora_cluster_btn">
					<?php echo esc_html( $settings['btn_text'] ); ?>
					<i class="eicon-arrow-right"></i>
				</<?php echo $btn_tag; ?>>
			</div>
			<?php endif; ?>

		</div>
		<?php
	}
}