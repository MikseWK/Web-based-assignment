<?php
require '../base.php';
include '../header.php';
?>
<link rel="stylesheet" href="../css/style.css">
<!-- Your switchrole content here -->
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <a href="adminlogin.php" class="btn btn-primary btn-lg btn-block">Go to Admin Page</a>
        </div>
        <div class="col-md-6">
            <a href="customerlogin.php" class="btn btn-success btn-lg btn-block">Go to Customer Page</a>
        </div>
    </div>
</div>

<?php
include '../footer.php';
?>