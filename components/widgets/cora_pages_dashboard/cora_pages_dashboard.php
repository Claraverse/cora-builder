<?php
namespace Cora_Builder\components;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Pages_Dashboard extends Widget_Base {

    public function get_name() { return 'cora_pages_dashboard'; }
    public function get_title() { return __( 'Cora Pages Dashboard', 'cora-builder' ); }
    public function get_icon() { return 'eicon-site-map'; }
    public function get_categories() { return [ 'cora-components' ]; }

    protected function register_controls() {
        
        $this->start_controls_section('query_section', ['label' => 'Query Settings']);

        $this->add_control('posts_per_page', [
            'label' => 'Limit Pages',
            'type' => Controls_Manager::NUMBER,
            'default' => 12,
        ]);

        $this->end_controls_section();

        $this->start_controls_section('style_section', ['label' => 'Dashboard Style', 'tab' => Controls_Manager::TAB_STYLE]);

        $this->add_control('accent_color', [
            'label' => 'Accent Color',
            'type' => Controls_Manager::COLOR,
            'default' => '#15803d', // Matching your brand green
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        // Fetch Pages similar to your Admin list
        $args = [
            'post_type'      => 'page',
            'posts_per_page' => $settings['posts_per_page'],
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC',
        ];

        $query = new \WP_Query($args);
        ?>

        <style>
            .cora-dash-container {
                width: 100%;
                font-family: 'Inter', sans-serif;
            }

            /* Dashboard Header */
            .cora-dash-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 40px;
            }
            .cora-dash-header h1 {
                font-size: 32px;
                font-weight: 800;
                color: #0f172a;
                margin: 0;
            }
            .cora-add-page-btn {
                background: <?php echo esc_attr($settings['accent_color']); ?>;
                color: #fff;
                padding: 12px 24px;
                border-radius: 12px;
                text-decoration: none;
                font-weight: 700;
                font-size: 14px;
                transition: transform 0.2s;
            }
            .cora-add-page-btn:hover { transform: translateY(-2px); }

            /* Grid Layout */
            .cora-page-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
                gap: 24px;
            }

            /* Modern Page Card */
            .cora-page-card {
                background: #ffffff;
                border: 1px solid #f1f5f9;
                border-radius: 24px;
                padding: 24px;
                position: relative;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                height: 100%;
            }
            .cora-page-card:hover {
                border-color: <?php echo esc_attr($settings['accent_color']); ?>44;
                box-shadow: 0 20px 40px rgba(0,0,0,0.04);
                transform: translateY(-4px);
            }

            .cora-page-card h3 {
                font-size: 18px;
                font-weight: 700;
                color: #1e293b;
                margin: 0 0 8px 0;
                line-height: 1.3;
            }

            .cora-page-meta {
                display: flex;
                align-items: center;
                gap: 12px;
                font-size: 13px;
                color: #64748b;
                margin-bottom: 24px;
            }

            .cora-page-status {
                background: #f1f5f9;
                color: #475569;
                padding: 4px 10px;
                border-radius: 6px;
                font-weight: 700;
                font-size: 11px;
                text-transform: uppercase;
            }

            /* Action Row */
            .cora-page-actions {
                display: flex;
                align-items: center;
                gap: 10px;
                border-top: 1px solid #f1f5f9;
                padding-top: 20px;
            }

            .cora-edit-btn {
                flex: 1;
                text-align: center;
                background: #f8fafc;
                color: #1e293b;
                padding: 10px;
                border-radius: 10px;
                text-decoration: none;
                font-size: 14px;
                font-weight: 600;
                transition: background 0.2s;
            }
            .cora-edit-btn:hover { background: #f1f5f9; }

            .cora-preview-btn {
                width: 40px;
                height: 40px;
                display: flex;
                align-items: center;
                justify-content: center;
                border: 1px solid #f1f5f9;
                border-radius: 10px;
                color: #64748b;
                text-decoration: none;
            }

            @media (max-width: 767px) {
                .cora-page-grid { grid-template-columns: 1fr; }
            }
        </style>

        <div class="cora-dash-container">
            <div class="cora-dash-header">
                <h1>All Pages</h1>
                <a href="<?php echo admin_url('post-new.php?post_type=page'); ?>" class="cora-add-page-btn">
                    <i class="fas fa-plus"></i> New Page
                </a>
            </div>

            <?php if ($query->have_posts()) : ?>
                <div class="cora-page-grid">
                    <?php while ($query->have_posts()) : $query->the_post(); 
                        $edit_link = get_edit_post_link();
                        $preview_link = get_permalink();
                    ?>
                        <div class="cora-page-card">
                            <div>
                                <span class="cora-page-status"><?php echo get_post_status(); ?></span>
                                <h3><?php the_title(); ?></h3>
                                <div class="cora-page-meta">
                                    <span><i class="far fa-calendar"></i> <?php echo get_the_date('M j, Y'); ?></span>
                                    <span><i class="far fa-user"></i> <?php the_author(); ?></span>
                                </div>
                            </div>

                            <div class="cora-page-actions">
                                <a href="<?php echo $edit_link; ?>" class="cora-edit-btn">
                                    <i class="fas fa-pen-nib"></i> Edit Page
                                </a>
                                <a href="<?php echo $preview_link; ?>" target="_blank" class="cora-preview-btn">
                                    <i class="fas fa-external-link-alt"></i>
                                </a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php wp_reset_postdata(); endif; ?>
        </div>

        <?php
    }
}