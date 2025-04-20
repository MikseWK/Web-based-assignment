<?php
require '../base.php';
// ----------------------------------------------------------------------------

auth('Admin');
$_title = 'Member Maintenance';

$fields = [
    'id'         => 'Id',
    'name'       => 'Name',
    'gender'     => 'Gender',
    'phoneNumber' => 'PhoneNumber',
];

$sort = req('sort');
key_exists($sort, $fields) || $sort = 'id';

$dir = req('dir');
in_array($dir, ['asc', 'desc']) || $dir = 'asc';

$page = req('page', 1);

require_once '../lib/SimplePager.php';
$p = new SimplePager("SELECT * FROM Customer ORDER BY $sort $dir", [], 10, $page);
$arr = $p->result;

// ----------------------------------------------------------------------------
$_title = 'Member Maintenance';
include '../header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $_title ?></title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
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
            <td><?= $s->gender ?></td>
            <td><?= $s->phoneNumber ?></td>
        </tr>
        <?php endforeach ?>
    </table>

    <br>

    <?= $p->html("sort=$sort&dir=$dir") ?>
</body>
<?php
include '../footer.php';