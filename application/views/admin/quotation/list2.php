<div class="main-panel">
  <div class="content">
    <div class="page-inner">
      <div class="page-header">
        <h4 class="page-title">
          <?php
          $status = $this->input->get('status');
          $confirm = $this->input->get('confirm');

          if ($status == 'Confirmed order') {
            echo "Invoices";
          } elseif ($confirm == '0') {
            echo "Quotations";
          } else {
            echo "Quotation / Confirmed Orders";
          }
          ?>
        </h4>
        <div class="btn-group btn-group-page-header ml-auto">

        </div>
      </div>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
      <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
      <style>
        /* ===== TABLE LOOK ===== */
        /* ===== TABLE HEADER (TH) FIX ===== */
        .table thead th {
          padding: 14px 18px !important;
          /* MORE SPACE */
          font-size: 12px;
          font-weight: bold;
          text-transform: uppercase;
          letter-spacing: 0.6px;
          color: #374151;
          background: #f8f9fd;
          border-bottom: 2px solid #e5e7eb;
          white-space: nowrap;
          text-align: left;
        }

        .table td.date-col {
          white-space: nowrap;
          /* prevent breaking into 2 lines */
          min-width: 110px;
          text-align: center;
        }

        /* First checkbox column center */
        .table thead th:first-child {
          text-align: center;
          width: 40px;
        }

        /* Action column center */
        .table thead th:last-child {
          text-align: center;
          width: 140px;
        }

        /* ===== HEADER SEPARATOR LINE EFFECT ===== */
        .table thead tr {
          box-shadow: inset 0 -1px 0 #e5e7eb;
        }

        /* ===== ALIGN BODY CELLS WITH HEADER ===== */
        .table tbody td {
          padding: 16px 20px !important;
          vertical-align: middle;
        }

        /* ===== REMOVE BORDER CLUTTER ===== */
        .table th,
        .table td {
          border-top: none !important;
        }

        /* ===== OPTIONAL: STICKY HEADER ===== */
        .table thead th {
          position: sticky;
          top: 0;
          z-index: 2;
        }

        .table-striped tbody tr:nth-of-type(odd) {
          background-color: #fafbff;
        }

        .table-hover tbody tr:hover {
          background-color: #eef2ff;
        }

        /* ===== TEXT CONTROL ===== */
        .text-nowrap {
          white-space: nowrap;
        }

        .customer-name {
          max-width: 220px;
          white-space: nowrap;
          overflow: hidden;
          text-overflow: ellipsis;
        }

        /* ===== BADGES ===== */
        .badge {
          font-size: 11px;
          padding: 6px 12px;
          border-radius: 14px;
          font-weight: 500;
          white-space: nowrap;
        }

        .phone-cell {
          white-space: nowrap;
          font-weight: 500;
          color: #374151;
        }

        /* ===== ACTION BUTTONS ===== */
        .form-button-action {
          display: flex;
          align-items: center;
          gap: 12px;
          /* MORE SPACE BETWEEN BUTTONS */
        }

        .form-button-action .btn {
          padding: 6px 10px !important;
          border-radius: 8px;
        }

        .form-button-action i {
          font-size: 16px;
        }

        /* ===== ICON COLORS ===== */
        .btn-view i {
          color: #3b82f6;
        }

        .btn-email i {
          color: #0ea5e9;
        }

        .btn-whatsapp i {
          color: #25D366;
        }

        .btn-delete i {
          color: #ef4444;
        }

        /* ===== CHECKBOX ALIGNMENT ===== */
        .table-checkbox {
          display: flex;
          align-items: center;
          justify-content: center;
        }

        /* ===== RESPONSIVE CLEANUP ===== */
        @media (max-width: 1200px) {
          .customer-name {
            max-width: 160px;
          }
        }

        .status-processing {
          background: #28a745;
          color: #fff;
          font-weight: 600;
        }

        .status-hold {
          background: #ffc107;
          color: #000;
          font-weight: 600;
        }

        .dropdown-menu {
          min-width: 70px;
          text-align: center;
        }

        .dropdown-menu .btn {
          padding: 5px;
        }

        /* Manufacturing select small */
        .small-status {
          width: 120px;
          padding: 3px 6px;
          font-size: 13px;
          border-radius: 6px;
        }

        /* Status colors */
        .status-processing {
          background: #e8f5e9;
          color: #2e7d32;
          border: 1px solid #c8e6c9;
        }

        .status-hold {
          background: #fff3cd;
          color: #856404;
          border: 1px solid #ffeeba;
        }

        /* Hold reason */
        .hold-reason {
          font-size: 12px;
          color: #dc3545;
          margin-top: 4px;
          max-width: 140px;
        }

        /* Dropdown actions */
        .action-icons {
          display: flex;
          gap: 8px;
          padding: 6px 10px;
        }

        /* Small dropdown */
        .dropdown-menu {
          min-width: 150px;
          padding: 5px;
        }

        /* Action icons */
        .action-icons .btn {
          padding: 4px 6px;
          font-size: 14px;
        }

        /* Column width control */
        #basic-datatables th:nth-child(2) {
          width: 50px;
        }

        /* ID */
        #basic-datatables th:nth-child(3) {
          width: 110px;
        }

        /* Date */
        #basic-datatables th:nth-child(4) {
          width: 110px;
        }

        /* SP */
        #basic-datatables th:nth-child(5) {
          width: 140px;
        }

        /* Company */
        #basic-datatables th:nth-child(6) {
          width: 120px;
        }

        /* Invoice */
        #basic-datatables th:nth-child(7) {
          width: 160px;
        }

        /* Customer */
        #basic-datatables th:nth-child(8) {
          width: 130px;
        }

        /* Phone */
        #basic-datatables th:nth-child(9) {
          width: 90px;
        }

        /* Amount */
        #basic-datatables th:nth-child(10) {
          width: 130px;
        }

        /* Order */
        #basic-datatables th:nth-child(11) {
          width: 110px;
        }

        /* MFG */
        #basic-datatables th:nth-child(12) {
          width: 130px;
        }

        /* Payment */
        #basic-datatables th:nth-child(13) {
          width: 70px;
        }
        /* Table base */
