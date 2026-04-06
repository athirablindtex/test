<?php $type_ar = array('Percentage', 'Flat Value Independent', 'Flat Value Width Dependent', 'Flat Value Height Dependent', 'Flat Value Square Meter Dependent', 'Flat Value Box Value', 'Matrix', "Multiple","Area");
$type_ar_str = "";
foreach ($type_ar as $t) {
   $type_ar_str .= '<option value="' . $t . '">' . $t . '</option>';
}

?>

<style>
   .pagination li>a {
      padding: 5px !important;
      border-radius: 23px;
      line-height: 15px !important;
      width: 35px;
   }
   input.form-control.sub-price-box {
    margin-left: 10px;
}
.type-value-wrapper {
   
    padding: 15px;
}
button.btn.btn-sm.btn-primary.ms-2.add-type-value

 {
    margin-left: 20px;
}
</style>

<!-- Modal Import -->
<div class="modal fade" id="importModal" aria-labelledby="importModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="card-title" id="importModalLabel">Import Fabrics</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <form id="" action="<?php echo site_url('admin/extras/excel_import'); ?>" method="POST" enctype="multipart/form-data">
            <div class="modal-body">
               <div class="row">
                  <div class="col-xs-12 col-sm-12 col-md-12">
                     <div class="form-group form-group-default">
                        <strong>Upload Excel/CSV Files:</strong>
                        <input type="file" name="excel_file" id="excel_file" class="form-control" />
                     </div>
                  </div>
               </div>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
               <button type="submit" class="btn btn-primary" onclick="this.disabled=true;this.form.submit();">Import</button>
               <a href="<?php echo site_url('assets/templates/extras_template.xls'); ?>" target="_blank">Download Template </a>
            </div>
         </form>
      </div>
   </div>
