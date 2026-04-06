<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Invoice Balance</title>
</head>

<body>
    <div style="max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; box-shadow: 0 0 10px rgba(0,0,0,.15);">
        <table style="width: 100%; line-height: inherit; text-align: left;">

            <!-- Header -->
            <tr class="top">
                <td colspan="3" style="padding: 5px; vertical-align: top;">
                    <table style="width: 100%; line-height: inherit; text-align: left;">
                        <tr>
                            <td class="title" style="padding: 5px; vertical-align: top; padding-bottom: 20px; font-size: 45px; line-height: 45px; color: #333; width:65%;">
                                <img src="<?php echo is_file('uploads/users/' . @$company['company_image']) ? base_url() . 'uploads/users/' . @$company['company_image'] : '' ?>"
                                     alt="Company Logo"
                                     style="max-width:300px; object-fit: contain; max-height:80px;"
                                     onerror="this.style.display='none'">
                            </td>
                            <td style="padding: 5px; vertical-align: top; text-align: left; padding-bottom: 20px; font-family: Arial, sans-serif; font-size: 14px; line-height: 1.6;">
                                <b>Status:
                                    <span style="background: #28a745; color: #fff; padding: 3px 8px; border-radius: 4px; font-size: 13px;">
                                        <?= @$quotation->status ?>
                                    </span>
                                </b><br>
                                <b>Ref:</b> <?= @$quotation->invoiceno ?><br>
                                <b>Date:</b> <?= $quotation->updated_date ?: $quotation->created_date ?><br>
                                <b>Salesperson:</b> <?= @$quotation->sales_person_name ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <!-- Invoice Title -->
            <tr class="inv">
                <td colspan="3" style="padding: 5px; vertical-align: top; border-bottom: 1px solid #ddd; border-top: 1px solid #ddd; font-weight: bold; text-align: center; letter-spacing: 2px;">
                    INVOICE
                </td>
            </tr>

            <!-- Company & Customer Info -->
            <tr class="information">
                <td colspan="3" style="padding: 5px; vertical-align: top;">
                    <table style="width: 100%; line-height: inherit; text-align: left;">
                        <tr>
                            <td style="width: 50%; vertical-align: top; padding-right: 20px;">
                                <b>Billed By</b><br>
                                <?= @$company['company_name'] ?><br>
                                <?= @$company['company_address'] ?><br>
                                Tel: <?= @$company['company_phone'] ?><br>
                                Mail: <?= @$company['company_email'] ?><br>
                            </td>
                            <td style="width: 50%; vertical-align: top; text-align: right; padding-left: 20px;">
                                <b>Billed To</b><br>
                                <?= @$quotation->customer_name ?><br>
                                <?= @$customer->address ?><br>
                                <?= @$quotation->customer_phone ?><br>
                                <?= @$quotation->customerEmail ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <!-- Product Headers -->
            <tr style="background: #eee; border-bottom: 1px solid #ddd; font-weight: bold;">
                <td style="padding: 5px; width: 50%;">Item</td>
                <td></td>
                <td style="padding: 5px;">Price</td>
            </tr>

            <!-- Product Rows -->
            <?php
            $win_count = 1;
            foreach ($rooms as $room) {
                foreach ($room['windows'] as $window) {
                    $trBorderStyle = ($win_count > 1) ? 'border-top: 1px solid #eee;' : '';
            ?>
            <tr style="<?= $trBorderStyle ?>">
                <td style="padding: 5px;">
                    <strong><?= $win_count ?>. <?= @$window['window_name'] ?></strong><br>
                    <div style="margin-left: 15px;">
                        Quantity: <?= @$window['quantity'] ?><br>
                        <?= @$window['priceBandVersion'] ?><br>
                        <?= @$window['product_name'] ?><br>

                        <?php if (!empty($window['extras'])): ?>
                            <span style="font-weight: bold;">Extras</span><br>
                            <?php foreach ($window['extras'] as $extras): ?>
                                <span style="font-size: 13px;">
                                    <?= htmlspecialchars(preg_replace('/\(.*?\)/', '', $extras['extra_name'] ?? '')) ?>
                                    <?php if (!empty($extras['sub_extra_name'])): ?>
                                        : <?= htmlspecialchars($extras['sub_extra_name']) ?>
                                        <?php if (!empty($extras['quantity']) && $extras['quantity'] > 1): ?>
                                            , Quantity: <?= htmlspecialchars($extras['quantity']) ?>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <?php if (!empty($extras['sub_sub_extra_name']) && $extras['sub_sub_extra_name'] !== 'null'): ?>
                                        : <?= htmlspecialchars($extras['sub_sub_extra_name']) ?>
                                    <?php endif; ?>
                                    <?php if (!empty($extras['sub_sub_sub_extra_name']) && $extras['sub_sub_sub_extra_name'] !== 'null'): ?>
                                        : <?= htmlspecialchars($extras['sub_sub_sub_extra_name']) ?>
                                    <?php endif; ?>
                                </span><br>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <?php if (@$window['noteOnline'] == 1): ?>
                            <span style="font-size: 13px; color: blue;">
                                Special Instructions: <?= @$window['note'] ?>.
                            </span>
                        <?php endif; ?>
                    </div>
                </td>
                <td></td>
                <td style="padding: 5px;">AED <?= @$window['total'] ?: '0' ?></td>
            </tr>
            <?php
                    $win_count++;
                }
            }
            ?>

            <!-- Remarks -->
            <tr>
                <td>Remarks: <?= @$quotation->remarks ?></td>
                <td></td>
                <td></td>
            </tr>

            <!-- Totals -->
            <tr>
                <td></td>
                <td style="text-align: right; font-weight: bold; border-top: 2px solid #eee; padding: 5px;">Total:</td>
                <td style="padding: 5px; font-weight: bold; border-top: 2px solid #eee;">
                    AED <?= round(@$quotation->total + $quotation->vat, 2) ?: 0 ?>
                </td>
            </tr>

            <?php if ($quotation->discount != 0) { ?>
            <tr>
                <td></td>
                <td style="text-align: right; font-weight: bold; border-top: 2px solid #eee; padding: 5px;">
                    Less <?= round(((@$quotation->discount / @$quotation->total) * 100), 2) . '%' ?> discount:
                </td>
                <td style="padding: 5px; font-weight: bold; border-top: 2px solid #eee;">
                    AED <?= @$quotation->discount ?: 0 ?>
                </td>
            </tr>
            <?php } ?>

            <tr>
                <td></td>
                <td style="text-align: right; font-weight: bold; padding: 5px;">Total Payable:</td>
                <td style="padding: 5px; font-weight: bold; border-top: 2px solid #eee;">
                    AED <?= @$quotation->sub_total ?: 0 ?>
                </td>
            </tr>

            <tr>
                <td></td>
                <td style="text-align: right; font-weight: bold; padding: 5px;">Deposit paid (<?= @$quotation->payment_type ?>):</td>
                <td style="padding: 5px; font-weight: bold;">AED <?= @$quotation->advance ?: 0 ?></td>
            </tr>

            <tr>
                <td></td>
                <td style="text-align: right; font-weight: bold; padding: 5px;">Balance Fully Paid (<?= @$quotation->balancePaymentType ?>):</td>
                <td style="padding: 5px; font-weight: bold;">AED <?= @$quotation->sub_total ?: 0 ?></td>
            </tr>

            <!-- VAT Details -->
            <?php if ($quotation->sub_total != 0): ?>
            <tr style="font-size: 15px;">
                <td colspan="3" style="padding-top: 20px;">
                    <b>
                        VAT <?= round((@$quotation->vat / (@$quotation->sub_total - @$quotation->vat)) * 100, 2) ?>%,
                        Net AED <?= round((@$quotation->sub_total - @$quotation->vat), 2) ?>,
                        Tax AED <?= round(@$quotation->vat, 2) ?>
                    </b>
                </td>
            </tr>
            <?php endif; ?>

            <!-- Bank Details -->
            <tr>
                <td colspan="3" style="padding: 10px 0;">
                    <b>Bank Details:</b><br>
                    Bank: <?= @$bank_name ?><br>
                    Account Name: <?= $account_name ?><br>
                    Account No: <?= $account_no ?><br>
                    IBAN: <?= $iban ?><br>
                    Branch: <?= $bank_branch ?><br>
                    Swift Code: <?= $swift_code ?>
                </td>
            </tr>

            <!-- Footer -->
            <tr>
              <?= $footer ?>
            </tr>
        </table>
    </div>
</body>

</html>
