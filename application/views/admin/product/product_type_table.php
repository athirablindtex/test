

<div class="form-group mt-3">
  <label for="target_company">Select Target Company(s):</label>
 <select id="target_company" name="target_company[]" class="form-control" multiple>
  <?php foreach ($company as $b): ?>
    <?php if (isset($company_id) && $b->id == $company_id) continue; // skip current company ?>
    <option value="<?= $b->id ?>"><?= $b->name; ?></option>
  <?php endforeach; ?>
</select>

</div>

<button type="button" id="copy_settings" class="btn btn-primary mt-3">
  <i class="fa fa-copy"></i> Transfer Settings
</button>
<table class="table table-bordered">


  <style>
    .show-checkbox {
      visibility: visible !important;
      opacity: 1 !important;
      position: static !important;
    }
  </style>
  <thead>
    <tr>
      <th><input type="checkbox" id="select_all" class="show-checkbox"> Select All</th>
      <th>Product Type</th>
      <th>Margin (%)</th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($product_types)) { ?>
      <?php foreach ($product_types as $type) { ?>
        <?php if (!empty($type->product_type_name)) { ?>
          <tr>
            <td><input type="checkbox" class="product_type_checkbox show-checkbox" name="product_types[]" value="<?= $type->product_type_id ?>"></td>
            <td><?= $type->product_type_name ?></td>
            <td><?= $type->margin_value ?? '' ?></td>
          </tr>
        <?php } ?>
      <?php } ?>
    <?php } else { ?>
      <tr><td colspan="3" class="text-center">No product types found.</td></tr>
    <?php } ?>
  </tbody>
</table>




<script>
$(document).ready(function () {


  // Initialize Select2
  $('#target_company').select2({
    placeholder: "Select one or more companies",
    allowClear: true
  });

  // Select all logic
  $('#select_all').on('change', function () {
    $('.product_type_checkbox').prop('checked', this.checked);
  });

  $('.product_type_checkbox').on('change', function () {
    $('#select_all').prop('checked', $('.product_type_checkbox:checked').length === $('.product_type_checkbox').length);
  });

  // Copy settings logic
  $('#copy_settings').on('click', function () {
    let selectedTypes = $('.product_type_checkbox:checked').map(function () {
      return $(this).val();
    }).get();

    let selectedCompanies = $('#target_company').val();

    if (selectedTypes.length === 0) {
      alert('Please select at least one product type.');
      return;
    }

    if (!selectedCompanies || selectedCompanies.length === 0) {
      alert('Please select at least one target company.');
      return;
    }

    if (confirm("Do you want to transfer selected settings to the selected company(s)?")) {
      $.ajax({
        url: "copy_company_product_types",
        type: "POST",
        dataType: "json",
        data: {
          product_types: selectedTypes,
          source_company: "<?= $company_id ?>",
          target_companies: selectedCompanies
        },
        success: function (response) {
          alert(response.message || 'Settings transferred successfully.');
        },
        error: function () {
          alert('Error transferring settings. Please try again.');
        }
      });
    }
  });
});
</script>
