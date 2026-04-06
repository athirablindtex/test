<?php $margin_type = array("Percentage", "Value"); ?>
<div class="main-panel">
   <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
   <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

   <style>


   </style>
   <div class="content">
      <div class="page-inner">
         <div class="row">
            <div class="col-md-12">
               <div class="card">
                  <div class="card-header">
                     <div class="d-flex align-items-center">
                        <h4 class="card-title"><?= @$page; ?></h4>
                        <?php if (@$permissions['add']) { ?>
                           <button class="btn btn-success btn-round ml-auto" data-toggle="modal" data-target="#addRowModal">
                              <i class="fa fa-plus"></i>
                              Add <?= @$page; ?>
                           </button>
                        <?php } ?>
                     </div>
                  </div>
                  <div class="card-body">
                     <!-- Modal -->
                   <div class="modal fade" id="addRowModal" tabindex="-1">
   <div class="modal-dialog modal-xl modal-dialog-centered">
      <div class="modal-content">

         <!-- HEADER -->
         <div class="modal-header border-0 pb-0">
            <h5 class="modal-title fw-bold">New Company</h5>
            <button type="button" class="btn-close close-model"></button>
         </div>

         <!-- BODY -->
         <div class="modal-body pt-2">
            <form action="" method="post" enctype="multipart/form-data" id="fm_form_data">

               <div class="row g-3">

                  <!-- LOGO -->
                  <div class="col-md-12 text-center mb-3">
                     <div class="position-relative d-inline-block">
                        <img class="rounded-circle shadow-sm"
                           width="110" height="110" id="image-label"
                           style="object-fit:cover; border:3px solid #f1f3f5;"
                           src="<?= is_file('uploads/users/' . @$res->image) ? base_url().'uploads/users/'.@$res->image : base_url().'uploads/placeholder/image1.png'; ?>">

                        <input type="file" class="d-none" id="uploadImg1" name="image">

                        <label for="uploadImg1"
                           class="btn btn-sm btn-light shadow position-absolute"
                           style="bottom:0; right:0; border-radius:50%;">
                           <i class="fa fa-pencil"></i>
                        </label>
                     </div>
                  </div>

                  <!-- BASIC INFO -->
                  <div class="col-md-6">
                     <label class="form-label">Name</label>
                     <input type="text" class="form-control"
                        name="name"
                        value="<?= @$res->name ?? $this->input->post('name'); ?>" required>
                  </div>

                  <div class="col-md-6">
                     <label class="form-label">Phone</label>
                     <input type="text" class="form-control"
                        name="phone"
                        value="<?= @$res->phone ?? $this->input->post('phone'); ?>" required>
                  </div>

                  <!-- EMAILS -->
                  <div class="col-md-6">
                     <label class="form-label">Company Email</label>
                     <input type="email" class="form-control"
                        name="company_email"
                        value="<?= @$res->company_email ?? $this->input->post('company_email'); ?>" required>
                  </div>

                  <div class="col-md-6">
                     <label class="form-label">Receiving Mail</label>
                     <input type="email" class="form-control"
                        name="email"
                        value="<?= @$res->email ?? $this->input->post('email'); ?>" required>
                  </div>

                  <div class="col-md-6">
                     <label class="form-label">Reply Mail</label>
                     <input type="email" class="form-control"
                        name="reply_mail"
                        value="<?= @$res->reply_mail ?? $this->input->post('reply_mail'); ?>" required>
                  </div>

                  <div class="col-md-6">
                     <label class="form-label">Password</label>
                     <input type="password" class="form-control"
                        name="password"
                        <?= @$res->id > 0 ? '' : 'required'; ?>>
                  </div>

                  <!-- ADDRESS -->
                  <div class="col-md-12">
                     <label class="form-label">Address</label>
                     <textarea class="form-control" rows="3"
                        name="address"><?= @$res->address ?? $this->input->post('address'); ?></textarea>
                  </div>

                  <!-- SECTION -->
                  <div class="col-md-12 mt-4">
                     <h6 class="fw-bold border-start ps-2" style="border-color:#6c63ff;">
                        Location & Finance
                     </h6>
                  </div>

                  <!-- COUNTRY -->
                  <div class="col-md-4">
                     <label class="form-label">Country</label>
                     <select class="form-select" name="country" id="country" required>
                        <option value="">Select</option>
                        <option value="AE" <?= (@$res->country == 'AE') ? 'selected' : '' ?>>🇦🇪 UAE</option>
                        <option value="UK" <?= (@$res->country == 'UK') ? 'selected' : '' ?>>🇬🇧 UK</option>
                     </select>
                  </div>

                  <!-- CURRENCY -->
                  <div class="col-md-4">
                     <label class="form-label">Currency</label>
                     <input type="text" class="form-control bg-light"
                        name="currency" id="currency"
                        value="<?= @$res->currency ?? '' ?>" readonly>
                  </div>

                  <!-- VAT -->
                  <div class="col-md-4">
                     <label class="form-label">VAT (%)</label>
                     <input type="number" class="form-control"
                        name="vat_percentage"
                        value="<?= @$res->vat_percentage ?? '' ?>">
                  </div>

                  <!-- MARGIN -->
                  <div class="col-md-6">
                     <label class="form-label">Margin Type</label>
                     <select class="form-select" name="margin_type" required>
                        <?php foreach ($margin_type as $p) { ?>
                           <option value="<?= $p ?>" <?= ($p == @$res->margin_type) ? 'selected' : '' ?>>
                              <?= $p ?>
                           </option>
                        <?php } ?>
                     </select>
                  </div>

                  <div class="col-md-6">
                     <label class="form-label">Margin Value</label>
                     <input type="number" class="form-control"
                        name="margin_value"
                        value="<?= @$res->margin_value ?? '' ?>" required>
                  </div>

                  <!-- PERMISSION -->
                  <div class="col-md-12 mt-2">
                     <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox"
                           name="exchange_margin_permission"
                           value="1"
                           <?= @$res->exchange_margin_permission == 1 ? 'checked' : '' ?>>
                        <label class="form-check-label">
                           Allow margin exchange
                        </label>
                     </div>
                  </div>

                  <!-- COLORS -->
                  <div class="col-md-6">
                     <label class="form-label">Primary Colour</label>
                     <input type="text" class="form-control"
                        name="branding_colour_code"
                        value="<?= @$res->branding_colour_code ?? '' ?>">
                  </div>

                  <div class="col-md-6">
                     <label class="form-label">Secondary Colour</label>
                     <input type="text" class="form-control"
                        name="branding_colour_code_secondry"
                        value="<?= @$res->branding_colour_code_secondry ?? '' ?>">
                  </div>

               </div>

               <input type="hidden" name="id" value="<?= @$res->$module_id ?? 0; ?>">

            </form>
         </div>

         <!-- FOOTER -->
         <div class="modal-footer border-0">
            <button type="submit" form="fm_form_data" class="btn btn-primary px-4">
               Save
            </button>
            <button type="button" class="btn btn-light close-model">
               Cancel
            </button>
         </div>

      </div>
   </div>
