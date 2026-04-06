<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title><?= $quotation->confirm == 1 ? "Invoice" : "Quotation" ?></title>
</head>

<body style="margin: 0; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #555; font-size: 16px; line-height: 24px;">
    <div style="max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; box-shadow: 0 0 10px rgba(0, 0, 0, .15);">

        <!-- Header Section -->
        <table style="width: 100%; line-height: inherit; text-align: left; border-collapse: collapse;">
            <tr>
                <td colspan="3" style="padding-bottom: 20px;">
                    <table style="width: 100%;">
                        <tr>
                            <td style="font-size: 45px; color: #333; width: 65%;">
                                <?php
                                $companyLogo = '/uploads/users/' . $company['company_image'];
                                if ($companyLogo):
                                ?>
                                    <img src="<?= SITE_URL() . $companyLogo ?>" alt="Company Logo" style="max-width: 200px; height: auto;">
                                <?php endif; ?>
                            </td>
                            <td style="text-align: left;">
                                <b>Order Status:</b>

                                <?= $quotation->confirm == 1 ?  @$quotation->status : "Pending" ?>

                                <br>
                                <b>Invoice No:</b> <?= @$quotation->invoiceno ?><br>
                                <b>Date:</b> <?= $quotation->updated_date ?: $quotation->created_date ?><br>
                                <b>Salesperson:</b> <?= @$quotation->sales_person_name ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <!-- Invoice Title -->
            <tr>
                <td colspan="3" style="border-top: 1px solid #ddd; border-bottom: 1px solid #ddd; font-weight: bold; text-align: center; letter-spacing: 2px; padding: 10px 0;">
                    <?= $quotation->confirm == 1 ?  "Invoice" : "Quotation" ?>
                </td>
            </tr>

            <!-- Company and Customer Info -->
            <!-- Company and Customer Info -->
            <tr>
                <td colspan="3" style="padding: 20px 0;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <!-- Billed By -->
                            <td style="width: 50%; vertical-align: top; padding-right: 20px;">
                                <strong>Billed By</strong><br>
                                <?= @$company['company_name'] ?><br>
                                <?= @$company['company_address'] ?><br>
                                Tel: <?= @$company['company_phone'] ?><br>
                                Mail: <?= @$company['company_email'] ?>
                            </td>

                            <!-- Billed To -->
                            <td style="width: 50%; vertical-align: top; text-align: right; padding-left: 20px;">
                                <strong>Billed To</strong><br>
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
        // Apply border-top to tr only after the first row
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
           <span style="padding-left:0px; font-weight:bold" >Extras </span><br>
                <?php foreach ($window['extras'] as $extras): ?>
                    <span style="font-size: 13px;">
                        <?= htmlspecialchars(preg_replace('/\(.*?\)/', '', $extras['extra_name'] ?? '')) ?>
                        <?php if (!empty($extras['sub_extra_name'])): ?>
                            : <?= htmlspecialchars($extras['sub_extra_name']) ?>  
                            <?php if (!empty($extras['quantity']) && $extras['quantity'] > 1): ?>
                           ,  Quantity : <?= htmlspecialchars($extras['quantity']) ?>
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
            <?php if(@$window['noteOnline']==1): ?>
                <span style="font-size: 13px; color: blue;">Special Instructions: <?= @$window['note'] ?>.</span>
            <?php endif; ?>
        </div>
    </td>

    <td></td> <!-- EMPTY CELL to align with Total row -->
    <td style="padding: 5px;">AED <?= @$window['total'] ?: '0' ?></td>
</tr>

<?php
        $win_count++;
    }
}
?>





            <!-- Remarks -->
            <tr style="font-weight: normal;">
                <td>Remarks :<?= @$quotation->remarks ?> </td>
                <td></td>
                <td></td>
            </tr>

         <!-- Totals -->
