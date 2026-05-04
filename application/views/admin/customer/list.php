<div class="main-panel">
   <div class="content">
      <div class="page-inner">
         <style>
            .table-custom {
               background: #ffffff;
               border-radius: 10px;
               overflow: hidden;
               box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            }

            .table-custom thead {
               background: #f8f9fa;
               font-weight: 600;
            }

            .table-custom thead th {
               padding: 14px;
               font-size: 14px;
               color: #444;
            }

            .table-custom tbody td {
               padding: 14px;
               vertical-align: middle;
            }

            .table-custom tbody tr:hover {
               background: #f5f7fb;
               transition: 0.3s;
            }

            .badge-company {
               background: #eef3ff;
               color: #3a6ff7;
               padding: 5px 10px;
               border-radius: 6px;
               font-size: 12px;
            }

            .badge-sales {
               background: #e8f8f1;
               color: #28a745;
               padding: 5px 10px;
               border-radius: 6px;
               font-size: 12px;
            }

            .customer-name {
               font-weight: 600;
            }

            .phone-format {
               white-space: nowrap;
            }

            .phone-format i {
               margin-right: 6px;
            }

            .address-format {
               max-width: 220px;
               word-break: break-word;
            }

            .address-format i {
               margin-right: 6px;
            }
         </style>
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
                        <h4 class="card-title"><?= @$page; ?></h4>
                        <?php if (@$permissions['add']) { ?>
                           <button class="btn btn-success btn-round ml-auto" data-toggle="modal" data-target="#addRowModal">
                              <i class="fa fa-plus"></i>
                              Add <?= @$page; ?>
                           </button>
                        <?php } ?>
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
                                       <?= @$page; ?>
                                    </span>
                                 </h5>
                                 <button type="button" class="close close-model" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                 </button>
                              </div>
                              <div class="modal-body">

                                 <form action="" method="post" enctype="multipart/form-data">
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
                                             <label>Name</label>
                                             <input id="addPosition" type="text" class="form-control" name="name" value="<?php echo @$res->name != '' ? @$res->name : $this->input->post('name'); ?>" required>
                                          </div>
                                       </div>
                                       <div class="col-md-6 pr-0">
                                          <div class="form-group form-group-default">
                                             <label>Phone</label>
                                             <input id="addOffice" type="text" class="form-control" name="phone" placeholder="Phone" value="<?php echo @$res->phone != '' ? @$res->phone : $this->input->post('phone'); ?>" required>
                                          </div>
                                       </div>
                                       <div class="col-md-6">
                                          <div class="form-group form-group-default">
                                             <label>Email</label>
                                             <input id="addOffice" type="email" class="form-control" name="email" value="<?php echo @$res->email != '' ? @$res->email : $this->input->post('email'); ?>">
                                          </div>
                                       </div>
                                       <div class="col-md-12">
                                          <div class="form-group form-group-default">
                                             <label>Address</label>
                                             <textarea id="addOffice" class="form-control" rows="4" name="address"><?php echo @$res->address != '' ? @$res->address : $this->input->post('address'); ?></textarea>
                                          </div>
                                       </div>
                                       <div class="col-sm-12 mb-3">
                                          <div class="form-group p-0">
                                             <label class="d-block">Appointment Date</label>
                                             <div class="input-group">
                                                <input type="text" class="form-control" id="datetime" name="appointment_date" value="">
                                                <div class="input-group-append">
                                                   <span class="input-group-text">
                                                      <i class="fa fa-calendar"></i>
                                                   </span>
                                                </div>
                                             </div>
                                          </div>
                                       </div>

                                       <div class="col-md-12">
                                          <div class="form-group form-group-default">
                                             <label>Select a company</label>
                                             <select id="addPosition" class="form-control" name="company" required>
                                                <option value="">Select</option>
                                                <?php foreach ($company as $c) { ?>
                                                   <option value="<?= $c->id; ?>" <?php if (@$c->id == @$res->company) {
                                                                                       echo "selected";
                                                                                    } else if ($this->input->post('company') == @$c->id) {
                                                                                       echo "selected";
                                                                                    } ?>><?= $c->name; ?></option>
                                                <?php } ?>

                                             </select>
                                          </div>
                                       </div>
                                       <div class="col-md-12">
                                          <div class="form-group form-group-default">
                                             <label>Sales person</label>

                                             <select id="addPosition" class="form-control" name="sales_person" required>
                                                <option value="">Select</option>
                                                <?php foreach ($sales_person as $c) { ?>
                                                   <option value="<?= $c->id; ?>" <?php if (@$c->id == @$res->sales_person) {
                                                                                       echo "selected";
                                                                                    } else if ($this->input->post('sales_person') == @$c->id) {
                                                                                       echo "selected";
                                                                                    } ?>><?= $c->name; ?></option>
                                                <?php } ?>

                                             </select>
                                          </div>
                                       </div>

                                       <div class="col-md-12">
                                          <div class="form-group form-group-default">
                                             <label>Remarks</label>
                                             <textarea id="addOffice" class="form-control" rows="4" name="remarks"><?php echo @$res->remarks != '' ? @$res->remarks : $this->input->post('remarks'); ?></textarea>
                                          </div>
                                       </div>



                                       <div class="col-md-12">
                                          <div class="form-check">
                                             <label class="form-check-label">
                                                <input class="form-check-input" type="checkbox" name="create_quotation" id="create_quotation" checked>
                                                <span class="form-check-sign">Create Quotation</span>
                                             </label>
                                          </div>
                                       </div>






                                    </div>

                              </div>
                              <input type="hidden" name="id" value="<?php echo @$res->$module_id ? $res->$module_id : 0; ?>">
                              <div class="modal-footer no-bd">
                                 <button type="submit" id="addRowButton" class="btn btn-primary">Add</button>
                                 <button type="button" class="btn close-model">Close</button>
                              </div>
                           </div>
                           </form>
                        </div>
                     </div>

                     <form method="get">
                        <div class="row">

                           <div class="col-md-4 mt-4 mt-lg-0">


                              <div class="form-group p-0">

                                 <select class="form-control" id="formGroupDefaultSelect" name="sales_person" required>
                                    <option value="">Sales Person</option>
                                    <?php foreach ($sales_person as $sp) { ?>
                                       <option value="<?= $sp->id; ?>"><?= $sp->name; ?></option>
                                    <?php } ?>
                                 </select>
                              </div>
                           </div>
                           <div class="col-md-2 mt-4 mt-lg-0">


                              <div class="form-group p-0">
                                 <button type="submit" id="addRowButton" class="btn btn-primary w-100">Search</button>
                              </div>
                           </div>

                        </div>
                     </form>
                     <?php if (@$tabledata) { ?>
                        <div class="table-responsive">
                           <hr />
                           <table id="basic-datatables" class="display table table-custom">
                              <thead>
                                 <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Address</th>
                                    <th>Sales Person</th>
                                    <th> Company Name</th>
                                 
                                    <!-- <th style="width: 70px">Action</th> -->
                                 </tr>
                              </thead>

                              <tbody>
                                 <?php
                                 $i = 1;
                                 foreach ($tabledata as $product) {
                                 ?>
                                    <tr>
                                       <td><?php echo $i; ?></td>
                                       <td class="customer-name">
                                          <i class="fa fa-user-circle text-primary"></i>
                                          <?= ucwords(strtolower($product->name)); ?>
                                       </td>
                                       <td class="phone-format">
                                          <i class="fa fa-phone text-success"></i>
                                          <?= @$product->phone; ?>
                                       </td>


                                       <td><?php echo @$product->email; ?></td>

                                       <td class="address-format">
                                          <i class="fa fa-map-marker text-danger"></i>
                                          <?= ucwords(strtolower($product->address)); ?>
                                       </td>
                                       <td><?php echo @$product->sales_person_name; ?></td>
                                       <td><?php echo @$product->company_name; ?></td>
                                

                                       <!-- <td>
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
                                       </td> -->
                                    </tr>

                                 <?php $i++;
                                 } ?>
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


<script>
   <?php if (@$edit == 1 || @validation_errors()) { ?>
      $(document).ready(function() {
         $('#addRowModal').modal('show');
         $('.close-model').click(function() {
            window.location = "<?= @$redirect; ?>";
         });
      });
   <?php } else {  ?>
      $('.close-model').click(function() {
         $('#fm_form_data').trigger('reset');
         $('#addRowModal').modal('hide');
      });
   <?php } ?>
   $(document).ready(function() {
      $('#basic-datatables').DataTable({
         "order": [
            [0, "desc"]
         ]
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
         format: 'YYYY-MM-DD',
      });
      $('#datetime2').datetimepicker({
         format: 'MM/DD/YYYY H:mm',
      });
      $('#datetime3').datetimepicker({
         format: 'MM/DD/YYYY H:mm',
      });

   });
</script>