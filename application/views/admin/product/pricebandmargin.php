<?php $band_type = array('Square Meter', 'Meter', 'Matrix'); ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link rel="stylesheet" href="<?= base_url('assets/admin/css/handsontable.full.min.css') ?>">
<script src="<?= base_url('assets/admin/js/handsontable.full.min.js') ?>"></script>
<style>
    .form-control[readonly] {
        background-color: #fff !important;
        color: #000 !important;
        cursor: not-allowed;
        font-size: 20px !important
    }
input[type="checkbox"] {
    visibility: visible !important;
}
    #company_title {
        font-size: 20px;
        font-weight: 600;
        color: #000000;
        text-transform: uppercase;
        margin-bottom: 20px;
    }

    #loader {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(255, 255, 255, 0.7);
        z-index: 1050;
        /* Higher than Bootstrap modals */
    }

    .loader-overlay {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .spinner-border {
        width: 2rem;
        height: 2rem;
        color: #007bff;
    }

    /* Custom Bootstrap-style enhancements */
    .btn-gradient-primary {
        background: linear-gradient(135deg, rgba(128, 0, 128, 0.9), rgba(70, 0, 140, 0.9));
        color: #fff;
        border: none;
        box-shadow: 0 4px 10px rgba(128, 0, 128, 0.2);
        transition: all 0.3s ease;
        margin-right: 20px;
    }

    /* Card-like section styling */
    .col-sm-12.bg-white {
        background-color: #fff;
    }

    /* Tabs */
    .nav-tabs .nav-link.active {
        border-color: transparent transparent #6f42c1 transparent;
        border-bottom: 3px solid #6f42c1;
        color: #6f42c1 !important;
    }

    .nav-tabs .nav-link {
        color: #555;
        transition: all 0.2s;
    }

    .nav-tabs .nav-link:hover {
        color: #6f42c1;
    }

    /* Header */
    #company_title {
        font-size: 1.8rem;
        text-transform: uppercase;
    }

    /* Loader */
    #loader .spinner-border {
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        100% {
            transform: rotate(360deg);
        }
    }

    /* Excel-style full modal */
    .modal-excel {
        max-width: 95vw !important;
        width: 95vw !important;
    }

    #marginListModal .modal-content {
        height: 90vh;
    }

    #marginListModal .modal-body {
        height: calc(90vh - 60px);
        overflow: hidden;
    }

    #marginExcel {
        height: 100%;
        width: 100%;
    }

    #margin-form {
        width: 100%;
    }
