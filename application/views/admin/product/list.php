<?php $band_type = array('Square Meter', 'Meter', 'Matrix'); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Modal Import -->
<div class="modal fade" id="importModal" aria-labelledby="importModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="card-title" id="importModalLabel">Import Fabrics</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <form id="" action="<?php echo site_url('admin/product/excel_import'); ?>" method="POST" enctype="multipart/form-data">
            <div class="modal-body">
               <div class="row">

                  <!-- COMPANY SELECT -->
         <div class="col-md-12">
            <div class="form-group form-group-default">
               <label>Select Company</label>
               <select name="company_id" class="form-control" required>
                  <option value="">Select Company</option>

       

                  <!-- Other companies -->
                  <?php foreach($companies as $c){ ?>
                     <option value="<?= $c->id ?>">
                        <?= $c->name ?>
                     </option>
                  <?php } ?>

               </select>
            </div>
         </div>
                  <div class="col-xs-12 col-sm-12 col-md-12">
                     <div class="form-group form-group-default">
                        <strong>Upload Excel/CSV File:</strong>
                        <input type="file" name="excel_file" id="excel_file" class="form-control" />
                     </div>
                  </div>
               </div>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
               <button type="submit" class="btn btn-primary" onclick="this.disabled=true;this.form.submit();">Import</button>
               <a href="<?php echo site_url('assets/templates/product_import_template.xls'); ?>" target="_blank">Download Template </a>
            </div>
         </form>
      </div>
   </div>
</div>
<!-- Modal Import -->
 <style>
input[type="checkbox"] {
    visibility: visible !important;
}
.card-body.add-product-form {
  
    background-color: #f5f7fa;
}

 </style>
<?php
$mode = $this->input->get('mode'); // add | edit | null

