<?php
include '../_base.php';

auth('Admin');
// ----------------------------------------------------------------------------

if (is_get()) {
    $id = req('id');

    $stm = $_db->prepare('SELECT * FROM product WHERE id = ?');
    $stm->execute([$id]);
    $p = $stm->fetch();

    if (!$p) {
        redirect('index.php');
    }

    extract((array)$p);
    // TODO
    $_SESSION['photo'] = $p->photo;
}

if (is_post()) { // part2: update
    $id    = req('id');
    $name  = req('name');
    $price = req('price');
    $f     = get_file('photo'); //get current chosen file
    $photo = $_SESSION['photo']; // TODO read session photo

    // Validate: name
    if ($name == '') {
        $_err['name'] = 'Required';
    }
    else if (strlen($name) > 100) {
        $_err['name'] = 'Maximum 100 characters';
    }

    // Validate: price
    if ($price == '') {
        $_err['price'] = 'Required';
    }
    else if (!is_money($price)) {
        $_err['price'] = 'Must be money';
    }
    else if ($price < 0.01 || $price > 99.99) {
        $_err['price'] = 'Must between 0.01 - 99.99';
    }

    // Validate: photo (file)
    // ** Only if a file is selected **
    if ($f) {
        if (!str_starts_with($f->type, 'image/')) {
            $_err['photo'] = 'Must be image';
        }
        else if ($f->size > 1 * 1024 * 1024) {
            $_err['photo'] = 'Maximum 1MB';
        }
    }

    // DB operation
    // if (!$_err) {
    //     // Delete photo + save photo
    //     // ** Only if a file is selected **
    //     if ($f) {
    //         unlink("../photos/$photo"); //unlink and delete the old photo
    //         $photo = save_photo($f, '../photos');   //save the new photo
    //     }
        
    //     $stm = $_db->prepare('
    //         UPDATE product
    //         SET name = ?, price = ?, photo = ?
    //         WHERE id = ?
    //     ');
    //     $stm->execute([$name, $price, $photo, $id]);

    //     $_SESSION['message'] = 'Record Updated';
    //     redirect('index.php');
    // }
}

// ----------------------------------------------------------------------------

$_title = 'Product | Update';
include '../_head.php';
?>

<p>
    <button data-get="index.php">Index</button>
</p>

<form method="post" class="form" enctype="multipart/form-data" novalidate>
    <label for="id">Id</label>
    <b><?= $id ?></b>
    <br>

    <label for="name">Name</label>
    <?= html_text('name', 'maxlength="100"') ?>
    <?= $_err('name') ?>

    <label for="price">Price</label>
    <?= html_number('price', 0.01, 99.99, 0.01) ?>
    <?= $_err('price') ?>

    <label for="photo">Photo</label>
    <label class="upload" tabindex="0">
        <?= html_file('photo', 'image/*', 'hidden') ?>
        <img src="/photos/<?= $photo ?>">
    </label>
    <?= $_err('photo') ?>

    <section>
        <button>Submit</button>
        <button type="reset">Reset</button>
    </section>
</form>

<?php
include '../_foot.php';