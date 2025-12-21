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

class Cora_Pricing_Card extends Base_Widget {

	public function get_name() {
		return 'cora_pricing_card';
	}

	public function get_title() {
		return 'Cora Pricing Card';
	}

	public function get_icon() {
		return 'eicon-price-table';
	}

	public function get_categories() {
		return [ 'cora_widgets' ];
	}

	protected function register_controls() {

		// ==========================
		// TAB: CONTENT
		// ==========================
		$this->start_controls_section(
			'section_header_content',
			[ 'label' => 'Header & Price', 'tab' => Controls_Manager::TAB_CONTENT ]
		);

		$this->add_control( 'badge_text', [ 'label' => 'Badge Text (Optional)', 'type' => Controls_Manager::TEXT, 'placeholder' => 'e.g. Popular' ] );
		$this->add_control( 'title', [ 'label' => 'Plan Title', 'type' => Controls_Manager::TEXT, 'default' => 'Lite', 'dynamic' => [ 'active' => true ] ] );
		$this->add_control( 'description', [ 'label' => 'Description', 'type' => Controls_Manager::TEXTAREA, 'default' => 'Perfect for solo entrepreneurs.', 'rows' => 2, 'dynamic' => [ 'active' => true ] ] );

		$this->add_control( 'hr_price', [ 'type' => Controls_Manager::DIVIDER ] );

		$this->add_control( 'currency', [ 'label' => 'Currency', 'type' => Controls_Manager::TEXT, 'default' => '$', 'wrapper_class' => 'elementor-control-type-text-auto-width' ] );
		$this->add_control( 'price', [ 'label' => 'Price', 'type' => Controls_Manager::TEXT, 'default' => '19', 'dynamic' => [ 'active' => true ], 'wrapper_class' => 'elementor-control-type-text-auto-width' ] );
		$this->add_control( 'period', [ 'label' => 'Period', 'type' => Controls_Manager::TEXT, 'default' => '/month', 'wrapper_class' => 'elementor-control-type-text-auto-width' ] );

		$this->add_control( 'header_link_text', [ 'label' => 'Header Link Text', 'type' => Controls_Manager::TEXT, 'default' => 'Billed annually', 'separator' => 'before' ] );
		$this->add_control( 'header_link_url', [ 'label' => 'Header Link URL', 'type' => Controls_Manager::URL, 'dynamic' => [ 'active' => true ] ] );

		$this->end_controls_section();


		// --- FEATURES LIST ---
		$this->start_controls_section(
			'section_features_content',
			[ 'label' => 'Features List', 'tab' => Controls_Manager::TAB_CONTENT ]
		);

		$repeater = new Repeater();
		$repeater->add_control( 'text', [ 'label' => 'Feature Text', 'type' => Controls_Manager::TEXT, 'default' => 'Feature item', 'label_block' => true ] );
		$repeater->add_control( 'icon', [ 'label' => 'Icon', 'type' => Controls_Manager::ICONS, 'default' => [ 'value' => 'fas fa-check', 'library' => 'fa-solid' ] ] );
		$repeater->add_control( 'item_active', [ 'label' => 'Active Style', 'type' => Controls_Manager::SWITCHER, 'default' => 'yes', 'return_value' => 'yes' ] );

		$this->add_control(
			'features',
			[
				'label' => 'Features',
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[ 'text' => '5 Projects' ],
					[ 'text' => 'Basic Analytics' ],
					[ 'text' => '24/7 Support' ],
				],
				'title_field' => '{{{ text }}}',
			]
		);

		$this->end_controls_section();


		// --- FOOTER BUTTON ---
		$this->start_controls_section(
			'section_footer_content',
			[ 'label' => 'Footer Button', 'tab' => Controls_Manager::TAB_CONTENT ]
		);
		
		$this->add_control( 'btn_text', [ 'label' => 'Button Text', 'type' => Controls_Manager::TEXT, 'default' => 'Get Started', 'dynamic' => [ 'active' => true ] ] );
		$this->add_control( 'btn_link', [ 'label' => 'Button Link', 'type' => Controls_Manager::URL, 'placeholder' => 'https://...', 'dynamic' => [ 'active' => true ] ] );
		
