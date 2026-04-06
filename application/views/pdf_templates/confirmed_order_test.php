<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Invoice Email</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #555;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }
        .email-container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            background: #fff;
            border: 1px solid #ddd;
            box-shadow: 0 0 10px rgba(0, 0, 0, .1);
        }
        h2 {
            color: #333;
        }
        .email-content {
            margin: 20px 0;
        }
        .footer {
            font-size: 12px;
            color: #888;
            margin-top: 30px;
        }
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, .15);
            font-size: 16px;
            line-height: 24px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <h2>Invoice for Your Recent Order</h2>
        <p>Dear <?= @$quotation->customer_name ?>,</p>
        <p>Thank you for choosing our services. Please find the details of your invoice below:</p>
        
        <!-- Invoice Table -->
        <div class="invoice-box">
            <table style="width: 100%;line-height: inherit;text-align: left;">
                <tr class="top">
                    <td colspan="3" style="padding: 5px;vertical-align: top;">
                        <table style="width: 100%;line-height: inherit;text-align: left;">
                            <tr>
                                <td class="title" style="padding: 5px;vertical-align: top;font-size: 45px;line-height: 45px;color: #333;width:65%;">
                                    <img src="<?php echo is_file('uploads/users/' . @$company['company_image']) ? base_url() . 'uploads/users/' . @$company['company_image'] : '' ?>" alt="Company Logo" style=" max-width:300px;object-fit: contain;object-fit: contain;max-height:80px;" onerror="this.style.display='none'">
                                </td>
                                <td style="padding: 5px;vertical-align: top;text-align: left;padding-bottom: 20px;">
                                    <b><?= @$quotation->status ?> Order</b><br />
                                    <b>Ref:</b> <?= @$quotation->id ?><br />
                                    <b>Date:</b> <?= $quotation->updated_date ?: $quotation->created_date ?><br />
                                    <b>Salesperson:</b> <?= @$quotation->sp_name; ?>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr class="inv">
                    <td colspan="3" style="padding: 5px;vertical-align: top;border-bottom: 1px solid #ddd;border-top: 1px solid #ddd;font-weight: bold;text-align: center;letter-spacing: 2px;">
                        INVOICE
                    </td>
                </tr>

                <!-- Billed By & Billed To -->
                <tr class="information">
                    <td colspan="3" style="padding: 5px;vertical-align: top;">
                        <table style="width: 100%;line-height: inherit;text-align: left;">
                            <tr>
                                <td style="width: 50%;padding: 5px;vertical-align: top;padding-bottom: 40px;">
                                    <b>Billed By</b><br />
                                    <?= @$company['company_name'] ?><br />
                                    <?= @$company['company_address'] ?><br />
                                    Tel: <?= @$company['company_phone'] ?><br />
                                    mail: <?= @$company['company_email'] ?>
                                </td>
                                <td style="padding: 5px;vertical-align: top;text-align: left;padding-bottom: 40px;">
                                    <b>Billed To</b><br />
                                    <?= @$quotation->customer_name ?><br />
                                    <?= @$customer->address . '<br />' ?><?= @$quotation->customer_phone ?><br />
                                    <?= @$quotation->customerEmail ?>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <!-- Item List -->
                <tr class="heading">
                    <td>Item</td>
                    <td>Measuring Type</td>
                    <td>Price</td>
                </tr>
                <?php
                $win_count = 1;
                foreach ($rooms as $room) {
                    foreach ($room['windows'] as $window) { ?>
                        <tr>
                            <td><?= @$win_count ?>. <?= @$room['room_name'] ?>, <?= @$window['window_name'] ?>, <?= @$window['product_name'] ?><br />&nbsp;&nbsp;<?php foreach ($window['extras'] as $extras) { ?><i></i> <?= @$extras['extra_name'] ? @$extras['extra_name'] . ';' : '' ?><i></i> <?= @$extras['sub_extra_name'] ? @$extras['sub_extra_name'] . ';' : '' ?><i></i><?= (@$extras['sub_sub_extra_name']!="" && @$extras['sub_sub_extra_name']!="null") ? @$extras['sub_sub_extra_name'] . ';' : '' ?><i></i><?= (@$extras['sub_sub_sub_extra_name']!="" && @$extras['sub_sub_sub_extra_name']!="null") ? @$extras['sub_sub_sub_extra_name']: '' ?><?php  } ?></td>
                            <td><?= @$window['measurmenttype'] ?: '' ?></td>
                            <td>AED&nbsp; <?= @$window['total'] ?: '0' ?></td>
                        </tr>
                <?php
                        $win_count++;
                    }
                } ?>
                
                <!-- Total Summary -->
                <tr class="total">
                    <td></td>
                    <td>Total:</td>
                    <td>AED&nbsp; <?= @$quotation->total ?: 0 ?></td>
                </tr>
                <?php if(@$quotation->discount !=0) { ?>
                <tr class="total">
                    <td></td>
                    <td>Less <?= round(((@$quotation->discount / @$quotation->total) * 100), 2) . '%' ?> discount:</td>
                    <td>AED&nbsp; <?= @$quotation->discount ?: 0 ?></td>
                </tr>
                <?php } ?>
                
                <tr class="total">
                    <td></td>
                    <td>Total Payable:</td>
                    <td>AED&nbsp; <?= @$quotation->sub_total ?: 0 ?></td>
                </tr>
                <tr class="total">
                    <td></td>
                    <td>Deposit paid (credit card):</td>
                    <td>AED&nbsp; <?= @$quotation->advance ?: 0 ?></td>
                </tr>
                <tr class="total">
                    <td></td>
                    <td>Balance Due:</td>
                    <td>AED&nbsp; <?= (@$quotation->sub_total - @$quotation->advance) ?: 0 ?></td>
                </tr>
                
                <tr class="item last" style="font-size:15px">
                    <td colspan=2>VAT <?= round(((@$quotation->vat / (@$quotation->sub_total-@$quotation->vat)) * 100), 2) ?: 0 ?>%, Net AED&nbsp;<?= @$quotation->sub_total ?: 0 ?>, VAT AED&nbsp; <?= @$quotation->vat ?: 0 ?></td>
                </tr>

                <tr>
                    <td colspan=2>Bank: Sharjah Islamic Bank<br />Account Name: Innov8 Products FZC <br />Account No: 0011 587 301 003<br />IBAN: AE 0604 1000 0011 5873 01003<br />Branch: Sharjah Airport Free Zone (SHSZ 0032)<br />Swift Code: NBSHAEAS</td>
                </tr>

                <tr class="terms">
                    <td colspan=2 style="font-size: 11px;line-height:1.2;border-top: 2px solid #eee;">Conditions Of Sale<br />
                        I agree to pay the balance due before or at the time of installation...
                    </td>
                </tr>
            </table>
        </div>

        <div class="footer">
            <p>If you have any questions regarding this invoice, please don't hesitate to contact us. We appreciate your business!</p>
            <p>Best regards,<br />[Your Company Name]</p>
        </div>
    </div>
</body>
</html>
