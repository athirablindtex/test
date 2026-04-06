<style>
    body {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 11px;
        color: #333;
    }

    h3 {
        margin-bottom: 10px;
        text-align: center;
    }

    table {
        border-collapse: collapse;
        width: 100%;
    }

    th {
        background: #f2f2f2;
        font-weight: bold;
        text-align: left;
    }

    th, td {
        border: 1px solid #555;
        padding:2px;
        vertical-align: top;
        word-wrap: break-word;
    }

    tr {
        page-break-inside: avoid;
    }

    /* 🔥 ADD THIS */
    .page-break {
        page-break-after: always;
    }
</style>


<h3>Product List</h3>

<table>
    <thead>
        <tr>
            <th width="5%">ID</th>
            <th width="18%">Name</th>
            <th width="12%">Fabric Code</th>
            <th width="15%">Product Type</th>
            <th width="15%">Vendor</th>
            <th width="12%">Price Band Type</th>
            <th width="15%">Price Band</th>
            <th width="8%">Turnable</th>
        </tr>
    </thead>
   <tbody>
<?php if (!empty($products)): $i = $start; ?>
    <?php foreach ($products as $p): ?>
        <tr>
            <td><?= $i ?></td>
            <td><?= htmlspecialchars($p['name']) ?></td>
            <td><?= htmlspecialchars($p['fabric_code']) ?></td>
            <td><?= htmlspecialchars($p['product_type_name']) ?></td>
            <td><?= htmlspecialchars($p['sub_product_type_name']) ?></td>
            <td><?= htmlspecialchars($p['price_band_type']) ?></td>
            <td><?= htmlspecialchars($p['priceband_name']) ?></td>
            <td><?= htmlspecialchars($p['turnable']) ?></td>
        </tr>
    <?php $i++; endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="8" align="center">No products found</td>
    </tr>
<?php endif; ?>
</tbody>
</table>
