<?php
require '../base.php';
// ----------------------------------------------------------------------------

auth('Admin');
$_title = 'Member Maintenance';

$fields = [
    'id'         => 'Id',
    'name'       => 'Name',
    'phoneNumber' => 'PhoneNumber',
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
$p = new SimplePager("SELECT * FROM Customer $where_clause ORDER BY $sort $dir", [], 10, $page);
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

<form method="post" class="form">
    <label for="search_field">Search by:</label>
    <select name="search_field" id="search_field">
        <?php foreach ($fields as $key => $value): ?>
            <?php if ($key): ?>
                <option value="<?= $key ?>" <?= $key == $search_field ? 'selected' : '' ?>><?= $value ?></option>
            <?php endif; ?>
        <?php endforeach; ?>
    </select>
    <input type="text" name="search_value" value="<?= htmlspecialchars($search_value) ?>">
    <button type="submit">Search</button>
</form>

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
            <td><?= $s->phoneNumber ?></td>
        </tr>
        <?php endforeach ?>
    </table>

    <br>

    <?= $p->html("sort=$sort&dir=$dir") ?>
</body>
<?php
include '../footer.php';