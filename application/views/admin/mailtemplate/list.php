<!-- include summernote css/js -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
<?php $categories = ['Invoice', 'Invoice Confirmed', 'Invoice with Balance', 'Worksheet','Quotation']; ?>
<div class="main-panel">
   <div class="content">
      <div class="page-inner">
         <!-- 		<div class="page-header">
            <h4 class="page-title">Company</h4>
            <div class="btn-group btn-group-page-header ml-auto">
            	<button type="button" class="btn btn-light">
            		<i class="fa fa-plus mr-2"></i> New
            	</button>
            </div>
            </div>
            -->
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


                     <?php if (@$tabledata) { 
                        
                        $companyMap = [];
foreach ($companies as $c) {
    $companyMap[$c->id] = $c->name;
}
?>
                        <div class="table-responsive mt-4">
                           <table id="basic-datatables" class="display table table-striped table-hover">
                              <thead>

                                 <tr>
                                    <th>ID</th>
                                    <th>Category</th>
                                    <th>Company name</th>
                                  
                                    <th>Description</th>

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
                                       <td><?php echo @$product->category; ?></td>
                                             <td>
                                          <?= isset($companyMap[$product->company_id]) 
                                          ? htmlspecialchars($companyMap[$product->company_id]) 
                                          : '-' ?>
                                          </td>
                                     
                                       <td>
    <button 
        class="btn btn-sm btn-info view-template"
        data-description="<?= htmlspecialchars($product->description, ENT_QUOTES, 'UTF-8'); ?>">
        View
    </button>
</td>

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

<!-- Model 	-->
<div class="modal fade" id="productTypeModal" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
         <div class="modal-header no-bd">
            <h5 class="modal-title">
               <span class="fw-mediumbold">
                  <?= @$page; ?>
               </span>
            </h5>
            <button type="button" class="close close-model" aria-label="Close">
               <span aria-hidden="true">×</span>
            </button>
         </div>
         <div class="modal-body">
            <form action="" method="post" id="fm_form_data">
               <div class="row">
                  <?php if (@validation_errors()) { ?>
                     <div class="col-md-12">
                        <div class="alert alert-danger" role="alert">
                           <strong>Form Insert Failed</strong> <?php echo @validation_errors(); ?>
                        </div>
                     </div>
                  <?php } ?>
                  <div class="col-md-12">
                     <div class="form-group form-group-default">
                        <label>Category</label>
                        <select class="form-control" id="formGroupDefaultSelect" name="category" required>
                           <?php foreach ($categories as $p) { ?>
                              <option value="<?php echo $p; ?>" <?php if ($p == @$res->category) {
                                                                     echo "selected";
                                                                  } else if ($this->input->post('category') == $p) {
                                                                     echo "selected";
                                                                  } ?>><?php echo $p; ?></option>
                           <?php } ?>
                        </select>
                     </div>
                  </div>
                  <div class="col-md-12">
                     <!-- <div class="form-group form-group-default">
                        <label>Subject</label>
                        <input id="addPosition" type="text" class="form-control" name="name" value="<?php echo @$res->name != '' ? @$res->name : $this->input->post('name'); ?>" >
                     </div> -->
                  </div>
                     <div class="col-md-12">
                     <div class="form-group form-group-default">
                     <label>Brand / Company</label>
                     <select class="form-control" name="company_id" required>
                     <option value="">Select Company</option>
                     <?php foreach ($companies as $c) { ?>
                     <option value="<?= $c->id ?>"
                     <?php
                     if (@$res->company_id == $c->id || $this->input->post('company_id') == $c->id) {
                     echo 'selected';
                     }
                     ?>>
                     <?= $c->name ?>
                     </option>
                     <?php } ?>
                     </select>
                     </div>
                     </div>

                  <div class="col-md-12">
                     <div class="form-group form-group-default">
                        <label>Description</label>
                        <textarea name="description" id="summernote" class="form-control" required><?php echo @$res->description != '' ? @$res->description : $this->input->post('description'); ?></textarea>
                     </div>
                  </div>
                  <div class="col-md-12">
                     <div class="form-group">
                        <label>Note:</label>
                        <span>Following dynamic fields you can use in template description</span>
                        <ul>
                           <li>{{customer_name}}</li>
                           <li>{{created_date}}</li>
                           <li>{{invoice_no}}</li>
                           <li>{{year}}</li>
                 
                        </ul>
                     </div>
                  </div>
               </div>

         </div>
         <input type="hidden" name="id" value="<?php echo @$res->$module_id ? $res->$module_id : 0; ?>">
         <div class="modal-footer no-bd">
            <button type="submit" id="addRowButton" class="btn btn-primary">Add</button>
            <button type="button" class="btn close-model">Close</button>
         </div>

         </form>
      </div>
   </div>
</div>
<!-- Model 	-->

<div class="modal fade" id="viewTemplateModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Template Preview</h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="templatePreview" style="min-height:200px;"></div>
      </div>
    </div>
  </div>
</div>


<script>

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

$(document).on('click', '.view-template', function () {
    let description = $(this).data('description');
    $('#templatePreview').html(description);
    $('#viewTemplateModal').modal('show');
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

   $(document).ready(function() {
      $('#summernote').summernote({
         placeholder: '',
         // tabsize: 2,
         height: 400
      });
   });
</script>