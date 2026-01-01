<?php
namespace Cora_Builder\Components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

if (!defined('ABSPATH'))
    exit;

class Cora_Comparison_Table extends Base_Widget
{
    public function get_name() { return 'cora_comparison_table'; }
    public function get_title() { return 'Cora Comparison Table'; }
    public function get_icon() { return 'eicon-table'; }

    // Load Fonts
    public function get_style_depends() {
        wp_register_style('cora-fonts', 'https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap', [], null);
        return ['cora-fonts'];
    }

    protected function register_controls()
    {
        // --- CONTENT ---
        $this->start_controls_section('section_content', ['label' => 'Feature Matrix']);

        $repeater = new Repeater();

        $repeater->add_control('row_type', [
            'label' => 'Row Type',
            'type' => Controls_Manager::SELECT,
            'default' => 'feature',
            'options' => [
                'category' => 'Category Header',
                'feature'  => 'Feature Row',
            ],
        ]);

        $repeater->add_control('title', [
            'label' => 'Title / Feature Name',
            'type' => Controls_Manager::TEXT,
            'default' => 'Feature Name',
        ]);

        $repeater->add_control('subtitle', [
            'label' => 'Subtitle (Optional)',
            'type' => Controls_Manager::TEXT,
            'condition' => ['row_type' => 'feature'],
        ]);

        // Cora Column Data
        $repeater->add_control('cora_heading', ['label' => 'Cora Platform', 'type' => Controls_Manager::HEADING, 'separator' => 'before', 'condition' => ['row_type' => 'feature']]);
        
        $repeater->add_control('cora_status', [
            'label' => 'Status',
            'type' => Controls_Manager::SELECT,
            'default' => 'check',
            'options' => ['check' => 'Checkmark', 'pill' => 'Text Pill', 'none' => 'Empty'],
            'condition' => ['row_type' => 'feature'],
        ]);

        $repeater->add_control('cora_pill_text', [
            'label' => 'Pill Text',
            'type' => Controls_Manager::TEXT,
            'default' => 'Automated',
            'condition' => ['row_type' => 'feature', 'cora_status' => 'pill'],
        ]);

        // Traditional Column Data
        $repeater->add_control('trad_heading', ['label' => 'Traditional Hosting', 'type' => Controls_Manager::HEADING, 'separator' => 'before', 'condition' => ['row_type' => 'feature']]);

        $repeater->add_control('trad_status', [
            'label' => 'Status',
            'type' => Controls_Manager::SELECT,
            'default' => 'pill',
            'options' => ['check' => 'Checkmark', 'pill' => 'Text Pill', 'none' => 'Empty', 'cross' => 'Cross (X)'],
            'condition' => ['row_type' => 'feature'],
        ]);

        $repeater->add_control('trad_pill_text', [
            'label' => 'Pill Text',
            'type' => Controls_Manager::TEXT,
            'default' => 'Manual',
            'condition' => ['row_type' => 'feature', 'trad_status' => 'pill'],
        ]);

        $this->add_control('table_rows', [
            'label' => 'Table Rows',
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                [ 'row_type' => 'category', 'title' => 'Security & Reliability' ],
                [ 'row_type' => 'feature', 'title' => 'Security & Daily Backups', 'subtitle' => 'Zero-config protection', 'cora_status' => 'pill', 'cora_pill_text' => 'Automated', 'trad_status' => 'pill', 'trad_pill_text' => 'Manual' ],
                [ 'row_type' => 'feature', 'title' => 'Free SSL Certificates', 'subtitle' => 'Auto-renewed', 'cora_status' => 'check', 'trad_status' => 'pill', 'trad_pill_text' => 'Optional' ],
            ],
            'title_field' => '{{{ title }}}',
        ]);

        $this->end_controls_section();

        // --- STYLE ---
        $this->start_controls_section('section_style', ['label' => 'Design', 'tab' => Controls_Manager::TAB_STYLE]);

        $this->add_control('header_bg_color', [
            'label' => 'Header Background (Cora)',
            'type' => Controls_Manager::COLOR,
            'default' => '#0F172A',
            'selectors' => ['{{WRAPPER}} .cora-col-header' => 'background-color: {{VALUE}};'],
        ]);

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $id = $this->get_id();
        ?>

