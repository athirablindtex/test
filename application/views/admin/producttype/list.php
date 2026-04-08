<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<style>
   .select2-selection--multiple {
      overflow: hidden !important;
      height: auto !important;
   }
</style>
<?php $company_id = $this->input->get('company_id'); ?>
<?php
$selected = false;
$margin = 0;
$mandatory = 0;
?>
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
                        <h4 class="card-title">Master - <?= @$page; ?></h4>
                              





                        <div class="btn-group btn-group-page-header ml-auto show">
                           <?php if (@$permissions['add']) { ?>
                              <button class="btn btn-success btn-rounded btn-round ml-auto" data-toggle="modal" data-target="#productTypeModal">
                                 <i class="fa fa-plus"></i>
                                 Add New <?= @$page; ?>
                              </button>
                           <?php } ?>



                        </div>





                     </div>
                  </div>
                  <div class="card-body">

                  <div class="col-md-12">
                  <div class="form-group form-group-default">
                  <label>Select Company</label>
                  <select name="company_id" class="form-control" id="company_id" required>
                  <option value="">Select Company</option>

                  <?php foreach ($companies as $c) { ?>
                  <option value="<?= $c->id ?>"
                  <?= @$this->input->get('company_id') == $c->id ? 'selected' : '' ?>>
                  <?= $c->name ?>
                  </option>
                  <?php } ?>

                  </select>
                  </div>
                  </div>

                     <?php if (@$tabledata) { ?>
                        <div class="table-responsive mt-4">
                           <table id="basic-datatables" class="display table table-striped table-hover">
                              <thead>

                                 <tr>
                                    <th>ID</th>
                                    <th>Name</th>

                                    <th style="width: 70px">Action</th>
                                 </tr>
                              </thead>

                              <tbody>
                                 <?php
                                 $i = 1;
                                 foreach ($tabledata as $product) {
                                 ?>
                                    <tr>
                                       <td><?php echo @$product->id; ?></td>
                                       <td><?php echo @$product->name; ?></td>

                                       <td>
                                          <div class="form-button-action">
                                             <?php if (@$permissions['add']) { ?>
                                               <a href="<?php echo site_url('admin/' . $controller . '/list/edit/' . @$product->$module_id . '?company_id=' . $company_id); ?>"
   class="btn btn-link btn-primary btn-lg">
   <i class="fa fa-edit"></i>
</a>
                                             <?php } ?>
                                             <?php if (@$permissions['add']) { ?>
                                                <a href="<?php echo site_url('admin/' . $controller . '/delete/' . @$product->$module_id . '?company_id=' . $company_id); ?>"
   onclick="return confirm('<?php echo $this->lang->line('common_confirm_delete'); ?>');"
   class="btn btn-link btn-danger">
   <i class="fa fa-times"></i>
</a>
                                             <?php } ?>
                                          </div>
                                       </td>
                                    </tr>
                                 <?php } ?>

                              </tbody>
                           </table>

                        </div>
                     <?php } else { ?>
                        <div class="alert alert-danger" role="alert">
                           <strong></strong> <?php echo $this->lang->line('common_no_data'); ?>
                        </div>
                     <?php } ?>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<!-- Model 	-->
<div class="modal fade" id="productTypeModal" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header no-bd">
            <h5 class="modal-title">
               <span class="fw-mediumbold">
                  <?= @$page; ?>
               </span>
            </h5>
            <button type="button" class="close close-model" aria-label="Close">
               <span aria-hidden="true">×</span>
            </button>
         </div>
         <div class="modal-body">
            <form action="" method="post" id="fm_form_data">
               <div class="row">
                  <?php if (@validation_errors()) { ?>
                     <div class="col-md-12">
                        <div class="alert alert-danger" role="alert">
                           <strong>Form Insert Failed</strong> <?php echo @validation_errors(); ?>
                        </div>
                     </div>
                  <?php } ?>
                   <div class="col-md-12">
                  <div class="form-group form-group-default">
                  <label>Select Company</label>
                  <select name="company" class="form-control"  id="company" required>
                  <option value="">Select Company</option>

                  <?php foreach ($companies as $c) { ?>
                  <option value="<?= $c->id ?>"
                  <?= @$res->company_id == $c->id ? 'selected' : '' ?>>
                  <?= $c->name ?>
                  </option>
                  <?php } ?>

                  </select>
                  </div>
                  </div>
          
                  <div class="col-md-12">
                     <div class="form-group form-group-default">
                        <label>Names</label>
                        <input id="addPosition" type="text" class="form-control" name="name" value="<?php echo @$res->name != '' ? @$res->name : $this->input->post('name'); ?>" required>
                     </div>
                  </div>
                  <div class="col-md-12">
                     <div class="form-group form-group-default">
                        <label>Extra</label>

                        <select id="extras" class="form-control select2" multiple="multiple" name="extras[]">
                           <?php foreach ($extras as $extra) { ?>
                              <option

                                 value="<?= @$extra['id']; ?>"
                                 <?php
                                 // Check if the current extra is in the selected extras
                                 $selected = false;
                         
                                 if (isset($selected_extras)) {
                                    foreach ($selected_extras as $selected_extra) {
                                       if ($selected_extra['id'] == $extra['id']) {
                                          $selected = true;
                                          $margin = $selected_extra['margin'];
                                          $mandatory = $selected_extra['mandatory'];
                                          break;
                                       }
                                    }
                                 }
                                 ?>
                                 data-mandatory="<?= $mandatory ?>" <?= $selected ? 'selected' : ''; ?> data-margin=" <?= $margin > 0 ? $margin : 0 ?>"  class="extra_option">

                                 <?= @$extra['name']; ?>
                              </option>
                           <?php } ?>
                        </select>

                     </div>
                  </div>
                  <div class="col-md-12">

                     <table class="table table-bordered " id="extras_table">
                        <thead>
                           <tr>
                              <th>Extra</th>
                           
                              <th>Mandatory</th>
                           </tr>
                        </thead>
                        <tbody>

                        </tbody>
                     </table>
                  </div>
               </div>

         </div>
         <input type="hidden" name="id" value="<?php echo @$res->$module_id ? $res->$module_id : 0; ?>">
         <div class="modal-footer no-bd">
            <button type="submit" id="addRowButton" class="btn btn-primary">Add</button>
            <button type="button" class="btn close-model">Close</button>
         </div>

         </form>
      </div>
   </div>