		$this->end_controls_section();


		// ==========================
		// TAB: STYLE
		// ==========================

		// --- CARD BOX ---
		$this->start_controls_section( 'section_style_box', [ 'label' => 'Card Settings', 'tab' => Controls_Manager::TAB_STYLE ] );
		
		$this->add_control(
			'is_featured',
			[
				'label' => 'Is Featured Card?',
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'return_value' => 'yes',
				'description' => 'Enables the dark theme style automatically.',
				'prefix_class' => 'cora-is-featured-',
			]
		);

		$this->add_responsive_control( 'padding', [ 'label' => 'Padding', 'type' => Controls_Manager::DIMENSIONS, 'selectors' => [ '{{WRAPPER}} .cora_price_card' => 'padding: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;' ], 'default' => [ 'top' => 32, 'right' => 32, 'bottom' => 32, 'left' => 32, 'isLinked' => true ] ] );
		$this->add_group_control( Group_Control_Background::get_type(), [ 'name' => 'bg', 'selector' => '{{WRAPPER}} .cora_price_card', 'fields_options' => [ 'background' => [ 'default' => 'classic' ], 'color' => [ 'default' => '#ffffff' ] ], 'condition' => [ 'is_featured' => '' ] ] );
		$this->add_group_control( Group_Control_Border::get_type(), [ 'name' => 'border', 'selector' => '{{WRAPPER}} .cora_price_card', 'fields_options' => [ 'border' => [ 'default' => 'solid' ], 'width' => [ 'default' => [ 'top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1, 'isLinked' => true ] ], 'color' => [ 'default' => '#EAEAEA' ] ], 'condition' => [ 'is_featured' => '' ] ] );
		$this->add_responsive_control( 'radius', [ 'label' => 'Border Radius', 'type' => Controls_Manager::DIMENSIONS, 'selectors' => [ '{{WRAPPER}} .cora_price_card' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;' ], 'default' => [ 'top' => 24, 'right' => 24, 'bottom' => 24, 'left' => 24, 'isLinked' => true ] ] );
		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [ 'name' => 'shadow', 'selector' => '{{WRAPPER}} .cora_price_card' ] );

		// Badge Style
		$this->add_control( 'heading_badge', [ 'label' => 'Badge Style', 'type' => Controls_Manager::HEADING, 'separator' => 'before', 'condition' => [ 'badge_text!' => '' ] ] );
		$this->add_control( 'badge_bg', [ 'label' => 'Background', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_price_badge' => 'background-color: {{VALUE}};' ], 'default' => '#101828', 'condition' => [ 'badge_text!' => '' ] ] );
		$this->add_control( 'badge_color', [ 'label' => 'Text Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_price_badge' => 'color: {{VALUE}};' ], 'default' => '#FFFFFF', 'condition' => [ 'badge_text!' => '' ] ] );
		$this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'badge_typo', 'selector' => '{{WRAPPER}} .cora_price_badge', 'condition' => [ 'badge_text!' => '' ] ] );

		$this->end_controls_section();


		// --- HEADER STYLE ---
		$this->start_controls_section( 'section_style_header', [ 'label' => 'Header Typography', 'tab' => Controls_Manager::TAB_STYLE ] );
		// Title
		$this->add_control( 'title_color', [ 'label' => 'Title Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_price_title' => 'color: {{VALUE}};' ], 'condition' => [ 'is_featured' => '' ] ] );
		$this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'title_typo', 'selector' => '{{WRAPPER}} .cora_price_title' ] );
		// Desc
		$this->add_control( 'desc_color', [ 'label' => 'Desc Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_price_desc' => 'color: {{VALUE}};' ], 'condition' => [ 'is_featured' => '' ] ] );
		$this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'desc_typo', 'selector' => '{{WRAPPER}} .cora_price_desc' ] );
		$this->end_controls_section();

