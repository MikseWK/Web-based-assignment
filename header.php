<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $_title ?? 'Frosty Delights' ?></title>
    <?php
    // Determine if we're in a subdirectory
    $base_path = dirname($_SERVER['PHP_SELF']) == '/modules' ? '../' : '';
    ?>
    <link rel="shortcut icon" href="<?= $base_path ?>Images/favicon.ico">
    <link rel="stylesheet" href="<?= $base_path ?>css/css.css">
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="<?= $base_path ?>js/app.js"></script> -->
</head>
<body>

<header>
      <nav>
        <a href="<?= $base_path ?>index.php"><img src="<?= $base_path ?>Images/logo.png" id="logo"></a>
        <a href="<?= $base_path ?>index.php">Home</a>
        <a href="<?= $base_path ?>modules/product.php">Product</a>
        <a href="<?= $base_path ?>modules/location.php">Where's us</a>
        <a href="<?= $base_path ?>modules/aboutus.php">About us</a>
        <a href="<?= $base_path ?>modules/switchrole.php" class="loginIcon"><img src="<?= $base_path ?>Images/loginIcon.png"></a>
     </nav>
    </header>

    <main>
        <h1><?= $_title ?? 'Frosty Delights' ?></h1>