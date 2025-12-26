<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;

if (!defined('ABSPATH'))
    exit;

class Cora_Invoice_Maker extends Base_Widget
{

    public function get_name()
    {
        return 'cora_invoice_maker';
    }
    public function get_title()
    {
        return __('Cora Invoice Maker', 'cora-builder');
    }

    protected function register_controls()
    {

        // --- TAB: CONTENT - BUSINESS IDENTITY [cite: 2, 3, 4, 5] ---
        $this->start_controls_section('identity', ['label' => 'Business Identity']);
        $this->add_control('logo', ['label' => 'Company Logo', 'type' => Controls_Manager::MEDIA, 'default' => ['url' => Utils::get_placeholder_image_src()]]);
        $this->add_control('pan_num', ['label' => 'PAN Number', 'type' => Controls_Manager::TEXT, 'default' => 'FZTPB0174F', 'dynamic' => ['active' => true]]);
        $this->add_control('contact_info', ['label' => 'Contact Details', 'type' => Controls_Manager::TEXTAREA, 'default' => "+91 9817059266\nsupport@claraverse.store"]);
        $this->end_controls_section();

        // --- TAB: CONTENT - INVOICE META [cite: 6, 11] ---
        $this->start_controls_section('meta', ['label' => 'Invoice Details']);
        $this->add_control('inv_number', ['label' => 'Invoice #', 'type' => Controls_Manager::TEXT, 'default' => 'BBP64']);
        $this->add_control('inv_date', ['label' => 'Invoice Date', 'type' => Controls_Manager::TEXT, 'default' => '25 Sept 2025']);
        $this->add_control('due_date', ['label' => 'Due Date', 'type' => Controls_Manager::TEXT, 'default' => '28 Sept 2025']);
        $this->end_controls_section();

        // --- TAB: CONTENT - LINE ITEMS [cite: 9] ---
        $this->start_controls_section('items_section', ['label' => 'Billable Items']);
        $repeater = new Repeater();
        $repeater->add_control('desc', ['label' => 'Item Description', 'type' => Controls_Manager::TEXT, 'default' => 'Framer Website Redesign']);
        $repeater->add_control('rate', ['label' => 'Rate (₹)', 'type' => Controls_Manager::NUMBER, 'default' => 12500]);
        $repeater->add_control('qty', ['label' => 'Qty', 'type' => Controls_Manager::NUMBER, 'default' => 1]);

        $this->add_control('items', [
            'label' => 'Line Items',
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [['desc' => 'Framer Website Redesign', 'rate' => 12500]],
        ]);
        $this->end_controls_section();

        // --- TAB: STYLE - BRANDING ---
        $this->start_controls_section('style_brand', ['label' => 'Branding', 'tab' => Controls_Manager::TAB_STYLE]);
        $this->add_control('accent_color', ['label' => 'Accent Color', 'type' => Controls_Manager::COLOR, 'default' => '#111827', 'selectors' => ['{{WRAPPER}} .invoice-accent' => 'color: {{VALUE}};']]);
        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="cora-invoice-capture" id="invoice-<?php echo $this->get_id(); ?>">
            <div class="inv-header">
                <div class="inv-brand">
                    <img src="<?php echo esc_url($settings['logo']['url']); ?>" class="inv-logo">
                    <p class="inv-pan">PAN: <?php echo esc_html($settings['pan_num']); ?></p>
                </div>
                <div class="inv-title-stack">
                    <h1 class="invoice-accent">INVOICE</h1>
                    <span class="inv-no">#<?php echo esc_html($settings['inv_number']); ?></span>
                </div>
            </div>

            <div class="inv-bill-grid">
                <div class="bill-col">
                    <h4 class="invoice-accent">Customer Details:</h4>
                    <p>Happy Media<br>divesh@stickyy.co</p>
                </div>
                <div class="bill-col inv-meta-col">
                    <p><strong>Date:</strong> <?php echo esc_html($settings['inv_date']); ?></p>
                    <p><strong>Due:</strong> <?php echo esc_html($settings['due_date']); ?></p>
                </div>
            </div>

            <table class="inv-table">
                <thead>
                    <tr>
                        <th>Item Description</th>
                        <th>Rate</th>
                        <th>Qty</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $subtotal = 0;
                    foreach ($settings['items'] as $item):
                        $amt = $item['rate'] * $item['qty'];
                        $subtotal += $amt;
                        ?>
                        <tr>
                            <td><?php echo esc_html($item['desc']); ?></td>
                            <td>₹<?php echo number_format($item['rate'], 2); ?></td>
                            <td><?php echo $item['qty']; ?></td>
                            <td>₹<?php echo number_format($amt, 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="inv-footer">
                <div class="pay-info">
                    <h4 class="invoice-accent">Bank Details:</h4>
                    <p>SBI | 43232305771<br>Fatehabad Branch</p>
                </div>
                <div class="inv-total-box">
                    <span>Total Payable Today</span>
                    <h2 class="invoice-accent">₹<?php echo number_format($subtotal, 2); ?></h2>
                </div>
            </div>
        </div>

        <div class="inv-actions">
            <button onclick="downloadCoraInvoice('invoice-<?php echo $this->get_id(); ?>')" class="cora-pdf-btn">
                <i class="fas fa-file-download"></i> Download PDF
            </button>
        </div>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
        <script>
            function downloadCoraInvoice(id) {
                const element = document.getElementById(id);
                html2pdf().from(element).set({
                    margin: 0.5,
                    filename: 'Invoice-<?php echo $settings['inv_number']; ?>.pdf',
                    image: { type: 'jpeg', quality: 0.98 },
                    html2canvas: { scale: 2 },
                    jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
                }).save();
            }
        </script>
        <?php
    }
}