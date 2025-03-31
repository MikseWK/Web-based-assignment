<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $_title ?? 'Frosty Delights' ?></title>
    <link rel="shortcut icon" href="/Images/favicon.ico">
    <link rel="stylesheet" href="/css/css.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="/js/app.js"></script>
</head>
<body>

    <header>
      <nav>
        <a href="/"><img src="/Images/logo.png" id="logo"></a>
        <a href="/">Home</a>
        <a href="/module/product.php">Product</a>
        <a href="/module/location.php">Where's us</a>
        <a href="/module/aboutus.php">About us</a>
        <a href="/module/switchRole.php" class="loginIcon"><img src="/Images/loginIcon.png" ></a>
     </nav>
    </header>



    <main>
        <h1><?= $_title ?? 'Frosty Delights' ?></h1>