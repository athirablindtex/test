<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title><?= $quotation->confirm == 1 ? "Tax Invoice" : "Quotation" ?></title>

    <style>
        /* Google Sans fallback for PDF */


        /* Base */
        body {
            margin: 0;
            font-family: 'Google Sans';
            font-size: 11px;
            color: #333;
          
        }

        body,
        table,
        td,
        th,
        div,
        span,
        strong,
        em {
font-family: 'Segoe UI', Verdana, Arial, sans-serif;

            font-size: 12px;
            color: #333;
        }


        .container {
            max-width: 800px;
            margin: auto;
            padding: 24px;
            border: 1px solid #eee;
        }

        /* Tables */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        td,
        th {
            padding: 6px;
            vertical-align: top;
        }

        /* Title */
        .invoice-title {
            text-align: center;
            font-size: 18px;
            font-weight: 600;
            border-top: 2px solid #222;
            border-bottom: 2px solid #222;
            padding: 8px 0;
            margin: 18px 0;
            letter-spacing: 0.5px;
        }

        .item-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        .item-table th {
            background: #f5f5f5;
            font-weight: bold;
            padding: 10px;
            border-bottom: 2px solid #ddd;
        }

        .item-table td {
            padding: 10px;
            vertical-align: top;
            border-bottom: 1px solid #eee;
        }

        .item-table td.small {
            line-height: 1.6;
        }

        .item-table .text-center {
            text-align: center;
            vertical-align: top;
        }

        .item-table .text-right {
            text-align: right;
            vertical-align: top;
        }

        .text-left {
            text-align: left;
            vertical-align: top;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .small {
            font-size: 12px;
        }

        /* Remarks */
        .remarks {
            margin-top: 8px;
            font-size: 11px;
            line-height: 1.5;
        }

        /* Bank + totals wrapper */
        .bank-total-wrapper {
            margin-top: 10px;
            border-top: 2px solid #222;
            padding-top: 8px;
        }

        /* Totals table */
        .totals-table td {
            padding: 3px 4px;
            font-size: 11px;
        }

        /* Signature */
        .signature-box {
            width: 110px;
            height: 40px;
        }
    </style>
</head>

<body>
    <div class="container">

        <!-- HEADER -->
        <table>
            <tr>
                <td style="width:50%;">
                    <?php if (!empty($company['company_image'])): ?>
                        <img src="<?= SITE_URL() . '/uploads/users/' . $company['company_image'] ?>" style="max-width:180px;">
                    <?php endif; ?>
                </td>

                <td style="width:50%; text-align:right;">
                    <strong><?= $company['company_name'] ?></strong><br>
                    <?= $company['company_address'] ?><br>
                    <?= $company['company_phone'] ?><br>
                    <?= $company['company_email'] ?>
                </td>
            </tr>
        </table>

        <div class="invoice-title">
            <?= $quotation->confirm == 1 ? 'TAX INVOICE' : 'QUOTATION' ?>
        </div>

        <!-- BILL TO -->
        <table>
            <tr>
                <td style="width:50%;">
                    <strong style="font-size:14px;">Billed To</strong><br>
               <?php if (!empty($quotation->customer_name)): ?>
                <?= @$quotation->customer_name ?><br>
                <?php endif; ?>

                <?php if (!empty($customer['address'])): ?>
                <?= @$customer['address'] ?><br>
                <?php endif; ?>

                <?php if (!empty($quotation->customer_phone)): ?>
                <?= @$quotation->customer_phone ?><br>
                <?php endif; ?>

                <?php if (!empty($quotation->customerEmail)): ?>
                <?= @$quotation->customerEmail ?><br>
                <?php endif; ?>

                <?php if (!empty($quotation->customerTrn)): ?>
                <strong>TRN No:</strong> <?= @$quotation->customerTrn ?>
                <?php endif; ?>
                </td>

                <td style="width:50%; text-align:right;">
                    <b>Status:</b> <?= $quotation->confirm ? $quotation->status : 'Pending' ?><br>
                    <b>Invoice No:</b> <?= $quotation->invoiceno ?><br>
                    <b>Date:</b> <?= $quotation->updated_date ?: $quotation->created_date ?><br>
                    <b>Salesperson:</b> <?= $quotation->sales_person_name ?>
                </td>
            </tr>
        </table>

        <!-- ITEMS -->
        <table class="item-table" style="margin-top:18px;">
            <tr>
                <th class="text-center">#</th>

                <th class="text-left">Item & Description</th>
                <th class="text-center">Qty</th>
                <th class="text-right">Unit Price</th>
                <th class="text-right">Total (AED)</th>
            </tr>

            <?php
            $i = 1;
            foreach ($rooms as $room):
                foreach ($room['windows'] as $window):
                    $qty = (int)($window['quantity'] ?? 1);
                    $total = (float)($window['total'] ?? 0);
                    $unit = $qty ? $total / $qty : 0;
            ?>
                    <tr>
                        <td class="text-center"><?= $i++ ?></td>

                        <td class="small text-left">

                            <strong><?= $window['window_name'] ?></strong><br>
                            <?= $window['product_name'] ?>



                            <span style="display:block; margin-top:6px;margin-bottom:0px">
                                <?= $window['priceBandVersion'] ?>
                            </span>
                            <?php if (!empty($window['extras'])): ?>
                                <b>Extras</b><br>
                                <?php foreach ($window['extras'] as $extras): ?>
                                    <?= htmlspecialchars(preg_replace('/\(.*?\)/', '', $extras['extra_name'] ?? '')) ?>
                                    <?php if (!empty($extras['sub_extra_name'])): ?>
                                        : <?= htmlspecialchars($extras['sub_extra_name']) ?>
                                         <?php if (!empty($extras['quantity']) && $extras['quantity'] > 1): ?>
                                    , Qty: <?= htmlspecialchars($extras['quantity']) ?>
                                <?php endif; ?>
                                    <?php endif; ?><br>
                                <?php endforeach; ?>
                            <?php endif; ?>

                            <?php if ($window['noteOnline'] == 1): ?>
                                <span style="color:#0275d8;">
                                    Special Instructions: <?= $window['note'] ?>
                                </span>
                            <?php endif; ?>
                        </td>

                        <td class="text-center"><?= $qty ?></td>
                        <td class="text-right"><?= number_format($unit, 2) ?></td>
                        <td class="text-right"><?= number_format($total, 2) ?></td>
                    </tr>
            <?php endforeach;
            endforeach; ?>
        </table>

        <?php
        $net_amount = $quotation->total - $quotation->discount;
        $vat_amount = round(($net_amount * 5) / 100, 2);
        $total_payable = $net_amount + $vat_amount;
        $deposit = floatval($quotation->advance);
        $balance = $total_payable - $deposit;
        ?>

        <!-- REMARKS -->
        <?php if (!empty($quotation->remarks)): ?>
            <div class="remarks">
                <strong>Remarks:</strong><br>
                <em><?= $quotation->remarks ?></em>
            </div>
        <?php endif; ?>

        <!-- BANK + TOTALS -->
        <table class="bank-total-wrapper">
            <tr>
                <td style="width:50%; font-size:11px;">
                    <b>Bank Details</b><br>
                    Bank: <?= $bank_name ?><br>
                    Account Name: <?= $account_name ?><br>
                    Account No: <?= $account_no ?><br>
                    IBAN: <?= $iban ?><br>
                    Branch: <?= $bank_branch ?><br>
                    Swift: <?= $swift_code ?>
                </td>

                <td style="width:50%;">
                    <table class="totals-table">
                        <tr>
                            <td class="text-right">Total :</td>
                            <td class="text-right">AED <?= number_format($quotation->total, 2) ?></td>
                        </tr>

                        <?php if ($quotation->discount > 0): ?>
                            <tr style="color:#d9534f;">
                                <td class="text-right">Discount :</td>
                                <td class="text-right">– AED <?= number_format($quotation->discount, 2) ?></td>
                            </tr>
                        <?php endif; ?>

                        <tr>
                            <td class="text-right">Net :</td>
                            <td class="text-right">AED <?= number_format($net_amount, 2) ?></td>
                        </tr>
                        <tr>
                            <td class="text-right">VAT 5% :</td>
                            <td class="text-right">AED <?= number_format($vat_amount, 2) ?></td>
                        </tr>

                        <tr>
                            <td class="text-right"><b>Total Payable :</b></td>
                            <td class="text-right"><b>AED <?= number_format($total_payable, 2) ?></b></td>
                        </tr>

                        <?php if ($quotation->confirm && $deposit > 0): ?>
                            <tr>
                                <td style="text-align:right;">Deposit Paid (<?= @$quotation->payment_type ?>):</td>
                                <td class="text-right">AED <?= number_format($deposit, 2) ?></td>
                            </tr>
                            <tr>
                                <td class="text-right"><b>Balance :</b></td>
                                <td class="text-right"><b>AED <?= number_format($balance, 2) ?></b></td>
                            </tr>
                        <?php endif; ?>

                        <tr>
                            <?php if($quotation->signature) :?>
                            <td colspan="2" style="padding-top:10px; text-align:right;">
                                Signature:<br>
                                    <?php
                                    $signature = trim($quotation->signature);
                                    $signaturePath = FCPATH . 'uploads/quotation/' . $signature;
                                    $signatureUrl  = SITE_URL('uploads/quotation/' . $signature);

                                    if (!empty($signature) && file_exists($signaturePath)):
                                    ?>
                                    <img src="<?= $signatureUrl ?>" alt="signature" style="max-width:120px;height:auto;">
                                    <?php else: ?>
                                    <div class="signature-box"></div>
                                    <?php endif; ?>
                            </td>
                                <?php endif; ?>
                        </tr>

                    </table>
                </td>
            </tr>
        </table>

        <div style="margin-top:100px; font-size:11px;">
            <?= $footer_new ?>
        </div>

    </div>
</body>

</html>