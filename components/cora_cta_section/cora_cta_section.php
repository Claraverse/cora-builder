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

class Cora_Cta_Section extends Base_Widget {

	public function get_name() {
		return 'cora_cta_section';
	}

	public function get_title() {
		return 'Cora CTA Section';
	}
// cora_widgets
	public function get_icon() {
		return 'eicon-call-to-action';
	}

	public function get_categories() {
		return [ 'cora_widgets ' ]; // Group with Hero
	}

	protected function register_controls() {

		// ==========================
		// TAB: CONTENT
		// ==========================
		$this->start_controls_section(
			'section_content',
			[ 'label' => 'Main Content', 'tab' => Controls_Manager::TAB_CONTENT ]
		);

		$this->add_control( 'pill_text', [ 'label' => 'Pill Text', 'type' => Controls_Manager::TEXT, 'default' => 'Let’s build something amazing together' ] );
		$this->add_control( 'title', [ 'label' => 'Title', 'type' => Controls_Manager::TEXTAREA, 'default' => 'Ready to elevate your digital presence?', 'rows' => 2 ] );
		$this->add_control( 'description', [ 'label' => 'Description', 'type' => Controls_Manager::TEXTAREA, 'default' => 'Let’s collaborate to create digital experiences that your users will love and your business will benefit from.', 'rows' => 3 ] );

		$this->end_controls_section();

		// --- BUTTONS ---
		$this->start_controls_section(
			'section_buttons',
			[ 'label' => 'Action Buttons', 'tab' => Controls_Manager::TAB_CONTENT ]
		);

		// Button 1
		$this->add_control( 'btn_1_text', [ 'label' => 'Primary Button', 'type' => Controls_Manager::TEXT, 'default' => 'Start a project' ] );
		$this->add_control( 'btn_1_link', [ 'label' => 'Link 1', 'type' => Controls_Manager::URL, 'placeholder' => 'https://...' ] );

		// Button 2
		$this->add_control( 'btn_2_text', [ 'label' => 'Secondary Button', 'type' => Controls_Manager::TEXT, 'default' => 'View our work' ] );
		$this->add_control( 'btn_2_link', [ 'label' => 'Link 2', 'type' => Controls_Manager::URL, 'placeholder' => 'https://...' ] );

		$this->end_controls_section();

		// --- STATS ---
		$this->start_controls_section(
			'section_stats',
			[ 'label' => 'Stats Grid', 'tab' => Controls_Manager::TAB_CONTENT ]
		);

		$repeater = new Repeater();
		$repeater->add_control( 'stat_value', [ 'label' => 'Value', 'type' => Controls_Manager::TEXT, 'default' => '150+' ] );
		$repeater->add_control( 'stat_label', [ 'label' => 'Label', 'type' => Controls_Manager::TEXT, 'default' => 'Projects delivered' ] );

		$this->add_control(
			'stats',
			[
				'label' => 'Stat Items',
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[ 'stat_value' => '150+', 'stat_label' => 'Projects delivered' ],
					[ 'stat_value' => '98%', 'stat_label' => 'Client satisfaction' ],
					[ 'stat_value' => '5+', 'stat_label' => 'Years experience' ],
					[ 'stat_value' => '24h', 'stat_label' => 'Response time' ],
				],
			]
		);

		$this->end_controls_section();


		// ==========================
		// TAB: STYLE
		// ==========================

		// --- CONTAINER ---
		$this->start_controls_section( 'section_style_box', [ 'label' => 'Section Settings', 'tab' => Controls_Manager::TAB_STYLE ] );
		
		$this->add_control( 'bg_color', [ 'label' => 'Background', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_cta_wrap' => 'background-color: {{VALUE}};' ], 'default' => '#0A0A0A' ] );
		
		$this->add_control( 'glow_color', [ 'label' => 'Glow Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_cta_glow' => 'background: radial-gradient(circle, {{VALUE}} 0%, rgba(0,0,0,0) 70%);' ], 'default' => 'rgba(124, 58, 237, 0.3)' ] );

		$this->add_responsive_control( 'padding', [ 'label' => 'Padding', 'type' => Controls_Manager::DIMENSIONS, 'selectors' => [ '{{WRAPPER}} .cora_cta_wrap' => 'padding: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;' ], 'default' => [ 'top' => 100, 'right' => 20, 'bottom' => 100, 'left' => 20, 'isLinked' => false ] ] );

		$this->end_controls_section();

		// --- TYPOGRAPHY ---
		$this->start_controls_section( 'section_style_typo', [ 'label' => 'Typography', 'tab' => Controls_Manager::TAB_STYLE ] );
		
		// Pill
		$this->add_control( 'pill_color', [ 'label' => 'Pill Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_cta_pill' => 'color: {{VALUE}}; border-color: {{VALUE}};' ], 'default' => 'rgba(255,255,255,0.7)' ] );
		