</div>
                     <?php if (@$tabledata) { ?>
                        <div class="table-responsive">
                           <table id="multi-filter-select" class="display table table-striped table-hover">
                              <thead>
                                 <tr>
                                    <th>ID</th>
                                    <th>Logo</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Bank Details</th>
                                    <th>Margin</th>
                                    <th style="width: 215px">Action</th>
                                 </tr>
                              </thead>

                              <tbody>
                                 <?php
                                 $i = 1;
                                 foreach ($tabledata as $product) {
                                 ?>
                                    <tr>
                                       <td><?php echo @$product->id; ?></td>
                                       <td><img src="<?php echo is_file('uploads/users/' . @$product->image) ? base_url() . 'uploads/users/' . @$product->image : base_url() . 'uploads/placeholder/image1.png'; ?>" alt="..." class="avatar-img logo rounded-circle"></td>
                                       <td><?php echo @$product->name; ?></td>
                                       <td><?php echo @$product->phone; ?></td>
                                       <td><?php echo @$product->email; ?></td>
                                       <td>
                                          <a class="btn btn-link btn-primary btn-lg open-bank-modal"
                                             data-id="<?= @$product->id; ?>"
                                             data-toggle="modal"
                                             data-target="#bankModal">
                                             <i class="fa fa-edit"></i> View/Update
                                          </a>
                                       </td>

                                       <td><?php echo @$product->margin_value; ?></td>
                                       <td>
                                          <div class="form-button-action">
                                             <?php if (@$permissions['add']) { ?>
                                                <a href="<?php echo site_url('admin/' . $controller . '/list/edit/' . @$product->$module_id . ''); ?>" data-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-original-title="Edit <?= @$page; ?>">
                                                   <i class="fa fa-edit"></i>
                                                </a>
                                                <?php if (@$product->active == 1) { ?>
                                                   <a href="<?php echo site_url('admin/' . $controller . '/disable/' . @$product->$module_id . ''); ?>" onclick="return confirm('<?php echo $this->lang->line('common_confirm_disable'); ?>');" data-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-original-title="Disable <?= @$page; ?>">
                                                      <i class="fa fa-check"></i>
                                                   </a>
                                                <?php  } else {  ?>
                                                   <a href="<?php echo site_url('admin/' . $controller . '/enable/' . @$product->$module_id . ''); ?>" onclick="return confirm('<?php echo $this->lang->line('common_confirm_enable'); ?>');" data-toggle="tooltip" title="" class="btn btn-link btn-danger btn-lg" data-original-title="Enable <?= @$page; ?>">
                                                      <i class="fa fa-check"></i>
                                                   </a>
                                                <?php } ?>
                                             <?php } ?>

                                             <!--  <button type="button" data-toggle="tooltip" title="" class="btn btn-link btn-danger" data-original-title="Remove">
                                          <i class="fa fa-times"></i>
                                          </button> -->
                                          </div>
                                       </td>
                                    </tr>
                                 <?php } ?>
                              </tbody>
                           </table>

                        </div>
                     <?php } else { ?>
                        <div class="alert alert-danger" role="alert">
                           <strong></strong> <?php echo $this->lang->line('common_no_data'); ?>
                        </div>
                     <?php } ?>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>


