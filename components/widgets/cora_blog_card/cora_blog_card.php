<?php
namespace Cora_Builder\Components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Cora_Blog_Card extends Base_Widget {

	public function get_name() {
		return 'cora_blog_card';
	}

	public function get_title() {
		return 'Cora Blog Card';
	}

	public function get_icon() {
		return 'eicon-post';
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

		// IMAGE
		$this->add_control(
			'image',
			[
				'label' => 'Image',
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [ 'active' => true ],
				'default' => [ 'url' => \Elementor\Utils::get_placeholder_image_src() ],
			]
		);

		// BADGE
		$this->add_control( 'show_badge', [ 'label' => 'Show Category Badge', 'type' => Controls_Manager::SWITCHER, 'default' => 'yes' ] );
		$this->add_control( 
			'badge_text', 
			[ 
				'label' => 'Badge Text', 
				'type' => Controls_Manager::TEXT, 
				'dynamic' => [ 'active' => true ],
				'placeholder' => 'Auto (Category)',
				'condition' => [ 'show_badge' => 'yes' ],
			] 
		);

		// INFO
		$this->add_control( 'meta_info', [ 'label' => 'Meta (Date/Time)', 'type' => Controls_Manager::TEXT, 'dynamic' => [ 'active' => true ], 'default' => 'Sep 28, 2025 • 11 min read' ] );
		$this->add_control( 'title', [ 'label' => 'Title', 'type' => Controls_Manager::TEXTAREA, 'dynamic' => [ 'active' => true ], 'default' => 'Building Accessible Web Experiences', 'rows' => 2 ] );
		$this->add_control( 'excerpt', [ 'label' => 'Excerpt', 'type' => Controls_Manager::TEXTAREA, 'dynamic' => [ 'active' => true ], 'default' => 'Understand the importance of optimizing workflow processes to enhance efficiency...', 'rows' => 3 ] );

		// FOOTER
		$this->add_control( 'heading_footer', [ 'label' => 'Footer (Author)', 'type' => Controls_Manager::HEADING, 'separator' => 'before' ] );
		$this->add_control( 'author_image', [ 'label' => 'Author Avatar', 'type' => Controls_Manager::MEDIA, 'dynamic' => [ 'active' => true ] ] );
		$this->add_control( 'author_name', [ 'label' => 'Author Name', 'type' => Controls_Manager::TEXT, 'dynamic' => [ 'active' => true ], 'default' => 'Marcus Rivera' ] );
		$this->add_control( 'read_btn', [ 'label' => 'Button Text', 'type' => Controls_Manager::TEXT, 'default' => 'Read' ] );
		$this->add_control( 'link', [ 'label' => 'Post Link', 'type' => Controls_Manager::URL, 'dynamic' => [ 'active' => true ], 'placeholder' => 'https://...' ] );

		$this->end_controls_section();


		// ==========================
		// TAB: STYLE
		// ==========================

		// --- BOX STYLE ---
		$this->start_controls_section( 'section_style_box', [ 'label' => 'Card Box', 'tab' => Controls_Manager::TAB_STYLE ] );
		
		$this->add_responsive_control(
			'content_align',
			[
				'label' => 'Alignment',
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [ 'title' => 'Left', 'icon' => 'eicon-text-align-left' ],
					'center' => [ 'title' => 'Center', 'icon' => 'eicon-text-align-center' ],
					'right' => [ 'title' => 'Right', 'icon' => 'eicon-text-align-right' ],
				],
				'selectors' => [ 
					'{{WRAPPER}} .cora_blog_card_inner' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .cora_blog_footer' => 'justify-content: {{VALUE}};',
					'{{WRAPPER}} .cora_blog_meta' => 'justify-content: {{VALUE}};' 
				],
			]
		);

		$this->add_responsive_control( 'padding', [ 'label' => 'Padding', 'type' => Controls_Manager::DIMENSIONS, 'selectors' => [ '{{WRAPPER}} .cora_blog_card_inner' => 'padding: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;' ], 'default' => [ 'top' => 24, 'right' => 24, 'bottom' => 24, 'left' => 24, 'isLinked' => true ] ] );
		
