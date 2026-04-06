<div class="main-panel">
   <div class="content">
      <div class="page-inner">
         <div class="page-header">
            <h4 class="page-title"><?= @$page; ?></h4>
            <div class="btn-group btn-group-page-header ml-auto">
              
            </div>
         </div>
         
			   
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
                              <button type="submit" id="addRowButton" class="btn btn-success btn-rounded mx-auto">Upload</button>
                           </div>
                        </div>
						</form>
                  
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