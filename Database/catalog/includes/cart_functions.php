<?php

// Инициализация сессии для корзины
function initCart() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
}

// Добавление товара в корзину
function addToCart($product_id, $quantity = 1) {
    initCart();
    
    // Проверяем, есть ли уже такой товар в корзине
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] += $quantity;
            $found = true;
            break;
        }
    }
    
    if (!$found) {
        $_SESSION['cart'][] = [
            'id' => $product_id,
            'quantity' => $quantity
        ];
    }
    
    return true;
}

// Удаление товара из корзины
function removeFromCart($product_id) {
    initCart();
    
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            $_SESSION['cart'] = array_values($_SESSION['cart']); // Переиндексация
            return true;
        }
    }
    
    return false;
}

// Очистка корзины
function clearCart() {
    initCart();
    $_SESSION['cart'] = [];
}

// Получение содержимого корзины с деталями товаров из БД
function getCartDetails($db) {
    initCart();
    
    if (empty($_SESSION['cart'])) {
        return [];
    }
    
    $cart_items = [];
    $total_price = 0;
    $total_items = 0;
    
    foreach ($_SESSION['cart'] as $cart_item) {
        $query = "SELECT * FROM products WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->execute([':id' => $cart_item['id']]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($product) {
            $product['cart_quantity'] = $cart_item['quantity'];
            $product['subtotal'] = $product['price'] * $cart_item['quantity'];
            $cart_items[] = $product;
            
            $total_price += $product['subtotal'];
            $total_items += $cart_item['quantity'];
        }
    }
    
    return [
        'items' => $cart_items,
        'total_price' => $total_price,
        'total_items' => $total_items
    ];
}

// Получение количества товаров в корзине
function getCartCount() {
    initCart();
    $count = 0;
    foreach ($_SESSION['cart'] as $item) {
        $count += $item['quantity'];
    }
    return $count;
}
?>