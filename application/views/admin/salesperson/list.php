<?php $margin_type = array("Percentage", "Value"); ?>
<div class="main-panel">
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
                     <div class="modal fade" id="addRowModal" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                           <div class="modal-content">
                              <div class="modal-header no-bd">
                                 <h5 class="modal-title">
                                    <span class="fw-mediumbold">
                                       New</span>
                                    <span class="fw-light">
                                       <?= @$page; ?>
                                    </span>
                                 </h5>
                                 <button type="button" class="close close-model" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                 </button>
                              </div>
                              <div class="modal-body">
                                 <form action="" method="post" enctype="multipart/form-data">
                                    <div class="row">
                                       <?php if (@validation_errors()) { ?>
                                          <div class="col-md-12">
                                             <div class="alert alert-danger" role="alert">
                                                <strong>Form Insert Failed</strong> <?php echo @validation_errors(); ?>
                                             </div>
                                          </div>
                                       <?php } ?>
                                       <div class="col-md-12 text-center mb-4">
                                          <div class="input-file input-file-image mx-auto">
                                             <img class="img-upload-preview img-circle mx-auto" width="100" height="100" id="image-label" src="<?php echo is_file('uploads/salesperson/' . @$res->image) ? base_url() . 'uploads/salesperson/' . @$res->image : base_url() . 'uploads/placeholder/image1.png'; ?>" alt="preview">
                                             <input type="file" class="form-control form-control-file" id="uploadImg1" name="image" accept="image/*">
                                             <label for="uploadImg1" class="  label-input-file btn btn-round border text-dark" style="color: #333 !important">
                                                <span class="btn-label">
                                                   <i class="fa fa-file-image"></i>
                                                </span>
                                                Upload Profile Pic
                                             </label>
                                          </div>
                                       </div>
                                       <div class="col-md-12">
                                          <div class="form-group form-group-default">
                                             <label>Select a company</label>
                                             <select id="addPosition" class="form-control" name="company" required>
                                                <option value="">Select</option>
                                                <?php foreach ($company as $c) { ?>
                                                   <option value="<?= $c->id; ?>" <?php if (@$c->id == @$res->company) {
                                                                                       echo "selected";
                                                                                    } else if ($this->input->post('company') == @$c->id) {
                                                                                       echo "selected";
                                                                                    } ?>><?= $c->name; ?></option>
                                                <?php } ?>

                                             </select>
                                          </div>
                                       </div>
                                       <div class="col-sm-12">
                                          <div class="form-group form-group-default">
                                             <label>Name</label>
                                             <input id="addName" type="text" class="form-control" name="name" value="<?php echo @$res->name != '' ? @$res->name : $this->input->post('name'); ?>" required>
                                          </div>
                                       </div>
                                       <div class="col-md-6 pr-0">
                                          <div class="form-group form-group-default">
                                             <label>Phone</label>
                                             <input id="addPosition" type="text" class="form-control" name="phone" placeholder="Phone" value="<?php echo @$res->phone != '' ? @$res->phone : $this->input->post('phone'); ?>" required>
                                          </div>
                                       </div>
                                       <div class="col-md-6">
                                          <div class="form-group form-group-default">
                                             <label>Email</label>
                                             <input id="addOffice" type="email" class="form-control" name="email" value="<?php echo @$res->email != '' ? @$res->email : $this->input->post('email'); ?>" required>
                                          </div>
                                       </div>
                                       <div class="col-sm-12">
                                          <div class="form-group form-group-default password-box">

                                             <?php if (!empty(@$res->id)) { ?>
                                                <!-- EDIT MODE -->

                                                <!-- CURRENT PASSWORD -->
                                                <div class="current-password">
                                                   <label>Current Password</label>
                                                   <div class="input-group">
                                                      <input type="password"
                                                         class="form-control"
                                                         value="********"
                                                         readonly>
                                                      <div class="input-group-append">
                                                         <button type="button"
                                                            class="btn btn-outline-primary"
                                                            onclick="enablePasswordChange()">
                                                            Change
                                                         </button>
                                                      </div>
                                                   </div>
                                                </div>

                                                <!-- NEW PASSWORD -->
                                                <div class="new-password mt-3" style="display:none;">
                                                   <label>New Password</label>
                                                   <input type="password"
                                                      id="new_password"
                                                      class="form-control"
                                                      name="new_password" 
                                                      onkeyup="passwordChanged()">
                                                   <small id="strength" class="form-text"></small>

                                                   <button type="button"
                                                      class="btn btn-link text-danger p-0 mt-2"
                                                      onclick="resetPasswordChange()">
                                                      Cancel password change
                                                   </button>
                                                </div>

                                                <input type="hidden" name="change_password" id="change_password" value="1">

                                             <?php } else { ?>
                                                <!-- ADD MODE -->
                                                <label>Password</label>
                                                <input type="password"
                                                   id="password"
                                                   class="form-control"
                                                   name="password"
                                                   onkeyup="passwordChanged()"
                                                   required>
                                                <small id="strength" class="form-text"></small>
                                                         <input type="hidden" name="change_password" id="change_password" value="0">
                                             <?php } ?>

                                          </div>
                                       </div>


                                       <div class="col-md-12">
                                          <div class="form-group form-group-default">
                                             <label>Address</label>
                                             <textarea id="addOffice" class="form-control" name="address" rows="4"><?php echo @$res->address != '' ? @$res->address : $this->input->post('address'); ?></textarea>
                                          </div>
                                       </div>


                                    </div>

                              </div>
                              <input type="hidden" name="id" value="<?php echo @$res->$module_id ? $res->$module_id : 0; ?>">

                              <div class="modal-footer no-bd">
                                 <button type="submit" class="btn btn-primary">Add</button>
                                 <button type="button" class="btn close-model">Close</button>
                              </div>
                           </div>
                           </form>
                        </div>

                     </div>

                     <?php 
                           $companyMap = [];
                           foreach ($company as $c) {
                           $companyMap[$c->id] = $c->name;
                           }
                     
                     if (@$tabledata) { ?>
                        <div class="table-responsive">
                           <table id="basic-datatables" class="display table table-striped table-hover">
                              <thead>
                                 <tr>
                                    <th>ID</th>
                                    <th>Logo</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Company</th>
                           
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
                                       <td><img src="<?php echo is_file('uploads/salesperson/' . @$product->image) ? base_url() . 'uploads/salesperson/' . @$product->image : base_url() . 'uploads/placeholder/image1.png'; ?>" alt="..." class="avatar-img logo rounded-circle"></td>
                                       <td><?php echo @$product->name; ?></td>
                                       <td><?php echo @$product->phone; ?></td>
                                      
                                       <td><?php echo @$product->email; ?></td>
                                           <td>
                                          <?php echo isset($companyMap[$product->company]) 
                                          ? $companyMap[$product->company] 
                                          : ''; 
                                          ?>
                                          </td>
                       
                                       <td>
                                          <div class="form-button-action">
                                             <?php if (@$permissions['add']) { ?>
                                                <a href="<?php echo site_url('admin/' . $controller . '/list/edit/' . @$product->$module_id . ''); ?>" data-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-original-title="Edit Task">
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
                                             <?php if (@$permissions['delete']) { ?>
                                                <a href="<?php echo site_url('admin/' . $controller . '/delete/' . @$product->$module_id . ''); ?>" data-toggle="tooltip" title="" class="btn btn-link btn-danger" data-original-title="Remove" onclick="return confirm('<?php echo $this->lang->line('common_confirm_delete'); ?>');">
                                                   <i class="fa fa-times"></i>
                                                </a>
                                             <?php } ?>

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


<script>
   <?php if (@$edit == 1 || @validation_errors()) { ?>
      $(document).ready(function() {
         $('#addRowModal').modal('show');
         $('.close-model').click(function() {
            window.location = "<?= @$redirect; ?>";
         });
      });
   <?php } else {  ?>
      $('.close-model').click(function() {
         $('#fm_form_data').trigger('reset');
         $('#addRowModal').modal('hide');
      });
   <?php } ?>
   $(document).ready(function() {
      $('#basic-datatables').DataTable({
         "order": [
            [0, "desc"]
         ]
      });

      // $('#multi-filter-select').DataTable( {
      //    "pageLength": 5,
      //    initComplete: function () {
      //       this.api().columns().every( function () {
      //          var column = this;
      //          var select = $('<select class="form-control"><option value=""></option></select>')
      //          .appendTo( $(column.footer()).empty() )
      //          .on( 'change', function () {
      //             var val = $.fn.dataTable.util.escapeRegex(
      //                $(this).val()
      //                );

      //             column
      //             .search( val ? '^'+val+'$' : '', true, false )
      //             .draw();
      //          } );

      //          column.data().unique().sort().each( function ( d, j ) {
      //             select.append( '<option value="'+d+'">'+d+'</option>' )
      //          } );
      //       } );
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


  function enablePasswordChange() {
   $('.current-password').slideUp();
   $('.new-password').slideDown();
   $('#new_password').val('').focus();
   $('#change_password').val(0);
}

function resetPasswordChange() {
   $('.new-password').slideUp();
   $('.current-password').slideDown();
   $('#new_password').val('');
   $('#change_password').val(1);
}

function passwordChanged() {
   var strength = document.getElementById('strength');
   var pwd = document.getElementById('new_password');

   if (!pwd) return;

   var strong = /^(?=.{8,})(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*\W).*$/;
   var medium = /^(?=.{7,})(((?=.*[A-Z])(?=.*[a-z]))|((?=.*[A-Z])(?=.*[0-9]))|((?=.*[a-z])(?=.*[0-9]))).*$/;

   if (pwd.value.length === 0) {
      strength.innerHTML = '';
   } else if (strong.test(pwd.value)) {
      strength.innerHTML = '<span class="text-success">Strong password</span>';
   } else if (medium.test(pwd.value)) {
      strength.innerHTML = '<span class="text-warning">Medium password</span>';
   } else {
      strength.innerHTML = '<span class="text-danger">Weak password</span>';
   }
}

</script>