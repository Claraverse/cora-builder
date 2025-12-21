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

class Cora_Team_Card extends Base_Widget {

	public function get_name() {
		return 'cora_team_card';
	}

	public function get_title() {
		return 'Cora Team Card';
	}

	public function get_icon() {
		return 'eicon-person';
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
			[ 'label' => 'Profile Info', 'tab' => Controls_Manager::TAB_CONTENT ]
		);

		$this->add_control( 'image', [ 'label' => 'Avatar', 'type' => Controls_Manager::MEDIA, 'default' => [ 'url' => \Elementor\Utils::get_placeholder_image_src() ], 'dynamic' => [ 'active' => true ] ] );
		
		$this->add_control( 'name', [ 'label' => 'Name', 'type' => Controls_Manager::TEXT, 'default' => 'David Park', 'dynamic' => [ 'active' => true ] ] );
		$this->add_control( 'role', [ 'label' => 'Role', 'type' => Controls_Manager::TEXT, 'default' => 'Business Analyst', 'dynamic' => [ 'active' => true ] ] );
		$this->add_control( 'bio', [ 'label' => 'Bio', 'type' => Controls_Manager::TEXTAREA, 'default' => 'Seasoned analyst providing data-driven perspectives on market trends and strategic business growth opportunities.', 'rows' => 4, 'dynamic' => [ 'active' => true ] ] );

		$this->end_controls_section();


		// --- SOCIAL LINKS ---
		$this->start_controls_section(
			'section_social',
			[ 'label' => 'Social Links', 'tab' => Controls_Manager::TAB_CONTENT ]
		);

		$repeater = new Repeater();
		$repeater->add_control( 'icon', [ 'label' => 'Icon', 'type' => Controls_Manager::ICONS, 'default' => [ 'value' => 'fab fa-linkedin', 'library' => 'fa-brands' ] ] );
		$repeater->add_control( 'link', [ 'label' => 'Link', 'type' => Controls_Manager::URL, 'placeholder' => 'https://...' ] );

