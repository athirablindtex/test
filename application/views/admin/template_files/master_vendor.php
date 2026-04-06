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
                        <h4 class="card-title">Master - Vendor</h4>
                      



                        <div class="btn-group btn-group-page-header ml-auto show">

                           <button class="btn btn-success btn-rounded ml-auto" data-toggle="modal" data-target="#vendorModal">
                        <i class="fa fa-plus"></i>
                        Add New Vendor
                        </button>



                
                  </div>




            

                     </div>
                  </div>
                  <div class="card-body">
                 
                  
                  
                     <div class="table-responsive mt-4">
                           <table id="multi-filter-select" class="display table table-striped table-hover" >
                               <thead>
                              <tr>
                                 <th>ID</th>
                                 <th>Name</th>
                               
                                 <th style="width: 70px">Action</th>
                              </tr>
                           </thead>
                     
                           <tbody>
                              <tr>
                                 <td>2252</td>
                                 <td>asdasdasd</td>
                               
                <td>
                                   <div class="form-button-action">
                                       <!--  <ul class="nav nav-pills nav-secondary nav-sm list-inline" id="pills-tab" role="tablist">
                                          <li class="nav-item submenu list-inline-item">
                                             <a class="nav-link active show" id="pills-today" data-toggle="pill" href="#pills-today" role="tab" aria-selected="true">Enable</a>
                                          </li>
                                          <li class="nav-item submenu list-inline-item">
                                             <a class="nav-link" id="pills-week" data-toggle="pill" href="#pills-week" role="tab" aria-selected="false">Disable</a>
                                          </li>
                                       </ul>-->
                                       <button type="button" data-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-original-title="View">
                                       <i class="fa fa-eye"></i>
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