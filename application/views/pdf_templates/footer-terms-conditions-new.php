<div style="font-family:'Segoe UI', Verdana, Arial, sans-serif;
font-size:10px; color:#7f7f7f; line-height:1.25; margin:0; padding:0;">
<style>
    /* Force everything to stay grey */
    div, p, span, strong, em, b {
        color: #7f7f7f !important;
    }

    /* Make headings softer (not heavy black) */
    strong {
        font-weight: 600; /* use 500 if you want even lighter */
    }

    em {
        font-style: italic;
        color: #7f7f7f;
    }
</style>
<div style="text-align:center; font-size:13px; font-weight:600;margin-top:20px">
    <strong> UAE Retail Terms and Conditions</strong><br>
   For Sale, Manufacture and Installation
</div><br>


These Terms and Conditions govern the supply, manufacture, delivery, and installation of all blinds, curtains,
shutters, and related products (“Products”) supplied by the Company. By placing an order, making any payment,
or permitting installation to proceed, the Customer confirms that they have read, understood, and accepted these
Terms and Conditions in full.<br><br>

<strong>1. DEFINITIONS</strong><br>
• Company: The supplier and installer of the Products.<br>
• Customer: The individual or entity placing the order for Products.<br>
• Order: A request by the Customer for Products to be manufactured and/or installed.<br>
• Accepted Order: An Order that has been confirmed in accordance with Clause 2.<br>
• Products: All made-to-measure or standard blinds, curtains, shutters, motors, and accessories supplied by the
Company.<br>
• Installation: The fitting of Products at the Customer’s premises.<br><br>

<strong>2. ACCEPTED ORDER</strong><br>
An Order shall be deemed accepted and legally binding when any one of the following occurs:<br>
• The Customer signs the Order form, quotation, or digital confirmation;<br>
• The Customer makes a deposit or any part payment;<br>
• The Company commences manufacture of the Products;<br>
• The Customer allows installation to proceed.<br>
Once an Order is accepted, it cannot be cancelled or amended for any reason, as all Products are made to the
Customer’s specific requirements unless otherwise stated in writing.<br><br>

<strong>3. PRODUCT SPECIFICATIONS AND CUSTOMER ACKNOWLEDGEMENT</strong><br>
The Customer confirms that:<br>
• They have fully understood all details relating to the Products, specifications, measurements, operation, and
installation requirements.<br>
• The Products will be manufactured strictly in accordance with the specifications confirmed in the Accepted
Order and any accompanying notes.<br>
• The Products will be installed at the fitter’s professional discretion unless alternative fixing instructions are
expressly stated in writing prior to installation.<br><br>

<strong>4. ORDER ACCURACY AND CUSTOMER RESPONSIBILITY</strong><br>
• It is the sole responsibility of the Customer to ensure that all details, measurements, product selections, colours,
controls, and specifications are correct before the Order is accepted.<br>
• The Company shall not be liable for errors resulting from incorrect or incomplete information supplied by the
Customer.<br>
• Orders cannot be cancelled, refunded, or amended once accepted.<br><br>

<strong>5. INSTALLATION RISKS AND PROPERTY CONDITION</strong><br>
The Customer acknowledges and accepts that:<br>
• It is not possible to inspect the condition or quality of paint, plaster, tiles, concrete, or other substrates prior to
drilling, and such materials may crack, chip, flake, or fail during installation.<br>
• The Company cannot see behind walls, ceilings, or fixing surfaces. Any damage caused by hidden pipes,
cables, wiring, steelwork, or structural elements shall be the Customer’s responsibility.<br>
• The Company shall not be liable for consequential or cosmetic damage arising from installation where
reasonable care has been exercised.<br><br>

<strong>6. PRESENCE DURING INSTALLATION</strong><br>
• The Customer agrees to be present at the premises on the day of installation to inspect and sign off the
completed work.<br>
• If the Customer is not present at the time of installation, the installation shall be deemed satisfactory and fully
accepted, with no right of redress.<br><br>

