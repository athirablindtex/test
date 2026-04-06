<div class="main-panel">
   <div class="content">
      <div class="page-inner">
         <div class="page-header">
            <h4 class="page-title"><?= @$page; ?></h4>
            <div class="btn-group btn-group-page-header ml-auto">
               <button type="button" class="btn btn-success btn-rounded <?= (@$edit==0)?'collapsed':''; ?>"  data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
               <i class="fa fa-plus mr-2"></i> New <?= @$page; ?>
               </button>
             
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
            </div>
         </div>
         <div class="row">
            <div class="col-md-12">
               <div class="collapse <?= (@$edit==0)?'':'show'; ?>" id="collapseExample" style="">
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
                                 <label>Full Name</label>
                                 <input type="text" class="form-control" name="name" placeholder="Name" value="<?php echo @$res->name!=''?@$res->name:$this->input->post('name'); ?>" required>
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group form-group-default">
                                 <label>Email</label>
                                 <input type="email" class="form-control" name="email" placeholder="E-mail" value="<?php echo @$res->email!=''?@$res->email:$this->input->post('email'); ?>" required>
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group form-group-default">
                                 <label>Password</label>
                                 <input class="form-control" type="password" name="password" placeholder="Password" <?= @$res->id>0 ? '' : 'required'; ?>>
                              </div>
                           </div>
                        <div class="col-md-12">
                        <div class="form-group form-group-default">
                        <label>Company Access</label>

                     <select name="companies[]" class="form-control" multiple id="company_select">
                     <option value="all">Select All</option>

                     <?php foreach ($companies as $company) { ?>
                     <option value="<?= $company->id ?>"
                     <?= in_array($company->id, $selected_companies) ? 'selected' : '' ?>>
                     <?= $company->name ?>
                     </option>
                     <?php } ?>
                     </select>


                        </div>
                        </div>


                           <div class="col-md-6">
                              <div class="form-group form-group-default">
                                 <label>User Group</label>
                                 <select class="form-control" id="formGroupDefaultSelect" name="user_group" required>
								 	<option value="">--select any --</option>
									<?php foreach($parent as $p){ ?>
									<option value="<?php echo $p->id; ?>" <?php if($p->id==@$res->user_group){ echo "selected"; } else if($this->input->post('user_group')==$p->id){ echo "selected"; } ?>><?php echo $p->name; ?></option>
									<?php } ?>
                                 </select>
                              </div>
                           </div>
						   <div class="col-md-12 text-center mb-4">
                                          <div class="input-file input-file-image mx-auto">
                                             <img class="img-upload-preview img-circle mx-auto" id="image-label" width="100" height="100"  src="<?php echo is_file('uploads/users/' . @$res->image)?base_url() . 'uploads/users/' . @$res->image:base_url() . 'uploads/placeholder/image1.png'; ?>" alt="preview">
                                             <input type="file" class="form-control form-control-file" id="uploadImg1" name="image" accept="image/*">
                                             <label for="uploadImg1" class="  label-input-file btn btn-round border text-dark" style="color: #333 !important">
                                             <span class="btn-label">
                                             <i class="fa fa-file-image"></i>
                                             </span>
                                             Upload Profile Pic
                                             </label>
                                          </div>
                                       </div>
						   <input type="hidden" name="id" value="<?php echo @$res->$module_id ? $res->$module_id : 0; ?>"> 
                           <div class="col-md-12 clearfix mt-3 mb-3  d-flex align-items-center justify-content-center justify-content-lg-center">
                              <button type="submit" id="addRowButton" class="btn btn-success btn-rounded mx-auto">Add Users</button>
                           </div>
                        </div>
						</form>
                     </div>
                  </div>
               </div>
			   <?php if(@$edit==0){ ?>
               <div class="card">
                  <div class="card-body">
				  	<?php if(@$tabledata) {?> 
                     <div class="table-responsive">
                        <table id="multi-filter-select" class="display table table-striped table-hover" >
                           <thead>
                              <tr>
                                 <th>ID</th>
                                 <th style="width: 20px">Photo</th>
                                 <th >Name</th>
                                 <th>Email</th>
                              
                                 <th>User Type</th>
                                 <th style="width: 10%">Action</th>
                                 
                              </tr>
                           </thead>
                           <tbody>
						   <?php
                              $i=1;
                              foreach($tabledata as $product){
                              ?>
                              <tr>
                                 <td><?php echo @$product->id; ?></td>
                                 <td><img src="<?php echo is_file('uploads/users/' . @$product->image)?base_url() . 'uploads/users/' . @$product->image:base_url() . 'uploads/placeholder/image1.png'; ?>" alt="..." class="avatar-img logo rounded-circle"></td>
                                 <td><?php echo @$product->name; ?></td>
                                 <td><?php echo @$product->email; ?></td>
                                 <td><?php echo @$product->group; ?></td>
                                 <td>
                                    <div class="form-button-action">
                                 <?php if ((int)$product->active === 1) { ?>
                                 <a href="<?php echo site_url('admin/'.$controller.'/disable/'.$product->$module_id); ?>"
                                 class="btn btn-success btn-sm"
                                 onclick="return confirm('Disable this item?');">
                                     <i class="fa fa-check"></i>
                                 </a>
                                 <?php } ?>

                                 <?php if ((int)$product->active === 0) { ?>
                                 <a href="<?php echo site_url('admin/'.$controller.'/enable/'.$product->$module_id); ?>"
                                 class="btn btn-warning btn-sm"
                                 onclick="return confirm('Enable this item?');">
                           
                                      <i class="fa fa-ban"></i>
                                 </a>
                                 <?php } ?>

                                      
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
 $('#company_select').select2({
        placeholder: "Select companies",
        width: '100%'
    });

    $('#company_select').on('select2:select', function (e) {
        if (e.params.data.id === 'all') {
            let allValues = [];

            $('#company_select option').each(function () {
                if ($(this).val() !== 'all') {
                    allValues.push($(this).val());
                }
            });

            $('#company_select').val(allValues).trigger('change');
        }
    });

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