#basic-datatables {
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    overflow: hidden;
    font-size: 14px;
}

/* Header */
#basic-datatables thead {
    background: #ffffff;
    border-bottom: 1px solid #e5e7eb;
}

#basic-datatables thead th {
    padding: 12px 10px;
    font-size: 12px;
    font-weight: 600;
    color: #6b7280;
    text-transform: uppercase;
}

/* Body */
#basic-datatables tbody td {
    padding: 12px 10px;
    border-bottom: 1px solid #f1f1f1;
    color: #374151;
}

/* Row hover (very subtle) */
#basic-datatables tbody tr:hover {
    background: #fafafa;
}


        /* Action */
      </style>

      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-body">
              <form method="get" action="<?= base_url('admin/quotation/list'); ?>">
                <div class="row">
                  <style>
                    input[type="radio"],
                    input[type="checkbox"] {

                      visibility: visible !important;
                    }
                  </style>

                  <div class="col-md-4 mb-3">
                    <input
                      type="text"
                      class="form-control"
                      name="invoiceno"
                      value="<?= trim($_GET['invoiceno'] ?? '') ?>"
                      placeholder="Search invoice ">
                  </div>
                  <!-- Company -->
                  <div class="col-md-4 mb-3">
                    <select id="company" class="form-control company" name="company">
                      <option value="">Select Company</option>
                      <?php foreach ($company as $c) { ?>
                        <option value="<?= $c->id; ?>" <?= (isset($_GET['company']) && $_GET['company'] == $c->id) ? 'selected' : ''; ?>>
                          <?= $c->name; ?>
                        </option>
                      <?php } ?>
                    </select>
                  </div>

                  <!-- Sales Person -->
                  <div class="col-md-4 mb-3">
                    <select class="form-control sales_person " name="sales_person">
                      <option value="">Sales Person</option>
                      <?php foreach ($sales_person as $sp) { ?>
                        <option value="<?= $sp->id; ?>" <?= (isset($_GET['sales_person']) && $_GET['sales_person'] == $sp->id) ? 'selected' : ''; ?>>
                          <?= $sp->name; ?>
                        </option>
                      <?php } ?>
                    </select>
                  </div>

                  <!-- Date From -->
                  <div class="col-md-4 mb-3">
                    <div class="input-group">
                      <input type="text" id="fromDate" class="form-control" name="from" value="<?= $_GET['from'] ?? ''; ?>" placeholder="Date From">
                      <div class="input-group-append">
                        <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                      </div>
                    </div>
                  </div>

                  <!-- Date To -->
                  <div class="col-md-4 mb-3">
                    <div class="input-group">
                      <input type="date" id="toDate" class="form-control" name="to" value="<?= $_GET['to'] ?? ''; ?>" placeholder="Date To">
                      <div class="input-group-append">
                        <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4 mb-3">
                    <input type="text" class="form-control" name="customer_name"
                      value="<?= trim($_GET['customer_name'] ?? ''); ?>" placeholder="Customer Name">
                  </div>
                  <!-- Customer Phone -->
                  <div class="col-md-4 mb-3">
                    <input type="text" class="form-control" name="customer_phone"
                      value="<?= trim($_GET['customer_phone'] ?? ''); ?>" placeholder="Customer Phone Number">
                  </div>

                  <!-- Status -->
                  <div class="col-md-4 mb-3">
                    <select name="status" class="form-control">
                      <option value="">Select Status</option>
                      <?php foreach ($status_list as $s) {
                        // Skip showing "Balance Paid" in dropdown
                        if (strtolower($s->status) == 'balance paid') continue;
                      ?>
                        <option value="<?= $s->status; ?>"
                          <?= (isset($_GET['status']) && $_GET['status'] == $s->status) ? 'selected' : ''; ?>>
                          <?= ucfirst($s->status); ?>
                        </option>
                      <?php } ?>
                    </select>
                  </div>
                  <!-- Global Search -->


                  <!-- Search Button -->
                  <div class="col-md-12 text-right">
                    <button type="submit" class="btn btn-primary px-4">
                      <i class="fa fa-search"></i> Search
                    </button>
                    <a href="<?= strtok($_SERVER["REQUEST_URI"], '?'); ?>" class="btn btn-secondary px-4">
                      <i class="fa fa-undo"></i> Reset
                    </a>
                  </div>

                </div>
              </form>



              <hr>
            </div> <!-- /.card-body -->
          </div> <!-- /.card -->
        </div> <!-- /.col-md-12 -->
      </div> <!-- /.row -->

      <div class="table-responsive">
        <?php

        if ($count > 0) {
        ?>
          <div class="d-flex justify-content-between align-items-center mb-3">

            <!-- Total Records -->
            <h6 class="mb-0 d-flex align-items-center">
              Total Records
              <span class="ml-2 px-3 py-1 badge badge-pill text-white"
                style="background: linear-gradient(135deg, #6f42c1, #9b59b6);
             font-size: 0.95rem;
             box-shadow: 0 2px 4px rgba(0,0,0,0.15);">
                <?= $count; ?>
              </span>
            </h6>

            <!-- Right-side Actions -->
            <div class="d-flex gap-2">

              <!-- Transfer -->
              <!-- <button id="transferBtn" class="btn btn-warning btn-sm transfer-quotation">
      <i class="fa fa-share mr-1"></i> Transfer
    </button> -->

              <!-- Bulk Delete -->
              <button id="bulkDeleteBtn" class="btn btn-danger btn-sm">
                <i class="fa fa-trash mr-1"></i> Bulk Delete
              </button>

            </div>

          </div>

          <table id="basic-datatables" class="display table table-striped table-hover">
            <thead>
              <tr>
                <th>
                  <label class="table-checkbox">
                    <input type="checkbox" id="selectAllCheckbox">
                    <span></span>
                  </label>
                </th>
                <th>ID</th>
                <th>Date</th>
                <th>SP Name</th>
                <th>Company</th>
                <th>Invoice No</th>
                <th>Customer Name</th>
                <!-- <th>Customer Phone</th> -->
                <!-- 
                <th>Amount</th> -->
                <th>Order Status</th>
                <th>Manufacturing<br>Status</th>
                <th>Payment Status</th>

                <th style="width:10%">Action</th>
              </tr>
            </thead>

            <tbody>
              <?php $sl = $sl_start;
              foreach ($tabledata as $product): ?>

                <?php
                $rawPhone = $product['cust_phone'] ?? '';

                // Remove non-numeric characters
                $phone = preg_replace('/\D/', '', $rawPhone);

                // Take last 9 digits
                $last9 = substr($phone, -9);

                // Add UAE country code
                $displayPhone = '+971 ' . $last9;


                /* ---------- CUSTOMER DETAILS ---------- */
                $name    = $product['cust_name'] ?? $product['customer_name'];
                $company = $product['company_name'];
                ?>

                <tr>
                  <!-- Checkbox -->
                  <td>
                    <label class="table-checkbox">
                      <input type="checkbox" class="rowCheckbox" value="<?= $product['id']; ?>">
                      <span></span>
                    </label>
                  </td>

                  <td><?= $sl++; ?></td>
                <td class="date-col">
                <?= date( 'd-m-Y',strtotime(!empty($product['created_date'])  ? $product['created_date']  : $product['created_date'])
                ); ?>
                    </td>
                 <td><?= $product['sales_person_name']; ?></td>
                  <td><?= $company; ?></td>
                  <td><?= $product['invoiceno']; ?></td>
                  <td class="customer-name"><?= $name; ?></td>
                  <!-- <td class="phone-cell"> $displayPhone; ?></td> -->

                  <!-- <td> //$product['sub_total']; ?></td> -->

                  <!-- Order Status -->
                  <td>
                    <?php
                    $status = $product['status'] ?? '';
                    if ($status === 'Balance Paid') {
                      $badge = 'bg-success text-white';
                      $text  = 'Confirmed Order';
                    } else {
                      $badge = 'bg-warning text-dark';
                      $text  = $status;
                    }
                    ?>
                    <span class="badge <?= $badge; ?>"><?= $text; ?></span>
                  </td>

                  <td>

                    <?php $manu_status = $product['manufacturing_status'] ?? 'Processing'; ?>

                    <?php if ($product['confirm'] == 1) { ?>
                      <select class="manufacturing-status small-status 
                     <?= ($manu_status == 'Processing') ? 'status-processing' : 'status-hold'; ?>"
                        data-id="<?= $product['id']; ?>">

                        <option value="Processing"
                          <?= ($manu_status == 'Processing') ? 'selected' : '' ?>>
                          Processing
                        </option>

                        <option value="On Hold"
                          <?= ($manu_status == 'On Hold') ? 'selected' : '' ?>>
                          On Hold
                        </option>

                      </select>

                      <?php if ($manu_status == 'On Hold' && !empty($product['sales_remarks'])): ?>

                        <div class="hold-reason">
                          <i class="fa fa-info-circle"></i>
                          <?= $product['sales_remarks']; ?>
                        </div>

                      <?php endif; ?>

                    <?php } ?>

                  </td>
                  <!-- Payment Status -->
                  <td>

                    <?php
                    $token     = $product['token'] ?? '';
                    $advance   = (float) ($product['advance'] ?? 0);
                    $subTotal  = (float) ($product['sub_total'] ?? 0);
                    $confirmed = (int) ($product['confirm'] ?? 0);

                    $status = strtolower(trim($product['status'] ?? ''));
                    $remaining = $subTotal - $advance;

                    if ($confirmed === 0) {
                      $payText = 'Pending';
                      $payCls  = 'bg-secondary text-white';
                    } elseif ($advance > 0 && $advance < $subTotal) {
                      $payText = 'Partially Paid';
                      $payCls  = 'bg-warning text-dark';
                    } else {
                      $payText = 'Fully Paid';
                      $payCls  = 'bg-success text-white';
                    }
                    ?>

                    <!-- STATUS -->
                    <span class="badge <?= $payCls; ?>"><?= $payText; ?></span>

                    <!-- ACTION -->
                    <div class="mt-1">

                      <?php if ($status !== 'save for later' && !empty($token)): ?>
                        <?php if ($payText === 'Partially Paid'): ?>
                          <!-- 
                <div class="text-danger small mb-1">
                    Balance: AED <?= number_format($remaining, 2); ?>
                </div> -->

                          <a href="<?= base_url('admin/quotation/checkout/' . $token); ?>?amount=<?= $remaining; ?>"
                            class="btn btn-sm btn-danger badge">
                            <i class="fa fa-wallet me-1"></i> Pay Balance
                          </a>

                        <?php elseif ($payText === 'Pending'): ?>

                          <a href="<?= base_url('payment/checkout/' . $token); ?>"
                            class="btn btn-sm btn-primary badge">
                            <i class="fa fa-bolt me-1"></i> Pay Now
                          </a>

                        <?php else: ?>

                          <button class="btn btn-sm btn-success" disabled>
                            <i class="fa fa-check me-1 badge"></i> Paid
                          </button>

                        <?php endif; ?>
                      <?php endif; ?>

                    </div>

                  </td> <!-- Actions -->
                  <td>

                    <div class="dropdown">

                      <!-- 3 dots button -->
                      <button class="btn btn-link btn-lg" data-toggle="dropdown">
                        <i class="fa fa-ellipsis-v"></i>
                      </button>

                      <!-- dropdown actions -->
                      <div class="dropdown-menu dropdown-menu-right">
                        <div class="action-icons">

                          <!-- View -->
                          <button class="btn btn-link btn-primary"
                            data-toggle="modal"
                            data-target="#exampleModal-<?= $product['id']; ?>"
                            title="View">
                            <i class="fa fa-eye"></i>
                          </button>

                          <!-- Transfer -->
                          <button class="btn btn-link btn-warning transfer-quotation"
                            data-id="<?= $product['id']; ?>"
                            data-company="<?= $product['company_id']; ?>"
                            title="Transfer Quotation">
                            <i class="fa fa-share"></i>
                          </button>

                          <?php
                          $pdfUrl  = base_url('uploads/invoice/INV-QTN-' . $product['invoiceno'] . '.pdf');
                          $docType = ($confirmed === 1) ? 'Confirmed Invoice' : 'Quotation';

                          $msg = "Hello $name,\n\n"
                            . "Your *$docType* from *$company* is ready.\n\n"
                            . "🧾 Ref: {$product['invoiceno']}\n"
                            . "📄 Download PDF:\n$pdfUrl\n\n"
                            . "Thank you,\n$company";
                          ?>

                          <!-- WhatsApp -->
                          <?php if (!empty($phone)):
                            $waPhone = '971' . $last9;

                          ?>

                            <a href="https://api.whatsapp.com/send?phone=<?= $waPhone; ?>&text=<?= rawurlencode($msg); ?>"
                              target="_blank"
                              class="btn btn-link btn-success"
                              title="Send WhatsApp">
                              <i class="fa-brands fa-whatsapp"></i>
                            </a>
                          <?php endif; ?>

                          <!-- Delete -->
                          <button class="btn btn-link btn-danger delete-quotation"
                            data-id="<?= $product['id']; ?>"
                            title="Delete">
                            <i class="fa fa-times"></i>
                          </button>

                        </div>

                      </div>

                    </div>

                  </td>
                </tr>

              <?php endforeach; ?>
            </tbody>
          </table>
          <div class="d-flex justify-content-end mt-3">
            <?= $pagination; ?>
          </div>

        <?php } else { ?>
          <div class="text-center p-5">
            <i class="fa fa-exclamation-triangle fa-3x text-warning mb-3"></i>
            <h5 class="text-muted">Sorry, no data found</h5>
          </div>
        <?php } ?>
      </div>

    </div>

  </div>
