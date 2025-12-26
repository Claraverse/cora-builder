<?php
namespace Cora_Builder\components;

use Cora_Builder\Core\Base_Widget;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cora_Invoice_Builder extends Base_Widget {

    public function get_name() { return 'cora_invoice_builder'; }
    public function get_title() { return __( 'Cora Pro Invoice Builder', 'cora-builder' ); }

    protected function render() {
        ?>
        <div class="v5-invoice-page" id="v5-capture-area">
            
            <div class="v5-header">
                <div class="v5-brand-box">
                    <div class="v5-logo-container" onclick="document.getElementById('v5-logo-trigger').click()">
                        <img src="<?php echo Utils::get_placeholder_image_src(); ?>" id="v5-logo-preview">
                        <input type="file" id="v5-logo-trigger" style="display:none" onchange="v5HandleLogo(this)">
                        <span class="v5-logo-hint no-print">Change Logo</span>
                    </div>
                    <input type="text" class="v5-ghost-input v5-pan" value="PAN: FZTPB0174F">
                </div>
                <div class="v5-meta-box">
                    <h1 class="v5-accent">INVOICE</h1>
                    <div class="v5-meta-field">No: <input type="text" value="BBP64" class="v5-ghost-input"></div>
                    <div class="v5-meta-field">Date: <input type="text" value="25 Sept 2025" class="v5-ghost-input"></div>
                </div>
            </div>

            <table class="v5-table">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Rate (₹)</th>
                        <th>Qty</th>
                        <th>Total</th>
                        <th class="no-print"></th>
                    </tr>
                </thead>
                <tbody id="v5-item-root">
                    <tr class="v5-item-row">
                        <td><input type="text" class="v5-ghost-input" value="Framer Website Redesign"></td>
                        <td><input type="number" class="v5-ghost-input v5-rate" value="16999" oninput="v5RunCalc()"></td>
                        <td><input type="number" class="v5-ghost-input v5-qty" value="1" oninput="v5RunCalc()"></td>
                        <td class="v5-row-total">₹16,999.00</td>
                        <td class="no-print v5-row-actions">
                            <button onclick="v5Clone(this)" class="v5-icon-btn"><i class="far fa-copy"></i></button>
                            <button onclick="this.closest('tr').remove(); v5RunCalc();" class="v5-icon-btn del">×</button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <button onclick="v5AddNew()" class="v5-add-trigger no-print">+ Add New Service Line</button>

            <div class="v5-settlement-grid">
                <div class="v5-bank-info">
                    <h4 class="v5-accent">Bank Details:</h4>
                    <textarea class="v5-ghost-area">SBI | 43232305771&#10;IFSC: SBIN0001156&#10;Branch: Fatehabad</textarea>
                </div>
                <div class="v5-calc-panel">
                    <div class="v5-summary-row">Subtotal: <span id="v5-sub-val">₹0.00</span></div>
                    
                    <div class="v5-summary-row v5-advance-config">
                        <select id="v5-adv-mode" onchange="v5RunCalc()">
                            <option value="p">Advance (%)</option>
                            <option value="f">Advance (₹)</option>
                        </select>
                        <input type="number" id="v5-adv-num" value="50" oninput="v5RunCalc()" class="v5-ghost-input">
                    </div>

                    <div class="v5-summary-row v5-grand">
                        <strong>Total Payable Today:</strong>
                        <h2 id="v5-grand-val" class="v5-accent">₹0.00</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="v5-actions-bar no-print">
            <button onclick="v5Download()" class="v5-main-pdf-btn">
                <i class="fas fa-file-pdf"></i> Download PDF Invoice
            </button>
        </div>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
        <script>
            function v5HandleLogo(i) { if(i.files && i.files[0]) { var r = new FileReader(); r.onload = function(e) { document.getElementById('v5-logo-preview').src = e.target.result; }; r.readAsDataURL(i.files[0]); } }
            function v5AddNew() { const b = document.getElementById('v5-item-root'); const r = b.querySelector('.v5-item-row').cloneNode(true); r.querySelectorAll('input').forEach(i => i.value = (i.type === 'number' ? 0 : '')); r.querySelector('.v5-row-total').innerText = '₹0.00'; b.appendChild(r); }
            function v5Clone(btn) { const r = btn.closest('tr'); r.after(r.cloneNode(true)); v5RunCalc(); }
            function v5RunCalc() {
                let s = 0;
                document.querySelectorAll('.v5-item-row').forEach(r => {
                    const rt = parseFloat(r.querySelector('.v5-rate').value) || 0;
                    const q = parseFloat(r.querySelector('.v5-qty').value) || 0;
                    const t = rt * q;
                    r.querySelector('.v5-row-total').innerText = '₹' + t.toLocaleString('en-IN');
                    s += t;
                });
                document.getElementById('v5-sub-val').innerText = '₹' + s.toLocaleString('en-IN');
                const m = document.getElementById('v5-adv-mode').value;
                const v = parseFloat(document.getElementById('v5-adv-num').value) || 0;
                let f = (m === 'p') ? (s * (v / 100)) : v;
                document.getElementById('v5-grand-val').innerText = '₹' + f.toLocaleString('en-IN', {minimumFractionDigits: 2});
            }
            function v5Download() { html2pdf().from(document.getElementById('v5-capture-area')).set({ margin: 0.5, filename: 'Invoice.pdf', html2canvas: { scale: 3 } }).save(); }
            v5RunCalc();
        </script>
        <?php
    }
}