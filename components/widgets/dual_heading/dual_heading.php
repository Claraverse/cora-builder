<?php
namespace Cora_Builder\Components;

use Cora_Builder\Core\Base_Widget; // Extend our custom base
use Elementor\Controls_Manager;

if (!defined('ABSPATH')) {
	exit;
}

class Dual_Heading extends Base_Widget
{

	public function get_name()
	{
		return 'cora_dual_heading';
	}

	public function get_title()
	{
		return 'Cora Dual Heading';
	}

	public function get_icon()
	{
		return 'eicon-heading';
	}
	public function get_categories()
	{
		return ['cora_widgets']; // This matches the slug we created above
	}

	protected function register_controls()
	{

		$this->start_controls_section(
			'section_content',
			[
				'label' => 'Content',
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'text_first',
			[
				'label' => 'First Text',
				'type' => Controls_Manager::TEXT,
				'default' => 'Cora',
			]
		);

		$this->add_control(
			'text_second',
			[
				'label' => 'Second Text',
				'type' => Controls_Manager::TEXT,
				'default' => 'Builder',
			]
		);

		$this->end_controls_section();
	}

	protected function render()
	{
		$settings = $this->get_settings_for_display();
		?>
		<div class="cora_dual_heading_wrapper">
			<span class="cora_dh_part_1"><?php echo esc_html($settings['text_first']); ?></span>
			<span class="cora_dh_part_2"><?php echo esc_html($settings['text_second']); ?></span>
		</div>
		<?php
	}
}