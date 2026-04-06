<div class="main-panel">
   <div class="content">
      <div class="page-inner">
         <div class="page-header">
            <h4 class="page-title">Permissions - <?= @$user_group->name; ?></h4>
            <div class="btn-group btn-group-page-header ml-auto">
               <div class="form-check pl-0">
                  <label class="form-check-label">
                  <input class="form-check-input" type="checkbox" value="" id="al" <?= count($all)==count($pr)?'checked':''; ?>>
                  <span class="form-check-sign">Select All</span>
                  </label>
               </div>
            </div>
         </div>
         <form action="" method="post">
         <div class="row">
            <div class="col-md-12">
               <div class="card">
                  <div class="card-body">
                     <?php foreach($modules as $m){ 
                        $this->db->where('parent',$m->module);
                        $mod=$this->usergroupsmodel->get_modules()->result();
                        $r=array('_list','_add','_delete');
                        $i=0;
                        foreach($pr as $k){
                           foreach($r as $rm){
                                 if(strpos($k,$rm) == true){
                                       $t=explode($rm,$k);
                                       ($t[0] == $m->module) ? $i++ : $i;
                                    }
                              }
                        }
                        ?>                       
                     <div class="table-responsive">
                        <table id="multi-filter-select" class="display table table-striped table-hover" >
                           <thead>
                              <tr>
                                 <th colspan="4"><?= $m->module_language_key; ?></th>
                              </tr>
                           </thead>
                           <tbody>
                              <tr>
                                 <td>
                                    <div class="form-check pl-0">
                                       <label class="form-check-label">
                                       <input class="form-check-input alls fs" type="checkbox" id="<?= $m->id; ?>" value=""  <?= $i==count($mod)?'checked':''; ?>>
                                       <span class="form-check-sign">Select All</span>
                                       </label>
                                    </div>
                                 </td>
                                 <?php
                                    foreach($mod as $o){
                                      ?>
                                 <td>
                                    <div class="form-check pl-0">
                                       <label class="form-check-label">
                                       <input class="form-check-input subs sub_<?= $m->id; ?> fs" type="checkbox" name="permission[]" value="<?= $o->module; ?>" <?= in_array($o->module,$pr)==1?'checked':''; ?>>
                                       <span class="form-check-sign"><?= $o->module_language_key; ?></span>
                                       </label>
                                    </div>
                                 </td>
                                 <?php } ?>
                              </tr>
                           </tbody>
                        </table>
                     </div>
                     <hr>
                     <?php } ?>
                     <input type="hidden" name="user" value="<?= $this->uri->segment('4'); ?>" />
                     <div class="col-md-12 clearfix mt-3  d-flex align-items-center justify-content-center justify-content-lg-center">
                        <button type="submit" id="addRowButton" class="btn btn-primary mx-auto">Add</button>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         </form>

      </div>
   </div>
</div>
</div>
<script >
   $('#datetime').datetimepicker({
   format: 'MM/DD/YYYY H:mm',
   });
     $('#datetime2').datetimepicker({
   format: 'MM/DD/YYYY H:mm',
   });
   
   
   
   
   
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
<script>
 
   
        ////
        
        $('#al').on('click', function(event){
           if($("#al").is(':checked')){
                 $(".fs").prop('checked', true); 
              }
           else{
                 $(".fs").prop('checked', false);
              }
           });
        $('.alls').on('click',function(){
              var id = $(this).attr("id");
              var class1='sub_'+id;
              if($(this).is(':checked')){
                    $('.'+class1).prop('checked', true); 
                 }
              else{
                    $('.'+class1).prop('checked', false); 
                 }   
           });
</script>