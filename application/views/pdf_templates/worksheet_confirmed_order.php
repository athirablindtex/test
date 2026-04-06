<!doctype html>

<html lang="en">

<head>

    <meta charset="utf-8">

    <!--fontsz=14-->

    <title>WorkSheet</title>



    <style>
     @page {
    size: A4 landscape;
    margin: 0;
}
body {
    padding-top: 20mm;
    padding-left: 10mm;
    padding-right: 10mm;
    padding-bottom: 15mm;
}
table {
    width: 100%;
    border-collapse: collapse;
}

        /* This ensures extra top space only after a page break */
        .page-break {
            page-break-before: always;
            padding-top: 20mm;
            /* Add extra space at top of new page */
        }

        .job-box {

            max-width: 1800px;

            margin: auto;

            padding: 30px;

            border: 1px solid #eee;

            box-shadow: 0 0 10px rgba(0, 0, 0, .15);

            font-size: 14px;

            line-height: 16px;

            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;

            color: #555;

        }

  .remarks {
            margin: 15px 0;
            font-size: 13px;
            font-style: italic;
        }

        .job-box table {

            width: 100%;

            line-height: inherit;

            text-align: left;

        }



        .job-box table td {

            padding: 5px;

            vertical-align: top;

        }



        .job-box table tr td:nth-child(2) {

            /* text-align: ; */

        }



        .job-box table tr.top table td {

            padding-bottom: 20px;

        }



        .job-box table tr.top table td.title {

            font-size: 25px;

            line-height: 25px;

            color: #333;

        }



        .job-box table tr.information table td {

            padding-bottom: 40px;

        }



        .job-box table tr.heading td {

            background: #eee;

            border-bottom: 1px solid #ddd;

            font-weight: bold;

            font-size: 14px;

        }



        .job-box table tr.details td {

            border-bottom: 1px solid #eee;

            page-break-inside: avoid;

            font-size: 14px;

        }



        .job-box table tr.item td {

            border-bottom: 1px solid #bbb;

            padding-bottom: 20px;

            font-size: 14px;

            page-break-inside: avoid
        }



        .job-box table tr.item.last td {

            border-bottom: none;

        }



        .job-box table tr.total td {

            border-top: 2px solid #eee;

            font-weight: bold;

        }



        .job-box table tr.table-td {

            width: 500px;

        }



        .job-status table tr td {

            table-layout: fixed;

            border-bottom: 1px solid #eee;

        }



        .job-status table tr.item td {

            border-bottom: 1px solid #eee;

        }



        .status-cell {

            border-right: 2px solid #eee;

            overflow: hidden;

            width: 150px;

        }



        .status-cell2 {

            border-right: 1px solid #eee;

            overflow: hidden;

            width: 50px;

        }



        @media only screen and (max-width: 800px) {

            .job-box table tr.top table td {

                width: 100%;

                display: block;

                text-align: center;

            }



            .job-box table tr.information table td {

                width: 100%;

                display: block;

                text-align: center;

            }

        }
    </style>

</head>



