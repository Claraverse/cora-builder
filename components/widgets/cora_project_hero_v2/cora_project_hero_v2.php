<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Project_Hero_V2 extends Base_Widget {

    public function get_name() { return 'cora_project_hero_v2'; }
    public function get_title() { return __( 'Cora Project Hero V2', 'cora-builder' ); }

    protected function register_controls() {
        
        $this->start_controls_section('content', [ 'label' => 'Identity' ]);
        $this->add_control('bg_img', [ 'label' => 'Hero Image', 'type' => Controls_Manager::MEDIA ]);
        $this->add_control('title', [ 'label' => 'Project Name', 'type' => Controls_Manager::TEXT, 'default' => 'Third Space London' ]);
        $this->add_control('excerpt', [ 'label' => 'Excerpt', 'type' => Controls_Manager::TEXTAREA, 'default' => 'Luxury fitness and wellness club...' ]);
        $this->add_control('tags', [ 'label' => 'Tags (Comma separated)', 'type' => Controls_Manager::TEXT, 'default' => 'Design, Dev, WordPress' ]);
        $this->end_controls_section();

        $this->start_controls_section('metrics', [ 'label' => 'Technical Specs' ]);
        $this->add_control('cost', [ 'label' => 'Cost', 'type' => Controls_Manager::TEXT, 'default' => '$1200' ]);
        $this->add_control('loc', [ 'label' => 'Location', 'type' => Controls_Manager::TEXT, 'default' => 'London' ]);
        $this->add_control('dur', [ 'label' => 'Duration', 'type' => Controls_Manager::TEXT, 'default' => '21 Days' ]);
        $this->add_control('service', [ 'label' => 'Service', 'type' => Controls_Manager::TEXT, 'default' => 'Restaurant Booster' ]);
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $tags = explode(',', $settings['tags']);
        ?>
        <div class="cora-hero-v2" style="background-image: url('<?php echo esc_url($settings['bg_img']['url']); ?>');">
            <div class="p-glass-bar">
                <div class="p-top-flex">
                    <div class="p-titles">
                        <h1 class="p-main-title"><?php echo esc_html($settings['title']); ?></h1>
                        <p class="p-subtext"><?php echo esc_html($settings['excerpt']); ?></p>
                    </div>
                    <div class="p-tag-hub">
                        <?php foreach($tags as $tag): ?>
                            <span class="p-pill"><?php echo esc_html(trim($tag)); ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="p-metrics">
                    <div class="p-m-unit"><span>Project Cost</span><strong><?php echo esc_html($settings['cost']); ?></strong></div>
                    <div class="p-m-unit"><span>Location</span><strong><?php echo esc_html($settings['loc']); ?></strong></div>
                    <div class="p-m-unit"><span>Duration</span><strong><?php echo esc_html($settings['dur']); ?></strong></div>
                    <div class="p-m-unit"><span>Service</span><strong><?php echo esc_html($settings['service']); ?></strong></div>
                </div>
            </div>
        </div>
        <?php
    }
}