<strong>7. FITTING DATES AND ACCESS</strong><br>
• Any fitting date provided is an estimate only and may change due to supplier delays, manufacturing lead times,
access issues, or high demand.<br>
• The Customer must ensure clear and safe access to the installation area. Delays caused by lack of access,
unfinished building works, or obstruction may result in additional charges.<br><br>

<strong>8. ACCEPTANCE OF INSTALLATION</strong><br>
• Upon completion, the Customer will be asked to sign an installation acceptance confirming the work has been
completed satisfactorily.<br>
• In the absence of a signed acceptance, the installation shall be deemed satisfactory and accepted in full.<br><br>

<strong>9. PAYMENT TERMS AND BALANCE DUE</strong><br>
• The full balance is payable either prior to or on the day of installation.<br>
• Failure to settle the balance in full entitles the Company to withhold or remove the Products until payment is
received.<br>
• Products will not be left on-site without full payment.<br>
• Additional charges may apply for failed payments, missed appointments, refits, or return visits.<br><br>

<strong>10. RETENTION OF TITLE</strong><br>
All Products remain the sole property of the Company until payment of all sums due under the invoice has been
received in full.<br><br>

<strong>11. REFUNDS AND CANCELLATIONS</strong><br>
• All made-to-measure Products are bespoke and manufactured specifically for the Customer.<br>
• Refunds, returns, or exchanges are not possible once an Order has been accepted.<br>
• These Terms do not affect the Customer’s statutory rights.<br><br>

<strong>12. WARRANTY – GENERAL</strong><br>
<strong>12.1 Signature Range</strong><br>
Signature blinds and curtains are covered by a Lifetime Warranty (where applicable). To qualify:<br>
• The Product name must include the word “Signature”;<br>
• The Product must be registered online within 30 days of ordering;<br>
• The damaged Product must be returned with the original invoice or receipt.<br>
A complimentary home repair service is included for the first year. Call-out charges apply thereafter.<br><br>

<strong>12.2 Standard Custom-Made Products</strong><br>
All other custom-made blinds and curtains are warranted for 12 months against manufacturing defects in materials,
mechanisms, and components. Sewing involves both hand and machine processes; stitching and hemlines will not
be perfectly straight and this does not constitute a defect.<br><br>

<strong>12.3 Motors</strong><br>
Motor warranties are provided by the manufacturer. Warranty periods commence from the date of purchase.<br><br>

<strong>12.4 Shutters</strong><br>
Shutters are guaranteed for three (3) years under normal conditions of use. Natural timber and paint variations are
considered acceptable.<br><br>

<strong>12.5 Environmental Conditions</strong><br>
Certain warranties may be void depending on environmental exposure and installation location.<br><br>

<strong>16. FINAL ACKNOWLEDGEMENT</strong><br>
By proceeding with payment, signing the Order, or allowing installation to commence, the Customer confirms
acceptance of these Terms and Conditions in full.<br><br>

<em>Lifetime Warranty full terms and conditions are available upon request where applicable.</em>

</div>
<div style="margin-top:40px; page-break-inside: avoid;">

    <table class="signature-section" width="100%">
        <tr>

            <td width="50%"></td>

            <td width="50%" align="center">

                <?php
                $signature = trim($quotation->signature);
                $signaturePath = FCPATH . 'uploads/quotation/' . $signature;
                $signatureUrl  = SITE_URL('uploads/quotation/' . $signature);

                if (!empty($signature) && file_exists($signaturePath)):
                ?>

                    <img src="<?= $signatureUrl ?>" alt="signature" style="max-width:150px;height:auto;">

                <?php else: ?>

                    <div class="signature-box"></div>

                <?php endif; ?>

                <div class="signature-label">
                    Signature
                </div>

            </td>

        </tr>
    </table>

</div>