</style>
<div class="main-panel">
    <div class="content">
        <div class="page-inner">
            <div class="page-header">
                <h4 class="page-title"><?= @$page; ?></h4>
                <div class="btn-group btn-group-page-header ml-auto">
                    <?php if (@$permissions['add']) { ?>
                        <button class="btn btn-secondary btn-round mr-2" data-toggle="modal" data-target="#importModal" id="ImportBtn">
                            <i class="fa fa-file-excel"></i>
                            Import
                        </button>
                        <button type="button" class="btn btn-success btn-round <?= @$edit == 0 ? 'collapsed' : ((@validation_errors() ? '' : 'collapsed')); ?>" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                            <i class="fa fa-plus mr-2"></i> Add <?= @$page; ?>
                        </button>
                    <?php } ?>
                </div>
            </div>
            <div class="row">
                <form id="margin-form">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group form-group-default">
                                            <label>Company</label>
                                            <select class="form-control company_name" id="company_name" name="company_name">
                                                <option value="">-- Select Company --</option>
                                                <?php foreach ($company as $b) { ?>
                                                    <option value="<?= $b->id ?>" <?= $b->id == $this->input->get('company_id') ? 'selected' : '' ?>><?= $b->name; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 bg-white shadow-sm rounded-3 p-4 r">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <!-- Left side: Company name + How to use -->
                                    <div class="d-flex align-items-center">
                                        <h1 id="company_title" class="fw-bold  mb-0 me-2" style="letter-spacing:1px;">
                                                <?= $company_name ? strtoupper($company_name) : 'SELECT A COMPANY'; ?>
                                        </h1>
                                        <button class="btn btn-outline-primary btn-sm" style="margin-left:20px" type="button"
                                            data-toggle="modal" data-target="#helpScreenshotModal">
                                            <i class="fa fa-question-circle"></i> How to Use
                                        </button>
                                    </div>
                                    <!-- Right side: View all -->
                                    <a href="<?= base_url('admin/product/margin_view_all?company_id=9') ?>"
                                        id="company_link"
                                        class="btn btn-primary">
                                        View All
                                    </a>
                                </div>
                                <div class="col-sm-12">
                                    <div class="row">
                                        <!-- Product Type Dropdown -->
                                        <div class="form-group col-md-6">
                                            <label for="product_type">Select Product Type</label>
                                            <select id="product_type" name="product_type" class="form-control">
                                                <option value="">-- Select Product Type --</option>
                                                <?php foreach ($product_types as $type): ?>
                                                    <option value="<?= $type->id ?>"><?= htmlspecialchars($type->name) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <!-- Price Band Dropdown -->
                                        <div class="form-group col-md-6">
                                            <label for="price_band">Select Price Band</label>
                                            <select id="price_band" name="price_band" class="form-control">
                                            </select>
                                        </div>
                                        <div class="row align-items-end">

                                            <!-- Margin input -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="margin">Margin (%)</label>

                                                   <div class="form-check mb-3 p-3 rounded"
         style="background:#f6e9f6; border:1px solid rgba(128,0,128,0.35); max-width: 260px;">
        <input
            class="form-check-input"
            type="checkbox"
            id="apply_to_all"
            name="apply_to_all"
            value="1"
            style="transform: scale(1.3); cursor:pointer;"
        >
        <label
            class="form-check-label fw-bold ms-2"
            for="apply_to_all"
            style="cursor:pointer; font-size:14px;"
        >
            Apply to all items
        </label>
    </div>


                                                    <input type="number"
                                                        id="margin"
                                                        name="margin"
                                                        class="form-control"
                                                        placeholder="Enter margin %"
                                                        step="0.1">
                                                </div>
                                            </div>

                                            <!-- Submit button -->
                                            <div class="col-md-6 text-end">
                                                <button type="submit"
                                                    id="savePriceBand"
                                                    class="btn btn-lg text-white px-4 shadow-sm"
                                                    style="background-color: rgba(128, 0, 128, 1); border: none;">
                                                    <i class="fas fa-save me-2"></i> Update Margin
                                                </button>
                                            </div>

                                        </div>
                                        <!-- Common Container -->
                                        <div class="col-md-12 p-3 mt-3" style="background-color: #f8f9fa; border: 1px solid #ddd; border-radius: 8px;">
                                            <!-- Square Meter Section -->
                                            <div id="square-meter-section" style="display: none;">
                                                <h3 style="font-size:18px; font-weight:bold; margin-bottom:15px;">Square Meter Price Band</h3>
                                                <div class="row">
                                                    <div class="form-group col-md-6">
                                                        <label for="min_unit">Min Unit</label>
                                                        <input type="number" id="min_unit" name="min_unit" class="form-control" readonly>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="unit_price">Unit Price</label>
                                                        <input type="number" id="unit_price" name="unit_price" class="form-control" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Matrix Section -->
                                            <div id="matrix-section" style="display: none; margin-top: 20px;">
                                                <h3 style="font-size:18px; font-weight:bold;">Matrix Price Band</h3>
                                                <div id="matrix"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>
</body>

</html>
<div class="modal fade" id="marginListModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-excel">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Updated Margin Sheet</h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="marginExcel" style="height:600px;"></div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="helpScreenshotModal" tabindex="-1">
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
                    <source src="<?= base_url('assets/help/priceband-margin-updation.mp4') ?>" type="video/mp4">
                    Your browser does not support the video.
                </video>
                <!-- STEPS -->
                <ol class="small text-muted">
                    <li>Select the <b>Company</b></li>
                    <li>Select the <b>Product Type</b></li>
                    <li>Select the <b>Price Band</b></li>
                    <li>Update the <b>Margin</b> value in the input field</li>
                    <li>
                        When the margin is changed, the <b>Unit Price</b> will update automatically
                        <br>
                        <span class="text-danger">
                            Note: Unit Price is auto-calculated and <b>cannot be edited manually</b>
                        </span>
                    </li>
                </ol>
            </div>
        </div>
    </div>
</div>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#viewMarginSheet').on('click', function() {
            loadMarginExcelSheet();
        });
        $('#savePriceBand').on('click', function(e) {
            e.preventDefault(); // Prevent normal form submission
            let formData = $('#margin-form').serialize(); // Serialize all form data
            $.ajax({
                url: "savePricebandMargin", // Controller method
                type: "POST",
                data: formData,
                dataType: "json",
                beforeSend: function() {
                    $('#savePriceBand').prop('disabled', true).text('Saving...');
                },
                success: function(response) {
                    $('#savePriceBand').prop('disabled', false).text('Update Margin');
                    if (response.status === 'success') {
                        alert('Margin updated successfully!');
                    } else {
                        alert(response.message || 'Something went wrong.');
                    }
                },
                error: function() {
                    $('#savePriceBand').prop('disabled', false).text('Update Margin');
                    alert('Error while saving data.');
                }
            });
        });
        $('#target_company, #target_company1').select2({
            placeholder: "Select one or more companies",
            allowClear: true,
            width: 'resolve'
        });
        // Dropdown change event
        $('#company_name').on('change', function() {
            let selectedCompany = $("#company_name option:selected").text();
            let company_id = $(this).val();
            $('#company_link').attr('href', '<?= base_url("admin/product/margin_view_all") ?>?company_id=' + company_id);
            // Show loader while processing
            $('#loader').show();
            setTimeout(function() {
                $('#company_title').text(selectedCompany);
                $('#margin1, #product-type, #sub-type, #extra-type, #sub-extra, #margin2, #margin3').val('');
                $('#loader').hide();
                window.location.href = '<?= base_url("admin/product/pricebandmargin") ?>?company_id=' + company_id;
            }, 1000);
        });

        function load_product_types(company_id) {
            $.ajax({
                url: "get_company_product_types",
                type: "POST",
                data: {
                    company_id: company_id
                },
                beforeSend: function() {
                    $('#productTypeTable').html('<p>Loading...</p>');
                },
                success: function(response) {
                    $('#productTypeTable').html(response);
                    $('#productTypeSection').show();
                    $('#target_company').select2();
                }
            });
        }
        $('#product_type').on('change', function() {
            $('#margin').val('');
            const productTypeId = $(this).val();
            $('#matrix-section, #square-meter-section').hide();
            if (productTypeId) {
                $.ajax({
                    url: '<?= base_url("admin/product/getPricebandByType") ?>',
                    type: 'POST',
                    data: {
                        type_id: productTypeId
                    },
                    dataType: 'json',
                    success: function(response) {
                        const $priceBand = $('#price_band');
                        $priceBand.empty().append('<option value="">-- Select Price Band --</option>');
                        if (response.data && response.data.length > 0) {
                            $.each(response.data, function(index, item) {
                                $priceBand.append(
                                    `<option value="${item.id}" 
                                    data-type="${item.type || ''}" 
                                    data-min="${item.min_unit || ''}" 
                                    data-price="${item.unit_price || ''}">
                                    ${item.name}
                                </option>`
                                );
                            });
                        } else {
                            $priceBand.append('<option value="">No price bands found</option>');
                        }
                    },
                    error: function() {
                        alert('Error fetching price bands');
                    }
                });
            } else {
                $('#price_band').empty();
            }
        });

        function getmargin(product_type, price_band, company_id) {
            $.ajax({
                url: '<?= base_url("admin/product/get_priceband_margin_value") ?>',
                type: 'POST',
                data: {
                    product_type: product_type,
                    price_band: price_band,
                    company_id: company_id
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#margin').val(response.margin);
                        let marginValue = response.margin;
                        applyMarginCalculation(marginValue);
                    } else {
                        $('#margin').val('');
                    }
                },
                error: function() {
                    alert('Error fetching margin');
                }
            });
        }
        // 🔹 Margin Apply Logic
        $('#margin').on('input', function() {
            applyMarginCalculation($(this).val());
        });
        let hot;
        let originalTableData = [];
        let matrixReady = false;
        let tableLoaded = false;
        $('#price_band').on('change', function() {
            let producT_type = $('#product_type').val();
            let price_band = $(this).val();
            let company_id = $('#company_name').val();
            $('#margin').val('');
            getmargin(producT_type, price_band, company_id);
            $('#matrix-section, #square-meter-section').hide();
            const selected = $(this).find('option:selected');
            const value = selected.val();
            const type = selected.data('type');
            const min = selected.data('min');
            const price = selected.data('price');
            $('#min_unit').val(min || '');
            $('#unit_price').val(price || '');
            if (type === 'Square Meter') {
                $('#square-meter-section').show();
                return;
            }
            if (type === 'Matrix') {
                $('#matrix-section').show();
                const container = document.getElementById('matrix');
                if (window.hot instanceof Handsontable) {
                    window.hot.destroy();
                }
                $('#matrix').html('<p style="padding:10px;color:#555;">Loading matrix data...</p>');
                $.get("<?= site_url('admin/priceband/get_price_matrix') ?>", {
                    id: value
                }, function(res) {
                    const data = JSON.parse(res);
                    const heights = data.heights || [];
                    const widths = data.widths || [];
                    const matrix = data.matrix || [];
                    const tableData = [];
                    const header = [' '];
                    widths.forEach(w => header.push(w));
                    tableData.push(header);
                    matrix.forEach(row => {
                        const temp = [row.height];
                        widths.forEach(w => temp.push(row[w] || ''));
                        tableData.push(temp);
                    });
                    originalTableData = JSON.parse(JSON.stringify(tableData));
                    tableLoaded = true;
                    const colWidths = [100];
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
                        readOnly: true,
                        licenseKey: 'non-commercial-and-evaluation',
                        contextMenu: true
                    });
                    matrixReady = true;
                    const marginVal = parseFloat($('#margin').val());
                    if (marginVal) {
                        applyMarginCalculation(marginVal);
                    }
                });
            }
        });

        function applyMarginCalculation(margin) {
            margin = parseFloat(margin) || 0;
            const selected = $('#price_band').find('option:selected');
            const type = selected.data('type');
            const price = selected.data('price');
            if (!type) return;
            /* -------- Square Meter -------- */
            if (type === 'Square Meter') {
                const base = parseFloat(price);
                if (!isNaN(base)) {
                    const newPrice = base + (base * (margin / 100));
                    $('#unit_price').val(newPrice.toFixed(2));
                }
                return;
            }
            console.log({
                type: type,
                originalLength: originalTableData.length,
                hotExists: !!window.hot
            });
            /* -------- Matrix -------- */
            if (type === 'Matrix' && matrixReady && originalTableData.length && typeof hot !== 'undefined') {
                const updated = JSON.parse(JSON.stringify(originalTableData));
                for (let r = 1; r < updated.length; r++) {
                    for (let c = 1; c < updated[r].length; c++) {
                        const val = parseFloat(updated[r][c]);
                        if (!isNaN(val)) {
                            const newVal = val + (val * (margin / 100));
                            updated[r][c] = newVal.toFixed(2);
                        }
                    }
                }
                hot.loadData(updated);
            }
        }
    });

    function loadMarginExcelSheet() {
        $.ajax({
            url: "<?= base_url('admin/product/get_margin_excel_data') ?>",
            type: "GET",
            dataType: "json",
            success: function(res) {
                const container = document.getElementById('marginExcel');
                if (window.marginHot instanceof Handsontable) {
                    window.marginHot.destroy();
                }
                window.marginHot = new Handsontable(container, {
                    data: res.data,
                    colHeaders: res.headers,
                    rowHeaders: true,
                    stretchH: 'all',
                    height: 600,
                    readOnly: true,
                    manualColumnResize: true,
                    manualRowResize: true,
                    filters: true,
                    dropdownMenu: true,
                    contextMenu: true,
                    licenseKey: 'non-commercial-and-evaluation'
                });
                $('#marginListModal').modal('show');
            }
        });
    }
    $('#helpScreenshotModal').on('hidden.bs.modal', function() {
        let video = $(this).find('video').get(0);
        if (video) {
            video.pause();
            video.currentTime = 0;
        }
    });
</script>