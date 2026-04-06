<style>

</style>
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


                     <?php if (@$tabledata) { ?>
                        <div class="table-responsive mt-4">
                           <table id="basic-datatables" class="display table table-striped table-hover">
                              <thead>

                                 <tr>
                                    <th>ID</th>
                                    <th>Parent</th>
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
                                       <td><?php echo @$product->parent_name; ?></td>
                                       <td><?php echo @$product->name; ?></td>


                                       <td>
                                          <div class="form-button-action">
                                             <?php if (@$permissions['add']) { ?>
                                                <a href="<?php echo site_url('admin/' . $controller . '/list/edit/' . @$product->$module_id . ''); ?>" data-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-original-title="Edit Task">
                                                   <i class="fa fa-edit"></i>
                                                </a>
                                             <?php } ?>
                                             <?php if (@$permissions['delete']) { ?>
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
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header no-bd">
            <h5 class="modal-title">
               <span class="fw-mediumbold">
                  <?= @$page; ?>
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
                        <label>Product Type</label>
                        <select class="form-control" id="formGroupDefaultSelect" name="parent" required>
                           <?php foreach ($parent as $p) { ?>
                              <option value="<?= @$p->id; ?>" <?= @$p->id == @$res->parent ? 'selected' : (@$this->input->post('parent') == @$res->parent ? 'selected' : ''); ?>><?= @$p->name.' ('.@$p->blind_code .') ';?></option>
                           <?php } ?>
                        </select>
                     </div>
                  </div>
                  <div class="col-md-12">
                     <div class="form-group form-group-default">
                        <label>Name</label>
                        <input id="addPosition" type="text" class="form-control" name="name" value="<?php echo @$res->name != '' ? @$res->name : $this->input->post('name'); ?>" required>
                     </div>
                  </div>
                  <div class="col-md-12">
                     <div class="form-group form-group-default">
                        <label>Blind Code</label>
                        <input id="blind_code" type="text" class="form-control" name="blind_code" value="<?php echo @$res->blind_code != '' ? @$res->blind_code : $this->input->post('blind_code'); ?>">
                     </div>
                     
                  </div>
<!-- Radio Button Section -->
<div class="col-md-12">
   <div class="form-group form-group-default">
      <label>Blind Code Dependent on Fabric ?</label>
      

      <label class="radio-inline" style="margin-top: 10px;">
         <input type="radio" name="fabricDependentCode" value="Yes" 
            <?php if(@$res->fabric_dependent_code == 'Yes') echo 'checked'; ?> style="visibility: visible !important;"> Yes
      </label>

      <label class="radio-inline" style="margin-left: 0px;">
         <input type="radio" name="fabricDependentCode" value="No" 
            <?php if(@$res->fabric_dependent_code != 'Yes') echo 'checked'; ?> style="visibility: visible !important;"> No
      </label>
      </label>

  
  
   </div>
</div>
<div class="col-md-12">
    <small class="form-text text-muted" style="margin-top: 10px; margin-bottom: 8px; color:green">
        <span style="color:red"> *</span> Sometimes blind code is added depending on the fabric name, so add blind code in fabric.
      </small>
</div>

                  <!-- <div class="col-md-12">


                     <div class="form-group p-0">
                                 <label>Extras</label>
                          <select data-placeholder="Choose Status" class="chosen-select form-control" tabindex="4" id="user" name="extras[]" multiple>
                              <option value="">Extras</option>
                              <?php // foreach(@$extras as $s){ 
                              ?>
                              <option value="<?php // echo $s->id; 
                                             ?>" <?php // echo in_array($s->id,@$extras_sel)?'selected':''; 
                                                   ?>><?php // echo $s->name; 
                                                      ?></option>
							  <?php // } 
                        ?>
                        </select>
                                </div> 
                  </div> -->
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
</script>
<link href="http://cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.min.css" rel="stylesheet" />
<script src="http://cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.jquery.min.js"></script>
<script>
   //$('.chosen,.chosen-select').chosen();
   // $('.chosen-select').css({ 'width':'350px' });
   //$(".chosen-select").chosen({width: "inherit"}) 
   $('#productTypeModal').on('shown.bs.modal', function() {
      //alert(111);
      $('.chosen-select', this).chosen();
   });
</script>