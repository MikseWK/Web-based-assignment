<?php
require '../base.php';

if (is_post()) {
    $id = req('id');

    $stm = $_db->prepare('SELECT * FROM product WHERE id = ?');
    $stm->execute([$id]);
    $product = $stm->fetch(PDO::FETCH_ASSOC);

    $quantity = $product['quantity'];
    $availability = $product['availability'];

    if ($quantity == 0) {
        $_SESSION['message'] = 'Please have enough stock to active';
        
    } else {
        if ($availability == 0) {
            $availability = 1;
        } elseif ($availability == 1) {
            $availability = 0;
        }
    
        $stm = $_db->prepare('
            UPDATE product
            SET availability = ?
            WHERE id = ?');
        $stm->execute([$availability, $id]);
    
        $_SESSION['message'] = 'Record Status Updated'; 
    }
}

redirect('/modules/productMain.php');