<tr>
    <td></td>
    <td style="text-align: right; font-weight: bold; border-top: 2px solid #eee; padding: 5px;">Total (Excl. VAT):</td>
    <td style="padding: 5px; font-weight: bold; border-top: 2px solid #eee;">
        AED <?= round(@$quotation->total, 2) ?: 0 ?>
    </td>
</tr>

<?php if (@$quotation->discount != 0): ?>
    <tr>
        <td></td>
        <td style="text-align: right; font-weight: bold; padding: 5px;">
            Less <?= round(((@$quotation->discount / @$quotation->total) * 100), 2) ?>% Discount:
        </td>
        <td style="padding: 5px; font-weight: bold;">
            AED <?= round(@$quotation->discount, 2) ?: 0 ?>
        </td>
    </tr>
<?php endif; ?>

<?php
    // Net amount before VAT
    $net_amount = (@$quotation->sub_total ? (@$quotation->sub_total - @$quotation->discount) : 0);

    // VAT calculation
    $vat_amount = ($net_amount * @$quotation->vat) / 100;

    // Total payable (Net + VAT)
    $total_payable = $net_amount + $vat_amount;

    // Balance after deposit
    $balance = $total_payable - @$quotation->advance;
?>

<tr>
    <td></td>
    <td style="text-align: right; font-weight: bold; padding: 5px;">Net Amount :</td>
    <td style="padding: 5px; font-weight: bold;">AED <?= round($net_amount, 2) ?></td>
</tr>

<tr>
    <td></td>
    <td style="text-align: right; font-weight: bold; padding: 5px;">Add 5% VAT:</td>
    <td style="padding: 5px; font-weight: bold;">AED <?= round($vat_amount, 2) ?></td>
</tr>

<tr>
    <td></td>
    <td style="text-align: right; font-weight: bold; padding: 5px;">Total Payable (Incl. VAT):</td>
    <td style="padding: 5px; font-weight: bold;">AED <?= round($total_payable, 2) ?></td>
</tr>

<tr>
    <td></td>
    <td style="text-align: right; font-weight: bold; padding: 5px;">
        Deposit Paid (<?= @$quotation->payment_type ?>):
    </td>
    <td style="padding: 5px; font-weight: bold;">AED <?= round(@$quotation->advance, 2) ?: 0 ?></td>
</tr>

<tr>
    <td></td>
    <td style="text-align: right; font-weight: bold; padding: 5px;">Balance Due (Incl. VAT):</td>
    <td style="padding: 5px; font-weight: bold;">AED <?= round($balance, 2) ?></td>
</tr>


            <!-- Signature -->
       <tr>
    <td></td>
    <td style="text-align: right; border-top: 2px solid #eee; padding: 5px;">Signature:</td>
    <td style="padding: 5px;">
        <?php
        $signature = $quotation->signature;
        $signaturePath = $_SERVER['DOCUMENT_ROOT'] . '/uploads/quotation/' . $signature;
        $signatureUrl  = SITE_URL() . '/uploads/quotation/' . $signature;

        if (!empty($signature) && file_exists($signaturePath)):
        ?>
            <img src="<?= $signatureUrl ?>" alt="signature" style="max-width: 80px; height: auto;">
        <?php else: ?>
            <!-- Signature blank space (optional: add dashed line or note) -->
            <div style="width: 80px; height: 40px; border-bottom: 1px dashed #ccc;"></div>
        <?php endif; ?>
    </td>
</tr>

            <!-- VAT -->
            <tr style="font-size: 15px;">
                <td colspan="3" style="padding-top: 20px;">
                   <?php  if ($quotation->sub_total != 0): ?>
                    <b>
                    VAT <?= round((@$quotation->vat / (@$quotation->sub_total - @$quotation->vat)) * 100, 2) ?>%,
                    Net AED <?= round((@$quotation->sub_total-$quotation->vat),2) ?>,
                    Tax AED <?= round(@$quotation->vat, 2) ?>
                    <?php endif; ?>
                </b>
                </td>
            </tr>

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

            <!-- Terms -->
            <tr>
                <?= $footer ?>
            </tr>
        </table>
    </div>
</body>

</html>