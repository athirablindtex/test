<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">

    <style>
        body {
            font-family: helvetica;
            font-size: 12px;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }

        /* RIGHT INFO BOXES */

        .invoice-meta {
            display: inline-block;
            background: #d8f1f1;
            padding: 8px 18px;
            border-radius: 20px;
            font-size: 11px;
            white-space: nowrap;
        }

        /* TABLE */

        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        /* HEADER */

        .table-head th {
            background: #006f6b;
            color: #fff;
            padding: 10px;
            font-weight: 600;
        }

        /* ROWS */

        .item-row td {
            padding: 10px 8px;
            vertical-align: top;
        }

        /* ALIGNMENT */

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        /* DESCRIPTION */

        .desc {
            line-height: 1.7;
            word-wrap: break-word;
        }

        /* SUMMARY */

        .summary-table {
            border-top: 2px solid #000;
            margin-top: 10px;
        }

        .bank-details {
            font-size: 13px;
            line-height: 1.7;
        }

        .totals td {
            padding: 5px 0;
        }

        .total-payable td {
            border-top: 2px solid #000;
            padding-top: 6px;
            font-weight: bold;
        }

        /* REMARKS */

        .remarks {
            margin-top: 10px;
            padding: 8px;
            font-size: 12px;
            line-height: 1.7;
        }

        /* PAGE BREAK CONTROL */

        .invoice-table tr {
            page-break-inside: auto;
        }

        .invoice-table td {
            page-break-inside: auto;
        }

        thead {
            display: table-row-group;
        }

        .desc {
            page-break-inside: auto;
        }

        .summary-table {
            page-break-inside: avoid;
        }

        .table-head {
            page-break-after: avoid;
        }

        /* EXTRAS */

        .extras-row td {
            border-bottom: 1px solid #000;
            padding: 2px;
            line-height: 1.6;
        }
    </style>
</head>

<body>

    <div class="invoice-info">

        <!-- RIGHT INFO -->
        <div style="width:100%; margin-top:5px;">

            <!-- LEFT PILL -->
            <div style="width:50%;float:left;">
                <div style="
width:270px;
float:left;

background:#d8f1f1;
padding:10px 18px;
border-radius:20px;
font-size:12px;
white-space:nowrap;
margin-bottom:6px;
">

                    Invoice: <strong><?= htmlspecialchars($quotation->invoiceno) ?></strong><br>

                    Date: <strong>
                        <?= date('d/m/Y', strtotime(!empty($quotation->updated_date) ? $quotation->updated_date : $quotation->created_date)) ?>
                    </strong>
                </div>

            </div>
            <div style="width:50%;float:right;">
                <!-- RIGHT PILL -->
                <div style="
width:270x;
float:right;

background:#d8f1f1;
padding:10px 18px;
border-radius:20px;
font-size:12px;
white-space:nowrap;
margin-bottom:6px;
">

                    Order Status:
                    <strong><?= $quotation->confirm ? $quotation->status : 'Pending' ?></strong> <br>

                    Sales Person:
                    <strong><?= $quotation->sales_person_name ?></strong>
                </div>
            </div>

            <div style="clear:both;"></div>
        </div>

        <!-- CUSTOMER -->

        <table width="100%" style="margin-top:10px; margin-bottom:15px;">
            <tr>

                <!-- LEFT: FROM -->
                <td width="50%" align="left" style="font-size:13px; line-height:1.7; vertical-align:top;">
                      <strong><?= $quotation->confirm == 1 ? 'Invoice From:' : 'Quotation From:' ?></strong><br>

                    <strong>Blindtex DMCC</strong><br>
                    Unit G-02, Preatoni Tower<br>
                    JLT Cluster L – Dubai, UAE<br>
                    Phone: 04 564 8448<br>
                    Email: salesjlt@blindtex.com<br>
                    TRN : 10058266050
                </td>

                <!-- RIGHT: TO -->
        <td width="45%" align="right" style="font-size:13px; line-height:1.7; vertical-align:top; padding-left:10px;">
    
    <strong><?= $quotation->confirm == 1 ? 'Invoice To:' : 'Quotation To:' ?></strong><br>

    <?php if (!empty(trim($quotation->customer_name))): ?>
        <strong><?= strtoupper($quotation->customer_name) ?></strong><br>
    <?php endif; ?>

    <?php if (!empty($customer['address'])): ?>
        <?= $customer['address'] ?><br>
    <?php endif; ?>

    <?php if (!empty($quotation->customer_phone)): ?>
         Phone:   <?= $quotation->customer_phone ?><br>
    <?php endif; ?>

    <?php if (!empty($quotation->customerEmail)): ?>
        Email: <?= $quotation->customerEmail ?><br>
    <?php endif; ?>

    <?php if (!empty($quotation->customerTrn)): ?>
        TRN:<?= $quotation->customerTrn ?>
    <?php endif; ?>

