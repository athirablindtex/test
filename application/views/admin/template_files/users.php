<?php include 'inc/header.php'; ?>	
<?php include 'inc/sidebar.php'; ?>	
<div class="main-panel">
   <div class="content">
      <div class="page-inner">
         		<div class="page-header">
            <h4 class="page-title">User</h4>
            <div class="btn-group btn-group-page-header ml-auto">
            	<button type="button" class="btn btn-success btn-rounded"  data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
            		<i class="fa fa-plus mr-2"></i> New User
            	</button>
            </div>
            </div>
     

         <div class="row">
            <div class="col-md-12">
              <div class="collapse" id="collapseExample" style="">
<div class="card">
                        
                        <div class="card-body">
                           <div class="row">
                              <div class="col-md-6">
                                 <div class="form-group form-group-default">
                                    <label>Full Name</label>
                                    <input type="text" class="form-control" name="name" placeholder="Name" value="Hizrian">
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-group form-group-default">
                                    <label>Email</label>
                                    <input type="email" class="form-control" name="email" placeholder="Name" value="hello@example.com">
                                 </div>
                              </div><div class="col-md-6">
                                 <div class="form-group form-group-default">
                                    <label>Password</label>
                                    <input type="email" class="form-control" name="email" placeholder="Name" value="hello@example.com">
                                 </div>
                              </div><div class="col-md-6">
                           <div class="form-group form-group-default">
                              <label>User type</label>
                              <select class="form-control" id="formGroupDefaultSelect">
                                 <option>1</option>
                                 <option>2</option>
                                 <option>3</option>
                                 <option>4</option>
                                 <option>5</option>
                              </select>
                           </div>
                        </div>
                        <div class="col-md-12 clearfix mt-3  d-flex align-items-center justify-content-center justify-content-lg-center">

<button type="button" id="addRowButton" class="btn btn-primary mx-auto">Add</button>
</div>
         
                           </div>
                            
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
                              <tr>
                                 <td>01</td>
                                   <td><img src="assets/img/logoproduct.svg" alt="..." class="avatar-img logo rounded-circle"></td>
                                     <td>test dfgdg dgdgdfgdgdgd</td>
                                       <td>test</td>
                                         <td>test</td>

                               
                                 <td>
                                    <div class="form-button-action">
                                     
                                       <button type="button" data-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-original-title="Edit Task">
                                       <i class="fa fa-edit"></i>
                                       </button>
                                          <button type="button" data-toggle="tooltip" title="" class="btn btn-link btn-danger" data-original-title="Remove">
                                          <i class="fa fa-times"></i>
                                          </button>
                                    </div>
                                 </td>
                              </tr>
                                  
                                          <tr>
                                 <td>01</td>
                                   <td><img src="assets/img/logoproduct.svg" alt="..." class="avatar-img logo rounded-circle"></td>
                                     <td>test dfgdg dgdgdfgdgdgd</td>
                                       <td>test</td>
                                         <td>test</td>

                               
                                 <td>
                                    <div class="form-button-action">
                                     
                                       <button type="button" data-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-original-title="Edit Task">
                                       <i class="fa fa-edit"></i>
                                       </button>
                                          <button type="button" data-toggle="tooltip" title="" class="btn btn-link btn-danger" data-original-title="Remove">
                                          <i class="fa fa-times"></i>
                                          </button>
                                    </div>
                                 </td>
                              </tr>
                                  <tr>
                                 <td>01</td>
                                   <td><img src="assets/img/logoproduct.svg" alt="..." class="avatar-img logo rounded-circle"></td>
                                     <td>test dfgdg dgdgdfgdgdgd</td>
                                       <td>test</td>
                                         <td>test</td>

                               
                                 <td>
                                    <div class="form-button-action">
                                     
                                       <button type="button" data-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-original-title="Edit Task">
                                       <i class="fa fa-edit"></i>
                                       </button>
                                          <button type="button" data-toggle="tooltip" title="" class="btn btn-link btn-danger" data-original-title="Remove">
                                          <i class="fa fa-times"></i>
                                          </button>
                                    </div>
                                 </td>
                              </tr>            
                                  
                                                  
                                  
                                                    
                                  
                                  
                              
                             
                            
                            
                           </tbody>
                              </table>




                     
         
                     </div>
                     <hr/>
                                    <nav aria-label="Page navigation example">
  <ul class="pagination mt-4">
    <li class="page-item"><a class="page-link" href="#">Previous</a></li>
    <li class="page-item active"><a class="page-link" href="#">1</a></li>
    <li class="page-item"><a class="page-link" href="#">2</a></li>
    <li class="page-item"><a class="page-link" href="#">3</a></li>
    <li class="page-item"><a class="page-link" href="#">Next</a></li>
  </ul>
</nav>
                  </div>

               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<?php include 'inc/footer.php'; ?>



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
   </script>