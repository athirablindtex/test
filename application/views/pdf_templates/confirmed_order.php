<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title><?= ($quotation->confirm == 1 ? "Invoice" : "Quotation") ?></title>

<style>

body{
    margin:0;
    font-family:Arial, Helvetica, sans-serif;
    font-size:14px;
    color:#444;
}

.container{
    width:100%;
    padding:25px;
}

table{
    width:100%;
    border-collapse:collapse;
}

th,td{
    padding:6px;
    vertical-align:top;
}

.header-title{
    border-top:2px solid #444;
    border-bottom:2px solid #444;
    text-align:center;
    font-weight:bold;
    font-size:18px;
    letter-spacing:2px;
    padding:8px 0;
    margin:15px 0;
}

.product-table th{
    background:#f7f7f7;
    border-top:1px solid #ddd;
    border-bottom:1px solid #ddd;
}

.product-row{
    page-break-inside:avoid;
}

.totals-table td{
    padding:5px;
}

.totals-table td:last-child{
    text-align:right;
}

.total-row{
    background:#f5f5f5;
    font-weight:bold;
    font-size:15px;
    border-top:2px solid #ccc;
}

.balance-row{
    font-weight:bold;
}

.remarks{
    margin-top:15px;
    font-style:italic;
}

.signature-box{
    width:120px;
    height:40px;
    border-bottom:1px dashed #aaa;
}

.bank-details{
    margin-top:20px;
    font-size:13px;
}

.footer{
    text-align:center;
    margin-top:25px;
    font-size:12px;
    color:#777;
}

</style>
</head>

<body>

<div class="container">

<!-- Header -->
<table>
<tr>

<td width="65%">
<?php if(!empty($company['company_image'])){ ?>
<img src="<?= SITE_URL().'uploads/users/'.$company['company_image'] ?>" style="max-width:200px;">
<?php } ?>
</td>

<td align="right">

<b>Order Status:</b> <?= ($quotation->confirm == 1 ? @$quotation->status : "Pending") ?><br>

<b>Invoice No:</b> <?= @$quotation->invoiceno ?><br>

<b>Date:</b> <?= ($quotation->updated_date ?: $quotation->created_date) ?><br>

<b>Salesperson:</b> <?= @$quotation->sales_person_name ?>

</td>

</tr>
</table>

<!-- Title -->

<div class="header-title">
<?= ($quotation->confirm == 1 ? "TAX INVOICE" : "QUOTATION") ?>
</div>


<!-- Company / Customer -->

<table style="margin-top:15px;">

<tr>

<td width="50%">

<b>Billed By</b><br>

<?= @$company['company_name'] ?><br>

<?= @$company['company_address'] ?><br>

Tel: <?= @$company['company_phone'] ?><br>

Mail: <?= @$company['company_email'] ?><br>
<?php if(!empty($company['trn_no'])){ ?>
TRN : <?= $company['trn_no'] ?> 
<?php } ?>

</td>

<td width="50%" align="right">

<b>Billed To</b><br>

<?= @$quotation->customer_name ?><br>

<?= @$customer['address'] ?><br>

<?= @$quotation->customer_phone ?><br>

<?= @$quotation->customerEmail ?><br>

<?php if(!empty($quotation->customerTrn)){ ?>
TRN : <?= $quotation->customerTrn ?>
<?php } ?>

</td>

</tr>

</table>


<!-- Product Table -->

<table class="product-table" style="margin-top:20px;">

<thead>

<tr>
<th width="70%" align="left">Item</th>
<th width="30%" align="right">Price</th>
</tr>

</thead>

<tbody>

<?php

$win_count = 1;

