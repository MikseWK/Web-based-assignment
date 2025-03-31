<?php
require '../base.php';

$_title = '';
include '../header.php';
?>
<div class="container">
    <form method="get" class="switchRole">
        <button type="submit" formaction="/admin/adminLogin.php" class="adminButton">Go to Admin Page</button>
        <button type="submit" formaction="/module/memberLogin.php" class="memberButton">Go to Member Page</button>
    </form>
</div>

<?php
include '../footer.php';