<body>

    <div class="job-box" style="max-width: 1800px; margin: auto; padding: 30px; border: 1px solid #eee; box-shadow: 0 0 10px rgba(0, 0, 0, .15); font-size: 14px; line-height: 16px; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; color: #555;">

        <table style="width: 100%; line-height: inherit; text-align: left;">

            <tr class="top">

                <td colspan="8" style="padding: 5px; vertical-align: top;">

                    <table style="width: 100%; line-height: inherit; text-align: left;">

                        <tr>

                            <td class="title" style="padding: 5px; vertical-align: top; padding-bottom: 20px; font-size: 25px; line-height: 25px; color: #333;">Worksheet</td>



                        </tr>

                    </table>

                </td>

            </tr>



            <tr>

                <td colspan="3" style="padding: 5px; vertical-align: top;">

                    <table style="width: 100%; line-height: inherit; text-align: left;">

                        <tr>

                            <td style="padding: 0px 0px 0px 0px; width: 40%; vertical-align: top;">

                                <table class="job-status" style="width: 100%; line-height: inherit; text-align: left;">

                                    <tr class="item">

                                        <td class="status-cell" style="border-right: 2px solid #eee; overflow: hidden; width: 150px; padding: 5px; vertical-align: top; border-bottom: 2px solid #eee;">Ref Number:</td>

                                        <td style="padding: 5px; vertical-align: top; /* text-align: ;*/ border-bottom: 2px solid #eee;"><?= @$quotation->invoiceno ?></td>

                                    </tr>

                                    <tr class="item">

                                        <td class="status-cell" style="border-right: 2px solid #eee; overflow: hidden; width: 150px; padding: 5px; vertical-align: top; border-bottom: 2px solid #eee;">Date:</td>

                                        <td style="padding: 5px; vertical-align: top; /* text-align: ;*/  border-bottom: 2px solid #eee;"><?= @$quotation->updated_date ?: @$quotation->created_date ?></td>

                                    </tr>

                                    <tr class="item">

                                        <td class="status-cell" style="border-right: 2px solid #eee; overflow: hidden; width: 150px; padding: 5px; vertical-align: top; border-bottom: 2px solid #eee;">Confirmed Order:</td>

                                        <td style="padding: 5px; vertical-align: top; /* text-align: ;*/ border-bottom: 2px solid #eee;"><?= (@$quotation->status == "Confirmed order") ? "Yes" : "No" ?></td>

                                    </tr>

                                    <tr class="item">

                                        <td class="status-cell" style="border-right: 2px solid #eee; overflow: hidden; width: 150px; padding: 5px; vertical-align: top; border-bottom: 2px solid #eee;">Customer:</td>

                                        <td style="padding: 5px; vertical-align: top; /* text-align: ;*/ border-bottom: 2px solid #eee;"><?= @$quotation->customer_name ?><br /><?= @$customer['address'] ?><br /><?= @$quotation->customerEmail ?><br /><?= @$quotation->customer_phone ?></td>

                                    </tr>
                                    <tr class="item">

                                        <td class="status-cell" style="border-right: 2px solid #eee; overflow: hidden; width: 150px; padding: 5px; vertical-align: top; border-bottom: 2px solid #eee;">Salesperson:</td>

                                        <td style="padding: 5px; vertical-align: top; /* text-align: ;*/ border-bottom: 2px solid #eee;"><?= @$quotation->sales_person_name; ?><br /><?= $company['company_name']; ?></td>

                                    </tr>
                                    <tr class="item">

                                        <td class="status-cell" style="border-right: 2px solid #eee; overflow: hidden; width: 150px; padding: 5px; vertical-align: top; border-bottom: 2px solid #eee;color:blue">Installation Date:</td>

                                        <td style="padding: 5px; vertical-align: top; /* text-align: ;*/ border-bottom: 2px solid #eee;color:blue"><?= @$quotation->insDate ?></td>

                                    </tr>
                                    <tr class="item">

                                        <td class="status-cell" style="border-right: 2px solid #eee; overflow: hidden; width: 150px; padding: 5px; vertical-align: top; border-bottom: 2px solid #eee; color:blue">Installation Time:</td>

                                        <td style="padding: 5px; vertical-align: top; /* text-align: ;*/ border-bottom: 2px solid #eee; color:blue"><?= @$quotation->insFromTime ?></td>

                                    </tr>
                                            <tr class="item">

                                        <td class="status-cell" style="border-right: 2px solid #eee; overflow: hidden; width: 150px; padding: 5px; vertical-align: top; border-bottom: 2px solid #eee; color:blue">Installation Hours:</td>

                                        <td style="padding: 5px; vertical-align: top; /* text-align: ;*/ border-bottom: 2px solid #eee; color:blue"><?= @$quotation->insToTime ?></td>

                                    </tr>

                                </table>

                            </td>



                        </tr>

                    </table>

                </td>

            </tr>



            <tr class="heading">

                <td style="padding: 5px; vertical-align: top; background: #eee; border-bottom: 2px solid #ddd; font-weight: bold;font-size: 14px">Room</td>

                <td style="padding: 5px; vertical-align: top; background: #eee; border-bottom: 2px solid #ddd; font-weight: bold;font-size: 14px">Details</td>

                <td style="padding: 5px; vertical-align: top; background: #eee; border-bottom: 2px solid #ddd; font-weight: bold;font-size: 14px">Width</td>

                <td style="padding: 5px; vertical-align: top; background: #eee; border-bottom: 2px solid #ddd; font-weight: bold;font-size: 14px">Height</td>

                <td style="padding: 5px; vertical-align: top; background: #eee; border-bottom: 2px solid #ddd; font-weight: bold;font-size: 14px">Measure<br />

                    Type</td>

                <td style="padding: 5px; vertical-align: top; background: #eee; border-bottom: 2px solid #ddd; font-weight: bold;font-size: 14px">Chain Length</td>

    

            </tr>


            <!-- -->
            
            <?php 


            $total_windows = 0;
