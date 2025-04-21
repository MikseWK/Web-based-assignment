<?php
include '../base.php';

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
    $_SESSION['photo'] = $p->photo;
}

if (is_post()) { // part2: update
    $id          = req('id');
    $name        = req('name');
    $category    = req('category');
    $Flavour     = req('Flavour');
    $price       = req('price');
    $f           = get_file('photo'); //get current chosen file
    $photo       = $_SESSION['photo']; // read session photo
    $Description = req('Description');
    $quantity    = req('quantity');

    // Validate: name
    if ($name == '') {
        $_err['name'] = 'Required';
    }
    else if (strlen($name) > 100) {
        $_err['name'] = 'Maximum 100 characters';
    }

    // Validate: category
    if ($category == '') {
        $_err['category'] = 'Required';
    }
    else if (strlen($category) > 100) {
        $_err['category'] = 'Maximum 100 characters';
    }
    
    // Validate: flavour
    if ($Flavour == '') {
        $_err['Flavour'] = 'Required';
    }
    else if (strlen($Flavour) > 20) {
        $_err['Flavour'] = 'Maximum 20 characters';
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

    // Validate: description
    if ($Description == '') {
        $_err['Description'] = 'Required';
    }
    else if (strlen($Description) > 200) {
        $_err['Description'] = 'Maximum 200 characters';
    }    

    // Validate: quantity
    if ($quantity == '') {
        $_err['quantity'] = 'Required';
    }
    else if (!preg_match('/^\d{1,3}$/', $quantity)) {
        $_err['quantity'] = 'Must be Integer';
    }
    else if ($price < 0 || $price > 999) {
        $_err['quantity'] = 'Must between 0 - 999';
    }

    //DB operation
    if (!$_err) {
        // Delete photo + save photo
        // ** Only if a file is selected **
        if ($f) {
            unlink("../photos/$photo"); //unlink and delete the old photo
            $photo = save_photo($f, '../photos');   //save the new photo
        }
        
        $stm = $_db->prepare('
            UPDATE product
            SET name = ?, category = ?, FLavour = ?, price = ?, photo = ?, Description = ?, quantity = ?
            WHERE id = ?
        ');
        $stm->execute([$name, $category, $Flavour, $price, $photo, $Description, $quantity, $id]);

        $_SESSION['message'] = 'Record Updated';
        redirect('/modules/productMain.php');
    }
}

// ----------------------------------------------------------------------------

$_title = 'Product Update';
include '../header.php';
?>

<p>
    <button data-get="/modules/productMain.php">Return to Product Maintenance</button>
</p>

<form method="post" class="form" enctype="multipart/form-data" novalidate>
    <label for="id">Id</label>
    <b><?= $id ?></b>
    <br>

    <label for="name">Name</label>
    <?= html_text('name', 'maxlength="100"') ?>
    <?php if ($name == ''): ?> 
       <?= $_err['name'] = 'Required' ?>
    <?php else : ?>
        <div></div>
    <?php endif; ?>

    <label for="category">Category</label>
    <?= html_text('category', 'maxlength="100"') ?>
    <?php if ($category == ''): ?> 
       <?= $_err['category'] = 'Required' ?>
    <?php else : ?>
        <div></div>
    <?php endif; ?>

    <label for="Flavour">Flavour</label>
    <?= html_text('Flavour', 'maxlength="20"') ?>
    <?php if ($Flavour == ''): ?> 
       <?= $_err['Flavour'] = 'Required' ?>
    <?php else : ?>
        <div></div>
    <?php endif; ?>

    <label for="price">Price</label>
    <?= html_number('price', 0.01, 99.99, 0.01) ?>
    <?php if ($price == ''): ?> 
        <?= $_err['priced'] = 'Required' ?>
    <?php else : ?>
        <div></div>
    <?php endif; ?>

    <label for="photo">Photo</label>
    <label class="upload" tabindex="0">
        <?= html_file('photo', 'image/*', 'hidden') ?>
        <img src="/images/<?= $photo ?>">
    </label>
    <?php if ($photo == ''): ?> 
        <?= $_err['photo'] = 'Required' ?>
    <?php else : ?>
        <div></div>
    <?php endif; ?>

    <label for="Description">Description</label>
    <?= html_textarea('Description', 'maxlength="200"') ?>
    <?php if ($Description == ''): ?> 
       <?= $_err['Description'] = 'Required' ?>
    <?php else : ?>
        <div></div>
    <?php endif; ?>

    <label for="quantity">Quantity</label>
    <?= html_number('quantity', 0, 999, 1) ?>
    <?php if ($quantity == ''): ?> 
        <?= $_err['quantity'] = 'Required' ?>
    <?php else : ?>
        <div></div>
    <?php endif; ?> 

    <section>
        <button>Submit</button>
        <button type="reset">Reset</button>
    </section>
</form>

<script src="../js/app.js"></script>
<?php
include '../footer.php';