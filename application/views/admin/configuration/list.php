<div class="main-panel">
   <div class="content">
      <div class="page-inner">
            <div class="page-header">
            <h4 class="page-title">Configuration</h4>
            
            </div>
     

         <div class="row">
            <div class="col-md-12">
              
               <div class="card">
  
                  <div class="card-body">
                           <form method="post" action="<?= site_url().'admin/configuration/add_email'; ?>">
                           <div class="row no-gutters">
                              <div class="col-md-4">
                                 
                     
                           <div class="form-group p-0">
                              
                              <input class="form-control" id="formGroupDefaultSelect" name="email" id="email_temp" placeholder="Invoice Emails">
                           </div>
                              </div>
                                      <div class="col-md-1">
                                         
                
                           <button type="submit" id="add_email" class="btn border text-center w-100 bg-white mt-2 mt-lg-0 ml-lg-1"><i class="fa fa-plus"></i></button>
                              </div>
                           </form>

<div class="col-12">
<div class="table-responsive border mt-3">
                                 <table class="table m-0">
                                    <tbody>
                                       <?php foreach($emails as $em){ ?>
                                       <tr>
                                          
                                          
                                          
                                          
                                          
                                          
                                          <td style="
    width: 90%;
    /* padding: 0 !important; */
"><?= $em->email; ?></td>
                                          <td style="
    /* padding: 0 !important; */
">
                                    <div class="form-button-action">
                                     
                                    <a href="<?php echo site_url('admin/'.$controller.'/delete/'.@$em->$module_id.''); ?>" onclick="return confirm('<?php echo $this->lang->line('common_confirm_delete'); ?>');" data-toggle="tooltip" title="" class="btn btn-link btn-danger" data-original-title="Remove">
                                          <i class="fa fa-times"></i>
                                          </a>
                                    </div>
                                 </td>
                                       </tr>
                                       <?php } ?>
                                       
                                       
                                       
                                    </tbody>
                                 </table>
                              </div></div>
                                   
                           </div>
                           <form method="post">
                           <div class="row mt-3">
                              
                           <?php  if(@validation_errors()){ ?>
                                          <div class="col-md-12">
                                             <div class="alert alert-danger" role="alert">
                                             <strong>Form Insert Failed</strong> <?php echo @validation_errors(); ?>
                                             </div>
                                       </div>
                     <?php } ?>
                              <div class="col-md-12">
                                 
                     
                           <div class="form-group p-0">
    <label>VAT Addition</label>
                              
                              <input class="form-control" type="number" id="formGroupDefaultSelect" name="vat_addition" value="<?php echo @$res->vat_addition!=''?@$res->vat_addition:$this->input->post('vat_addition'); ?>" required>
                           </div>
                              </div>

<div class="col-md-12 clearfix mt-3  d-flex align-items-center justify-content-center justify-content-lg-center">

<button type="submit" id="addRowButton" class="btn btn-primary mx-auto">Add</button>
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
   </script>