        <style>
            /* Main Wrapper */
            .cora-table-container-<?php echo $id; ?> {
                width: 100%;
                border-radius: 24px;
                border: 1px solid #E2E8F0;
                overflow: hidden; /* Rounded corners for the shell */
                background: #FFFFFF;
                font-family: "Inter", sans-serif;
            }

            /* Scrollable Inner Area */
            .cora-root-<?php echo $id; ?> {
                width: 100%;
                overflow-x: hidden; /* Default: No scroll on desktop */
            }

            /* --- Grid Architecture --- */
            .cora-root-<?php echo $id; ?> .cora-grid-row {
                display: grid;
                grid-template-columns: 2fr 1fr 1fr; /* Desktop: Feature | Cora | Traditional */
                border-bottom: 1px solid #F1F5F9;
                min-width: 100%; /* Ensures it fills desktop */
            }

            .cora-root-<?php echo $id; ?> .cora-grid-row:last-child {
                border-bottom: none;
            }

            /* --- Table Header --- */
            .cora-root-<?php echo $id; ?> .cora-grid-header {
                font-family: "Fredoka", sans-serif;
                position: sticky;
                top: 0;
                z-index: 10;
            }

            .cora-root-<?php echo $id; ?> .header-cell {
                padding: 24px;
                text-align: center;
                display: flex;
                flex-direction: column;
                justify-content: center;
                gap: 4px;
            }

            .cora-root-<?php echo $id; ?> .header-empty {
                background: #FFFFFF;
                border-right: 1px solid #F1F5F9;
            }

            .cora-root-<?php echo $id; ?> .cora-col-header {
                background: #0F172A;
                color: #FFFFFF;
            }

            .cora-root-<?php echo $id; ?> .trad-col-header {
                background: #F8FAFC;
                color: #64748B;
                border-left: 1px solid #E2E8F0;
            }

            .cora-root-<?php echo $id; ?> .brand-name {
                font-size: 18px;
                font-weight: 700;
            }
            .cora-root-<?php echo $id; ?> .brand-sub {
                font-size: 11px;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                opacity: 0.8;
                font-weight: 600;
            }

            /* --- Category Row --- */
            .cora-root-<?php echo $id; ?> .row-category {
                background: #F8FAFC;
                /* Sticky logic applied below for mobile */
            }
            .cora-root-<?php echo $id; ?> .cat-cell {
                grid-column: 1 / -1; /* Span all columns */
                padding: 16px 32px;
                display: flex;
                align-items: center;
                gap: 12px;
                font-size: 13px;
                font-weight: 800;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                color: #0F172A;
            }
            .cora-root-<?php echo $id; ?> .cat-icon {
                font-size: 16px;
                color: #0F172A;
            }

            /* --- Feature Row --- */
            .cora-root-<?php echo $id; ?> .row-feature {
                transition: background 0.2s;
            }
            .cora-root-<?php echo $id; ?> .row-feature:hover {
                background: #FAFAFA;
            }

            .cora-root-<?php echo $id; ?> .cell-feature {
                padding: 20px 32px;
                display: flex;
                flex-direction: column;
                justify-content: center;
                border-right: 1px solid #F1F5F9;
                background: #FFFFFF; /* Needed for sticky opacity */
            }

            .cora-root-<?php echo $id; ?> .f-title {
                font-weight: 600;
                font-size: 15px;
                color: #0F172A;
            }
            .cora-root-<?php echo $id; ?> .f-sub {
                font-size: 13px;
                color: #64748B;
                margin-top: 4px;
            }

            .cora-root-<?php echo $id; ?> .cell-status {
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 20px;
            }

            .cora-root-<?php echo $id; ?> .cell-trad {
                border-left: 1px solid #F1F5F9;
            }