<!-- Bank Info Modal -->
<div class="modal fade" id="bankModal" tabindex="-1" role="dialog" aria-labelledby="bankModalLabel" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <form id="bankForm" method="post" action="<?= base_url('admin/company/update_bank_info') ?>">
         <input type="hidden" name="product_id" id="product_id">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title">Update Bank Details</h5>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
            </div>
            <div class="modal-body">
               <div class="form-group">
                  <label>Bank</label>
                  <input type="text" class="form-control" name="bank" required>
               </div>
               <div class="form-group">
                  <label>Account Name</label>
                  <input type="text" class="form-control" name="account_name" required>
               </div>
               <div class="form-group">
                  <label>Account No</label>
                  <input type="text" class="form-control" name="account_no" required>
               </div>
               <div class="form-group">
                  <label>IBAN</label>
                  <input type="text" class="form-control" name="iban" required>
               </div>
               <div class="form-group">
                  <label>Branch</label>
                  <input type="text" class="form-control" name="branch" required>
               </div>
               <div class="form-group">
                  <label>Swift Code</label>
                  <input type="text" class="form-control" name="swift" required>
               </div>
            </div>
            <div class="modal-footer">
               <button type="submit" class="btn btn-success">Save</button>
               <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
         </div>
      </form>
   </div>
</div>


