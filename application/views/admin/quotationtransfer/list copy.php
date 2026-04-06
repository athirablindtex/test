<div class="main-panel">
   <div class="content">
      <div class="page-inner">
         		<div class="page-header">
            <h4 class="page-title">Quotation</h4>
            <div class="btn-group btn-group-page-header ml-auto">
            	
            </div>
            </div>
     

         <div class="row">
            <div class="col-md-12">
              <div class="card">
    
                  <div class="card-body">

                
<form> 
                     <div class="row">
                              <div class="col-md-4">
                                 
                     
                            <div class="form-group p-0">
                                 
                                          <div class="input-group">
  <input type="text" class="form-control" id="datetime2" name="from" placeholder="Date From">
                                       <div class="input-group-append">
                                          <span class="input-group-text">
                                             <i class="fa fa-calendar"></i>
                                          </span>
                                       </div>
                                    </div>

  </div>
                              </div>
                                           <div class="col-md-4 mt-3 mt-lg-0">
                                 
                     
                                       <div class="input-group">
  <input type="text" class="form-control" id="datetime3" name="to" placeholder="Date To">
                                       <div class="input-group-append">
                                          <span class="input-group-text">
                                             <i class="fa fa-calendar"></i>
                                          </span>
                                       </div>
                                    </div>
                              </div>
                                         <div class="col-md-4 mt-3 mt-lg-0">
                                 
                     
                            <div class="form-group p-0">
                         
                                        <select class="form-control" id="formGroupDefaultSelect" name="sales_person_from">
                                        <option value="">Transfer From Sales Person</option>
                                 <?php foreach($sales_person as $sp){ ?>
                                 <option value="<?= $sp->id; ?>"><?= $sp->name; ?></option>
                                 <?php } ?>
                              </select>
                           </div>
                              </div>

                              <div class="col-md-4 mt-3 mt-lg-0">
                                 
                     
                                 <div class="form-group p-0">
                              
                                             <select class="form-control" id="formGroupDefaultSelect" name="sales_person_to">
                                             <option value="">Transfer To Sales Person</option>
                                      <?php foreach($sales_person as $sp){ ?>
                                      <option value="<?= $sp->id; ?>"><?= $sp->name; ?></option>
                                      <?php } ?>
                                   </select>
                                </div>
                                   </div>
                                      
                                          <div class="col-md-5 mt-4">
                                 
                     
                            <div class="form-group p-0">
                         
                            <select id="addPosition"  class="form-control" name="company" >
                                                  <option value="">Select Company</option>
                                                 <?php foreach($company as $c){ ?>
                                                   <option value="<?= $c->id; ?>" <?php if(@$c->id==@$res->company){ echo "selected"; } else if($this->input->post('company')==@$c->id){ echo "selected"; } ?>><?= $c->name; ?></option>
                                                 <?php } ?>
                                                 
                                             </select>
                           </div>
                              </div>
                              <div class="col-md-5 mt-4">
                                 
                     
                                 <div class="form-group p-0">
                              
                                        <input class="form-control" name="customer_phone" placeholder="Customer Phone Number" >
                                        
                                </div>
                                   </div>
                                  <div class="col-md-2 mt-4">
                                 
                     
                            <div class="form-group p-0">
                    <button type="submit" id="addRowButton" class="btn btn-primary w-100">Search</button>
                 </div>
                              </div>
                           </div>
                           </form>
              <div class="table-responsive">

                        <table id="multi-filter-select" class="display table table-striped table-hover" >
                            <thead>
                              <tr>
                                 <th>Transfer ID</th>
                                 <th>Transfer Date</th>
                                <th>Transfer From SP Name</th>
                                 <th>Transfer To SP Name</th>
                                 <th>Transfer Reason</th>
                                 <th>Quotation Id</th>
                                 <th>Customer Name</th>
                                 <th>Company</th>
                                 <th>Amount</th>
                                 <th>Transfer Status</th>
                                 <th style="width: 10%">Action</th>
                              </tr>
                           </thead>
                       
                           <tbody>

                            <?php foreach($tabledata as $product){ ?>
                              <tr>
                                 <td><?= @$product['id']; ?></td>
                                 <td><?= date('d-m-Y',strtotime(@$product['created_date'])); ?></td>
                                 <td><?= @$product['from_name']; ?></td>
                                 <td><?= @$product['to_name']; ?></td>
                                 <td><?= @$product['reason']; ?></td>
                                 <td><?= @$product['quotation']; ?></td>
                                 <td><?= @$product['customer_name']; ?></td>
                                 <td><?= @$product['company_name']; ?></td>
                                <td><?= @$product['total']; ?></td>
                                    <td><span class="badge  border-0 bg-success text-white"><?= @$product['status']; ?></span></td>
                                 <td>
                                    <div class="form-button-action">
                                     
                                       <button  data-toggle="modal" data-target="#exampleModal-<?= @$product['id']; ?>" type="button"  title="" class="btn btn-link btn-primary btn-lg" data-original-title="Edit Task">
                                       <i class="fa fa-eye"></i>
                                       </button>
                                          <button type="button" data-toggle="tooltip" title="" class="btn btn-link btn-danger d-none" data-original-title="Remove">
                                          <i class="fa fa-times"></i>
                                          </button>
                                    </div>
                                 </td>
                              </tr>
                            
                            <?php } ?>
                                      
                                    
                           
                              
                             
                            
                            
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


