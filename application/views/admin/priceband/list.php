<?php $band_type = array('Square Meter', 'Meter', 'Matrix'); ?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/handsontable@14.3.0/dist/handsontable.full.min.css">

<script src="https://cdn.jsdelivr.net/npm/handsontable@14.3.0/dist/handsontable.full.min.js"></script>
<style>
   #matrix {
      width: 100%;
      height: 600px;
      overflow: auto;
   }
</style>

<div class="main-panel">
   <div class="content">
      <div class="page-inner">
         <div class="page-header">
            <h4 class="page-title"><?= @$page; ?></h4>
            <div class="btn-group btn-group-page-header ml-auto">
               <?php if (@$permissions['add']) { ?>
                  <button type="button" class="btn btn-success btn-rounded <?= @$edit == 0 ? 'collapsed' : ((@validation_errors() ? '' : 'collapsed')); ?>" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                     <i class="fa fa-plus mr-2"></i> New <?= @$page; ?>
                  </button>
               <?php } ?>
            </div>
         </div>
         <div class="row">
            <div class="col-md-12">
               <div class="collapse <?= @$edit == 0 ? (@validation_errors() ? 'show' : '') : 'show'; ?>" id="collapseExample" style="">
                  <div class="card">
                     <div class="card-header">
                        <div class="row">
                           <div class="col-6 d-flex align-items-center justify-content-left">
                              <div class="card-title">New <?= @$page; ?>
                              </div>
                           </div>

                           <!-- <div class="col-6 d-flex align-items-center justify-content-end">
                              <div class="clearfix border">
                                 <h3>Import Price Matrix</h3>
                                 <form id="import_priceband" method="post" enctype="multipart/form-data">
                                    <div class="form-row">
                                       <div class="col" style="padding-left: 20px;">
                                          <select class="form-control select2" id="formGroupDefaultSelect" name="type">
                                             <option value="">PriceBand name</option>
                                             <?php foreach ($priceBandnames as $b) { ?>
                                                <option value="<?= $b['id']; ?>"><?= $b['name']; ?></option>
                                             <?php } ?>
                                          </select>
                                       </div>

                                       <div class="col">
                                          <div class="input-file">
                                             <input type="file" class="form-control form-control-file" id="upload_file" name="upload_file">
                                             <label for="upload_file" class="btn bg-white border-0" style="
                             color: #000000 !important;">
                                                <i class="fa fa-upload mr-2"></i>Import File
                                             </label>
                                          </div>
                                       </div>

                                       <div class="col-auto">
                                          <button type="submit" class="btn btn-primary" id="import_submit">
                                             <i class="fa fa-paper-plane mr-1"></i> Submit
                                          </button>
                                          <a href="https://staging.tradeblindsdirect.com/blindtex-app/assets/templates/Matrix_template_priceband.xls" target="_blank">Download Template </a>
                                       </div>
                                    </div>
                                 </form>
                                 <div id="priceband-result"></div>

                              </div>
                           </div> -->
                        </div>
                     </div>
                     <div class="card-body">
                        <form method="post" id="form1">
                           <div class="row">
                              <?php if (@validation_errors()) { ?>
                                 <div class="col-md-12">
                                    <div class="alert alert-danger" role="alert">
                                       <?php echo @validation_errors(); ?>
                                    </div>
                                 </div>
                              <?php } ?>

                              <div class="col-md-12">
                                 <div class="form-group form-group-default">
                                    <label>Select Company</label>
                                    <select name="company" class="form-control company_id" id="company_id" required>
                                       <option value="">Select Company</option>

                                       <?php foreach ($companies as $c) { ?>
                                          <option value="<?= $c->id ?>"
                                             <?= $res->company_id == $c->id ? 'selected' : '' ?>>
                                             <?= $c->name ?>
                                          </option>
                                       <?php } ?>

                                    </select>
                                 </div>
                              </div>
                              <div class="col-md-3">
                                 <div class="form-group form-group-default">
                                    <label>Version </label>
                                    <select class="form-control" id="priceband_version" name="priceband_version" required>
                                       <option value="">Version</option>
                                       <?php foreach ($priceband as $p) {  ?>
                                          <option value="<?= $p->id; ?>" <?php if ($p->id == @$res->priceband_version) {
                                                                              echo "selected";
                                                                           } else if ($this->input->post('priceband_version') == $p->id) {
                                                                              echo "selected";
                                                                           } ?>><?= $p->name; ?></option>
                                       <?php } ?>
                                    </select>
                                 </div>
                              </div>
                              <div class="col-md-3">
                                 <div class="form-group form-group-default">
                                    <label>PRICE BAND TYPE</label>
                                    <select class="form-control" id="priceBand" name="type" required>
                                       <option value="">Type</option>
                                       <?php foreach ($band_type as $b) { ?>
                                          <option value="<?= $b; ?>" <?php if ($b == @$res->type) {
                                                                        echo "selected";
                                                                     } ?>><?= $b; ?></option>
                                       <?php } ?>


                                    </select>
                                 </div>
                              </div>
                              <div class="col-md-3">
                                 <div class="form-group form-group-default">
                                    <label>PRODUCT TYPE</label>
                                    <select class="form-control" id="producType" name="ptype" required>
                                       <option value="">Product Type</option>
                                       <?php foreach ($product_types as $p) { ?>
                                          <option value="<?= $p->id; ?>" <?php if ($p->id == @$res->product_type) {
                                                                              echo "selected";
                                                                           } ?>><?= $p->name; ?></option>
                                       <?php } ?>


                                    </select>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-group form-group-default">
                                    <label>Name</label>
                                    <input type="text" name="name" value="<?php echo @$res->name != '' ? @$res->name : $this->input->post('name'); ?>" class="form-control rounded-0" placeholder="" aria-label="Username" aria-describedby="basic-addon1" required>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-12 default-form form-type <?= (@$res->type == 'Square Meter' || @$res->type == 'Meter') ? '' : 'd-none'; ?>" id="type1">
                                 <div class="row">
                                    <div class="col-md-6">
                                       <div class="form-group form-group-default">
                                          <label>Unit Price</label>
                                          <input id="Name" name="unit_price" value="<?php echo @$res->unit_price != '' ? @$res->unit_price : $this->input->post('unit_price'); ?>" type="number" step="any" class="form-control form-type1" placeholder="Unit Price" <?= (@$res->type == 'Square Meter' || @$res->type == 'Meter') ? 'required' : ''; ?>>
                                       </div>
                                    </div>
                                    <div class="col-md-6">
                                       <div class="form-group form-group-default" id="meter_div">
                                          <label> <?= (@$res->type == 'Square Meter') ? 'Min. Sqr Meter' : 'Min. Meter'; ?></label>
                                          <input id="Name" name="min_unit" step="any" value="<?php echo @$res->min_unit != '' ? @$res->min_unit : $this->input->post('min_unit'); ?>" type="number" class="form-control form-type1" placeholder="Min. Sqr Meter" <?= (@$res->type == 'Square Meter' || @$res->type == 'Meter') ? 'required' : ''; ?>>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-md-12 new-form form-type <?= (@$res->type == 'Matrix') ? '' : 'd-none'; ?>" id="type2">

                                 <div class="row" style="margin-top:25px">
                                    <div id="matrix"></div>
                                    <input type="hidden" name="matrix_data" id="matrix_data">



                                 </div>

                              </div>
                              <input type="hidden" name="id" value="<?php echo @$res->$module_id ? $res->$module_id : 0; ?>">
                              <div class="col-md-12 clearfix mt-3 mb-3  d-flex align-items-center justify-content-center justify-content-lg-center">
                                 <button type="submit" id="addRowButton" class="btn btn-success btn-rounded mx-auto">Add Price Band</button>
                              </div>
                        </form>
                     </div>
                  </div>
               </div>
            </div>
            <div class="card">
               <div class="card-body">
                  <form>
                     <div class="row">


                        <div class="col-md-12">
                           <div class="form-group form-group-default">
                              <label>Select Company</label>
                              <select name="company" class="form-control company_id" id="company" required>
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
                        <div class="col-md-6">
                           <div class="form-group form-group-default">
                              <label>Version</label>
                              <select class="form-control" id="formGroupDefaultSelect" name="priceband_version">
                                 <option value="">Version</option>
                                 <?php foreach ($priceband as $p) {  ?>
                                    <option value="<?= $p->id; ?>" <?php if ($p->id == @$this->input->get('priceband_version')) {
                                                                        echo "selected";
                                                                     } else if ($this->input->post('version') == $p->id) {
                                                                        echo "selected";
                                                                     } ?>><?= $p->name; ?></option>
                                 <?php } ?>
                              </select>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group form-group-default">
                              <label>Price Band Type</label>
                              <select class="form-control" id="formGroupDefaultSelect" name="type">
                                 <option value="">Type</option>
                                 <?php foreach ($band_type as $b) { ?>
                                    <option value="<?= $b; ?>" <?php if ($b == @$this->input->get('type')) {
                                                                  echo "selected";
                                                               } ?>><?= $b; ?></option>
                                 <?php } ?>
                              </select>
                           </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-12">
                           <div class="input-group mb-3">
                              <div class="input-group-prepend">
                                 <span class="input-group-text" id="basic-addon1"> <i class="fa fa-search search-icon"></i></span>
                              </div>
                              <input type="text" class="form-control rounded-0" placeholder="From" id="datetime" name="from" aria-label="Username" aria-describedby="basic-addon1" autocomplete="off"><input type="text" id="datetime2" class="form-control rounded-0" placeholder="To" aria-label="Username" name="to" aria-describedby="basic-addon1" autocomplete="off">
                              <button class="btn btn-primary rounded-0" type="submit">Search</button>
                           </div>
                           <div class="clearfix"></div>
                           <hr class="mt-4 mb-4 d-block">
                        </div>
                     </div>
                  </form>

                  <?php if (@$tabledata) { ?>
                     <div class="table-responsive">
                        <hr />
                        <table id="basic-datatables" class="display table table-striped table-hover">
                           <thead>
                              <tr>
                                 <th>ID</th>
                                 <th>Name</th>
                                 <th>Type</th>
                                 <th>Product Type</th>
                                 <th>Unit Cost</th>
                                 <th>Minimum Meter</th>

                                 <th style="width: 10%">Action</th>
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
                                    <td><?php echo @$product->type; ?></td>
                                    <td><?php echo @$product->product_type_name; ?></td>
                                    <td><?php echo @$product->unit_price; ?></td>
                                    <td><?php echo @$product->min_unit; ?></td>


                                    <td>
                                       <div class="form-button-action">
                                          <?php if (@$permissions['add']) { ?>
                                             <a href="<?php echo site_url('admin/' . $controller . '/list/edit/' . @$product->$module_id . ''); ?>" data-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-original-title="Edit Task">
                                                <i class="fa fa-edit"></i>
                                             </a>
                                          <?php } ?>
                                          <?php if (@$permissions['delete']) { ?>
                                             <a href="<?php echo site_url('admin/' . $controller . '/delete/' . @$product->$module_id . ''); ?>" onclick="return confirm('<?php echo $this->lang->line('common_confirm_delete'); ?>');" data-toggle="tooltip" title="" class="btn btn-link btn-danger" data-original-title="Remove">
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
                  <hr />

               </div>
            </div>
         </div>
      </div>
   </div>
</div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
   let price_band_id = "<?= isset($res->$module_id) ? $res->$module_id : 0 ?>";


   $(document).ready(function() {
      let hot;
      let originalData = [];
      let tableLoaded = false;
      const container = document.getElementById('matrix');


      function convertMatrixToJSON() {
         const data = hot.getData();
         if (!data || data.length < 2) return [];

         const headers = data[0].slice(1); // first row except first cell
         const result = [];

         for (let r = 1; r < data.length; r++) {
            const row = data[r];
            const height = row[0]; // first column
            for (let c = 1; c < row.length; c++) {
               const width = headers[c - 1];
               const price = row[c];
               if (width !== null && height !== null && price !== null && price !== "") {
                  result.push({
                     width,
                     height,
                     price
                  });
               }
            }
         }
         return result;
      }

      // Function to update hidden field
      function updateHiddenInput() {
         if (tableLoaded && hot) {
            const json = JSON.stringify(convertMatrixToJSON());
            $('#matrix_data').val(json);
         }
      }

      if (parseInt(price_band_id) === 0) {
         const data = Handsontable.helper.createEmptySpreadsheetData(5, 5);

         hot = new Handsontable(container, {
            data: data,
            rowHeaders: true,
            colHeaders: true,
            stretchH: 'all',
            height: 400,
            width: '100%',
            contextMenu: true,
            manualColumnResize: true,
            manualRowResize: true,
            licenseKey: 'non-commercial-and-evaluation',
            afterChange: function(changes, source) {
               if (source !== 'loadData') {
                  updateHiddenInput();
               }
            }
         });

         tableLoaded = true;
         updateHiddenInput(); // initialize
         console.log('New price band — blank matrix loaded.');
      } else {
         // Fetch matrix data from backend for existing price band
         $.get("<?= site_url('admin/priceband/get_price_matrix') ?>", {
            id: price_band_id
         }, function(res) {
            let data = JSON.parse(res);
            let heights = data.heights || [];
            let widths = data.widths || [];
            let matrix = data.matrix || [];

            let tableData = [];
            let header = ['Height ↓ / Width →']; // Bold corner label
            widths.forEach(w => header.push(w));
            tableData.push(header);

            matrix.forEach(row => {
               let temp = [row.height];
               widths.forEach(w => temp.push(row[w] || ''));
               tableData.push(temp);
            });

            originalData = JSON.parse(JSON.stringify(tableData));
            tableLoaded = true;

            let colWidths = [100];
            widths.forEach(() => colWidths.push(80));

            hot = new Handsontable(container, {
               data: tableData,
               colWidths: colWidths,
               colHeaders: true,
               rowHeaders: true,
               stretchH: 'all',
               height: 600,
               manualColumnResize: true,
               manualRowResize: true,
               fixedColumnsLeft: 1,
               readOnly: false,
               licenseKey: 'non-commercial-and-evaluation',
               copyPaste: true,
               contextMenu: true,
               afterChange: function(changes, source) {
                  if (source !== 'loadData') {
                     updateHiddenInput();
                  }
               }
            });

            updateHiddenInput();
         });
      }


      $("#saveBtn").click(function() {
         const hotData = hot.getData();
         const headers = hotData[0].slice(1); // widths (skip first cell)
         const rows = [];

         for (let i = 1; i < hotData.length; i++) {
            const dim_drop = hotData[i][0];
            for (let j = 1; j < hotData[i].length; j++) {
               const dim_width = headers[j - 1];
               const price = hotData[i][j];
               if (dim_width && dim_drop && price !== null && price !== '') {
                  rows.push({
                     dim_width: dim_width,
                     dim_drop: dim_drop,
                     price: price
                  });
               }
            }
         }

         $.ajax({
            url: "<?= base_url('priceband/update_matrix'); ?>", // your CI URL
            type: "POST",
            data: {
               price_band: price_band_id,
               matrix: JSON.stringify(rows)
            },
            beforeSend: function() {
               $("#saveBtn").prop("disabled", true).text("Saving...");
            },
            success: function(res) {
               console.log(res);
               alert("Matrix saved successfully!");
            },
            error: function(err) {
               console.error(err);
               alert("Error saving matrix!");
            },
            complete: function() {
               $("#saveBtn").prop("disabled", false).text("Save Changes");
            }
         });
      });
   });



   // Reset to original DB values



   $('#datetime').datetimepicker({
      format: 'YYYY/MM/DD',
      maxDate: 'now'
   });
   $('#datetime2').datetimepicker({
      format: 'YYYY/MM/DD',
      maxDate: 'now'
   });


   $('#datetime').datetimepicker().on('dp.change', function(e) {
      var incrementDay = moment(new Date(e.date));
      //incrementDay.add(1, 'days');
      $('#datetime2').data('DateTimePicker').minDate(incrementDay);
      $(this).data("DateTimePicker").hide();
   });

   $('#datetime2').datetimepicker().on('dp.change', function(e) {
      var decrementDay = moment(new Date(e.date));
      //decrementDay.subtract(1, 'days');
      $('#datetime').data('DateTimePicker').maxDate(decrementDay);
      $(this).data("DateTimePicker").hide();
   });





   $(document).ready(function() {
      $('#basic-datatables').DataTable({
         "order": [
            [0, "desc"]
         ]
      });



   });





   $(function() { // Makes sure the code contained doesn't run until
      //     all the DOM elements have loaded
      //$('.form-type').addClass('d-none');
      $('#priceBand').change(function() {
         //type1,type2
         $('#load_data').html('');
         $('.form-type1').prop('required', false);
         $('.form-type2').prop('required', false);
         if ($(this).val() != "") {
            $('.form-type').addClass('d-none');
            if ($(this).val() == "Matrix") {
               $('#type2').removeClass('d-none');
               $('.form-type1').prop('required', false);
               $('.form-type2').prop('required', true);
            } else if ($(this).val() == "Square Meter" || $(this).val() == "Meter") {
               $('#type1').removeClass('d-none');
               $('.form-type1').prop('required', true);
               $('.form-type2').prop('required', false);

               if ($(this).val() == "Square Meter") {
                  $('#meter_div').find("label").text('Min. Sqr Meter');
                  $('#meter_div').find("input").attr("placeholder", "Min. Sqr Meter").blur();
               } else if ($(this).val() == "Meter") {
                  $('#meter_div').find("label").text('Min. Meter');
                  $('#meter_div').find("input").attr("placeholder", "Min. Meter").blur();
               }
            }


         } else {
            $('.form-type').addClass('d-none');
         }
         //$('.form-type').addClass('d-none');
         //$('#' + $(this).val()).removeClass('d-none');
      });

   });

   var width_old = 0;
   var heigth_old = 0;

   $('#generate_values').click(function() {
      var width = $('#width_set').val();
      var height = $('#height_set').val();
      if (width != "" && height != "") {
         if (isNumber(width) && isNumber(height)) {
            if (width > 0 && height > 0) {
               //var sets=$('input[name=set_value]').val();
               //var ids=$('input[name=set_id]').val();  
               //var form_data=$('#form1').serializeArray();   
               //var dt=get_set_values_data(form_data);
               //var ids=get_set_ids_data(form_data);
               //console.log(dt);
               // var sets=
               //console.log(form_data);
               //console.log(form_data['set_value']);
               var i = 0;
               var dt_ar_index = 0;
               var h = '';
               h += '<table class="table">';
               h += '<tbody>';
               for (i = 0; i <= height; i++) {
                  h += ' <tr id="">';
                  for (j = 0; j <= width; j++) {
                     if (i == 0 && j == 0) {
                        h += ' <td scope="row">width/drop <input type="hidden" class="set_value" name="set_value[' + i + '][' + j + ']"><input class="set_id" type="hidden" name="set_id[' + i + '][' + j + ']"></td>';
                     } else {
                        var val = "";
                        var id = "";
                        /*if(dt.length>0){
                           if(typeof dt[i][j] !== 'undefined'){
                                 val=dt[i][j];
                              }
                           if(typeof ids[i][j] !== 'undefined'){
                                 id=ids[i][j];
                              } 
                           }*/
                        h += ' <td id=""><input type="number" step="any" name="set_value[' + i + '][' + j + ']" value="' + val + '" class="form-control rounded-0 set_value form_set2 form-type2" aria-label="Username" aria-describedby="basic-addon1"></td>';
                        h += ' <input class="set_id" value="' + id + '" type="hidden" name="set_id[' + i + '][' + j + ']">';
                     }
                     dt_ar_index++;
                  }
                  h += ' </tr>';
               }
               h += '</tbody>';
               h += '</table>';
               $('#load_data').html(h);
               $('.form-type2').prop('required', true);
               width_old = width;
               heigth_old = height;
            } else {
               alert("Height and Width must be positive number");
            }
         } else {
            alert("Fill Valid Height and Width");
         }
      } else {
         alert("Fill Height and Width");
      }



   });

   function isNumber(x) {
      return parseInt(x) == x
   };

   function get_set_values_data(data) {
      var cur_i = 0;
      var cur_j = 0;
      var vals = [];
      for (i = 0; i < data.length; i++) {
         if (data[i]['name'] == "set_value[][]") {
            vals.push(data[i]['value']);


         }

      }
      console.log(vals);
      return vals;
   }

   function get_set_ids_data(data) {
      var vals = [];
      for (i = 0; i < data.length; i++) {
         if (data[i]['name'] == "set_id[][]") {
            //vals.push(data[i]['value']);
         }
      }
      return vals;
   }

   $(document).ready(function() {
      $('.select2').select2({
         placeholder: 'Select a PriceBand name', // Optional: Adds a placeholder
         width: '100%' // Optional: Adjusts the width to the container
      });
      $('#import_priceband').on('submit', function(e) {
         e.preventDefault();
         var formData = new FormData(this);

         $.ajax({
            url: '<?php echo base_url("admin/priceband/import"); ?>',
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
               $('#import_submit').prop('disabled', true);
            },
            error: function() {
               $('#priceband-result').html('<div class="alert alert-danger">An error occurred while importing data.</div>');
               $('#import_submit').prop('disabled', false);
            }
         });
      });
   });
   $('body').on('change', '.company_id', function() {
      var company_id = $(this).val();
      window.location.href = '<?php echo base_url("admin/priceband/list"); ?>?company_id=' + company_id;
   });

</script>