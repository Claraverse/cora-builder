<?php
namespace Cora_Builder\Components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

if (!defined('ABSPATH'))
	exit;

class Cora_Advance_Heading extends Base_Widget
{

	public function get_name()
	{
		return 'cora_advance_heading';
	}
	public function get_title()
	{
		return 'Cora Advance Heading';
	}
	public function get_icon()
	{
		return 'eicon-heading';
	}

	protected function register_controls()
	{

		// --- TAB 1: CONTENT ---
		$this->start_controls_section('section_content', ['label' => 'Heading Content']);

		$this->add_control('text_before', [
			'label' => 'Text Before',
			'type' => Controls_Manager::TEXT,
			'default' => 'Browse by',
			'dynamic' => ['active' => true],
		]);

		$this->add_control('text_highlight', [
			'label' => 'Highlighted Text',
			'type' => Controls_Manager::TEXT,
			'default' => 'Category',
			'dynamic' => ['active' => true],
		]);

		$this->add_control('highlight_type', [
			'label' => 'Highlight Source',
			'type' => Controls_Manager::SELECT,
			'default' => 'svg',
			'options' => [
				'image' => 'Image Upload',
				'svg' => 'Raw SVG Code',
			],
		]);

		$this->add_control('highlight_svg', [
			'label' => 'Paste SVG Code',
			'type' => Controls_Manager::TEXTAREA,
			'condition' => ['highlight_type' => 'svg'],
		]);

		$this->add_control('description', [
			'label' => 'Description',
			'type' => Controls_Manager::TEXTAREA,
			'default' => 'Find guides organized by your current business challenge',
			'dynamic' => ['active' => true],
		]);

		$this->end_controls_section();

		// --- TAB 2: STYLE (Core Structural Logic) ---
		$this->start_controls_section('style_reset', ['label' => 'Layout Panel', 'tab' => Controls_Manager::TAB_STYLE]);

		// Replicating style.css structural "bones" for the Brush Layer
		$this->add_control('heading_structural_reset', [
			'type' => Controls_Manager::HIDDEN,
			'default' => 'reset',
			'selectors' => [
				'{{WRAPPER}} .cora_adv_heading_title' => 'margin: 0; line-height: 1.2; position: relative; z-index: 1;',
				'{{WRAPPER}} .cora_head_highlight_wrap' => 'position: relative; display: inline-block; z-index: 1;',
				'{{WRAPPER}} .cora_head_highlight' => 'position: relative; z-index: 2;',
				'{{WRAPPER}} .cora_brush_layer' => 'position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 1; pointer-events: none; display: flex; align-items: center; justify-content: center;',
				'{{WRAPPER}} .cora_brush_layer svg' => 'width: 100%; height: auto; display: block; overflow: visible;',
			 
			],
		]);

		$this->end_controls_section();

		// Brush Customization Engine
		$this->start_controls_section('section_brush_style', ['label' => 'Brush Highlight', 'tab' => Controls_Manager::TAB_STYLE]);

		$this->add_responsive_control('brush_width', [
			'label' => 'Brush Width (%)',
			'type' => Controls_Manager::SLIDER,
			'default' => ['size' => 110],
			'range' => ['px' => ['min' => 50, 'max' => 200]],
			'selectors' => ['{{WRAPPER}} .cora_brush_layer' => 'width: {{SIZE}}%;'],
		]);

		$this->add_control('brush_color', [
			'label' => 'Brush Fill',
			'type' => Controls_Manager::COLOR,
			'selectors' => ['{{WRAPPER}} .cora_brush_layer svg path' => 'fill: {{VALUE}};'],
		]);

		$this->end_controls_section();

		// Typography Engines
		$this->register_text_styling_controls('heading_typo', 'Heading Typography', '{{WRAPPER}} .cora_adv_heading_title');
		$this->register_text_styling_controls('desc_typo', 'Description Typography', '{{WRAPPER}} .cora_adv_desc');

		// --- TAB 3: GLOBAL (Design Matrix) ---
		$this->register_global_design_controls('.cora_adv_heading_wrapper');
		$this->register_layout_geometry('.cora_adv_heading_wrapper'); // Zero-Margin Authority
		$this->register_surface_styles('.cora_adv_heading_wrapper');
		$this->register_common_spatial_controls();
		$this->register_alignment_controls('heading_align', '.cora_adv_heading_wrapper', '.cora_adv_heading_title, .cora_adv_desc');

		// --- TAB 4: ADVANCED (Interactions) ---
		$this->register_interaction_motion();
		$this->register_transform_engine('.cora_head_highlight_wrap'); // Makes the brush/text rotate together
	}

	protected function render()
	{
		$settings = $this->get_settings_for_display();
		?>
		<div class="cora-unit-container cora_adv_heading_wrapper">
			<h2 class="cora_adv_heading_title">
				<span class="cora_head_main"><?php echo esc_html($settings['text_before']); ?></span>
				<span class="cora_head_highlight_wrap">
					<span class="cora_head_highlight"><?php echo esc_html($settings['text_highlight']); ?></span>
					<div class="cora_brush_layer">
						<?php echo $settings['highlight_svg']; ?>
					</div>
				</span>
			</h2>
			<?php if (!empty($settings['description'])): ?>
				<div class="cora_adv_desc"><?php echo esc_html($settings['description']); ?></div>
			<?php endif; ?>
		</div>
		<?php
	}
}