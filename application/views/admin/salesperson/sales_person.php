<?php include 'inc/header.php'; ?>	
<?php include 'inc/sidebar.php'; ?>	
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
                        <h4 class="card-title">Sales Persons</h4>
                        <button class="btn btn-success btn-round ml-auto" data-toggle="modal" data-target="#addRowModal">
                        <i class="fa fa-plus"></i>
                        Add Sales Person
                        </button>
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
                                    Company
                                    </span>
                                 </h5>
                                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                 <span aria-hidden="true">&times;</span>
                                 </button>
                              </div>
                              <div class="modal-body">
                                 <form>
                                    <div class="row">
                                       <div class="col-md-12 text-center mb-4">
                                          <div class="input-file input-file-image mx-auto">
                                             <img class="img-upload-preview img-circle mx-auto" width="100" height="100" src="http://placehold.it/100x100" alt="preview">
                                             <input type="file" class="form-control form-control-file" id="uploadImg1" name="uploadImg1" accept="image/*" required="">
                                             <label for="uploadImg1" class="  label-input-file btn btn-round border text-dark" style="color: #333 !important">
                                             <span class="btn-label">
                                             <i class="fa fa-file-image"></i>
                                             </span>
                                             Upload company logo
                                             </label>
                                          </div>
                                       </div>
                                       <div class="col-sm-12">
                                          <div class="form-group form-group-default">
                                             <label>Name</label>
                                             <input id="addName" type="text" class="form-control">
                                          </div>
                                       </div>
                                       <div class="col-md-6 pr-0">
                                          <div class="form-group form-group-default">
                                             <label>Phone</label>
                                             <input id="addPosition" type="text" class="form-control">
                                          </div>
                                       </div>
                                       <div class="col-md-6">
                                          <div class="form-group form-group-default">
                                             <label>Email</label>
                                             <input id="addOffice" type="text" class="form-control">
                                          </div>
                                       </div>
                                       <div class="col-md-12">
                                          <div class="form-group form-group-default">
                                             <label>Address</label>
                                             <textarea id="addOffice" class="form-control" rows="4"></textarea>
                                          </div>
                                       </div>
                                       <div class="col-md-6 pr-0">
                                          <div class="form-group form-group-default">
                                             <label>Username</label>
                                             <input id="addPosition" type="text" class="form-control">
                                          </div>
                                       </div><div class="col-md-6">
                                          <div class="form-group form-group-default">
                                             <label>Password</label>
                                             <input id="addPosition" type="text" class="form-control">
                                          </div>
                                       </div>
                                       
                                       
                                       
                                       
                                    </div>
                                 </form>
                              </div>
                              <div class="modal-footer no-bd">
                                 <button type="button" id="addRowButton" class="btn btn-primary">Add</button>
                                 <button type="button" class="btn" data-dismiss="modal">Close</button>
                              </div>
                           </div>
                        </div>
                     </div>


                       <div class="row">
                              <div class="col-md-4">
                                 
                     
                           <div class="form-group">
                              <select class="form-control" id="formGroupDefaultSelect">
                                 <option>Company</option>
                                 <option>2</option>
                                 <option>3</option>
                                 <option>4</option>
                                 <option>5</option>
                              </select>
                           </div>
                              </div>
                                      <div class="col-md-3">
                                         
                
                           <div class="form-group">
                                   <input id="Name" type="text" class="form-control" placeholder="Email">
                           </div>
                              </div>
                                   <div class="col-md-3">
                           
                    
                           <div class="form-group">
                         
                                    <input id="Name" type="text" class="form-control" placeholder="Phone">
                           </div>
                              </div>
                                <div class="col-md-2">
                               <div class="form-group">
                    <button type="button" id="addRowButton" class="btn btn-primary w-100">Search</button>
                 </div>
                              </div>
                           </div>
                  
                       <div class="table-responsive">
