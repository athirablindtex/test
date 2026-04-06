<div class="main-panel">
  <div class="content">
    <div class="page-inner">
      <div class="page-header">
        <h4 class="page-title">Quotation</h4>
        <div class="btn-group btn-group-page-header ml-auto">

        </div>
      </div>
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

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
                      <input type="date"  id="toDate" class="form-control" name="to" value="<?= $_GET['to'] ?? ''; ?>" placeholder="Date To">
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
                         <!-- Customer Phone -->
             

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
        $count = count($tabledata);
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

            <!-- Bulk Delete Button -->
            <button id="bulkDeleteBtn" class="btn btn-danger btn-sm">
              <i class="fa fa-trash mr-1"></i> Bulk Delete
            </button>



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
                <th>Invoice No</th>
                <th>Customer Name</th>
                <th>Customer Phone</th>
                <th>Company</th>
                <th>Amount</th>
                <th>Order Status</th>
                <th>Payment Status</th>
                <th style="width: 10%">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $sl = 1;
              foreach ($tabledata as $product) {
              ?>
                <tr>
                  <label class="table-checkbox">
                    <td><input type="checkbox" class="rowCheckbox" value="<?= $product['id']; ?>"></td>
                  </label>
                  <td><?= $sl++; ?></td>
                  <td><?= date('d-m-Y', strtotime(@$product['created_date'])); ?></td>
                  <td><?= @$product['sales_person_name']; ?></td>
                  <td><?= @$product['invoiceno'] ?: @$product['invoiceno']; ?></td>
                  <td><?= @$product['cust_name'] ?: @$product['customer_name']; ?></td>
                  <td><?= @$product['cust_phone'] ?: @$product['customer_phone']; ?></td>
                  <td><?= @$product['company_name']; ?></td>
                  <td><?= @$product['total']; ?></td>
                  <td><?php
                      $status = $product['status'] ?? '';

                      if ($status === 'Balance Paid') {
                        $display_status = 'Confirmed Order';
                        $badge_class = 'bg-success text-white'; // green
                      } else {
                        $display_status = $status;
                        $badge_class = 'bg-warning text-dark'; // yellow
                      }
                      ?>
                    <span class="badge border-0 <?= $badge_class; ?>"><?= $display_status; ?></span>
                    </span>
                  </td>
                  <td>
                    <?php
                    $advance   = isset($product['advance']) ? (float) $product['advance'] : 0.0;
                    $sub_total = isset($product['sub_total']) ? (float) $product['sub_total'] : 0.0;
                    $status    = strtolower(trim($product['status'] ?? ''));
                    $confirmed = (int) ($product['confirm'] ?? 0);
                    $id        = (int) ($product['id'] ?? 0);

                    if ($confirmed == 0) {
                      $final_status = 'Pending';
                      $badge_class  = 'bg-secondary text-white';
                    } elseif ($advance > 0 && $advance < $sub_total && in_array($status, ['confirmed', 'confirmed order'])) {

                      $final_status = 'Partially Paid';
                      $badge_class  = 'bg-warning text-dark';
                    } elseif (
                      in_array($status, ['balance paid', 'confirmed order', 'confirmed']) &&
                      ($advance >= $sub_total || $confirmed == 1)
                    ) {

                      $final_status = 'Fully Paid';
                      $badge_class  = 'bg-success text-white';
                    } else {
                      $final_status = 'Unknown';
                      $badge_class  = 'bg-light text-dark';
                    }


                    ?>

                    <span class="badge border-0 <?= $badge_class; ?>"><?= $final_status; ?></span>

                  </td>
<td>
  <div class="form-button-action">

    <!-- View -->
    <button data-toggle="modal"
            data-target="#exampleModal-<?= $product['id']; ?>"
            type="button"
            class="btn btn-link btn-primary btn-lg"
            title="View">
      <i class="fa fa-eye"></i>
    </button>

    <!-- Resend Email -->

      <button type="button"
              class="btn btn-link btn-info btn-lg resend-email"
              data-id="<?= $product['id']; ?>"
              data-email="<?= htmlspecialchars($product['cust_email']); ?>"
              title="Resend Email">
        <i class="fa fa-envelope"></i>
      </button>


    <!-- WhatsApp -->
    <?php if (!empty($product['cust_phone'])) {
        $phone = preg_replace('/\D/', '', $product['cust_phone']);

        // UAE fix (optional)
        if (strlen($phone) == 9) {
            $phone = '971' . $phone;
        }

        $name = !empty($product['cust_name'])
                  ? $product['cust_name']
                  : $product['customer_name'];

        $company = $product['company_name'];

        $msg = urlencode(
          "Hello $name,\n\n".
          "Your quotation from $company is ready.\n".
          "Ref: {$product['id']}\n".
          "Total: {$product['total']}\n\n".
          "Thank you,\n$company"
        );
    ?>
      <a href="https://wa.me/<?= $phone ?>?text=<?= $msg ?>"
         target="_blank"
         class="btn btn-link btn-success btn-lg"
         title="Send WhatsApp">
        <i class="fa fa-whatsapp"></i>
      </a>
    <?php } ?>

    <!-- Delete -->
    <button type="button"
            data-id="<?= $product['id']; ?>"
            class="btn btn-link btn-danger btn-lg delete-quotation"
            title="Remove">
      <i class="fa fa-times"></i>
    </button>

  </div>
</td>
                </tr>
              <?php } ?>
            </tbody>
          </table>

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
        src="<?= base_url('uploads/worksheet/' . 'WS-QTN-'. $invoice_no . '.pdf'); ?>"
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
          <!--<li style="margin-top:6px;">-->
          <!--  <strong>Permanent Delete</strong> –-->
          <!--  The quotation data will be <b>deleted permanently</b> and <b>cannot be recovered</b>.-->
          <!--</li>-->
        </ul>

        <p class="text-danger mt-2" style="font-size:12px;">
          ⚠ Permanent delete action cannot be undone.
        </p>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-warning" id="softDeleteBtn">Soft Delete</button>
        <!--<button type="button" class="btn btn-danger" id="permanentDeleteBtn">Permanent Delete</button>-->
      </div>
    </div>
  </div>
</div>






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









  $('#basic-datatables').DataTable({

  });
  //   $('#multi-filter-select').DataTable({});

  // });
</script>