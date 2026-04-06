<div class="main-panel">
   <div class="content">
      <div class="page-inner">
         		<div class="page-header">
            <h4 class="page-title"><?php echo @$page; ?></h4>
            <div class="btn-group btn-group-page-header ml-auto">
            	<button type="button" class="btn btn-success btn-rounded <?= @$edit==0?'collapsed':''; ?>"  data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
            		<i class="fa fa-plus mr-2"></i> New <?php echo @$page; ?>
            	</button>
            </div>
            </div>
     

         <div class="row">
            <div class="col-md-12">
          
                  <div class="collapse <?= $edit==0?'':'show'; ?>" id="collapseExample" style="">
                  
                     <div class="card">
                        
                        <div class="card-body">
                        <form action="" method="post" enctype="multipart/form-data">
                           <div class="row">
                              <div class="col-md-12">
                                 <div class="input-group">
                           
                              <div class="input-group-prepend">
                                 
                              </div>
                              
                              <input type="text" class="form-control rounded-0" placeholder="Group Name" name="name" value="<?php echo @$res->name!=''?@$res->name:$this->input->post('name'); ?>" aria-label="Username" aria-describedby="basic-addon1" required>
                              <input type="hidden" name="id" value="<?php echo @$res->$module_id ? $res->$module_id : 0; ?>"> 
                              <button class="btn btn-primary rounded-0">Create</button>
                             
                           </div>
                           </form>
                           <div class="clearfix"></div>
                           
                        </div>
                                      


                                   
                           </div>
                            
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
                                 <th>Group</th>
                   <th style="width: 10%">Action</th>
                              </tr>
                           </thead>
                       
                           <tbody>
                           <?php
                              $i=1;
                              foreach($tabledata as $product){
                              ?>
                              <tr>
                                 <td><?= @$product->name; ?></td>
                               
                                 <td>
                                    <div class="form-button-action">
                                       
                                       <a  data-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-original-title="Permission" href="<?php echo site_url('admin/'.$controller.'/permissions/'.@$product->$module_id.''); ?>">
                                       <i class="fa fa-lock"></i>
                                       </a>
                                       <a data-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-original-title="Edit Task" href="<?php echo site_url('admin/'.$controller.'/list/edit/'.@$product->$module_id.''); ?>">
                                       <i class="fa fa-edit"></i>
                                       </a>
                                          <a href="<?php echo site_url('admin/'.$controller.'/delete/'.@$product->$module_id.''); ?>" onclick="return confirm('<?php echo $this->lang->line('common_confirm_delete'); ?>');" data-toggle="tooltip" title="" class="btn btn-link btn-danger" data-original-title="Remove">
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
                                   
                  </div>

               </div>
               <?php } ?>   

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