if ($mode === 'add') {
   $res  = null;
   $edit = 0;
}
?>
<div class="main-panel">
   <div class="content">
      <div class="page-inner">
         <div class="page-header">
            <h4 class="page-title"><?= @$page; ?></h4>
            <div class="btn-group btn-group-page-header ml-auto">
               <?php if (@$permissions['add']) { ?>
                  <button class="btn btn-secondary btn-round mr-2" data-toggle="modal" data-target="#importModal" id="ImportBtn">
                     <i class="fa fa-file-excel"></i>
                     Import
                  </button>

                  <a href="<?= site_url('admin/product/list?mode=add'); ?>"
                     class="btn btn-success btn-round">
                     <i class="fa fa-plus mr-2"></i> Add <?= @$page ?>
                  </a>
               <?php } ?>
            </div>
         </div>
         <div class="row">
            <div class="col-md-12">
     <div class="col-md-12 text-right mb-3">
    <button
        type="button"
        class="btn btn-outline-secondary mb-3"
        data-toggle="collapse"
        data-target="#collapseExample"
        aria-controls="collapseExample"
        data-toggle="tooltip"
        data-placement="left"
        title="Show / Hide section"
    >Show / Hide Form
        <i class="fa fa-eye-slash"></i>
    </button>
     </div>



               <div class="collapse <?= ($mode === 'add' || $edit == 1) ? 'show' : '' ?>" id="collapseExample">


                  <div class="card">
                     <div class="card-header">
                        <div class="card-title">
                           <?= ($edit == 1) ? 'Edit ' . $page : 'Add New ' . $page ?>
                        </div>

                     </div>
                     <div class="card-body add-product-form">

                        <form method="post">
                           <div class="row">

                              <div class="col-md-6">
                                 <div class="form-group form-group-default">
                                    <label>Product Type</label>
                                    <select class="form-control" name="product_type" id="product_type" required>
                                       <option value="">Product Type</option>
                                       <?php foreach ($product_types as $p) { ?>
                                          <option value="<?= @$p->id; ?>" <?= @$p->id == @$res->product_type ? 'selected' : ''; ?>><?= @$p->name; ?></option>
                                       <?php } ?>
                                    </select>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-group form-group-default">
                                    <label>Vendor</label>
                                    <select class="form-control" name="sub_product_type" id="sub_product_type" required>
                                       <option value=""> Vendor</option>
                                       <?php
                                       if (@$sub_product_types) {
                                          foreach (@$sub_product_types as $p) { ?>
                                             <option value="<?= @$p->id; ?>" <?= @$p->id == @$res->sub_product_type ? 'selected' : ''; ?>><?= @$p->name; ?></option>
                                       <?php }
                                       } ?>

                                    </select>
                                 </div>
                              </div>

                           </div>
                           <div class="row">
                              <div class="col-md-4">
                                 <div class="form-group form-group-default">
                                    <label>Name</label>
                                    <input id="Name" type="text" class="form-control" placeholder="Fill Name" value="<?php echo @$res->name != '' ? @$res->name : $this->input->post('name'); ?>" name="name" required>
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="form-group form-group-default">
                                    <label>Price Band Type</label>
                                    <select class="form-control" id="price_band_type" name="price_band_type" required>
                                       <option value="">Type</option>
                                       <?php foreach ($band_type as $b) { ?>
                                          <option value="<?= $b; ?>" <?php if ($b == @$res->price_band_type) {
                                                                        echo "selected";
                                                                     } ?>><?= $b; ?></option>
                                       <?php } ?>

                                    </select>
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="form-group form-group-default">
                                    <label>Price Band</label>
                                    <select class="form-control" id="price_band" name="price_band" required>
                                       <option value="">Price Band Type</option>
                                       <?php
                                       if (@$pricebands) {
                                          foreach ($pricebands as $p) { ?>
                                             <option value="<?= @$p->id; ?>" <?= @$p->id == @$res->price_band ? 'selected' : ''; ?>><?= @$p->name; ?></option>
                                       <?php    }
                                       } ?>

                                    </select>
                                 </div>
                              </div>

                              <div class="col-md-4">
                                 <div class="form-group form-group-default">
                                    <label>Fabric Code</label>
                                    <input id="fabric_code" type="text" step="any" placeholder="fab-01" class="form-control" name="fabric_code" value="<?php echo @$res->fabric_code != '' ? @$res->fabric_code  : $this->input->post('fabric_code'); ?>">
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="form-group form-group-default">
                                    <label>Blind code</label>
                                    <input id="blind_code" type="text" step="any" placeholder=" " class="form-control" name="blind_code" value="<?php echo @$res->blind_code != '' ? @$res->blind_code  : $this->input->post('blind_code'); ?>">
                                 </div>
                              </div>
                              <div class="col-md-3">
                                 <div class="form-group form-group-default">
                                    <label>Minimum Drop (mm)</label>
                                    <input id="Name" type="number" step="any" placeholder="0 mm" class="form-control" name="min_drop" value="<?php echo @$res->min_drop != '' ? @$res->min_drop : $this->input->post('min_drop'); ?>" required>
                                 </div>
                              </div>
                              <div class="col-md-3">
                                 <div class="form-group form-group-default">
                                    <label>Minimum Width (mm)</label>
                                    <input id="Name" type="number" step="any" placeholder="0 mm" class="form-control" name="min_width" value="<?php echo @$res->min_width != '' ? @$res->min_width : $this->input->post('min_width'); ?>" required>
                                 </div>
                              </div>
                              <div class="col-md-3">
                                 <div class="form-group form-group-default">
                                    <label>Maximum Drop (mm)</label>
                                    <input id="Name" type="number" step="any" placeholder="0 mm" class="form-control" name="max_drop" value="<?php echo @$res->max_drop != '' ? @$res->max_drop : $this->input->post('max_drop'); ?>" required>
                                 </div>
                              </div>
                              <div class="col-md-3">
                                 <div class="form-group form-group-default">
                                    <label>Maximum Width (mm)</label>
                                    <input id="Name" type="number" step="any" placeholder="0 mm" class="form-control" name="max_width" value="<?php echo @$res->max_width != '' ? @$res->max_width : $this->input->post('max_width'); ?>" required>
                                 </div>
                              </div>

                              <div class="col-md-12">
                                 <div class="form-group tagsinput form-group-default pb-2">
                                    <label for="tagsinput" class="d-block mb-2">Tags</label>
                                    <input type="text" value="<?= @$tags; ?>" name="tags" data-role="tagsinput" class="form-control mt-3 " style="height: auto !important" />
                                 </div>
                              </div>
                              <input type="hidden" name="id" value="<?php echo @$res->$module_id ? $res->$module_id : 0; ?>">
                              <div class="col-md-12 clearfix mt-3 mb-3  d-flex align-items-center justify-content-center justify-content-lg-center">
                                 <button type="submit" class="btn btn-success btn-rounded">
                                    <?= ($edit == 1) ? 'Update Product' : 'Add Product' ?>
                                 </button>

                              </div>
                           </div>
                        </form>
                     </div>
                  </div>
               </div>
               <div class="card">
                  <!--           <div class="card-header">
                     <div class="d-flex align-items-center">
                        <h4 class="card-title">Products</h4>
                        <button class="btn btn-primary btn-round ml-auto" data-toggle="modal" data-target="#addRowModal">
                        <i class="fa fa-plus"></i>
                        Add Company
                        </button>
                     </div>
                     </div> -->
                  <div class="card-body">
                     <form>
                        <div class="row">
                           
                             <div class="col-md-6">
                                 <div class="form-group form-group-default">
                                    <label>Select company</label>
                                    <select class="form-control filter" name="company_id" id="company_id" required>
                                       <option value="">Company</option>
                                       <?php foreach ($companies as $c) { ?>
                                          <option value="<?= @$c->id; ?>" <?= @$c->id == @$res->company_id ? 'selected' : ''; ?>><?= @$c->name; ?></option>
                                       <?php } ?>
                                    </select>
                                 </div>
                              </div>
                           <div class="col-md-6">
                              <div class="form-group form-group-default">
                                 <label>Product Type</label>
                                 <select class="form-control filter" name="product_type" id="product_type1">
                                    <option value="">Product Type</option>
                                    <?php foreach ($product_types as $p) { ?>
                                       <option value="<?= @$p->id; ?>" <?= @$p->id == @$this->input->get('product_type') ? 'selected' : ''; ?>><?= @$p->name; ?></option>
                                    <?php } ?>
                                 </select>
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group form-group-default">
                                 <label>Vendor</label>
                                 <select class="form-control filter" name="sub_product_type" id="sub_product_type1">

                                 </select>
                              </div>
                           </div>

                        </div>
                        <div class="row">

                           <div class="col-md-6">
                              <div class="form-group form-group-default ">
                                 <label>Price Band Type</label>
                                 <select class="form-control filter" id="price_band_type1" name="price_band_type">
                                    <option value="">Price Band Type</option>
                                    <?php foreach ($band_type as $b) { ?>
                                       <option value="<?= $b; ?>"><?= $b; ?></option>
                                    <?php } ?>
                                 </select>
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group form-group-default">
                                 <label>Price Band</label>
                                 <select class="form-control filter" id="price_band1" name="price_band">

                                 </select>
                              </div>
                           </div>
                           <div class="col-md-12">
                              <div class="input-group mb-3">
                                 <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1"> <i class="fa fa-search search-icon"></i></span>
                                 </div>
                                 <input type="text" name="search"  class="form-control rounded-0 filter " id="searchInput" placeholder="Search by name" aria-label="Username" aria-describedby="basic-addon1">
                                 <!-- <button class="btn btn-primary rounded-0">Search</button> -->
                              </div>
                              <div class="clearfix"></div>
                              <hr class="mt-4 mb-4 d-block" />                           </div>
                     </form>

                  </div>
                  <?php if (@$tabledata) { ?>
                     <span class="badge badge-secondary">

                        Total: <?= $total_rows ?>
                     </span>

                     <div class="d-flex justify-content-end mb-3">
                        <?php if (@$permissions['delete']) { ?>
                           <button id="bulkDeleteBtn" class="btn btn-danger btn-sm mr-2" disabled>
                              <i class="fa fa-trash"></i> Bulk Delete
                           </button>
                        <?php } ?>

         
            
      

                    

                        <button id="exportCsvBtn" class="btn btn-info btn-sm mr-2">
                           <i class="fa fa-file-csv"></i> Export CSV
                        </button>

                        <!-- <button id="downloadPdfBtn" class="btn btn-secondary btn-sm">
                           <i class="fa fa-file-pdf"></i> Download PDF
                        </button> -->
                     </div>

                     <div class="table-responsive">
                        <table id="basic-datatables" class="display table table-striped table-hover">
                           <thead>
                              <tr>
                              <th>
                              <input type="checkbox" id="selectAll">
                              </th>
                                 <th>ID</th>
                                 <th>Name</th>
                                 <th>Type</th>

                                 <th>Vendor</th>
                                 <th>Price Band Type</th>
                                 <th>Price Band</th>
                                 <th>Turnable</th>
                                 <th style="width: 10%">Action</th>
                              </tr>
                           </thead>
                           <tbody id="tabledata">
                              <?php $i = $offset; ?>
                              <?php foreach ($tabledata as $product) {
                                 $i++; ?>
                                 <tr>
                                       <td>
                                       <input type="checkbox"
                                       class="product-checkbox
                                       "
                                       value="<?= @$product['id']; ?>">
                                       </td>

                                    <td><?= $i ?></td>
                                    <td><?= @$product['name']; ?></td>
                                    <td><?= @$product['product_type_name']; ?></td>
                                    <td><?= @$product['sub_product_type_name']; ?></td>
                                    <td><?= @$product['price_band_type']; ?></td>
                                    <td><?= @$product['priceband_name']; ?></td>
                                    <td><?= @$product['turnable']; ?></td>
                                    <td>
                                       <div class="form-button-action">
                                          <?php if (@$permissions['add']) { ?>
                                             <a href="<?php echo site_url('admin/' . $controller . '/list/edit/' . @$product[$module_id] . ''); ?>" data-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-original-title="Edit Task">
                                                <i class="fa fa-edit"></i>
                                             </a>
                                                <button
                                                class="btn btn-link btn-secondary btn-sm copyBtn"
                                                data-id="<?= @$product[$module_id]; ?>" data-toggle="tooltip" title=""
                                                data-original-title="Duplicate">
                                                <i class="fa fa-copy"></i>
                                                </button>
                                          <?php } ?>
                                          <?php if (@$permissions['delete']) { ?>
                                             <a href="<?php echo site_url('admin/' . $controller . '/delete/' . @$product[$module_id] . ''); ?>" onclick="return confirm('<?php echo $this->lang->line('common_confirm_delete'); ?>');" data-toggle="tooltip" title="" class="btn btn-link btn-danger" data-original-title="Remove">
                                                <i class="fa fa-times"></i>
                                             </a>
                                          <?php } ?>
                                       </div>
                                    </td>
                                 </tr>
                              <?php } ?>


                           </tbody>
                        </table>
                        <div id="pagination">
                           <?= $pagination_links ?>
                        </div>
                     </div>
                  <?php } else { ?>
                     <div class="alert alert-danger" role="alert">
                        <strong></strong> <?php echo $this->lang->line('common_no_data'); ?>
                     </div>
                  <?php } ?>
                  <hr />

               </div>
            </div>
         </div>
      </div>
   </div>
