<?php
require '../base.php';

auth('Admin');
// ----------------------------------------------------------------------------

if (is_post()) {
    // Input
    $id         = req('id');
    $name       = req('name');
    $category   = req('category');
    $Flavour    = req('Flavour');
    $price      = req('price');
    $f          = get_file('photo');
    $photo      = '';
    $Description= req('Description');

    // Validate id
    if ($id == '') {
        $_err['id'] = 'Required';
    }
    else if (!preg_match('/^P\d{3}$/', $id)) {
        $_err['id'] = 'Invalid format (must be P followed by 3 digits)';
    }
    else {
        $stm = $_db->prepare('SELECT COUNT(*) FROM product WHERE id = ?');
        $stm->execute([$id]);

        if ($stm->fetchColumn() > 0) {
            $_err['id'] = 'Product ID already exists';
        }
    }
    
    // Validate name
    if ($name == '') {
        $_err['name'] = 'Required';
    }
    else if (strlen($name) > 100) {
        $_err['name'] = 'Maximum length 100 characters';
    }

    // Validate category
    if ($category == '') {
        $_err['category'] = 'Required';
    }
    else if (strlen($category) > 20) {
        $_err['category'] = 'Maximum length 20 characters';
    }

    // Validate flaour
    if ($Flavour == '') {
        $_err['Flavour'] = 'Required';
    }
    else if (strlen($Flavour) > 100) {
        $_err['Flavour'] = 'Maximum length 100 characters';
    }

    // Validate price
    if ($price == '') {
        $_err['price'] = 'Required';
    }
    else if (!is_numeric($price)) {
        $_err['price'] = 'Must be a number';
    }
    else if ($price < 0.01 || $price > 999.99) {
        $_err['price'] = 'Must be between 0.01 and 999.99';
    }

    // Validate photo
    if (!$f) {
        $_err['photo'] = 'Required';
    }
    else {
        if (!str_starts_with($f->type, 'image/')) {
            $_err['photo'] = 'Must be an image file';
        }
        else if ($f->size > 1 * 1024 * 1024) {
            $_err['photo'] = 'Maximum file size is 1MB';
        }
    }
    
    // Validate description
    if ($Description == '') {
        $_err['Description'] = 'Required';
    }
    else if (strlen($Description) > 200) {
        $_err['Description'] = 'Maximum length 200 characters';
    }

    // Output
    if (!$_err) {
        // Genrate photo name to save
        $photo = $name. '.jpg';

        require_once '../lib/SimpleImage.php';
        $img = new SimpleImage();
        $img->fromFile($f->tmp_name)
            ->resize(200, 200)
            ->toFile("../images/$photo", 'image/jpeg');
        
        // Insert the new product
        $stm = $_db->prepare('INSERT INTO product
                                (id, name, category, Flavour, price, photo, Description, quantity)
                                VALUES(?, ?, ?, ?, ?, ?, ?, 0)
                            ');
        $stm->execute([$id, $name, $category, $Flavour, $price, $photo, $Description]);
        
        $_SESSION['message'] = 'Product added successfully';
        redirect('/modules/productMain.php');
    }
}

// ----------------------------------------------------------------------------
$_title = 'Add Product';
include '../header.php';
?>

<p>
    <button data-get="/modules/productMain.php">Return to Product Maintenance</button>
</p>

<form method="post" class="form" enctype="multipart/form-data" novalidate>
    <label for="id">Product ID
        <span class="tip">
            <span class="info-icon">i</span>
            <span class="tiptext">Format: P followed by 3 digits (e.g., P001)</span>
        </span>
    </label>
    <?= html_text('id', 'maxlength="4" data-upper') ?>
    <?php if(isset($_err['id'])): ?>
        <div class="error-message"><?= $_err['id'] ?></div>
    <?php else : ?>
        <div></div>
    <?php endif; ?>

    <label for="name">Product Name
        <span></span>
    </label>
    <?= html_text('name', 'maxlength="100"') ?>
    <?php if(isset($_err['name'])): ?>
        <div class="error-message"><?= $_err['name'] ?></div>
    <?php else : ?>
        <div></div>
    <?php endif; ?>

    <label for="category">Category</label>
    <?= html_text('category', 'maxlength="100"') ?>
    <?php if(isset($_err['category'])): ?>
        <div class="error-message"><?= $_err['category'] ?></div>
    <?php else : ?>
        <div></div>
    <?php endif; ?>

    <label for="FLavour">Flavour</label>
    <?= html_text('Flavour', 'maxlength="20"') ?>
    <?php if(isset($_err['Flavour'])): ?>
        <div class="error-message"><?= $_err['Flavour'] ?></div>
    <?php else : ?>
        <div></div>
    <?php endif; ?>

    <label for="price">Price (RM)</label>
    <?= html_number('price', 0.01, 999.99, 0.01) ?>
    <?php if(isset($_err['price'])): ?>
        <div class="error-message"><?= $_err['price'] ?></div>
    <?php else : ?>
        <div></div>
    <?php endif; ?>

    <label for="photo">Product Photo
        <span class="tip">
            <span class="info-icon">i</span>
            <span class="tiptext">Maximum 1MB, must be an image file</span>
        </span>
    </label>
    <label class="upload" tabindex="0">
        <?= html_file('photo', 'image/*', 'hidden') ?>
        <img src="">
    </label>
    <?php if(isset($_err['photo'])): ?>
        <div class="error-message"><?= $_err['photo'] ?></div>
    <?php else : ?>
        <div class="error-message"></div>
    <?php endif; ?>

    <label for="Description">Description</label>
    <?= html_text('Description', 'maxlength="200"') ?>
    <?php if(isset($_err['Description'])): ?>
        <div class="error-message"><?= $_err['Description'] ?></div>
    <?php else : ?>
        <div></div>
    <?php endif; ?>

    <section>
        <button>Add Product</button>
        <button type="reset">Reset</button>
    </section>
</form>

<script src="../js/app.js"></script>
<?php
include '../footer.php';
?>