foreach ($rooms as $room) {
    $total_windows += count($room['windows']);
}

            $window_count = 1;
            foreach ($rooms as $k => $room) {
            $window_counts = 1;
                     
                $total_window = count($room['windows']);
                foreach ($room['windows'] as $window) {
               
            ?>

                    <tr class='item' style=" page-break-inside: avoid;">
                        <td><?= @$window_count ?> of <?= @$total_windows ?><br /><?= @$room['room_name']; ?>&nbsp; #<?= @$window_counts ?></td>
                        <td><?= @$window['window_name']; ?><br>
                            Quantity: <?= @$window['quantity']; ?><br>
                            <?= @$window['priceBandVersion'] ?><br>
                            <?= @$window['subProductType'] ?><br>

                            <?= @$window['product_name']; ?><br />&nbsp;&nbsp;<?php if (!empty($window['extras'])): ?>
                            <strong>Extras:</strong><br>
                            <?php foreach ($window['extras'] as $extras): ?>
                                <span style="font-size: 13px;">
                                    <?= htmlspecialchars(preg_replace('/\(.*?\)/', '', $extras['extra_name'] ?? '')) ?>
                                    <?php if (!empty($extras['sub_extra_name'])): ?>
                                        : <?= htmlspecialchars($extras['sub_extra_name']) ?> <?php if (!empty($extras['quantity']) && $extras['quantity'] > 1): ?>
                                            , Quantity : <?= htmlspecialchars($extras['quantity']) ?>
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
                        <br>

                        <?php if (@$window['note']): ?>
                            <span style="font-size: 13px; color: blue;">Special Instructions: <?= @$window['note'] ?>.</span>
                        <?php endif; ?>

                        </td>
                        <td><?= @$window['width'] ? @$window['width'] . @$window['unit'] : '' ?></td>
                        <td><?= @$window['height'] ? @$window['height'] . @$window['unit'] : '' ?></td>
                        <td><?= @$window['measurmenttype'] ? @$window['measurmenttype'] : '' ?></td>
                        <td><?= @$window['chain_drop'] ? @$window['chain_drop'] . @$window['unit'] : '0' . @$window['unit'] ?></td>
                        <!--  <td></td>
                        <td>0</td>
                        <td></td>
                        <td></td> -->
                    </tr>
            <?php
                    $window_count++;
                        $window_counts++;
                }
            } ?>
            <!-- -->

            <tr>

                <td colspan="8" style="padding-top: 50px; padding: 5px; vertical-align: top;"></td>

            </tr>

        </table>
          <?php if (!empty($quotation->remarks) ) : ?>
    <div class="remarks">
        <strong>Remarks:</strong> <?= $quotation->remarks ?>
    </div>
<?php endif; ?>


        <table style="width: 80%; line-height: inherit; text-align: left;">

            <tr class="heading">

                <td style="padding: 5px; vertical-align: top; background: #eee; border-bottom: 2px solid #ddd; font-weight: bold;">Product</td>

                <td style="padding: 5px; vertical-align: top; background: #eee; border-bottom: 2px solid #ddd; font-weight: bold;">Details</td>

                <td style="padding: 5px; vertical-align: top; background: #eee; border-bottom: 2px solid #ddd; font-weight: bold;">Total Metres</td>

            </tr>

            <!-- -->
            <!-- -->

        </table>

       

    </div>

</body>

</html>