		// Title
		$this->add_control( 'title_color', [ 'label' => 'Title Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_cta_title' => 'color: {{VALUE}};' ], 'default' => '#ffffff' ] );
		$this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'title_typo', 'selector' => '{{WRAPPER}} .cora_cta_title' ] );
		
		// Desc
		$this->add_control( 'desc_color', [ 'label' => 'Desc Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_cta_desc' => 'color: {{VALUE}};' ], 'default' => '#9CA3AF' ] );
		$this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'desc_typo', 'selector' => '{{WRAPPER}} .cora_cta_desc' ] );

		$this->end_controls_section();

		// --- BUTTONS ---
		$this->start_controls_section( 'section_style_btns', [ 'label' => 'Buttons', 'tab' => Controls_Manager::TAB_STYLE ] );
		
		$this->add_control( 'btn_primary_bg', [ 'label' => 'Primary Bg', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_btn_primary' => 'background-color: {{VALUE}};' ], 'default' => '#ffffff' ] );
		$this->add_control( 'btn_primary_text', [ 'label' => 'Primary Text', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_btn_primary' => 'color: {{VALUE}};' ], 'default' => '#0A0A0A' ] );
		
		$this->add_control( 'btn_sec_text', [ 'label' => 'Secondary Text', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_btn_secondary' => 'color: {{VALUE}};' ], 'default' => '#ffffff' ] );
		$this->add_control( 'btn_sec_border', [ 'label' => 'Secondary Border', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_btn_secondary' => 'border-color: {{VALUE}};' ], 'default' => 'rgba(255,255,255,0.2)' ] );

		$this->end_controls_section();

		// --- STATS ---
		$this->start_controls_section( 'section_style_stats', [ 'label' => 'Stats', 'tab' => Controls_Manager::TAB_STYLE ] );
		
		$this->add_control( 'stat_val_color', [ 'label' => 'Value Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_stat_val' => 'color: {{VALUE}};' ], 'default' => '#ffffff' ] );
		$this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'stat_val_typo', 'selector' => '{{WRAPPER}} .cora_stat_val' ] );
		
		$this->add_control( 'stat_lbl_color', [ 'label' => 'Label Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_stat_lbl' => 'color: {{VALUE}};' ], 'default' => '#9CA3AF' ] );
		
		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		?>
		<div class="cora_cta_wrap">
			<div class="cora_cta_glow"></div>
			
			<div class="cora_cta_content">
				<?php if ( ! empty( $settings['pill_text'] ) ) : ?>
					<div class="cora_cta_pill">
						<i class="eicon-sparkles"></i> <?php echo esc_html( $settings['pill_text'] ); ?>
					</div>
				<?php endif; ?>

				<h2 class="cora_cta_title"><?php echo esc_html( $settings['title'] ); ?></h2>
				<div class="cora_cta_desc"><?php echo esc_html( $settings['description'] ); ?></div>

				<div class="cora_cta_actions">
					<?php if ( ! empty( $settings['btn_1_text'] ) ) : ?>
						<a href="<?php echo esc_url( $settings['btn_1_link']['url'] ); ?>" class="cora_cta_btn cora_btn_primary">
							<?php echo esc_html( $settings['btn_1_text'] ); ?> <i class="eicon-arrow-right"></i>
						</a>
					<?php endif; ?>

					<?php if ( ! empty( $settings['btn_2_text'] ) ) : ?>
						<a href="<?php echo esc_url( $settings['btn_2_link']['url'] ); ?>" class="cora_cta_btn cora_btn_secondary">
							<?php echo esc_html( $settings['btn_2_text'] ); ?>
						</a>
					<?php endif; ?>
				</div>
			</div>

			<div class="cora_cta_stats">
				<?php foreach ( $settings['stats'] as $stat ) : ?>
					<div class="cora_stat_item">
						<div class="cora_stat_val"><?php echo esc_html( $stat['stat_value'] ); ?></div>
						<div class="cora_stat_lbl"><?php echo esc_html( $stat['stat_label'] ); ?></div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}
}