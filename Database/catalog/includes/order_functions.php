<?php

require_once 'cart_functions.php';

/**
 * Генерация уникального номера заказа
 */
function generateOrderNumber() {
    return 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
}

/**
 * Создание заказа
 */
function createOrder($customer_data, $db) {
    initCart();
    
    if (empty($_SESSION['cart'])) {
        return false;
    }
    
    // Получаем детали корзины
    $cart_data = getCartDetails($db);
    
    if (empty($cart_data['items'])) {
        return false;
    }
    
    // Начинаем транзакцию
    $db->beginTransaction();
    
    try {
        // Генерируем номер заказа
        $order_number = generateOrderNumber();
        
        // Вставляем заказ
        $order_query = "INSERT INTO orders (
            order_number, 
            customer_name, 
            customer_email, 
            customer_phone, 
            delivery_address, 
            comment, 
            total_amount, 
            status
        ) VALUES (
            :order_number, 
            :customer_name, 
            :customer_email, 
            :customer_phone, 
            :delivery_address, 
            :comment, 
            :total_amount, 
            'new'
        )";
        
        $order_stmt = $db->prepare($order_query);
        $order_stmt->execute([
            ':order_number' => $order_number,
            ':customer_name' => $customer_data['name'],
            ':customer_email' => $customer_data['email'],
            ':customer_phone' => $customer_data['phone'] ?? '',
            ':delivery_address' => $customer_data['address'] ?? '',
            ':comment' => $customer_data['comment'] ?? '',
            ':total_amount' => $cart_data['total_price']
        ]);
        
        $order_id = $db->lastInsertId();
        
        // Вставляем позиции заказа
        $item_query = "INSERT INTO order_items (
            order_id, 
            product_id, 
            product_name, 
            price, 
            quantity, 
            subtotal
        ) VALUES (
            :order_id, 
            :product_id, 
            :product_name, 
            :price, 
            :quantity, 
            :subtotal
        )";
        
        $item_stmt = $db->prepare($item_query);
        
        foreach ($cart_data['items'] as $item) {
            $item_stmt->execute([
                ':order_id' => $order_id,
                ':product_id' => $item['id'],
                ':product_name' => $item['name'],
                ':price' => $item['price'],
                ':quantity' => $item['cart_quantity'],
                ':subtotal' => $item['subtotal']
            ]);
        }
        
        // Подтверждаем транзакцию
        $db->commit();
        
        // Очищаем корзину
        clearCart();
        
        return [
            'order_id' => $order_id,
            'order_number' => $order_number,
            'total_amount' => $cart_data['total_price']
        ];
        
    } catch (Exception $e) {
        // Откатываем транзакцию в случае ошибки
        $db->rollBack();
        error_log("Order creation error: " . $e->getMessage());
        return false;
    }
}

/**
 * Получение заказа по ID
 */
function getOrderById($order_id, $db) {
    $query = "SELECT * FROM orders WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->execute([':id' => $order_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Получение заказа по номеру
 */
function getOrderByNumber($order_number, $db) {
    $query = "SELECT * FROM orders WHERE order_number = :order_number";
    $stmt = $db->prepare($query);
    $stmt->execute([':order_number' => $order_number]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Получение позиций заказа
 */
function getOrderItems($order_id, $db) {
    $query = "SELECT * FROM order_items WHERE order_id = :order_id";
    $stmt = $db->prepare($query);
    $stmt->execute([':order_id' => $order_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Обновление статуса заказа
 */
function updateOrderStatus($order_id, $status, $db) {
    $query = "UPDATE orders SET status = :status WHERE id = :id";
    $stmt = $db->prepare($query);
    return $stmt->execute([
        ':status' => $status,
        ':id' => $order_id
    ]);
}

/**
 * Получение всех заказов (для админки)
 */
function getAllOrders($db, $limit = 50, $offset = 0) {
    $query = "SELECT * FROM orders ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Получение статистики заказов
 */
function getOrdersStats($db) {
    $stats = [];
    
    // Общее количество заказов
    $query = "SELECT COUNT(*) as total FROM orders";
    $stats['total'] = $db->query($query)->fetch(PDO::FETCH_ASSOC)['total'];
    
    // По статусам
    $query = "SELECT status, COUNT(*) as count FROM orders GROUP BY status";
    $result = $db->query($query);
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $stats['by_status'][$row['status']] = $row['count'];
    }
    
    // Общая сумма всех заказов
    $query = "SELECT SUM(total_amount) as total_sum FROM orders";
    $stats['total_sum'] = $db->query($query)->fetch(PDO::FETCH_ASSOC)['total_sum'] ?? 0;
    
    return $stats;
}
?>