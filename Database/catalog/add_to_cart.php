<?php
require_once 'includes/cart_functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'])) {
    $product_id = (int)$_POST['product_id'];
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
    
    addToCart($product_id, $quantity);
}

// Перенаправление обратно на страницу каталога
header('Location: index.php');
exit;
?>