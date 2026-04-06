<div class="main-panel">
   <div class="content">
      <div class="page-inner">

         <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
         <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

         <style>
/* Force Select2 to align properly under the label */
#productTypeModal .select2-container {
    width: 100% !important;
}

/* Multi select inside form-group */
#productTypeModal .select2-selection.select2-selection--multiple {
    border: 1px solid #ced4da !important;
    height: auto !important;          /* Let Select2 auto-adjust */
    min-height: 0 !important;         /* Remove fixed minimum */
    padding: 6px !important;
    border-radius: 4px !important;
    display: flex !important;
    flex-wrap: wrap !important;
    align-items: center !important;
}


/* Tag styling */
#productTypeModal .select2-selection__choice {
    background: #eef1f5 !important;
    border: none !important;
    color: #333 !important;
    border-radius: 4px !important;
    padding: 4px 7px !important;
    margin-top: 6px !important;
}

/* Fix label + select spacing */
#productTypeModal .form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
}

/* Fix big left spacing issue */
#productTypeModal .form-group {
    width: 100%;
}

.select2-container--default .select2-selection--multiple .select2-selection__choice__remove

 {
   border-right: none !important;
 }



         </style>

         <div class="row">
            <div class="col-md-12">
               <div class="card">
                  <div class="card-header">
                     <div class="d-flex align-items-center">
                        <h4 class="card-title">Master - <?= @$page; ?></h4>






                        <div class="btn-group btn-group-page-header ml-auto show">
                           <?php if (@$permissions['add']) { ?>
                              <button class="btn btn-success btn-rounded btn-round ml-auto" data-toggle="modal" data-target="#productTypeModal">
                                 <i class="fa fa-plus"></i>
                                 Add New <?= @$page; ?>
                              </button>
                           <?php } ?>



                        </div>





                     </div>
                  </div>
                  <div class="card-body">


                     <?php if (@$tabledata) { ?>
                        <div class="table-responsive mt-4">
                           <table id="basic-datatables" class="display table table-striped table-hover">
                              <thead>

                                 <tr>
                                    <th>ID</th>
                                    <th>Name</th>

                                    <th style="width: 70px">Action</th>
                                 </tr>
                              </thead>

                              <tbody>
                                 <?php
                                 $i = 1;
                                 foreach ($tabledata as $product) {
                                 ?>
                                    <tr>
                                       <td><?php echo @$product->id; ?></td>
                                       <td><?php echo @$product->name; ?></td>

                                       <td>
                                          <div class="form-button-action">
                                             <?php if (@$permissions['add']) { ?>
                                                <a href="<?php echo site_url('admin/' . $controller . '/list/edit/' . @$product->$module_id . ''); ?>" data-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-original-title="Edit Task">
                                                   <i class="fa fa-edit"></i>
                                                </a>
                                             <?php } ?>
                                             <?php if (@$permissions['add']) { ?>
                                                <a href="<?php echo site_url('admin/' . $controller . '/delete/' . @$product->$module_id . ''); ?>" onclick="return confirm('<?php echo $this->lang->line('common_confirm_delete'); ?>');" data-toggle="tooltip" title="" class="btn btn-link btn-danger" data-original-title="Remove">
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
<!-- Modal -->
<div class="modal fade" id="productTypeModal" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">

         <div class="modal-header">
            <h5 class="modal-title"><?= @$page; ?></h5>
            <button type="button" class="close close-model" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>

         <div class="modal-body">
            <form action="" method="post" id="fm_form_data">

               <div class="row">

                  <?php if (@validation_errors()) { ?>
                     <div class="col-md-12">
                        <div class="alert alert-danger">
                           <strong>Form Insert Failed</strong> <?= @validation_errors(); ?>
                        </div>
                     </div>
                  <?php } ?>

                  <div class="col-md-12">
                     <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control"
                           value="<?= @$res->name != '' ? @$res->name : $this->input->post('name'); ?>"
                           required>
                     </div>
                  </div>

                  <div class="col-md-12">

                     <?php
                        $selectedTypes = [];
                        if (!empty($res->product_type)) {
                           $selectedTypes = explode(',', $res->product_type);
                        }
                     ?>

                     <div class="form-group">
                        <label>Product Types</label>

                        <select name="product_type[]" id="product_type"
                                class="form-control select2" multiple required>
                           <?php foreach ($product_types as $pt): ?>
                              <option value="<?= $pt['id'] ?>"
                                 <?= in_array($pt['id'], $selectedTypes) ? 'selected' : '' ?>>
                                 <?= $pt['name'] ?>
                              </option>
                           <?php endforeach; ?>
                        </select>
                     </div>

                  </div>

               </div>

               <input type="hidden" name="id"
                      value="<?= @$res->$module_id ? $res->$module_id : 0; ?>">

               <div class="modal-footer">
                  <button type="submit" class="btn btn-primary">Add</button>
                  <button type="button" class="btn btn-secondary close-model">Close</button>
               </div>

            </form>
         </div>

      </div>
   </div>
</div>


<script>
   $(document).ready(function() {
      $('#product_type').select2({
         placeholder: "Select product types",
         allowClear: true
      });
   });

   <?php if (@$edit == 1 || @validation_errors()) { ?>
      $(document).ready(function() {
         $('#productTypeModal').modal('show');
         $('.close-model').click(function() {
            window.location = "<?= @$redirect; ?>";
         });
      });
   <?php } else {  ?>
      $('.close-model').click(function() {
         $('#fm_form_data').trigger('reset');
         $('#productTypeModal').modal('hide');
      });
   <?php } ?>
   $(document).ready(function() {
      $('#basic-datatables').DataTable({
         "order": [
            [0, "desc"]
         ]
      });



      $('#datetime').datetimepicker({
         format: 'MM/DD/YYYY H:mm',
      });
      $('#datetime2').datetimepicker({
         format: 'MM/DD/YYYY H:mm',
      });
      $('#datetime3').datetimepicker({
         format: 'MM/DD/YYYY H:mm',
      });

   });
</script>