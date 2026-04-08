<?php $type_ar = array('Percentage', 'Flat Value Independent', 'Flat Value Width Dependent', 'Flat Value Height Dependent', 'Flat Value Square Meter Dependent', 'Flat Value Box Value', 'Matrix', "Multiple", "Area");
$type_ar_str = "";
foreach ($type_ar as $t) {
   $type_ar_str .= '<option value="' . $t . '">' . $t . '</option>';
}
?>
<style>
</style>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="<?= base_url('assets/css/extras.css') ?>">
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
         </div>
         <div class="row">
            <div class="col-md-12">
               <div class="collapse <?= @$edit == 0 ? (@validation_errors() ? 'show' : '') : 'show'; ?>" id="collapseExample" style="">
                  <div class="card">
                     <div class="card-header">
                        <div class="card-title">Edit extra iteqm</div>
                     </div>
                     <div class="card-body">
                        <form method="post" action="<?= base_url('admin/extras/update_extras') ?>">
                                    <select name="company_extra" class="form-control" required>
                           <option value="">Select Company</option>



                           <!-- Other companies -->
                           <?php foreach ($companies as $c) { ?>
                              <option value="<?= $c->id ?>" <?= (isset($tree['company_id']) && $tree['company_id'] == $c->id) ? 'selected' : '' ?>>
                                 <?= $c->name ?>
                              </option>
                           <?php } ?>

                        </select>
                           <div class="tree-container" id="extrasTree"></div>
                           <input type="hidden" name="id" value="<?= $tree['id'] ?? 0 ?>">
                           <div class="text-center mt-3">
                              <button type="submit" class="btn btn-success">Update Extras</button>
                           </div>
                        </form>
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
   const editTree = <?= json_encode($tree ?? []) ?>;
   console.log('Edit Tree:');
</script>
<script>
   function renderNode(node, path, $container, level = 0) {
      const indentStyle = level > 0 ?
         'margin-left:25px;border-left:2px dashed #ccc;padding-left:15px;' :
         '';
          let matrixBtn = '';
    if ( node.id) {
        matrixBtn =
            '<a href="<?= base_url('admin/extras/matrix/') ?>' + node.id + '" ' +
            'class="view-matrix" target="_blank" style="display:' + (node.type === 'Matrix' ? 'inline-block' : 'none') + '">' +
            'View / Edit Matrix </a>';
    }

    
      const html = `
   <div class="tree-item"
     style="${indentStyle}"
     data-path="${path}"
     data-level="${level}">
        <div class="item-header">
            <input type="text" name="extras${path}[name]" value="${node.name ?? ''}" class="form-control">
            <input type="text" name="extras${path}[code]" value="${node.extra_code ?? ''}" placeholder="Code" class="form-control">
          <input type="hidden" name="extras${path}[id]" value="${node.id ?? ''}" class="form-control">
            <select name="extras${path}[type]" class="form-control">
                <option value="">Value Type</option>
                <?php foreach ($type_ar as $t) { ?>
                    <option value="<?= $t ?>" ${node.type === '<?= $t ?>' ? 'selected' : ''}>
                        <?= $t ?>
                    </option>
                <?php } ?>
            </select>
  ${matrixBtn}
            <input type="text" name="extras${path}[value]" value="${node.value ?? 0}" class="form-control">
            <button type="button" class="btn btn-success btn-sm add-child">+Sub</button>
            <button type="button" class="btn btn-info btn-sm add-option">+Option</button>
            <button type="button" class="btn btn-danger btn-sm remove-item" data-id="${node.id}">×</button>
        </div>
        <div class="option-list"></div>
        <div class="children"></div>
    </div>`;
      const $node = $(html);
      $container.append($node);
      const $optionList = $node.children('.option-list').first();
      const $childrenContainer = $node.children('.children').first();
      if (Array.isArray(node.options) && node.options.length > 0) {
         $node.find('select[name*="[type]"], input[name*="[value]"]').hide();
         node.options.forEach((opt, i) => {
                let optionMatrixBtn = '';
if (opt.type === 'Matrix' && node.id) {
    optionMatrixBtn =
        '<a href="<?= base_url('admin/extras/matrix/') ?>' + node.id + '" ' +
        'class="view-matrix option-matrix" target="_blank">' +
        'View / Edit Matrix</a>';
}
            $optionList.append(`
                <div class="option-item">
                    <select name="extras${path}[options][${i}][type]" class="form-control">
                        <?php foreach ($type_ar as $t) { ?>
                            <option value="<?= $t ?>" ${opt.type === '<?= $t ?>' ? 'selected' : ''}>
                                <?= $t ?>
                            </option>
                        <?php } ?>
                    </select>
                      ${optionMatrixBtn}
                    <input type="text" name="extras${path}[options][${i}][price]" value="${opt.price}" class="form-control">
                    <button type="button" class="btn btn-danger btn-sm remove-option">×</button>
                </div>
            `);
         });
      }
      if (Array.isArray(node.children) && node.children.length > 0) {
         node.children.forEach((child, i) => {
            renderNode(
               child,
               `${path}[children][${i}]`,
               $childrenContainer,
               level + 1
            );
         });
      }
   }
   $(document).ready(function() {
      $('#extrasTree').empty();
      if (editTree && editTree.id) {
         renderNode(editTree, '[0]', $('#extrasTree'), 0);
      }
   });