<hr/>
                        <table id="multi-filter-select" class="display table table-striped table-hover" >
                               <thead>
                              <tr>
                                 <th>ID</th>
                                 <th>Photo</th>
                                 <th>Name</th>
                                 <th>Email</th>
                                 <th>Phone</th>
                                 <th>Address</th>
                                 <th style="width: 240px">Action</th>
                              </tr>
                           </thead>
                     
                           <tbody>
                              <tr>
                                 <td>2252</td>
                                 <td><img src="assets/img/profile.jpg" alt="..." class="avatar-img logo rounded-circle"></td>
                                 <td>ABC</td>

                                 <td>company@email.com</td>
                                 <td>+0987654321</td>
                                 <td>asda asdasd sadasdasd asdasd adasd asdasd</td>
                                 <td>
                                    <div class="form-button-action">
                                       <ul class="nav nav-pills nav-secondary nav-sm list-inline" id="pills-tab" role="tablist">
                                          <li class="nav-item submenu list-inline-item">
                                             <a class="nav-link active show" id="pills-today" data-toggle="pill" href="#pills-today" role="tab" aria-selected="true">Enable</a>
                                          </li>
                                          <li class="nav-item submenu list-inline-item">
                                             <a class="nav-link" id="pills-week" data-toggle="pill" href="#pills-week" role="tab" aria-selected="false">Disable</a>
                                          </li>
                                       </ul>
                                       <button type="button" data-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-original-title="View">
                                       <i class="fa fa-eye"></i>
                                       </button>
                                       <!--  <button type="button" data-toggle="tooltip" title="" class="btn btn-link btn-danger" data-original-title="Remove">
                                          <i class="fa fa-times"></i>
                                          </button> -->
                                    </div>
                                 </td>
                              </tr>
                               <tr>
                                 <td>2252</td>
                                 <td><img src="assets/img/profile.jpg" alt="..." class="avatar-img logo rounded-circle"></td>
                                 <td>ABC</td>

                                 <td>company@email.com</td>
                                 <td>+0987654321</td>
                                 <td>asda asdasd sadasdasd asdasd adasd asdasd</td>
                                 <td>
                                    <div class="form-button-action">
                                       <ul class="nav nav-pills nav-secondary nav-sm list-inline" id="pills-tab" role="tablist">
                                          <li class="nav-item submenu list-inline-item">
                                             <a class="nav-link active show" id="pills-today" data-toggle="pill" href="#pills-today" role="tab" aria-selected="true">Enable</a>
                                          </li>
                                          <li class="nav-item submenu list-inline-item">
                                             <a class="nav-link" id="pills-week" data-toggle="pill" href="#pills-week" role="tab" aria-selected="false">Disable</a>
                                          </li>
                                       </ul>
                                       <button type="button" data-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-original-title="View">
                                       <i class="fa fa-eye"></i>
                                       </button>
                                       <!--  <button type="button" data-toggle="tooltip" title="" class="btn btn-link btn-danger" data-original-title="Remove">
                                          <i class="fa fa-times"></i>
                                          </button> -->
                                    </div>
                                 </td>
                              </tr>
                                <tr>
                                 <td>2252</td>
                                 <td><img src="assets/img/profile.jpg" alt="..." class="avatar-img logo rounded-circle"></td>
                                 <td>ABC</td>

                                 <td>company@email.com</td>
                                 <td>+0987654321</td>
                                 <td>asda asdasd sadasdasd asdasd adasd asdasd</td>
                                 <td>
                                    <div class="form-button-action">
                                       <ul class="nav nav-pills nav-secondary nav-sm list-inline" id="pills-tab" role="tablist">
                                          <li class="nav-item submenu list-inline-item">
                                             <a class="nav-link active show" id="pills-today" data-toggle="pill" href="#pills-today" role="tab" aria-selected="true">Enable</a>
                                          </li>
                                          <li class="nav-item submenu list-inline-item">
                                             <a class="nav-link" id="pills-week" data-toggle="pill" href="#pills-week" role="tab" aria-selected="false">Disable</a>
                                          </li>
                                       </ul>
                                       <button type="button" data-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-original-title="View">
                                       <i class="fa fa-eye"></i>
                                       </button>
                                       <!--  <button type="button" data-toggle="tooltip" title="" class="btn btn-link btn-danger" data-original-title="Remove">
                                          <i class="fa fa-times"></i>
                                          </button> -->
                                    </div>
                                 </td>
                              </tr>
                           </tbody>
                              </table>
               
                     </div>
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