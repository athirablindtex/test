<?php $type_ar=array('Percentage','Flat Value Independent','Flat Value Width Dependent','Flat Value Height Dependent','Flat Value Square Meter Dependent','Flat Value Box Value'); 
      $type_ar_str="";
      foreach($type_ar as $t){
            $type_ar_str.='<option value="'.$t.'">'.$t.'</option>';
         }
      
?>
<div class="main-panel">
   <div class="content">
      <div class="page-inner">
         <div class="page-header">
            <h4 class="page-title">Extras</h4>
            <div class="btn-group btn-group-page-header ml-auto">
               <?php if(@$permissions['add']){ ?>
               <button type="button" class="btn btn-success btn-rounded <?= @$edit==0?'collapsed':((@validation_errors()?'':'collapsed')); ?>"  data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
               <i class="fa fa-plus mr-2"></i> New Extras
               </button>
               <?php } ?>
            </div>
         </div>
         <div class="row">
            <div class="col-md-12">
               <div class="collapse <?= @$edit==0?(@validation_errors()?'show':''):'show'; ?>" id="collapseExample" style="">
                  <div class="card">
                     <div class="card-header">
                        <div class="card-title">New extra item</div>
                     </div>
                     <div class="card-body">
                        <form method="post">
                        <div class="row">
                        <?php  if(@validation_errors()){ ?>
                                          <div class="col-md-12">
                                             <div class="alert alert-danger" role="alert">
                                              <?php echo @validation_errors(); ?>
                                             </div>
                                       </div>
                     <?php } ?>


                           <div class="col-md-12 col-main">

                               <!-- Add -->
                              <div class="input-group">
                                 <input id="Name" type="text" class="form-control" name="name" placeholder="Name" value="<?php echo @$res['name']!=''?@$res['name']:$this->input->post('name'); ?>">
                                 <select class="form-control main-items-input <?= @$sub?( count($sub)>0?'d-none':''):''; ?>"  name="type" id="formGroupDefaultSelect" style="height: 40px;">
                                    <option value="">Value Type</option>
                                    
                                 
                                    <?php foreach($type_ar as $t){ ?>
                                       <option value="<?= $t; ?>" <?= $t==@$res['type']?'selected':''; ?>><?= $t; ?></option>
                                    <?php } ?>
                                   
                                 </select>
                                 <input id="Name" name="value" type="number" step="any" class="form-control main-items-input <?= @$sub?( count($sub)>0?'d-none':''):''; ?>" value="<?php echo @$res['value']!=''?@$res['value']:$this->input->post('value'); ?>" placeholder="Value">
                                 
                                 
                                 <!-- Subs  -->
                                          <?php 
                                           $sub_item_id=0;  
                                           $subs=array();
                                          if(@$sub){?>
                                          <div   class=" d-block mt-2 w-100" id="small-btn-main">
                                          <a style="cursor: pointer;" class="text-primary"  onclick="removeSubItemsMainChild(this)">Remove Sub</a>
                                          </div>
                                          <?php 
                                          foreach(@$sub as $s){ 
                                                   $cur_item=array("count"=>count($s['sub_sub']));
                                                   $cur_item["sub_sub"]=array();
                                                   $subs[]=$cur_item;
                                                ?>
                                                <!-- Sub Row  -->
                                                <div style=" margin-left:15px" class="w-100 sub-wr mt-2">
                                                   <div class="input-group mb-3 mt-3 cs-sub-item" id="cs-sub-item<?= $sub_item_id; ?>"> 
                                                         <input id="Name" type="text" name="sub_name[<?= $sub_item_id; ?>]" value="<?= @$s['name'];?>" class="form-control" placeholder="Name"> 
                                                         <select class="form-control sub-select-type <?= @$s['sub_sub']?(count(@$s['sub_sub'])>0?'d-none':''):''; ?>" name="sub_type[<?= $sub_item_id; ?>]" id="formGroupDefaultSelect" style="height: 41px;">  
                                                               <option value="">Value Type</option>
                                                               <?php foreach($type_ar as $t){ ?>
                                                                  <option value="<?= $t; ?>" <?= $t==@$s['type']?'selected':''; ?>><?= $t; ?></option>
                                                               <?php } ?>
                                                         </select> 
                                                         <input id="Name" name="sub_value[<?= $sub_item_id; ?>]" value="<?= @$s['value'];?>" type="number" step="any" class="form-control sub-price-box <?= @$s['sub_sub']?(count(@$s['sub_sub'])>0?'d-none':''):''; ?>" placeholder="Value">
                                                         <input type="hidden" name="sub_id[<?= $sub_item_id; ?>]"  value="<?= @$s['id']; ?>"> 
                                                         <span class="btn-box"> 
                                                               <?php if($sub_item_id+1==count($sub)){
                                                                  ?>
                                                                  <button type="button" onclick="addSubItemSiblink(this)" id="addRowButton2" class="btn border text-center bg-white"><i class="fa fa-plus"></i></button>
                                                                  <?php
                                                               }else{ ?>
                                                               <button type="button" onclick="removeSubItemSub1(this)" id="addRowButton2" class="btn border text-center bg-white"><i class="fa fa-minus"></i></button>
                                                               <?php
                                                               } ?>
                                                               
                                                         </span> 
                                                         <?php if(count(@$s['sub_sub'])>0){ 
                                                            ?>
                                                             <div class=" d-block mt-2 w-100 small-btn2" id="mast-div-sub-<?= $sub_item_id; ?>"><a style="cursor: pointer;" class="text-primary"  onclick="removeSubItemsSub1(this)">Remove Sub</a></div>
                                                         <?php }else{ ?>
                                                            <div class=" d-block mt-2 w-100 small-btn2" id="mast-div-sub-<?= $sub_item_id; ?>"><a style="cursor: pointer;" class="text-primary" id="cs-sub-btn-sub<?= $sub_item_id; ?>" onclick="addSubItemSub1(this)">Add Sub</a></div>
                                                         <?php } ?>
                                                         <!-- Sub Sub Items  -->
                                                         <?php 
                                                         $sub_sub_id=0;
                                                         foreach(@$s['sub_sub'] as $ss){ 
                                                                  $subs[$sub_item_id]["sub_sub"][$sub_sub_id]=count(@$ss['sub_sub_sub']);
                                                                  $sub_sub_item_id=$sub_item_id.'-'.$sub_sub_id;
                                                               ?>
                                                               <!-- Sub Sub Row  -->
                                                               <div style="margin-left:25px" class="w-100 sub-wr p-3 mt-2 ddf cs-sub-sub-item">
                                                                  <div class="input-group mb-3 mt-3" id="cs-sub-sub-item-<?= $sub_sub_item_id; ?>"> 
                                                                     <input id="Name" type="text" name="sub_sub_name[<?= $sub_item_id; ?>][<?= $sub_sub_id; ?>]" value="<?= $ss['name']; ?>" class="form-control" placeholder="Name"> 
                                                                     <select class="form-control sub-sub-select-type <?= @$ss['sub_sub_sub']?(count(@$ss['sub_sub_sub'])>0?'d-none':''):''; ?>" name="sub_sub_type[<?= $sub_item_id; ?>][<?= $sub_sub_id; ?>]" id="formGroupDefaultSelect" style="height: 41px;">  
                                                                        <option value="">Value Type</option>
                                                                        <?php foreach($type_ar as $t){ ?>
                                                                           <option value="<?= $t; ?>" <?= $t==@$ss['type']?'selected':''; ?>><?= $t; ?></option>
                                                                        <?php } ?>
                                                                     </select> 
                                                                     <input id="Name" name="sub_sub_value[<?= $sub_item_id; ?>][<?= $sub_sub_id; ?>]" type="number" value="<?= $ss['value']; ?>" class="form-control sub-sub-price-box <?= @$ss['sub_sub_sub']?(count(@$ss['sub_sub_sub'])>0?'d-none':''):''; ?>" placeholder="Value"> 
                                                                     <input type="hidden" name="sub_sub_id[<?= $sub_item_id; ?>][<?= $sub_sub_id; ?>]"  value="<?= $ss['id']; ?>"> 
                                                                     <span class="btn-box" id="cs-sub-spn-sub-sub-<?= $sub_sub_item_id; ?>"> 
                                                                        <?php if($sub_sub_id+1==count(@$s['sub_sub'])){ ?>
                                                                           <button type="button" onclick="addSubItemSub1Siblink(this)" id="addRowButton2" class="btn border text-center bg-white"><i class="fa fa-plus"></i></button>
                                                                          
                                                                           <?php }else{ ?>
                                                                           <button type="button" onclick="removeSubItemSub1(this)" id="addRowButton2" class="btn border text-center bg-white"><i class="fa fa-minus"></i></button>
                                                                        <?php } ?>
                                                                     </span> 

                                                                     <div class=" d-block mt-2 w-100 small-btn2" id="mast-div-sub-sub-<?= $sub_sub_item_id; ?>">
                                                                           <?php if(count(@$ss['sub_sub_sub'])>0){ ?>   
                                                                              <a style="cursor: pointer;" class="text-primary"  onclick="removeSubItemsSub2(this)">Remove Sub</a>
                                                                           <?php }else{  ?>
                                                                              <a style="cursor: pointer;" class="text-primary" id="cs-sub-btn-sub-sub-<?= $sub_sub_item_id; ?>" onclick="addSubItemSub2(this)">Add Sub</a>
                                                                           <?php } ?>   
                                                                     </div>
                                                                      <!-- Sub Sub Sub List  -->
                                                                     <?php 
                                                                     $sub_sub_sub_id=0;
                                                                     foreach(@$ss['sub_sub_sub'] as $sss){ 
                                                                           $sub_sub_sub_item_id=$sub_item_id.'-'.$sub_sub_id.'-'.$sub_sub_sub_id;
                                                                        ?>
                                                                          <!-- Sub Sub Sub Row  -->
                                                                           <div style="margin-left:40px" class="w-100 sub-wr p-3 mt-2 ddf cs-sub-sub-sub-item">
                                                                              <div class="input-group mb-3 mt-3" id="cs-sub-sub-sub-item-<?= @$sub_sub_item_id; ?>"> 
                                                                                    <input id="Name" type="text" name="sub_sub_sub_name[<?= @$sub_item_id; ?>][<?= @$sub_sub_id; ?>][<?= @$sub_sub_sub_id; ?>]" value="<?= $sss['name']; ?>" class="form-control" placeholder="Name"> 
                                                                                    <select class="form-control sub-sub-sub-select-type" name="sub_sub_sub_type[<?= @$sub_item_id; ?>][<?= @$sub_sub_id; ?>][<?= @$sub_sub_sub_id; ?>]"  id="formGroupDefaultSelect" style="height: 41px;">  
                                                                                       <option value="">Value Type</option>
                                                                                       <?php foreach($type_ar as $t){ ?>
                                                                                          <option value="<?= $t; ?>" <?= $t==@$sss['type']?'selected':''; ?>><?= $t; ?></option>
                                                                                       <?php } ?>
                                                                                    </select> 
                                                                                    <input id="Name" name="sub_sub_sub_value[<?= @$sub_item_id; ?>][<?= @$sub_sub_id; ?>][<?= @$sub_sub_sub_id; ?>]" type="text" value="<?= $sss['value']; ?>" class="form-control sub-sub-sub-price-box" placeholder="Value"> 
                                                                                    <input name="sub_sub_sub_id[<?= @$sub_item_id; ?>][<?= @$sub_sub_id; ?>][<?= @$sub_sub_sub_id; ?>]" type="hidden" value="<?= $sss['id']; ?>" > 
                                                                                    
                                                                                    <span class="btn-box" id="cs-sub-spn-sub-sub-sub-<?= @$sub_sub_item_id; ?>"> 
                                                                                       <?php if($sub_sub_sub_id+1==count($ss['sub_sub_sub'])){ ?>
                                                                                          <button type="button" onclick="addSubItemSub2Siblink(this)" id="addRowButton2" class="btn border text-center bg-white"><i class="fa fa-plus"></i></button>
                                                                                       <?php }else{ ?>
                                                                                          <button type="button" onclick="removeSubItemSub1(this)" id="addRowButton2" class="btn border text-center bg-white"><i class="fa fa-minus"></i></button>
                                                                                       <?php } ?>
                                                                                       

                                                                                    </span> 
                                                                                    <div class=" d-block mt-2 w-100 small-btn2" id="mast-div-sub-sub-sub-<?= @$sub_sub_item_id; ?>"></div>
                                                                              </div>
                                                                           </div>
                                                                          <!-- Sub Sub Sub Row  -->
                                                                     <?php 
                                                                  $sub_sub_sub_id++;
                                                                  } ?>   
                                                                      <!-- Sub Sub Sub List  -->
                                                                  </div>      
                                                               </div>
                                                               <!-- Sub Sub Row  -->
                                                         <?php
                                                            $sub_sub_id++;
                                                               } ?>
                                                         <!-- Sub Sub Items  -->      
                                                      </div>
                                                </div>
                                                <!-- /Sub Row  -->
                                          <?php 
                                       $sub_item_id++;   
                                       }
                                       
                                       }else{?>
                                       <div   class=" d-block mt-2 w-100" id="small-btn-main">
                                          <a style="cursor: pointer;" class="text-primary" onclick="addSubItemMainChild(this)">ADD SUB</a>
                                       </div>

                                      <?php } ?>       
                                 <!-- /Subs -->
                              </div>
                               <!-- /Add -->

                           </div>
                           <div class="col-md-12 show-sub-item-form-wrpr d-none">
                              <div class="row">
                                 <div class="col-md-12">
                                    <hr/>
                                    <div class="card-title mb-3">ADD SUB</div>
                                 </div>
                                 <div class="col-12 show-sub-item-form">
                                    <ul class="list-unstyled"></ul>
                                 </div>
                              </div>
                           </div>


                           <input type="hidden" name="id" value="<?php echo @$res['id'] ? $res['id'] : 0; ?>">
                           <div class="col-md-12 clearfix mt-3 mb-3  d-flex align-items-center justify-content-center justify-content-lg-center">
                              <button type="submit" id="addRowButton" class="btn btn-success btn-rounded mx-auto">Add Extras</button>
                           </div>
                        </div>
                        </form>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-12">
                     <div class="collapse" id="collapseExample" style="">
                        <div class="card">
                           <div class="card-header">
                              <div class="card-title">New extra item</div>
                           </div>
                           <div class="card-body">
                              <div class="row">
                                 <div class="col-md-12 col-main">

                                    <div class="input-group">
                                       <input id="Name" type="text" class="form-control" placeholder="Name">
                                       <select class="form-control select-type" id="formGroupDefaultSelect" style="height: 40px;">
                                          <option>Extra Type</option>
                                          <option>Remote</option>
                                          <option>Child Lock</option>
                                          
                                       </select>
                                       <input id="Name" type="text" class="form-control price-box" placeholder="Value">
                                       <span class="btn-box"> 
                                       <button type="button" onclick="addMainExtra(this)" id="addRowButton" class="btn border text-center bg-white"><i class="fa fa-plus"></i></button>
                                       </span>
                                       <div class=" d-block mt-2 w-100 small-btn" id="small-btn-main">
                                          <a style="cursor: pointer;" class="text-primary" onclick="addSubItem(this)">Add Sub</a>
                                       </div>
                                       <!-- -->
                                       
                                       <!-- -->
                                    </div>


                                 </div>
                                 <div class="col-md-12 show-sub-item-form-wrpr d-none">
                                    <div class="row">
                                       <div class="col-md-12">
                                          <hr>
                                          <div class="card-title mb-3">Add Sub</div>
                                       </div>
                                       <div class="col-12 show-sub-item-form">
                                          <ul class="list-unstyled"></ul>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-md-12 clearfix mt-3  d-flex align-items-center justify-content-center justify-content-lg-center">
                                    <button type="button" id="addRowButton" class="btn btn-success btn-rounded mx-auto">Add Extras</button>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="card p-3">
                        <div class="row">
                           <div class="col-12">
                           <form>
                              <div class="row">
                                
                                 <div class="col-lg-12">
                                    <div class="input-group">
                                       <div class="input-group-prepend">
                                          <span class="input-group-text" id="basic-addon1"> <i class="fa fa-search search-icon"></i></span>
                                       </div>
                                       <input type="text" name="search" class="form-control rounded-0" placeholder="Search Extra" aria-label="Username" aria-describedby="basic-addon1">
                                       <button type="submit" class="btn btn-primary rounded-0">Search</button>
                                    </div>
                                 </div>
                                 
                              </div>
                           </form>
                           </div>
                        </div>
                     </div>
                     <div class="row">


                    

                     <?php foreach($tabledata as $pro){ ?>
                        <div class="col-lg-12">
                           <div class="card pl-4 pr-4 pb-3 pt-3">
                              <div class="row">
                              </div>
                              <?php if(@$permissions['delete']){ ?>
                              <a href="<?php echo site_url('admin/'.$controller.'/delete/'.@$pro['id'].''); ?>" onclick="return confirm('<?php echo $this->lang->line('common_confirm_delete'); ?>');" data-toggle="tooltip" title="" class="text-muted text-right d-inline-block float-right p-0 bg-white close-box" data-original-title="Remove">
                              <i class="far fa-trash-alt"></i>
                              </a>
                              <?php } ?>
                              
                              <p class="mt-1 mb-0 p-main"> <?= @$pro['name']; ?>
                                 <?php if(count($pro['sub'])==0){ ?>
                                       : Value Type:<?= $pro['type']; ?><span class="font-weight-bold pl-2"> - <?= $pro['value']; ?> - </span>
                                    <?php } ?>
                              </p>
                              <?php foreach($pro['sub'] as $s){ ?>
                              <p class="mt-1 mb-0 p-main"> <?= $s['name']; ?>
                                 <?php if(count($s['sub'])==0){ ?>
                                       : Value Type:<?= $s['type']; ?><span class="font-weight-bold pl-2"> - <?= $s['value']; ?> - </span>
                                    <?php } ?>
                              </p>
                              <ul class="
                                 list-unstyled mb-2 mt-0 ul-extra">
                                    <?php foreach($s['sub'] as $ss){ ?>
                                       <li>
                                          <p c=""><?= $ss['name']; ?>
                                          <?php if(count($ss['sub'])==0){ ?>
                                                : Value Type:<?= $ss['type']; ?><span class="font-weight-bold pl-2"> - <?= $ss['value']; ?> - </span>
                                             <?php } ?>
                                          </p>
                                       </li>
                                          
                                             
                                             <?php foreach($ss['sub'] as $sss){  ?>
                                                <li>
                                                <p><?= $sss['name']; ?>: Value Type:<?= $sss['type']; ?><span class="font-weight-bold pl-2"> - <?= $sss['value']; ?> - </span></p>
                                             </li>
                                              
                                             <?php } ?>
                                       <?php } ?>
                                
                                 
                                
                                
                               
                              </ul>
                              <?php } ?>

                            
                           </div>
                        </div>
                     <?php } ?>
                       

                        <div class="col-12  d-flex align-items-center justify-content-center">
                           
                        <div class="col-12 text-center mx-auto">
                
                <nav aria-label="Page navigation example">
                       <?php echo $links; ?>
                </nav>
             </div>
                           
                        </div>
                     </div>
                  </div>
               </div>
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
    var htmlt = '<div class="input-group mb-3 mt-3"> <input id="Name" type="text" class="form-control" placeholder="Name"> <select class="form-control select-type" id="formGroupDefaultSelect" style="height: 40px;"> <option>Value Type</option> <option>2</option> <option>3</option> <option>4</option> <option>5</option> </select> <input id="Name" type="text" class="form-control price-box" placeholder="Value"> <span class="btn-box"> <button type="button" onclick="addMainExtra(this)" id="addRowButton" class="btn border text-center bg-white"><i class="fa fa-plus"></i></button></span>   <div   class=" d-block mt-2 w-100 small-btn"><a style="cursor: pointer;" class="text-primary" onclick="addSubItem(this)">Add Sub</a></div></div>';
   
   
    var htmlt2 = '<div class="input-group mb-3 mt-3"> <input id="Name" type="text" class="form-control" placeholder="Name"> <select class="form-control select-type" id="formGroupDefaultSelect" style="height: 41px;"> <option>Value Type</option> <option>2</option> <option>3</option> <option>4</option> <option>5</option> </select> <input id="Name" type="text" class="form-control price-box" placeholder="Value"> <span class="btn-box"> <button type="button" onclick="addMainExtra2(this)" id="addRowButton2" class="btn border text-center bg-white"><i class="fa fa-plus"></i></button></span> <div   class=" d-block mt-2 w-100 small-btn2"><a style="cursor: pointer;" class="text-primary" onclick="addSubItem2(this)">Add Sub</a></div>';
  
   
   
    var htmlt3 = '<div class="input-group mb-3 mt-3"> <input id="Name" type="text" class="form-control" placeholder="Name"> <select class="form-control select-type" id="formGroupDefaultSelect" style="height: 41px;"> <option>Value Type</option> <option>2</option> <option>3</option> <option>4</option> <option>5</option> </select> <input id="Name" type="text" class="form-control price-box" placeholder="Value"> <span class="btn-box"> <button type="button" onclick="addMainExtra2(this)" id="addRowButton" class="btn border text-center bg-white"><i class="fa fa-plus"></i></button></span> <div   class=" d-block mt-2 w-100 small-btn2"><a style="cursor: pointer;" class="text-primary linit mr-3" onclick="addSubItem2(this)">Add Sub</a>   <a style="cursor: pointer;" class="text-primary" onclick="removeSubItem2(this)">Remove Sub</a></div>     ';
   
   
   
   
   
   
   
   
   
   
   function addMainExtra(e){
      $(e).parents('.col-main').append(htmlt);
      $(e).parents('.input-group').find('.btn-box').html('<button onclick="removeMainItem(this)" type="button" id="addRowButton" class="btn border text-center bg-white"><i class="fa fa-minus"></i></button>');
   
   
   }
   
   
   
   
   
   
   var count = 1;
   var sub_item_id=<?= @$sub_item_id>0?$sub_item_id:0; ?>;
   var subs=<?= json_encode(@$subs); ?>;

   ////main Sub///
   ////First Level Child - 1
   function  addSubItemMainChild(e){
            $('.main-items-input').addClass('d-none');
            var htmlt_temp = '<div class="input-group mb-3 mt-3 cs-sub-item" id="cs-sub-item'+sub_item_id+'"> <input id="Name" type="text" name="sub_name['+sub_item_id+']" class="form-control" placeholder="Name"> <select class="form-control sub-select-type" name="sub_type['+sub_item_id+']" id="formGroupDefaultSelect" style="height: 41px;">  <option value="">Value Type</option> <?= $type_ar_str ?> </select> <input id="Name" name="sub_value['+sub_item_id+']" type="number" step="any" class="form-control sub-price-box" placeholder="Value"><input type="hidden" name="sub_id['+sub_item_id+']"  value="0"> <span class="btn-box"> <button type="button" onclick="addSubItemSiblink(this)" id="addRowButton2" class="btn border text-center bg-white"><i class="fa fa-plus"></i></button></span> <div   class=" d-block mt-2 w-100 small-btn2" id="mast-div-sub-'+sub_item_id+'"><a style="cursor: pointer;" class="text-primary" id="cs-sub-btn-sub'+sub_item_id+'" onclick="addSubItemSub1(this)">Add Sub</a></div>     ';
            $(e).parents('.input-group').append('<div style=" margin-left:15px" class="w-100 sub-wr mt-2">'+htmlt_temp+'</div>');
            $('#small-btn-main').html('<a style="cursor: pointer;" class="text-primary"  onclick="removeSubItemsMainChild(this)">Remove Sub</a>');
            subs.push({count:0});
            sub_item_id++;
      }


    ////Sub Level Siblinks
    function addSubItemSiblink(e){
         var htmlt3_temp = '<div class="input-group mb-3 mt-3 cs-sub-item" id="cs-sub-item'+sub_item_id+'"> <input id="Name" type="text" name="sub_name['+sub_item_id+']" class="form-control" placeholder="Name"> <select class="form-control sub-select-type" name="sub_type['+sub_item_id+']" id="formGroupDefaultSelect" style="height: 41px;"> <option value="">Value Type</option> <?= $type_ar_str ?> </select> <input id="Name" type="number" step="any" name="sub_value['+sub_item_id+']" class="form-control sub-price-box" placeholder="Value"><input type="hidden" name="sub_id['+sub_item_id+']"  value="0"> <span class="btn-box"> <button type="button" onclick="addSubItemSiblink(this)" id="addRowButton" class="btn border text-center bg-white"><i class="fa fa-plus"></i></button></span> <div   class=" d-block mt-2 w-100 small-btn2" id="mast-div-sub-'+sub_item_id+'"><a style="cursor: pointer;" class="text-primary linit mr-3" id="cs-sub-btn-sub'+sub_item_id+'" onclick="addSubItemSub1(this)">Add Sub</a>   <a style="cursor: pointer;" class="text-primary" onclick="removeSubItemMain(this)">Remove Sub</a></div>';
         $(e).html('<i class="fa fa-minus"></i>');
         $(e).attr('onclick', 'removeSubItemMain(this)');
         $(e).parents('.sub-wr').append('<div class="mt-2">'+htmlt3_temp+'</div>');
         subs.push({count:0});
         sub_item_id++;
      }


      //Sub Level 1 Items remove Main
      function removeSubItemsMainChild(e){
            $('.main-items-input').removeClass('d-none');
            $(e).parents('.input-group').find('.sub-wr').removeClass('p-3');
            $(e).parents('.input-group').find('.sub-wr').remove();
            $('#small-btn-main').html('<a style="cursor: pointer;" class="text-primary"  onclick="addSubItemMainChild(this)">Add Sub</a>');
   }


    //Sub Level 1 Item remove Main
   function removeSubItemMain(e){
         if($('.cs-sub-item').length==1){
                  $('.main-items-input').removeClass('d-none');
                  $(e).parents('.input-group').find('.sub-wr').removeClass('p-3');
                  $(e).parents('.input-group').find('.sub-wr').remove();
                  $('#small-btn-main').html('<a style="cursor: pointer;" class="text-primary"  onclick="addSubItemMainChild(this)">Add Sub</a>');
            }
         else{
                  $(e).parent().parent().remove();
            }   
      }


      //Sub Level Item remove

      function removeSubItem2(e){
               $(e).parent().parent().parent().remove();
               var htmlt3_temp = '<div class="input-group mb-3 mt-3 cs-sub-item" id="cs-sub-item'+sub_item_id+'"> <input id="Name" type="text" name="sub_name['+sub_item_id+']" class="form-control" placeholder="Name"> <select class="form-control select-type" name="sub_type['+sub_item_id+']" id="formGroupDefaultSelect" style="height: 41px;"> <option value="">Value Type</option><option value="Percentage">Percentage</option><option value="Flat">Flat</option> </select> <input id="Name" type="text" name="sub_value['+sub_item_id+']" class="form-control price-box" placeholder="Value"> <span class="btn-box"> <button type="button" onclick="addSubItemSiblink(this)" id="addRowButton" class="btn border text-center bg-white"><i class="fa fa-plus"></i></button></span> <div   class=" d-block mt-2 w-100 small-btn2"><a style="cursor: pointer;" class="text-primary linit mr-3" id="cs-sub-btn-sub'+sub_item_id+'" onclick="addSubItemSub1(this)">Add Sub</a>   <a style="cursor: pointer;" class="text-primary" onclick="removeSubItemMain(this)">Remove Sub</a></div>';
            
            }

       ////Sub Sub 1///
       function  addSubItemSub1(e){
                    var sub_item_id=$(e).attr('id').split("cs-sub-btn-sub")[1];
                    if(typeof subs[sub_item_id]["count"]!='undefined'){
                           var sub_sub_id=subs[sub_item_id]["count"];
                           if(typeof subs[sub_item_id]["sub_sub"]=="undefined"){
                                 subs[sub_item_id]["sub_sub"]=[];
                              }

                        }
                     else{
                           subs[sub_item_id]["count"]=0;
                           subs[sub_item_id]["sub_sub"]=[];
                           var sub_sub_id=0;
                        }   
                     subs[sub_item_id]["sub_sub"][sub_sub_id]=0;   
                     var sub_sub_item_id=sub_item_id.toString()+"-"+sub_sub_id.toString();
                     var htmlt_temp = '<div class="input-group mb-3 mt-3" id="cs-sub-sub-item-'+sub_sub_item_id+'"> <input id="Name" type="text" name="sub_sub_name['+sub_item_id+']['+sub_sub_id+']" class="form-control" placeholder="Name"> <select class="form-control sub-sub-select-type" name="sub_sub_type['+sub_item_id+']['+sub_sub_id+']" id="formGroupDefaultSelect" style="height: 41px;">  <option value="">Value Type</option> <?= $type_ar_str ?> </select> <input id="Name" name="sub_sub_value['+sub_item_id+']['+sub_sub_id+']" type="number" step="any" class="form-control sub-sub-price-box" placeholder="Value"> <input type="hidden" name="sub_sub_id['+sub_item_id+']['+sub_sub_id+']"  value="0"> <span class="btn-box" id="cs-sub-spn-sub-sub-'+sub_sub_item_id+'"> <button type="button" onclick="addSubItemSub1Siblink(this)" id="addRowButton2" class="btn border text-center bg-white"><i class="fa fa-plus"></i></button></span> <div   class=" d-block mt-2 w-100 small-btn2" id="mast-div-sub-sub-'+sub_sub_item_id+'"><a style="cursor: pointer;" class="text-primary" id="cs-sub-btn-sub-sub-'+sub_sub_item_id+'" onclick="addSubItemSub2(this)">Add Sub</a></div>';
                     $(e).parent().parent().append('<div style="margin-left:25px" class="w-100 sub-wr p-3 mt-2 ddf cs-sub-sub-item">'+htmlt_temp+'</div>');
                     $(e).parent().parent().find('.sub-price-box').addClass('d-none');
                     $(e).parent().parent().find('.sub-select-type').addClass('d-none');
                     $('#mast-div-sub-'+sub_item_id).html('<a style="cursor: pointer;" class="text-primary"  onclick="removeSubItemsSub1(this)">Remove Sub</a>');
                     subs[sub_item_id]["count"]=subs[sub_item_id]["count"]+1;
         }  

      function  addSubItemSub1Siblink(e){
                     var temp=$(e).parent().attr('id').split("cs-sub-spn-sub-sub-")[1];
                     var sub_item_id=temp.split('-')[0];
                     if(typeof subs[sub_item_id]["count"]!='undefined'){
                           var sub_sub_id=subs[sub_item_id]["count"];
                           subs[sub_item_id]["sub_sub"].push(0);
                           if(typeof subs[sub_item_id]["sub_sub"]=="undefined"){
                                 subs[sub_item_id]["sub_sub"]=[];
                              }
                        }
                     else{
                           subs[sub_item_id]["count"]=0;
                           subs[sub_item_id]["sub_sub"]=[];
                           var sub_sub_id=0;
                        }   
                     subs[sub_item_id]["sub_sub"][sub_sub_id]=0; 
                     var sub_sub_item_id=sub_item_id.toString()+"-"+sub_sub_id.toString();

                     var htmlt_temp = '<div class="input-group mb-3 mt-3" id="cs-sub-sub-item-'+sub_sub_item_id+'"> <input id="Name" type="text" name="sub_sub_name['+sub_item_id+']['+sub_sub_id+']" class="form-control" placeholder="Name"> <select class="form-control sub-sub-select-type" name="sub_sub_type['+sub_item_id+']['+sub_sub_id+']" id="formGroupDefaultSelect" style="height: 41px;">  <option value="">Value Type</option> <?= $type_ar_str ?> </select> <input id="Name" name="sub_sub_value['+sub_item_id+']['+sub_sub_id+']" type="number" step="any" class="form-control sub-sub-price-box" placeholder="Value"><input type="hidden" name="sub_sub_id['+sub_item_id+']['+sub_sub_id+']"  value="0"> <span class="btn-box" id="cs-sub-spn-sub-sub-'+sub_sub_item_id+'"> <button type="button" onclick="addSubItemSub1Siblink(this)" id="addRowButton2" class="btn border text-center bg-white"><i class="fa fa-plus"></i></button></span> <div   class=" d-block mt-2 w-100 small-btn2" id="mast-div-sub-sub-'+sub_sub_item_id+'"><a style="cursor: pointer;" class="text-primary" id="cs-sub-btn-sub-sub-'+sub_sub_item_id+'" onclick="addSubItemSub2(this)">Add Sub</a></div>     ';
                     $(e).parent().parent().parent().parent().append('<div style="margin-left:25px" class="w-100 sub-wr p-3 mt-2 ddf cs-sub-sub-item">'+htmlt_temp+'</div>');
                     $(e).parent().parent().parent().parent().find('.sub-price-box').addClass('d-none');
                     $(e).parent().parent().parent().parent().find('.sub-select-type').addClass('d-none');
                     $(e).html('<i class="fa fa-minus"></i>');
                     $(e).attr('onclick', 'removeSubItemSub1(this)');
                     subs[sub_item_id]["count"]=subs[sub_item_id]["count"]+1;
         }    

      ///remove Sub sub items sub   
      function removeSubItemsSub1(e){
               $(e).parent().parent().find('.cs-sub-sub-item').remove();
               var sub_item_id=$(e).parent().attr('id').split('mast-div-sub-')[1];
               $(e).parent().html('<a style="cursor: pointer;" class="text-primary linit mr-3" id="cs-sub-btn-sub'+sub_item_id+'" onclick="addSubItemSub1(this)">Add Sub</a>');
         }  

      function removeSubItemSub1(e){
            $(e).parent().parent().parent().remove();
            
         }     

     

       ////////////   

        ////Sub Sub Sub Items
        function  addSubItemSub2(e){
               var sub_item=$(e).attr('id').split('cs-sub-btn-sub-sub-')[1];
               console.log(sub_item);
               var sub_item_id=sub_item.split('-')[0];
               var sub_sub_id=sub_item.split('-')[1];
               var sub_sub_sub_id=subs[sub_item_id]["sub_sub"][sub_sub_id];
               var sub_sub_item_id=sub_item_id+"-"+sub_sub_id+"-"+sub_sub_sub_id;
               subs[sub_item_id]["sub_sub"][sub_sub_id]=subs[sub_item_id]["sub_sub"][sub_sub_id]+1;
               $(e).parent().parent().find(".sub-sub-select-type").addClass('d-none');
               $(e).parent().parent().find(".sub-sub-price-box").addClass('d-none');
               var htmlt_temp = '<div class="input-group mb-3 mt-3" id="cs-sub-sub-sub-item-'+sub_sub_item_id+'"> <input id="Name" type="text" name="sub_sub_sub_name['+sub_item_id+']['+sub_sub_id+']['+sub_sub_sub_id+']" class="form-control" placeholder="Name"> <select class="form-control sub-sub-sub-select-type" name="sub_sub_sub_type['+sub_item_id+']['+sub_sub_id+']['+sub_sub_sub_id+']" id="formGroupDefaultSelect" style="height: 41px;">  <option value="">Value Type</option> <?= $type_ar_str ?> </select> <input id="Name" name="sub_sub_sub_value['+sub_item_id+']['+sub_sub_id+']['+sub_sub_sub_id+']" type="number" step="any" class="form-control sub-sub-sub-price-box" placeholder="Value"> <input name="sub_sub_sub_id['+sub_item_id+']['+sub_sub_id+']['+sub_sub_sub_id+']" type="hidden" value="0" > <span class="btn-box" id="cs-sub-spn-sub-sub-sub-'+sub_sub_item_id+'"> <button type="button" onclick="addSubItemSub2Siblink(this)" id="addRowButton2" class="btn border text-center bg-white"><i class="fa fa-plus"></i></button></span> <div   class=" d-block mt-2 w-100 small-btn2" id="mast-div-sub-sub-sub-'+sub_sub_item_id+'"></div>     ';
               $(e).parent().parent().append('<div style="margin-left:40px" class="w-100 sub-wr p-3 mt-2 ddf cs-sub-sub-sub-item">'+htmlt_temp+'</div>');
               $(e).parent().html('<a style="cursor: pointer;" class="text-primary"  onclick="removeSubItemsSub2(this)">Remove Sub</a>');
               $(e).attr('onclick', 'removeSubItemSub2(this)');
            }  

         function addSubItemSub2Siblink(e){
               console.log($(e).parent().attr('id'));
               var sub_item=$(e).parent().attr('id').split('cs-sub-spn-sub-sub-sub-')[1];
               var sub_item_id=sub_item.split('-')[0];
               var sub_sub_id=sub_item.split('-')[1];
               var sub_sub_sub_id=subs[sub_item_id]["sub_sub"][sub_sub_id];
               var sub_sub_item_id=sub_item_id+"-"+sub_sub_id+"-"+sub_sub_sub_id;
               subs[sub_item_id]["sub_sub"][sub_sub_id]=subs[sub_item_id]["sub_sub"][sub_sub_id]+1;
               var htmlt_temp = '<div class="input-group mb-3 mt-3" id="cs-sub-sub-sub-item-'+sub_sub_item_id+'"> <input id="Name" type="text" name="sub_sub_sub_name['+sub_item_id+']['+sub_sub_id+']['+sub_sub_sub_id+']" class="form-control" placeholder="Name"> <select class="form-control sub-sub-sub-select-type" name="sub_sub_sub_type['+sub_item_id+']['+sub_sub_id+']['+sub_sub_sub_id+']" id="formGroupDefaultSelect" style="height: 41px;">  <option value="">Value Type</option><?= $type_ar_str ?> </select> <input id="Name" name="sub_sub_sub_value['+sub_item_id+']['+sub_sub_id+']['+sub_sub_sub_id+']" type="number" step="any" class="form-control sub-sub-sub-price-box" placeholder="Value"> <input name="sub_sub_sub_id['+sub_item_id+']['+sub_sub_id+']['+sub_sub_sub_id+']" type="hidden" value="0" > <span class="btn-box" id="cs-sub-spn-sub-sub-sub-'+sub_sub_item_id+'"> <button type="button" onclick="addSubItemSub2Siblink(this)" id="addRowButton2" class="btn border text-center bg-white"><i class="fa fa-plus"></i></button></span> <div   class=" d-block mt-2 w-100 small-btn2" id="mast-div-sub-sub-sub-'+sub_sub_item_id+'"></div>     ';
               $(e).parent().parent().parent().parent().append('<div style="margin-left:40px" class="w-100 sub-wr p-3 mt-2 ddf cs-sub-sub-item">'+htmlt_temp+'</div>');
               $(e).html('<i class="fa fa-minus"></i>');
                     $(e).attr('onclick', 'removeSubItemSub1(this)');
            }      

      function removeSubItemsSub2(e){
            //console.log($(e).parent().attr('id'));
            var sub_item=$(e).parent().attr('id').split('mast-div-sub-sub-')[1];
            //console.log(sub_item);
           // var sub_item_id=sub_item.split('-')[0];
            //var sub_sub_id=sub_item.split('-')[1];
            $(e).parent().parent().find('.cs-sub-sub-sub-item').remove();
            $(e).parent().html('<a style="cursor: pointer;" class="text-primary linit mr-3" id="cs-sub-btn-sub-sub-'+sub_item+'" onclick="addSubItemSub2(this)">Add Sub</a>');
         }       


      /////      


      


