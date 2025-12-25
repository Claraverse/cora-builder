<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if (!defined('ABSPATH'))
	exit;

class Cora_Pricing_Card extends Base_Widget
{

	public function get_name()
	{
		return 'cora_pricing_card';
	}
	public function get_title()
	{
		return __('Cora Pricing Card', 'cora-builder');
	}

	protected function register_controls()
	{

		// --- TAB: CONTENT - PLAN INFO ---
		$this->start_controls_section('content', ['label' => __('Plan Details', 'cora-builder')]);

		$this->add_control('is_recommended', ['label' => 'Mark as Recommended', 'type' => Controls_Manager::SWITCHER, 'default' => '']);
		$this->add_control('plan_name', ['label' => 'Plan Name', 'type' => Controls_Manager::TEXT, 'default' => 'Starter']);
		$this->add_control('plan_desc', ['label' => 'Description', 'type' => Controls_Manager::TEXT, 'default' => 'Great for first-time users.']);

		$this->add_control('old_price', ['label' => 'Strike Price', 'type' => Controls_Manager::TEXT, 'default' => '₹ 199.00']);
		$this->add_control('current_price', ['label' => 'Monthly Price', 'type' => Controls_Manager::TEXT, 'default' => '99.00']);
		$this->add_control('save_tag', ['label' => 'Discount Badge', 'type' => Controls_Manager::TEXT, 'default' => 'Save 80%']);

		$this->end_controls_section();

		// --- TAB: CONTENT - SPECS GRID ---
		$this->start_controls_section('specs', ['label' => __('Technical Specs', 'cora-builder')]);
		$this->add_control('storage', ['label' => 'Storage', 'type' => Controls_Manager::TEXT, 'default' => '10 GB NVMe']);
		$this->add_control('bandwidth', ['label' => 'Bandwidth', 'type' => Controls_Manager::TEXT, 'default' => '100 GB/mo']);
		$this->add_control('visits', ['label' => 'Visits', 'type' => Controls_Manager::TEXT, 'default' => '10K visits/mo']);
		$this->add_control('products', ['label' => 'Products', 'type' => Controls_Manager::TEXT, 'default' => 'Up to 100']);
		$this->end_controls_section();

		// Features Repeater
		$this->start_controls_section('features_sec', ['label' => __('Feature List', 'cora-builder')]);
		$repeater = new Repeater();
		$repeater->add_control('f_label', ['label' => 'Feature', 'type' => Controls_Manager::TEXT, 'default' => 'Managed WooCommerce']);
		$repeater->add_control('f_icon', ['label' => 'Icon Type', 'type' => Controls_Manager::SELECT, 'default' => 'bolt', 'options' => ['bolt' => 'Bolt Icon', 'check' => 'Check Icon']]);
		$this->add_control('features', ['label' => 'Features', 'type' => Controls_Manager::REPEATER, 'fields' => $repeater->get_controls()]);
		$this->end_controls_section();

		$this->register_common_spatial_controls();
	}

	protected function render()
	{
		$settings = $this->get_settings_for_display();
		$card_class = ('yes' === $settings['is_recommended']) ? 'pricing-card recommended' : 'pricing-card';
		?>
		<div class="cora-unit-container <?php echo $card_class; ?>">
			<?php if ('yes' === $settings['is_recommended']): ?>
				<div class="recommended-ribbon">RECOMMENDED</div>
			<?php endif; ?>

			<div class="pricing-header">
				<h3 class="plan-title"><?php echo esc_html($settings['plan_name']); ?></h3>
				<p class="plan-subtitle"><?php echo esc_html($settings['plan_desc']); ?></p>

				<div class="price-row">
					<div class="price-val">
						<span class="old-price"><?php echo esc_html($settings['old_price']); ?></span>
						<div class="main-price-group">
							<span class="curr">₹</span>
							<span class="val"><?php echo esc_html($settings['current_price']); ?></span>
							<span class="mo">/mo</span>
						</div>
					</div>
					<span class="save-badge"><?php echo esc_html($settings['save_tag']); ?></span>
				</div>
			</div>

			<a href="#" class="plan-cta">Choose plan</a>
			<p class="policy-sub">Cancel anytime. 100% Refund Policy*</p>

			<div class="specs-box">
				<div class="spec-unit"><span>STORAGE</span><strong><?php echo esc_html($settings['storage']); ?></strong></div>
				<div class="spec-unit"><span>BANDWIDTH</span><strong><?php echo esc_html($settings['bandwidth']); ?></strong>
				</div>
				<div class="spec-unit"><span>VISITS</span><strong><?php echo esc_html($settings['visits']); ?></strong></div>
				<div class="spec-unit"><span>PRODUCTS</span><strong><?php echo esc_html($settings['products']); ?></strong>
				</div>
			</div>

			<div class="feature-stack">
				<?php foreach ($settings['features'] as $f): ?>
					<div class="f-row">
						<div class="f-icon-box <?php echo $f['f_icon']; ?>">
							<?php echo ($f['f_icon'] == 'bolt') ? '<i class="fas fa-bolt"></i>' : '<i class="fas fa-check"></i>'; ?>
						</div>
						<span><?php echo esc_html($f['f_label']); ?></span>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}
}