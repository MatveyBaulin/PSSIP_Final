<?php
require_once 'includes/cart_functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    
    if ($quantity <= 0) {
        removeFromCart($product_id);
    } else {
        initCart();
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $product_id) {
                $item['quantity'] = $quantity;
                break;
            }
        }
    }
}

header('Location: cart.php');
exit;
?>