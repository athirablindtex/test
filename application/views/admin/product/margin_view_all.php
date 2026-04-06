<div class="main-panel">
    <div class="content">
        <div class="page-inner">
<?php $exclude_company_id = $this->input->get('company_id') ?>
            <!-- ================= PAGE HEADER ================= -->
            <div class="page-header">
                <h4 class="page-title"><?= @$page; ?></h4>
                <div class="btn-group btn-group-page-header ml-auto">
                    <a href="<?= site_url('admin/product/pricebandmargin') ?>" class="btn btn-secondary btn-round">
                        <i class="fa fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>

            <!-- ================= ACTION BUTTONS ================= -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">

                            <div class="mb-3">
                    <button id="exportCsv" class="btn btn-success btn-sm">
                    <i class="fa fa-file-excel"></i> Export CSV
                    </button>

                            
                            <button id="exportBtn" class="btn btn-info btn-sm">
                            <i class="fa fa-share-square"></i> Export to Other Companies
                            </button><button class="btn btn-outline-primary btn-sm"
        data-toggle="modal"
        data-target="#howToUseModal">
    <i class="fa fa-question-circle"></i> How to Use
</button></div>
<div class="mb-2">
    <input type="text"
           id="tableSearch"
           class="form-control form-control-sm"
           placeholder="Search company / product / band / margin...">
</div>



                            <!-- ================= TABLE ================= -->
                            <div class="table-responsive">
                                <table id="marginTable" class="display nowrap">
                                    <thead>
                                        <tr>
                                            <th width="30">
                                                <input type="checkbox" id="checkAll">
                                            </th>
                                            <th>Sr. No.</th>
                                            <th>Company</th>
                                            <th>Product Type</th>
                                            <th>Price Band</th>
                                            <th>Band Type</th>
                                            <th>Unit Price</th>
                                            <th>Margin (%)</th>
                                            <th>Updated At</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($margins)): $i=1; ?>
                                            <?php foreach ($margins as $row): ?>
                                                <tr>
                                                    <td>
                                                        <input type="checkbox"
                                                               class="row-check"
                                                               value="<?= (int)$row->id ?>">
                                                    </td>
                                                    <td><?= $i++; ?></td>
                                                    <td><?= htmlspecialchars($row->company_name); ?></td>
                                                    <td><?= htmlspecialchars($row->product_type); ?></td>
                                                    <td><?= htmlspecialchars($row->price_band); ?></td>
                                                    <td><?= htmlspecialchars($row->band_type); ?></td>
                                                    <td><?= htmlspecialchars($row->unit_price); ?></td>
                                                    <td><?= htmlspecialchars($row->margin); ?></td>
                                                    <td>
                                                        <?= !empty($row->updated_at)
                                                            ? date('d-m-Y H:i', strtotime($row->updated_at))
                                                            : '-' ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>

                        </div><!-- card-body -->
                    </div><!-- card -->
                </div>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="howToUseModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">
          <i class="fa fa-info-circle"></i> How to Export Margin Data
        </h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="modal-body">

        <!-- VIDEO -->
        <video controls class="w-100 mb-3" preload="metadata">
          <source src="<?= base_url('assets/help/export_companies_margin.mp4') ?>" type="video/mp4">
          Your browser does not support the video.
        </video>

        <!-- STEPS -->
        <ol class="small text-muted">
          <li>Select margin rows using checkboxes</li>
          <li>Click <b>Export to Other Companies</b></li>
          <li>Select companies</li>
          <li>Click <b>Transfer Data</b></li>
        </ol>

      </div>

    </div>
  </div>
</div>



<div class="modal fade" id="exportCompanyModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">

        <form method="post" action="<?= base_url('admin/product/export_to_companies') ?>">

            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fa fa-building"></i> Export Margin Data
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">

                    <!-- Hidden field -->
                    <input type="hidden" name="margin_ids" id="selected_margin_ids">
                    <input type="hidden" name="exclude_company_id" value="<?= $exclude_company_id ?>">

                    <div class="alert alert-info">
                        <strong>Selected Margins:</strong>
                        <span id="selectedCount">0</span>
                    </div>

                    <h6 class="mb-2">Select Companies</h6>

                    <div class="row">
                        <?php foreach ($companies as $company): ?>
                                <?php if ($company->id == $exclude_company_id) continue; ?>
                            <div class="col-md-4">
                                <label class="border p-2 w-100 rounded">
                                    <input type="checkbox"
                                           name="company_ids[]"
                                           value="<?= $company->id ?>">
                                    <?= htmlspecialchars($company->name) ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>

                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-upload"></i> Transfer Data
                    </button>
                </div>

            </div>
        </form>

    </div>
