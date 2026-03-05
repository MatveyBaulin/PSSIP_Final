<?php
session_start();

if (!isset($_SESSION['last_order'])) {
    header('Location: index.php');
    exit;
}

$order = $_SESSION['last_order'];
unset($_SESSION['last_order']); // Очищаем после отображения
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Заказ оформлен</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <div class="success-page">
            <div class="success-icon">✅</div>
            <h1>Спасибо за заказ!</h1>
            
            <div class="order-info">
                <p>Ваш заказ № <strong><?php echo $order['order_number']; ?></strong> успешно оформлен.</p>
                <p>Сумма заказа: <strong><?php echo number_format($order['total_amount'], 0, '.', ' '); ?> ₽</strong></p>
            </div>
            
            <div class="next-steps">
                <p>В ближайшее время наш менеджер свяжется с вами для подтверждения заказа.</p>
                <p>Все детали заказа отправлены на вашу электронную почту.</p>
            </div>
            
            <div class="success-actions">
                <a href="index.php" class="btn">Вернуться в каталог</a>
            </div>
        </div>
    </div>
</body>
</html>