$(document).on('click', '.add-child', function () {
  const parent = $(this).closest('.tree-item');
  const parentPath  = parent.data('path');
  const parentLevel = parent.data('level');
  const container = parent.children('.children');
  const index = container.children('.tree-item').length;
// parent.find(
//   '.item-header select,' +
//   '.item-header input[name*="[value]"]'
// ).hide();
  renderNode(
    {},
    `[${parentPath}][children][${index}]`,
    container,
    parentLevel + 1
  );
});
   $(document).on('click', '.add-option', function() {
      const parent = $(this).closest('.tree-item');
      const parentPath = parent.find('input[name]').first().attr('name').replace('[name]', '');
      const list = parent.find('.option-list');
      const index = list.children('.option-item').length;
      parent.find('> .item-header select, > .item-header input[name*="[value]"]').hide();
      list.append(`
         <div class="option-item">
            <select name="${parentPath}[options][${index}][type]" class="form-control">
               <?php foreach ($type_ar as $t) { ?><option><?= $t ?></option><?php } ?>
            </select>
            <input type="text" name="${parentPath}[options][${index}][price]" class="form-control">
            <button type="button" class="btn btn-danger btn-sm remove-option">×</button>
         </div>
      `);
   });
   // REMOVE
$(document).on('click', '.remove-item', function () {
    const $btn = $(this);
    const $item = $btn.closest('.tree-item');
    const id = $btn.data('id');
    if (!id) {
        Swal.fire('Error', 'Invalid item ID', 'error');
        return;
    }
    Swal.fire({
        title: 'Are you sure?',
        text: 'This action will permanently delete this item.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?= base_url('admin/extras/delete_extra/') ?>' + id,
                type: 'POST',
                dataType: 'json',
                success: function (res) {
                    if (res.status === 'success') {
                        Swal.fire(
                            'Deleted!',
                            'The item has been deleted successfully.',
                            'success'
                        );
                        $item.remove();
                    } else {
                        Swal.fire(
                            'Failed!',
                            res.message || 'Delete failed.',
                            'error'
                        );
                    }
                },
                error: function () {
                    Swal.fire(
                        'Error!',
                        'Server error. Please try again.',
                        'error'
                    );
                }
            });
        }
    });
});
 $(document).on('click', '.remove-option', function () {
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
   $(document).on('change', 'select[name*="[typee]"]', function () {
    const $select = $(this);
    const selectedType = $select.val();
    const $item = $select.closest('.tree-item');
    const $matrixLink = $item.find('.view-matrix');
    if (selectedType === 'Matrix') {
        $matrixLink.show();
    } else {
        $matrixLink.hide();
    }
});
</script>