</div>
</div>
<script>
   $(document).ready(function() {

      let productType = '<?= $this->input->get('product_type') ?>';
      let subProductType = '<?= $this->input->get('sub_product_type') ?>';
      let priceBandType = '<?= $this->input->get('price_band_type') ?>';
      let priceBand = '<?= $this->input->get('price_band') ?>';
    let name = '<?= $this->input->get('search') ?>';
    if(name){
        $('#searchInput').val(name);
}


      if (productType) {
         $('#product_type1').val(productType).trigger('change');
      }

      $(document).ajaxComplete(function() {
         if (subProductType) {
            $('#sub_product_type1').val(subProductType);
         }
         if (priceBand) {
            $('#price_band1').val(priceBand);
         }
      });

      if (priceBandType) {
         $('#price_band_type1').val(priceBandType).trigger('change');
      }
 
   });
   $('#product_type').change(function() {

      $.ajax({
         'type': 'POST',
         'data': {
            category: $('#product_type').val()
         },
         'url': '<?php echo site_url('admin/product/change_product_type'); ?>',
         'success': function(data) {
            $('#sub_product_type').html(data);
            $('#price_band_type').val('Type').trigger('change');
            $('#price_band').html('');
         }
      });
   });
   $('#product_type1').change(function() {
      $.ajax({
         'type': 'POST',
         'data': {
            category: $('#product_type1').val()
         },
         'url': '<?php echo site_url('admin/product/change_product_type'); ?>',
         'success': function(data) {
            $('#sub_product_type1').html(data);
         }
      });
   });
