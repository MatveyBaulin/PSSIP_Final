<?php
require_once 'includes/cart_functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'])) {
    $product_id = (int)$_POST['product_id'];
    removeFromCart($product_id);
}

header('Location: cart.php');
exit;
?>