		$this->add_group_control( Group_Control_Background::get_type(), [ 'name' => 'bg', 'selector' => '{{WRAPPER}} .cora_blog_card' ] );
		$this->add_group_control( Group_Control_Border::get_type(), [ 'name' => 'border', 'selector' => '{{WRAPPER}} .cora_blog_card' ] );
		
		$this->add_responsive_control( 'radius', [ 'label' => 'Border Radius', 'type' => Controls_Manager::DIMENSIONS, 'selectors' => [ '{{WRAPPER}} .cora_blog_card' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;', '{{WRAPPER}} .cora_blog_img_wrap' => 'border-radius: {{TOP}}px {{RIGHT}}px 0 0;' ] ] );
		
		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [ 'name' => 'shadow', 'selector' => '{{WRAPPER}} .cora_blog_card' ] );
		
		$this->end_controls_section();

		// --- IMAGE STYLE ---
		$this->start_controls_section( 'section_style_img', [ 'label' => 'Image & Badge', 'tab' => Controls_Manager::TAB_STYLE ] );
		
		$this->add_responsive_control( 'img_height', [ 'label' => 'Height', 'type' => Controls_Manager::SLIDER, 'range' => [ 'px' => [ 'min' => 100, 'max' => 500 ] ], 'default' => [ 'size' => 240, 'unit' => 'px' ], 'selectors' => [ '{{WRAPPER}} .cora_blog_img' => 'height: {{SIZE}}px;' ] ] );
		
		$this->add_control( 'badge_heading', [ 'label' => 'Badge', 'type' => Controls_Manager::HEADING, 'separator' => 'before' ] );
		$this->add_control( 'badge_bg', [ 'label' => 'Background', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_blog_badge' => 'background-color: {{VALUE}};' ] ] );
		$this->add_control( 'badge_color', [ 'label' => 'Text Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_blog_badge' => 'color: {{VALUE}};' ] ] );
		$this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'badge_typo', 'selector' => '{{WRAPPER}} .cora_blog_badge' ] );
		
		$this->end_controls_section();

		// --- TYPOGRAPHY ---
		$this->start_controls_section( 'section_style_typo', [ 'label' => 'Typography', 'tab' => Controls_Manager::TAB_STYLE ] );
		
		// Meta
		$this->add_control( 'heading_meta', [ 'label' => 'Meta', 'type' => Controls_Manager::HEADING ] );
		$this->add_control( 'meta_color', [ 'label' => 'Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_blog_meta' => 'color: {{VALUE}};' ] ] );
		$this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'meta_typo', 'selector' => '{{WRAPPER}} .cora_blog_meta' ] );
		$this->add_responsive_control( 'meta_spacing', [ 'label' => 'Bottom Spacing', 'type' => Controls_Manager::SLIDER, 'selectors' => [ '{{WRAPPER}} .cora_blog_meta' => 'margin-bottom: {{SIZE}}px;' ] ] );

		// Title
		$this->add_control( 'heading_title', [ 'label' => 'Title', 'type' => Controls_Manager::HEADING, 'separator' => 'before' ] );
		$this->add_control( 'title_color', [ 'label' => 'Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_blog_title' => 'color: {{VALUE}};' ] ] );
		$this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'title_typo', 'selector' => '{{WRAPPER}} .cora_blog_title' ] );
		$this->add_responsive_control( 'title_spacing', [ 'label' => 'Bottom Spacing', 'type' => Controls_Manager::SLIDER, 'selectors' => [ '{{WRAPPER}} .cora_blog_title' => 'margin-bottom: {{SIZE}}px;' ] ] );

		// Excerpt
		$this->add_control( 'heading_excerpt', [ 'label' => 'Excerpt', 'type' => Controls_Manager::HEADING, 'separator' => 'before' ] );
		$this->add_control( 'excerpt_color', [ 'label' => 'Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_blog_excerpt' => 'color: {{VALUE}};' ] ] );
		$this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'excerpt_typo', 'selector' => '{{WRAPPER}} .cora_blog_excerpt' ] );
		$this->add_responsive_control( 'excerpt_spacing', [ 'label' => 'Bottom Spacing', 'type' => Controls_Manager::SLIDER, 'selectors' => [ '{{WRAPPER}} .cora_blog_excerpt' => 'margin-bottom: {{SIZE}}px;' ] ] );
		
		$this->end_controls_section();

		// --- FOOTER STYLE ---
		$this->start_controls_section( 'section_style_footer', [ 'label' => 'Footer & Divider', 'tab' => Controls_Manager::TAB_STYLE ] );
		
		$this->add_control( 'divider_color', [ 'label' => 'Divider Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_blog_divider' => 'background-color: {{VALUE}};' ] ] );
		$this->add_responsive_control( 'divider_spacing', [ 'label' => 'Divider Spacing', 'type' => Controls_Manager::SLIDER, 'selectors' => [ '{{WRAPPER}} .cora_blog_divider' => 'margin-bottom: {{SIZE}}px;' ] ] );
		
		$this->add_control( 'author_color', [ 'label' => 'Author Name Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_author_name' => 'color: {{VALUE}};' ] ] );
		$this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'author_typo', 'selector' => '{{WRAPPER}} .cora_author_name' ] );

		$this->add_control( 'btn_color', [ 'label' => 'Button Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_read_btn' => 'color: {{VALUE}};' ] ] );
		$this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'btn_typo', 'selector' => '{{WRAPPER}} .cora_read_btn' ] );
		
		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		
		// Dynamic Data Handling (Fallback to manual if not set)
		$img_url = $settings['image']['url'];
		$title = $settings['title'];
		$link = $settings['link']['url'];
		$badge = $settings['badge_text'];
		$author_img = $settings['author_image']['url'];
		$author_name = $settings['author_name'];

		// Auto-fetch Category if badge is empty and we are in a loop
		if ( empty( $badge ) && get_the_ID() ) {
			$cats = get_the_category();
			if ( ! empty( $cats ) ) $badge = $cats[0]->name;
		}

		// Auto-fetch Author Avatar if empty
		if ( empty( $author_img ) && get_the_ID() ) {
			$author_img = get_avatar_url( get_the_author_meta( 'ID' ) );
		}

		?>
		<article class="cora_blog_card">
			<a href="<?php echo esc_url( $link ); ?>" class="cora_blog_img_wrap">
				<div class="cora_blog_img" style="background-image: url('<?php echo esc_url( $img_url ); ?>');"></div>
				<?php if ( 'yes' === $settings['show_badge'] ) : ?>
					<span class="cora_blog_badge"><?php echo esc_html( $badge ); ?></span>
				<?php endif; ?>
			</a>

			<div class="cora_blog_card_inner">
				<div class="cora_blog_meta">
					<?php echo esc_html( $settings['meta_info'] ); ?>
				</div>

				<h3 class="cora_blog_title">
					<a href="<?php echo esc_url( $link ); ?>"><?php echo esc_html( $title ); ?></a>
				</h3>

				<div class="cora_blog_excerpt">
					<?php echo esc_html( $settings['excerpt'] ); ?>
				</div>

				<div class="cora_blog_divider"></div>

				<div class="cora_blog_footer">
					<div class="cora_blog_author">
						<?php if ( $author_img ) : ?>
							<img src="<?php echo esc_url( $author_img ); ?>" alt="<?php echo esc_attr( $author_name ); ?>" class="cora_author_avatar">
						<?php else: ?>
							<span class="cora_author_initial"><?php echo substr($author_name, 0, 1); ?></span>
						<?php endif; ?>
						<span class="cora_author_name"><?php echo esc_html( $author_name ); ?></span>
					</div>

					<a href="<?php echo esc_url( $link ); ?>" class="cora_read_btn">
						<?php echo esc_html( $settings['read_btn'] ); ?> 
						<i class="eicon-arrow-right"></i>
					</a>
				</div>
			</div>
		</article>
		<?php
	}
}