<?php
$editPriceBandType = @$res->price_band_type;
$editPriceBand     = @$res->price_band;
$editProductType   = @$res->product_type;
?>

   function loadPriceBand() {
    let product_type = $('#product_type').val();

    $.ajax({
        type: 'POST',
        url: '<?= site_url("admin/product/change_product_band_type") ?>',
        data: {
            category: $('#price_band_type').val(),
            product_type: product_type
        },
        success: function (data) {
            $('#price_band').html(data);

            <?php if ($edit == 1): ?>
            $('#price_band').val('<?= @$res->price_band ?>');
            <?php endif; ?>
        }
    });
}

 $('#price_band_type').on('change', function () {
    loadPriceBand();
});
<?php if ($edit == 1 && !empty($res->price_band_type)): ?>
$(document).ready(function () {
    loadPriceBand();
});
<?php endif; ?>


   $('#price_band_type1').change(function() {
      let price_band_type = $(this).val();
      let product_type = $('#product_type1').val();
      $.ajax({
         'type': 'POST',
         'data': {
            product_type: product_type,
            category: price_band_type
         },
         'url': '<?php echo site_url('admin/product/change_product_band_type'); ?>',
         'success': function(data) {
            $('#price_band1').html(data);
         }
      });
   });

   // $('#search').on('keyup', function() {
   //    var name = $(this).val().toLowerCase();
   //    if (name.length > 0) {
   //       $('#pagination').hide();
   //       $.ajax({
   //          url: '<?= base_url('admin/product/search') ?>',
   //          type: 'POST',
   //          data: {
   //             search: name
   //          },
   //          dataType: 'json',
   //          success: function(data) {


   //             let html = '';
   //             let i = 1;

   //             if (data.length > 0) {
   //                let html = '';
   //                let i = 1;

   //                $.each(data, function(index, pro) {
   //                   html += `<tr>
   //          <td>${i++}</td>
   //          <td>${pro.name}</td>
   //          <td>${pro.product_id}</td>
   //          <td>${pro.product_name}</td>
   //          <td>${pro.product_type_name}</td>
   //          <td>${pro.sub_product_type_name}</td>
   //          <td>${pro.price_band_type}</td>
   //          <td>${pro.priceband_name}</td>
   //          <td>${pro.turnable}</td>
   //          <td>`;

   //                   <?php if (@$permissions['add']) { ?>
   //                      html += `<a href="<?= site_url('admin/' . $controller . '/list/edit/') ?>${pro.id}" class="text-muted d-inline-block bg-white mr-1" data-toggle="tooltip" title="Edit">
   //          <i class="fa fa-edit"></i>
   //      </a>`;
   //                   <?php } ?>

   //                   <?php if (@$permissions['delete']) { ?>
   //                      html += `<a href="<?= site_url('admin/' . $controller . '/delete/') ?>${pro.id}" onclick="return confirm('<?= $this->lang->line('common_confirm_delete') ?>');" class="text-muted d-inline-block bg-white" data-toggle="tooltip" title="Remove">
   //          <i class="far fa-trash-alt"></i>
   //      </a>`;
   //                   <?php } ?>

   //                   html += `</td></tr>`;
   //                });

   //                $('#tabledata').html(html);
   //             } else {
   //                html = `<tr><td colspan="3">No matching results found.</td></tr>`;
   //             }

   //             $('#tabledata').html(html);
   //          }

   //       });
   //    } else {
   //       location.reload();
   //    }
   // });
   $('#exportCsvBtn').on('click', function () {
    let params = window.location.search; // keep filters
    window.location.href = '<?= site_url("admin/product/export_csv") ?>' + params;
});