</div>
<!-- Model 	-->

<script>
   <?php if (@$edit == 1 || @validation_errors()) { ?>
      $(document).ready(function() {
         $('#productTypeModal').modal('show');
         $('.close-model').click(function() {
            window.location = "<?= @$redirect; ?>";
         });
         setSelectedToTable()
      });
   <?php } else {  ?>
      $('.close-model').click(function() {
         $('#fm_form_data').trigger('reset');
         $('#productTypeModal').modal('hide');
      });
   <?php } ?>
   $(document).ready(function() {
      $('#basic-datatables').DataTable({
         "order": [
            [0, "desc"]
         ]
      });




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

   $('.select2').select2({
      allowClear: true,
      minimumResultsForSearch: -1,
      width: '100%'
   });


   $('#extras').change(function() {
      setSelectedToTable()
   })

   function setSelectedToTable() {
      let selectedData = getSelectedValue();
      $('#extras_table tbody').html(setExtrasTable(selectedData))
   }

   const setExtrasTable = (data) => {
      console.log(data);
      let tr = '';

      $.each(data, function(i, d) {

         tr += `<tr>
                              <td>
                                 ${d.text}
                                 <input type="hidden" name="extras[${i}][extra]" value="${d.value}">
                                 <input type="hidden" name="extras[${i}][margin]" value="${d.margin}">
                              </td>

                              <td>
                                 <div class="form-check p-0">
                                    <label class="form-check-label m-0">
                                    
                                       <input class="form-check-input" style="margin-left: -1.25rem !important;" type="checkbox" name="extras[${i}][mandatory]" ${d.mandatory==1 ?'checked':''} value="1">
                                       <span class="form-check-sign">Mandatory</span>
                                    </label>
                                 </div>
                              </td>
                           </tr>`
      });
      return tr;
   }

   const getSelectedValue = () => {
      var selected = [];
      $('#extras :selected').each(function() {
         selected.push({
            value: $(this).val(),
            text: $(this).text(),
            margin: $(this).data('margin') ? $(this).data('margin') : false,

            mandatory: $(this).data('mandatory') == 1 ? 1 : 0,
         });
      });
      return selected;
   }
   $(document).on('click', '.edit-margin', function() {
      $(this).hide(); // hide the link
      $(this).siblings('.margin-show').removeClass('d-none').focus();
   });
    $('#extras').on('select2:unselect', function (e) {
    let removedId = e.params.data.id;
let product_type = <?= !empty($res->module_id) ? (int)$res->module_id : 0 ?>;
    $.ajax({
      url: "<?= base_url('admin/' . $controller . '/remove_extra_margin') ?>",

        type: 'POST',
        data: { extra_id: removedId ,product_type_id:product_type},
        success: function(response) {
            console.log('Extra margin removed successfully');
        },
        error: function(xhr, status, error) {
            console.error('Error removing extra margin:', error);
        }
    });
    


  
});

$('#company_id').on('change', function () {
   let company_id = $(this).val();
   window.location.href = "<?= site_url('admin/producttype/list') ?>?company_id=" + company_id;

});
$('#company').on('change', function () {
   let company_id = $(this).val();

   // keep modal open after reload
   window.location.href = "<?= site_url('admin/producttype/list') ?>?company_id=" + company_id + "&open_modal=1";
});
</script>