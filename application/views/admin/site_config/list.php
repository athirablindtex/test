<div class="main-panel">
   <div class="content">
      <div class="page-inner">
         <div class="page-header">
            <h4 class="page-title"><?= @$page; ?></h4>
            <div class="btn-group btn-group-page-header ml-auto">
               <button type="button" class="btn btn-success btn-rounded <?= (@$edit==0 || @validation_errors())?'collapsed':''; ?>"  data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
               <i class="fa fa-plus mr-2"></i> New <?= @$page; ?>
               </button>
            </div>
         </div>
         <div class="row">
            <div class="col-md-12">
               <div class="collapse <?= (@$edit==0 || @validation_errors())?'':'show'; ?>" id="collapseExample" style="">
                  <div class="card">
                     <div class="card-body">
					 	<form action="" method="post" enctype="multipart/form-data">
                        <div class="row">
                        <?php  if(@validation_errors()){ ?>
                           <div class="col-md-12">
                              <div class="alert alert-danger" role="alert">
                              <strong>Form Insert Failed</strong> <?php echo @validation_errors(); ?>
                              </div>
                        </div>
      <?php } ?>
                           <div class="col-md-6">
                              <div class="form-group form-group-default">
                                 <label>Key</label>
                                 <input type="text" class="form-control" name="key_config" placeholder="Config Key" value="<?php echo @$res->key_config!=''?@$res->key_config:$this->input->post('key_config'); ?>" required>
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group form-group-default">
                                 <label>Value</label>
                                 <input type="text" class="form-control" name="value" placeholder="Config Value" value="<?php echo @$res->value!=''?@$res->value:$this->input->post('value'); ?>" required>
                              </div>
                           </div>
                          
                           
						   <input type="hidden" name="id" value="<?php echo @$res->$module_id ? $res->$module_id : 0; ?>"> 
                           <div class="col-md-12 clearfix mt-3 mb-3  d-flex align-items-center justify-content-center justify-content-lg-center">
                              <button type="submit" id="addRowButton" class="btn btn-success btn-rounded mx-auto">Add <?= @$page; ?></button>
                           </div>
                        </div>
						</form>
                     </div>
                  </div>
               </div>
			   <?php if(@$edit==0 || @validation_errors()){ ?>
               <div class="card">
                  <div class="card-body">
                 
				  	<?php if(@$tabledata) {?> 
                     <div class="table-responsive">
                        <table id="multi-filter-select" class="display table table-striped table-hover" >
                           <thead>
                              <tr>
                                 <th>Key</th>
                                 <th>Value</th>
                                 <th style="width: 10%">Action</th>
                              </tr>
                           </thead>
                           <tbody>
						   <?php
                              $i=1;
                              foreach($tabledata as $product){
                              ?>
                              <tr>
                              <td><?php echo @$product->key_config; ?></td>
				                  <td><?php echo @$product->value; ?></td>        
                              <td>
                                    <div class="form-button-action">
                                       <a href="<?php echo site_url('admin/'.$controller.'/list/edit/'.@$product->$module_id.''); ?>" data-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-original-title="Edit Task">
                                       <i class="fa fa-edit"></i>
                                       </a>
                                       <a href="<?php echo site_url('admin/'.$controller.'/delete/'.@$product->$module_id.''); ?>" data-toggle="tooltip" title="" class="btn btn-link btn-danger" data-original-title="Remove" onclick="return confirm('<?php echo $this->lang->line('common_confirm_delete'); ?>');">
                                       <i class="fa fa-times"></i>
                                       </a>
                                    </div>
                                 </td>
                              </tr>
                              <?php } ?> 
                           </tbody>
                        </table>
                     </div>
					 <?php }else{ ?>
                     <div class="alert alert-danger" role="alert">
                     <strong></strong> <?php echo $this->lang->line('common_no_data'); ?>
                     </div>
                  <?php } ?>
					 <?php } ?>   
                  
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<script >
   $(document).ready(function() {
      $('#basic-datatables').DataTable({
      });
   
      $('#multi-filter-select').DataTable( {
         "pageLength": 5,
         initComplete: function () {
            this.api().columns().every( function () {
               var column = this;
               var select = $('<select class="form-control"><option value=""></option></select>')
               .appendTo( $(column.footer()).empty() )
               .on( 'change', function () {
                  var val = $.fn.dataTable.util.escapeRegex(
                     $(this).val()
                     );
   
                  column
                  .search( val ? '^'+val+'$' : '', true, false )
                  .draw();
               } );
   
               column.data().unique().sort().each( function ( d, j ) {
                  select.append( '<option value="'+d+'">'+d+'</option>' )
               } );
            } );
         }
      });
   
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
</script>