$('#downloadPdfBtn').on('click', function () {
    let params = window.location.search; // keep filters
    window.location.href = '<?= site_url("admin/product/download_pdf") ?>' + params;
});
$(document).on('change', '.product-checkbox', function () {
    $('#bulkDeleteBtn').prop(
        'disabled',
        $('.product-checkbox:checked').length === 0
    );
});

$('#selectAll').on('change', function () {
    $('.product-checkbox').prop('checked', this.checked).trigger('change');
});

$('#bulkDeleteBtn').on('click', function () {

    Swal.fire({
        title: 'Are you sure?',
        html: `This will delete <b>ALL</b> products matching current filters.<br><br>This action cannot be undone.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Yes, delete all'
    }).then((result) => {

        if (!result.isConfirmed) return;

        $.ajax({
            url: '<?= site_url("admin/product/bulk_delete") ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                product_type: $('#product_type1').val(),
                sub_product_type: $('#sub_product_type1').val(),
                price_band_type: $('#price_band_type1').val(),
                price_band: $('#price_band1').val(),
                search: $('#searchInput').val()
            },
            success: function (res) {
                Swal.fire('Deleted!', res.deleted + ' products deleted', 'success')
                    .then(() => location.reload());
            }
        });
    });
});
$(document).on('click', '.copyBtn', function () {

    let productId = $(this).data('id');
    alert(productId);

    Swal.fire({
        title: 'Copy Product?',
        text: 'This will create a duplicate of this product.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, copy it',
        confirmButtonColor: '#3085d6'
    }).then((result) => {

        if (!result.isConfirmed) return;

        $.ajax({
            url: '<?= site_url("admin/product/copy_product") ?>',
            type: 'POST',
            dataType: 'json',
            data: { id: productId },
            success: function (res) {
                if (res.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Copied!',
                        text: 'Product copied successfully',
                        timer: 1200,
                        showConfirmButton: false
                    }).then(() => location.reload());
                } else {
                    Swal.fire('Error', 'Copy failed', 'error');
                }
            }
        });
    });
});



$('.filter').on('input', function () {
    $('form').submit();
});


</script>