/*

      function  addSubItem2(e){
               var time = $('.sub-wr').find('.input-group').length;
               //alert(time);
               if(time < 4){
                        var htmlt3_temp = '<div class="input-group mb-3 mt-3"> <input id="Name" type="text" class="form-control" placeholder="Name"> <select class="form-control select-type" id="formGroupDefaultSelect" style="height: 41px;"> <option>Value Type</option> <option>2</option> <option>3</option> <option>4</option> <option>5</option> </select> <input id="Name" type="text" class="form-control price-box" placeholder="Value"> <span class="btn-box"> <button type="button" onclick="addMainExtra2(this)" id="addRowButton" class="btn border text-center bg-white"><i class="fa fa-plus"></i></button></span> <div   class=" d-block mt-2 w-100 small-btn2"><a style="cursor: pointer;" class="text-primary linit mr-3" onclick="addSubItem2(this)">Add Sub</a>   <a style="cursor: pointer;" class="text-primary" onclick="removeSubItem2(this)">Remove Sub</a></div>     ';
                        count++;
                        $(e).parents('.input-group').find('.price-box').addClass('d-none');
                        $(e).parents('.input-group').find('.select-type').addClass('d-none');
                        $(e).parents('.sub-wr').append('<div class="ddf" style="margin-left:'+count+'5px" class="w-100 sub-wr p-3 mt-2">'+htmlt3_temp+'</div>');
                  }
   
               if(time == 4){
                     // $(e).parents('.small-btn2').find('.linit').remove();
                     alert('Sub items limit exceeded');
                  }
   }  */



   function removeMainItem(e){
   $(e).parents('.input-group').removeClass('mb-3 mt-3');
   $(e).parents('.input-group').html('');
   
   
   
   
   
   }
   
   
   function removeSubItem(e){
   $(e).parents('.input-group').find('.price-box').removeClass('d-none');
   $(e).parents('.input-group').find('.select-type').removeClass('d-none');
   
   $(e).parents('.input-group').find('.sub-wr').removeClass('p-3');
   $(e).parents('.input-group').find('.sub-wr').remove();
   
   $(e).parents('.input-group').find('.small-btn').html('<a style="cursor: pointer;" class="text-primary"  onclick="addSubItem(this)">Add Sub</a>');
   }

   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
  
   
   
    function removeExtra2(e){
    
   $(e).parent().parent().remove();
   }
   
</script>