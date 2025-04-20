<?php
require '../base.php';

if (is_post()) {
    $id = req('id');

    $stm = $_db->prepare('DELETE FROM Product WHERE id = ?');
    $stm->execute([$id]);

    $_SESSION['message'] = 'Record Deleted';
}

redirect('/');