</div>
</div>
</div>
</div>
</div>


<?php foreach ($tabledata as $product) { ?>
  <!-- Model -->
  <div class="modal modal-vv fade" id="exampleModal-<?= @$product['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Quotaion #<?= @$product['id'] ?></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body p-4">
          <div class="row d-none">
            <div class="col mx-auto form-inline">
              <div class="form-group mb-2 mx-auto">
                <label class="pr-2">Quataion Status</label>
                <select class="form-control select-type" id="formGroupDefaultSelect" style="height: 40px;">
                  <option>Status 1</option>
                  <option>Status 2</option>
                  <option>Status 3</option>
                  <option>Status 4</option>
                  <option>Status 5</option>
                </select>
                <!--    <button class="btn btn-primary rounded-0">Search</button> -->
              </div>
            </div>
          </div>
          <!-- <hr /> -->
          <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item submenu">
              <a class="nav-link active show" data-toggle="tab" href="#tabs-1-<?= @$product['id']; ?>" role="tab" aria-selected="true">Customer</a>
            </li>
            <li class="nav-item submenu">
              <a class="nav-link" data-toggle="tab" href="#tabs-2-<?= @$product['id']; ?>" role="tab" aria-selected="false">Survey</a>
            </li>
            <li class="nav-item submenu">
              <a class="nav-link" data-toggle="tab" href="#tabs-3-<?= @$product['id']; ?>" role="tab" aria-selected="false">Invoice</a>
            </li>
            <li class="nav-item submenu">
              <a class="nav-link" data-toggle="tab" href="#tabs-4-<?= @$product['id']; ?>" role="tab" aria-selected="false">Worksheet</a>
            </li>
          </ul>
          <!-- Tab panes -->
          <div class="tab-content">
            <div class="tab-pane active show" id="tabs-1-<?= @$product['id']; ?>" role="tabpanel">
              <div class="card-body p-0 mt-3">
                <div class="row">
                  <div class="col-md-4 mb-2">
                    <strong class="d-block mb-2">Quotaion To:</strong>
                    <address>
                      <?= @$product['cust_name'] != "" ? @$product['cust_name'] : @$product['customer_name']; ?><br>
                      <?= @$product['cust_address']; ?>
                    </address>
                  </div>
                  <div class="col-md-3 mb-4"> <strong class="d-block mb-2">Contact No</strong>
                    <a href="tel:98575854585" style="font-size: 14px;/* color: #0056b3; *//* text-decoration: underline; *//* font-weight: 600; */"><?= @$product['cust_phone'] != "" ? $product['cust_phone'] : $product['customer_phone']; ?></a>
                  </div>
                  <div class="col-md-3 mb-4"> <strong class="d-block mb-2">Email</strong>
                    <a href="mailto:test@test.com" style="
                                                            font-size: 14px;
                                                            "><?= @$product['cust_email']; ?></a>
                  </div>
                  <div class="col-md-2 mb-4"> <strong class="d-block mb-2">Price</strong>
                    <span>AED: <?= @$product['total']; ?></span>
                  </div>
                  <div class="col-sm-12">
                    <hr>
                    <strong class="d-block mb-2">Remarks</strong>
                    <span><?= @$product['remarks']; ?></span>
                  </div>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tabs-2-<?= @$product['id']; ?>" role="tabpanel">
              <p class="  mt-3 mb-0">Total No of windows: <?= @$product['window_count']; ?></p>
              <hr />
              <div class="table-responsive">
                <table id="multi-filter-select" class="display table table-striped table-hover">
                  <thead>
                    <tr>
                      <th>Product Type</th>
                      <th>Vendor</th>
                      <th>Fabric</th>
                      <th>Window Name</th>
                      <th>Room Name</th>
                      <th>Width</th>
                      <th>Drop</th>

                      <th>Chain Drop</th>
                      <th>Measuring Type</th>
                      <th>Cost</th>


                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach (@$product['rooms'] as $rm) { ?>
                      <?php foreach ($rm['windows'] as $wd) { ?>
                        <tr>
                          <td><?= @$wd['product_type']; ?></td>
                          <td><?= @$wd['sub_product_type']; ?></td>
                          <td><?= @$wd['product_name']; ?></td>
                          <td><?= @$wd['window_name']; ?></td>
                          <td><?= @$rm['room_name']; ?></td>
                          <td><?= @$wd['width'] . ' ' . @$wd['unit']; ?></td>
                          <td><?= @$wd['height'] . ' ' . @$wd['unit']; ?></td>

                          <td><?= @$wd['chain_drop'] . ' ' . @$wd['unit']; ?></td>
                          <td><?= @$wd['measuring_type']; ?></td>
                          <td><?= @$wd['total']; ?></td>

                        </tr>
                    <?php }
                    } ?>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="tab-pane" id="tabs-3-<?= @$product['id']; ?>" role="tabpanel">
              <div class="page-divider"></div>
              <div class="row">
                <div class="col-md-12">
                  <div class="row mt-3 mb-3">
                    <div class="col-10 d-flex align-items-center justify-content-end">
                      <div class="w-100">
                        <h4 class="page-title m-0">Invoice</h4>
                        <div class="clearfix"></div>
                      </div>
                    </div>
                    <div class="col-2 d-flex align-items-center justify-content-end">
                      <div class="w-100">
                        <?php $invoice_no = str_replace(['/', '\\'], '-', $product['invoiceno']); ?>
                        <a id="download_invoice"
                          href="<?= base_url('uploads/invoice/' . 'INV-QTN-' . $invoice_no . '.pdf'); ?>"
                          class="btn btn-primary float-lg-right text-white"
                          download>
                          Download Invoice
                        </a>
                      </div>
                    </div>
                  </div>


                  <div style="width:100%; height:90vh;">
                    <iframe
                      src="<?= base_url('uploads/invoice/' . 'INV-QTN-' . $invoice_no . '.pdf'); ?>"
                      width="100%"
                      height="100%"
                      style="border:none;">
                    </iframe>
                  </div>


                </div>
              </div>
            </div>
            <div class="tab-pane" id="tabs-4-<?= @$product['id']; ?>" role="tabpanel">
              <div class="page-divider"></div>
              <div class="row">
                <div class="col-md-12">
                  <div class="row mt-3 mb-3">
                    <div class="col-9 d-flex align-items-center justify-content-end">
                      <div class="w-100">
                        <h4 class="page-title m-0">Datasheet</h4>
                        <div class="clearfix"></div>
                      </div>
                    </div>
                    <div class="col-3 d-flex align-items-center justify-content-end">
                      <div class="w-100">
                        <a type="button" id="print_datasheet" href="<?php echo site_url('admin/' . $controller . '/print_quotation_worksheet/' . @$product['id'] . ''); ?>" class="btn btn-primary float-lg-right text-white">Print</a>
                        <div class="clearfix"></div>
                      </div>
                    </div>
                  </div>


                  <div class="job-box" style="max-width: 1800px; margin: auto; padding: 30px; border: 1px solid #eee; box-shadow: none; font-size: 14px; line-height: 16px; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; color: #555;">

                    <div style="width:100%; height:90vh;">
                      <iframe
                        src="<?= base_url('uploads/worksheet/' . 'WS-QTN-' . $invoice_no . '.pdf'); ?>"
                        width="100%"
                        height="100%"
                        style="border:none;">
                      </iframe>
                    </div>



                  </div>

                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Model -->
<?php } ?>