		// --- PRICING STYLE ---
		$this->start_controls_section( 'section_style_pricing', [ 'label' => 'Pricing Typography', 'tab' => Controls_Manager::TAB_STYLE ] );
		// Price
		$this->add_control( 'price_color', [ 'label' => 'Price Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_price_val' => 'color: {{VALUE}};' ], 'condition' => [ 'is_featured' => '' ] ] );
		$this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'price_typo', 'selector' => '{{WRAPPER}} .cora_price_val' ] );
		// Currency/Period
		$this->add_control( 'meta_color', [ 'label' => 'Meta Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_price_currency, {{WRAPPER}} .cora_price_period' => 'color: {{VALUE}};' ], 'condition' => [ 'is_featured' => '' ] ] );
		$this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'meta_typo', 'selector' => '{{WRAPPER}} .cora_price_currency, {{WRAPPER}} .cora_price_period' ] );
		// Header Link
		$this->add_control( 'header_link_color', [ 'label' => 'Link Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_price_header_link' => 'color: {{VALUE}};' ] ] );
		$this->end_controls_section();

		// --- FEATURES STYLE ---
		$this->start_controls_section( 'section_style_features', [ 'label' => 'Features List', 'tab' => Controls_Manager::TAB_STYLE ] );
		$this->add_responsive_control( 'list_gap', [ 'label' => 'Item Gap', 'type' => Controls_Manager::SLIDER, 'selectors' => [ '{{WRAPPER}} .cora_price_features' => 'gap: {{SIZE}}px;' ], 'default' => [ 'size' => 16 ] ] );
		$this->add_control( 'feature_text_color', [ 'label' => 'Text Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_feat_text' => 'color: {{VALUE}};' ], 'condition' => [ 'is_featured' => '' ] ] );
		$this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'feature_typo', 'selector' => '{{WRAPPER}} .cora_feat_text' ] );
		$this->add_control( 'icon_color_active', [ 'label' => 'Active Icon Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_feat_item.is-active .cora_feat_icon' => 'color: {{VALUE}}; fill: {{VALUE}};' ], 'default' => '#007bff', 'condition' => [ 'is_featured' => '' ] ] );
		$this->add_control( 'icon_color_inactive', [ 'label' => 'Inactive Icon Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_feat_item:not(.is-active) .cora_feat_icon' => 'color: {{VALUE}}; fill: {{VALUE}};' ], 'default' => '#98A2B3', 'condition' => [ 'is_featured' => '' ] ] );
		$this->add_responsive_control( 'icon_size', [ 'label' => 'Icon Size', 'type' => Controls_Manager::SLIDER, 'selectors' => [ '{{WRAPPER}} .cora_feat_icon i' => 'font-size: {{SIZE}}px;', '{{WRAPPER}} .cora_feat_icon svg' => 'width: {{SIZE}}px; height: {{SIZE}}px;' ] ] );
		$this->end_controls_section();

		// --- FOOTER BTN STYLE ---
		$this->start_controls_section( 'section_style_btn', [ 'label' => 'Footer Button', 'tab' => Controls_Manager::TAB_STYLE ] );
		$this->start_controls_tabs( 'tabs_btn' );
		// Normal
		$this->start_controls_tab( 'tab_btn_normal', [ 'label' => 'Normal' ] );
		$this->add_control( 'btn_bg', [ 'label' => 'Background', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_price_btn' => 'background-color: {{VALUE}};' ], 'default' => '#101828', 'condition' => [ 'is_featured' => '' ] ] );
		$this->add_control( 'btn_color', [ 'label' => 'Text Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_price_btn' => 'color: {{VALUE}};' ], 'default' => '#ffffff', 'condition' => [ 'is_featured' => '' ] ] );
		$this->add_group_control( Group_Control_Border::get_type(), [ 'name' => 'btn_border', 'selector' => '{{WRAPPER}} .cora_price_btn', 'condition' => [ 'is_featured' => '' ] ] );
		$this->end_controls_tab();
		// Hover
		$this->start_controls_tab( 'tab_btn_hover', [ 'label' => 'Hover' ] );
		$this->add_control( 'btn_bg_hover', [ 'label' => 'Background', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_price_btn:hover' => 'background-color: {{VALUE}};' ], 'condition' => [ 'is_featured' => '' ] ] );
		$this->add_control( 'btn_color_hover', [ 'label' => 'Text Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_price_btn:hover' => 'color: {{VALUE}};' ], 'condition' => [ 'is_featured' => '' ] ] );
		$this->add_group_control( Group_Control_Border::get_type(), [ 'name' => 'btn_border_hover', 'selector' => '{{WRAPPER}} .cora_price_btn:hover', 'condition' => [ 'is_featured' => '' ] ] );
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->add_responsive_control( 'btn_radius', [ 'label' => 'Radius', 'type' => Controls_Manager::SLIDER, 'selectors' => [ '{{WRAPPER}} .cora_price_btn' => 'border-radius: {{SIZE}}px;' ], 'default' => [ 'size' => 8 ], 'separator' => 'before' ] );
		$this->add_responsive_control( 'btn_padding', [ 'label' => 'Padding', 'type' => Controls_Manager::DIMENSIONS, 'selectors' => [ '{{WRAPPER}} .cora_price_btn' => 'padding: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;' ], 'default' => [ 'top' => 14, 'right' => 24, 'bottom' => 14, 'left' => 24, 'isLinked' => true ] ] );
		$this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'btn_typo', 'selector' => '{{WRAPPER}} .cora_price_btn' ] );
		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		
		// Header Link
		$h_link_tag = 'div'; $h_link_href = '';
		if ( ! empty( $settings['header_link_url']['url'] ) ) {
			$h_link_tag = 'a';
			$h_link_href = 'href="' . esc_url( $settings['header_link_url']['url'] ) . '"';
		}

		// Footer Button tag
		$btn_tag = 'div'; $btn_href = '';
		if ( ! empty( $settings['btn_link']['url'] ) ) {
			$btn_tag = 'a';
			$btn_href = 'href="' . esc_url( $settings['btn_link']['url'] ) . '"';
		}
		?>
		<div class="cora_price_card">
			
			<?php if ( ! empty( $settings['badge_text'] ) ) : ?>
				<div class="cora_price_badge"><?php echo esc_html( $settings['badge_text'] ); ?></div>
			<?php endif; ?>

			<div class="cora_price_header">
				<h3 class="cora_price_title"><?php echo esc_html( $settings['title'] ); ?></h3>
				<div class="cora_price_desc"><?php echo esc_html( $settings['description'] ); ?></div>
			</div>

			<div class="cora_price_wrap">
				<div class="cora_price_box">
					<span class="cora_price_currency"><?php echo esc_html( $settings['currency'] ); ?></span>
					<span class="cora_price_val"><?php echo esc_html( $settings['price'] ); ?></span>
					<span class="cora_price_period"><?php echo esc_html( $settings['period'] ); ?></span>
				</div>
				<?php if ( ! empty( $settings['header_link_text'] ) ) : ?>
					<<?php echo $h_link_tag . ' ' . $h_link_href; ?> class="cora_price_header_link">
						<?php echo esc_html( $settings['header_link_text'] ); ?> <i class="eicon-arrow-right"></i>
					</<?php echo $h_link_tag; ?>>
				<?php endif; ?>
			</div>

			<div class="cora_price_divider"></div>

			<?php if ( $settings['features'] ) : ?>
			<div class="cora_price_features">
				<?php foreach ( $settings['features'] as $item ) : 
					$active_class = ( 'yes' === $item['item_active'] ) ? 'is-active' : '';
				?>
				<div class="cora_feat_item <?php echo esc_attr( $active_class ); ?>">
					<div class="cora_feat_icon">
						<?php \Elementor\Icons_Manager::render_icon( $item['icon'], [ 'aria-hidden' => 'true' ] ); ?>
					</div>
					<span class="cora_feat_text"><?php echo esc_html( $item['text'] ); ?></span>
				</div>
				<?php endforeach; ?>
			</div>
			<?php endif; ?>

			<div class="cora_price_footer">
				<<?php echo $btn_tag . ' ' . $btn_href; ?> class="cora_price_btn">
					<?php echo esc_html( $settings['btn_text'] ); ?>
				</<?php echo $btn_tag; ?>>
			</div>

		</div>
		<?php
	}
}