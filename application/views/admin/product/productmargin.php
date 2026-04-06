<?php $band_type = array('Square Meter', 'Meter', 'Matrix'); ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">






<!-- Modal Import -->
<div class="modal fade" id="importModal" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="card-title" id="importModalLabel">Change for <span id="change-product-name" style="font-weight:bolder;"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="margin-form2">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group form-group-default">
                                <div class="col-md-12">
                                    <div class="col-md-6">
                                        <strong>Margin</strong>
                                    </div>
                                    <div class="col-md-6 form-group form-group-default">
                                        <input type="text" name="margin_value" id="margin2" class="form-control" />
                                        <input type="hidden" id="product-id" value="">
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" id="update-btn" class="btn btn-primary update-btn" data-dismiss="modal" data-type="2">Update</button>
                </div>
            </form>

        </div>
    </div>
</div>
<!-- Modal Import -->

<style>
    #company_title {
        font-size: 20px;
        font-weight: 600;
        color: #343a40;
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
                <?php $company_id = $this->input->get('company_id') ? $this->input->get('company_id') : 9; ?>
                <?php $search = $this->input->get('search') ? $this->input->get('search') : ''; ?>
                <div class="col-md-12">

                    <div class="card">

                        <div class="card-body">

                            <div class="row">

                                <div class="col-md-12">
                                    <div class="form-group form-group-default">
                                        <label>Company</label>
                                      <?php $company_id = $this->input->get('company_id') ? $this->input->get('company_id') : 9; ?>

<select class="form-control company_name" id="company_name" name="company_name">
    <?php foreach ($company as $b) { ?>
        <option value="<?= $b->id ?>" <?= $b->id == $company_id ? 'selected' : '' ?>>
            <?= $b->name; ?>
        </option>
    <?php } ?>
</select>

                                    </div>
                                </div>





                            </div>



                        </div>
                        <?php
$selected_company_name = '';
foreach ($company as $b) {
    if ($b->id == $company_id) {
        $selected_company_name = $b->name;
        break;
    }
}
?>
                        
                        <div class="col-sm-12 bg-white shadow-sm rounded-3 p-4 r">
                            <!-- Header -->
                        <h1 id="company_title" class="fw-bold text-center text-primary mb-4" style="letter-spacing: 1px;">
    <?= $selected_company_name ?>
</h1>




                            <!-- Loader -->
                            <div id="loader" style="display: none; text-align: center; margin-top: 10px;">
                                <div class="spinner-border text-primary" role="status" style="width: 2.5rem; height: 2.5rem;">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>




                            <!-- Tab Contents -->
                            <div class="tab-content bg-white rounded p-4 shadow-sm" id="marginTabsContent">




                                <!-- Products Tab -->
                                <div>

                                    <h3 style="padding:20px 10px">Product Margin Management</h3>

                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">
                                                <i class="fa fa-search search-icon"></i>
                                            </span>
                                        </div>
                                        <input type="text" name="tag" class="form-control rounded-0"
                                            id="searchInput" placeholder="Search by name" value="<?= $search ?>"
                                            aria-label="Search" aria-describedby="basic-addon1">
                                        <button class="btn btn-primary rounded-0">Search</button>
                                    </div>

                                    <?php


                                    if (@$tabledata) { ?>
                                        <div class="table-responsive">
                                         <div class="card shadow-sm mb-3">
    <div class="card-body py-2">
        <div class="d-flex justify-content-between align-items-center">
            
            <h6 class="mb-0 text-muted">
                <span class="text-primary font-weight-bold">
                    Total Products: <?= $count ?>
                </span>
            </h6>

            <a href="<?= base_url('admin/product/productmargin_export?search=' . urlencode($this->input->get('search'))) ?>"
               class="btn btn-success btn-sm" id="exportBtn">
                Export CSV
            </a>

        </div>
    </div>
</div>



                                            <table id="basic-datatables" class="display table table-hover table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Name</th>
                                                        <th>Type</th>
                                                        <th>Collection</th>
                                                        <th>Priceband</th>

                                                        <th>Margin</th>

                                                        <th style="width: 10%">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $fabric_id_arr = array();

                                                    foreach ($tabledata as $product) {
                                                        $fabric_id_arr[] = @$product['id'];
                                                    ?>
                                                        <tr>
                                                            <td id="id-<?= @$product['id'] ?>"><?= @$product['id']; ?></td>
                                                            <td id="name-<?= @$product['id'] ?>"><?= @$product['name']; ?></td>
                                                            <td id="type-<?= @$product['id'] ?>">
                                                                <?= @$product['product_type_name']; ?>
                                                                <input type="hidden"
                                                                    id="company-config-<?= @$product['id'] ?>"
                                                                    value='<?= @$product["company_config"] ?>'>
                                                            </td>
                                                            <td id="collection-<?= @$product['id'] ?>"><?= @$product['sub_product_type_name']; ?></td>
                                                            <td id="priceband-<?= @$product['priceband_name'] ?>"><?= @$product['priceband_name']; ?></td>




                                                            <td id="margin-<?= @$product['margin_value'] ?>"> <?= @$product['margin_value']; ?></td>

                                                            <td id="action-<?= @$product['id'] ?>">
                                                                <button type="button"
                                                                    data-id='<?= @$product['id'] ?>'
                                                                    class='changebtn-cls btn btn-sm btn-success d-flex align-items-center gap-1'
                                                                    data-type="2"
                                                                    data-toggle='modal'
                                                                    data-target='#importModal'>
                                                                    <i class="bi bi-pencil-square"></i> Change
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                            <?= $pagination_links ?>
                                        </div>
                                    <?php } else { ?>
                                        <div class="alert alert-warning">No product data found.</div>
                                    <?php } ?>
                                </div>
                            </div>




                        </div>
                    </div>


                    <hr />

                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>




</body>

</html>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialize Select2
        $('#target_company').select2({
            placeholder: "Select one or more companies",
            allowClear: true
        });
        $('#target_company1').select2({
            placeholder: "Select one or more companies",
            allowClear: true,
            width: 'resolve'
        });





        // Handle tab click
        $('#marginTabs a[data-toggle="tab"]').on('click', function(e) {
            e.preventDefault();

            // Remove all active classes
            $('#marginTabs a').removeClass('active');
            $('.tab-pane').removeClass('show active');

            // Add active to clicked tab
            $(this).addClass('active');
            const target = $(this).attr('href');
            $(target).addClass('show active');

            // Get selected company ID
            let company_id = $('#company_name').val();

            // If no company selected, stop here
            if (!company_id) {
                alert('Please select a company first.');
                return;
            }


        });

        // Dropdown change event
        $('#company_name2').on('change', function() {
            let selectedCompany = $("#company_name option:selected").text();
            let company_id = $(this).val();




            // Show loader while processing
            $('#loader').show();

            setTimeout(function() {
                $('#company_title').text(selectedCompany);
                $('#margin1, #product-type, #sub-type, #extra-type, #sub-extra, #margin2, #margin3').val('');
                $('#loader').hide();
            }, 1000);
        });

        // Show the first tab (Product Type) by default
        $('#marginTabs a:first').addClass('active');
        $('.tab-pane:first').addClass('show active');
    });