		$this->add_control(
			'social_items',
			[
				'label' => 'Social Icons',
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[ 'icon' => [ 'value' => 'fab fa-linkedin-in', 'library' => 'fa-brands' ] ],
					[ 'icon' => [ 'value' => 'fab fa-instagram', 'library' => 'fa-brands' ] ],
					[ 'icon' => [ 'value' => 'fab fa-twitter', 'library' => 'fa-brands' ] ],
				],
			]
		);

		$this->end_controls_section();


		// ==========================
		// TAB: STYLE
		// ==========================

		// --- CARD BOX ---
		$this->start_controls_section( 'section_style_box', [ 'label' => 'Card Box', 'tab' => Controls_Manager::TAB_STYLE ] );
		
		$this->add_responsive_control( 'padding', [ 'label' => 'Padding', 'type' => Controls_Manager::DIMENSIONS, 'selectors' => [ '{{WRAPPER}} .cora_team_card' => 'padding: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;' ], 'default' => [ 'top' => 32, 'right' => 32, 'bottom' => 32, 'left' => 32, 'isLinked' => true ] ] );
		
		$this->add_group_control( Group_Control_Background::get_type(), [ 'name' => 'bg', 'selector' => '{{WRAPPER}} .cora_team_card' ] );
		$this->add_group_control( Group_Control_Border::get_type(), [ 'name' => 'border', 'selector' => '{{WRAPPER}} .cora_team_card' ] );
		$this->add_responsive_control( 'radius', [ 'label' => 'Border Radius', 'type' => Controls_Manager::DIMENSIONS, 'selectors' => [ '{{WRAPPER}} .cora_team_card' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;' ], 'default' => [ 'top' => 24, 'right' => 24, 'bottom' => 24, 'left' => 24, 'isLinked' => true ] ] );
		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [ 'name' => 'shadow', 'selector' => '{{WRAPPER}} .cora_team_card' ] );
		
		$this->end_controls_section();


		// --- HEADER LAYOUT ---
		$this->start_controls_section( 'section_style_header', [ 'label' => 'Header Layout', 'tab' => Controls_Manager::TAB_STYLE ] );
		
		$this->add_responsive_control( 
			'avatar_size', 
			[ 
				'label' => 'Avatar Size', 
				'type' => Controls_Manager::SLIDER, 
				'default' => [ 'size' => 80 ],
				'selectors' => [ '{{WRAPPER}} .cora_team_avatar' => 'width: {{SIZE}}px; height: {{SIZE}}px;' ] 
			] 
		);
		
		$this->add_responsive_control( 'avatar_radius', [ 'label' => 'Avatar Radius', 'type' => Controls_Manager::SLIDER, 'selectors' => [ '{{WRAPPER}} .cora_team_avatar' => 'border-radius: {{SIZE}}px;' ], 'default' => [ 'size' => 24 ] ] );
		
		$this->add_responsive_control( 'header_gap', [ 'label' => 'Gap (Avatar/Social)', 'type' => Controls_Manager::SLIDER, 'selectors' => [ '{{WRAPPER}} .cora_team_header' => 'margin-bottom: {{SIZE}}px;' ], 'default' => [ 'size' => 24 ] ] );

		$this->end_controls_section();


		// --- SOCIAL ICONS ---
		$this->start_controls_section( 'section_style_social', [ 'label' => 'Social Icons', 'tab' => Controls_Manager::TAB_STYLE ] );
		
		$this->add_responsive_control( 'social_size', [ 'label' => 'Icon Size', 'type' => Controls_Manager::SLIDER, 'selectors' => [ '{{WRAPPER}} .cora_team_social_link i' => 'font-size: {{SIZE}}px;', '{{WRAPPER}} .cora_team_social_link svg' => 'width: {{SIZE}}px; height: {{SIZE}}px;' ], 'default' => [ 'size' => 16 ] ] );
		
		$this->add_responsive_control( 'social_box_size', [ 'label' => 'Box Size', 'type' => Controls_Manager::SLIDER, 'selectors' => [ '{{WRAPPER}} .cora_team_social_link' => 'width: {{SIZE}}px; height: {{SIZE}}px;' ], 'default' => [ 'size' => 40 ] ] );

		$this->start_controls_tabs( 'tabs_social' );
		
		$this->start_controls_tab( 'tab_social_normal', [ 'label' => 'Normal' ] );
		$this->add_control( 'social_color', [ 'label' => 'Icon Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_team_social_link' => 'color: {{VALUE}};' ], 'default' => '#667085' ] );
		$this->add_control( 'social_bg', [ 'label' => 'Background', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_team_social_link' => 'background-color: {{VALUE}};' ], 'default' => '#F2F4F7' ] );
		$this->end_controls_tab();

		$this->start_controls_tab( 'tab_social_hover', [ 'label' => 'Hover' ] );
		$this->add_control( 'social_color_hover', [ 'label' => 'Icon Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_team_social_link:hover' => 'color: {{VALUE}};' ], 'default' => '#FFFFFF' ] );
		$this->add_control( 'social_bg_hover', [ 'label' => 'Background', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_team_social_link:hover' => 'background-color: {{VALUE}};' ], 'default' => '#101828' ] );
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		$this->end_controls_section();


		// --- TYPOGRAPHY ---
		$this->start_controls_section( 'section_style_typo', [ 'label' => 'Typography', 'tab' => Controls_Manager::TAB_STYLE ] );
		
		// Name
		$this->add_control( 'name_color', [ 'label' => 'Name Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_team_name' => 'color: {{VALUE}};' ] ] );
		$this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'name_typo', 'selector' => '{{WRAPPER}} .cora_team_name' ] );
		$this->add_responsive_control( 'name_mb', [ 'label' => 'Spacing', 'type' => Controls_Manager::SLIDER, 'selectors' => [ '{{WRAPPER}} .cora_team_name' => 'margin-bottom: {{SIZE}}px;' ] ] );

		// Role
		$this->add_control( 'role_color', [ 'label' => 'Role Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_team_role' => 'color: {{VALUE}};' ] ] );
		$this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'role_typo', 'selector' => '{{WRAPPER}} .cora_team_role' ] );
		$this->add_responsive_control( 'role_mb', [ 'label' => 'Spacing', 'type' => Controls_Manager::SLIDER, 'selectors' => [ '{{WRAPPER}} .cora_team_role' => 'margin-bottom: {{SIZE}}px;' ] ] );

		// Bio
		$this->add_control( 'bio_color', [ 'label' => 'Bio Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cora_team_bio' => 'color: {{VALUE}};' ] ] );
		$this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'bio_typo', 'selector' => '{{WRAPPER}} .cora_team_bio' ] );
		
		// Bio Truncation
		$this->add_responsive_control( 
			'trunc_bio_lines', 
			[ 
				'label' => 'Truncate Bio', 
				'type' => Controls_Manager::NUMBER, 
				'min' => 1, 
				'selectors' => [ '{{WRAPPER}} .cora_team_bio' => 'display: -webkit-box; -webkit-line-clamp: {{VALUE}}; -webkit-box-orient: vertical; overflow: hidden;' ] 
			] 
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		?>
		<div class="cora_team_card">
			
			<div class="cora_team_header">
				<div class="cora_team_avatar" style="background-image: url('<?php echo esc_url( $settings['image']['url'] ); ?>');"></div>
				
				<?php if ( $settings['social_items'] ) : ?>
				<div class="cora_team_social">
					<?php foreach ( $settings['social_items'] as $item ) : 
						$target = $item['link']['is_external'] ? ' target="_blank"' : '';
						$nofollow = $item['link']['nofollow'] ? ' rel="nofollow"' : '';
					?>
					<a href="<?php echo esc_url( $item['link']['url'] ); ?>" class="cora_team_social_link" <?php echo $target . $nofollow; ?>>
						<?php \Elementor\Icons_Manager::render_icon( $item['icon'], [ 'aria-hidden' => 'true' ] ); ?>
					</a>
					<?php endforeach; ?>
				</div>
				<?php endif; ?>
			</div>

			<div class="cora_team_info">
				<h3 class="cora_team_name"><?php echo esc_html( $settings['name'] ); ?></h3>
				<div class="cora_team_role"><?php echo esc_html( $settings['role'] ); ?></div>
				<div class="cora_team_bio"><?php echo esc_html( $settings['bio'] ); ?></div>
			</div>

		</div>
		<?php
	}
}