</td>

            </tr>
        </table>


        <!-- ITEMS -->

        <table class="invoice-table">

            <tr class="table-head">
                <th width="5%">#</th>
                <th width="45%">Item Description</th>
                <th width="10%">Qty.</th>
                <th width="20%">Unit Price (<?= @$company['currency'] ?>)</th>
                <th width="20%">Total (<?= @$company['currency'] ?>)</th>
            </tr>

            <tbody>

                <?php
                $i = 1;

                foreach ($rooms as $room):

                    foreach ($room['windows'] as $window):

                        $qty   = (int)($window['quantity'] ?? 1);
                        $total = (float)($window['total'] ?? 0);
                        $unit  = $qty ? $total / $qty : 0;
                ?>

                        <tr class="item-row">

                            <td class="center"><?= $i++ ?></td>

                            <td class="desc">

                                <strong><?= @$window['window_name'] ?></strong><br>

                                Quantity: <?= @$window['quantity'] ?><br>

                                <?= $window['priceBandVersion'] ?><br>

                                <?= $window['product_name'] ?><br>

                            </td>

                            <td class="center"><?= $qty ?></td>

                            <td class="right"><?= number_format($unit, 2) ?></td>

                            <td class="right"><?= number_format($total, 2) ?></td>

                        </tr>


                        <?php if (!empty($window['extras'])): ?>

                            <tr class="extras-row">

                                <td></td>

                                <td colspan="4">

                                    <strong>Extras</strong><br>

                                    <?php foreach ($window['extras'] as $extras): ?>

                                        <?= preg_replace('/\(.*?\)/', '', $extras['extra_name'] ?? '') ?>

                                        <?= !empty($extras['sub_extra_name']) ? ': ' . $extras['sub_extra_name'] : '' ?>

                                        <?= !empty($extras['quantity']) && $extras['quantity'] > 1 ? ' , Qty: ' . $extras['quantity'] : '' ?>

                                        <br>

                                    <?php endforeach; ?>


                                    <?php if (@$window['noteOnline'] == 1): ?>

                                        <span style="color:#0275d8;">
                                            Special Instructions: <?= $window['note'] ?>
                                        </span>

                                    <?php endif; ?>

                                </td>

                            </tr>

                        <?php endif; ?>

                <?php endforeach;
                endforeach; ?>

            </tbody>

        </table>


        <!-- REMARKS -->

        <?php if (!empty($quotation->remarks) && ($quotation->is_remarks == 1)) : ?>

            <div class="remarks">
                <strong>Remarks:</strong> <?= $quotation->remarks ?>
            </div>

        <?php endif; ?>


        <?php

        $net_amount = $quotation->total - $quotation->discount;
        $vat_amount = round(($net_amount * @$company['vat']) / 100, 2);
        $total_payable = $net_amount + $vat_amount;

        $deposit = (float)$quotation->advance;
        $balance = $total_payable - $deposit;

        ?>


        <!-- SUMMARY -->

        <table class="summary-table" width="100%">

            <tr>

                <td width="60%" class="bank-details">

                    <strong>Bank Details</strong><br><br>

                    Bank: <?= $bank_name ?><br>
                    Account Name: <?= $account_name ?><br>
                    Account No: <?= $account_no ?><br>
                    IBAN: <?= $iban ?><br>
                    Branch: <?= $bank_branch ?><br>
                    Swift: <?= $swift_code ?>

                </td>


                <td width="40%" class="totals">

                    <table width="100%">

                        <tr>
                            <td>Total :</td>
                            <td class="right"><?= @$company['currency'] ?> <?= number_format($quotation->total, 2) ?></td>
                        </tr>

                        <?php if ($quotation->discount > 0): ?>

                            <tr>
                                <td>Discount (<?= round((@$quotation->discount / @$quotation->total) * 100, 2) ?>%) :</td>
                                <td class="right">- <?= @$company['currency'] ?> <?= number_format($quotation->discount, 2) ?></td>
                            </tr>

                        <?php endif; ?>

                        <tr>
                            <td>Net :</td>
                            <td class="right"><?= @$company['currency'] ?> <?= number_format($net_amount, 2) ?></td>
                        </tr>

                        <tr>
                            <td>VAT (<?= @$company['vat'] ?>%) :</td>
                            <td class="right"><?= @$company['currency'] ?> <?= number_format($vat_amount, 2) ?></td>
                        </tr>

                        <tr class="total-payable">
                            <td>Total Payable :</td>
                            <td class="right"><?= @$company['currency'] ?> <?= number_format($total_payable, 2) ?></td>
                        </tr>

                        <?php if ($quotation->confirm && $deposit > 0): ?>

                            <tr>
                                <td>Deposit Paid (<?= @$quotation->payment_type ?>):</td>
                                <td class="right"><?= @$company['currency'] ?> <?= number_format($deposit, 2) ?></td>
                            </tr>

                            <tr>
                                <td>Balance :</td>
                                <td class="right"><?= @$company['currency'] ?> <?= number_format($balance, 2) ?></td>
                            </tr>

                        <?php endif; ?>

                    </table>

                </td>

            </tr>

        </table>

        <!-- SIGNATURE -->

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

</body>

</html>