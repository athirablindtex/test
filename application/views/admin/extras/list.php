<?php $type_ar = array('Percentage', 'Flat Value Independent', 'Flat Value Width Dependent', 'Flat Value Height Dependent', 'Flat Value Square Meter Dependent', 'Flat Value Box Value', 'Matrix', "Multiple", "Area");
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

   button.btn.btn-sm.btn-primary.ms-2.add-type-value {
      margin-left: 20px;
   }

   .main-items-input {
      margin-right: 10px;
   }

   .form-field {
      margin-right: 10px;
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
                        <label>Select Company</label>
                        <select name="company_id" class="form-control" required>
                           <option value="">Select Company</option>



                           <!-- Other companies -->
                           <?php foreach ($companies as $c) { ?>
                              <option value="<?= $c->id ?>">
                                 <?= $c->name ?>
                              </option>
                           <?php } ?>

                        </select>
                     </div>
                  </div>
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
                        <form id="extrasForm" action="<?= base_url('admin/extras/save_extras') ?>" method="post">
                           <div class="row">
                              <?php if (@validation_errors()) { ?>
                                 <div class="col-md-12">
                                    <div class="alert alert-danger" role="alert">
                                       <?php echo @validation_errors(); ?>
                                    </div>
                                 </div>
                              <?php } ?>
                              <div class="col-md-12 col-main">
                                 <style>
                                    .tree-container {
                                       background: #fafafa;
                                       border-radius: 6px;
                                       padding: 10px;
                                    }

                                    .tree-item {
                                       margin-left: 20px;
                                       border-left: 2px dashed #ccc;
                                       padding-left: 15px;
                                       margin-top: 10px;
                                    }

                                    .item-header,
                                    .option-item {
                                       display: flex;
                                       flex-wrap: wrap;
                                       align-items: center;
                                       gap: 10px;
                                       margin-bottom: 8px;
                                    }

                                    .option-list {
                                       margin-left: 35px;
                                       padding: 8px;
                                       border-left: 2px dotted #aaa;
                                       background: #fff;
                                       border-radius: 6px;
                                    }

                                    .form-control,
                                    .form-select {
                                       flex: 1;
                                       min-width: 150px;
                                       max-width: 220px;
                                    }

                                    .btn-sm {
                                       padding: 5px 10px;
                                    }
                                 </style>
                                 <div class="tree-container" id="extrasTree">
                                    <div class="tree-item root" data-level="0" data-index="0">
                                       <div class="item-header">
                                          <input type="text" name="extras[0][name]" class="form-control" placeholder="Name">
                                          <input type="text" name="extras[0][code]" class="form-control" placeholder="Code">
                                          <select name="extras[0][value_type]" class="form-control">
                                             <option value="">Value Type</option>
                                             <option>Percentage</option>
                                             <option>Flat Value Independent</option>
                                             <option>Flat Value Width Dependent</option>
                                             <option>Flat Value Height Dependent</option>
                                             <option>Flat Value Square Meter Dependent</option>
                                             <option>Flat Value Box Value</option>
                                             <option>Matrix</option>
                                             <option>Multiple</option>
                                             <option>Area</option>
                                          </select>
                                          <input type="text" name="extras[0][value]" class="form-control" placeholder="Value">
                                          <button type="button" class="btn btn-success btn-sm add-child">+Sub</button>
                                          <button type="button" class="btn btn-info btn-sm add-option">+Option</button>
                                          <button type="button" class="btn btn-danger btn-sm remove-item">×</button>
                                       </div>
                                       <div class="option-list"></div>
                                       <div class="children"></div>
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
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-md-12">
            <div class="card">

               <div class="card-header">
                  <div class="card-title">New extra item</div>
               </div>

               <div class="card-body">

                  <!-- Search -->
                  <form method="get" action="<?= site_url('admin/extras/list'); ?>">

                      <div class="col-md-6">
                              <div class="form-group form-group-default">
                                 <label>Select Company</label>
                                 <select class="form-control" name="company" id="companyid" required>
                                    <option value="">Company</option>
                                    <?php foreach ($companies as $c) { ?>
                                       <option value="<?= $c->id; ?>" <?= @$c->id == @$res->company_id ? 'selected' : ''; ?>>
                                          <?= $c->name; ?>
                                       </option>
                                    <?php } ?>
                                 </select>
                              </div>
                           </div>
                     <div class="d-flex justify-content-end mb-3">
                        <input type="text"
                           name="search"
                           value="<?= $this->input->get('search'); ?>"
                           class="form-control me-2"
                           style="width:250px;"
                           placeholder="Search name...">

                        <button class="btn btn-primary me-2">Search</button>

                        <a href="<?= site_url('admin/extras/list'); ?>"
                           class="btn btn-secondary">Reset</a>
                     </div>
                  </form>

                  <!-- Table -->
                  <table class="table table-bordered table-striped" id="extrasTable">
                     <thead>
                        <tr>
                           <th width="60">ID</th>
                           <th>Name</th>
                           <th width="120">Actions</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php foreach ($tabledata as $pro) { ?>
                           <tr>
                              <td><?= $pro['id']; ?></td>
                              <td>
                                 <strong><?= $pro['name']; ?></strong>
                                 <?php if (empty($pro['sub'])) { ?>
                                    <br>
                                    <small class="text-muted">
                                       Value Type:
                                       <strong><?= $pro['type']; ?></strong>
                                       — <strong><?= $pro['value']; ?></strong>
                                    </small>
                                 <?php } ?>
                              </td>
                              <td>
                                 <?php if (@$permissions['add']) { ?>
                                    <a href="<?= site_url('admin/' . $controller . '/edit/' . $pro['id']); ?>"
                                       class="btn btn-sm btn-primary">
                                       <i class="fa fa-edit"></i>
                                    </a>
                                 <?php } ?>
                                 <?php if (@$permissions['delete']) { ?>
                                    <a href="<?= site_url('admin/' . $controller . '/delete/' . $pro['id']); ?>"
                                       onclick="return confirm('<?= $this->lang->line('common_confirm_delete'); ?>');"
                                       class="btn btn-sm btn-danger">
                                       <i class="fa fa-trash"></i>
                                    </a>
                                 <?php } ?>
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
<script>
   $(document).ready(function() {
    
      $(document).on('click', '.add-child', function() {
         const parent = $(this).closest('.tree-item');
         const parentHeader = parent.children('.item-header'); // ensure targeting only this header
         const parentName = parent.find('input[name], select[name]').first().attr('name').match(/^extras(\[.*\])\[name\]/)[1];
         const childrenContainer = parent.children('.children');
         const childCount = childrenContainer.children('.tree-item').length;
         // Hide only this parent’s own fields — not its children’s
         parentHeader.find('> select[name*="[value_type]"], > input[name*="[value]"], > .add-option').hide();
         const childHTML = `
    <div class="tree-item" data-level="${parseInt(parent.data('level')) + 1}" data-index="${childCount}">
      <div class="item-header">
        <input type="text" name="extras${parentName}[children][${childCount}][name]" class="form-control" placeholder="Sub Name">
                <input type="text" name="extras${parentName}[children][${childCount}][code]" class="form-control" placeholder="Code">
        <select name="extras${parentName}[children][${childCount}][value_type]" class="form-control">
          <option value="">Value Type</option>
          <option>Percentage</option>
          <option>Flat Value Independent</option>
          <option>Flat Value Width Dependent</option>
          <option>Flat Value Height Dependent</option>
          <option>Flat Value Square Meter Dependent</option>
          <option>Flat Value Box Value</option>
          <option>Matrix</option>
          <option>Multiple</option>
          <option>Area</option>
        </select>
        <input type="text" name="extras${parentName}[children][${childCount}][value]" class="form-control" placeholder="Value">
        <button type="button" class="btn btn-success btn-sm add-child">+Sub</button>
        <button type="button" class="btn btn-info btn-sm add-option">+Option</button>
        <button type="button" class="btn btn-danger btn-sm remove-item">×</button>
      </div>
      <div class="option-list"></div>
      <div class="children"></div>
    </div>
  `;
         childrenContainer.append(childHTML);
      });
      // Add Option
      $(document).on('click', '.add-option', function() {
         const parent = $(this).closest('.tree-item');
         const parentName = parent.find('input[name], select[name]').first().attr('name').match(/^extras(\[.*\])\[name\]/)[1];
         const optionList = parent.find('.option-list');
         const optionCount = optionList.children('.option-item').length;
         const parentHeader = parent.children('.item-header');
         parentHeader.find('> select[name*="[value_type]"], > input[name*="[value]"]').hide();
         const optionHTML = `
      <div class="option-item">
        <select name="extras${parentName}[options][${optionCount}][type]" class="form-control">
          <option value="">Option Type</option>
          <option>Percentage</option>
          <option>Flat Value Independent</option>
          <option>Flat Value Width Dependent</option>
          <option>Flat Value Height Dependent</option>
          <option>Flat Value Square Meter Dependent</option>
          <option>Flat Value Box Value</option>
          <option>Matrix</option>
          <option>Multiple</option>
          <option>Area</option>
        </select>
        <input type="text" name="extras${parentName}[options][${optionCount}][value]" class="form-control" placeholder="Option Value">
        <button type="button" class="btn btn-danger btn-sm remove-option">×</button>
      </div>
    `;
         optionList.append(optionHTML);
      });
      // Remove Extra or Option
      $(document).on('click', '.remove-item', function() {
         $(this).closest('.tree-item').remove();
      });
      $(document).on('click', '.remove-option', function() {
         const optionItem = $(this).closest('.option-item');
         const treeItem = optionItem.closest('.tree-item');
         const optionList = treeItem.find('.option-list');
         optionItem.remove();
         // if no options left → show parent fields again
         if (optionList.children('.option-item').length === 0) {
            treeItem.children('.item-header')
               .find('select, input[name*="[value]"]')
               .show();
         }
      });
   });
</script>