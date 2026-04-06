<div class="tree-container" id="extrasTree">
<?php
if (!empty($extra)) {
    render_extra_item($extra);
} else {
?>
    <div class="tree-item root" data-level="0">
        <div class="item-header">
            <input name="extras[0][name]" class="form-control" placeholder="Name">
            <input name="extras[0][code]" class="form-control" placeholder="Code">

            <select name="extras[0][value_type]" class="form-control">
                <option value="">Value Type</option>
                <?php foreach ($type_ar as $t) echo "<option>$t</option>"; ?>
            </select>

            <input name="extras[0][value]" class="form-control" placeholder="Value">

            <button type="button" class="btn btn-success btn-sm add-child">+Sub</button>
            <button type="button" class="btn btn-info btn-sm add-option">+Option</button>
        </div>
        <div class="option-list"></div>
        <div class="children"></div>
    </div>
<?php } ?>
</div>