<?php foreach($tabledata as $product){ ?>
    <!-- Model -->
    <div class="modal modal-vv fade" id="exampleModal-<?= @$product['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" >
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                          <h5 class="modal-title" id="exampleModalLabel">Quotaion #<?= @$product['id'] ?></h5>
                                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">×</span>
                                          </button>
                                      </div>
                                      <div class="modal-body p-4">
                                          <div class="row d-none">
                                            <div class="col mx-auto form-inline">
                                                <div class="form-group mb-2 mx-auto">
                                                  <label class="pr-2">Quataion Status</label>
                                                  <select class="form-control select-type" id="formGroupDefaultSelect" style="height: 40px;">
                                                      <option>Status 1</option>
                                                      <option>Status 2</option>
                                                      <option>Status 3</option>
                                                      <option>Status 4</option>
                                                      <option>Status 5</option>
                                                  </select>
                                                  <!--    <button class="btn btn-primary rounded-0">Search</button> -->
                                                </div>
                                            </div>
                                          </div>
                                          <hr/>
                                          <ul class="nav nav-tabs" role="tablist">
                                            <li class="nav-item submenu">
                                                <a class="nav-link active show" data-toggle="tab" href="#tabs-1-<?= @$product['id']; ?>" role="tab" aria-selected="true">Customer</a>
                                            </li>
                                            <li class="nav-item submenu">
                                                <a class="nav-link" data-toggle="tab" href="#tabs-2-<?= @$product['id']; ?>" role="tab" aria-selected="false">Survey</a>
                                            </li>
                                            <li class="nav-item submenu">
                                                <a class="nav-link" data-toggle="tab" href="#tabs-3-<?= @$product['id']; ?>" role="tab" aria-selected="false">Invoice</a>
                                            </li>
                                          </ul>
                                          <!-- Tab panes -->
                                          <div class="tab-content">
                                            <div class="tab-pane active show" id="tabs-1-<?= @$product['id']; ?>" role="tabpanel">
                                                <div class="card-body p-0 mt-3">
                                                  <div class="row">
                                                      <div class="col-md-4 mb-2">
                                                        <strong class="d-block mb-2">Quotaion To:</strong>
                                                        <address>
                                                        <?= @$product['cust_name']!=""?@$product['cust_name']:@$product['customer_name']; ?><br>
                                                            <?= @$product['cust_address']; ?>
                                                        </address>
                                                      </div>
                                                      <div class="col-md-3 mb-4"> <strong class="d-block mb-2">Contact No</strong>
                                                        <a href="tel:98575854585" style="font-size: 14px;/* color: #0056b3; *//* text-decoration: underline; *//* font-weight: 600; */"><?= @$product['cust_phone']!=""?$product['cust_phone']:$product['customer_phone']; ?></a> 
                                                      </div>
                                                      <div class="col-md-3 mb-4"> <strong class="d-block mb-2">Email</strong>
                                                        <a href="mailto:test@test.com" style="
                                                            font-size: 14px;
                                                            "><?= @$product['cust_email']; ?></a> 
                                                      </div>
                                                      <div class="col-md-2 mb-4"> <strong class="d-block mb-2">Price</strong>
                                                        <span>AED: <?= @$product['total']; ?></span> 
                                                      </div>
                                                      <div class="col-sm-12">
                                                        <hr>
                                                        <strong class="d-block mb-2">Remarks</strong>
                                                        <span><?= @$product['remarks']; ?></span> 
                                                      </div>
                                                  </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="tabs-2-<?= @$product['id']; ?>" role="tabpanel">
                                                <p class="  mt-3 mb-0">Total No of windows: <?= @$product['window_count']; ?></p>
                                                <hr/>
                                                <div class="table-responsive">
                                                  <table id="multi-filter-select" class="display table table-striped table-hover" >
                                                      <thead>
                                                        <tr>
                                                            <th>Product Type</th>
                                                            <th>Sub Type</th>
                                                            <th>Product Name</th>
                                                            <th>Window Name</th>
                                                            <th>Room Name</th>
                                                            <th>Drob</th>
                                                            <th>Width</th>
                                                            <th>Chain Drop</th>
                                                            <th>Cost</th>
                                                            
                                                            
                                                        </tr>
                                                      </thead>
                                                      <tbody>
                                                        <?php foreach(@$product['rooms'] as $rm){ ?>
                                                          <?php foreach($rm['windows'] as $wd){ ?>
                                                              <tr>
                                                                  <td><?= @$wd['product_type']; ?></td>
                                                                  <td><?= @$wd['sub_product_type']; ?></td>
                                                                  <td><?= @$wd['product_name']; ?></td>
                                                                  <td><?= @$wd['window_name']; ?></td>
                                                                  <td><?= @$rm['room_name']; ?></td>
                                                                  <td><?= @$wd['height'].' '.@$wd['unit']; ?></td>
                                                                  <td><?= @$wd['width'].' '.@$wd['unit']; ?></td>
                                                                  <td><?= @$wd['chain_drop'].' '.@$wd['unit']; ?></td>
                                                                  <td><?= @$wd['total']; ?></td>
                                                                  
                                                              </tr>
                                                        <?php }} ?>
                                                      </tbody>
                                                  </table>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="tabs-3-<?= @$product['id']; ?>" role="tabpanel">
                                                <div class="page-divider"></div>
                                                <div class="row">
                                                  <div class="col-md-12">
                                                      <div class="row mt-3 mb-3">
                                                        <div class="col-9 d-flex align-items-center justify-content-end">
                                                            <div class="w-100">
                                                              <h4 class="page-title m-0">Invoice</h4>
                                                              <div class="clearfix"></div>
                                                            </div>
                                                        </div>
                                                        <div class="col-3 d-flex align-items-center justify-content-end">
                                                            <div class="w-100">
                                                              <button type="button" id="addRowButton" class="btn btn-primary float-lg-right">Print</button>
                                                              <div class="clearfix"></div>
                                                            </div>
                                                        </div>
                                                      </div>
                                                      <div class="card card-invoice border">
                                                        <div class="card-header">
                                                            <div class="invoice-header">
                                                              <h3 class="invoice-title">
                                                                  Invoice
                                                              </h3>
                                                              <div class="invoice-logo">
                                                                  <img src="<?= base_url(); ?>assets/admin/img/Blindtex-logo.png" alt="company logo">
                                                              </div>
                                                            </div>
                                                            <div class="invoice-desc">
                                                              <?= $product['company_name']; ?><br><?= $product['company_address']; ?>
                                                            </div>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="separator-solid"></div>
                                                            <div class="row">
                                                              <div class="col-md-4 info-invoice">
                                                                  <h5 class="sub">Date</h5>
                                                                  <p><?= date('F d,Y',strtotime(@$product['updated_date'])); ?></p>
                                                              </div>
                                                              <div class="col-md-4 info-invoice">
                                                                  <h5 class="sub">Invoice ID</h5>
                                                                  <p>#<?= @$product['id']; ?></p>
                                                              </div>
                                                              <div class="col-md-4 info-invoice">
                                                                  <h5 class="sub">Invoice To</h5>
                                                                  <p>
                                                                  <?= @$product['cust_name']!=""?@$product['cust_name']:@$product['customer_name']; ?><br>
                                                                  <?= @$product['cust_address']; ?>
                                                                  </p>
                                                              </div>
                                                            </div>
                                                            <div class="row">
                                                              <div class="col-md-12">
                                                                  <div class="invoice-detail">
                                                                    <div class="invoice-top">
                                                                        <h3 class="title"><strong>Order summary</strong></h3>
                                                                    </div>
                                                                    <div class="invoice-item">
                                                                        <div class="table-responsive">
                                                                          <table class="table table-striped">
                                                                              <thead>
                                                                                <tr>
                                                                                    <td><strong>Item</strong></td>
                                                                                    <td class="text-center"><strong>Price</strong></td>
                                                                                    <td class="text-center"><strong>Quantity</strong></td>
                                                                                    <td class="text-right"><strong>Totals</strong></td>
                                                                                </tr>
                                                                              </thead>
                                                                              <tbody>
                                                                                <?php 
                                                                                  foreach($product['products'] as $it){ 
                                                                                ?>
                                                                                <tr>
                                                                                    <td><?= $it['item_name'];?></td>
                                                                                    <td class="text-center">AED <?= $it['price'];?></td>
                                                                                    <td class="text-center"><?= $it['quantity'];?></td>
                                                                                    <td class="text-right">AED <?= $it['total'];?></td>
                                                                                </tr>
                                                                                  <?php } ?>
                                                                                  <tr>
                                                                                      <td></td>
                                                                                      <td></td>
                                                                                      <td class="text-center"><strong>Subtotal</strong></td>
                                                                                      <td class="text-right"><?= @$product['sub_total']; ?></td>
                                                                                  </tr>
                                                                                  <tr>
                                                                                      <td></td>
                                                                                      <td></td>
                                                                                      <td class="text-center"><strong>VAT</strong></td>
                                                                                      <td class="text-right"><?= @$product['vat']; ?></td>
                                                                                  </tr>
                                                                                  <tr>
                                                                                      <td></td>
                                                                                      <td></td>
                                                                                      <td class="text-center"><strong>Discount</strong></td>
                                                                                      <td class="text-right"><?= @$product['discount']; ?></td>
                                                                                  </tr>
                                                                                  <tr>
                                                                                      <td></td>
                                                                                      <td></td>
                                                                                      <td class="text-center"><strong>Total</strong></td>
                                                                                      <td class="text-right"><?= @$product['total']; ?></td>
                                                                                  </tr>
                                                                              </tbody>
                                                                          </table>
                                                                        </div>
                                                                    </div>
                                                                  </div>
                                                                  <div class="separator-solid  mb-3"></div>
                                                              </div>
                                                            </div>
                                                        </div>
                                                        <div class="card-footer">
                                                            <div class="row">
                                                              <div class="col-sm-7 col-md-5 mb-3 mb-md-0 transfer-to">
                                                                  <h5 class="sub">Bank Transfer</h5>
                                                                  <div class="account-transfer">
                                                                    <div><span>Account Name:</span><span>Syamsuddin</span></div>
                                                                    <div><span>Account Number:</span><span>1234567890934</span></div>
                                                                    <div><span>Code:</span><span>BARC0032UK</span></div>
                                                                  </div>
                                                              </div>
                                                              <div class="col-sm-5 col-md-7 transfer-total">
                                                                  <h5 class="sub">Total Amount</h5>
                                                                  <div class="price">AED <?= @$product['total']; ?></div>
                                                                  <span>Taxes Included</span>
                                                              </div>
                                                            </div>
                                                            <div class="separator-solid"></div>
                                                            <h6 class="text-uppercase mt-4 mb-3 fw-bold">
                                                              Notes
                                                            </h6>
                                                            <p class="text-muted mb-0">
                                                              <?= @$product['remarks']; ?>
                                                            </p>
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
                              <!-- Model -->
<?php } ?>








      </div>
      
    </div>
  </div>
</div>







<script >
      $(document).ready(function() {



        $('#datetime').datetimepicker({
         format: 'YYYY-MM-DD',
      });
                 $('#datetime2').datetimepicker({
         format: 'YYYY-MM-DD',
      });
                      $('#datetime3').datetimepicker({
         format: 'YYYY-MM-DD',
      });

      $('#datetime2').datetimepicker().on('dp.change', function (e) {
                var incrementDay = moment(new Date(e.date));
                //incrementDay.add(1, 'days');
                $('#datetime3').data('DateTimePicker').minDate(incrementDay);
                $(this).data("DateTimePicker").hide();
            });

            $('#datetime3').datetimepicker().on('dp.change', function (e) {
                var decrementDay = moment(new Date(e.date));
                //decrementDay.subtract(1, 'days');
                $('#datetime2').data('DateTimePicker').maxDate(decrementDay);
                 $(this).data("DateTimePicker").hide();
            });
                    

    


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