<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteQuotationModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="deleteModalLabel">Delete Quotation</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <p>Please choose how you want to delete this quotation:</p>

        <ul style="padding-left:18px;">
          <li>
            <strong>Soft Delete</strong> –
            The quotation data will be stored in the system but <b>will not be visible</b> anywhere in the application.
          </li>
          <li style="margin-top:6px;">
            <strong>Permanent Delete</strong> –
            The quotation data will be <b>deleted permanently</b> and <b>cannot be recovered</b>.
          </li>
        </ul>

        <p class="text-danger mt-2" style="font-size:12px;">
          ⚠ Permanent delete action cannot be undone.
        </p>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-warning" id="softDeleteBtn">Soft Delete</button>
        <?php if (isset($session['admin_type']) && $session['admin_type'] == 1) { ?>

          <button type="button" class="btn btn-danger" id="permanentDeleteBtn">Permanent Delete</button>
        <?php  }  ?>
      </div>
    </div>
  </div>
</div>






</div>

</div>
</div>
</div>



<div class="modal fade" id="transferQuotationModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header bg-warning text-dark">
        <h5 class="modal-title">Transfer Quotation</h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <input type="hidden" id="transferQuotationId">
        <input type="hidden" id="transferCompanyId">

        <div class="form-group">
          <label>Sales Person (Same Company)</label>
          <select id="transferSalesPerson" class="form-control">
            <option value="">Select Sales Person</option>
          </select>
        </div>
        <!-- NEW: Transfer Reason -->
        <div class="form-group mt-3">
          <label>Transfer Reason <span class="text-danger">*</span></label>
          <textarea id="transferReason"
            class="form-control"
            rows="3"
            placeholder="Enter reason for transfer..."></textarea>
        </div>

        <small class="text-muted">
          ⚠ Only sales persons from the same company can be selected.
        </small>
      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button class="btn btn-warning" id="confirmTransferBtn">Transfer</button>
      </div>

    </div>
  </div>