<!-- Sweet Alert -->
<script src="a<?= base_url(); ?>assets/admin/js/plugin/bootstrap-tagsinput/bootstrap-tagsinput.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/3.4.0/css/bootstrap-colorpicker.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/3.4.0/js/bootstrap-colorpicker.min.js"></script>
<script>
   $('#address').summernote({
      height: 150,
      toolbar: [
         ['style', ['bold', 'italic', 'underline']],
         ['para', ['ul', 'ol']],
         ['view', ['codeview']]
      ]
   });

   $(function() {
      $('#cp1,#cp2').colorpicker({
         autoInputFallback: false
      });
   });
   <?php if (@$edit == 1 || @validation_errors()) { ?>
      $(document).ready(function() {
         $('#addRowModal').modal('show');
         $('.close-model').click(function() {
            window.location = "<?= @$redirect; ?>";
         });
      });

   <?php } else { ?>
      $('.close-model').click(function() {
         $('#fm_form_data').trigger('reset');
         //$('#fm_form_data').reset();
         $('#addRowModal').modal('hide');


      });
   <?php } ?>


   $(document).ready(function() {
      $('#multi-filter-select').DataTable({
         "order": [
            [0, "desc"]
         ]
      });

      $('#alert_demo_7').click(function(e) {
         swal({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            buttons: {
               confirm: {
                  text: 'Yes, delete it!',
                  className: 'btn btn-success'
               },
               cancel: {
                  visible: true,
                  className: 'btn btn-danger'
               }
            }
         }).then((Delete) => {
            if (Delete) {
               swal({
                  title: 'Deleted!',
                  text: 'Your file has been deleted.',
                  type: 'success',
                  buttons: {
                     confirm: {
                        className: 'btn btn-success'
                     }
                  }
               });
            } else {
               swal.close();
            }
         });
      });


      // $('#multi-filter-select').DataTable({
      //    "pageLength": 5,
      //    initComplete: function() {
      //       this.api().columns().every(function() {
      //          var column = this;
      //          var select = $('<select class="form-control"><option value=""></option></select>')
      //             .appendTo($(column.footer()).empty())
      //             .on('change', function() {
      //                var val = $.fn.dataTable.util.escapeRegex(
      //                   $(this).val()
      //                );

      //                column
      //                   .search(val ? '^' + val + '$' : '', true, false)
      //                   .draw();
      //             });

      //          column.data().unique().sort().each(function(d, j) {
      //             select.append('<option value="' + d + '">' + d + '</option>')
      //          });
      //       });
      //    }
      // });

   });

   function readURL(input) {
      if (input.files && input.files[0]) {
         var reader = new FileReader();

         reader.onload = function(e) {
            $('#image-label').attr('src', e.target.result);
         }

         reader.readAsDataURL(input.files[0]); // convert to base64 string
      }
   }

   $("#uploadImg1").change(function() {
      readURL(this);
   });

   function passwordChanged() {
      var strength = document.getElementById('strength');
      var strongRegex = new RegExp("^(?=.{8,})(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*\\W).*$", "g");
      var mediumRegex = new RegExp("^(?=.{7,})(((?=.*[A-Z])(?=.*[a-z]))|((?=.*[A-Z])(?=.*[0-9]))|((?=.*[a-z])(?=.*[0-9]))).*$", "g");
      var enoughRegex = new RegExp("(?=.{6,}).*", "g");
      var pwd = document.getElementById("password");
      if (pwd.value.length == 0) {
         strength.innerHTML = 'Type Password';
      } else if (false == enoughRegex.test(pwd.value)) {
         strength.innerHTML = 'More Characters';
      } else if (strongRegex.test(pwd.value)) {
         strength.innerHTML = '<span style="color:green">Strong!</span>';
      } else if (mediumRegex.test(pwd.value)) {
         strength.innerHTML = '<span style="color:orange">Medium!</span>';
      } else {
         strength.innerHTML = '<span style="color:red">Weak!</span>';
      }
   }

   $(document).on('click', '.open-bank-modal', function() {
      let productId = $(this).data('id');
      $('#product_id').val(productId);
      $('#bankForm')[0].reset();

      $.ajax({
         url: '<?= base_url("admin/company/get_bank_info") ?>',
         type: 'POST',
         data: {
            id: productId
         },
         dataType: 'json',
         success: function(response) {
            if (response.success) {
               $('[name="bank"]').val(response.data.bank);
               $('[name="account_name"]').val(response.data.account_name);
               $('[name="account_no"]').val(response.data.account_no);
               $('[name="iban"]').val(response.data.iban);
               $('[name="branch"]').val(response.data.branch);
               $('[name="swift"]').val(response.data.swift);
            }
         }
      });



      // set hidden input in modal
   });

   $('#country').on('change', function() {
      let country = $(this).val();

      let config = {
         'AE': {
            currency: 'AED',
            vat: 5
         },
         'UK': {
            currency: 'GBP',
            vat: 20
         }
      };

      if (config[country]) {
         $('#currency').val(config[country].currency);
         $('[name="vat_percentage"]').val(config[country].vat);
      }
   });
</script>