$('#company_name').on('change', function () {

    let company_id = $(this).val();
    let search = $('#searchInput').val();

    window.location.href =
        '<?= base_url("admin/product/productmargin") ?>?company_id='
        + company_id + '&search=' + search;
});

    let company_id = $('#company_name').val();
    $('body').on('change', '.view-stype', function() {
        let value = $('.view-stype:checked').val();


        if (value) {
            $('#sub-type-wrapper').show();
            $('.view-stype').val(1);


        } else {

            $('#sub-type-wrapper').hide();
            $('.view-stype').val(0);

        }
    });
    $('body').on('change', '.view-extra', function() {
        let value = $('.view-extra:checked').val();


        if (value) {
            $('#extra-type-wrapper').show();
            $('.view-extra').val(1);


        } else {

            $('#extra-type-wrapper').hide();
            $('.view-extra').val(0);

        }
    });
    $('body').on('change', '.sub-type', function(e) {
        let id = $('#sub-type').val();
        margin_change(1, id, company_id)

    });



    $('body').on('click', '.update-btn', function(e) {
        e.preventDefault();

        let type = $(this).data('type');

        let company_id = $('#company_name').val();
        let product_id = 0;
        let type_id = 0;
        let product_type = 0;
        let sub_type = 0;
        let type_check = $('.view-stype').val();
        if (type == 2) {
            product_id = $('#product-id').val();
        }
        if (type == 1) {
            product_type = $('#product-type').val();
            sub_type = $('#sub-type').val();

        }
        let formData = $('#margin-form' + type).serialize();
        let formDataObject = formData.split('&').reduce(function(result, item) {
            const parts = item.split('=');
            result[decodeURIComponent(parts[0])] = decodeURIComponent(parts[1]);
            return result;
        }, {});

        $.ajax({
            url: 'updateMarginFabric', // Replace with your correct URL
            type: 'POST',
            data: {
                company_id: company_id,
                type: type,
                product_id: product_id,
                product_type: product_type,
                sub_type: sub_type,
                type_check: type_check,
                ...formDataObject // Append serialized form data

            }, // Send parent_id as POST data
            dataType: 'json', // Expect JSON response
            success: function(response) {
                alert("updated");
                location.reload();

            },
            error: function(xhr, status, error) {
                console.error("AJAX error: " + error); // Handle any errors in the request
            }
        });
    });

    $(document).on('click', '.changebtn-cls', function() {

        let p_id = $(this).data('id');
        $('#product-id').val(p_id);
        company_id = $('#company_name').val();
        margin_change(2, p_id, company_id)
    });

    function margin_change(type, id, company_id) {
        company_id = $('#company_name').val();

        $.ajax({
            url: 'get_margin_value', // Replace with your correct URL
            type: 'POST',
            data: {
                type: type,
                id: id,
                company_id: company_id


            }, // Send parent_id as POST data
            dataType: 'json', // Expect JSON responsed
            success: function(response) {
                $('#margin' + type).val(response.margin)


            },
        });
    }


    $('#searchInput').on('keyup', function() {
        company_id = $('#company_name').val();
        var searchTerm = $(this).val();
        window.location.href = '<?= base_url() ?>admin/product/productmargin?company_id=' + company_id + '&search=' + searchTerm;



    });

    function updateExportUrl() {
    var company_id = $('#company_name').val();
    var search = $('input[name="search"]').val() || '';

    var url = "<?= base_url('admin/product/productmargin_export') ?>?search=" + encodeURIComponent(search) + "&company_id=" + company_id;

    $('#exportBtn').attr('href', url);
}

/* run when dropdown changes */
$('#company_name').change(function () {
    updateExportUrl();
});

/* run on page load */
$(document).ready(function () {
    updateExportUrl();
});
</script>