</div>
<div class="modal fade" id="holdReasonModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">On Hold Reason</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="modal-body">

        <input type="hidden" id="hold_order_id">

        <textarea class="form-control"
          id="hold_reason"
          placeholder="Enter reason for On Hold"></textarea>

      </div>

      <div class="modal-footer">

        <button class="btn btn-primary" id="saveHoldReason">
          Save
        </button>

      </div>

    </div>
  </div>
</div>




<script>
  flatpickr("#fromDate", {
    altInput: true,
    altFormat: "d-m-Y",
    dateFormat: "Y-m-d"
  });

  flatpickr("#toDate", {
    altInput: true,
    altFormat: "d-m-Y",
    dateFormat: "Y-m-d"
  });

  $(document).on('click', '.transfer-quotation', function() {
    let quotationId = $(this).data('id');
    let companyId = $(this).data('company');

    $('#transferQuotationId').val(quotationId);
    $('#transferCompanyId').val(companyId);

    $('#transferSalesPerson').html('<option>Loading...</option>');

    $.ajax({
      url: '<?= base_url('admin/' . $controller . '/get_sales_person_by_company'); ?>',
      type: 'POST',
      dataType: 'json',

      data: {
        company_id: companyId
      },
      success: function(res) {
        let html = '<option value="">Select Sales Person</option>';
        $.each(res, function(_, sp) {
          html += `<option value="${sp.id}">${sp.name}</option>`;
        });
        $('#transferSalesPerson').html(html);
        $('#transferQuotationModal').modal('show');
      }
    });
  });
  $('#confirmTransferBtn').click(function() {
    let quotationId = $('#transferQuotationId').val();
    let salesPerson = $('#transferSalesPerson').val();
    let reason = $('#transferReason').val();

    if (!salesPerson) {
      alert('Please select a sales person');
      return;
    }
    if (!reason) {
      alert('Please add Reason');
      return;
    }

    $.ajax({
      url: '<?= base_url('admin/quotationtransfer/transfer_quotation'); ?>',
      type: 'POST',
      dataType: 'json',
      data: {
        quotation_id: quotationId,
        sales_person_id: salesPerson,
        reason: reason
      },
      success: function(res) {
        if (res.success) {
          $('#transferQuotationModal').modal('hide');
          alert(res.message || 'Successfully Transferred');
          if (res.redirect) {
            window.location.href = res.redirect;
          }
        } else {
          alert(res.message || 'Transfer failed');
        }
      }
    });
  });

  function loadSalesPersons(companyId, selectedSalesPersonId = '') {
    if (companyId) {
      $.ajax({
        url: '<?= base_url('admin/' . $controller . '/get_sales_person_by_company') ?>',
        type: 'POST',
        dataType: 'json',
        data: {
          company_id: companyId
        },
        success: function(response) {
          $('.sales_person').empty();
          $('.sales_person').append('<option value="">Select Sales Person</option>');

          $.each(response, function(index, contact) {
            const selected = (selectedSalesPersonId == contact.id) ? 'selected' : '';
            $('.sales_person').append('<option value="' + contact.id + '" ' + selected + '>' + contact.name + '</option>');
          });
        }
      });
    }
  }
  $('#selectAllCheckbox').on('change', function() {

    $('.rowCheckbox').prop('checked', this.checked);
    let countChecked = $('.rowCheckbox:checked').length;
    alert('Selected ' + countChecked + ' quotations.');
  });

  $('.rowCheckbox').on('change', function() {


    $('#selectAllCheckbox').prop(
      'checked',
      $('.rowCheckbox:checked').length === $('.rowCheckbox').length
    );
  });

  // When company dropdown changes manually
  $('.company').change(function() {
    const companyId = $(this).val();
    loadSalesPersons(companyId);
  });

  // Auto-load on page load (when company is already selected in URL)
  const companyFromUrl = '<?= isset($_GET['company']) ? $_GET['company'] : ''; ?>';
  const salesPersonFromUrl = '<?= isset($_GET['sales_person']) ? $_GET['sales_person'] : ''; ?>';

  if (companyFromUrl !== '') {
    loadSalesPersons(companyFromUrl, salesPersonFromUrl);
  }

  function getSelectedQuotationIds() {
    let ids = [];

    $('.rowCheckbox:checked').each(function() {
      ids.push($(this).val());
    });

    return ids;
  }


  let selectedQuotationId = null;
  let deleteMode = 'single';

  $(document).on('click', '#bulkDeleteBtn', function() {
    deleteMode = 'bulk';
    selectedQuotationId = null; //
    $('#deleteQuotationModal').modal('show'); // Show modal
  });

  $(document).on('click', '.delete-quotation', function() {

    deleteMode = 'single';
    selectedQuotationId = $(this).data('id');
    $('#deleteQuotationModal').modal('show'); // Show modal
  });


  $('#softDeleteBtn').click(function() {
    alert(deleteMode);

    handleDelete(0);
  });


  $('#permanentDeleteBtn').click(function() {
    alert(deleteMode);
    handleDelete(1);
  });

  function handleDelete(isPermanent) {
    if (deleteMode === 'single') {
      sendDeleteRequest(isPermanent);
    } else {
      sendBulkDeleteRequest(isPermanent);
    }
  }


  function sendDeleteRequest(isPermanent) {
    if (!selectedQuotationId) return;

    $.ajax({
      url: '<?= base_url('admin/' . $controller . '/delete_quotation') ?>',
      type: 'POST',
      dataType: 'json',
      data: {
        id: selectedQuotationId,
        permanent: isPermanent
      },
      success: function(response) {
        $('#deleteQuotationModal').modal('hide');
        if (response.success) {
          location.reload();
        } else {
          alert('Error deleting quotation.');
        }
      }
    });
  }

  function sendBulkDeleteRequest(isPermanent) {

    let selectedIds = getSelectedQuotationIds();

    if (selectedIds.length === 0) {
      alert('Please select at least one quotation.');
      return;
    }

    $.ajax({
      url: '<?= base_url('admin/' . $controller . '/delete_quotation') ?>',
      type: 'POST',
      dataType: 'json',
      data: {
        ids: selectedIds, // 👈 ARRAY
        permanent: isPermanent
      },
      success: function(response) {
        $('#deleteQuotationModal').modal('hide');

        if (response.success) {
          location.reload();
        } else {
          alert('Error deleting quotations.');
        }
      }
    });
  }





  $(document).on("change", ".manufacturing-status", function() {

    let status = $(this).val();
    let id = $(this).data("id");

    if (status === "On Hold") {

      $("#hold_order_id").val(id);
      $("#hold_reason").val("");

      $("#holdReasonModal").modal("show");

    } else {

      updateManufacturingStatus(id, status, '');

    }

  });
  $("#saveHoldReason").click(function() {

    let id = $("#hold_order_id").val();
    let reason = $("#hold_reason").val();

    if (reason.trim() === '') {
      alert("Please enter reason");
      return;
    }

    updateManufacturingStatus(id, 'On Hold', reason);

    $("#holdReasonModal").modal("hide");

  });

  function updateManufacturingStatus(id, status, remarks) {

    $.ajax({
      url: '<?= base_url('admin/' . $controller . '/update_manufacturing_status') ?>',
      type: "POST",
      data: {
        id: id,
        status: status,
        remarks: remarks
      },
      success: function(res) {
        location.reload();
      }
    });

  }

  $(document).on("change", ".manufacturing-status", function() {

    if ($(this).val() === "Processing") {
      $(this).removeClass("status-hold").addClass("status-processing");
    } else {
      $(this).removeClass("status-processing").addClass("status-hold");
    }

  });
  $('#basic-datatable2').DataTable({

  });
  //   $('#multi-filter-select').DataTable({});

  // });
</script>