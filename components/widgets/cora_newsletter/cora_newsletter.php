<?php
namespace Cora_Builder\Components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Cora_Newsletter extends Base_Widget {

	public function get_name() {
		return 'cora_newsletter';
	}

	public function get_title() {
		return 'Cora Newsletter Section';
	}

	public function get_icon() {
		return 'eicon-mail';
	}

	public function get_categories() {
		return [ 'cora_cat_hero' ];
	}

	// Helper to get Elementor Templates list
	protected function get_elementor_templates() {
		$templates = \Elementor\Plugin::$instance->templates_manager->get_source( 'local' )->get_items();
		$options = [ '0' => '— Select Template —' ];
		if ( ! empty( $templates ) ) {
			foreach ( $templates as $template ) {
				$options[ $template['template_id'] ] = $template['title'];
			}
		}
		return $options;
	}

	protected function register_controls() {

		// ==========================
		// TAB: CONTENT (LEFT)
		// ==========================
		$this->start_controls_section(
			'section_content_left',
			[ 'label' => 'Left Column Content', 'tab' => Controls_Manager::TAB_CONTENT ]
		);

		$this->add_control( 'pill_text', [ 'label' => 'Pill Text', 'type' => Controls_Manager::TEXT, 'default' => 'Newsletter', 'dynamic' => [ 'active' => true ] ] );
		$this->add_control( 'title', [ 'label' => 'Title', 'type' => Controls_Manager::TEXTAREA, 'default' => 'Stay Ahead of the Curve', 'rows' => 2, 'dynamic' => [ 'active' => true ] ] );
		$this->add_control( 'description', [ 'label' => 'Description', 'type' => Controls_Manager::TEXTAREA, 'default' => 'Join 10,000+ professionals receiving our weekly insights on building better digital products.', 'rows' => 3, 'dynamic' => [ 'active' => true ] ] );

		// Benefits List
		$repeater = new Repeater();
		$repeater->add_control( 'text', [ 'label' => 'Benefit Text', 'type' => Controls_Manager::TEXT, 'default' => 'Weekly curated content', 'label_block' => true ] );
		
		$this->add_control(
			'benefits',
			[
				'label' => 'Benefits List',
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[ 'text' => 'Weekly curated content on Business, Tech & AI' ],
					[ 'text' => 'Exclusive insights from industry experts' ],
					[ 'text' => 'Early access to new resources and tools' ],
					[ 'text' => 'No spam, unsubscribe anytime' ],
				],
			]
		);

		$this->end_controls_section();


		// ==========================
		// TAB: CONTENT (RIGHT)
		// ==========================
		$this->start_controls_section(
			'section_content_right',
			[ 'label' => 'Right Column (Form)', 'tab' => Controls_Manager::TAB_CONTENT ]
		);

		$this->add_control(
			'form_template_id',
			[
				'label' => 'Select MetForm Template',
				'type' => Controls_Manager::SELECT,
				'options' => $this->get_elementor_templates(),
				'default' => '0',
				'description' => 'Create your MetForm in a Saved Template and select it here.',
			]
		);

		$this->add_control( 'form_note', [ 'label' => 'Footer Note', 'type' => Controls_Manager::TEXT, 'default' => 'We respect your privacy. Unsubscribe at any time.', 'dynamic' => [ 'active' => true ] ] );

		$this->end_controls_section();


		// ==========================
		// TAB: STYLE
		// ==========================

		// --- SECTION CONTAINER ---
		$this->start_controls_section( 'section_style_box', [ 'label' => 'Section Container', 'tab' => Controls_Manager::TAB_STYLE ] );
		
