<?php

$payAmount        = $payAmount ?? 0;
$isBalancePayment = $isBalancePayment ?? false;
$advance = $advanceAmount ?? 0;
$discountAmount = $quotation->discount ?? 0;
$totalAmount = $quotation->total ?? 0;
$sub_total = $quotation->sub_total ?? 0; // make sure this is correct field

$discountPercent = 0;

if ($totalAmount > 0) {
    $discountPercent = ($discountAmount / $totalAmount) * 100;
}

$brand = $company['branding_colour_code'] ?? '#9A0653';

$pdfUrl  = base_url('uploads/invoice/INV-QTN-' . $quotation->invoiceno . '.pdf');

if (!preg_match('/^#([A-Fa-f0-9]{6})$/', $brand)) {
    $brand = '#9A0653';
}

function isLight($hex)
{
    $hex = str_replace('#', '', $hex);
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));
    return (($r * 299 + $g * 587 + $b * 114) / 1000) > 155;
}
$textColor = isLight($brand) ? '#000' : '#fff';
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Checkout</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Marcellus&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        body {
            font-family: 'Marcellus', serif;
            background: #f5f6f8;
            font-size: 16px;
            color: #222;
        }

        /* CONTAINER */
        .container {
            max-width: 1200px;
        }

        /* HEADER */
        .header-box {
            background: #fff;
            padding: 14px 20px;
            border-bottom: 1px solid #eee;
        }

        /* CARD (clean flat look) */
        .card {

            border-radius: 6px;
            box-shadow: none;
        }

        /* CARD TITLE */
        .card h5 {
            font-size: 17px;
            font-weight: 600;
            margin-bottom: 15px;
        }



        .product-type {
            font-size: 14px;
            font-weight: 500;
        }

        .utap-box {
            background: #ffffff;
            border: 1px solid #f1e2d8;
            border-radius: 10px;
        }

        .utap-icon {
            width: 36px;
            height: 36px;
            background: #dc3545;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
        }

        .item:hover {
            border-color: #ddd;
        }

        .item.selected {
            border-left: 3px solid <?= $brand ?>;
            background: #fafafa;
        }

        /* TEXT */
        .item small {
            color: #777;
            font-size: 15px;
        }

        /* PRICE ALIGN RIGHT */
        .item .fw-semibold {
            font-size: 17px;
            text-align: right;
            min-width: 90px;
        }

        /* CHECKBOX */
        .product-check {
            transform: scale(1.1);
        }

        /* PAYMENT OPTION */
        .payment-option {
            border: 1px solid #eee;
            border-radius: 6px;
            padding: 12px;
            background: #f5f6f8;
            transition: 0.2s;
        }

        .payment-option.active {
            border-left: 3px solid <?= $brand ?>;
            background: #fafafa;
        }

        /* SUMMARY BOX */
        .summary-box,
        .card.position-sticky {
            border-radius: 6px;
        }

        /* SUMMARY ROW */
        .summary-row,
        .card-body .d-flex {
            font-size: 16px;
        }

        /* TOTAL */
        .summary-total,
        #payable {
            font-size: 20px;
            font-weight: 600;
            color: <?= $brand ?>;
        }

        /* BUTTON */
        #payBtn {
            border-radius: 6px;
            padding: 12px;
            font-size: 17px;
            font-weight: 600;
            background: <?= $brand ?>;
            color: <?= $textColor ?>;
            border: none;
            transition: 0.2s;
        }

        #payBtn:hover {
            opacity: 0.9;
        }

        /* VIEW MORE */
        #toggleProducts {
            border-radius: 4px;
            font-size: 14px;
        }

        /* REMOVE EXTRA ROUNDNESS */
        .card,
        .item,
        .payment-option,
        .summary-box {
            border-radius: 6px !important;
        }



        /* ORDER BOX */
        .order-box {
            background: #fff;
            border: 1px solid #eee;
            border-radius: 10px;
            padding: 14px;
            margin-bottom: 10px;
            transition: 0.2s;
        }



        /* PAYMENT */
        .payment-box {
            border: 1px solid #eee;
            border-radius: 10px;
            padding: 14px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            background-color: #fff;
        }

        .payment-box.active {
            border: 1px solid <?= $brand ?>;

        }

        /* SUMMARY */
        .summary-card {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #eee;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .summary-total {
            font-size: 19px;
            font-weight: 700;
            color: <?= $brand ?>;
        }

        .powered-by {
            text-align: center;
            /* ✅ center align */

            line-height: 1.2;
        }

        .powered-by .label {
            display: block;
            font-size: 10px;
            /* ✅ smaller */
            color: #999;
            /* lighter grey */
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }

        .powered-by .brand {
            font-size: 14px;
            /* ✅ smaller brand */
            font-weight: 600;
        }

        .powered-by .m {
            color: #00A6B4;
            font-weight: 700;
        }

        .powered-by .swipe {
            color: #444;
        }
