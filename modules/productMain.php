<?php
require '../base.php';
// ----------------------------------------------------------------------------

auth('Admin');
$_title = 'Product Maintenance';

$fields = [
    '',
    'id'            => 'Id',
    'name'          => 'Name',
    'quantity'      => 'Quantity',
    'price'         => 'Price',
    'photo'         => 'Photo',
    'availability'  => 'Availability',
    ''
];

$sort = req('sort');
key_exists($sort, $fields) || $sort = 'id';

$dir = req('dir');
in_array($dir, ['asc', 'desc']) || $dir = 'asc';

$page = req('page', 1);

$search_field = req('search_field');
$search_value = req('search_value');

$where_clause = '';
if ($search_field && $search_value) {
    $where_clause = "WHERE $search_field LIKE '%$search_value%'";
}

require_once '../lib/SimplePager.php';
$p = new SimplePager("SELECT * FROM Product $where_clause ORDER BY $sort $dir", [], 10, $page);
$arr = $p->result;

// ----------------------------------------------------------------------------
$_title = 'Product Maintenance';
include '../header.php';
?>

<form method="post" class="form">
    <label for="search_field">Search by:</label>
    <select name="search_field" id="search_field">
        <option value="id" <?= $search_field == 'id' ? 'selected' : '' ?>>Id</option>
        <option value="name" <?= $search_field == 'name' ? 'selected' : '' ?>>Name</option>
        <option value="price" <?= $search_field == 'price' ? 'selected' : '' ?>>Price</option>
        <option value="photo" <?= $search_field == 'photo' ? 'selected' : '' ?>>Photo</option>
    </select>
    <input type="text" name="search_value" value="<?= htmlspecialchars($search_value) ?>">
    <button type="submit">Search</button>
</form>

<p class="pagination">
    <?= $p->count ?> of <?= $p->item_count ?> record(s) |
    Page <?= $p->page ?> of <?= $p->page_count ?>
    <br>
    <button data-get="/modules/productAdd.php">Add Product</button>
</p>

<div class="batch">
    <label for="selectAll">Select All</label>
    <input type="checkbox" id="select-all-product">
    <form id="batch-actions-form" method="post">
        <button data-post="/modules/batchProductMain.php" id="batch-activate">Batch Activate</button>
        <button data-post="/modules/batchProductMain.php" id="batch-disable">Batch Disable</button>
        <button data-post="/modules/batchProductMain.php" id="batch-add-quantity">Add Quantities</button>
    </form>
</div>

<table class="stock-table">
    <tr>
        <?= table_headers($fields, $sort, $dir, "page=$page") ?>
    </tr>

    <?php foreach ($arr as $s): ?>
    <tr>
        <td><input type="checkbox" name="productSelected[]" value="<?= $s->id ?>"></td>
        <td><?= $s->id ?></td>
        <td><?= $s->name ?></td>
        <td>
            <?php if ($s->quantity >= 100):?>
                <div style="color: #57E964;"><?= $s->quantity ?></div>
            <?php elseif ($s->quantity >=50 && $s->quantity < 100):?>
                <div style="color: #FFFF00;"><?= $s->quantity ?></div>
            <?php elseif ($s->quantity >= 20 && $s->quantity < 50):?>
                <div style="color: #FFA500;"><?= $s->quantity ?></div>
            <?php else:?>
                <div style="color: #FF0000;"><?= $s->quantity ?></div>
            <?php endif;?>
        </td>
        <td>RM<?= $s->price ?></td>
        <td><img src="/images/<?= $s->photo ?>"></td>
        <td>
            <?php if ($s->availability == 1):?>
                <div style="color: #57E964;"><?= '&#10004' ?></div>
            <?php else:?>
                <div style="color: #FF0000;"><?= '&#10006'?></div>
            <?php endif;?>
        </td>
        <td>
            <button class="popup" data-name="<?= $s->id ?>">Detail</button>
            <button data-get="productUpdate.php?id=<?= $s->id ?>">Update</button>
            <button data-post="productAvailability.php?id=<?= $s->id ?>">
                <?= $s->availability == 1? 'Disable' : 'Active'?>
            </button>
        </td>
    </tr>
    <?php endforeach ?>
</table>

<!-- Product Description Popup -->
<div id="product-description-popup" class="product-popup">
    <div class="popup-content">
        <span class="close-popup">&times;</span>
        <h3 id="popup-product-name"></h3>
        <div id="popup-product-image"></div>
        <p id="popup-product-description"></p>
        <div class="popup-price-container">
            <span id="popup-product-price"></span>
            <button id="popup-close-description" class="close-description-btn">
                Close Description
            </button>
        </div>
    </div>
</div>

<br>

<?= $p->html("sort=$sort&dir=$dir") ?>

<script src="../js/app.js"></script>

<?php
include '../footer.php';