<?php
require '../base.php';

auth('Admin');
if (is_post()) {
    $action = $_POST['action'] ?? '';
    $ids = explode(',', $_POST['ids'] ?? '');

    switch ($action) {
        case 'activate':
            $_SESSION['message'] = 'NIgga2';            
            foreach ($ids as $id) {
                $_SESSION['message'] = 
                $stm = $_db->prepare('SELECT * FROM product WHERE id = ?');
                $stm->execute([$id]);
                $product = $stm->fetch(PDO::FETCH_ASSOC);
            
                $quantity = $product['quantity'];

                if($quantity != 0) {
                    $stmt = $_db->prepare('UPDATE product SET availability = 1 WHERE id = ?');
                    $stmt->execute([$id]);
                }
            }
            $_SESSION['message'] = 'Selected products activated successfully.';
            redirect('productMain.php');
            break;

        case 'disable':
            $stmt = $_db->prepare("UPDATE product SET availability = 0 WHERE id IN (" . implode(',', array_fill(0, count($ids), '?')) . ")");
            $stmt->execute($ids);
            redirect('productMain.php', 'Selected products disabled successfully.');
            break;

        case 'update-add':
            $quantity = $_POST['value'] ?? '0';
            if (!is_numeric($quantity)) {
                redirect('productMain.php', 'Invalid quantity value.');
            }
            $stmt = $_db->prepare("UPDATE product SET quantity = ? WHERE id IN (" . implode(',', array_fill(0, count($ids), '?')) . ")");
            $stmt->execute(array_merge([$quantity], $ids));
            redirect('productMain.php', 'Quantities updated successfully.');
            break;

        default:
            $_SESSION['message'] = 'Invalid action.';
            redirect('productMain.php');
    }
}

redirect('productMain.php');