</div>




<!-- ================= EXCEL STYLE ================= -->
<style>
/* Excel-like look */
#marginTable {
    font-family: Calibri, Arial, sans-serif;
    font-size: 13px;
    border-collapse: collapse;
}

#marginTable thead th {
    background: #f3f3f3;
    border: 1px solid #cfcfcf;
    padding: 7px;
    font-weight: 600;
}

#marginTable tbody td {
    border: 1px solid #e0e0e0;
    padding: 6px;
}

#marginTable tbody tr:hover {
    background: #eaf1fb;
}

/* Excel checkbox */
#marginTable input[type="checkbox"] {
    appearance: none;
    width: 14px;
    height: 14px;
    border: 1px solid #666;
    cursor: pointer;
    position: relative;
}

#marginTable input[type="checkbox"]:checked {
    background: #217346;
    border-color: #217346;
}

#marginTable input[type="checkbox"]:checked::after {
    content: "✔";
    color: #fff;
    font-size: 11px;
    position: absolute;
    top: -1px;
    left: 2px;
}

/* Pagination beauty */
.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: #217346 !important;
    color: #fff !important;
}
input[type="checkbox"] {
    visibility: visible;
 }
</style>

<!-- ================= jQuery SCRIPT ================= -->
<script>
$(document).ready(function () {

$('#howToUseModal').on('hidden.bs.modal', function () {
    let video = $(this).find('video').get(0);
    if (video) {
        video.pause();
        video.currentTime = 0;
    }
});


$('#exportBtn').on('click', function () {

    let marginIds = [];

    $('.row-check:checked').each(function () {
        marginIds.push($(this).val());
    });

    if (marginIds.length === 0) {
        alert('Please select at least one margin row');
        return;
    }

    $('#selected_margin_ids').val(marginIds.join(','));
    $('#selectedCount').text(marginIds.length);

    $('#exportCompanyModal').modal('show');
});


    /* Select all */
    $('#checkAll').on('change', function () {
        $('.row-check').prop('checked', this.checked);
    });

    function getSelectedIds() {
        let ids = [];
        $('.row-check:checked').each(function () {
            ids.push($(this).val());
        });
        return ids;
    }

    /* Export Excel */
    $('#exportExcel').on('click', function () {
        let ids = getSelectedIds();
        if (!ids.length) {
            alert('Please select at least one row');
            return;
        }
        window.location.href =
            "<?= site_url('admin/product/export_margin_excel') ?>?ids=" + ids.join(',');
    });

    /* Export PDF */
    $('#exportPdf').on('click', function () {
        let ids = getSelectedIds();
        if (!ids.length) {
            alert('Please select at least one row');
            return;
        }
        window.location.href =
            "<?= site_url('admin/product/export_margin_pdf') ?>?ids=" + ids.join(',');
    });

     function getSelectedIds() {
        let ids = [];
        $('.row-check:checked').each(function () {
            ids.push($(this).val());
        });
        return ids;
    }


$('#exportCsv').on('click', function () {

    let csv = [];
    let rows = [];

    // Header
    $('#marginTable thead th').each(function () {
        rows.push($(this).text().trim());
    });
    csv.push(rows.join(','));

    // Data rows
    $('#marginTable tbody tr').each(function () {

        if (!$(this).find('.row-check').is(':checked')) {
            return;
        }

        let row = [];
        $(this).find('td').each(function (i) {
            if (i === 0) return; // skip checkbox column
            row.push('"' + $(this).text().trim().replace(/"/g, '""') + '"');
        });

        csv.push(row.join(','));
    });

    if (csv.length === 1) {
        alert('Please select at least one row');
        return;
    }

    // Download CSV
    let blob = new Blob([csv.join('\n')], { type: 'text/csv;charset=utf-8;' });
    let link = document.createElement("a");
    link.href = URL.createObjectURL(blob);
    link.download = "margin_export.csv";
    link.click();
});

$('#tableSearch').on('keyup', function () {

    let value = $(this).val().toLowerCase();

    $('#marginTable tbody tr').filter(function () {
        $(this).toggle(
            $(this).text().toLowerCase().indexOf(value) > -1
        );
    });

});



});
</script>
