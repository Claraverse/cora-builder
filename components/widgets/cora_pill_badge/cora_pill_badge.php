<?php
namespace Cora_Builder\Components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;

if (!defined('ABSPATH'))
	exit;

class Cora_Pill_Badge extends Base_Widget
{

	public function get_name()
	{
		return 'cora_pill_badge';
	}
	public function get_title()
	{
		return 'Cora Pill Badge';
	}
	public function get_icon()
	{
		return 'eicon-button';
	}

	protected function register_controls()
	{

		// --- TAB 1: CONTENT ---
		$this->start_controls_section('section_content', ['label' => 'Badge Content']);

		$this->add_control('text', [
			'label' => 'Label',
			'type' => Controls_Manager::TEXT,
			'default' => 'Solutions',
			'dynamic' => ['active' => true],
		]);

		$this->add_control('icon_type', [
			'label' => 'Icon Type',
			'type' => Controls_Manager::SELECT,
			'default' => 'icon',
			'options' => [
				'none' => 'None',
				'icon' => 'Icon Library',
				'svg' => 'SVG Code',
			],
		]);

		$this->add_control('selected_icon', [
			'label' => 'Icon',
			'type' => Controls_Manager::ICONS,
			'default' => ['value' => 'fas fa-bullseye', 'library' => 'fa-solid'],
			'condition' => ['icon_type' => 'icon'],
		]);

		$this->add_control('icon_svg', [
			'label' => 'SVG Code',
			'type' => Controls_Manager::TEXTAREA,
			'placeholder' => '<svg>...</svg>',
			'condition' => ['icon_type' => 'svg'],
		]);

		$this->end_controls_section();

		// --- TAB 2: STYLE (Local Element Skins) ---
		// 1. Mandatory Structural Reset (Deletes need for style.css)
		$this->start_controls_section('style_reset', ['label' => 'Structural Reset', 'tab' => Controls_Manager::TAB_STYLE]);
		$this->add_control('base_pill_reset', [
			'type' => Controls_Manager::HIDDEN,
			'default' => 'reset',
			'selectors' => [
				'{{WRAPPER}} .cora_pill' => 'display: inline-flex; align-items: center; justify-content: center; box-sizing: border-box; text-decoration: none;',
				'{{WRAPPER}} .cora_pill_icon' => 'display: flex; align-items: center; justify-content: center; line-height: 1; flex-shrink: 0;',
				'{{WRAPPER}} .cora_pill_icon svg' => 'width: 1em; height: 1em; fill: currentColor;',
				'{{WRAPPER}} .cora_pill_text' => 'margin: 0; padding: 0; line-height: 1;',
			],
		]);
		$this->end_controls_section();

		// 2. Icon Skin (Local Colors/Size)
		$this->start_controls_section('style_icon_local', ['label' => 'Icon Skin', 'tab' => Controls_Manager::TAB_STYLE, 'condition' => ['icon_type!' => 'none']]);
		$this->add_responsive_control('icon_size', [
			'label' => 'Icon Size (px)',
			'type' => Controls_Manager::SLIDER,
			'selectors' => ['{{WRAPPER}} .cora_pill_icon' => 'font-size: {{SIZE}}px;'],
		]);
		$this->add_control('icon_color', [
			'label' => 'Icon Color',
			'type' => Controls_Manager::COLOR,
			'selectors' => ['{{WRAPPER}} .cora_pill_icon' => 'color: {{VALUE}};'],
		]);
		$this->end_controls_section();

		// 3. Text Skin (Typography Engine)
		$this->register_text_styling_controls('pill_text', 'Label Typography', '{{WRAPPER}} .cora_pill_text');

		// --- TAB 3: GLOBAL (4th Tab Design Engine) ---
		// These call your modular methods from Base_Widget
		$this->register_global_design_controls('.cora_pill');
		$this->register_layout_geometry('.cora_pill'); // Handles width, padding, border, and gaps
		$this->register_surface_styles('.cora_pill');  // Handles Glassmorphism & Radius
		$this->register_common_spatial_controls();     // Handles rotation and max-width
		$this->register_alignment_controls('pill_align', '.cora_pill_wrapper', '.cora_pill');

		// --- TAB 4: ADVANCED (Interactions) ---
		$this->register_interaction_motion();
		$this->register_transform_engine('.cora_pill'); // Kinetic hover/active movement
		$this->register_overlay_engine('.cora_pill');   // Background patterns/noise
		$this->register_cursor_engine('.cora_pill');    // Custom interaction cursor
	}

	protected function render()
	{
		$settings = $this->get_settings_for_display();
		?>
		<div class="cora_pill_wrapper cora-unit-container">
			<div class="cora_pill">
				<?php if ('icon' === $settings['icon_type'] && !empty($settings['selected_icon']['value'])): ?>
					<div class="cora_pill_icon">
						<?php Icons_Manager::render_icon($settings['selected_icon'], ['aria-hidden' => 'true']); ?>
					</div>
				<?php elseif ('svg' === $settings['icon_type'] && !empty($settings['icon_svg'])): ?>
					<div class="cora_pill_icon">
						<?php echo wp_kses($settings['icon_svg'], ['svg' => ['xmlns' => [], 'viewbox' => [], 'fill' => [], 'class' => []], 'path' => ['d' => [], 'fill' => []]]); ?>
					</div>
				<?php endif; ?>

				<span class="cora_pill_text"><?php echo esc_html($settings['text']); ?></span>
			</div>
		</div>
		<?php
	}
}