            /* --- Status Elements --- */
            .cora-root-<?php echo $id; ?> .check-box {
                width: 28px;
                height: 28px;
                border-radius: 8px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 12px;
            }
            .cora-root-<?php echo $id; ?> .check-black { background: #0F172A; color: #FFFFFF; }
            .cora-root-<?php echo $id; ?> .check-gray { background: #F1F5F9; color: #94A3B8; }

            .cora-root-<?php echo $id; ?> .status-pill {
                padding: 6px 14px;
                border-radius: 100px;
                font-size: 12px;
                font-weight: 600;
                white-space: nowrap;
            }
            .cora-root-<?php echo $id; ?> .pill-auto { background: #F1F5F9; color: #0F172A; border: 1px solid #E2E8F0; }
            .cora-root-<?php echo $id; ?> .pill-manual { background: #FFFFFF; color: #64748B; border: 1px solid #E2E8F0; }
            .cora-root-<?php echo $id; ?> .cross-icon { color: #CBD5E1; font-size: 18px; }

            /* --- MOBILE OPTIMIZATION (Scroll + Sticky) --- */
            @media (max-width: 768px) {
                .cora-root-<?php echo $id; ?> {
                    overflow-x: auto; /* Enable Horizontal Scroll */
                    -webkit-overflow-scrolling: touch; /* Smooth scroll */
                    /* Padding bottom to ensure scrollbar doesn't hide content if visible */
                    padding-bottom: 2px;
                }

                .cora-root-<?php echo $id; ?> .cora-grid-row {
                    min-width: 600px; /* Force table to be wider than screen */
                    grid-template-columns: 200px 1fr 1fr; /* Fixed width for sticky feature col */
                }

                /* STICKY COLUMN LOGIC */
                .cora-root-<?php echo $id; ?> .header-empty,
                .cora-root-<?php echo $id; ?> .cell-feature {
                    position: sticky;
                    left: 0;
                    z-index: 5;
                    border-right: 1px solid #E2E8F0;
                    /* Add subtle shadow to indicate scrollability */
                    box-shadow: 4px 0 12px rgba(0,0,0,0.03); 
                }
                
                /* Ensure Category Row spans full width correctly even with scroll */
                .cora-root-<?php echo $id; ?> .row-category {
                    position: sticky;
                    left: 0; 
                    min-width: 600px; 
                }
                
                /* Adjust padding for mobile */
                .cora-root-<?php echo $id; ?> .cell-feature {
                    padding: 16px;
                }
                .cora-root-<?php echo $id; ?> .f-title {
                    font-size: 14px;
                }
            }
        </style>

        <div class="cora-unit-container cora-table-container-<?php echo $id; ?>">
            <div class="cora-root-<?php echo $id; ?>">
                
                <div class="cora-grid-row cora-grid-header">
                    <div class="header-cell header-empty"></div>
                    
                    <div class="header-cell cora-col-header">
                        <span class="brand-name">Cora</span>
                        <span class="brand-sub">MANAGED</span>
                    </div>

                    <div class="header-cell trad-col-header">
                        <span class="brand-name">Traditional</span>
                        <span class="brand-sub">STANDARD</span>
                    </div>
                </div>

                <?php foreach ($settings['table_rows'] as $row) : ?>
                    
                    <?php if ( 'category' === $row['row_type'] ) : ?>
                        <div class="cora-grid-row row-category">
                            <div class="cat-cell">
                                <i class="fas fa-layer-group cat-icon"></i>
                                <?php echo esc_html($row['title']); ?>
                            </div>
                        </div>

                    <?php else : ?>
                        <div class="cora-grid-row row-feature">
                            
                            <div class="cell-feature">
                                <span class="f-title"><?php echo esc_html($row['title']); ?></span>
                                <?php if (!empty($row['subtitle'])) : ?>
                                    <span class="f-sub"><?php echo esc_html($row['subtitle']); ?></span>
                                <?php endif; ?>
                            </div>

                            <div class="cell-status">
                                <?php if ($row['cora_status'] === 'check') : ?>
                                    <div class="check-box check-black"><i class="fas fa-check"></i></div>
                                <?php elseif ($row['cora_status'] === 'pill') : ?>
                                    <span class="status-pill pill-auto">● <?php echo esc_html($row['cora_pill_text']); ?></span>
                                <?php endif; ?>
                            </div>

                            <div class="cell-status cell-trad">
                                <?php if ($row['trad_status'] === 'check') : ?>
                                    <div class="check-box check-gray"><i class="fas fa-check"></i></div>
                                <?php elseif ($row['trad_status'] === 'pill') : ?>
                                    <span class="status-pill pill-manual">⚠️ <?php echo esc_html($row['trad_pill_text']); ?></span>
                                <?php elseif ($row['trad_status'] === 'cross') : ?>
                                    <i class="fas fa-times cross-icon"></i>
                                <?php endif; ?>
                            </div>

                        </div>
                    <?php endif; ?>

                <?php endforeach; ?>

            </div>
        </div>
        <?php
    }
}