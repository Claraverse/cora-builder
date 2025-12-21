<?php
namespace Cora_Builder\Components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Post_Grid extends Base_Widget {

	public function get_name() {
		return 'cora_post_grid';
	}

	public function get_title() {
		return 'Cora Post Grid';
	}

	public function get_icon() {
		return 'eicon-gallery-grid';
	}

	public function get_categories() {
		return [ 'cora_widgets' ];
	}

	protected function get_available_categories() {
		$categories = get_categories( [ 'hide_empty' => false ] );
		$cat_array = [];
		if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
			foreach ( $categories as $cat ) {
				$cat_array[ $cat->term_id ] = $cat->name;
			}
		}
		return $cat_array;
	}

	protected function register_controls() {

		// --- TAB: CONTENT ---
		$this->start_controls_section(
			'section_layout',
			[ 'label' => 'Layout Settings', 'tab' => Controls_Manager::TAB_CONTENT ]
		);

		$this->add_control(
			'skin_type',
			[
				'label' => 'Skin',
				'type' => Controls_Manager::SELECT,
				'default' => 'card',
				'options' => [
					'card' => 'Modern Overlay (Grid)',
					'list' => 'Side List (Horizontal)',
				],
			]
		);

		$this->add_responsive_control(
			'columns',
			[
				'label' => 'Columns',
				'type' => Controls_Manager::SELECT,
				'default' => '3',
				'tablet_default' => '2',
				'mobile_default' => '1',
				'options' => [ '1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5' ],
				'selectors' => [ '{{WRAPPER}} .cora_post_grid_wrapper' => 'grid-template-columns: repeat({{VALUE}}, 1fr);' ],
				'condition' => [ 'skin_type' => 'card' ],
			]
		);

		$this->add_control( 'posts_per_page', [ 'label' => 'Posts Per Page', 'type' => Controls_Manager::NUMBER, 'default' => 6 ] );
		$this->end_controls_section();

		$this->start_controls_section(
			'section_query',
			[ 'label' => 'Query Source', 'tab' => Controls_Manager::TAB_CONTENT ]
		);

		$this->add_control(
			'category_filter',
			[
				'label' => 'Filter by Category',
				'type' => Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple' => true,
				'options' => $this->get_available_categories(),
			]
		);

		$this->add_control( 'exclude_ids', [ 'label' => 'Exclude IDs', 'type' => Controls_Manager::TEXT ] );
		$this->add_control(
			'orderby',
			[
				'label' => 'Order By',
				'type' => Controls_Manager::SELECT,
				'default' => 'date',
				'options' => [ 'date' => 'Date', 'title' => 'Title', 'rand' => 'Random' ],
			]
		);
		$this->add_control( 'order', [ 'label' => 'Order', 'type' => Controls_Manager::SELECT, 'default' => 'DESC', 'options' => [ 'ASC' => 'ASC', 'DESC' => 'DESC' ] ] );
		$this->end_controls_section();

		$this->start_controls_section(
			'section_elements',
			[ 'label' => 'Content Elements', 'tab' => Controls_Manager::TAB_CONTENT ]
		);

		$this->add_control( 'show_category', [ 'label' => 'Show Category', 'type' => Controls_Manager::SWITCHER, 'default' => 'yes' ] );
		$this->add_control( 'show_meta', [ 'label' => 'Show Meta', 'type' => Controls_Manager::SWITCHER, 'default' => 'yes' ] );
		$this->add_control(
			'meta_data',
			[
				'label' => 'Meta Data',
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'default' => [ 'date', 'read_time' ],
				'options' => [ 'author' => 'Author', 'date' => 'Date', 'read_time' => 'Read Time' ],
				'condition' => [ 'show_meta' => 'yes' ],
			]
		);
		$this->add_control( 'show_excerpt', [ 'label' => 'Show Excerpt', 'type' => Controls_Manager::SWITCHER, 'default' => 'yes' ] );
		$this->add_control( 'excerpt_length', [ 'label' => 'Excerpt Length', 'type' => Controls_Manager::NUMBER, 'default' => 15, 'condition' => [ 'show_excerpt' => 'yes' ] ] );
		$this->end_controls_section();


		// --- TAB: STYLE ---
		$this->start_controls_section(
			'section_style_container',
			[ 'label' => 'Container & Gap', 'tab' => Controls_Manager::TAB_STYLE ]
		);

		$this->add_responsive_control(
			'gap_column',
			[
				'label' => 'Column Gap',
				'type' => Controls_Manager::SLIDER,
				'default' => [ 'size' => 24 ],
				'selectors' => [
					'{{WRAPPER}} .cora_post_grid_wrapper' => 'column-gap: {{SIZE}}px;',
					'{{WRAPPER}} .cora_skin_list .cora_grid_item' => 'gap: {{SIZE}}px;',
				],
			]
		);

		$this->add_responsive_control(
			'gap_row',
			[
				'label' => 'Row Gap',
				'type' => Controls_Manager::SLIDER,
				'default' => [ 'size' => 24 ],
				'selectors' => [ '{{WRAPPER}} .cora_post_grid_wrapper' => 'row-gap: {{SIZE}}px;' ],
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_card',
			[ 'label' => 'Card Box', 'tab' => Controls_Manager::TAB_STYLE ]
		);

		$this->add_responsive_control(
			'card_padding',
			[
				'label' => 'Content Padding',
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'default' => [ 'top' => 24, 'right' => 24, 'bottom' => 24, 'left' => 24, 'unit' => 'px', 'isLinked' => true ],
				'selectors' => [ '{{WRAPPER}} .cora_card_content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
			]
		);
		
		$this->add_control(
			'card_radius',
			[
				'label' => 'Border Radius',
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [ 'top' => 12, 'right' => 12, 'bottom' => 12, 'left' => 12, 'unit' => 'px', 'isLinked' => true ],
				'selectors' => [ '{{WRAPPER}} .cora_grid_item' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;' ],
			]
		);

		$this->add_group_control( Group_Control_Background::get_type(), [ 'name' => 'card_background', 'selector' => '{{WRAPPER}} .cora_grid_item' ] );
		$this->add_group_control( Group_Control_Border::get_type(), [ 'name' => 'card_border', 'selector' => '{{WRAPPER}} .cora_grid_item' ] );
		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [ 'name' => 'card_shadow', 'selector' => '{{WRAPPER}} .cora_grid_item' ] );
		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_image',
			[ 'label' => 'Image & Overlay', 'tab' => Controls_Manager::TAB_STYLE ]
		);

		$this->add_responsive_control(
			'image_height',
			[
				'label' => 'Height',
				'type' => Controls_Manager::SLIDER,
				'range' => [ 'px' => [ 'min' => 100, 'max' => 600 ] ],
				'default' => [ 'size' => 300, 'unit' => 'px' ],
				'selectors' => [ 
					'{{WRAPPER}} .cora_card_img_wrap' => 'height: {{SIZE}}px;',
					'{{WRAPPER}} .cora_skin_list .cora_card_img_wrap' => 'width: {{SIZE}}px; height: {{SIZE}}px;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'overlay_background',
				'label' => 'Overlay Color',
				'types' => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .cora_card_overlay',
				'condition' => [ 'skin_type' => 'card' ],
			]
		);
		$this->add_control( 'hover_animation', [ 'label' => 'Hover Zoom', 'type' => Controls_Manager::SWITCHER, 'default' => 'yes', 'return_value' => 'yes' ] );
		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_typo',
			[ 'label' => 'Typography', 'tab' => Controls_Manager::TAB_STYLE ]
		);

		// TITLE CONTROLS
		$this->add_control( 'heading_title', [ 'label' => 'Title', 'type' => Controls_Manager::HEADING ] );
		$this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'title_typo', 'selector' => '{{WRAPPER}} .cora_card_title a' ] );
		
		// Two controls for color to prevent conflict
		$this->add_control( 'title_color_grid', [ 'label' => 'Color (Grid)', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_skin_card .cora_card_title a' => 'color: {{VALUE}};' ], 'condition' => [ 'skin_type' => 'card' ] ] );
		$this->add_control( 'title_color_list', [ 'label' => 'Color (List)', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_skin_list .cora_card_title a' => 'color: {{VALUE}};' ], 'condition' => [ 'skin_type' => 'list' ] ] );

		// META CONTROLS
		$this->add_control( 'heading_meta', [ 'label' => 'Meta', 'type' => Controls_Manager::HEADING, 'separator' => 'before' ] );
		$this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'meta_typo', 'selector' => '{{WRAPPER}} .cora_card_meta' ] );
		
		$this->add_control( 'meta_color_grid', [ 'label' => 'Color (Grid)', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_skin_card .cora_card_meta' => 'color: {{VALUE}};' ], 'condition' => [ 'skin_type' => 'card' ] ] );
		$this->add_control( 'meta_color_list', [ 'label' => 'Color (List)', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_skin_list .cora_card_meta' => 'color: {{VALUE}};' ], 'condition' => [ 'skin_type' => 'list' ] ] );

		// EXCERPT CONTROLS
		$this->add_control( 'heading_excerpt', [ 'label' => 'Excerpt', 'type' => Controls_Manager::HEADING, 'separator' => 'before' ] );
		$this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'excerpt_typo', 'selector' => '{{WRAPPER}} .cora_card_excerpt' ] );
		
		$this->add_control( 'excerpt_color_grid', [ 'label' => 'Color (Grid)', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_skin_card .cora_card_excerpt' => 'color: {{VALUE}};' ], 'condition' => [ 'skin_type' => 'card' ] ] );
		$this->add_control( 'excerpt_color_list', [ 'label' => 'Color (List)', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_skin_list .cora_card_excerpt' => 'color: {{VALUE}};' ], 'condition' => [ 'skin_type' => 'list' ] ] );

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$args = [
			'post_type' => 'post',
			'posts_per_page' => $settings['posts_per_page'],
			'ignore_sticky_posts' => 1,
			'orderby' => $settings['orderby'],
			'order' => $settings['order'],
		];

		if ( ! empty( $settings['category_filter'] ) ) { $args['cat'] = implode( ',', $settings['category_filter'] ); }
		if ( ! empty( $settings['exclude_ids'] ) ) { $args['post__not_in'] = array_map( 'intval', explode( ',', $settings['exclude_ids'] ) ); }

		$query = new \WP_Query( $args );
		$wrapper_classes = 'cora_post_grid_wrapper cora_skin_' . $settings['skin_type'];
		if ( 'yes' === $settings['hover_animation'] ) { $wrapper_classes .= ' cora-anim-zoom'; }

		if ( $query->have_posts() ) {
			echo '<div class="' . esc_attr( $wrapper_classes ) . '">';
			while ( $query->have_posts() ) {
				$query->the_post();
				$thumb_url = get_the_post_thumbnail_url( get_the_ID(), 'large' );
				$bg_style = $thumb_url ? "background-image: url('{$thumb_url}');" : "background-color: #333;";
				
				$content = get_the_content();
				$read_time = !empty($content) ? ceil( str_word_count( strip_tags( $content ) ) / 200 ) . ' min read' : '1 min read';

				$cats = get_the_category();
				$cat_name = ( ! empty( $cats ) && ! is_wp_error( $cats ) ) ? $cats[0]->name : '';
				
				$meta_keys = is_array($settings['meta_data']) ? $settings['meta_data'] : [];
				?>
				<article class="cora_grid_item">
					<a href="<?php the_permalink(); ?>" class="cora_card_img_wrap">
						<div class="cora_card_bg" style="<?php echo esc_attr( $bg_style ); ?>"></div>
						<div class="cora_card_overlay"></div>
						<?php if ( 'yes' === $settings['show_category'] && ! empty( $cat_name ) ) : ?>
							<span class="cora_card_cat"><?php echo esc_html( $cat_name ); ?></span>
						<?php endif; ?>
					</a>

					<div class="cora_card_content">
						<?php if ( 'yes' === $settings['show_meta'] ) : ?>
						<div class="cora_card_meta">
							<?php if ( in_array( 'author', $meta_keys ) ) : ?><span><?php the_author(); ?></span><?php endif; ?>
							<?php if ( in_array( 'date', $meta_keys ) ) : ?><span><?php echo get_the_date( 'M d, Y' ); ?></span><?php endif; ?>
							<?php if ( in_array( 'read_time', $meta_keys ) ) : ?><span><?php echo esc_html( $read_time ); ?></span><?php endif; ?>
						</div>
						<?php endif; ?>

						<h3 class="cora_card_title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

						<?php if ( 'yes' === $settings['show_excerpt'] ) : ?>
							<div class="cora_card_excerpt"><?php echo wp_trim_words( get_the_excerpt(), $settings['excerpt_length'] ); ?></div>
						<?php endif; ?>
					</div>
				</article>
				<?php
			}
			echo '</div>';
			wp_reset_postdata();
		} else {
			if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) { echo '<div style="padding:20px;text-align:center;background:#eee;">No posts found.</div>'; }
		}
	}
}