.secured-info
{
    font-size: 12px;
}
        /* LOADER OVERLAY */
        #loaderOverlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(3px);
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* LOADER BOX */
        .loader-box {
            background: #fff;
            padding: 14px 20px;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            font-weight: 500;
        }

        /* PDF BUTTON */
        .pdf-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 6px;
            background: #fff5f5;
            color: #dc3545;
            font-size: 13px;
            font-weight: 500;
            border: 1px solid #f5c2c7;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        /* ICON */
        .pdf-btn i {
            font-size: 16px;
        }

        /* HOVER EFFECT */
        .pdf-btn:hover {
            background: #dc3545;
            color: #fff;
            border-color: #dc3545;
        }

        .order-box.disabled {
            background: #f5f5f5;
            border-color: #ddd;
            opacity: 0.6;
        }

        .order-box.disabled .fw-semibold,
        .order-box.disabled small {
            color: #999 !important;
        }

        .order-box.disabled input {
            cursor: not-allowed;
        }

        .order-new {
            background: #f5f6f8;
        }

        .card.p-3.mb-4.order-new {
            border: none !important;
        }

        .card.p-3 {
            background: #f5f6f8;
            border: none !important;
        }
    </style>

</head>

<body>

    <div class="container-xll">

        <!-- HEADER -->
        <div class="header-box d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center gap-3">
                <img src="<?= base_url('uploads/users/' . $company['company_image']) ?>" style="height:45px">
            </div>

            <div class="text-end">
                <div class="fw-bold">#<?= $quotation->invoiceno ?></div>
                <small class="text-muted d-block">
                    <?= date('d M Y', strtotime($quotation->created_date)) ?>
                </small>

                <a href="<?= $pdfUrl ?>" target="_blank" class="pdf-btn mt-1">
                    <i class="fa fa-file-pdf"></i> <?= $docType ?>
                </a>
            </div>
        </div>

        <div class="row justify-content-center">

            <div class="col-12 col-sm-8">

                <div class="row g-4">

                    <!-- LEFT SIDE -->
                    <div class="col-lg-8">

                        <?php if ($isBalancePayment): ?>

                            <!-- ✅ BALANCE BOX -->
                            <div class="card p-4 mb-4 text-center ">

                                <h6 class="text-muted mb-2">Remaining Amount</h6>

                                <div class="fw-bold" style="font-size:32px; color:<?= $brand ?>">
                                    AED <?= number_format($payAmount, 2) ?>
                                </div>
                                <input type="hidden" id="balance" name="balance" value="1">

                                <small class="text-muted">
                                    You are paying only the balance amount
                                </small>

                            </div>

                        <?php else: ?>

                            <!-- ✅ ORDER DETAILS -->
                            <div class="card p-3 mb-4 order-new">

                                <h6 class="fw-bold mb-3">Order Details</h6>
                                <input type="hidden" id="balance" name="balance" value="0">

                                <?php $i = 0;
                                $totalItems = 0; ?>

                                <?php foreach ($rooms as $room): ?>
                                    <?php foreach ($room['windows'] as $window): ?>

                                        <div class="order-box <?= $i >= 3 ? 'd-none extra-item' : '' ?>  <?= ($window['activeItem'] == 1) ? 'selected' : 'disabled' ?>">

                                            <div class="d-flex justify-content-between align-items-center">

                                                <div class="d-flex align-items-center">
                                                    <input type="checkbox"
                                                        class="form-check-input me-3 product-check" value="1"
                                                        <?= ($window['activeItem'] == 1) ? 'checked' : '' ?>

                                                        data-price="<?= $window['total'] ?>" data-id="<?= $window['id'] ?>" data-room="<?= $window['room'] ?>" data-product="<?= $window['product'] ?>">


                                                    <div>
                                                        <!-- Title -->
                                                        <div class="fw-semibold"><?= $window['window_name'] ?></div>



                                                        <!-- Variant / Price Band -->
                                                        <?php if (!empty($window['priceBandVersion'])): ?>
                                                            <small class="d-block product-type" style="color: <?= $brand ?>;">
                                                                <?= $window['priceBandVersion'] ?>
                                                            </small>
                                                        <?php endif; ?>
                                                        <!-- Product -->
                                                        <small class="text-muted d-block">
                                                            <?= $window['product_name'] ?>
                                                        </small>

                                                        <!-- Quantity -->
                                                        <small class="text-muted d-block">
                                                            Qty: <?= $window['quantity'] ?>
                                                        </small>
                                                    </div>
                                                </div>

                                                <div class="fw-semibold">
                                                    AED <?= number_format($window['total'], 2) ?>
                                                </div>

                                            </div>

                                        </div>

                                        <?php $i++; ?>
                                    <?php endforeach; ?>
                                    <?php $totalItems += count($room['windows']); ?>
                                <?php endforeach; ?>

                                <?php if ($totalItems > 3): ?>
                                    <div class="text-center mt-2">
                                        <button class="btn btn-sm btn-outline-secondary" id="toggleProducts">
                                            View More
                                        </button>
                                    </div>
                                <?php endif; ?>

                            </div>
                        <?php endif; ?>
                        <!-- PAYMENT METHOD -->
                        <div class="card p-3">

                            <h6 class="fw-bold mb-3">Payment Method</h6>

                            <div class="payment-box active" onclick="selectPayment(this)">
                                <div>
                                    <div class="fw-semibold">Pay Full Amount</div>
                                </div>
                                <input type="radio" name="pay" value="full" checked>
                            </div>
                            <?php if (!$isBalancePayment): ?>
                                <div class="payment-box mt-2" onclick="selectPayment(this)">
                                    <div>
                                        <div class="fw-semibold">Pay 50% Advance</div>
                                    </div>
                                    <input type="radio" name="pay" value="half">
                                </div>
                            <?php endif; ?>

                        </div>



                    </div>

                    <!-- RIGHT SIDE (FIXED POSITION) -->
                    <div class="col-lg-4">

                        <div class="summary-card p-3 mt-5">

                            <h6 class="fw-bold mb-3">Summary</h6>

                            <?php if ($isBalancePayment): ?>

                                <div class="summary-row">
                                    <span>Total</span>
                                    <span>AED <?= number_format($sub_total, 2) ?></span>
                                </div>

                                <div class="summary-row text-danger">
                                    <span>Paid</span>
                                    <span>- AED <?= number_format($advance, 2) ?></span>
                                </div>

                                <hr>

                                <div class="summary-total d-flex justify-content-between">
                                    <span>Remaining</span>
                                    <span>AED <?= number_format($payAmount, 2) ?></span>
                                </div>

                            <?php else: ?>

                                <div class="summary-row">
                                    <span>Total</span>
                                    <span id="total">AED 0.00</span>
                                </div>

                                <?php if ($discountAmount > 0) { ?>
                                    <div class="summary-row text-danger">
                                        <span>Discount</span>
                                        <span id="discount">- AED 0.00</span>
                                    </div>
                                <?php } ?>

                                <div class="summary-row">
                                    <span>VAT (5%)</span>
                                    <span id="vat">AED 0.00</span>
                                </div>

                                <hr>

                                <div class="summary-total d-flex justify-content-between">
                                    <span id="payableLabel">Total Payable</span>
                                    <span id="payable">AED 0.00</span>
                                </div>

                            <?php endif; ?>

                            <button class="btn w-100 mt-3"
                                style="background:<?= $brand ?>; color:<?= $textColor ?>;"
                                id="payBtn">
                                Proceed to Payment
                            </button>

                        </div>

                   <div class="utap-box mt-3 p-3">

    <!-- TOP ROW -->
    <div class="d-flex justify-content-between align-items-center">

        <!-- LEFT -->
        <div class="d-flex align-items-start gap-2">
            <div class="utap-icon">
                <i class="fa fa-lock"></i>
            </div>

            <div>
                <div class="fw-semibold">Secure Payment</div>
                <small class="text-muted d-block secured-info">
                    All transactions are secure and encrypted.<br>
                    Card information is never stored.
                </small>
            </div>
        </div>

        <!-- RIGHT -->
        <div class="text-end">
            <img src="<?= base_url('assets/images/mswipe.png') ?>" alt="mswipe" class="mswipe-logo">
        </div>

    </div>

    <!-- ✅ NEW LINE (FULL WIDTH CENTER) -->
    <div class="powered-by mt-2">
        <span class="label">powered by</span>
        <span class="brand">
            <span class="m">m</span><span class="swipe">swipe</span>
        </span>
    </div>

