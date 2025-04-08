<?php
require '../base.php';
// ----------------------------------------------------------------------------

auth('Admin');
$_title = 'Product Maintenance';

$fields = [
    'id'         => 'Id',
    'name'       => 'Name',
    'category'     => 'Category',
    'price' => 'Price',
    'photo' => 'Photo',
    ''
];

$sort = req('sort');
key_exists($sort, $fields) || $sort = 'id';

$dir = req('dir');
in_array($dir, ['asc', 'desc']) || $dir = 'asc';

$page = req('page', 1);

require_once '../lib/SimplePager.php';
$p = new SimplePager("SELECT * FROM Product ORDER BY $sort $dir", [], 10, $page);
$arr = $p->result;

// ----------------------------------------------------------------------------
$_title = 'Member Maintenance';
include '../header.php';
?>

<p class="pagination">
    <?= $p->count ?> of <?= $p->item_count ?> record(s) |
    Page <?= $p->page ?> of <?= $p->page_count ?>
</p>

<table class="table">
    <tr>
        <?= table_headers($fields, $sort, $dir, "page=$page") ?>
    </tr>

    <?php foreach ($arr as $s): ?>
    <tr>
        <td><?= $s->id ?></td>
        <td><?= $s->name ?></td>
        <td><?= $s->category ?></td>
        <td>RM<?= $s->price ?></td>
        <td><img src="/images/<?= $s->photo ?>"></td>
        <td>
            <button data-get="update.php?id=<?= $s->id ?>">Update</button>
            <button data-post="delete.php?id=<?= $s->id ?>">Delete</button>
        </td>
    </tr>
    <?php endforeach ?>
</table>

<br>

<?= $p->html("sort=$sort&dir=$dir") ?>

<?php
include '../footer.php';