</div>
<!-- Modal Import -->
<div class="main-panel">
   <div class="content">
      <div class="page-inner">
         <div class="page-header">
            <h4 class="page-title">Extras</h4>
            <div class="btn-group btn-group-page-header ml-auto">
               <?php if (@$permissions['add']) { ?>
                  <button class="btn btn-secondary btn-round mr-2" data-toggle="modal" data-target="#importModal" id="ImportBtn">
                     <i class="fa fa-file-excel"></i>
                     Import
                  </button>

                  <button type="button" class="btn btn-success btn-rounded <?= @$edit == 0 ? 'collapsed' : ((@validation_errors() ? '' : 'collapsed')); ?>" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                     <i class="fa fa-plus mr-2"></i> New Extras
                  </button>
               <?php } ?>
            </div>
         </div>
         <div class="row">
            <div class="col-md-12">
               <div class="collapse <?= @$edit == 0 ? (@validation_errors() ? 'show' : '') : 'show'; ?>" id="collapseExample" style="">
                  <div class="card">
                     <div class="card-header">
                        <div class="card-title">New extra item</div>
                     </div>
                     <div class="card-body">
                        <form method="post">
                           <div class="row">
                              <?php if (@validation_errors()) { ?>
                                 <div class="col-md-12">
                                    <div class="alert alert-danger" role="alert">
                                       <?php echo @validation_errors(); ?>
                                    </div>
                                 </div>
                              <?php } ?>


                              <div class="col-md-12 col-main">

                                 <!-- Add -->
                                 <div class="input-group">
                                    <input id="Name" type="text" class="form-control" name="name" placeholder="Name" value="<?php echo @$res['name'] != '' ? @$res['name'] : $this->input->post('name'); ?>">
                                    <!-- <div class="border text-center">
                                       <div class="form-check p-0 px-2">
                                          <label class="form-check-label m-0" style="line-height: 38px;">
                                             <input class="form-check-input" <?php // echo @$res['mandatory'] == 1 ? 'checked' : ''; 
                                                                              ?> type="checkbox" name="mandatory" value="1">
                                             <span class="form-check-sign">Mandatory</span>
                                          </label>
                                       </div>
                                    </div> -->

                                    <select class="form-control main-items-input <?= @$sub ? (count($sub) > 0 ? 'd-none' : '') : ''; ?>" name="type" id="formGroupDefaultSelect" style="height: 40px;">
                                       <option value="">Value Type</option>


                                       <?php foreach ($type_ar as $t) { ?>
                                          <option value="<?= $t; ?>" <?= $t == @$res['type'] ? 'selected' : ''; ?>><?= $t; ?></option>
                                       <?php } ?>

                                    </select>
                                    <input id="Name" name="value" type="number" step="any" class="form-control main-items-input <?= @$sub ? (count($sub) > 0 ? 'd-none' : '') : ''; ?>" value="<?php echo @$res['value'] != '' ? @$res['value'] : $this->input->post('value'); ?>" placeholder="Value">


                                    <!-- Subs  -->
                                    <?php
                                    $sub_item_id = 0;
                                    $subs = array();
                                    // echo "<pre>";
                                    // print_r(@$sub);
                                    // exit;

                                    if (@$sub) { ?>
                                       <div class=" d-block mt-2 w-100" id="small-btn-main">
                                          <a style="cursor: pointer;" class="text-primary" onclick="removeSubItemsMainChild(this)">Remove Sub</a>
                                       </div>
                                       <?php
                                       foreach (@$sub as $s) {
                                          $cur_item = array("count" => count($s['sub_sub']));
                                          $cur_item["sub_sub"] = array();
                                          $subs[] = $cur_item;
                           ?>
                                          <!-- Sub Row  -->
                                          <div style=" margin-left:15px" class="w-100 sub-wr mt-2">
                                             <div class="input-group mb-3 mt-3 cs-sub-item" id="cs-sub-item<?= $sub_item_id; ?>">
                                                <input id="Name" data-id="<?= @$s['id']; ?>"  type="text" name="sub_name[<?= $sub_item_id; ?>]" value="<?= @$s['name']; ?>" class="form-control" placeholder="Name">
                                       
                                                <div class="type-value-wrapper">

                                                <?php if($s['type'] !='Multiple'){ ?>
                                          
                                                   <div class="d-flex type-value-row mb-2 align-items-center">
                                                      <select class="form-control me-2 sub-type sub-select-type <?= @$s['sub_sub'] && count(@$s['sub_sub']) > 0 ? 'd-none' : ''; ?>"
                                                         name="sub_type[<?= $sub_item_id; ?>][]"
                                                         style="height: 41px;">
                                                         <option value="">Value Types</option>
                                                         <?php foreach ($type_ar as $t) { ?>
                                                            <option value="<?= $t; ?>" <?= $t == @$s['type'] ? 'selected' : ''; ?>><?= $t; ?></option>
                                                         <?php } ?>
                                                      </select>



                                                      <input name="sub_value[<?= $sub_item_id; ?>][]"
                                                         value="<?= @$s['value']; ?>"
                                                         type="number"
                                                         step="any"
                                                         class="form-control sub-price-box <?= @$s['sub_sub'] && count(@$s['sub_sub']) > 0 ? 'd-none' : ''; ?>"
                                                         placeholder="Value">

                                                      <input type="hidden" name="sub_id[<?= $sub_item_id; ?>]" value="<?= @$s['id']; ?>">

                                                      <button type="button"
                                                         class="btn btn-sm btn-primary ms-2 add-type-value"
                                                         data-sub-id="<?= $sub_item_id; ?>"
                                                         data-id="<?= @$s['id']; ?>">+ Add Option</button>
                                                   </div>
                                                   <?php } 
                                                     else{ ?>
                                                     <?php foreach($s['sub_sub'] as $sub_sub_item){ ?>
                                                      <div class="d-flex type-value-row mb-2 align-items-center">
                                                         <select class="form-control me-2 sub-type sub-select-type"
                                                            name="sub_type[<?= $sub_item_id; ?>][]"
                                                            style="height: 41px;">
                                                            <option value="">Value Types</option>
                                                            <?php foreach ($type_ar as $t) { ?>
                                                               <option value="<?= $t; ?>" <?= $t == @$sub_sub_item['type'] ? 'selected' : ''; ?>><?= $t; ?></option>
                                                            <?php } ?>
                                                         </select>

                                                         <input name="sub_value[<?= $sub_item_id; ?>][]"
                                                            value="<?= @$sub_sub_item['price']; ?>"
                                                            type="number"
                                                            step="any"
                                                            class="form-control sub-price-box"
                                                            placeholder="Value">
                                                             <button type="button" class="btn btn-danger ms-2 remove-type-value" style="margin-left:10px">×</button>

                                                         <input type="hidden" name="sub_id[<?= $sub_item_id; ?>]" value="<?= @$s['id']; ?>">
                                                           <button type="button"
                                                         class="btn btn-sm btn-primary ms-2 add-type-value"
                                                         data-sub-id="<?= $sub_item_id; ?>"
                                                         data-id="<?= @$s['id']; ?>">+ Add Option</button>
                                                      </div>


                                                     <?php }  ?>
                                                       <?php }  ?>
                                                    
                                                </div>



                                                <!-- Add more button -->


                                                <span class="btn-box">
                                                   <?php if ($sub_item_id + 1 == count($sub)) {
                                                   ?>
                                                      <button type="button" onclick="addSubItemSiblink(this)" id="addRowButton2" class="btn border text-center bg-white"><i class="fa fa-plus"></i></button>
                                                   <?php
                                                   } else { ?>
                                                      <button type="button" onclick="removeSubItemSub1(this)" id="addRowButton2" class="btn border text-center bg-white"><i class="fa fa-minus"></i></button>
                                                   <?php
                                                   } ?>

                                                </span>
                                                <!-- <?php if (count(@$s['sub_sub']) > 0) {
                                                      ?>
                                                   <div class=" d-block mt-2 w-100 small-btn2" id="mast-div-sub-<?= $sub_item_id; ?>"><a style="cursor: pointer;" class="text-primary" onclick="removeSubItemsSub1(this)">Remove Sub</a></div>
                                                <?php } else { ?>
                                                   <div class=" d-block mt-2 w-100 small-btn2" id="mast-div-sub-<?= $sub_item_id; ?>"><a style="cursor: pointer;" class="text-primary" id="cs-sub-btn-sub<?= $sub_item_id; ?>" onclick="addSubItemSub1(this)">Add Sub</a></div>
                                                <?php } ?> -->
                                                <!-- Sub Sub Items  -->
                                                <?php
                                                $sub_sub_id = 0;
                                                ?>
                                                <!-- Sub Sub Items  -->
                                             </div>
                                          </div>
                                          <!-- /Sub Row  -->
                                       <?php
                                          $sub_item_id++;
                                       }
                                    } else { ?>
                                       <div class=" d-block mt-2 w-100" id="small-btn-main">
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
                                       <hr />
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
                                          <input type="text" name="search"  id="search" class="form-control rounded-0" placeholder="Search Extra" aria-label="Username" aria-describedby="basic-addon1">
                                          <button type="submit" class="btn btn-primary rounded-0">Search</button>
                                       </div>


                                    </div>

                                 </div>
                              </form>

                              <!-- Add a wrapper for toggling -->
                              <div style="margin-top: 30px;">
                                 <h3 id="toggle_form" style="cursor: pointer;">
                                    Extras Matrix Import
                                    <i id="toggle_icon" class="fa fa-chevron-down"></i>
                                 </h3>

                                 <form id="import_extras" method="post" enctype="multipart/form-data" style="display: none;">
                                    <div class="form-row">
                                       <div class="col" style="padding-left: 20px;">
                                          <select id="parent_extra" class="form-control select2">
                                             <option value="">Select Parent</option>
                                             <?php foreach ($extras_parent as $parent): ?>
                                                <option value="<?= $parent['id'] ?>"><?= $parent['name'] ?></option>
                                             <?php endforeach; ?>
                                          </select>
                                       </div>

                                       <div class="col" style="padding-left: 20px;">
                                          <select id="child_extra" class="form-control select2" name="extras_id">
                                             <option value="">Select Child</option>
                                          </select>
                                       </div>

                                       <div class="col">
                                          <div class="input-file">
                                             <input type="file" class="form-control form-control-file" id="upload_file" name="upload_file">
                                             <label for="upload_file" class="btn bg-white border-0" style="color: #000000 !important;">
                                                <i class="fa fa-upload mr-2"></i>Import File
                                             </label>
                                          </div>
                                       </div>

                                       <div class="col-auto">
                                          <button type="submit" class="btn btn-primary">
                                             <i class="fa fa-paper-plane mr-1"></i> Submit
                                          </button>
                                          <a href="https://staging.tradeblindsdirect.com/blindtex-app/assets/templates/Matrix_template_priceband.xls" target="_blank">Download Template</a>
                                       </div>
                                    </div>
                                 </form>
                                 <div id="priceband-result"></div>
                              </div>

                           </div>
                        </div>
                     </div>
                     <div class="row" style="background-color: white;">




                       

                             <div class="table-responsive">
                        <table id="basic-datatables" class="display table table-hover table-bordered">
                           <thead>
                              <tr>
                                 <th>ID</th>
                                 <th>Name</th>
                             
                                 <th style="width: 10%">Action</th>
                              </tr>
                           </thead>
                           <tbody id="tabledata">
                              <?php $i = 1; echo "<pre>"; //print_r($tabledata)?>
                        <?php foreach ($tabledata as $key=>$pro) {  ?>
                                 <tr>
                                    <td><?= $i++ ?></td>
                                    <td><?php echo  @$pro['name']; ?></td>
                             
                                    <td> <?php if (@$permissions['add']) { ?>
                                       <a href="<?php echo site_url('admin/' . $controller . '/list/edit/' . @$pro['id'] . ''); ?>" data-toggle="tooltip" title="" class="text-muted d-inline-block bg-white mr-1" data-original-title="Edit">
                                          <i class="fa fa-edit"></i>
                                       </a>
                                    <?php } ?>
                                    <?php if (@$permissions['delete']) { ?>
                                       <a href="<?php echo site_url('admin/' . $controller . '/delete/' . @$pro['id'] . ''); ?>" onclick="return confirm('<?php echo $this->lang->line('common_confirm_delete'); ?>');" data-toggle="tooltip" title="" class="text-muted d-inline-block bg-white" data-original-title="Remove">
                                          <i class="far fa-trash-alt"></i>
                                       </a>
                                    <?php } ?></td>
                                    
                                   
                                 </tr>
                              <?php } ?>


                           </tbody>
                        </table>
                       
                     </div>
                  


                        <div class="col-12  d-flex align-items-center justify-content-center">

                           <div class="col-12 text-center mx-auto">

                              <nav aria-label="Page navigation example" id="pagination">
                          
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
<script>
   $(document).ready(function() {
      //$('#basic-datatables').DataTable({});

      $('#multi-filter-select').DataTable({
         "pageLength":10,
         initComplete: function() {
            this.api().columns().every(function() {
               var column = this;
               var select = $('<select class="form-control"><option value=""></option></select>')
                  .appendTo($(column.footer()).empty())
                  .on('change', function() {
                     var val = $.fn.dataTable.util.escapeRegex(
                        $(this).val()
                     );

                     column
                        .search(val ? '^' + val + '$' : '', true, false)
                        .draw();
                  });

               column.data().unique().sort().each(function(d, j) {
                  select.append('<option value="' + d + '">' + d + '</option>')
               });
            });
         }
      });

   });
   var htmlt = '<div class="input-group mb-3 mt-3"> <input id="Name" type="text" class="form-control" placeholder="Name"> <select class="form-control select-type" id="formGroupDefaultSelect" style="height: 40px;"> <option>Value Type</option> <option>2</option> <option>3</option> <option>4</option> <option>5</option> </select> <input id="Name" type="text" class="form-control price-box" placeholder="Value"> <span class="btn-box"> <button type="button" onclick="addMainExtra(this)" id="addRowButton" class="btn border text-center bg-white"><i class="fa fa-plus"></i></button></span>   <div   class=" d-block mt-2 w-100 small-btn"><a style="cursor: pointer;" class="text-primary" onclick="addSubItem(this)">Add Sub</a></div></div>';


   var htmlt2 = '<div class="input-group mb-3 mt-3"> <input id="Name" type="text" class="form-control" placeholder="Name"> <select class="form-control select-type" id="formGroupDefaultSelect" style="height: 41px;"> <option>Value Type</option> <option>2</option> <option>3</option> <option>4</option> <option>5</option> </select> <input id="Name" type="text" class="form-control price-box" placeholder="Value"> <span class="btn-box"> <button type="button" onclick="addMainExtra2(this)" id="addRowButton2" class="btn border text-center bg-white"><i class="fa fa-plus"></i></button></span> <div   class=" d-block mt-2 w-100 small-btn2"><a style="cursor: pointer;" class="text-primary" onclick="addSubItem2(this)">Add Sub</a></div>';



   var htmlt3 = '<div class="input-group mb-3 mt-3"> <input id="Name" type="text" class="form-control" placeholder="Name"> <select class="form-control select-type" id="formGroupDefaultSelect" style="height: 41px;"> <option>Value Type</option> <option>2</option> <option>3</option> <option>4</option> <option>5</option> </select> <input id="Name" type="text" class="form-control price-box" placeholder="Value"> <span class="btn-box"> <button type="button" onclick="addMainExtra2(this)" id="addRowButton" class="btn border text-center bg-white"><i class="fa fa-plus"></i></button></span> <div   class=" d-block mt-2 w-100 small-btn2"><a style="cursor: pointer;" class="text-primary linit mr-3" onclick="addSubItem2(this)">Add Sub</a>   <a style="cursor: pointer;" class="text-primary" onclick="removeSubItem2(this)">Remove Sub</a></div>     ';










   function addMainExtra(e) {
      $(e).parents('.col-main').append(htmlt);
      $(e).parents('.input-group').find('.btn-box').html('<button onclick="removeMainItem(this)" type="button" id="addRowButton" class="btn border text-center bg-white"><i class="fa fa-minus"></i></button>');


   }






   var count = 1;
   var sub_item_id = <?= @$sub_item_id > 0 ? $sub_item_id : 0; ?>;
   var subs = <?= json_encode(@$subs); ?>;

   ////main Sub///
   ////First Level Child - 1
   function addSubItemMainChild(e) {
      $('.main-items-input').addClass('d-none');
      var htmlt_temp = '<div class="input-group mb-3 mt-3 cs-sub-item" id="cs-sub-item' + sub_item_id + '"> <input id="Name" type="text" name="sub_name[' + sub_item_id + ']" class="form-control" placeholder="Name"> <select class="form-control sub-select-type" name="sub_type[' + sub_item_id + ']" id="formGroupDefaultSelect" style="height: 41px;">  <option value="">Value Type</option> <?= $type_ar_str ?> </select> <input id="Name" name="sub_value[' + sub_item_id + ']" type="number" step="any" class="form-control sub-price-box" placeholder="Value"><input type="hidden" name="sub_id[' + sub_item_id + ']"  value="0"> <span class="btn-box"> <button type="button" onclick="addSubItemSiblink(this)" id="addRowButton2" class="btn border text-center bg-white"><i class="fa fa-plus"></i></button></span> <div   class=" d-block mt-2 w-100 small-btn2" id="mast-div-sub-' + sub_item_id + '"><a style="cursor: pointer;" class="text-primary" id="cs-sub-btn-sub' + sub_item_id + '" onclick="addSubItemSub1(this)">Add Sub</a></div>     ';
      $(e).parents('.input-group').append('<div style=" margin-left:15px" class="w-100 sub-wr mt-2">' + htmlt_temp + '</div>');
      $('#small-btn-main').html('<a style="cursor: pointer;" class="text-primary"  onclick="removeSubItemsMainChild(this)">Remove Sub</a>');
      subs.push({
         count: 0
      });
      sub_item_id++;
   }


   ////Sub Level Siblinks
   function addSubItemSiblink(e) {
      var htmlt3_temp = '<div class="input-group mb-3 mt-3 cs-sub-item" id="cs-sub-item' + sub_item_id + '"> <input id="Name" type="text" name="sub_name[' + sub_item_id + ']" class="form-control" placeholder="Name"> <select class="form-control sub-select-type" name="sub_type[' + sub_item_id + ']" id="formGroupDefaultSelect" style="height: 41px;"> <option value="">Value Type</option> <?= $type_ar_str ?> </select> <input id="Name" type="number" step="any" name="sub_value[' + sub_item_id + ']" class="form-control sub-price-box" placeholder="Value"><input type="hidden" name="sub_id[' + sub_item_id + ']"  value="0"> <span class="btn-box"> <button type="button" onclick="addSubItemSiblink(this)" id="addRowButton" class="btn border text-center bg-white"><i class="fa fa-plus"></i></button></span> <div   class=" d-block mt-2 w-100 small-btn2" id="mast-div-sub-' + sub_item_id + '"><a style="cursor: pointer;" class="text-primary linit mr-3" id="cs-sub-btn-sub' + sub_item_id + '" onclick="addSubItemSub1(this)">Add Sub</a>   <a style="cursor: pointer;" class="text-primary" onclick="removeSubItemMain(this)">Remove Sub</a></div>';
      $(e).html('<i class="fa fa-minus"></i>');
      $(e).attr('onclick', 'removeSubItemMain(this)');
      $(e).parents('.sub-wr').append('<div class="mt-2">' + htmlt3_temp + '</div>');
      subs.push({
         count: 0
      });
      sub_item_id++;
   }


   //Sub Level 1 Items remove Main
   function removeSubItemsMainChild(e) {
      $('.main-items-input').removeClass('d-none');
      $(e).parents('.input-group').find('.sub-wr').removeClass('p-3');
      $(e).parents('.input-group').find('.sub-wr').remove();
      $('#small-btn-main').html('<a style="cursor: pointer;" class="text-primary"  onclick="addSubItemMainChild(this)">Add Sub</a>');
   }


   //Sub Level 1 Item remove Main
   function removeSubItemMain(e) {
      if ($('.cs-sub-item').length == 1) {
         $('.main-items-input').removeClass('d-none');
         $(e).parents('.input-group').find('.sub-wr').removeClass('p-3');
         $(e).parents('.input-group').find('.sub-wr').remove();
         $('#small-btn-main').html('<a style="cursor: pointer;" class="text-primary"  onclick="addSubItemMainChild(this)">Add Sub</a>');
      } else {
         $(e).parent().parent().remove();
      }
   }


   //Sub Level Item remove

   function removeSubItem2(e) {
      $(e).parent().parent().parent().remove();
      var htmlt3_temp = '<div class="input-group mb-3 mt-3 cs-sub-item" id="cs-sub-item' + sub_item_id + '"> <input id="Name" type="text" name="sub_name[' + sub_item_id + ']" class="form-control" placeholder="Name"> <select class="form-control select-type" name="sub_type[' + sub_item_id + ']" id="formGroupDefaultSelect" style="height: 41px;"> <option value="">Value Type</option><option value="Percentage">Percentage</option><option value="Flat">Flat</option> </select> <input id="Name" type="text" name="sub_value[' + sub_item_id + ']" class="form-control price-box" placeholder="Value"> <span class="btn-box"> <button type="button" onclick="addSubItemSiblink(this)" id="addRowButton" class="btn border text-center bg-white"><i class="fa fa-plus"></i></button></span> <div   class=" d-block mt-2 w-100 small-btn2"><a style="cursor: pointer;" class="text-primary linit mr-3" id="cs-sub-btn-sub' + sub_item_id + '" onclick="addSubItemSub1(this)">Add Sub</a>   <a style="cursor: pointer;" class="text-primary" onclick="removeSubItemMain(this)">Remove Sub</a></div>';

   }

   ////Sub Sub 1///
   function addSubItemSub1(e) {
      var sub_item_id = $(e).attr('id').split("cs-sub-btn-sub")[1];
      if (typeof subs[sub_item_id]["count"] != 'undefined') {
         var sub_sub_id = subs[sub_item_id]["count"];
         if (typeof subs[sub_item_id]["sub_sub"] == "undefined") {
            subs[sub_item_id]["sub_sub"] = [];
         }

      } else {
         subs[sub_item_id]["count"] = 0;
         subs[sub_item_id]["sub_sub"] = [];
         var sub_sub_id = 0;
      }
      subs[sub_item_id]["sub_sub"][sub_sub_id] = 0;
      var sub_sub_item_id = sub_item_id.toString() + "-" + sub_sub_id.toString();
      var htmlt_temp = '<div class="input-group mb-3 mt-3" id="cs-sub-sub-item-' + sub_sub_item_id + '"> <input id="Name" type="text" name="sub_sub_name[' + sub_item_id + '][' + sub_sub_id + ']" class="form-control" placeholder="Name"> <select class="form-control sub-sub-select-type" name="sub_sub_type[' + sub_item_id + '][' + sub_sub_id + ']" id="formGroupDefaultSelect" style="height: 41px;">  <option value="">Value Type</option> <?= $type_ar_str ?> </select> <input id="Name" name="sub_sub_value[' + sub_item_id + '][' + sub_sub_id + ']" type="number" step="any" class="form-control sub-sub-price-box" placeholder="Value"> <input type="hidden" name="sub_sub_id[' + sub_item_id + '][' + sub_sub_id + ']"  value="0"> <span class="btn-box" id="cs-sub-spn-sub-sub-' + sub_sub_item_id + '"> <button type="button" onclick="addSubItemSub1Siblink(this)" id="addRowButton2" class="btn border text-center bg-white"><i class="fa fa-plus"></i></button></span> <div   class=" d-block mt-2 w-100 small-btn2" id="mast-div-sub-sub-' + sub_sub_item_id + '"><a style="cursor: pointer;" class="text-primary" id="cs-sub-btn-sub-sub-' + sub_sub_item_id + '" onclick="addSubItemSub2(this)">Add Sub</a></div>';
      $(e).parent().parent().append('<div style="margin-left:25px" class="w-100 sub-wr p-3 mt-2 ddf cs-sub-sub-item">' + htmlt_temp + '</div>');
      $(e).parent().parent().find('.sub-price-box').addClass('d-none');
      $(e).parent().parent().find('.sub-select-type').addClass('d-none');
      $('#mast-div-sub-' + sub_item_id).html('<a style="cursor: pointer;" class="text-primary"  onclick="removeSubItemsSub1(this)">Remove Sub</a>');
      subs[sub_item_id]["count"] = subs[sub_item_id]["count"] + 1;
   }

   function addSubItemSub1Siblink(e) {
      var temp = $(e).parent().attr('id').split("cs-sub-spn-sub-sub-")[1];
      var sub_item_id = temp.split('-')[0];
      if (typeof subs[sub_item_id]["count"] != 'undefined') {
         var sub_sub_id = subs[sub_item_id]["count"];
         subs[sub_item_id]["sub_sub"].push(0);
         if (typeof subs[sub_item_id]["sub_sub"] == "undefined") {
            subs[sub_item_id]["sub_sub"] = [];
         }
      } else {
         subs[sub_item_id]["count"] = 0;
         subs[sub_item_id]["sub_sub"] = [];
         var sub_sub_id = 0;
      }
      subs[sub_item_id]["sub_sub"][sub_sub_id] = 0;
      var sub_sub_item_id = sub_item_id.toString() + "-" + sub_sub_id.toString();

      var htmlt_temp = '<div class="input-group mb-3 mt-3" id="cs-sub-sub-item-' + sub_sub_item_id + '"> <input id="Name" type="text" name="sub_sub_name[' + sub_item_id + '][' + sub_sub_id + ']" class="form-control" placeholder="Name"> <select class="form-control sub-sub-select-type" name="sub_sub_type[' + sub_item_id + '][' + sub_sub_id + ']" id="formGroupDefaultSelect" style="height: 41px;">  <option value="">Value Type</option> <?= $type_ar_str ?> </select> <input id="Name" name="sub_sub_value[' + sub_item_id + '][' + sub_sub_id + ']" type="number" step="any" class="form-control sub-sub-price-box" placeholder="Value"><input type="hidden" name="sub_sub_id[' + sub_item_id + '][' + sub_sub_id + ']"  value="0"> <span class="btn-box" id="cs-sub-spn-sub-sub-' + sub_sub_item_id + '"> <button type="button" onclick="addSubItemSub1Siblink(this)" id="addRowButton2" class="btn border text-center bg-white"><i class="fa fa-plus"></i></button></span> <div   class=" d-block mt-2 w-100 small-btn2" id="mast-div-sub-sub-' + sub_sub_item_id + '"><a style="cursor: pointer;" class="text-primary" id="cs-sub-btn-sub-sub-' + sub_sub_item_id + '" onclick="addSubItemSub2(this)">Add Sub</a></div>     ';
      $(e).parent().parent().parent().parent().append('<div style="margin-left:25px" class="w-100 sub-wr p-3 mt-2 ddf cs-sub-sub-item">' + htmlt_temp + '</div>');
      $(e).parent().parent().parent().parent().find('.sub-price-box').addClass('d-none');
      $(e).parent().parent().parent().parent().find('.sub-select-type').addClass('d-none');
      $(e).html('<i class="fa fa-minus"></i>');
      $(e).attr('onclick', 'removeSubItemSub1(this)');
      subs[sub_item_id]["count"] = subs[sub_item_id]["count"] + 1;
   }

   ///remove Sub sub items sub   
   function removeSubItemsSub1(e) {
      $(e).parent().parent().find('.cs-sub-sub-item').remove();
      var sub_item_id = $(e).parent().attr('id').split('mast-div-sub-')[1];
      $(e).parent().html('<a style="cursor: pointer;" class="text-primary linit mr-3" id="cs-sub-btn-sub' + sub_item_id + '" onclick="addSubItemSub1(this)">Add Sub</a>');
   }

   function removeSubItemSub1(e) {
      $(e).parent().parent().parent().remove();

   }



   ////////////   

   ////Sub Sub Sub Items
   function addSubItemSub2(e) {
      var sub_item = $(e).attr('id').split('cs-sub-btn-sub-sub-')[1];
      console.log(sub_item);
      var sub_item_id = sub_item.split('-')[0];
      var sub_sub_id = sub_item.split('-')[1];
      var sub_sub_sub_id = subs[sub_item_id]["sub_sub"][sub_sub_id];
      var sub_sub_item_id = sub_item_id + "-" + sub_sub_id + "-" + sub_sub_sub_id;
      subs[sub_item_id]["sub_sub"][sub_sub_id] = subs[sub_item_id]["sub_sub"][sub_sub_id] + 1;
      $(e).parent().parent().find(".sub-sub-select-type").addClass('d-none');
      $(e).parent().parent().find(".sub-sub-price-box").addClass('d-none');
      var htmlt_temp = '<div class="input-group mb-3 mt-3" id="cs-sub-sub-sub-item-' + sub_sub_item_id + '"> <input id="Name" type="text" name="sub_sub_sub_name[' + sub_item_id + '][' + sub_sub_id + '][' + sub_sub_sub_id + ']" class="form-control" placeholder="Name"> <select class="form-control sub-sub-sub-select-type" name="sub_sub_sub_type[' + sub_item_id + '][' + sub_sub_id + '][' + sub_sub_sub_id + ']" id="formGroupDefaultSelect" style="height: 41px;">  <option value="">Value Type</option> <?= $type_ar_str ?> </select> <input id="Name" name="sub_sub_sub_value[' + sub_item_id + '][' + sub_sub_id + '][' + sub_sub_sub_id + ']" type="number" step="any" class="form-control sub-sub-sub-price-box" placeholder="Value"> <input name="sub_sub_sub_id[' + sub_item_id + '][' + sub_sub_id + '][' + sub_sub_sub_id + ']" type="hidden" value="0" > <span class="btn-box" id="cs-sub-spn-sub-sub-sub-' + sub_sub_item_id + '"> <button type="button" onclick="addSubItemSub2Siblink(this)" id="addRowButton2" class="btn border text-center bg-white"><i class="fa fa-plus"></i></button></span> <div   class=" d-block mt-2 w-100 small-btn2" id="mast-div-sub-sub-sub-' + sub_sub_item_id + '"></div>     ';
      $(e).parent().parent().append('<div style="margin-left:40px" class="w-100 sub-wr p-3 mt-2 ddf cs-sub-sub-sub-item">' + htmlt_temp + '</div>');
      $(e).parent().html('<a style="cursor: pointer;" class="text-primary"  onclick="removeSubItemsSub2(this)">Remove Sub</a>');
      $(e).attr('onclick', 'removeSubItemSub2(this)');
   }

   function addSubItemSub2Siblink(e) {
      console.log($(e).parent().attr('id'));
      var sub_item = $(e).parent().attr('id').split('cs-sub-spn-sub-sub-sub-')[1];
      var sub_item_id = sub_item.split('-')[0];
      var sub_sub_id = sub_item.split('-')[1];
      var sub_sub_sub_id = subs[sub_item_id]["sub_sub"][sub_sub_id];
      var sub_sub_item_id = sub_item_id + "-" + sub_sub_id + "-" + sub_sub_sub_id;
      subs[sub_item_id]["sub_sub"][sub_sub_id] = subs[sub_item_id]["sub_sub"][sub_sub_id] + 1;
      var htmlt_temp = '<div class="input-group mb-3 mt-3" id="cs-sub-sub-sub-item-' + sub_sub_item_id + '"> <input id="Name" type="text" name="sub_sub_sub_name[' + sub_item_id + '][' + sub_sub_id + '][' + sub_sub_sub_id + ']" class="form-control" placeholder="Name"> <select class="form-control sub-sub-sub-select-type" name="sub_sub_sub_type[' + sub_item_id + '][' + sub_sub_id + '][' + sub_sub_sub_id + ']" id="formGroupDefaultSelect" style="height: 41px;">  <option value="">Value Type</option><?= $type_ar_str ?> </select> <input id="Name" name="sub_sub_sub_value[' + sub_item_id + '][' + sub_sub_id + '][' + sub_sub_sub_id + ']" type="number" step="any" class="form-control sub-sub-sub-price-box" placeholder="Value"> <input name="sub_sub_sub_id[' + sub_item_id + '][' + sub_sub_id + '][' + sub_sub_sub_id + ']" type="hidden" value="0" > <span class="btn-box" id="cs-sub-spn-sub-sub-sub-' + sub_sub_item_id + '"> <button type="button" onclick="addSubItemSub2Siblink(this)" id="addRowButton2" class="btn border text-center bg-white"><i class="fa fa-plus"></i></button></span> <div   class=" d-block mt-2 w-100 small-btn2" id="mast-div-sub-sub-sub-' + sub_sub_item_id + '"></div>     ';
      $(e).parent().parent().parent().parent().append('<div style="margin-left:40px" class="w-100 sub-wr p-3 mt-2 ddf cs-sub-sub-item">' + htmlt_temp + '</div>');
      $(e).html('<i class="fa fa-minus"></i>');
      $(e).attr('onclick', 'removeSubItemSub1(this)');
   }

   function removeSubItemsSub2(e) {
      //console.log($(e).parent().attr('id'));
      var sub_item = $(e).parent().attr('id').split('mast-div-sub-sub-')[1];
      //console.log(sub_item);
      // var sub_item_id=sub_item.split('-')[0];
      //var sub_sub_id=sub_item.split('-')[1];
      $(e).parent().parent().find('.cs-sub-sub-sub-item').remove();
      $(e).parent().html('<a style="cursor: pointer;" class="text-primary linit mr-3" id="cs-sub-btn-sub-sub-' + sub_item + '" onclick="addSubItemSub2(this)">Add Sub</a>');
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



   function removeMainItem(e) {
      $(e).parents('.input-group').removeClass('mb-3 mt-3');
      $(e).parents('.input-group').html('');

   }


   function removeSubItem(e) {
      $(e).parents('.input-group').find('.price-box').removeClass('d-none');
      $(e).parents('.input-group').find('.select-type').removeClass('d-none');

      $(e).parents('.input-group').find('.sub-wr').removeClass('p-3');
      $(e).parents('.input-group').find('.sub-wr').remove();

      $(e).parents('.input-group').find('.small-btn').html('<a style="cursor: pointer;" class="text-primary"  onclick="addSubItem(this)">Add Sub</a>');
   }


   function removeExtra2(e) {

      $(e).parent().parent().remove();
   }

   $('#parent_extra').on('change', function() {
      var parentID = $(this).val();
      if (parentID) {
         $.ajax({
            type: 'POST',
            url: '<?= base_url("admin/extras/get_children") ?>',
            data: {
               parent_id: parentID
            },
            dataType: 'json',
            success: function(data) {
               $('#child_extra').html('<option value="">Select Child</option>');
               $.each(data, function(i, val) {
                  $('#child_extra').append('<option value="' + val.id + '">' + val.name + '</option>');
               });
            }
         });
      } else {
         $('#child_extra').html('<option value="">Select Child</option>');
      }
   });
   $('#import_extras').on('submit', function(e) {
      e.preventDefault();
      var formData = new FormData(this);

      $.ajax({
         url: '<?php echo base_url("admin/extras/matrix_import"); ?>',
         type: 'POST',
         data: formData,
         contentType: false,
         processData: false,
         dataType: 'json',
         success: function(response) {
            if (response.status === 'success') {
               $('#priceband-result').html('<div class="alert alert-success">' + response.message + '</div>');
            } else {
               $('#priceband-result').html('<div class="alert alert-danger">' + response.message + '</div>');
            }
         },
         error: function() {
            $('#priceband-result').html('<div class="alert alert-danger">An error occurred while importing data.</div>');
         }
      });
   });
   $('#toggle_form').click(function() {
      $('#import_extras').slideToggle('fast');
      $('#toggle_icon').toggleClass('fa-chevron-down fa-chevron-up');
   });
   $('.sub-type').change(function() {
      let value = $(this).val();
      if (value == 'Flat Value Width and Height Dependent') {

      } else {
         alert(3)
      }


   });
   $(document).on('click', '.add-type-value', function() {
      const wrapper = $(this).closest('.type-value-wrapper');
      const subId = $(this).data('sub-id');


      const typeOptions = `<?php foreach ($type_ar as $t) {
                              echo "<option value='{$t}'>{$t}</option>";
                           } ?>`;

      const newRow = `
      <div class="d-flex type-value-row mb-2 align-items-center">
         <select class="form-control me-2 " name="sub_type[${subId}][]">
            <option value="">Value Types</option>
            ${typeOptions}
         </select>

         <input type="number" name="sub_value[${subId}][]" class="form-control sub-price-box" placeholder="Value">
         <button type="button" class="btn btn-danger ms-2 remove-type-value" style="margin-left:10px">×</button>
      </div>
    `;
      wrapper.append(newRow);
   });

   $(document).on('click', '.remove-type-value', function() {
      $(this).closest('.type-value-row').remove();
   });


   $(document).on('click', '.remove-type-value', function() {
      $(this).closest('.type-value-row').remove();
   });

   $('#search').on('keyup', function() {
      var name= $(this).val().toLowerCase();
      if(name.length > 0) {
         $('#pagination').hide();
       $.ajax({
            url: '<?= base_url('admin/extras/search_extra_by_name') ?>',
            type: 'POST',
            data: { name: name },
            dataType: 'json',
            success: function (data) {
               

                  let html = '';
                let i = 1;

                if (data.length > 0) {
                    $.each(data, function (index, pro) {
                        html += `<tr>
                            <td>${i++}</td>
                            <td>${pro.name}</td>
                            <td>`;

                        // Add button only if permission is granted (simulate from PHP)
                        <?php if (@$permissions['add']) { ?>
                        html += `<a href="<?= site_url('admin/' . $controller . '/list/edit/') ?>${pro.id}" class="text-muted d-inline-block bg-white mr-1" data-toggle="tooltip" title="Edit">
                            <i class="fa fa-edit"></i>
                        </a>`;
                        <?php } ?>

                        <?php if (@$permissions['delete']) { ?>
                        html += `<a href="<?= site_url('admin/' . $controller . '/delete/') ?>${pro.id}" onclick="return confirm('<?= $this->lang->line('common_confirm_delete') ?>');" class="text-muted d-inline-block bg-white" data-toggle="tooltip" title="Remove">
                            <i class="far fa-trash-alt"></i>
                        </a>`;
                        <?php } ?>

                        html += `</td></tr>`;
                    });
                } else {
                    html = `<tr><td colspan="3">No matching results found.</td></tr>`;
                }

                $('#tabledata').html(html);
            }
            
         });
      }
      else
      {
        location
      }
   });
  

</script>