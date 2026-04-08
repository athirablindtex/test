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
                                        <!-- <div class="form-group form-group-default">
                                            <label>Company</label>
                                            <select class="form-control company_name" id="company_name" name="company_name">
                                                <?php //foreach ($company as $b) { ?>
                                                    <option value="<?= $b->id ?>" <?= $b->id == 9 ? 'selected' : '' ?>><?= $b->name; ?></option>
                                                <?php //} ?>
                                            </select>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 bg-white shadow-sm rounded-3 p-4 r">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <!-- Left side: Company name + How to use -->
                                    <div class="d-flex align-items-center">
                                        <!-- <h1 id="company_title" class="fw-bold  mb-0 me-2" style="letter-spacing:1px;">
                                            Blindtex DMCC
                                        </h1> -->
                                        <!-- <button class="btn btn-outline-primary btn-sm" style="margin-left:20px" type="button"
                                            data-toggle="modal" data-target="#helpScreenshotModal">
                                          <i class="fa fa-question-circle"></i> How to Use
                                        </button> -->
                                    </div>
                                    <!-- Right side: View all -->
                                   <button type="button"
        id="viewMarginSheet"
        class="btn btn-primary btn-round">
    <i class="fa fa-table me-1"></i> View All
</button>

                                </div>
                                <div class="col-sm-12">
                                    <div class="row">
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
                                            <label for="price_band">Select Extras</label>
                                            <select id="price_band" name="extra_id" class="form-control">
                                            </select>
                                        </div>
                                        <div class="row align-items-end">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="margin">Margin (%)</label>
                                                    <input type="number" id="margin" name="margin" class="form-control" placeholder="Enter margin %" step="0.1">
                                                </div>
                                            </div>
                                            <div class="col-md-6 text-end">
                                                <button type="submit" id="saveExtra" class="btn btn-lg text-white px-4 shadow-sm"
                                                    style="background-color: rgba(128, 0, 128, 1); border: none;">
                                                    <i class="fas fa-save me-2"></i> Update Margin
                                                </button>
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
        $('#saveExtra').on('click', function(e) {
            e.preventDefault(); // Prevent normal form submission
            let formData = $('#margin-form').serialize(); // Serialize all form data
            $.ajax({
                url: "saveExtraMargin", // Controller method
                type: "POST",
                data: formData,
                dataType: "json",
                beforeSend: function() {
                    $('#saveExtra').prop('disabled', true).text('Saving...');
                },
                success: function(response) {
         
                    $('#saveExtra').prop('disabled', false).text('Update Margin');
                    if (response.status) {
                        let newMargin=response.margin;
                   
                        let $selected=$('#price_band option:selected');
                                        $selected
                                        .attr('data-margin', newMargin)   
                                        .data('margin', newMargin);        



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
       $('#product_type').on('change', function () {
    const productTypeId = $(this).val();

    $('#price_band').html('<option value="">-- Select Extra --</option>');
    $('#margin').val('');

    if (!productTypeId) return;

    $.ajax({
        url: '<?= base_url("admin/extras/getextras") ?>',
        type: 'POST',
        dataType: 'json',
        data: {
            product_type_id: productTypeId
        },
        success: function (res) {
            if (res.status && res.data.length) {
                res.data.forEach(item => {
                    $('#price_band').append(
                        `<option value="${item.id}" data-margin="${item.margin}">${item.name}</option>`
                    );
                });
            }
        }
    });
});
$('#price_band').on('change', function () {
    const selected = $(this).find(':selected');
    const margin = selected.data('margin');


    if (margin !== undefined && margin !== '') {
        $('#margin').val(margin);
    } else {
        $('#margin').val('');
    }
});


      
        // 🔹 Margin Apply Logic
        $('#margin').on('input', function() {
            //applyMarginCalculation($(this).val());
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
            url: "<?= base_url('admin/extras/get_margin_excel_data') ?>",
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