		// Advanced Background Control (Solid, Gradient, Image/Pattern, Video)
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'section_bg',
				'label' => 'Background',
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .cora_news_wrap',
			]
		);

		$this->add_responsive_control( 'padding', [ 'label' => 'Padding', 'type' => Controls_Manager::DIMENSIONS, 'selectors' => [ '{{WRAPPER}} .cora_news_wrap' => 'padding: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;' ], 'default' => [ 'top' => 80, 'right' => 60, 'bottom' => 80, 'left' => 60, 'isLinked' => false ] ] );
		$this->add_responsive_control( 'radius', [ 'label' => 'Border Radius', 'type' => Controls_Manager::DIMENSIONS, 'selectors' => [ '{{WRAPPER}} .cora_news_wrap' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;' ], 'default' => [ 'top' => 24, 'right' => 24, 'bottom' => 24, 'left' => 24, 'isLinked' => true ] ] );
		$this->end_controls_section();

		// --- TYPOGRAPHY ---
		$this->start_controls_section( 'section_style_typo', [ 'label' => 'Typography', 'tab' => Controls_Manager::TAB_STYLE ] );
		
		// Pill
		$this->add_control( 'heading_pill', [ 'label' => 'Pill', 'type' => Controls_Manager::HEADING ] );
		$this->add_control( 'pill_color', [ 'label' => 'Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_news_pill' => 'color: {{VALUE}}; border-color: {{VALUE}};' ], 'default' => 'rgba(255,255,255,0.7)' ] );
		
		// Title
		$this->add_control( 'heading_title', [ 'label' => 'Title', 'type' => Controls_Manager::HEADING, 'separator' => 'before' ] );
		$this->add_control( 'title_color', [ 'label' => 'Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_news_title' => 'color: {{VALUE}};' ], 'default' => '#ffffff' ] );
		$this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'title_typo', 'selector' => '{{WRAPPER}} .cora_news_title' ] );
		
		// Desc
		$this->add_control( 'heading_desc', [ 'label' => 'Description', 'type' => Controls_Manager::HEADING, 'separator' => 'before' ] );
		$this->add_control( 'desc_color', [ 'label' => 'Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_news_desc' => 'color: {{VALUE}};' ], 'default' => '#9CA3AF' ] );
		$this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'desc_typo', 'selector' => '{{WRAPPER}} .cora_news_desc' ] );
		
		// List
		$this->add_control( 'heading_list', [ 'label' => 'Benefits List', 'type' => Controls_Manager::HEADING, 'separator' => 'before' ] );
		$this->add_control( 'list_color', [ 'label' => 'Text Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_news_list li' => 'color: {{VALUE}};' ], 'default' => '#D1D5DB' ] );
		$this->add_control( 'check_color', [ 'label' => 'Checkmark Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_news_check' => 'color: {{VALUE}}; border-color: {{VALUE}};' ], 'default' => '#10B981' ] );
		$this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'list_typo', 'selector' => '{{WRAPPER}} .cora_news_list li' ] );
		$this->end_controls_section();

		// --- FORM CONTAINER ---
		$this->start_controls_section( 'section_style_form_box', [ 'label' => 'Form Container', 'tab' => Controls_Manager::TAB_STYLE ] );
		
		// Advanced Background for Form Box
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'form_box_bg',
				'label' => 'Background',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .cora_news_form_box',
			]
		);

		$this->add_responsive_control( 'form_padding', [ 'label' => 'Padding', 'type' => Controls_Manager::DIMENSIONS, 'selectors' => [ '{{WRAPPER}} .cora_news_form_box' => 'padding: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;' ], 'default' => [ 'top' => 40, 'right' => 40, 'bottom' => 40, 'left' => 40, 'isLinked' => true ] ] );
		$this->add_responsive_control( 'form_radius', [ 'label' => 'Radius', 'type' => Controls_Manager::DIMENSIONS, 'selectors' => [ '{{WRAPPER}} .cora_news_form_box' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;' ], 'default' => [ 'top' => 16, 'right' => 16, 'bottom' => 16, 'left' => 16, 'isLinked' => true ] ] );
		
		$this->add_control( 'note_color', [ 'label' => 'Footer Note Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_news_note' => 'color: {{VALUE}};' ], 'default' => '#6B7280', 'separator' => 'before' ] );
		
		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		?>
		<div class="cora_news_wrap">
			<div class="cora_news_grid">
				
				<div class="cora_news_left">
					<?php if ( ! empty( $settings['pill_text'] ) ) : ?>
						<div class="cora_news_pill">
							<i class="eicon-star"></i> <?php echo esc_html( $settings['pill_text'] ); ?>
						</div>
					<?php endif; ?>

					<h2 class="cora_news_title"><?php echo esc_html( $settings['title'] ); ?></h2>
					<div class="cora_news_desc"><?php echo esc_html( $settings['description'] ); ?></div>

					<?php if ( $settings['benefits'] ) : ?>
					<ul class="cora_news_list">
						<?php foreach ( $settings['benefits'] as $item ) : ?>
						<li>
							<span class="cora_news_check"><i class="eicon-check"></i></span>
							<?php echo esc_html( $item['text'] ); ?>
						</li>
						<?php endforeach; ?>
					</ul>
					<?php endif; ?>
				</div>

				<div class="cora_news_right">
					<div class="cora_news_form_box">
						<?php 
						if ( ! empty( $settings['form_template_id'] ) ) {
							echo \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $settings['form_template_id'] );
						} else {
							echo '<div style="color:white; text-align:center; padding:20px; border:1px dashed rgba(255,255,255,0.2);">Select your MetForm Template in widget settings.</div>';
						}
						?>
						
						<?php if ( ! empty( $settings['form_note'] ) ) : ?>
							<div class="cora_news_note"><?php echo esc_html( $settings['form_note'] ); ?></div>
						<?php endif; ?>
					</div>
				</div>

			</div>
		</div>
		<?php
	}
}