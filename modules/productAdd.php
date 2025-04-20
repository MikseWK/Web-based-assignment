<?php
require '../base.php';
// ----------------------------------------------------------------------------

if (is_post()) {
    // Input
    $id         = req('id');
    $name       = req('name');
    $gender     = req('gender');
    $program_id = req('program_id');

    // Validate id
    if ($id == '') {
        $_err['id'] = 'Required';
    }
    else if (!preg_match('/^[P]{1}\d{3}$/', $id)) {
        $_err['id'] = 'Invalid format';
    }
    else {
        $stm = $_db->prepare('SELECT COUNT(*) FROM Product WHERE id = ?');
        $stm->execute([$id]);

        if ($stm->fetchColumn() > 0) {
            $_err['id'] = 'Duplicated';
        }
    }
    
    // Validate name
    if ($name == '') {
        $_err['name'] = 'Required';
    }
    else if (strlen($name) > 100) {
        $_err['name'] = 'Maximum length 100';
    }

    // Validate category
    if ($gender == '') {
        $_err['category'] = 'Required';
    }
    else if (!array_key_exists($gender, $_genders)) {
        $_err['name'] = 'Invalid value';
    }

    // Validate program_id
    if ($program_id == '') {
        $_err['program_id'] = 'Required';
    }
    else if (!array_key_exists($program_id, $_programs)) {
        $_err['program_id'] = 'Invalid value';
    }

    // Output
    if (!$_err) {
        $stm = $_db->prepare('INSERT INTO product
                                (id, name, 
                            ');
        $_SESSION['message'] = 'Record Updated';
        redirect('/modules/productMain.php');
    }
}

// ----------------------------------------------------------------------------
$_title = 'Add Product';
include '../header.php';
?>

<form method="post" class="form">
    <label for="id">Id</label>
    <?= html_text('id', 'maxlength="10" data-upper') ?>
    <?= $_err['id'] ?>

    <label for="name">Name</label>
    <?= html_text('name', 'maxlength="100"') ?>
    <?= $_err['name'] ?>

    <label>Category</label>
    <?= html_text('category', 'maxlength="100"') ?>
    <?= $_err['gender'] ?>

    <label>Price</label>
    <?= html_number('price', 0.01, 99.99, 0.01) ?>
    <?= $_err['program_id'] ?>

    <section>
        <button>Submit</button>
        <button type="reset">Reset</button>
    </section>
</form>

<?php
include '../footer.php';