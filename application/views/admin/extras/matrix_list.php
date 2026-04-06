<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/handsontable@14.3.0/dist/handsontable.full.min.css">

<script src="https://cdn.jsdelivr.net/npm/handsontable@14.3.0/dist/handsontable.full.min.js"></script>
<style>
   #matrix {
      width: 100%;
      height: 600px;
      overflow: auto;
   }
     .ht-bold {
      font-weight: 700 !important;
  }
</style>

<div class="main-panel">
    <div class="content">
        <div class="page-inner">

            <div class="page-header">
                <h4 class="page-title">Matrix Extras</h4>
                <a href="<?php echo base_url('admin/extras/list'); ?>" class="btn btn-secondary btn-sm">
        ← Back
    </a>
            </div>

            <div class="row">
                <div class="col-md-12 new-form form-type">

                    <div class="row mt-4">
                        <div id="matrix"></div>
                        <input type="hidden" name="matrix_data" id="matrix_data">
                    </div>
                    <div class="text-center mt-3 mb-5">
                        <button id="saveBtn" class="btn btn-primary">Save Changes</button>
</div>

                </div>
            </div>

        </div>
    </div>
</div>


   <script>
      $(document).ready(function() {
         let extras_id = <?= $this->uri->segment(4) ?? 0 ?>;

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
         $.get("<?= site_url('admin/extras/get_price_matrix') ?>", {
            id: extras_id
         }, function(res) {
            let data = JSON.parse(res);
            let heights = data.heights || [];
            let widths = data.widths || [];
            let matrix = data.matrix || [];

            let tableData = [];
            let header = [' '];
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
    
    cells: function (row, col) {
        const cellProperties = {};

        if (row === 0 || col === 0) {
            cellProperties.className = 'ht-bold';
        }

        return cellProperties;
           },
               afterChange: function(changes, source) {
                  if (source !== 'loadData') {
                     updateHiddenInput();
                  }
               }
            });

            updateHiddenInput();
         });

          $("#saveBtn").click(function() {

             if (hot.getActiveEditor()) {
        hot.getActiveEditor().finishEditing();
    }

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
            url: "<?= base_url('admin/extras/update_matrix'); ?>", // your CI URL
            type: "POST",
            data: {
                extras_id: extras_id,
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
   </script>