foreach ($rooms as $room){

foreach ($room['windows'] as $window){

?>

<tr class="product-row">

<td>

<strong><?= $win_count ?>. <?= @$window['window_name'] ?></strong><br>

Quantity : <?= @$window['quantity'] ?><br>

<?= @$window['priceBandVersion'] ?><br>

<?= @$window['product_name'] ?><br>


<?php if(!empty($window['extras'])){ ?>

<b>Extras</b><br>

<?php foreach($window['extras'] as $extras){ ?>

<?= htmlspecialchars(preg_replace('/\(.*?\)/','',$extras['extra_name'] ?? '')) ?>

<?php if(!empty($extras['sub_extra_name'])){ ?>

: <?= htmlspecialchars($extras['sub_extra_name']) ?>

<?php } ?>

<br>

<?php } ?>

<?php } ?>


<?php if(@$window['noteOnline']==1){ ?>

<span style="color:#0275d8">

Special Instructions: <?= @$window['note'] ?>

</span>

<?php } ?>

</td>

<td align="right">

<?= @$company['currency'] ?> <?= number_format(@$window['total'],2) ?>

</td>

</tr>

<?php

$win_count++;

}

}

?>

</tbody>

</table>


<!-- Remarks -->

<?php if(!empty($quotation->remarks) && $quotation->is_remarks==1){ ?>

<div class="remarks">

<b>Remarks:</b> <?= $quotation->remarks ?>

</div>

<?php } ?>


<!-- Totals -->

<?php

$total = floatval($quotation->total);

$discount = floatval($quotation->discount);

$net = $total - $discount;

$vat = round(($net * @$company['vat'])/100,2);

$payable = $net + $vat;

$deposit = floatval($quotation->advance);

$balance = $payable - $deposit;

?>

<table class="totals-table" style="margin-top:20px;">

<tr>

<td width="60%"></td>

<td>Total :</td>

<td><?= @$company['currency'] ?> <?= number_format($total,2) ?></td>

</tr>


<?php if($discount>0){ ?>

<tr style="color:#d9534f">

<td></td>

<td>   Discount (<?= round((@$quotation->discount/@$quotation->total)*100,2) ?>%):</td>

<td>- <?= @$company['currency'] ?> <?= number_format($discount,2) ?></td>

</tr>

<?php } ?>


<tr>

<td></td>

<td>Net Amount :</td>

<td><?= @$company['currency'] ?> <?= number_format($net,2) ?></td>

</tr>


<tr>

<td></td>

<td>VAT (<?= @$company['vat'] ?>%) :</td>

<td><?= @$company['currency'] ?> <?= number_format($vat,2) ?></td>

</tr>


<tr class="total-row">

<td width="60%"></td>

<td>Total Payable :</td>

<td><?= @$company['currency'] ?> <?= number_format($payable,2) ?></td>

</tr>


<?php if($quotation->confirm==1 && $deposit>0){ ?>

<tr style="color:#0275d8">

<td></td>

<td style="text-align:right;">Deposit Paid (<?= @$quotation->payment_type ?>):</td>

<td><?= @$company['currency'] ?> <?= number_format($deposit,2) ?></td>

</tr>

<tr class="balance-row">

<td></td>

<td>Balance Due :</td>

<td><?= @$company['currency'] ?> <?= number_format($balance,2) ?></td>

</tr>

<?php } ?>


</table>


<!-- Signature -->

<table style="margin-top:30px;">

<tr>

<td></td>

<td align="right" width="120">Signature:</td>

<td width="150">

<?php

$signature = trim($quotation->signature);

$signaturePath = FCPATH.'uploads/quotation/'.$signature;

$signatureUrl = SITE_URL('uploads/quotation/'.$signature);

if(!empty($signature) && file_exists($signaturePath)){

?>

<img src="<?= $signatureUrl ?>" style="max-width:120px">

<?php } else { ?>

<div class="signature-box"></div>

<?php } ?>

</td>

</tr>

<tr>

<td colspan="3" class="bank-details">

<b>Bank Details</b><br>

Bank : <?= $bank_name ?><br>

Account Name : <?= $account_name ?><br>

Account No : <?= $account_no ?><br>

IBAN : <?= $iban ?><br>

Branch : <?= $bank_branch ?><br>

Swift Code : <?= $swift_code ?>

</td>

</tr>

</table>


<div class="footer">

<?= $footer ?>

</div>


</div>

</body>
</html>