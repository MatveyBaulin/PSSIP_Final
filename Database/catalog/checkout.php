<?php
require_once 'config/database.php';
require_once 'includes/cart_functions.php';
require_once 'includes/order_functions.php';

session_start();
$database = new Database();
$db = $database->getConnection();

$cart_data = getCartDetails($db);

// Если корзина пуста, перенаправляем в каталог
if (empty($cart_data['items'])) {
    header('Location: index.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Валидация данных
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $comment = trim($_POST['comment'] ?? '');
    
    $errors = [];
    
    if (empty($name)) {
        $errors[] = 'Укажите ваше имя';
    }
    
    if (empty($email)) {
        $errors[] = 'Укажите email';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Укажите корректный email';
    }
    
    if (empty($phone)) {
        $errors[] = 'Укажите телефон';
    }
    
    if (empty($address)) {
        $errors[] = 'Укажите адрес доставки';
    }
    
    if (empty($errors)) {
        // Создаем заказ
        $order_data = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'address' => $address,
            'comment' => $comment
        ];
        
        $result = createOrder($order_data, $db);
        
        if ($result) {
            // Сохраняем номер заказа в сессию для страницы успеха
            $_SESSION['last_order'] = $result;
            header('Location: order_success.php');
            exit;
        } else {
            $error = 'Произошла ошибка при оформлении заказа. Пожалуйста, попробуйте позже.';
        }
    } else {
        $error = implode('<br>', $errors);
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Оформление заказа</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>📝 Оформление заказа</h1>
        
        <div class="checkout-header">
            <a href="cart.php" class="back-link">← Вернуться в корзину</a>
        </div>
        
        <?php if ($error): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <div class="checkout-grid">
            <!-- Форма заказа -->
            <div class="checkout-form">
                <h2>Контактная информация</h2>
                
                <form method="POST" action="checkout.php" id="checkout-form">
                    <div class="form-group">
                        <label for="name">ФИО *</label>
                        <input type="text" id="name" name="name" required 
                               value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" required 
                               value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Телефон *</label>
                        <input type="tel" id="phone" name="phone" required 
                               placeholder="+7 (999) 123-45-67"
                               value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="address">Адрес доставки *</label>
                        <textarea id="address" name="address" rows="3" required><?php 
                            echo htmlspecialchars($_POST['address'] ?? ''); 
                        ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="comment">Комментарий к заказу</label>
                        <textarea id="comment" name="comment" rows="3"><?php 
                            echo htmlspecialchars($_POST['comment'] ?? ''); 
                        ?></textarea>
                    </div>
                    
                    <button type="submit" class="submit-btn">Подтвердить заказ</button>
                </form>
            </div>
            
            <!-- Состав заказа -->
            <div class="order-summary">
                <h2>Ваш заказ</h2>
                
                <div class="order-items">
                    <?php foreach ($cart_data['items'] as $item): ?>
                    <div class="order-item">
                        <div class="item-info">
                            <span class="item-name"><?php echo htmlspecialchars($item['name']); ?></span>
                            <span class="item-quantity">x<?php echo $item['cart_quantity']; ?></span>
                        </div>
                        <div class="item-price">
                            <?php echo number_format($item['subtotal'], 0, '.', ' '); ?> ₽
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="order-total">
                    <div class="total-row">
                        <span>Товаров:</span>
                        <span><?php echo $cart_data['total_items']; ?> шт.</span>
                    </div>
                    <div class="total-row grand-total">
                        <span>Итого к оплате:</span>
                        <span><?php echo number_format($cart_data['total_price'], 0, '.', ' '); ?> ₽</span>
                    </div>
                </div>
                
                <div class="order-note">
                    <p>Нажимая "Подтвердить заказ", вы соглашаетесь с условиями обработки персональных данных.</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>