</div>

                    </div>

                </div>
            </div>
        </div>
    </div>
    <script>
        let expanded = false;

        $('#toggleProducts').on('click', function() {

            $('.extra-item').toggleClass('d-none');

            expanded = !expanded;

            $(this).text(expanded ? 'View Less' : 'View More');
        });

        let payMode = 'full';

        function selectPayment(el) {

            $('.payment-option').removeClass('active');
            $(el).addClass('active');

            let input = $(el).find('input');
            input.prop('checked', true);

            payMode = input.val(); // 'full' or 'half'

            calculate(); // always recalculate
        }


        function calculate() {

            $('#loaderOverlay').removeClass('d-none');

            setTimeout(function() {

                let total = 0;



                let discountPercent = <?= $discountPercent ?>;


                let vat = 0;
                let payable = 0;

                // ✅ BALANCE MODE
                if (<?= $isBalancePayment ? 'true' : 'false' ?>) {

                    let totalAmount = <?= $totalAmount ?>;
                    let remaining = <?= $payAmount ?>;

                    $('#total').text("AED " + totalAmount.toFixed(2));
                    $('#discount').text("- AED 0.00");
                    $('#vat').text("AED 0.00");

                    $('#payableLabel').text('Remaining Amount');
                    $('#payable').text("AED " + remaining.toFixed(2));
                    $('#balance').text("AED 0.00");

                    payable = remaining; // important for redirect
                }

                // ✅ NORMAL MODE
                else {


                    $('.product-check').each(function() {

                        let row = $(this).closest('.order-box');

                        if ($(this).is(':checked')) {
                            row.removeClass('disabled');
                            total += parseFloat($(this).data('price'));
                            row.addClass('selected');

                        } else {
                            row.addClass('disabled');
                            row.removeClass('selected');
                        }

                    });


                    let discount = (discountPercent / 100) * total;



                    let net = total - discount;


                    vat = net * 0.05;

                    let fullPayable = net + vat;

                    // ✅ PAYMENT MODE (FULL / HALF)
                    if (payMode === 'half') {

                        payable = fullPayable / 2;

                        $('#payableLabel').text('50% Advance');

                        let balance = fullPayable - payable;
                        $('#balance').text("AED " + balance.toFixed(2));

                    } else {

                        payable = fullPayable;

                        $('#payableLabel').text('Total Payable');
                        $('#balance').text("AED 0.00");
                    }

                    // UI update
                    $('#total').text("AED " + total.toFixed(2));
                    $('#discount').text(
                        "- AED " + discount.toFixed(2) +
                        " (" + discountPercent.toFixed(2) + "%)"
                    );
                    $('#vat').text("AED " + vat.toFixed(2));
                    $('#payable').text("AED " + payable.toFixed(2));
                }

                // ✅ BUTTON CLICK
                $('#payBtn').off('click').on('click', function() {

                    let mode = payMode; // full / half

                    $('#payBtn').prop('disabled', true);
                    $('#btnLoader').removeClass('d-none');

                    let windows = [];

                    $('.product-check').each(function() {
                        windows.push({
                            window_id: $(this).data('id'),
                            room_id: $(this).data('room'),
                            product: $(this).data('product'),
                            status: $(this).is(':checked') ? 1 : 0
                        });
                    });




                    $.ajax({
                        url: "<?= base_url('payment/createPaymentLink') ?>",
                        type: "POST",

                        data: {
                            quotation_id: "<?= $encryptedId ?>",
                            mode: mode,
                            windows: JSON.stringify(windows)
                        },
                        dataType: "json",
                        success: function(res) {

                         if (res.status === 'success') {
                              
                                window.location.href = res.link;
                            } else {
                                alert(res.message || 'Payment failed');
                                $('#payBtn').prop('disabled', false);
                                $('#btnLoader').addClass('d-none');
                            }
                        },
                        error: function() {
                            alert('Something went wrong');
                            $('#payBtn').prop('disabled', false);
                            $('#btnLoader').addClass('d-none');
                        }
                    });

                });
                $('#loaderOverlay').addClass('d-none');

            }, 300);
        }
        // EVENTS
        $(document).on('change', '.product-check', function() {

            let id = $(this).data('id');
            let price = parseFloat($(this).data('price')) || 0;
            let product = $(this).data('product');
            let room = $(this).data('room');

            let status = $(this).is(':checked') ? 1 : 0;
            $(this).val(status);


            // $.ajax({
            //     url: '<?= base_url("payment/updateWindowStatus") ?>',
            //     type: 'POST',
            //     data: {
            //         id: id,
            //         product: product,
            //         room_id: room, 
            //         window_id: id,
            //         status: status
            //     },
            //     success: function (res) {
            //         console.log('Updated DB', res);
            //     },
            //     error: function (err) {
            //         console.log('Error:', err);
            //     }
            // });

            calculate();
        });
        $('#payBtn').on('click', function() {

            $(this).prop('disabled', true);

            $('#btnText').text('Processing...');
            $('#btnLoader').removeClass('d-none');

        });

        // INITIAL
        calculate();
    </script>
    <!-- LOADER OVERLAY (ADD HERE) -->
    <div id="loaderOverlay" class="d-none">
        <div class="loader-box d-flex align-items-center gap-2">
            <div class="spinner-border text-dark"></div>
            <span>Calculating...</span>
        </div>
    </div>

</body>

</html>